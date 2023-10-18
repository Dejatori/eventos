<?php
require_once 'servidor/funciones.php'; // Archivo que contiene las funciones del usuario
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Registrarse - ADSO</title>
    <meta name="description" content="Clase del 09 de Octubre del 2023">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel='stylesheet' href='assets/fonts/fontawesome-all.min.css'>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic&amp;display=swap">
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
                                    <h2>Formulario de registro</h2>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6 col-xl-6">
                                    <div class="card mb-5 mx-auto">
                                        <?php
                                            echo "\n";
                                            if (!empty($_SESSION['register_message'])) {
                                                echo $mensaje = mostrar_mensaje_registro();
                                                unset($_SESSION['register_message']);
                                            }
                                        ?>
                                        <div class="card-body col-md-8 mx-auto align-items-center">
                                            <div class="bs-icon-xl bs-icon-circle bs-icon-primary bs-icon my-4 mx-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                                    fill="currentColor" viewBox="0 0 16 16" class="bi bi-person">
                                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"></path>
                                                </svg>
                                            </div>
                                            <form class="text-center" method="POST" id="registrar_usuario">
                                                <div class="mb-3">
                                                    <label for="nombre" class="form-label" style="font-size: 20px;">Ingresa tu nombre</label>
                                                    <input class="form-control item" type="text" name="nombre" id="nombre" placeholder="Nombre" required minlength="2">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="apellido" class="form-label" style="font-size: 20px;">Ingresa tu apellido/s</label>
                                                    <input class="form-control item" type="text" name="apellido" placeholder="Apellido/s" id="apellido" required minlength="2">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="correo" class="form-label" style="font-size: 20px;">Ingresa tu correo electrónico</label>
                                                    <input class="form-control item" type="email" name="correo" inputmode="email" id="correo" placeholder="ejemplo@gmail.com" required minlength="4">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="clave" class="form-label" style="font-size: 20px;">Crea una contraseña</label>
                                                    <div class="input-group">
                                                        <input class="form-control item" type="password" name="clave" id="clave" placeholder="********" required minlength="8">
                                                        <button class="btn btn-outline-secondary" type="button" id="ver_clave"><i class="fas fa-eye"></i></button>
                                                    </div>
                                                    <div class="invalid-feedback" id="password-warning">La contraseña debe tener al menos 8 caracteres.</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="confirmar_clave" class="form-label" style="font-size: 20px;">Confirmar contraseña</label>
                                                    <div class="input-group">
                                                        <input class="form-control item" type="password" name="confirmar_clave" id="confirmar_clave" placeholder="********" minlength="8" required>
                                                        <button class="btn btn-outline-secondary" type="button" id="ver_confirmar_clave"><i class="fas fa-eye"></i></button>
                                                    </div>
                                                    <div class="invalid-feedback" id="password-error">Las contraseñas no coinciden</div>
                                                </div>
                                                <div class="mb-3">
                                                    <button class="btn btn-primary d-block w-100" type="submit" name="registrar_usuario">Registrarse</button>
                                                    <p class="text-muted" style="margin: 10px;">¿Ya tienes cuenta?</p>
                                                    <a href="iniciar-sesion.php">Iniciar sesión</a>
                                                </div>
                                            </form>
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
<script>
    const togglePasswordVisibility = (input, button) => {
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        button.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash">';
    };

    const validatePassword = () => {
        const claveInput = document.querySelector('#clave');
        const confirmarClaveInput = document.querySelector('#confirmar_clave');
        const passwordError = document.querySelector('#password-error');
        const passwordWarning = document.querySelector('#password-warning');

        if (claveInput.value.length < 8) {
            passwordWarning.style.display = 'block';
            claveInput.classList.add('is-invalid');
        } else {
            claveInput.classList.remove('is-invalid');
            claveInput.classList.add('is-valid');
            passwordWarning.style.display = 'none';
            if (confirmarClaveInput.value !== claveInput.value) {
                passwordError.style.display = 'block';
                confirmarClaveInput.classList.add('is-invalid');
            } else {
                confirmarClaveInput.classList.remove('is-invalid');
                confirmarClaveInput.classList.add('is-valid');
                passwordError.style.display = 'none';
            }
        }
    };

    const verClaveBtn = document.querySelector('#ver_clave');
    const verConfirmarClaveBtn = document.querySelector('#ver_confirmar_clave');

    verClaveBtn.addEventListener('click', () => togglePasswordVisibility(document.querySelector('#clave'), verClaveBtn));

    verConfirmarClaveBtn.addEventListener('click', () => togglePasswordVisibility(document.querySelector('#confirmar_clave'), verConfirmarClaveBtn));

    document.querySelector('#clave').addEventListener('input', validatePassword);

    document.querySelector('#confirmar_clave').addEventListener('input', validatePassword);
</script>
<?php require_once "servidor/alerts.php"; ?>

</body>

</html>