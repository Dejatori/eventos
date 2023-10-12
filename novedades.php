<?php
require_once 'servidor/funciones.php'; // Archivo que contiene las funciones

$logeado = verificarLogin();

// Redirigir al usuario a index.php si cerró sesión despues de 3 segundos
if (!empty($_SESSION['logout_message'])) {
    echo '<script>
            setTimeout(function () {
                window.location.href = "/eventos/index.php";
            }, 3000);
          </script>';
}

// Redireccionar al error 403 si el usuario no está logeado
if ($logeado == false && empty($_SESSION['logout_message'])) {
    header('Location: 403.html');
}

try {
    // Crear una instancia de la clase Conexion
    $conexion = new Conexion();
    $pdo = $conexion->conectar();
    
    // Obtener los registros de la tabla eventos
    $lista_eventos = $pdo->query("SELECT * FROM eventos");

    // Mover el registro a la tabla eventos_eliminados y eliminarlo de la tabla eventos
    if (isset($_GET['eventID'])) {
        $Eliminar = moverYeliminarEvento($pdo, $_GET, $_GET['eventID']);

        $_SESSION['event_message'] = ($Eliminar ? 4 : 1);
    }

    // Actualizar el registro en la tabla eventos
    if (isset($_POST['actualizar_evento'])) {
        $Actualizar = actualizarEvento($pdo, $_POST);

        $_SESSION['event_message'] = ($Actualizar ? 3 : 1);
    }

    // Agregar un registro a la tabla eventos
    if (isset($_POST["agregar_evento"])) {
        $Agregar = agregarEvento($pdo, $_POST);

        $_SESSION['event_message'] = ($Agregar ? 2 : 1);
    }
} catch (PDOException $e) {
    // Mostrar mensaje de error con un echo
    echo $e->getMessage();
} finally {
    // Cerrar la conexión
    $conexion = null;
}
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Novedades - ADSO</title>
    <meta name="description" content="Clase del 09 de Octubre del 2023">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel='stylesheet' href='assets/fonts/fontawesome-all.min.css'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Serif+Dogra&amp;display=swap">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body id="page-top">

<?php require_once 'templates/navbar.php'; ?>
                <main>
                    <section>
                        <div class="container">
                        <h1 class="mt-4 mb-4 text-uppercase" style="font-family: 'Noto Serif Dogra', serif;font-size: 40px;font-weight: bold;">Tabla de eventos</h1>
                        <a type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#registerModal">Agregar evento</a>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 text-nowrap">
                                        <div id="eventTable_length" class="eventTables_length" aria-controls="eventTable">
                                            <label class="form-label">Filtrar por
                                                <select class="d-inline-block form-select form-select-sm">
                                                    <option>Ordenar por</option>
                                                    <option value="1">ID-Evento</option>
                                                    <option value="2">Nombre</option>
                                                    <option value="3">Descripción</option>
                                                    <option value="4">Fecha de registro</option>
                                                    <option value="5">Lugar</option>
                                                    <option value="6">Fecha y Hora</option>
                                                </select>&nbsp;
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-md-end eventTables_filter" id="eventTable_filter">
                                            <label class="form-label">
                                                <input type="search" class="form-control form-control-sm" aria-controls="eventTable" placeholder="Buscar">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive table mt-2" id="eventTable" role="grid" aria-describedby="eventTable_info">
                            <table class="table my-0" id="eventTable">
                                <thead>
                                    <tr>
                                        <th scope="col">ID-Evento</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Fecha de registro</th>
                                        <th scope="col">Lugar</th>
                                        <th scope="col">Fecha y Hora</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Mostrar los registros en la tabla
                                    if ($lista_eventos->rowCount() > 0) {
                                        while ($row = $lista_eventos->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td>" . $row["ID_Evento"] . "</td>";
                                            echo "<td>" . $row["Nombre_Evento"] . "</td>";
                                            echo "<td>" . $row["Descripcion_Evento"] . "</td>";
                                            echo "<td>" . $row["Fecha_De_Registro"] . "</td>";
                                            echo "<td>" . $row["Lugar"] . "</td>";
                                            echo "<td>" . $row["Fecha_Y_Hora"] . "</td>";
                                            echo "<td>
                                                    <button type='button' class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#updateModal' data-id='" . $row["ID_Evento"] . "'>Editar</button>
                                                    <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#confirmModal' data-id='" . $row["ID_Evento"] . "'>Eliminar</button>
                                                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No hay registros</td></tr>";
                                    }
                                    // Cerrar la conexión
                                    $conexion = null;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <section>
                    <!-- Modal de registro de evento -->
                        <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="registerModalLabel">Agregar evento</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre del evento</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="descripcion" class="form-label">Descripción del evento</label>
                                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="lugar" class="form-label">Lugar del evento</label>
                                                <input type="text" class="form-control" id="lugar" name="lugar" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_hora" class="form-label">Fecha y hora del evento</label>
                                                <input type="datetime-local" class="form-control" id="fecha_hora" name="fecha_hora" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary" name="agregar_evento">Agregar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de edición de evento -->
                        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateModalLabel">Editar evento</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST">
                                            <input type="hidden" id="editID_evento" name="editID_evento" value="">                                            
                                            <div class="mb-3">
                                                <label for="editNombre" class="form-label">Nombre del evento</label>
                                                <input type="text" class="form-control" id="editNombre" name="editNombre" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editDescripcion" class="form-label">Descripción del evento</label>
                                                <textarea class="form-control" id="editDescripcion" name="editDescripcion" rows="3" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editLugar" class="form-label">Lugar del evento</label>
                                                <input type="text" class="form-control" id="editLugar" name="editLugar" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editFecha_hora" class="form-label">Fecha y hora del evento</label>
                                                <input type="datetime-local" class="form-control" id="editFecha_hora" name="editFecha_hora" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary" name="actualizar_evento">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de éxito -->
                        <div class='modal fade' id='successModal' tabindex='-1' role='dialog' aria-labelledby='successModalLabel'
                            aria-hidden='true'>
                            <div class='modal-dialog modal-dialog-centered'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title text-success' id='successModalLabel'>Éxito</span></h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body fw-bold'>
                                        <p>¡Se ha agregado el evento! Se recargará la página en 5 segundos.</p>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de error -->
                        <div class='modal fade' id='errorModal' tabindex='-1' role='dialog' aria-labelledby='errorModalLabel'
                            aria-hidden='true'>
                            <div class='modal-dialog modal-dialog-centered'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title text-danger' id='errorModalLabel'>Error</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body fw-bold'>
                                        <p>Lo sentimos, ocurrió un error al procesar tu solicitud. Por favor, intenta nuevamente más
                                            tarde. Se recargará la página en 5 segundos.</p>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de confirmación -->
                        <div class='modal fade' id='confirmModal' tabindex='-1' role='dialog' aria-labelledby='confirmModalLabel'
                            aria-hidden='true'>
                            <div class='modal-dialog modal-dialog-centered'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title text-danger' id='confirmModalLabel'>Confirmación</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body fw-bold'>
                                        <p>¿Estás seguro de que deseas eliminar este evento?</p>
                                    </div>
                                    <div class='modal-footer'>
                                        <a type='button' class='btn btn-danger' href='novedades.php?eventID=' id='deleteEventLink'>Eliminar</a>
                                        <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de actualización -->
                        <div class='modal fade' id='updatedModal' tabindex='-1' role='dialog' aria-labelledby='updatedModalLabel'
                            aria-hidden='true'>
                            <div class='modal-dialog modal-dialog-centered'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title text-success' id='updatedModalLabel'>Actualizado</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body fw-bold'>
                                        <p>Se ha actualizado correctamente el evento. Se recargará la página en 5 segundos.</p>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de eliminación -->
                        <div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='errorModalLabel'
                            aria-hidden='true'>
                            <div class='modal-dialog modal-dialog-centered'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title text-success' id='deleteModalLabel'>Eliminado</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body fw-bold'>
                                        <p>Se ha eliminado correctamente el evento. Se recargará la página en 5 segundos.</p>
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>
<?php require_once 'templates/footer.php'; ?>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/theme.js"></script>
<?php require_once "servidor/alerts.php"; ?>

</body>

</html>