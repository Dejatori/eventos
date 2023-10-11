<?php
require_once 'funciones.php'; // Archivo que contiene las funciones

// Verificar si el formulario ha sido enviado
if (isset($_POST["agregar_evento"])) {

    // Recuperar los datos del formulario
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $lugar = $_POST["lugar"];
    $fecha_hora = $_POST["fecha_hora"];
    
    // Preparar sentencia
    $sentencia = $conexion -> prepare("INSERT INTO eventos (nombre, descripcion, lugar, fecha_hora) VALUES (:nombre, :descripcion, :lugar, :fecha_hora)");

    // Vincular parámetros
    $sentencia -> bindParam(':nombre', $nombre);
    $sentencia -> bindParam(':descripcion', $descripcion);
    $sentencia -> bindParam(':lugar', $lugar);
    $sentencia -> bindParam(':fecha_hora', $fecha_hora);

    // Ejecutar sentencia
    if ($sentencia->execute()) {
        return ($_SESSION["form_status"] == "success");
        header(location: "/eventos/novedades.php");
    } else {
        return ($_SESSION["form_status"] == "error");
        header(location: "/eventos/novedades.php");
    }
}

?>