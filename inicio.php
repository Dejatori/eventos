<?php
require_once 'servidor/funciones.php'; // Archivo que contiene las funciones del usuario
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Inicio - ADSO</title>
    <meta name="description" content="Clase del 09 de Octubre del 2023">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel='stylesheet' href='assets/fonts/fontawesome-all.min.css'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Serif+Dogra&amp;display=swap">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body id="page-top">

<?php require_once 'templates/navbar.php'; ?>
                <header class="text-center text-white masthead" style="background:url('assets/img/bg-masthead.jpg')no-repeat center center;background-size:cover;">
                    <div class="overlay"></div>
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-9 mx-auto position-relative">
                                <h1 class="mb-5">Hola estudiantes de ADSO 2560979!</h1>
                            </div>
                            <div class="col-md-10 col-lg-8 col-xl-7 mx-auto position-relative">
                                <form>
                                    <div class="row">
                                        <div class="col"><a class="btn btn-lg" role="button" href="https://github.com/Dejatori/eventos.git" target="_blank" style="font-family: 'Noto Serif Dogra', serif;color: rgb(255,255,255);text-align: center;background: #006663;border: 2px solid #000aff;border-radius: 12px;">Ver repositorio con todo el código de estos ejemplos</a></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>
                <main>
                    <section class="container mt-4">
                    </section>
                </main>
<?php require_once 'templates/footer.php'; ?>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/theme.js"></script>
<?php require_once "servidor/alerts.php"; ?>

</body>

</html>