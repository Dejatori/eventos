<?php
require_once 'helpers.php'; // Archivo que contiene los mensajes de alerta

/**
 * Funciones de la tabla eventos
 */

try {
    // Crear una instancia de la clase Conexion
    $pdo = obtenerConexion();

    // Obtener los registros de la tabla eventos
    $lista_eventos = $pdo->query('SELECT * FROM eventos');

    // Mover el registro a la tabla eventos_eliminados y eliminarlo de la tabla eventos
    if (isset($_GET['eventID'])) {
        $Eliminar = moverYEliminarEvento($pdo, $_GET, $_GET['eventID']);
        $_SESSION['event_message'] = ($Eliminar ? 4 : 1);
    }

    // Actualizar el registro en la tabla eventos
    if (isset($_POST['actualizar_evento'])) {
        $Actualizar = actualizarEvento($pdo, $_POST);
        $_SESSION['event_message'] = ($Actualizar ? 3 : 1);
    }

    // Agregar un registro a la tabla eventos
    if (isset($_POST['agregar_evento'])) {
        $Agregar = agregarEvento($pdo, $_POST);
        $_SESSION['event_message'] = ($Agregar ? 2 : 1);
    }
} catch (PDOException $e) {
    // Guardar mensaje de error en el archivo de logs
    logPDOException($e, 'Descripción de la excepción: ');
} finally {
    // Cerrar la conexión
    $pdo = null;
}

// Función para mover y eliminar un evento
function moverYEliminarEvento(PDO $pdo, array $getData, string $eventID): bool
{
    $eventID = $getData['eventID'];

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
function obtenerEventosConOrden($pdo, $ordenamiento)
{
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
    $pdo = obtenerConexion();

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
