<?php
// Incluir las funciones
require_once 'servidor/funciones.php'; // Archivo que contiene las funciones

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