<?php

// Función para mostrar el mensaje de error o éxito al registrar un usuario
function mostrar_mensaje_registro() { 
    if (isset($_SESSION['register_message']) && $_SESSION['register_message'] == 1) {
        // Mostrar el mensaje de error
        $_SESSION['register_message'] = "<div class='alert alert-danger'>
                                            <strong>Ha ocurrido un error al registrar su usuario. Por favor, inténtelo de nuevo.</strong>.
                                        </div>";
    } elseif (isset($_SESSION['register_message']) && $_SESSION['register_message'] == 2) {
        // Mostrar el mensaje de error
        $_SESSION['register_message'] = "<div class='alert alert-warning'>
                                            <strong>El correo electrónico ya está registrado.</strong>
                                        </div>";
    } elseif (isset($_SESSION['register_message']) && $_SESSION['register_message'] == 3) {
        // Mostrar el mensaje de exito
        $_SESSION['register_message'] = "<div class='alert alert-success'>
                                            <strong>Se ha registrado exitosamente su usuario.</strong>.
                                        </div>";
    } else {
        // No mostrar nada
        echo "";
    }
}

// Función para mostrar el mensaje de error o éxito al iniciar sesión


// Función para mostrar el mensaje de error o éxito al agregar un evento
function mostrar_mensaje_evento() {
    if (isset($_SESSION['evento_message']) && $_SESSION['evento_message'] == 1) {
        // Mostrar el mensaje de error
        $_SESSION['evento_message'] =   "$(document).ready(function () {
            jQuery.noConflict();
            jQuery('#errorModal').modal('show');
        });";
    } elseif (isset($_SESSION['evento_message']) && $_SESSION['evento_message'] == 2) {
        // Mostrar el mensaje de exito
        $_SESSION['evento_message'] =   "$(document).ready(function () {
            jQuery.noConflict();
            jQuery('#successModal').modal('show');
        });";
    } elseif (isset($_SESSION['evento_message']) && $_SESSION['evento_message'] == 3) {
        // Mostrar el mensaje de actualizado
        $_SESSION['evento_message'] =   "$(document).ready(function () {
            jQuery.noConflict();
            jQuery('#updatedModal').modal('show');
        });";
    } elseif (isset($_SESSION['evento_message']) && $_SESSION['evento_message'] == 4) {
        // Mostrar el mensaje de eliminado
        $_SESSION['evento_message'] =   "$(document).ready(function () {
            jQuery.noConflict();
            jQuery('#deleteModal').modal('show');
        });";
    } else {
        // No mostrar nada
        echo "";
    }
}

