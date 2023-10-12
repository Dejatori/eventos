<?php
require_once 'funciones.php'; // Archivo que contiene las funciones

// Verificar si el formulario ha sido enviado
if (isset($_POST["agregar_evento"])) {

    // Recuperar los datos del formulario
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $lugar = $_POST["lugar"];
    $fecha_hora = $_POST["fecha_hora"];

    // Conectar a la base de datos
    $conexion = obtenerConexion();
    
    // Preparar sentencia
    $sentencia = $conexion -> prepare("INSERT INTO eventos (Nombre_evento, Descripcion_Evento, Lugar, Fecha_Y_Hora) VALUES (:nombre, :descripcion, :lugar, :fecha_hora)");

    // Vincular parámetros
    $sentencia -> bindParam(':nombre', $nombre);
    $sentencia -> bindParam(':descripcion', $descripcion);
    $sentencia -> bindParam(':lugar', $lugar);
    $sentencia -> bindParam(':fecha_hora', $fecha_hora);

    // Ejecutar sentencia
    if ($sentencia->execute()) {
        mostrar_mensaje_evento($_SESSION['evento_message'] = 2);
    } else {
        mostrar_mensaje_evento($_SESSION['evento_message'] = 1);
    }
    header("location: ../novedades.php");
}

?>