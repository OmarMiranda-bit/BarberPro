<?php

namespace MVC;

//Se encarga de registrar rutas HTTP y decidir qué vista o controlador
//debe ejecutarse según la URL y el método de la petición.
class Router
{
    //Almacena todas las rutas GET registradas.
    public array $getRoutes = [];
    //Almacena todas las rutas POST registradas
    public array $postRoutes = [];
    //Registra una ruta de tipo GET.
    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }
    //Registra una ruta de tipo POST.
    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }
    //Verifica la URL actual y ejecuta la función asociada al método HTTP.
    public function comprobarRutas()
    {

        // $url_actual = strtok($_SERVER['REQUEST_URI'], '?') ?? '/'; // Con esto nos aseguramos de que si hay un query string no lo tome en cuenta
        // $url_actual = $_SERVER['PATH_INFO'] ?? '/';

        $url_actual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$url_actual] ?? null;
        } else {
            $fn = $this->postRoutes[$url_actual] ?? null;
        }


        if ($fn) {
            // Call user fn va a llamar una función cuando no sabemos cual sera
            call_user_func($fn, $this); // This es para pasar argumentos
        } else {
            echo "Página No Encontrada o Ruta no válida";
        }
    }
    //Renderiza una vista y la envuelve con el layout adecuado según la ruta y el tipo de usuario.
    public function render($view, $datos = [])
    {
        // líneas mágicas anti-caché
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        // Procesar datos para la vista
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        // Capturar el contenido de la vista
        ob_start(); //almacenamiento en memoria durante un momento....
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();

        // Obtener la URL actual
        // $url_actual = $_SERVER['PATH_INFO'] ?? '/';

        $url_actual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';


        // Arrays de rutas para cada tipo de layout
        $rutas_auth = ['/login', '/crear-cuenta', '/olvide', '/recuperar',  '/confirmar-cuenta', '/mensaje', '/reservar/cita'];
        //rutas para el empleado (admin)
        $rutas_admin = ['/admin/employees', '/servicios'];
        //ruta para el dashboard admin principal
        $rutas_dashboard = ['/dashboard'];

        // CASO A: Es Super Admin y quiere ver el dashboard
        if ($this->matchRuta($url_actual, $rutas_dashboard) && isset($_SESSION['super_admin'])) {
            include_once __DIR__ . '/views/dashboard-layout.php'; // Asumo que crearás un layout diferente o usarás el admin-layout con más opciones
        }
        // CASO B: Es Admin (Empleado)
        else if ($this->matchRuta($url_actual, $rutas_admin) && isset($_SESSION['admin'])) {
            include_once __DIR__ . '/views/admin-layout.php';
        } // CASO C: Rutas de autenticación o cualquier otra ruta pública
        else if ($this->matchRuta($url_actual, $rutas_auth)) {
            $contenedor_clase = 'contenedor-auth';
            include_once __DIR__ . '/views/layout.php';
        } else {
            $contenedor_clase = 'contenedor-paginas';
            include_once __DIR__ . '/views/layout.php';
        }
    }
    //Comprueba si la URL actual coincide con alguna ruta dentro de una lista de rutas.
    private function matchRuta($url_actual, $rutas): bool
    {
        foreach ($rutas as $ruta) {
            if (str_contains($url_actual, $ruta)) {
                return true;
            }
        }
        return false;
    }
}
