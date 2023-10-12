<?php
require_once 'servidor/funciones.php'; // Archivo que contiene las funciones

// Instancia de conexión y autenticación
$conexion = new Conexion();
$Auth = new Auth($conexion);

// Verificar si el formulario ha sido enviado
if (isset($_POST["registrar_usuario"])) {

    // Recuperar los datos del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];

    // Insertar el nuevo usuario en la base de datos
    if ($Auth->registrar_usuario($nombre, $apellido, $correo, $clave)) {
        // Mensaje de se ha registrado un usuario exitosamente
        mostrar_mensaje_registro($_SESSION['register_message'] = 3);
    } else {
        // Mensaje de error al registrar un usuario
        mostrar_mensaje_registro($_SESSION['register_message'] = 1);
    }
    header("location: registrarse.php");
}