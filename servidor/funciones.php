<?php
(session_status() === PHP_SESSION_NONE ? session_start() : ''); // Iniciar la sesión de PHP si no está iniciada
require_once 'dirs.php'; // Archivo que contiene las rutas de los directorios
require_once (CLASS_PATH . 'conexion.php'); // Archivo que contiene la clase Conexion
require_once (CLASS_PATH . 'auth.php'); // Archivo que contiene la clase Auth
require_once (SERVER_PATH . 'msg.php'); // Archivo que contiene los mensajes de alerta

$url = $_SERVER['REQUEST_URI']; // Obtener la URL actual

/**
 * Funciones de autenticación de usuarios
 * Requieren la instancia de la clase Auth
 */

// Función para obtener la instancia de conexión y autenticación
function obtenerConexionYAuth(): array
{
    $conexion = new Conexion(); // Crear una instancia de la clase Conexion
    $auth = new Auth($conexion); // Crear una instancia de la clase Auth
    return [$conexion, $auth]; // Retornar un array con la instancia de conexión y autenticación
}

// Función para logear al usuario
function logearUsuario($correo, $clave) : bool
{
    list($conexion, $auth) = obtenerConexionYAuth(); // Obtener la instancia de conexión y autenticación
    return $auth->logear_usuario($correo, $clave); // Llamar a la función logear_usuario de la clase Auth
}

// Función para registrar un usuario
function registrarUsuario($nombre, $apellido, $correo, $clave) : bool
{
    list($conexion, $auth) = obtenerConexionYAuth(); // Obtener la instancia de conexión y autenticación
    return $auth->registrar_usuario($nombre, $apellido, $correo, $clave); // Llamar a la función registrar_usuario de la clase Auth
}

// Función para cerrar la sesión
function cerrarSesion() : bool
{
    session_unset(); // Eliminar todas las variables de sesión
    session_destroy(); // Destruir la sesión
    return true;
}

// Función para verificar si el usuario está logeado
function verificarLogin(): bool
{
    return isset($_SESSION['usuario_id']); // isset(): determina si una variable está definida y no es NULL
}

// Función para redirigir a la página de inicio si el usuario está logeado
function redirigirSiLogeado(): void
{
    if (verificarLogin()) { // Si el usuario está logeado lo redirige a la página de inicio
        header('location: inicio.php');
        exit(); // Terminar la ejecución del script
    }
}

// Función para obtener los datos de la sesión
function obtenerDatosUsuario(): array
{
    $id_usuario = $_SESSION['usuario_id'];
    $nombre = $_SESSION['nombre'];
    $apellido = $_SESSION['apellido'];
    $correo = $_SESSION['correo'];
    return [$id_usuario, $nombre, $apellido, $correo]; // Retornar un array con los datos de la sesión
}

if (isset($_POST['login_user'])) { // Si se ha enviado el formulario de inicio de sesión
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];
    $login = logearUsuario($correo, $clave); // Llamar a la función logearUsuario con los parámetros del formulario
}

if (isset($_POST['registrar_usuario'])) { // Si se ha enviado el formulario de registro
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];
    $registro = registrarUsuario($nombre, $apellido, $correo, $clave); // Llamar a la función registrarUsuario con los parámetros del formulario
}

if (isset($_POST['cerrar_sesion'])) {
    $_SESSION['logout_message'] = cerrarSesion() ? 1 : 2; // Llamar a la función cerrarSesion y guardar la alerta en la sesión
}

if (verificarLogin()) {
    list($id_usuario, $nombre, $apellido, $correo) = obtenerDatosUsuario(); // Obtener los datos de la sesión y guardarlos en variables
}

/**
 * Funciones de la tabla eventos
 */

// Función para mover y eliminar un evento
function moverYEliminarEvento(PDO $pdo, array $getData, string $eventID): bool
{
    $eventID = $getData['eventID'] ?? die('No se ha encontrado el ID del evento');

    // Combinar las consultas en una transacción para mayor eficiencia
    try {
        $pdo->beginTransaction();

        // Obtener los datos del evento antes de eliminarlo
        $sqlObtenerEvento = $pdo->prepare('SELECT * FROM eventos WHERE ID_Evento = :ID_evento');
        $sqlObtenerEvento->bindParam(':ID_evento', $eventID, PDO::PARAM_INT);
        $sqlObtenerEvento->execute();
        $evento = $sqlObtenerEvento->fetch(PDO::FETCH_ASSOC);

        // Mover el evento a la tabla eventos_eliminados
        $sqlMoverEvento = $pdo->prepare('INSERT INTO eventos_eliminados (ID_Evento, Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora) VALUES (:ID_evento, :nombre, :descripcion, :lugar, :fecha_hora)');
        $sqlMoverEvento->execute([
            ':ID_evento' => $evento['ID_Evento'],
            ':nombre' => $evento['Nombre_Evento'],
            ':descripcion' => $evento['Descripcion_Evento'],
            ':lugar' => $evento['Lugar'],
            ':fecha_hora' => $evento['Fecha_Y_Hora'],
        ]);

        // Eliminar el evento de la tabla eventos
        $sqlEliminarEvento = $pdo->prepare('DELETE FROM eventos WHERE ID_Evento = :ID_evento');
        $sqlEliminarEvento->bindParam(':ID_evento', $eventID, PDO::PARAM_INT);
        $sqlEliminarEvento->execute();

        // Confirmar la transacción
        $pdo->commit();
        return true;
    } catch (Exception) {
        // En caso de error, deshacer la transacción
        $pdo->rollBack();
        return false;
    }
}

// Función para actualizar un evento
function actualizarEvento(PDO $pdo, array $postData): bool
{
    // Preparar la sentencia
    $sqlActualizarEvento = $pdo->prepare('UPDATE eventos SET Nombre_evento = :nombre, Descripcion_Evento = :descripcion, Lugar = :lugar, Fecha_Y_Hora = :fecha_hora WHERE ID_Evento = :ID_evento');

    // Vincular los parámetros
    $sqlActualizarEvento->bindParam(':ID_evento', $postData['editID_evento'], PDO::PARAM_INT);
    $sqlActualizarEvento->bindParam(':nombre', $postData['editNombre'], PDO::PARAM_STR);
    $sqlActualizarEvento->bindParam(':descripcion', $postData['editDescripcion'], PDO::PARAM_STR);
    $sqlActualizarEvento->bindParam(':lugar', $postData['editLugar'], PDO::PARAM_STR);
    $sqlActualizarEvento->bindParam(':fecha_hora', $postData['editFecha_hora'], PDO::PARAM_STR);

    // Ejecutar la sentencia y retornar el resultado
    return $sqlActualizarEvento->execute();
}

// Función para agregar un evento
function agregarEvento(PDO $pdo, array $postData): bool
{
    // Preparar la sentencia y vincular los parámetros
    $sqlAgregarEvento = $pdo->prepare('INSERT INTO eventos (Nombre_evento, Descripcion_Evento, Lugar, Fecha_Y_Hora) VALUES (:nombre, :descripcion, :lugar, :fecha_hora)');
    $sqlAgregarEvento->bindParam(':nombre', $postData['nombre'], PDO::PARAM_STR);
    $sqlAgregarEvento->bindParam(':descripcion', $postData['descripcion'], PDO::PARAM_STR);
    $sqlAgregarEvento->bindParam(':lugar', $postData['lugar'], PDO::PARAM_STR);
    $sqlAgregarEvento->bindParam(':fecha_hora', $postData['fecha_hora'], PDO::PARAM_STR);

    // Ejecutar la sentencia y retornar el resultado
    return $sqlAgregarEvento->execute();
}

/**
 * Debido a que MySQL hace un ordenamiento lexicográfico algunos valores como eventos con números en el nombre no se ordenan correctamente
 * Por ejemplo: Evento1, luego Evento10 y luego Evento2. Para solucionar esto se utiliza una función de comparación personalizada
 * Esta función de comparación se utiliza en la función usort() para ordenar los eventos
 * Más información sobre usort(): https://www.php.net/manual/es/function.usort.php
 * Más información sobre strnatcasecmp(): https://www.php.net/manual/es/function.strnatcasecmp.php
 */

// Función de comparación para ordenar alfabéticamente, considerando números en cualquier posición
function customSort($a, $b): int
{
    // Comparar sin distinguir mayúsculas y minúsculas
    $a = strtolower($a);
    $b = strtolower($b);

    // Comparar los valores numéricos
    return strnatcasecmp($a, $b);
}

// Función para obtener los eventos con ordenamiento personalizado
function obtenerEventosConOrden($pdo, $ordenamiento) {
    // Validar que $ordenamiento sea una opción válida
    $ordenamientos = [
        'ID_Evento ASC',
        'Nombre_Evento ASC',
        'Descripcion_Evento ASC',
        'Fecha_De_Registro DESC',
        'Lugar ASC',
        'Fecha_Y_Hora DESC',
    ];

    // Verificar si $ordenamiento es una opción válida
    if (!in_array($ordenamiento, $ordenamientos)) {
        // Si no es una opción válida, usar ordenamiento predeterminado
        $ordenamiento = 'ID_Evento ASC';
    }

    // Separar el campo y la dirección
    list($campo, $direccion) = explode(' ', $ordenamiento);

    // Consulta SQL para obtener los eventos con el ordenamiento
    $sql = "SELECT * FROM eventos ORDER BY $campo $direccion";

    // Ejecutar la consulta SQL y obtener todos los registros
    $stmt = $pdo->query($sql);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si los eventos son válidos antes de ordenar
    if (!empty($eventos)) {
        // Ordenar los eventos utilizando la función de comparación personalizada
        usort($eventos, function ($a, $b) use ($campo, $direccion) {
            $columnA = $a[$campo];
            $columnB = $b[$campo];

            // Aplicar ordenamiento personalizado para todas las columnas
            return customSort($columnA, $columnB);
        });
    }

    return $eventos;
}

// Función para generar el HTML de la tabla de eventos
function generarTablaEventos($lista_eventos): false|string
{
    ob_start(); // Iniciar almacenamiento en búfer de salida

    if (!empty($lista_eventos)) {
        foreach ($lista_eventos as $row) {
            echo '<tr>';
            echo '<td class="text-center">' . $row['ID_Evento'] . '</td>';
            echo '<td class="td-scroll">' . $row['Nombre_Evento'] . '</td>';
            echo '<td class="td-scroll">' . $row['Descripcion_Evento'] . '</td>';
            echo '<td>' . date('d/m/y H:i', strtotime($row['Fecha_De_Registro'])) . '</td>';
            echo '<td class="td-scroll">' . $row['Lugar'] . '</td>';
            echo '<td>' . date('d/m/y H:i', strtotime($row['Fecha_Y_Hora'])) . '</td>';
            echo '<td>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-id="' . $row['ID_Evento'] . '">Editar</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal" data-id="' . $row['ID_Evento'] . '">Eliminar</button>
                </td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='6'>No hay registros</td></tr>";
    }

    // Obtener el contenido del búfer y limpiarlo
    return ob_get_clean(); // Devolver el HTML de la tabla
}

// Procesar solicitud AJAX para ordenar eventos
if (isset($_POST['ordenarEventos'])) {
    $ordenarEventos = $_POST['ordenarEventos'];

    // Crear una instancia de la clase Conexion
    $conexion = new Conexion();
    $pdo = $conexion->conectar();

    // Array de ordenamientos personalizados
    $ordenamientos = [
        'id-asc' => 'ID_Evento ASC',
        'nombre-asc' => 'Nombre_Evento ASC',
        'descripcion-asc' => 'Descripcion_Evento ASC',
        'fecha-registro-desc' => 'Fecha_De_Registro DESC',
        'lugar-asc' => 'Lugar ASC',
        'fecha-hora-desc' => 'Fecha_Y_Hora DESC',
    ];

    // Verificar si el criterio seleccionado existe en el array de ordenamientos
    $ordenamiento = $ordenamientos[$ordenarEventos] ?? 'ID_Evento ASC';

    // Obtener los eventos con ordenamiento personalizado
    $eventos = obtenerEventosConOrden($pdo, $ordenamiento);

    // Generar el HTML de la tabla y enviarlo como respuesta AJAX
    $tablaHTML = generarTablaEventos($eventos);
    echo $tablaHTML;
    exit(); // Terminar la ejecución después de la respuesta AJAX
}

/**
 * Función para registrar los PDOException en el archivo de log
 */

function logPDOException($e, $message): void {
    // Obtener la fecha y hora actual en la zona horaria deseada
    $currentDateTime = date('d-m-Y H:i:s', strtotime('now -7 hours'));
    // Crear el mensaje de registro
    $logMessage = "[$currentDateTime] $message " . $e->getMessage() . PHP_EOL . $e . PHP_EOL;
    // Registrar el mensaje en el archivo de log
    error_log($logMessage, 3, 'logs/error.log');
}
