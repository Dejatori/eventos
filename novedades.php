<?php
require_once 'servidor/funciones.php'; // Archivo que contiene las funciones del usuario
require_once 'servidor/eventos.php'; // Archivo que contiene las funciones para los eventos

$logeado = verificarLogin();

// Redirigir al usuario a inicio.php si cerró sesión despues de 3 segundos
if (!empty($_SESSION['logout_message'])) {
    header("Refresh: 2; URL = inicio.php");
}

// Redireccionar al error 403 si el usuario no está logeado
if (!$logeado && empty($_SESSION['logout_message'])) {
    header('Location: 403.html');
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
                        <div class="container-fluid" id="content">
                            <h1 class="mt-4 mb-4 text-uppercase" style="font-family: 'Noto Serif Dogra', serif;font-size: 40px;font-weight: bold;">Tabla de eventos</h1>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="eventTable_length" class="eventTables_length" aria-controls="eventTable">
                                            <select class="form-select form-select-sm ms-end" id="ordenar-eventos" name="ordenar-eventos"
                                                    aria-label=".form-select-sm example" style="width: auto;">
                                                <option>Ordenar por...</option>
                                                <option value="id-asc">ID-Evento (Ascendente)</option>
                                                <option value="nombre-asc">Nombre (Ascendente)</option>
                                                <option value="descripcion-asc">Descripción (Ascendente)</option>
                                                <option value="fecha-registro-desc">Fecha de Registro (Más reciente a más antigua)</option>
                                                <option value="lugar-asc">Lugar (Ascendente)</option>
                                                <option value="fecha-hora-desc">Fecha y Hora (Más reciente a más antigua)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <div class="eventTables_filter me-3" id="eventTable_filter">
                                                <label class="form-label">
                                                    <input type="search" class="form-control form-control-sm" aria-controls="eventTable" placeholder="Buscar" id="busqueda">
                                                </label>
                                            </div>
                                            <div class='eventTables_filter'>
                                                <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#registerModal'>Agregar evento</button>
                                            </div>
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
                                        $tablaHTML = generarTablaEventos($lista_eventos);
                                        echo $tablaHTML;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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
<script>
    const busqueda = document.querySelector('#busqueda');
    const tabla = document.querySelector('#eventTable tbody');

    busqueda.addEventListener('keyup', () => {
        const textoBusqueda = busqueda.value.toLowerCase().trim();
        const filas = Array.from(tabla.querySelectorAll('tr'));

        filas.map((fila) => {
            const celdas = Array.from(fila.querySelectorAll('td'));
            const mostrarFila = celdas.filter((celda) => celda.textContent.toLowerCase().includes(textoBusqueda)).length > 0;

            if (mostrarFila) {
                fila.classList.remove('d-none');
            } else {
                fila.classList.add('d-none');
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const ordenarEventosSelect = document.querySelector('#ordenar-eventos');

        ordenarEventosSelect.addEventListener('change', function () {
            const selectedOption = ordenarEventosSelect.value;

            // Realizar una solicitud AJAX al servidor para ordenar los eventos
            fetch('servidor/eventos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'ordenarEventos=' + selectedOption,
            })
                .then((response) => response.text())
                .then((data) => {
                    // Actualizar el contenido de la tabla con los eventos ordenados
                    const eventTable = document.querySelector('#eventTable tbody');
                    eventTable.innerHTML = data;
                })
                .catch((error) => {
                    console.error('Error al ordenar eventos:', error);
                });
        });
    });
</script>
<?php require_once "servidor/alerts.php"; ?>

</body>

</html>
