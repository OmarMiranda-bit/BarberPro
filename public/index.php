<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\APIController;
use Controllers\BarberosController;
use Controllers\CitasController;
use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\PaginasController;
use Controllers\RegistradosController;
use Controllers\ReservarController;
// use Controllers\ServicioController;
use Controllers\ServiciosController;
use MVC\Router;

$router = new Router();

//Pagina principal 
$router->get('/', [PaginasController::class, 'index']);
$router->get('/galeria', [PaginasController::class, 'galeria']);
$router->get('/services', [PaginasController::class, 'services']);
$router->get('/equipo', [PaginasController::class, 'equipo']);
$router->get('/contacto', [PaginasController::class, 'contacto']);
$router->post('/contacto', [PaginasController::class, 'contacto']);

//Pagina para reservar 
$router->get('/reservar',[ReservarController::class, 'index']);
$router->get('/reservar/cita',[ReservarController::class, 'cita']);
$router->get('/reservar/cita/detalle',[ReservarController::class, 'detalle']);
$router->get('/reservar/cita/editar', [ReservarController::class, 'editar']);

//iniciar sesion 
$router->get('/login',[LoginController::class, 'login']);
$router->post('/login',[LoginController::class, 'login']);
$router->post('/api/login',[LoginController::class, 'loginAPI']); // API para login desde modal
$router->post('/api/verificar-cita', [LoginController::class, 'verificarCitaAPI']); // API para login de detalle
//Para cerra sesion
$router->post('/logout',[LoginController::class, 'logout']);

//Recuperar password
$router->get('/olvide',[LoginController::class, 'olvide']);
$router->post('/olvide',[LoginController::class, 'olvide']);
$router->get('/recuperar',[LoginController::class, 'recuperar']);
$router->post('/recuperar',[LoginController::class, 'recuperar']);

//Crear cuenta
$router->get('/crear-cuenta',[LoginController::class, 'crear']);
$router->post('/crear-cuenta',[LoginController::class, 'crear']);

//confirmar cuenta 
$router->get('/confirmar-cuenta',[LoginController::class, 'confirmar']);

//Mensaje de exito
$router->get('/mensaje',[LoginController::class, 'mensaje']);

//-----------------------AREA PRIVADA - ADMINISTRADOR-----------------------------
$router->get('/admin/dashboard', [DashboardController::class, 'index']);
$router->get('/admin/dashboard/citas', [CitasController::class, 'index']);
//enlaces para gestionar los barberos 
$router->get('/admin/dashboard/barberos', [BarberosController::class, 'index']);
$router->get('/admin/dashboard/barberos/crear', [BarberosController::class, 'crear']);
$router->post('/admin/dashboard/barberos/crear', [BarberosController::class, 'crear']);
$router->get('/admin/dashboard/barberos/editar', [BarberosController::class, 'editar']);
$router->post('/admin/dashboard/barberos/editar', [BarberosController::class, 'editar']);
$router->post('/admin/dashboard/barberos/eliminar', [BarberosController::class, 'eliminar']);

//enlaces para gestionar los servicios
$router->get('/admin/dashboard/servicios', [ServiciosController::class, 'index']);
$router->get('/admin/dashboard/servicios/crear', [ServiciosController::class, 'crear']);
$router->post('/admin/dashboard/servicios/crear', [ServiciosController::class, 'crear']);
$router->get('/admin/dashboard/servicios/editar', [ServiciosController::class, 'editar']);
$router->post('/admin/dashboard/servicios/editar', [ServiciosController::class, 'editar']);
$router->post('/admin/dashboard/servicios/eliminar', [ServiciosController::class, 'eliminar']);

//enlaces para gestionar los registrados
$router->get('/admin/dashboard/registrados', [RegistradosController::class, 'index']);


//---------------------- Administracion de citas de empleados ---------------------
$router->get('/admin/employees',[AdminController::class, 'index']);
$router->get('/admin/employees/disponibilidad',[AdminController::class, 'disponibilidad']);

//---------------------API de las citas--------------- 
//API de Citas para los clientes (reservar cita desde el frontend)
$router->get('/api/servicios', [APIController::class, 'index']);
$router->get('/api/barberos', [APIController::class, 'obtenerBarberos']);
$router->get('/api/horarios', [APIController::class, 'obtenerHorariosDisponibles']);
$router->post('/api/citas', [APIController::class, 'guardar']);
$router->post('/api/citas/actualizar', [APIController::class, 'actualizar']);
$router->post('/api/citas/cancelar', [APIController::class, 'cancelar']);

// para el modulo de los barberos 
$router->post('/api/no-asistio', [APIController::class, 'noAsistio']);
$router->post('/api/citas/actualizar-estado', [APIController::class, 'actualizarEstado']);

// Rutas de API para disponibilidad de los horarios de los empleados (barberos)
$router->post('/api/disponibilidad/guardar', [APIController::class, 'guardarHorarios']);
$router->post('/api/disponibilidad/bloquear', [APIController::class, 'bloquearDia']);

//CRUD de servicios (Codigo comentado porque se movio a ServiciosController.php)
// $router->get('/servicios',[ServicioController::class, 'index']);
// $router->get('/servicios/crear',[ServicioController::class, 'crear']);
// $router->post('/servicios/crear',[ServicioController::class, 'crear']);
// $router->get('/servicios/actualizar',[ServicioController::class, 'actualizar']);
// $router->post('/servicios/actualizar',[ServicioController::class, 'actualizar']);
// $router->post('/servicios/eliminar',[ServicioController::class, 'eliminar']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();