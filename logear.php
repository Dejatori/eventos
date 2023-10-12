<?php
// Incluir las funciones
require_once 'servidor/funciones.php'; // Archivo que contiene las funciones

// Instancia de conexión y autenticación
$conexion = new Conexion();
$Auth = new Auth($conexion);

$correo = $_POST['correo'];
$clave = $_POST['clave'];

// Función para logear al usuario
if (isset($_POST['correo']) && isset($_POST['clave'])) {
    
    if ($Auth->logear_usuario($correo, $clave)) {
        $_SESSION['login_message'] = 3;
        header('Location: index.php');
    } else {
        $_SESSION['login_message'] = 1;
        header('Location: iniciar-sesion.php');
    }
} else {
    $_SESSION['login_message'] = 2;
    header('Location: iniciar-sesion.php');
}
?>