<?php

namespace Controllers;

use Model\Cita;
// use Model\Usuario;
use MVC\Router;

class ReservarController
{

    public static function index(Router $router)
    {
        $alertas = [];

        // session_start();
        $router->render('reservar/index', [
            'alertas' => $alertas,
            'titulo' => 'Reservar Cita o Verificar Cita'
        ]);
    }
    public static function cita(Router $router)
    {
        //verificar que la sesion este iniciada
        if (!isAuth()) {
            header('Location: /reservar');
            exit;
        }
        // Consulta para traer solo los barberos (usuarios con rol = 2)


        // $barberos = Usuario::SQL($consulta);

        $router->render('reservar/cita', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id'],
            // 'barberos' => $barberos,
            'titulo' => 'Crear Nueva Cita'
        ]);
    }
    // Metodo para buscar la cita
    public static function detalle(Router $router)
    {
        $alertas = [];

        if (!isset($_SESSION)) {
            session_start();
        }

        // 1. Validar que el usuario haya iniciado sesión (ya sea por login normal o por el modal de verificación)
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            header('Location: /reservar');
            exit;
        }

        // 2. Capturamos únicamente el ID seguro desde la URL (?id=...)
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        $usuarioId = $_SESSION['id']; // Obtenemos el ID del usuario logueado actualmente

        if (!$id) {
            header('Location: /reservar');
            exit;
        }

        // 3. Consulta blindada: Busca la cita por su ID y valida estrictamente que pertenezca al usuario de la sesión
        $consulta  = "SELECT ";
        $consulta .= "  citas.id, citas.fecha, citas.hora, citas.folio, citas.estado, ";
        $consulta .= "  CONCAT(usuarios.nombre, ' ', usuarios.apellido) AS cliente, ";
        $consulta .= "  usuarios.email, ";
        $consulta .= "  GROUP_CONCAT(CONCAT(servicios.nombre, '|', servicios.precio) SEPARATOR '||') AS servicios, ";
        $consulta .= "  SUM(servicios.precio) AS precioTotal, ";
        $consulta .= "  CONCAT(u_barbero.nombre, ' ', u_barbero.apellido) AS barberoNombre, ";
        $consulta .= "  empleados.imagen AS barberoImagen ";
        $consulta .= "FROM citas ";
        $consulta .= "LEFT JOIN usuarios ON usuarios.id = citas.usuarioId ";
        $consulta .= "LEFT JOIN citasservicios ON citasservicios.citaId = citas.id ";
        $consulta .= "LEFT JOIN servicios ON servicios.id = citasservicios.servicioId ";
        $consulta .= "LEFT JOIN empleados ON empleados.id = citas.empleadoId ";
        $consulta .= "LEFT JOIN usuarios u_barbero ON u_barbero.id = empleados.usuario_id ";
        $consulta .= "WHERE citas.id = {$id} AND citas.usuarioId = {$usuarioId} ";
        $consulta .= "GROUP BY citas.id LIMIT 1 ";

        $resultado = Cita::SQL($consulta);
        $cita = array_shift($resultado);

        // Si la cita no existe o intentan adivinar el ID de otra persona, los sacamos
        if (!$cita) {
            header('Location: /reservar');
            exit;
        }

        $router->render('reservar/detalle', [
            'titulo' => 'Detalles de la Cita',
            'cita' => $cita,
            'alertas' => $alertas
        ]);
    }
    
    public static function editar(Router $router)
    {
        // Asegurar que la sesión de PHP esté iniciada
        if (!isset($_SESSION)) {
            session_start();
        }
        //Seguridad: Forzar que esté logueado
        if (!isAuth()) {
            header('Location: /reservar'); // O adonde tengas tu login
            exit;
        }
        // debuguear([
        //     'isAuth'  => isAuth(),
        //     'session' => $_SESSION
        // ]);

        $citaId = s($_GET['id'] ?? '');
        if (!$citaId) {
            $citaId = $_SESSION['cita_actual_id'] ?? null;
        }

        $usuarioId = $_SESSION['id'] ?? null;

        if (!$citaId || !$usuarioId) {
            header('Location: /reservar');
            exit;
        }

        $citaId = (int) $citaId;

        // Buscar la cita con todos los datos necesarios para pre-poblar
        $consulta  = "SELECT ";
        $consulta .= "  citas.id, citas.fecha, citas.hora, citas.folio, citas.empleadoId, ";
        $consulta .= "  GROUP_CONCAT(cs.servicioId) AS serviciosIds, ";
        $consulta .= "  SUM(s.duracion) AS duracionTotal ";
        $consulta .= "FROM citas ";
        $consulta .= "LEFT JOIN citasservicios cs ON cs.citaId  = citas.id ";
        $consulta .= "LEFT JOIN servicios s ON s.id       = cs.servicioId ";
        $consulta .= "WHERE citas.id = '{$citaId}' ";
        $consulta .= "AND citas.usuarioId  = '{$usuarioId}' ";
        $consulta .= "GROUP BY citas.id LIMIT 1 ";

        $resultado = Cita::SQL($consulta);
        $cita = array_shift($resultado);

        if (!$cita) {
            header('Location: /reservar');
            exit;
        }
        $alertas = [];

        $router->render('reservar/editar', [
            'titulo' => 'Modificar Cita',
            'cita' => $cita,
            'alertas' => $alertas
            // 'duracionTotal' => $duracionTotal
        ]);
    }
}
