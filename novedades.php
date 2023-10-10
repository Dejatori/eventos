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
                            echo "<td>" . $row["id_evento"] . "</td>";
                            echo "<td>" . $row["nombre"] . "</td>";
                            echo "<td>" . $row["descripcion"] . "</td>";
                            echo "<td>" . $row["fecha_registro"] . "</td>";
                            echo "<td>" . $row["lugar"] . "</td>";
                            echo "<td>" . $row["fecha_hora"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay registros</td></tr>";
                    }

                    // Cerrar la conexión a la base de datos
                    $cerrar_conexion = cerrarConexion();
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php require_once 'templates/footer.php'; ?>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>


</html>
