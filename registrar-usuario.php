<?php
require_once 'servidor/funciones.php'; // Archivo que contiene las funciones

// Instancia de conexión y autenticación
$conexion = new Conexion();
$Auth = new Auth($conexion);

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recuperar los datos del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];

    // Insertar el nuevo usuario en la base de datos
    if ($Auth->registrar_usuario($nombre, $apellido, $correo, $clave)) {
        // Mensaje de se ha registrado un usuario exitosamente
        $_SESSION['register_success'] = "<div class='alert alert-success'>
                                        <strong>Se ha registrado exitosamente su usuario.</strong>.
                                    </div>";
        // Redirigir al usuario a la página de registrarse
        header("location: registrarse.php");
        exit(); // Detener el script
    } else {
        // Mensaje de error al registrar un usuario
        $_SESSION['register_error'] = "<div class='alert alert-danger'>
                                        <strong>Ha ocurrido un error al registrar su usuario. Por favor, inténtelo de nuevo.</strong>.
                                    </div>";
        header("location: registrarse.php");
    }
}