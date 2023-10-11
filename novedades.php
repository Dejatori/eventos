<?php
session_start(); // Iniciar la sesión
require_once 'servidor/funciones.php'; // Archivo que contiene las funciones

// Conectar a la base de datos
$conexion = obtenerConexion();

// Obtener los registros de la tabla eventos
$sql = "SELECT * FROM eventos";
$result = $conexion->query($sql);
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Novedades - ADSO</title>
    <meta name="description" content="Clase del 09 de Octubre del 2023">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Serif+Dogra&amp;display=swap">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <?php require_once 'templates/navbar.php'; ?>
    <section>
        <div class="container">
            <h1 class="mt-4 mb-4 text-uppercase" style="font-family: 'Noto Serif Dogra', serif;font-size: 40px;font-weight: bold;">Tabla de eventos</h1>
            <a type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#registerModal">Agregar evento</a>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID-Evento</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Fecha de registro</th>
                        <th scope="col">Lugar</th>
                        <th scope="col">Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar los registros en la tabla
                    if ($result->rowCount() > 0) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . $row["ID_Evento"] . "</td>";
                            echo "<td>" . $row["Nombre_Evento"] . "</td>";
                            echo "<td>" . $row["Descripcion_Evento"] . "</td>";
                            echo "<td>" . $row["Fecha_De_Registro"] . "</td>";
                            echo "<td>" . $row["Lugar"] . "</td>";
                            echo "<td>" . $row["Fecha_Y_Hora"] . "</td>";
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
                        <form action="servidor/agregar_evento.php" method="POST">
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
        <!-- Modal de éxito -->
        <div class='modal fade' id='successModal' tabindex='-1' role='dialog' aria-labelledby='successModalLabel'
             aria-hidden='true'>
            aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content text-success'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='successModalLabel'>Éxito</span></h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-body'>
                        <p>¡El formulario se ha enviado correctamente! Se recargará la página en 5 segundos.</p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-staking' data-bs-dismiss='modal'
                                style='background: var(--bs-danger);'>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal de error -->
        <div class='modal fade' id='errorModal' tabindex='-1' role='dialog' aria-labelledby='errorModalLabel'
             aria-hidden='true'>
            aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content text-danger'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='errorModalLabel'>Error</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-body'>
                        <p>Lo sentimos, ocurrió un error al procesar tu solicitud. Por favor, intenta nuevamente más
                            tarde. Se recargará la página en 5 segundos.</p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-staking' data-bs-dismiss='modal'
                                style='background: var(--bs-danger);'>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require_once 'templates/footer.php'; ?>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script>
        <?php
        // Verificar si el formulario ha sido enviado
        if (isset($_SESSION["form_status"])) {
            if ($_SESSION["form_status"] == "success") {
                echo "$('#successModal').modal('show');";
            } else if ($_SESSION["form_status"] == "error") {
                echo "$('#errorModal').modal('show');";
            }
            unset($_SESSION["form_status"]);
        }
        setTimeout(function() {
            location.reload();
        }, 5000);
        ?>
    </script>
</body>


</html>
