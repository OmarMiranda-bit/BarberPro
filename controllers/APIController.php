<?php

namespace Controllers;

use Classes\Email;
use Model\Bloqueo;
use Model\Cita;
use Model\CitaServicio;
use Model\Empleado;
use Model\Horario;
use Model\Servicio;
use Model\Usuario;
use PHPMailer\PHPMailer\PHPMailer;

class APIController
{
    public static function index()
    {
        $servicios = Servicio::all();
        // header('Content-Type: application/json');
        echo json_encode($servicios);
    }

    public static function obtenerBarberos()
    {
        // Consulta para traer barberos con sus datos de usuario
        $consulta = "SELECT empleados.id, empleados.imagen, usuarios.nombre, usuarios.apellido 
                 FROM empleados 
                 JOIN usuarios ON empleados.usuario_id = usuarios.id 
                 WHERE empleados.activo = 1";

        $barberos = Empleado::SQL($consulta);

        // Convertir a JSON para JavaScript
        echo json_encode($barberos);
        exit;
    }

    public static function obtenerHorariosDisponibles()
    {

        $barberoId = $_GET['barberoId'] ?? null;
        $duracion  = $_GET['duracion']  ?? null; // duración total en minutos desde JS
        $fecha     = $_GET['fecha']     ?? null; // "2026-05-07"
        $excluirFolio = $_GET['excluirFolio'] ?? '';

        if (!$barberoId || !$duracion || !$fecha) {
            echo json_encode(['error' => 'barberoId, duracion y fecha son requeridos']);
            exit;
        }

        $duracion   = (int) $duracion;
        $horaInicio = 10 * 60; // 10:00 = 600 min
        $horaFin    = 21 * 60; // 21:00 = 1260 min

        //Obtener fecha y hora actual en minutos
        $fechaHoy = date('Y-m-d');
        $horaActualMinutos = ((int)date('H') * 60) + (int)date('i');

        // Traer citas ya agendadas para ese barbero ese día
        $citasOcupadas = Cita::obtenerOcupadasPorBarberoYFecha($barberoId, $fecha, $excluirFolio);

        // Generar slots de 15 en 15 y verificar solapamiento (cruce de rangos entre slot y cita)
        for ($min = $horaInicio; $min + $duracion <= $horaFin; $min += 15) {

            $slotInicio = $min;
            $slotFin    = $min + $duracion;
            $disponible = true;

            //Si la cita es para HOY y la hora del slot ya transcurrió o es la hora actual
            if ($fecha === $fechaHoy && $slotInicio <= $horaActualMinutos) {
                $disponible = false;
            } else {
                foreach ($citasOcupadas as $citaOcupada) {
                    // Convertir "10:30" → minutos
                    [$h, $m]    = explode(':', $citaOcupada['hora']);
                    $citaInicio = ((int)$h * 60) + (int)$m;
                    $citaFin    = $citaInicio + (int)$citaOcupada['duracion'];
                    //evitar solapamientos entre el slot que se quiere reservar y las citas ya agendadas, si el inicio del slot es menor al fin de la cita y el fin del slot es mayor al inicio de la cita, entonces hay un solapamiento
                    if ($slotInicio < $citaFin && $slotFin > $citaInicio) {
                        $disponible = false;
                        break;
                    }
                }
            }

            $horas   = intdiv($min, 60);
            $minutos = $min % 60;

            $horarios[] = [
                'hora'       => sprintf("%02d:%02d", $horas, $minutos),
                'disponible' => $disponible
            ];
        }

        echo json_encode($horarios);
        exit;
    }

    public static function guardar()
    {
        // Iniciamos un buffer de salida para atrapar cualquier texto basura
        ob_start();
        //verificar que la session este iniciada
        isAuth();

        // validación de seguridad para evitar guardar horas pasadas del mismo día
        $fechaHoy = date('Y-m-d');
        $horaActual = date('H:i:s');
        $fechaCita = $_POST['fecha'] ?? '';
        $horaCita  = date('H:i:s', strtotime($_POST['hora'] ?? ''));

        if ($fechaCita === $fechaHoy && $horaCita <= $horaActual) {
            ob_clean();
            echo json_encode([
                'resultado' => false,
                'mensaje'   => 'No puedes agendar una cita en un horario que ya transcurrió hoy.'
            ]);
            return;
        }

        //Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        //genera el folio de la cita antes de guardala
        $cita->folio = $cita->folioCita();
        //guarda la cita primero 
        $resultado = $cita->guardar();

        // Solo si la cita se guardó correctamente en la BD, procedemos
        if ($resultado['resultado']) {
            $id = $resultado['id'];

            //Almacenar los servicios
            $idServicios = explode(",", $_POST['servicios']);
            foreach ($idServicios as $idServicio) {
                $args = [
                    'citaId' => $id,
                    'servicioId' => $idServicio
                ];
                $citaServicio = new CitaServicio($args);
                $citaServicio->guardar();
            }
        }
        $email = new Email(
            $_SESSION['nombre'] . " " . $_SESSION['apellido'],
            $_SESSION['email'],
            '',
            $cita->folio
        );
        $email->enviarDetalles();
        //Limpiamos CUALQUIER texto que se haya impreso (errores, avisos de PHPMailer, etc.)
        ob_clean();
        // Si llegamos aquí, todo salió bien
        echo json_encode([
            'resultado' => $resultado['resultado'],
            'folio' => $cita->folio
        ]);
        return; // Salimos de la función para evitar enviar otra respuesta
    }
    //Funcion para que el cliente actualice su cita desde el formulario de editar cita, esta funcion es llamada desde el archivo views/reservar/formulario.php
    public static function actualizar()
    {
        // Verificar que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        // Tomar los datos del formulario
        $id         = $_POST['id']         ?? null;
        $empleadoId = $_POST['empleadoId'] ?? null;
        $fecha      = $_POST['fecha']      ?? null;
        $hora       = $_POST['hora']       ?? null;
        $servicios  = $_POST['servicios']  ?? null;

        // Validar que llegaron todos los datos
        if (!$id || !$empleadoId || !$fecha || !$hora || !$servicios) {
            // Si falta algún dato, redirigir de vuelta al formulario
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
//Validar si la hora ya transcurrió en caso de ser hoy
        $fechaHoy = date('Y-m-d');
        $horaActual = date('H:i:s');
        $horaFormateada = date('H:i:s', strtotime($hora));

        if ($fecha === $fechaHoy && $horaFormateada <= $horaActual) {
            echo json_encode([
                'resultado' => false,
                'mensaje'   => 'No puedes agendar en un horario que ya transcurrió hoy'
            ]);
            exit;
        }
        // Buscar la cita existente por el id
        $cita = Cita::find($id);
        if (!$cita) {
            header('Location: /reservar');
            exit;
        }

        // Actualizar los campos con los nuevos datos 
        $cita->empleadoId = $empleadoId;
        $cita->fecha = $fecha;
        $cita->hora = $hora;
        $cita->guardar();

        // Borrar los servicios previamente guardadis de la tabla de la bd citasservicios
        //    porque pueden haber cambiado
        CitaServicio::eliminarPorCitaId($id);

        // Insertar los servicios nuevos
        $idServicios = explode(",", $servicios); // explode para separar los IDs de los servicios en un array
        // Guardar cada servicio en la tabla citasservicios asociabdolo al id de la cita
        foreach ($idServicios as $idServicio) {
            $args = [
                'citaId'     => $id,
                'servicioId' => trim($idServicio) // trim para eliminar espacios en blanco
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        // Enviar respuesta JSON indicando éxito
        echo json_encode([
            'resultado' => true
        ]);
        exit;
    }

    //Metodo que actualiza el estado de la cita a "NO_ASISTIO" cuando el cliente no se presenta a su cita, este metodo es llamado desde el boton "Marcar como No Asistió" en la vista admin/employees/index.php
    public static function noAsistio()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id   = $_POST['id'] ?? null;
            if (!$id) return;

            $cita = Cita::find($id);
            if (!$cita) return;

            $cita->estado = 'NO_ASISTIO'; // Actualizamos el estado de la cita a "NO_ASISTIO"
            $cita->guardar();

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    //Para marcar la cita como "CANCELADO" desde el boton "Cancelar Cita" en la vista admin/employees/index.php, este metodo es llamado desde el archivo src/js/cita.js
    public static function cancelar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtenemos el ID enviado por el fetch
            $id = $_POST['id'] ?? null;

            if ($id) {
                // Buscamos la cita usando el ActiveRecord
                $cita = Cita::find($id);

                if ($cita) {
                    // Cambiamos el estado de la propiedad a CANCELADO
                    $cita->estado = 'CANCELADA';

                    // El método guardar() detecta automáticamente que ya existe un ID y ejecuta un UPDATE en lugar de un INSERT
                    $resultado = $cita->guardar();

                    // Respondemos a JavaScript con la confirmación del éxito
                    echo json_encode([
                        'resultado' => $resultado
                    ]);
                    exit;
                }
            }

            // Si algo falla, respondemos false
            echo json_encode(['resultado' => false]);
            exit;
        }
    }

    //    este metodo es llamado desde el archivo buscador.js para actualizar el estado de la cita a "EN_PROCESO" o "FINALIZADO" desde los botones "Iniciar Servicio" y "Finalizar Servicio"
    public static function actualizarEstado()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verificamos que sea una peticion POST
            // Obtenemos el ID y el nuevo estado enviados por el Fetch
            $id = $_POST['id'] ?? null;
            $estado = $_POST['estado'] ?? null;

            if ($id && $estado) {
                // Buscamos la cita 
                $cita = Cita::find($id);

                if ($cita) {
                    // Actualizamos la propiedad del estado dinámicamente
                    $cita->estado = $estado;

                    // Guardamos los cambios en la base de datos
                    $resultado = $cita->guardar();

                    // Respondemos a JavaScript con el formato exacto que espera: resultado true/false[cite: 10]
                    echo json_encode([
                        'resultado' => $resultado['resultado'] ?? $resultado
                    ]);
                    exit;
                }
            }

            // Si algo falla o no se encuentran los datos, respondemos false[cite: 10]
            echo json_encode(['resultado' => false]);
            exit;
        }
    }

    // para modificar la disponibilidad de los horarios de los empleados (barberos) desde el panel de administración, este metodo es llamado desde el archivo src/js/disponibilidad.js

    // Guardar o Actualizar el Horario Semanal Habitual
    public static function guardarHorarios()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleadoId = $_POST['empleado_id'] ?? null;
            $horariosInput = $_POST['horarios'] ?? [];

            if (!$empleadoId) {
                echo json_encode(['tipo' => 'error', 'mensaje' => 'Empleado no válido']);
                return;
            }

            // Recorremos los 7 días de la semana (0 a 6)
            foreach (range(0, 6) as $dia) {
                $datosDia = $horariosInput[$dia] ?? null;

                // Buscamos si ya existe el registro de este día para el empleado
                $horarioExistente = Horario::whereArray([
                    'empleado_id' => $empleadoId,
                    'dia_semana' => $dia
                ]);

                $horario = $horarioExistente[0] ?? new Horario();
                // revisar bien los horarios 
                $horario->empleado_id = $empleadoId;
                $horario->dia_semana   = $dia;
                $horario->activo      = isset($datosDia['activo']) ? 1 : 0;
                $horario->hora_inicio = $datosDia['hora_inicio'] ?? '10:00:00';
                $horario->hora_fin    = $datosDia['hora_fin'] ?? '20:00:00';

                $horario->guardar();
            }

            echo json_encode([
                'tipo' => 'exito',
                'mensaje' => 'Horario semanal actualizado correctamente'
            ]);
        }
    }


    // para bloquear un Día Específico por Imprevisto

    public static function bloquearDia()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $empleadoId = $_POST['empleado_id'] ?? null;
            $fecha = $_POST['fecha_bloqueo'] ?? null;
            $confirmado = $_POST['confirmado'] ?? false;

            if (!$empleadoId || !$fecha) {
                echo json_encode(['tipo' => 'error', 'mensaje' => 'Faltan datos requeridos']);
                return;
            }

            // 1. Revisar si ya existe un bloqueo previo en esa fecha
            $bloqueoExistente = Bloqueo::whereArray([
                'empleado_id' => $empleadoId,
                'fecha' => $fecha
            ]);

            if (!empty($bloqueoExistente)) {
                echo json_encode(['tipo' => 'error', 'mensaje' => 'Este día ya se encuentra bloqueado']);
                return;
            }

            // 2. Buscar si hay citas pendientes de clientes
            $citasPendientes = Cita::SQL("SELECT * FROM citas WHERE empleadoId = {$empleadoId} AND fecha = '{$fecha}' AND estado = 'PENDIENTE'");

            if (count($citasPendientes) > 0 && !$confirmado) {
                echo json_encode([
                    'tipo' => 'conflicto',
                    'total_citas' => count($citasPendientes),
                    'mensaje' => 'Tienes citas agendadas en esta fecha.'
                ]);
                return;
            }

            // 3. Si se confirmó, cancelar únicamente las citas pendientes existentes
            if (count($citasPendientes) > 0 && $confirmado) {
                foreach ($citasPendientes as $cita) {
                    $cita->estado = 'CANCELADA_POR_BARBERO';
                    $cita->guardar();

                    $cliente = Usuario::find($cita->usuarioId);
                    if ($cliente && $cliente->email) {
                        self::enviarCorreoCancelacion($cliente->email, $cliente->nombre, $cita->fecha, $cita->hora);
                    }
                }
            }

            // 4. Registrar la fecha bloqueada en la nueva tabla dedicada
            $bloqueo = new Bloqueo();
            $bloqueo->empleado_id = $empleadoId;
            $bloqueo->fecha = $fecha;
            $bloqueo->guardar();

            echo json_encode([
                'tipo' => 'exito',
                'mensaje' => 'El día ha sido bloqueado correctamente' . (count($citasPendientes) > 0 ? ' y se notificó a los clientes.' : '.')
            ]);
        }
    }

    // Auxiliar para enviar correo electrónico
    private static function enviarCorreoCancelacion($email, $nombre, $fecha, $hora)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'] ?? 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'] ?? 2525;
        $mail->Username = $_ENV['EMAIL_USER'] ?? '';
        $mail->Password = $_ENV['EMAIL_PASS'] ?? '';
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('no-reply@barberpro.com', 'BarberPro');
        $mail->addAddress($email, $nombre);
        $mail->Subject = 'Aviso Importante: Cancelación de Cita';

        $contenido = '<html>';
        $contenido .= '<p>Hola <strong>' . $nombre . '</strong>,</p>';
        $contenido .= '<p>Te informamos que tu cita programada para el <strong>' . $fecha . '</strong> a las <strong>' . $hora . '</strong> ha sido cancelada por imprevisto del barbero.</p>';
        $contenido .= '<p>Te invitamos a ingresar a nuestra plataforma para reagendar tu cita en otro horario disponible.</p>';
        $contenido .= '<p>Lamentamos los inconvenientes.</p>';
        $contenido .= '</html>';

        $mail->Body = $contenido;
        $mail->send();
    }
}
