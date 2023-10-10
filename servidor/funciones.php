<?php
require_once 'clases/conexion.php'; // Archivo que contiene la configuración de la base de datos
require_once 'clases/auth.php'; // Archivo que contiene la clase Auth

// Función para conectar a la base de datos
function obtenerConexion(): PDO
{
    $pdo = new Conexion();
    return $pdo->conectar();
}

// Función para cerrar la sesión
function cerrarSesion(): void
{
    session_unset();
    session_destroy();
}

$url = $_SERVER['REQUEST_URI']; // Obtener la URL actual

?>