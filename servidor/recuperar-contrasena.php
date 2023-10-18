<?php
require_once(VENDOR_PATH . 'autoload.php'); // Cargar Composer's autoloader

// Importar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Funciones para restablecer la contraseña
 */

try {
    if (isset($_POST['recuperar_contrasena'])) {

        $correo = $_POST['correo'];
        $Recuperar = recuperarClave($correo);
    }
} catch (PDOException $e) {
    logPDOException($e, 'Descripción de la excepción: ');
}

// Función para verificar si el correo existe en la base de datos
function verificarSolicitudRestablecerClave(PDO $pdo, string $correo): bool {
    $sqlRestablecerClave = $pdo->prepare('SELECT Correo FROM restablecer_contrasena WHERE Correo = :correo');
    $sqlRestablecerClave->bindParam(':correo', $correo, PDO::PARAM_STR);
    $sqlRestablecerClave->execute();
    return $sqlRestablecerClave->fetch() !== false;
}

// Función para generar un token aleatorio
function generarToken(): string {
    try {
        return bin2hex(random_bytes(32));
    } catch (\Exception $e) {
        logException($e, 'Error al generar el token: ');
        return '';
    }
}

// Función para insertar el token en la tabla restablecer_contrasena
function insertarTokenEnTabla(PDO $pdo, string $correo, string $token): void {
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    $sqlInsertarToken = $pdo->prepare('INSERT INTO restablecer_contrasena (Correo, Token) VALUES (:correo, :token)');
    $sqlInsertarToken->bindParam(':correo', $correo, PDO::PARAM_STR);
    $sqlInsertarToken->bindParam(':token', $hashedToken, PDO::PARAM_STR);
    $sqlInsertarToken->execute();
}

// Función para enviar el correo electrónico
function enviarCorreoElectronico(string $correo, string $token): bool
{
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Habilitar la depuración
        $mail->isSMTP(); // Usar SMTP
        $mail->Host = 'smtp.gmail.com'; // Nombre del servidor SMTP
        $mail->SMTPAuth = true; // Habilitar la autenticación SMTP
        $mail->Username = 'correo de gmail'; // Nombre de usuario SMTP
        $mail->Password = 'contraseña para aplicaciones'; // Contraseña SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar el cifrado TLS; `PHPMailer::ENCRYPTION_SMTPS` también aceptado
        $mail->Port = 587; // Puerto TCP para conectarse
        $mail->CharSet = 'UTF-8'; // Establece la codificación a UTF-8

        // Configuración del mensaje
        $mail->setFrom('dejatori@adso.com', 'ADSO eventos'); // Dirección de correo electrónico del remitente
        $mail->addAddress($correo); // Dirección de correo electrónico del destinatario

        // Contenido del mensaje
        $mail->isHTML(true); // Establecer el formato del correo electrónico a HTML
        $mail->Subject = 'Restablecer contraseña - ADSO eventos'; // Asunto del correo electrónico
        $mail->Body = '
            <html lang="es">
                <head>
                    <meta charset="utf-8">
                    <title>Restablecer contraseña</title>
                </head>
                <body>
                    <p>Saludos,</p>
                    <p>Hemos recibido una solicitud para restablecer la contraseña de su cuenta.</p>
                    <p>Si no ha solicitado restablecer la contraseña, ignore este mensaje.</p>
                    <p>Para restablecer su contraseña, haga clic en el siguiente enlace:</p>
                    <a href="https://localhost/eventos/restablecer-contrasena.php?correo=' . $correo . '&token=' . $token . '">Restablecer contraseña</a>
                </body>
            </html>';

        // Enviar el correo electrónico
        return $mail->send();
    } catch (Exception $e) {
        logException($e, 'Error al enviar el correo electrónico: ');
        return false;
    }
}

// Función para el proceso completo de recuperar la clave
function recuperarClave(string $correo): bool {

    list($pdo, $auth) = obtenerConexionYAuth(); // Obtener la instancia de conexión y autenticación

    if (verificarSolicitudRestablecerClave($pdo, $correo)) {
        // El correo ya ha solicitado restablecer la contraseña
        $_SESSION['pswdrst'] = '
            <div class="alert alert-danger">
                <strong>Correo no enviado</strong> El correo electrónico: <strong>' . $correo . '</strong>. Ya ha solicitado restablecer la contraseña.
            </div>';
        return false;
    }

    if (!$auth->verificar_correo($correo)) {
        // El correo no se encuentra registrado
        $_SESSION['pswdrst'] = '
                <div class="alert alert-danger">
                    <strong>Correo no encontrado</strong> El correo electrónico: <strong>' . $correo . '</strong>. No se encuentra registrado.
                </div>';
        return false;
    }

    $token = generarToken();
    insertarTokenEnTabla($pdo, $correo, $token);

    if (enviarCorreoElectronico($correo, $token)) {
        // Correo enviado con éxito
        $_SESSION['pswdrst'] = '
                    <div class="alert alert-success">
                        <strong>Correo enviado</strong> Se ha enviado un correo electrónico a: <strong>' . $correo . '</strong>. Siga las instrucciones para restablecer la contraseña.
                    </div>';
        return true;
    } else {
        // Error al enviar el correo
        $_SESSION['pswdrst'] = '
                    <div class="alert alert-danger">
                        <strong>Correo no enviado</strong> No se ha podido enviar el correo electrónico a: <strong>' . $correo . '</strong>.
                    </div>';
        return false;
    }
}
