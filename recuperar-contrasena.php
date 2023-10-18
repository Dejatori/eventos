<?php
require_once 'servidor/funciones.php'; // Archivo con las funciones del usuario
require_once 'servidor/recuperar-contrasena.php'; // Archivo con las funciones para restablecer la contraseña

redirigirSiLogeado(); // Función para volver al index si ya se ha iniciado sesión
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Recuperar Contraseña - ADSO</title>
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
                    <section class="position-relative py-4 py-xl-5">
                        <div class="container">
                            <div class="row mb-5">
                                <div class="col-md-8 col-xl-6 text-center mx-auto">
                                    <h2>Recuperar Contraseña</h2>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6 col-xl-4">
                                    <div class="card mb-5 mx-auto">
                                        <?php
                                        echo "\n";
                                        if (!empty($_SESSION['pswdrst'])) {
                                            echo $_SESSION['pswdrst'];
                                            unset($_SESSION['pswdrst']);
                                        }
                                        ?><div class="card-body d-flex flex-column align-items-center">
                                            <div class="bs-icon-xl bs-icon-circle bs-icon-primary bs-icon my-4">
                                                <svg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' fill='currentColor' class='bi bi-envelope-arrow-down' viewBox='0 0 16 16'>
                                                    <path d='M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4.5a.5.5 0 0 1-1 0V5.383l-7 4.2-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h5.5a.5.5 0 0 1 0 1H2a2 2 0 0 1-2-1.99V4Zm1 7.105 4.708-2.897L1 5.383v5.722ZM1 4v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1Z'/>
                                                    <path d='M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.354-1.646a.5.5 0 0 1-.722-.016l-1.149-1.25a.5.5 0 1 1 .737-.676l.28.305V11a.5.5 0 0 1 1 0v1.793l.396-.397a.5.5 0 0 1 .708.708l-1.25 1.25Z'/>
                                                </svg>
                                            </div>
                                            <form method="POST" id="recuperar_contrasena">
                                                <div class="mb-3">
                                                    <label class="label" for="correo">Ingresa tu correo</label>
                                                    <input class="form-control form-control-lg" type="email" name="correo" id="correo" inputmode="email" placeholder="example@gmail.com" required>
                                                </div>
                                                <div class="mb-3">
                                                    <button class="btn btn-primary d-block w-100" type="submit" name="recuperar_contrasena">Recuperar Contraseña</button>
                                                </div>
                                            </form>
                                            <small>¿Ya tienes cuenta? <a href='iniciar-sesion.php'>→ Iniciar Sesión ←</a></small>
                                        </div>
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

</body>

</html>