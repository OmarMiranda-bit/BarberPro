<?php

namespace Controllers;

use Classes\Email;
use Classes\Paginacion;
use Intervention\Image\ImageManagerStatic as Image;
use Model\Empleado;
use Model\Usuario;
use MVC\Router;

class BarberosController
{
    public static function index(Router $router)
    {
        if (!isSuperAdmin()) {
            header('Location: /login');
        }

        // $pagina_actual = $_GET['page'];
        // $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);

        // if (!$pagina_actual || $pagina_actual < 1) {
        //     header('Location: /admin/dashboard/barberos?page=1');
        // }
        // //Es el numero de registros que se muestran en cada pagina
        // $registros_por_pagina = 5;

        // //Obtener el total de registros
        // $total = Empleado::total();
        // $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);


        // //Manda a llamar a los empleados (barberos) ya con la paginacion
        // //offset indica desde que registro va a empezar a traer los datos
        // $barberos = Empleado::paginar($registros_por_pagina, $paginacion->offset());

        $barberos = Empleado::all();
        // // Cargar el usuario para cada empleado
        foreach ($barberos as $barbero) {
            $barbero->usuario = $barbero->obtenerUsuario();
        }

        // //Si la pagina actual es mayor al total de paginas redirecciona a la ultima pagina
        // if ($pagina_actual > $paginacion->total_paginas()) {
        //     header('Location: /admin/dashboard/barberos?page=' . $paginacion->total_paginas());
        // }


        $router->render('admin/dashboard/barberos/index', [
            'titulo' => 'Gestión de Barberos',
            'barberos' => $barberos,
            // 'paginacion' => $paginacion->paginacion()

        ]);
    }

    public static function crear(Router $router)
    {
        if (!isSuperAdmin()) {
            header('Location: /login');
        }

        $alertas = [];
        $usuario = new Usuario;
        $empleado = new Empleado;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isSuperAdmin()) {
                header('Location: /login');
            }

            //Validacion de la imagen
            if (!empty($_FILES['imagen']['tmp_name'])) {
                // Definir el tamaño máximo (2MB en bytes)
                $medida = 1000 * 2000; // Aproximadamente 2MB
                // VALIDACIÓN DE TAMAÑO
                if ($_FILES['imagen']['size'] > $medida) {
                    $alertas['error'][] = 'La imagen es muy pesada. Máximo 2MB';
                } else {
                    // Si pasa la validación de tamaño, procedemos con el resto
                    $carpeta_imagenes = '../public/img/barberos';

                    if (!is_dir($carpeta_imagenes)) {
                        mkdir($carpeta_imagenes, 0755, true);
                    }

                    $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                    $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);
                    $nombre_imagen = md5(uniqid(rand(), true));

                    $_POST['imagen'] = $nombre_imagen;
                }
            } else {
                // Validación manual si no hay imagen
                $alertas['error'][] = 'La imagen es obligatoria para los Barberos.';
            }

            //convierte las redes sociales a un string para que se pueda guardar en la base de datos
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);

            //sincronizacion de los datos en el formulario con el modelo para que se puedan validar
            $usuario->sincronizar($_POST);
            $empleado->sincronizar($_POST); // Al sincronizar, el $empleado ya toma el $_POST['imagen']

            //Validacion
            $alertas_usuario = $usuario->validar();

            // Unimos las alertas del usuario (nombre, apellido) con las de la imagen (si las hubo arriba)
            $alertas = array_merge_recursive($alertas_usuario, $alertas);

            //guardar el registro si no hay alertas
            if (empty($alertas)) {

                // Verificamos si existe el correo justo antes de guardar
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario) {
                    $alertas['error'][] = 'El correo ya está registrado por otro usuario';
                } else {

                    // Dar permisos de escritura a la carpeta
                    chmod($carpeta_imagenes, 0755);

                    // Guardar las imagenes en memoria principal físicamente
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . '.png');
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . '.webp');

                    // Guardar en la BD - Primero en la tabla de Usuarios
                    $usuario->hashPassword();
                    $usuario->crearToken();
                    $usuario->rol_id = 2; // Asignamos el rol de empleado directamente al crear el usuario desde el dashboard

                    $resultado = $usuario->guardar();
                    // Enviar email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion(); // verificar que funcione el enviar el email y mostrar la alerta con sweet alert

                    if ($resultado['resultado']) {
                        // Asignamos la llave foránea y guardamos el Empleado
                        $empleado->usuario_id = $resultado['id'];
                        $resultado_empleado = $empleado->guardar();

                        // Redireccionar cuando retorna un true 
                        if ($resultado_empleado) {
                            header('Location: /admin/dashboard/barberos');
                            exit;
                        }
                    }
                }
            }
        }

        $router->render('admin/dashboard/barberos/crear', [
            'titulo' => 'Añadir Nuevo Barbero',
            'alertas' => $alertas,
            'usuario' => $usuario,
            'empleado' => $empleado,
            'redes' => json_decode($empleado->redes) // Convertir el string a objeto para autocompletar
        ]);
    }

    public static function editar(Router $router)
    {
        if (!isSuperAdmin()) {
            header('Location: /login');
        }

        $alertas = [];
        //validar el id
        $id = $_GET['id'];
        //Verificar que sea un valor entero
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: /admin/dashboard/barberos');
        }
        //Obtener al empleado a editar
        $empleado = Empleado::find($id);

        if (!$empleado) {
            header('Location: /admin/dashboard/barberos');
        }
        // Obtener el id de la tabla usuarios que esta asociado a la tabla empleados
        $usuario = Usuario::find($empleado->usuario_id);

        $empleado->imagen_actual = $empleado->imagen;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Leer la imagen 
            if (!empty($_FILES['imagen']['tmp_name'])) {
                $carpeta_imagenes = '../public/img/barberos';

                //crea la carpeta si no existe 
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }
                //Convierte la imgen en png y en webp
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                //Genera un nommbre aleatorio
                $nombre_imagen = md5(uniqid(rand(), true));

                //pasa la imagen al post para pasar la validacion
                $_POST['imagen'] = $nombre_imagen;
            } else { // Se recupera o se queda la imagen que estaba 
                $_POST['imagen'] = $empleado->imagen_actual;
            }

            //Convierte el arreglo de redes en un string
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);

            //Sincroniza los cambios 
            // No sincronizar password y email vacíos en edición para no sobrescribir los existentes
            if (empty($_POST['password'])) {
                unset($_POST['password']);
            }
            if (empty($_POST['email'])) {
                unset($_POST['email']);
            }
            $usuario->sincronizar($_POST);
            $empleado->sincronizar($_POST);

            //Validacion
            $alertas_usuario = $usuario->validar();

            // Unimos las alertas del usuario (nombre, apellido) con las de la imagen (si las hubo arriba)
            $alertas = array_merge_recursive($alertas_usuario, $alertas);

            if (empty($alertas)) {
                // Si hay imagen nueva, guardarla
                if (isset($nombre_imagen)) {
                    // Dar permisos de escritura a la carpeta
                    chmod($carpeta_imagenes, 0755);

                    // Guardar las imágenes
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . '.png');
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . '.webp');

                    // Eliminar imágenes antiguas si existen
                    if (file_exists($carpeta_imagenes . '/' . $empleado->imagen_actual . '.png')) {
                        unlink($carpeta_imagenes . '/' . $empleado->imagen_actual . '.png');
                    }
                    if (file_exists($carpeta_imagenes . '/' . $empleado->imagen_actual . '.webp')) {
                        unlink($carpeta_imagenes . '/' . $empleado->imagen_actual . '.webp');
                    }
                }

                // Guardar los cambios del usuario
                $resultado_usuario = $usuario->guardar();

                // Guardar los cambios del empleado
                $resultado_empleado = $empleado->guardar();

                // Si todo se guardó correctamente, redirigir
                if ($resultado_usuario && $resultado_empleado) {
                    header('Location: /admin/dashboard/barberos');
                }
            }
        }

        $router->render('admin/dashboard/barberos/editar', [
            'titulo' => 'Editar Barbero',
            'alertas' => $alertas,
            'usuario' => $usuario,
            'empleado' => $empleado,
            'redes' => json_decode($empleado->redes) //COnvertir el string de las redes a un objeto y autocompletar
        ]);
    }

    public static function eliminar()
    {
        if (!isSuperAdmin()) {
            header('Location: /login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            //Obtiene toda la informacion del objeto a eliminar 
            $empleado = Empleado::find($id);

            //Verificar que el empleado no venga vacio 
            if (!isset($empleado)) {
                header('Location: /admin/dashboard/barberos');
            }

            // Obtener el ID del usuario asociado antes de eliminar
            $usuario_id = $empleado->usuario_id;

            // Eliminar las imágenes del servidor
            $carpeta_imagenes = '../public/img/barberos';
            if (file_exists($carpeta_imagenes . '/' . $empleado->imagen . '.png')) {
                unlink($carpeta_imagenes . '/' . $empleado->imagen . '.png');
            }
            if (file_exists($carpeta_imagenes . '/' . $empleado->imagen . '.webp')) {
                unlink($carpeta_imagenes . '/' . $empleado->imagen . '.webp');
            }

            //Elimina al empleado de la BD
            $resultado_empleado = $empleado->eliminar();

            if ($resultado_empleado) {
                // Eliminar el usuario asociado
                $usuario = Usuario::find($usuario_id);
                if ($usuario) {
                    $usuario->eliminar();
                }
                header('Location: /admin/dashboard/barberos');
            }
        }
    }
}
