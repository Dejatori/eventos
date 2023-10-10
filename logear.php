<?php
(session_status() === PHP_SESSION_NONE ? session_start() : ''); // Iniciar la sesión de PHP si no está iniciada

// Incluir archivo de conexión, clase Auth y archivo de funciones
require_once 'clases/conexion.php';
require_once 'clases/auth.php';

// Instancia de conexión y autenticación
$conexion = new Conexion();
$Auth = new Auth($conexion);

$correo = $_POST['correo'];
$clave = $_POST['clave'];

// Función para crear una cookie
if (isset($_POST['correo']) && isset($_POST['clave'])) {
    
    if ($Auth->logear_usuario($correo, $clave)) {
        header('Location: novedades.php');
    } else {
        $_SESSION['login_error'] = "<div class='alert alert-danger'>
                                        <strong>El correo electrónico o la contraseña son incorrectos. Por favor, inténtalo de nuevo.</strong>.
                                    </div>";
        header('Location: iniciar-sesion.php');
    }
} else {
    $_SESSION['login_error'] = "<div class='alert alert-danger'>
                                    <strong>El correo electrónico o la contraseña son incorrectos. Por favor, inténtalo de nuevo.</strong>.
                                </div>";
    header('Location: iniciar-sesion.php');
}