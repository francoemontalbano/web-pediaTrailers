<?php
session_start();

// Verificar si el usuario inició sesión antes de intentar cerrarla
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Destruir la sesión y todas las variables de sesión
session_destroy();
$_SESSION = array();

// Deshabilitar la caché y evitar que el usuario pueda volver atrás
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Redirigir al usuario a la página de inicio de sesión
header("Location: login.php");
exit;
?>
