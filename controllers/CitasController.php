<?php

namespace Controllers;

use Classes\Paginacion;
use Model\ActiveRecord;
use MVC\Router;

class CitasController
{
    public static function index(Router $router) {
        if(!isSuperAdmin()){
            header('Location: /login');
            exit;
        }

        // 1. PRIMERO: Capturar variables GET desde el inicio
        $estado = $_GET['estado'] ?? '';
        $barbero_id = $_GET['barbero'] ?? '';

        // 2. Construir filtro SQL
        $where = [];
        
        if(!empty($estado) && in_array($estado, ['COMPLETADA', 'PENDIENTE', 'CANCELADA', 'EN_PROCESO', 'NO_ASISTIO'])) {
            $where[] = "c.estado = '{$estado}'";
        }
        
        if(!empty($barbero_id) && filter_var($barbero_id, FILTER_VALIDATE_INT)) {
            $where[] = "c.empleadoId = {$barbero_id}";
        }

        $whereSQL = '';
        if(!empty($where)) {
            $whereSQL = 'WHERE ' . implode(' AND ', $where);
        }

        // 3. Paginación
        $pagina_actual = $_GET['page'] ?? 1;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        if(!$pagina_actual || $pagina_actual < 1){
            $pagina_actual = 1;
        }

        $registros_por_pagina = 8;

        // Total registros filtrados
        $resTotal = ActiveRecord::consultarPlano("SELECT COUNT(DISTINCT c.id) as total FROM citas c {$whereSQL}");
        $total = $resTotal[0]->total ?? 0;

        // Armar arreglo de parámetros activos para la URL de paginación
        $paramsFiltro = array_filter([
            'estado'  => $estado,
            'barbero' => $barbero_id
        ]);

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total, $paramsFiltro);

        if($total > 0 && $pagina_actual > $paginacion->total_paginas()){
            $queryUrl = http_build_query(array_merge($paramsFiltro, ['page' => $paginacion->total_paginas()]));
            header('Location: /admin/dashboard/citas?' . $queryUrl);
            exit;
        }

        $offset = $paginacion->offset();

        // 4. Consulta optimizada
        $query = "
            SELECT c.id, c.fecha, c.hora, c.estado, 
                   CONCAT(u.nombre, ' ', u.apellido) as cliente,
                   CONCAT(ub.nombre, ' ', ub.apellido) as barbero,
                   GROUP_CONCAT(s.nombre SEPARATOR ', ') as servicio,
                   COALESCE(SUM(s.precio), 0) as precio
            FROM citas c
            JOIN usuarios u ON u.id = c.usuarioId
            JOIN empleados e ON e.id = c.empleadoId
            JOIN usuarios ub ON ub.id = e.usuario_id
            LEFT JOIN citasservicios cs ON cs.citaId = c.id
            LEFT JOIN servicios s ON s.id = cs.servicioId
            {$whereSQL}
            GROUP BY c.id, c.fecha, c.hora, c.estado, u.nombre, u.apellido, ub.nombre, ub.apellido
            ORDER BY c.fecha DESC, c.hora DESC
            LIMIT {$registros_por_pagina} OFFSET {$offset}
        ";

        $citas = ActiveRecord::consultarPlano($query);

        // 5. Lista de barberos para el select
        $barberos = ActiveRecord::consultarPlano("
            SELECT e.id, CONCAT(u.nombre, ' ', u.apellido) as nombre 
            FROM empleados e
            JOIN usuarios u ON u.id = e.usuario_id
            ORDER BY u.nombre ASC
        ");

        $router->render('admin/dashboard/citas/index', [
            'titulo'              => 'Visualización de Citas',
            'citas'               => $citas,
            'barberos'            => $barberos,
            'estadoSeleccionado'  => $estado,
            'barberoSeleccionado' => $barbero_id,
            'paginacion'          => $paginacion->paginacion()
        ]);
    }
}