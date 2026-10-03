<?php

namespace Controllers;

use Classes\Paginacion;
use Model\ActiveRecord;
use MVC\Router;

class RegistradosController {

    public static function index(Router $router) {
        if(!isSuperAdmin()) {
            header('Location: /login');
            exit;
        }

        $pagina_actual = $_GET['page'] ?? 1;
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT) ?: 1;
        $registros_por_pagina = 10;

        // 1. Total de usuarios con rol_id = 0 (Clientes)
        $resTotal = ActiveRecord::consultarPlano("
            SELECT COUNT(*) as total 
            FROM usuarios 
            WHERE rol_id = 0
        ");
        $total = $resTotal[0]->total ?? 0;

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        if($total > 0 && $pagina_actual > $paginacion->total_paginas()){
            header('Location: /admin/dashboard/registrados?page=' . $paginacion->total_paginas());
            exit;
        }

        $offset = $paginacion->offset();

        // 2. Consulta de clientes con rol_id = 0 y conteo de citas
        $registrados = ActiveRecord::consultarPlano("
            SELECT u.id, u.nombre, u.apellido, u.email, u.telefono, u.confirmado,
                   COUNT(c.id) as total_citas
            FROM usuarios u
            LEFT JOIN citas c ON c.usuarioId = u.id
            WHERE u.rol_id = 0
            GROUP BY u.id, u.nombre, u.apellido, u.email, u.telefono, u.confirmado
            ORDER BY u.id DESC
            LIMIT {$registros_por_pagina} OFFSET {$offset}
        ");

        $router->render('admin/dashboard/registrados/index', [
            'titulo'      => 'Clientes Registrados',
            'registrados' => $registrados,
            'paginacion'  => $paginacion->paginacion()
        ]);
    }
}