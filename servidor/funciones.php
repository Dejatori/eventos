<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/eventos/servidor/dirs.php";
require_once (CLASS_PATH . 'conexion.php');
require_once (CLASS_PATH . 'auth.php');

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