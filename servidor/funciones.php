<?php /** @noinspection PhpUnusedLocalVariableInspection */
/** @noinspection PhpRedundantOptionalArgumentInspection */
(session_status() === PHP_SESSION_NONE ? session_start() : ''); // Iniciar la sesión de PHP si no está iniciada
require 'dirs.php'; // Archivo que contiene las rutas de los directorios
require_once(CLASS_PATH . 'conexion.php'); // Archivo que contiene la clase Conexion
require_once(CLASS_PATH . 'auth.php'); // Archivo que contiene la clase Auth
require_once(SERVER_PATH . 'helpers.php'); // Archivo que contiene funciones auxiliares
require_once(SERVER_PATH . 'msg.php'); // Archivo que contiene los mensajes de alerta

$url = $_SERVER['REQUEST_URI']; // Obtener la URL actual

/**
 * Funciones de autenticación de usuarios
 * Requieren la instancia de la clase Auth
 */

// Función para logear al usuario
function logearUsuario($correo, $clave): bool
{
    $auth = obtenerAuth(); // Obtener la instancia de la clase Auth
    return $auth->logear_usuario($correo, $clave); // Llamar a la función logear_usuario de la clase Auth
}

if (isset($_POST['login_user'])) { // Si se ha enviado el formulario de inicio de sesión
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];
    $login = logearUsuario($correo, $clave); // Llamar a la función logearUsuario con los parámetros del formulario
}

// Función para registrar un usuario
function registrarUsuario($nombre, $apellido, $correo, $clave): bool
{
    $auth = obtenerAuth(); // Obtener la instancia de la clase Auth
    return $auth->registrar_usuario($nombre, $apellido, $correo, $clave); // Llamar a la función registrar_usuario de la clase Auth
}

if (isset($_POST['registrar_usuario'])) { // Si se ha enviado el formulario de registro
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];
    $registro = registrarUsuario($nombre, $apellido, $correo, $clave); // Llamar a la función registrarUsuario con los parámetros del formulario
}

// Función para verificar si el usuario está logeado
function verificarLogin(): bool
{
    return isset($_SESSION['usuario_id']); // isset(): determina si una variable está definida y no es NULL
}

if (verificarLogin()) {
    list($id_usuario, $nombre, $apellido, $correo) = obtenerDatosUsuario(); // Obtener los datos de la sesión y guardarlos en variables
}

// Función para cerrar la sesión
function cerrarSesion(): bool
{
    session_unset(); // Eliminar todas las variables de sesión
    session_destroy(); // Destruir la sesión
    return true;
}

if (isset($_POST['cerrar_sesion'])) {
    $_SESSION['logout_message'] = cerrarSesion() ? 1 : 2; // Llamar a la función cerrarSesion y guardar la alerta en la sesión
}

/**
 * Funciones para usuarios logeados
 */

// Función para redirigir a la página de inicio si el usuario está logeado
function redirigirSiLogeado(): void
{
    if (verificarLogin()) { // Si el usuario está logeado lo redirige a la página de inicio
        header('location: inicio.php');
        exit(); // Terminar la ejecución del script
    }
}

function obtenerDatosUsuario(): array
{
    $id_usuario = $_SESSION['usuario_id'];
    $nombre = $_SESSION['nombre'];
    $apellido = $_SESSION['apellido'];
    $correo = $_SESSION['correo'];
    return [$id_usuario, $nombre, $apellido, $correo]; // Retornar un array con los datos de la sesión
}
