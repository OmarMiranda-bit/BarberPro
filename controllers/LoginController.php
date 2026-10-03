<?php

namespace Controllers;

use Model\Cita;
use MVC\Router;
use Classes\Email;
use Model\Usuario;

// controla toda la logica de autenticacion, registro y recuperacion de contraseñas 
class LoginController
{
    //metodo que renderiza las vistas 
    public static function login(Router $router)
    {
        $auth = new Usuario;
        // ----funciona para autocompletar los campos en el formulario 
        $alertas = [];
        //verrificar si el formulario fue enviado via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //crear una instancia del usuario con los datos del formulario 
            $auth = new Usuario($_POST);
            //validar que el email y el password cumplan los requisitos 
            $alertas = $auth->validarLogin();
            //Si no hay errores de validacion, continua con la autenticacion 
            if (empty($alertas)) {
                //Comprobar que exista el usuario en la BD por medio de su email
                $usuario = Usuario::where('email', $auth->email);
                //Si el usiario existe entonces 
                if ($usuario) {
                    //verificar el password correcto y que la cuenta este confirmada
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        //Autenticar al usuario si la sesion no esta iniciada
                        if (!isset($_SESSION)) {
                            session_start();
                        };
                        //guadar los datos del usuario en la sesión
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido; // Concatenamos el nombre y apellido con un espacio entre ellos para mostrar el nombre completo en la barra de navegación después de iniciar sesión
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Guardamos el nivel exacto que viene de la BD (0, 1 o 2)
                        // $_SESSION['admin'] = $usuario->admin;
                        $_SESSION['rol_id'] = $usuario->rol_id;
                        //Redireccionar segun el tipo de usuario

                        if ($usuario->rol_id == 1) {
                            $_SESSION['super_admin'] = true;
                            header('Location: /admin/dashboard');
                        } else if ($usuario->rol_id == 2) {
                            $_SESSION['admin'] = true;
                            header('Location: /admin/employees');
                        } else {
                            header('Location: /reservar/cita'); // Es cliente
                        }
                    }
                } else { // sino existe el usuario manda el mensaje de error 
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        //obtener todas las alertas
        $alertas = Usuario::getAlertas();
        //rederizar la vista del login 
        $router->render('auth/login', [
            'alertas' => $alertas,
            'titulo' => 'Iniciar Sesión',
            'auth' => $auth
        ]);
    }
    //metodo para cerrar la sesion del usuario 
    public static function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            //vaciar el arreglo de sesión para eliminar los datos del usuario
            $_SESSION = [];
            // Regenerar el ID de sesión y eliminar el anterior
            // El parámetro true elimina la sesión antigua
            session_regenerate_id(true);
            // Eliminar la cookie de sesión del navegador
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }

            header('Location: /');
        }
    }


    // Método para login vía AJAX (desde el modal en la pagina de /reservar)
    //Devuelve JSON con alertas o datos de autenticación
    public static function loginAPI()
    {
        // Iniciar sesión si no está iniciada
        if (!isset($_SESSION)) {
            session_start();
        }
        // crea nuva instancia del usuario 
        $auth = new Usuario($_POST);
        // validar el email y password
        $alertas = $auth->validarLogin();

        // Si hay errores de validación, devolver JSON con los errores
        if (!empty($alertas)) {
            echo json_encode([
                'success' => false,
                'alertas' => $alertas
            ]);
            exit;
        }

        // Comprobar que exista el usuario por medio de su email 
        $usuario = Usuario::where('email', $auth->email);
        //si no existe el usuario, devolver error
        if (!$usuario) {
            Usuario::setAlerta('error', 'Usuario no encontrado');
            $alertas = Usuario::getAlertas();
            // http_response_code(404);
            echo json_encode([
                'success' => false,
                'alertas' => $alertas
            ]);
            exit;
        }

        // Verificar el password y que la cuenta esté verificada
        if (!$usuario->comprobarPasswordAndVerificado($auth->password)) {
            Usuario::setAlerta('error', 'Password incorrecto');
            $alertas = Usuario::getAlertas();
            echo json_encode([
                'success' => false,
                'alertas' => $alertas
            ]);
            exit;
        }

        // Validar que los administradores no inicien sesión desde el modal
        $desdeModal = $_POST['desde_modal'] ?? null;
        if (((int)$usuario->rol_id === 1 || (int)$usuario->rol_id === 2) && (int)$desdeModal === 1) {
            Usuario::setAlerta('error', 'Acceso no permitido desde esta página');
            $alertas = Usuario::getAlertas();
            echo json_encode([
                'success' => false,
                'alertas' => $alertas,
                'redirect' => '/login'
            ]);
            exit; //Evita que el código continúe ejecutándose y se inicie sesión para un admin desde el modal
        }

        // guarda los datos del usuario en la sesión solo si es un cliente (rol_id = 0)
        $_SESSION['id'] = $usuario->id;
        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido; // Concatenamos el nombre y apellido con un espacio entre ellos para mostrar el nombre completo en la barra de navegación después de iniciar sesión
        $_SESSION['email'] = $usuario->email;
        $_SESSION['login'] = true;

        $_SESSION['rol_id'] = $usuario->rol_id;

        // Para el modal de verificar cita, la redirección debe llevar al detalle de la cita
        // y no al flujo general de crear una nueva cita.
        if ($usuario->rol_id == 0) {
            $redirect = '/reservar/cita';
        }

        // Devolver respuesta exitosa
        echo json_encode([
            'success' => true,
            'mensaje' => 'Sesión iniciada correctamente',
            'redirect' => $redirect
        ]);
        exit;
    }

    /*Método para verificar cita vía AJAX (desde el modal) 
  Devuelve JSON con alertas o datos de redirección*/
    public static function verificarCitaAPI()
    {
        // Iniciar sesión si no está iniciada
        if (!isset($_SESSION)) {
            session_start();
        }

        // Validar que existan los 3 datos requeridos
        if (empty($_POST['email']) || empty($_POST['codigo']) || empty($_POST['password'])) {
            Usuario::setAlerta('error', 'Todos los campos son obligatorios');
            $alertas = Usuario::getAlertas();
            echo json_encode([
                'success' => false,
                'alertas' => $alertas
            ]);
            exit;
        }

        // Sanitizar datos
        $email = s($_POST['email']);
        $folio = s($_POST['codigo']);
        $password = $_POST['password'];

        // Paso de Seguridad 1: Buscar al usuario por Email
        $usuario = Usuario::where('email', $email);

        if (!$usuario) {
            Usuario::setAlerta('error', 'Usuario no encontrado');
            $alertas = Usuario::getAlertas();
            echo json_encode([
                'success' => false,
                'alertas' => $alertas
            ]);
            exit;
        }

        // Verificar la contraseña
        if (!password_verify($password, $usuario->password)) {
            Usuario::setAlerta('error', 'Contraseña incorrecta');
            $alertas = Usuario::getAlertas();
            echo json_encode([
                'success' => false,
                'alertas' => $alertas
            ]);
            exit;
        }

        // Paso de Seguridad 2: Verificar si ese usuario es dueño del folio
        $consulta = "SELECT id FROM citas WHERE folio = '{$folio}' AND usuarioId = '{$usuario->id}' LIMIT 1";
        $citaExiste = Cita::SQL($consulta);

        if (empty($citaExiste)) {
            Usuario::setAlerta('error', 'El folio no pertenece a esta cuenta');
            $alertas = Usuario::getAlertas();
            echo json_encode([
                'success' => false,
                'alertas' => $alertas
            ]);
            exit;
        }
        // Si todo es correcto, guardar el identificador interno de la cita y redirigir con el ID en la URL
        $cita = $citaExiste[0] ?? null;
        if ($cita) {
            // Guardar los datos del usuario en la sesión
            $_SESSION['id'] = $usuario->id;
            $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
            $_SESSION['email'] = $usuario->email;
            $_SESSION['login'] = true;
            $_SESSION['rol_id'] = $usuario->rol_id;

            //guardar el ID de la cita y el folio en la sesión para usarlo en la página de detalle
            $_SESSION['cita_actual_id'] = $cita->id;
            $_SESSION['cita_actual_folio'] = $folio;
        }

        echo json_encode([
            'success' => true,
            'mensaje' => 'Cita encontrada correctamente',
            'redirect' => '/reservar/cita/detalle?id=' . urlencode((string)($cita->id ?? 0))
        ]);
        exit;
    }


    //Metodo para solicitar reestablecimiento de su contraseña 
    public static function olvide(Router $router)
    {
        $alertas = [];
        //Si el formulario fue enviado via POST 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //crea la instancia del usuario con el email 
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail(); //se manda a llamar la alerta para confirmar el email
            //si el email es valido entonces 
            if (empty($alertas)) {
                //Busca al usuario en la BD 
                $usuario = Usuario::where('email', $auth->email);
                //si el usuario existe y esta confirmado
                if ($usuario && $usuario->confirmado === "1") {
                    //Generar un token unico para recuperar la contraseña
                    $usuario->crearToken();
                    //guarda el token en la BD
                    $usuario->guardar();

                    //Enviar el email con las instrucciones para recuperar la contraseña 
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();
                    //Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                } else { // si no existe el usuario o no esta confirmado 
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        } //obtener las alertas 
        $alertas = Usuario::getAlertas();
        //renderizar la vista 
        $router->render('auth/olvide-password', [
            'alertas' => $alertas,
            'titulo' => 'Reestablecer Contraseña'
        ]);
    }
    //metodo para cambiar la cpntraseña con el token recibido por email 
    public static function recuperar(Router $router)
    {

        $alertas = [];
        $error = false;
        //obtener y sanitizar el token de la URL
        $token = s($_GET['token']);
        //Buscar al usuario en la BD por su token
        $usuario = Usuario::where('token', $token);
        // si el token no existe o es invalido 
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token No Válido');
            $error = true;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            //validar que la contraseña cumpla con los requisitos
            $alertas = $password->validarPassword();
            // si no hay errorres o las alertas estan vacias
            if (empty($alertas)) {
                $usuario->password = null; // anula el password anterior
                $usuario->password = $password->password; // Se actualiza el password anterior por el nuevo 
                $usuario->hashPassword(); // se llama la funcion que encripta la nueva contraseña
                $usuario->token = null; //Anula el token (ya no se puede volver a usar)

                $resultado = $usuario->guardar(); //Actualiza en la base de datos
                if ($resultado) {
                    header('Location: /');
                }

                // debuguear($usuario);
            }
        }
        //obtener las alertas
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error,
            'titulo' => 'Reestablecer Contraseña'
        ]);
    }

    //metodo para crear una nueva cuenta de usuario
    public static function crear(Router $router)
    {
        
        $alertas = [];
        $usuario = new Usuario; //crea una nueva instancia de usuario vacia 
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            //cargar los datos del formulario al objeto usuario
            $usuario->sincronizar($_POST);
            
            //validar que los datos cumplan con los requisitos 
            $alertas = $usuario->validar();

            //revisar que alerta este vacio 
            if (empty($alertas)) {
                //Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //hashear o encriptar el password
                    $usuario->hashPassword();
                    //generar token 
                    $usuario->crearToken();
                    //enviar el email de confirmacion de cuenta 
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //crear al usuario
                    $resultado = $usuario->guardar();

                    // debuguear($usuario);
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas,
            'titulo' => 'Crear Cuenta'
        ]);
    }
    //Método para mostrar mensaje de confirmación de cuenta
    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', [
            'titulo' => 'Confirma tu Cuenta'
        ]);
    }
    //confirma que exista el usuario por medio del token unico 
    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']); // sanitiza los datos entrantes para evitar inyecciones SQL

        $usuario = Usuario::where('token', $token); //Se manda a llamar la consulta desde activerecord.php
        if (empty($usuario)) {
            //Mostrar mensaje de error 
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            //Modificar a usuario confirmado 
            $usuario->confirmado = 1;
            // limpia el token una vez confirmado
            $usuario->token = '';
            //guarda los cambios en la BD
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }
        //Obtener alertas
        $alertas = Usuario::getAlertas();
        //Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas,
            'titulo' => 'Cuenta Confirmada'
        ]);
    }
}
