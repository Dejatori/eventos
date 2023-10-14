<?php
require_once '../clases/conexion.php';

/**
 * Esta clase extiende PHPUnit\Framework\TestCase y contiene las pruebas para la clase Conexion.
 */
class conexionTest extends PHPUnit\Framework\TestCase
{
    protected Conexion $conexion;

    // Este método se ejecuta antes de cada prueba y crea una instancia de la clase Conexion
    protected function setUp(): void
    {
        $this->conexion = new Conexion();
    }

    public function testConexion()
    {
        // Intentar conectar a la base de datos
        $result = $this->conexion->conectar();
        $this->assertInstanceOf(PDO::class, $result, 'La conexión a la base de datos es exitosa');
    }
}