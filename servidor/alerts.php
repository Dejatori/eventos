<?php
if (!empty($_SESSION['logout_message'])) {
    echo $mensaje = mostrar_mensaje_logout();
    echo '<script>
            jQuery(document).ready(function () {
            jQuery("#logoutModal").modal("show");
            });
          </script>';
    unset ($_SESSION['logout_message']);
}

if (!empty($_SESSION['login_message'] ?? 3)) {
    echo $mensaje = mostrar_mensaje_login();
    echo '<script>
            jQuery(document).ready(function () {
            jQuery("#loginModal").modal("show");
            });
          </script>';
    unset ($_SESSION['login_message']);
}

$mensajeEvento = mostrar_mensaje_evento();

if (!empty($_SESSION['event_message'])) {
    echo '<script>
            ' . $mensajeEvento . ';
            setTimeout(function () {
                window.location.href = "novedades.php";
            }, 5000);
          </script>';
    unset ($_SESSION['event_message']);
}

$scriptEvento = "
    <script>
        $('#confirmModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget); // Botón que activó el modal
            let eventID = button.data('id'); // ID del evento

            // Actualiza el enlace de Eliminar en el modal con el ID del evento
            let deleteLink = $('#deleteEventLink');
            deleteLink.attr('href', 'novedades.php?eventID=' + eventID);
        });

        $('#updateModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let editID_evento = button.data('id');

            // Actualiza el value en el modal con el ID del evento
            let editModal = $('#editID_evento');
            editModal.attr('value', editID_evento);
        });
    </script>
";

// Si el usuario esta en novedades.php hace echo del scriptEvento
if (str_contains($_SERVER['REQUEST_URI'], 'novedades.php')) {
    echo $scriptEvento;
}