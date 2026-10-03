<?php

namespace Controllers;

use Model\AdminCita;
use Model\Empleado;
use Model\Horario;
use MVC\Router;

class AdminController
{
    public static function index(Router $router)
    {
        // session_start();
        if (!isAdmin()) {
            header('Location: /login');
            exit;
        }

        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-', $fecha);
        if (!checkdate($fechas[1], $fechas[2], $fechas[0])) {
            header('Location: /404');
        }

        // El subquery obtiene empleados.id del usuario logueado
        $usuarioId = $_SESSION['id'];


        // trae TODAS las citas del día del barbero logueado, incluyendo los servicios asociados a cada cita 
        $consulta  = "SELECT citas.id, citas.hora, citas.estado, ";
        $consulta .= "citas.usuarioId as clienteId, ";
        $consulta .= "CONCAT(usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= "usuarios.email, usuarios.telefono, ";
        $consulta .= "servicios.nombre as servicio, servicios.precio, servicios.duracion ";
        $consulta .= "FROM citas ";
        $consulta .= "LEFT JOIN usuarios ON usuarios.id = citas.usuarioId ";
        $consulta .= "LEFT JOIN citasServicios ON citasServicios.citaId = citas.id ";
        $consulta .= "LEFT JOIN servicios ON servicios.id = citasServicios.servicioId ";
        $consulta .= "WHERE citas.fecha = '{$fecha}' ";
        $consulta .= "AND citas.empleadoId = ( ";
        $consulta .= "  SELECT id FROM empleados WHERE usuario_id = '{$usuarioId}' LIMIT 1 ";
        $consulta .= ") ";
        $consulta .= "ORDER BY citas.hora ASC ";

        $todasLasCitas = AdminCita::SQL($consulta);

        // array_filter() recorre $todasLasCitas y conserva solo los elementos
        // cuyo estado (sin espacios extra) sea PENDIENTE o EN_PROCESO
        // array_values() reinicia los índices para que el foreach no falle

        $citas = array_values(array_filter(
            $todasLasCitas,
            fn($c) => in_array(trim($c->estado), ['PENDIENTE', 'EN_PROCESO'])
        ));

        // Mismo proceso pero conservando solo las marcadas como NO_ASISTIO
        // Estas se mostrarán aparte en la sección de inasistencias
        $inasistencias = array_values(array_filter(
            $todasLasCitas,
            fn($c) => trim($c->estado) === 'NO_ASISTIO'
        ));

        // Contamos únicamente los IDs únicos de las citas filtradas
        $totalCitasHoy = count(array_unique(array_column($citas, 'id')));

        $router->render('admin/employees/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha,
            'inasistencias' => $inasistencias,
            'titulo' => 'Panel de Administración',
            'totalCitasHoy' => $totalCitasHoy
        ]);
    }

    public static function disponibilidad(Router $router)
    {
        if (!isAdmin()) {
            header('Location: /login');
            exit;
        }

        // Obtener el ID del empleado a partir del usuario logueado en sesión
        $usuarioId = $_SESSION['id'] ?? null;
        $empleado = Empleado::where('usuario_id', $usuarioId);

        if (!$empleado) {
            header('Location: /login');
            exit;
        }

        // Buscar los horarios configurados para este barbero
        $horariosDB = Horario::SQL("SELECT * FROM horarios_empleados WHERE empleado_id = {$empleado->id} ORDER BY dia_semana ASC");

        // Mapear los 7 días de la semana
        $diasSemana = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            0 => 'Domingo'
        ];

        // Indexar horarios existentes por día de semana
        $horarios = [];
        foreach ($horariosDB as $h) {
            $horarios[$h->dia_semana] = $h;
        }
        
        $router->render('admin/employees/disponibilidad', [
            'nombre' => $_SESSION['nombre'],
            'titulo' => 'Disponibilidad de Horarios',
            'diasSemana' => $diasSemana,
            'horarios' => $horarios,
            'empleadoId' => $empleado->id
        ]);
    }
}
