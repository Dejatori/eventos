<?php

function logPDOException($e, $message): void
{
    // Obtener la fecha y hora actual en la zona horaria deseada
    $currentDateTime = date('d-m-Y H:i:s', strtotime('now -7 hours'));
    // Crear el mensaje de registro
    $logMessage = "[$currentDateTime] $message " . $e->getMessage() . PHP_EOL . $e . PHP_EOL;
    // Registrar el mensaje en el archivo de log
    error_log($logMessage, 3, '../logs/errorTest.log');
}

function errorLog(): void
{
    try {
        // Intenta realizar una operación que generará una excepción
        // En este caso, intentamos conectarnos a una base de datos que no existe
        $pdo = new PDO('mysql:host=localhost;dbname=base_de_datos_inexistente', 'usuario', 'contrasena');
    } catch (PDOException $e) {
        logPDOException($e, 'Descripción de la excepción: ');
    } finally {
        // Cierra la conexión
        $pdo = null;
    }
}

try {
    // Código que puede lanzar una excepción
    $result = divide(10, 0);
} catch (DivisionByZeroError $e) {
    // Captura una excepción específica (DivisionByZeroError)
    echo 'Error: ' . $e->getMessage();
} catch (Exception $e) {
    // Captura cualquier otra excepción de tipo Exception
    echo 'Otro error: ' . $e->getMessage();
} finally {
    // Este código se ejecuta siempre, independientemente de si se capturó una excepción
    // Puedes usarlo para liberar recursos, cerrar conexiones, etc.
}

function divide($numerator, $denominator): float|int
{
    if ($denominator == 0) {
        throw new DivisionByZeroError('No puedes dividir por cero.');
    }
    return $numerator / $denominator;
}

class PDOExceptionTest extends PHPUnit\Framework\TestCase
{
    public function testErrorLog()
    {
        // Llama a la función errorLog
        errorLog();

        // Verifica si el archivo error.log se ha creado
        $this->assertFileExists('../logs/errorTest.log');

        // Verifica si el mensaje de error se encuentra en el archivo de registro
        $logContent = file_get_contents('../logs/errorTest.log');

        // Verifica si el contenido del archivo contiene el mensaje de error esperado
        $this->assertStringContainsString('Descripción de la excepción: ', $logContent);
    }

    public function testDivisionByZeroError()
    {
        $this->expectException(DivisionByZeroError::class);
        $this->expectExceptionMessage('No puedes dividir por cero.');

        divide(10, 0);
    }
}