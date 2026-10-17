<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;
// use Model\Usuario;

class DashboardController
{

    public static function index(Router $router)
    {
        if (!isSuperAdmin()) {
            header('Location: /login');
            exit;
        }
        $mesActual = date('m');
        $añoActual = date('Y');

        // 1. KPI: Ingresos del Mes (Suma de precios de citas completadas)
        $resIngresos = ActiveRecord::consultarPlano("
            SELECT SUM(s.precio) as total 
            FROM citasservicios cs
            JOIN citas c ON c.id = cs.citaId
            JOIN servicios s ON s.id = cs.servicioId
            WHERE c.estado = 'COMPLETADA' 
              AND MONTH(c.fecha) = {$mesActual} AND YEAR(c.fecha) = {$añoActual}
        ");
        $ingresos = $resIngresos[0]->total ?? 0;

        // 2. KPI: Citas del Mes
        $resCitasMes = ActiveRecord::consultarPlano("
            SELECT COUNT(*) as total 
            FROM citas 
            WHERE MONTH(fecha) = {$mesActual} AND YEAR(fecha) = {$añoActual}
        ");
        $citasMes = $resCitasMes[0]->total ?? 0;

        // 3. KPI: Completadas
        $resCompletadas = ActiveRecord::consultarPlano("
            SELECT COUNT(*) as total 
            FROM citas 
            WHERE estado = 'COMPLETADA' 
              AND MONTH(fecha) = {$mesActual} AND YEAR(fecha) = {$añoActual}
        ");
        $completadas = $resCompletadas[0]->total ?? 0;

        // 4. KPI: Cancelaciones (Cliente y Barbero)
        $resCanceladas = ActiveRecord::consultarPlano("
            SELECT COUNT(*) as total 
            FROM citas 
            WHERE estado IN ('CANCELADA', 'CANCELADA_POR_BARBERO') 
              AND MONTH(fecha) = {$mesActual} AND YEAR(fecha) = {$añoActual}
        ");
        $canceladas = $resCanceladas[0]->total ?? 0;

        // 5. Tabla: Citas Recientes (Últimas 5)
        $citasRecientes = ActiveRecord::consultarPlano("
            SELECT c.id, c.fecha, c.hora, c.estado, 
                   CONCAT(u.nombre, ' ', u.apellido) as cliente,
                   CONCAT(ub.nombre, ' ', ub.apellido) as barbero,
                   GROUP_CONCAT(s.nombre SEPARATOR ', ') as servicio
            FROM citas c
            JOIN usuarios u ON u.id = c.usuarioId
            JOIN empleados e ON e.id = c.empleadoId
            JOIN usuarios ub ON ub.id = e.usuario_id
            LEFT JOIN citasservicios cs ON cs.citaId = c.id
            LEFT JOIN servicios s ON s.id = cs.servicioId
            GROUP BY c.id
            ORDER BY c.fecha DESC, c.hora DESC 
            LIMIT 5
        ");

        // 6. Tabla: Top Barberos
        $topBarberos = ActiveRecord::consultarPlano("
            SELECT CONCAT(ub.nombre, ' ', ub.apellido) as nombre,
                   COUNT(c.id) as total_citas
            FROM citas c
            JOIN empleados e ON e.id = c.empleadoId
            JOIN usuarios ub ON ub.id = e.usuario_id
            WHERE c.estado = 'COMPLETADA'
            GROUP BY e.id
            ORDER BY total_citas DESC
            LIMIT 3
        ");

        // 7. Datos para Gráficas JSON
        $resEstados = ActiveRecord::consultarPlano("
            SELECT UPPER(TRIM(estado)) as estado, COUNT(*) as cantidad
            FROM citas 
            WHERE MONTH(fecha) = {$mesActual} AND YEAR(fecha) = {$añoActual}
            GROUP BY UPPER(TRIM(estado))
        ");

        $resServicios = ActiveRecord::consultarPlano("
            SELECT s.nombre, COUNT(cs.id) as total
            FROM citasservicios cs
            JOIN servicios s ON s.id = cs.servicioId
            JOIN citas c ON c.id = cs.citaId
            WHERE MONTH(c.fecha) = {$mesActual} AND YEAR(c.fecha) = {$añoActual}
            GROUP BY s.id
            ORDER BY total DESC
            LIMIT 6
        ");

        // 8. Datos para Gráfica de Tendencia de Citas (Últimos 7 días)
        $resTendencia = ActiveRecord::consultarPlano("
    SELECT DATE_FORMAT(fecha, '%d/%m') as dia, COUNT(*) as total
    FROM citas
    WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY fecha
    ORDER BY fecha ASC
");

        // 9. Datos Gráfica Tendencia (Últimos 7 días con relleno automático de ceros)
        $resTendenciaBD = ActiveRecord::consultarPlano("
    SELECT DATE(fecha) as fecha_db, COUNT(*) as total
    FROM citas
    WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(fecha)
");

        // Crear mapa de fechas encontradas en BD
        $citasPorFecha = [];
        foreach ($resTendenciaBD as $row) {
            $citasPorFecha[$row->fecha_db] = (int)$row->total;
        }

        // Generar los 7 días consecutivos (rellenando con 0 si no hay citas)
        $resTendencia = [];
        for ($i = 6; $i >= 0; $i--) {
            $fechaLoop = date('Y-m-d', strtotime("-$i days"));
            $diaFormateado = date('d/m', strtotime($fechaLoop));

            $resTendencia[] = [
                'dia'   => $diaFormateado,
                'total' => $citasPorFecha[$fechaLoop] ?? 0
            ];
        }

        // 10. Datos Gráfica Ingresos por Semana
        $resIngresosSemana = ActiveRecord::consultarPlano("
            SELECT CONCAT('Sem ', WEEK(c.fecha, 1) - WEEK(DATE_SUB(c.fecha, INTERVAL DAYOFMONTH(c.fecha)-1 DAY), 1) + 1) as semana,
                   COALESCE(SUM(s.precio), 0) as total
            FROM citasservicios cs
            JOIN citas c ON c.id = cs.citaId
            JOIN servicios s ON s.id = cs.servicioId
            WHERE c.estado = 'COMPLETADA'
              AND MONTH(c.fecha) = {$mesActual} AND YEAR(c.fecha) = {$añoActual}
            GROUP BY semana
            ORDER BY semana ASC
        ");


        $router->render('admin/dashboard/index', [
            'titulo' => 'Dashboard Administrativo',
            'ingresos'          => $ingresos,
            'citasMes'          => $citasMes,
            'completadas'       => $completadas,
            'canceladas'        => $canceladas,
            'citasRecientes'    => $citasRecientes,
            'topBarberos'       => $topBarberos,
            'estadosData'       => json_encode($resEstados),
            'serviciosData'     => json_encode($resServicios),
            'tendenciaData'     => json_encode($resTendencia),
            'ingresosSemanaData' => json_encode($resIngresosSemana)
        ]);
    }
}
