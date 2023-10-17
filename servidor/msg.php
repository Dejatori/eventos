<?php

// Función para mostrar el mensaje de error o éxito al registrar un usuario
function mostrar_mensaje_registro(): string
{
    $messages = [
        1 => '<div class="alert alert-danger">
                <strong>Ha ocurrido un error al registrar su usuario. Por favor, inténtelo de nuevo.</strong>
              </div>',
        2 => '<div class="alert alert-warning">
                <strong>El correo electrónico ya está registrado.</strong>
              </div>',
        3 => '<div class="alert alert-success">
                <strong>Se ha registrado exitosamente su usuario.</strong>
              </div>',
    ];

    if (isset($_SESSION['register_message']) && isset($messages[$_SESSION['register_message']])) {
        return $messages[$_SESSION['register_message']];
    }
    return ''; // En caso de que no haya mensaje
}

// Función para mostrar el mensaje de error o éxito al iniciar sesión
function mostrar_mensaje_login(): string
{
    $messages = [
        1 => '<div class="alert alert-danger">
                <strong>El correo electrónico no existe. Por favor, registrate o inténtalo de nuevo.</strong>
              </div>',
        2 => '<div class="alert alert-danger">
                <p>La contraseña ingresada es incorrecta, Por favor, inténtelo de nuevo.</p>
              </div>',
        3 => '<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-label="loginModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-success">Has iniciado sesión correctamente</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body fw-bold">
                            <div class="alert alert-success text-center">
                                <p>Se ha iniciado sesión exitosamente.</p> 
                                <p>Bienvenido ' . ($_SESSION["nombre"] ?? '') . ' ' . ($_SESSION["apellido"] ?? '') . '!</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>',
        4 => '<div class="alert alert-danger">
                    <p>Ha ocurrido un error al iniciar sesión. Por favor, inténtelo de nuevo.</p>
                  </div>',
    ];

    if (isset($_SESSION['login_message']) && isset($messages[$_SESSION['login_message']])) {
        return $messages[$_SESSION['login_message']];
    }
    return '';
}

// Función para mostrar el mensaje de error o éxito al cerrar sesión
function mostrar_mensaje_logout(): string
{
    $messages = [
        1 => '<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-label="logoutModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-success">Has cerrado sesión correctamente</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body fw-bold">
                            <div class="alert alert-success">
                                <p>Se ha cerrado sesión exitosamente.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>',
        2 => '<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-label="logoutModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-success">Has cerrado sesión correctamente</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body fw-bold">
                            <div class="alert alert-success">
                            <p>Ha ocurrido un error al cerrar sesión. Por favor, inténtelo de nuevo.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>',
    ];

    if (isset($_SESSION['logout_message']) && isset($messages[$_SESSION['logout_message']])) {
        return $messages[$_SESSION['logout_message']];
    }
    return '';
}

// Función para mostrar los mensajes al recuperar la contraseña
function mostrar_mensaje_recuperar_contrasena(): string
{
    $messages = [
        'exito' => '<div class="alert alert-success">
                        <strong>Correo enviado</strong> Se ha enviado un correo electrónico. Siga las instrucciones para restablecer la contraseña.
                    </div>',
        'no_enviado' => '<div class="alert alert-danger">
                            <strong>Correo no enviado</strong> No se ha podido enviar el correo electrónico.
                        </div>',
        'correo_duplicado' => '<div class="alert alert-danger">
                                <strong>Correo no enviado</strong> El correo electrónico ya ha solicitado restablecer la contraseña.
                            </div>',
        'correo_no_encontrado' => '<div class="alert alert-danger">
                                    <strong>Correo no encontrado</strong> El correo electrónico no se encuentra registrado.
                                </div>',
    ];

    if (isset($_SESSION['pswdrst']) && isset($messages[$_SESSION['pswdrst']])) {
        return $messages[$_SESSION['pswdrst']];
    }
    return ''; // En caso de que no haya mensaje
}

// Función para mostrar los mensajes al crear, actualizar o eliminar un evento
function mostrar_mensaje_evento(): string
{
    $messages = [
        1 => 'errorModal',
        2 => 'successModal',
        3 => 'updatedModal',
        4 => 'deleteModal',
    ];

    if (isset($_SESSION['event_message']) && isset($messages[$_SESSION['event_message']])) {
        return '$(document).ready(function () {
                    jQuery.noConflict();
                    jQuery("#' . $messages[$_SESSION['event_message']] . '").modal("show");
        });';
    }
    return '';
}