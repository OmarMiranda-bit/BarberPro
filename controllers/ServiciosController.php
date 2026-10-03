<?php

namespace Controllers;

use Model\Servicio;
use Intervention\Image\ImageManagerStatic as Image;
use MVC\Router;

class ServiciosController
{
    public static function index(Router $router)
    {
        if (!isSuperAdmin()) {
            header('Location: /login');
        }
        // Obtenemos todos los servicios usando Active Record
        $servicios = Servicio::all();
        // $pagina_actual = $_GET['page'];
        // $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        // if(!$pagina_actual || $pagina_actual < 1){
        //     header('Location: /admin/dashboard/barberos?page=1');
        // }

        $router->render('admin/dashboard/servicios/index', [
            'titulo' => 'Análisis de Servicios y administración',
            'servicios' => $servicios
        ]);
    }


    public static function crear(Router $router)
    {
        error_reporting(E_ALL & ~E_DEPRECATED);
        if (!isSuperAdmin()) {
            header('Location: /login');
            exit;
        }
        $alertas = [];
        $servicio = new Servicio;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Protección de la ruta para que solo el admin principal acceda
            if (!isSuperAdmin()) {
                header('Location: /login');
                exit;
            }

            $archivoImagen = $_FILES['imagen'] ?? null;
            $errorArchivo = $archivoImagen['error'] ?? UPLOAD_ERR_NO_FILE;
            $imagenValida = is_array($archivoImagen)
                && (!empty($archivoImagen['tmp_name']) || !empty($archivoImagen['name']))
                && $errorArchivo === UPLOAD_ERR_OK;

            // Validacion de la imagen
            if ($imagenValida) {
                // Definir el tamaño máximo (2MB en bytes)
                $medida = 1000 * 2000;

                // VALIDACIÓN DE TAMAÑO
                if ($archivoImagen['size'] > $medida) {
                    $alertas['error'][] = 'La imagen es muy pesada. Máximo 2MB';
                } else {
                    // Carpeta independiente para las imágenes de los servicios
                    $carpeta_imagenes = '../public/img/servicios';

                    if (!is_dir($carpeta_imagenes)) {
                        mkdir($carpeta_imagenes, 0755, true);
                    }

                    // Generamos versiones optimizadas de la imagen
                    $imagen_png = Image::make($archivoImagen['tmp_name'])->fit(800, 800)->encode('png', 80);
                    $imagen_webp = Image::make($archivoImagen['tmp_name'])->fit(800, 800)->encode('webp', 80);
                    $nombre_imagen = md5(uniqid(rand(), true));

                    // Asignamos el nombre al POST para sincronizarlo con el modelo
                    $_POST['imagen'] = $nombre_imagen;
                }
            } elseif (is_array($archivoImagen) && !empty($archivoImagen['name'])) {
                $alertas['error'][] = 'La imagen no se pudo subir correctamente';
            } else {
                $alertas['error'][] = 'La imagen del servicio es obligatoria';
            }

            // Sincronizamos el modelo con los datos del POST
            $servicio->sincronizar($_POST);

            // Validamos campos (Nombre, Precio, Descripción)
            $alertas = array_merge($alertas, $servicio->validar());
            // debuguear($alertas);
            if (empty($alertas)) {
                // Guardar imágenes en el servidor
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . '.png');
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . '.webp');

                // Guardar en la BD
                $resultado = $servicio->guardar();

                if ($resultado) {
                    header('Location: /admin/dashboard/servicios');
                    exit;
                }
            }
        }

        $router->render('admin/dashboard/servicios/crear', [
            'titulo' => 'Anáde un nuevo servicio',
            'alertas' => $alertas,
            'servicio' => $servicio
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
            header('Location: /admin/dashboard/servicios');
        }
        //Obtener al empleado a editar
        $servicio = Servicio::find($id);

        if (!$servicio) {
            header('Location: /admin/dashboard/servicios');
        }
        //obtiene la referencia de la imagen actual
        $servicio->imagen_actual = $servicio->imagen;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Leer la imagen 
            $archivoImagen = $_FILES['imagen'] ?? null;
            $errorArchivo = $archivoImagen['error'] ?? UPLOAD_ERR_NO_FILE;
            if (is_array($archivoImagen) && (!empty($archivoImagen['tmp_name']) || !empty($archivoImagen['name'])) && $errorArchivo === UPLOAD_ERR_OK) {
                $carpeta_imagenes = '../public/img/servicios';

                //crea la carpeta si no existe 
                if (!is_dir($carpeta_imagenes)) {
                    mkdir($carpeta_imagenes, 0755, true);
                }
                //Convierte la imgen en png y en webp
                $imagen_png = Image::make($archivoImagen['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagen_webp = Image::make($archivoImagen['tmp_name'])->fit(800, 800)->encode('webp', 80);

                //Genera un nommbre aleatorio
                $nombre_imagen = md5(uniqid(rand(), true));

                //pasa la imagen al post para pasar la validacion
                $_POST['imagen'] = $nombre_imagen;
            } else { // Se recupera o se queda la imagen que estaba 
                $_POST['imagen'] = $servicio->imagen_actual;
            }

            // Sincronizar el modelo con los datos del POST
            $servicio->sincronizar($_POST);

            // Validar campos
            $alertas = $servicio->validar();

            if (empty($servicio->imagen_actual) && empty($_POST['imagen'])) {
                $alertas['error'][] = 'La imagen del servicio es obligatoria';
            }

            if (empty($alertas)) {
                // Si el usuario subió una imagen nueva, guardamos y borramos la vieja
                if (isset($nombre_imagen)) {
                    chmod($carpeta_imagenes, 0755);

                    // Guardar nuevas versiones
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . '.png');
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . '.webp');

                    // Eliminar imágenes antiguas para no saturar el servidor
                    $ruta_vieja = $carpeta_imagenes . '/' . $servicio->imagen_actual;
                    if (file_exists($ruta_vieja . '.png')) {
                        unlink($ruta_vieja . '.png');
                    }
                    if (file_exists($ruta_vieja . '.webp')) {
                        unlink($ruta_vieja . '.webp');
                    }
                }

                // Guardar cambios en la BD
                $resultado = $servicio->guardar();

                if ($resultado) {
                    header('Location: /admin/dashboard/servicios');
                    exit;
                }
            }
        }

        $router->render('admin/dashboard/servicios/editar', [
            'titulo' => 'Actualización del Servicio',
            'alertas' => $alertas,
            'servicio' => $servicio
        ]);
    }
    public static function eliminar()
    {
        if (!isSuperAdmin()) {
            header('Location: /login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if (!$id) {
                header('Location: /admin/dashboard/servicios');
                exit;
            }

            $servicio = Servicio::find($id);

            if (!$servicio) {
                header('Location: /admin/dashboard/servicios');
                exit;
            }
            
            // Carpeta donde se almacenan las imágenes de los servicios
            $carpeta_imagenes = '../public/img/servicios';

            // Borramos el formato PNG
            if (file_exists($carpeta_imagenes . '/' . $servicio->imagen . '.png')) {
                unlink($carpeta_imagenes . '/' . $servicio->imagen . '.png');
            }
            // Borramos el formato WebP
            if (file_exists($carpeta_imagenes . '/' . $servicio->imagen . '.webp')) {
                unlink($carpeta_imagenes . '/' . $servicio->imagen . '.webp');
            }

            $resultado = $servicio->eliminar();

            if ($resultado) {
                header('Location: /admin/dashboard/servicios');
                exit;
            }
        }
    }
}
