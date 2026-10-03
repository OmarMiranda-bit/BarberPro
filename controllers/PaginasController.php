<?php

namespace Controllers;

use Classes\Email;
use Model\Empleado;
use Model\Servicio;
use Model\Usuario;
use MVC\Router;

class PaginasController
{

    public static function index(Router $router)
    {

        // Si ya hay sesión de cliente activa redirigir
        if (isAuth() && !isAdmin() && !isSuperAdmin()) {
            header('Location: /reservar');
            exit;
        }

        // Consultamos los servicios también para la página de inicio
        $servicios = Servicio::all();
        // Consultar empleados y sus datos de usuario para la sección del equipo en el inicio
        $empleados = Empleado::all();
        foreach ($empleados as $empleado) {
            $empleado->usuario = Usuario::find($empleado->usuario_id);
        }

        $router->render('paginas/index', [
            'titulo' => 'Inicio',
            'servicios' => $servicios,
            'empleados' => $empleados
        ]);
    }

    public static function services(Router $router)
    {
        // Consultar todos los servicios usando Active Record
        $servicios = Servicio::all();

        $router->render('paginas/services', [
            'titulo' => 'Servicios',
            'servicios' => $servicios
        ]);
    }
    public static function galeria(Router $router)
    {
        $router->render('paginas/galeria', [
            'titulo' => 'Galerias'
        ]);
    }
    public static function equipo(Router $router)
    {

        // Consultar todos los empleados (puedes filtrar por los activos si lo deseas: Empleado::where('activo', 1))
        $empleados = Empleado::all();

        // Recorrer cada empleado para adjuntarle los datos de su tabla Usuario (nombre, apellido)
        foreach ($empleados as $empleado) {
            $empleado->usuario = Usuario::find($empleado->usuario_id);
        }
        $router->render('paginas/equipo', [
            'titulo' => 'Equipo',
            'empleados' => $empleados,

        ]);
    }

    public static function contacto(Router $router)
    {
        $alertas = [];
        $respuestas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitizar datos
            $respuestas = $_POST;

            // Validaciones
            if (empty($respuestas['nombre'])) {
                $alertas['error'][] = 'El nombre es obligatorio';
            }

            if (empty($respuestas['email'])) {
                $alertas['error'][] = 'El email es obligatorio';
            } else if (!filter_var($respuestas['email'], FILTER_VALIDATE_EMAIL)) {
                $alertas['error'][] = 'El email no es válido';
            }

            if (empty($respuestas['telefono'])) {
                $alertas['error'][] = 'El teléfono es obligatorio';
            }

            if (empty($respuestas['asunto'])) {
                $alertas['error'][] = 'Selecciona un asunto';
            }

            if (empty($respuestas['mensaje'])) {
                $alertas['error'][] = 'El mensaje es obligatorio';
            }

            // Si no hay errores, enviar el email
            if (empty($alertas)) {
                // Instanciar Email (email del remitente, nombre y token vacío)
                $email = new Email($respuestas['nombre'], $respuestas['email'], '');

                // Intentar el envío por SMTP / Mailer
                $enviado = $email->enviarContacto(
                    $respuestas['telefono'],
                    $respuestas['asunto'],
                    $respuestas['mensaje']
                );

                if ($enviado) {
                    $alertas['exito'][] = '¡Mensaje enviado correctamente! Nos pondremos en contacto contigo pronto.';
                    // Limpiar formulario para que los inputs queden vacíos
                    $respuestas = [];
                } else {
                    $alertas['error'][] = 'Hubo un problema al enviar el mensaje. Inténtalo más tarde.';
                }
            }
        }

        $router->render('paginas/contacto', [
            'titulo' => 'Contacto',
            'alertas' => $alertas,
            'datos' => $respuestas
        ]);
    }
}
