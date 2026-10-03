<?php

function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $actual, string $proximo): bool
{
    if ($actual !== $proximo) {
        return true;
    } else {
        return false;
    }
}

// Cuando se selecciona el enlace se muestra la clase y se resalta el enlace actual
function pagina_actual($path): bool
{
    $url_actual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    // Para el Inicio/Dashboard exige coincidencia exacta para que no marque "Inicio" en subrutas
    if ($path === '/dashboard' || $path === '/admin/dashboard') {
        return $url_actual === '/admin/dashboard' || $url_actual === '/dashboard';
    }
    return str_contains($url_actual, $path);
}

// Verifica autenticación de usuario
function isAuth(): bool
{
    if (!isset($_SESSION)) {
        session_start();
    }
    // Buscamos la llave 'login' que creamos en el Controller
    return isset($_SESSION['login']) && $_SESSION['login'] === true;
    // return isset($_SESSION['nombre']) && !empty($_SESSION);
}


// Verifica si es empleado
function isAdmin(): bool
{
    if (!isset($_SESSION)) {
        session_start();
    }
    // Primero: ¿Está logueado? Segundo: ¿Es admin?
    return isAuth() && isset($_SESSION['admin']) && $_SESSION['admin'] == "2";
    // return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}
//verificar si es super admin
function isSuperAdmin(): bool
{
    if (!isset($_SESSION)) {
        session_start();
    }

    // return isset($_SESSION['super_admin']) && !empty($_SESSION['super_admin']);
    // Primero: ¿Está logueado? Segundo: ¿Es super_admin?
    return isAuth() && isset($_SESSION['super_admin']) && $_SESSION['super_admin'] === true;
}


// Verifica si hay usuario autenticado sin redirigir
function hayUsuarioLogueado(): bool
{
    if (!isset($_SESSION)) {
        session_start();
    }

    return isset($_SESSION['login']) && $_SESSION['login'];
}

//Para formatear la fecha en español
function formatearFecha($fecha): string
{
    $dias = [
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo'
    ];

    $meses = [
        'January' => 'enero',
        'February' => 'febrero',
        'March' => 'marzo',
        'April' => 'abril',
        'May' => 'mayo',
        'June' => 'junio',
        'July' => 'julio',
        'August' => 'agosto',
        'September' => 'septiembre',
        'October' => 'octubre',
        'November' => 'noviembre',
        'December' => 'diciembre'
    ];

    $fechaObj = new DateTime($fecha);

    $diaSemana = $dias[$fechaObj->format('l')];
    $dia = $fechaObj->format('j');
    $mes = $meses[$fechaObj->format('F')];
    $año = $fechaObj->format('Y');

    return "{$diaSemana}, {$dia} de {$mes} de {$año}";
}

function formatearHora($hora): string
{
    // Convertir la hora a objeto DateTime
    $horaObj = DateTime::createFromFormat('H:i:s', $hora);

    // Si solo viene HH:MM sin segundos
    if (!$horaObj) {
        $horaObj = DateTime::createFromFormat('H:i', $hora);
    }

    // Obtener hora en formato 12 horas
    $hora12 = $horaObj->format('g:i'); // g = hora sin ceros a la izquierda, i = minutos
    $periodo = $horaObj->format('A'); // A = AM/PM

    // Traducir AM/PM al español (opcional)
    $periodoEspanol = ($periodo === 'AM') ? 'a.m.' : 'p.m.';

    return "{$hora12} {$periodoEspanol}";
}
