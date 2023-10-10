<?php
require_once 'servidor/funciones.php'; // Archivo que contiene las funciones

(session_status() === PHP_SESSION_NONE ? session_start() : ''); // Iniciar la sesión si no está iniciada

// Redirigir al usuario si ya hay una sesión iniciada
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
};
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Iniciar Sesión - ADSO</title>
    <meta name="description" content="Clase del 09 de Octubre del 2023">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Serif+Dogra&amp;display=swap">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <?php require_once 'templates/navbar.php'; ?>
    <section class="position-relative py-4 py-xl-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8 col-xl-6 text-center mx-auto">
                    <h2>Formulario de inicio de sesión</h2>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card mb-5">
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="bs-icon-xl bs-icon-circle bs-icon-primary bs-icon my-4"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-person">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"></path>
                                </svg></div>
                            <form class="text-center" action="logear.php" method="post" id="login_user">
                                <div class="mb-3">
                                    <label class="label" for="correo"></label>
                                    <input class="form-control form-control-lg" type="email" name="email" inputmode="email" placeholder="correo" minlength="4" required></div>
                                <div class="mb-3">
                                    <label class="label" for="clave"></label>
                                    <input class="form-control form-control-lg" type="password" name="password" placeholder="*********" minlength="8" required></div>
                                <div class="mb-3">
                                    <button class="btn btn-primary d-block w-100" type="submit">Iniciar sesión</button>
                                    <p class="text-muted" style="margin: 10px;">¿Aún no tienes cuenta?</p><a href="registrarse.php">Registrarse</a>
                                </div>
                                <?php
                                $_SESSION['login_error'] = null;
                                if (!empty($_SESSION['login_error'])) {
                                    echo $_SESSION['login_error'];
                                    unset($_SESSION['login_error']);
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require_once 'templates/footer.php'; ?>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>