<?php
date_default_timezone_set('America/Bogota');
error_reporting(E_ALL);

// Configuración de la sesión
session_name('SGP_SESSION');

session_start([
    'cookie_httponly' => true, // Previene acceso a la cookie desde JavaScript
    'cookie_secure' => isset($_SERVER['HTTPS']), // Solo envía cookies por HTTPS
    'use_strict_mode' => true, // Previene ataques de sesión por fijación
    'cookie_samesite' => 'Strict' // Mejora seguridad contra CSRF
]);

/**
 * Verifica si la sesión está activa y válida.
 * Si no lo está, destruye la sesión y muestra un mensaje.
 */
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sessionCheck() {
 
if (empty($_SESSION['loggedin']))
 {
        session_unset();
        session_destroy();
        header('X-Session-Status: Invalid');
        http_response_code(401);
        die("⚠️ Su sesión ha expirado o no es válida. Por favor, inicie sesión nuevamente.");
    }
    }


    // Opcional: Validar tiempo de inactividad
    $timeout = 1800; // 30 minutos
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        http_response_code(440); // Sesión expirada
        die("⏳ Su sesión ha expirado por inactividad.");
    }

    $_SESSION['last_activity'] = time(); // Actualiza el tiempo de actividad

?>
