<?php

// Función para mostrar el mensaje de error o éxito al registrar un usuario
function mostrar_mensaje_registro() { 
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
function mostrar_mensaje_login() {
    $messages = [
        1 => '<div class="alert alert-danger">
                <strong>El correo electrónico o la contraseña son incorrectos. Por favor, inténtalo de nuevo.</strong>
              </div>',
        2 => '<div class="alert alert-danger">
                <p>Ha ocurrido un error al iniciar sesión. Por favor, inténtelo de nuevo.</p>
              </div>',
        3 => '<div class="alert alert-success text-center">
                <p>Se ha iniciado sesión exitosamente.</p>',
    ];

    if (isset($_SESSION['login_message']) && isset($messages[$_SESSION['login_message']])) {
        return $messages[$_SESSION['login_message']];
    }
    return '';
}

// Función para mostrar el mensaje de error o éxito al cerrar sesión
function mostrar_mensaje_logout() {
    $messages = [
        1 => '<div class="modal fade show" id="logoutModal" tabindex="-1" role="dialog" aria-label="logoutModalLabel">
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
        2 => '<div class="modal fade show" id="logoutModal" tabindex="-1" role="dialog" aria-label="logoutModalLabel">
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

// Función para mostrar los mensajes al crear, actualizar o eliminar un evento
function mostrar_mensaje_evento() {
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