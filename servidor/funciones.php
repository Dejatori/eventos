<?php
(session_status() === PHP_SESSION_NONE ? session_start() : ''); // Iniciar la sesión de PHP si no está iniciada
require_once $_SERVER['DOCUMENT_ROOT'] . "/eventos/servidor/dirs.php";
require_once (CLASS_PATH . 'conexion.php');
require_once (CLASS_PATH . 'auth.php');
require_once (SERVER_PATH . 'msg.php'); // Archivo que contiene los mensajes de error y éxito

$url = $_SERVER['REQUEST_URI']; // Obtener la URL actual

// Función para conectar a la base de datos
function obtenerConexion() {
    // Instancia de conexión y autenticación
    $conexion = new Conexion();
    $auth = new Auth($conexion);

    return [$conexion, $auth];
}

// Logear usuario
if (isset($_POST["login_user"])) {

    // Obtener las instancias de conexión y autenticación
    list($conexion, $auth) = obtenerConexion();

    // Obtener los valores del formulario
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];

    // Intentar logear al usuario
    $login = $auth->logear_usuario($correo, $clave);
}

// Función para registrar un usuario
if (isset($_POST["registrar_usuario"])) {

    // Obtener las instancias de conexión y autenticación
    list($conexion, $auth) = obtenerConexion();

    // Obtener los valores del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];

    // Intentar insertar el nuevo usuario en la base de datos
    $registro = $auth->registrar_usuario($nombre, $apellido, $correo, $clave);
}

// Función para cerrar la sesión
function cerrarSesion() : bool
{
    session_unset();
    session_destroy();
    return true;
}

if (isset($_POST['cerrar_sesion'])) {
    $_SESSION['logout_message'] = cerrarSesion() ? 1 : 2;
}

// Función para verificar si el usuario está logeado
function verificarLogin(): bool
{
    return isset($_SESSION['usuario_id']); // Devuelve true si el usuario está logeado
}

function volverIndex(): void
{
    if (verificarLogin()) {
        header('location: inicio.php');
        exit();
    }
}

// Función para obtener los datos de la sesión
function datosUsuario(): array
{
    $id_usuario = $_SESSION['usuario_id'];
    $nombre = $_SESSION['nombre'];
    $apellido = $_SESSION['apellido'];
    $correo = $_SESSION['correo'];
    return array($id_usuario, $nombre, $apellido, $correo);
}

if (isset($_SESSION['usuario_id'])) {
    list($id_usuario, $nombre, $apellido, $correo) = datosUsuario();
}

/**
 * Funciones de la tabla eventos
 */

// Función para mover y elimar un evento
function moverYEliminarEvento(PDO $pdo, array $getData, string $ID_evento) : bool
{
    $eventID = $getData['eventID'] ?? die("No se ha encontrado el ID del evento");

    // Obtener los datos del evento antes de eliminarlo
    $sqlObtenerEvento = $pdo->prepare("SELECT * FROM eventos WHERE ID_Evento = :ID_evento");
    $sqlObtenerEvento->bindParam(':ID_evento', $eventID, PDO::PARAM_INT);
    $sqlObtenerEvento->execute();
    $evento = $sqlObtenerEvento->fetch(PDO::FETCH_ASSOC);

    // Mover el evento a la tabla eventos_eliminados
    $sqlMoverEvento = $pdo->prepare("INSERT INTO eventos_eliminados (ID_Evento, Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora) VALUES (:ID_evento, :nombre, :descripcion, :lugar, :fecha_hora)");
    $sqlMoverEvento->bindParam(':ID_evento', $evento['ID_Evento'], PDO::PARAM_INT);
    $sqlMoverEvento->bindParam(':nombre', $evento['Nombre_Evento'], PDO::PARAM_STR);
    $sqlMoverEvento->bindParam(':descripcion', $evento['Descripcion_Evento'], PDO::PARAM_STR);
    $sqlMoverEvento->bindParam(':lugar', $evento['Lugar'], PDO::PARAM_STR);
    $sqlMoverEvento->bindParam(':fecha_hora', $evento['Fecha_Y_Hora'], PDO::PARAM_STR);
    $sqlMoverEvento->execute();

    // Eliminar el evento de la tabla eventos
    $sqlEliminarEvento = $pdo->prepare("DELETE FROM eventos WHERE ID_Evento = :ID_evento");
    $sqlEliminarEvento->bindParam(':ID_evento', $eventID, PDO::PARAM_INT);

    // Ejecutar la sentencia
    $sqlEliminarEvento->execute();
    return (bool)$sqlEliminarEvento;
}

// Función para actualizar un evento
function actualizarEvento(PDO $pdo, array $postData) : bool
{   
    // Obtener los valores del formulario
    $editID_evento = $postData['editID_evento'];
    $editNombre = $postData['editNombre'];
    $editDescripcion = $postData['editDescripcion'];
    $editLugar = $postData['editLugar'];
    $editFecha_hora = $postData['editFecha_hora'];

    // Preparar la sentencia
    $sqlActualizarEvento = $pdo->prepare("UPDATE eventos SET Nombre_evento = :nombre, Descripcion_Evento = :descripcion, Lugar = :lugar, Fecha_Y_Hora = :fecha_hora WHERE ID_Evento = :ID_evento");

    // Vincular los parámetros
    $sqlActualizarEvento->bindParam(':ID_evento', $editID_evento, PDO::PARAM_INT);
    $sqlActualizarEvento->bindParam(':nombre', $editNombre, PDO::PARAM_STR);
    $sqlActualizarEvento->bindParam(':descripcion', $editDescripcion, PDO::PARAM_STR);
    $sqlActualizarEvento->bindParam(':lugar', $editLugar, PDO::PARAM_STR);
    $sqlActualizarEvento->bindParam(':fecha_hora', $editFecha_hora, PDO::PARAM_STR);

    // Ejecutar la sentencia
    $sqlActualizarEvento->execute();
    return (bool)$sqlActualizarEvento;
}

// Función para agregar un evento
function agregarEvento(PDO $pdo, array $postData) : bool
{
    // Obtener los valores del formulario
    $nombre = $postData['nombre'];
    $descripcion = $postData['descripcion'];
    $lugar = $postData['lugar'];
    $fecha_hora = $postData['fecha_hora'];

    // Preparar la sentencia
    $sqlAgregarEvento = $pdo->prepare("INSERT INTO eventos (Nombre_evento, Descripcion_Evento, Lugar, Fecha_Y_Hora) VALUES (:nombre, :descripcion, :lugar, :fecha_hora)");

    // Vincular los parámetros
    $sqlAgregarEvento->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $sqlAgregarEvento->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $sqlAgregarEvento->bindParam(':lugar', $lugar, PDO::PARAM_STR);
    $sqlAgregarEvento->bindParam(':fecha_hora', $fecha_hora, PDO::PARAM_STR);

    // Ejecutar la sentencia
    $sqlAgregarEvento->execute();
    return (bool)$sqlAgregarEvento;
}

?>