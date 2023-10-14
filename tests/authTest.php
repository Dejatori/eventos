<?php
require_once '../clases/conexion.php';
require_once '../clases/auth.php';

/**
 * Este Test fue realizado con PHP version 8.2.4, Xdebug 3.2.2 y PHPUnit 9.6.13 en Windows 11
 * Para que las pruebas funcionen correctamente, se deben comentar las siguientes líneas del archivo 'auth.php'
 * línea 45: mostrar_mensaje_registro($_SESSION['register_message'] = 2);
 * línea 46: header('location: registrarse.php');
 * líne 85: header('location: iniciar-sesion.php');
*
/**
 * Esta clase extiende PHPUnit\Framework\TestCase y contiene las pruebas para la clase Auth_Testing.
 * */
class authTest extends PHPUnit\Framework\TestCase
{
    protected Auth $auth;

    // Este método se ejecuta antes de cada prueba y crea una instancia de la clase Auth_Testing
    protected function setUp(): void
    {
        $conexion = new Conexion();
        $this->auth = new Auth($conexion);
    }

    public function testRegistro()
    {
        $nombre = 'Probandus';
        $apellido = 'Testus';
        $correo = 'usuario@example.com';
        $clave = 'password123';

        // Intentar registrar usuario con credenciales válidas
        $result = $this->auth->registrar_usuario($nombre, $apellido, $correo, $clave);
        $this->assertTrue($result, 'Usuario registrado correctamente');

        // Intentar registrar un usuario con el mismo correo debería fallar
        $result = $this->auth->registrar_usuario($nombre, $apellido, $correo, $clave);
        $this->assertFalse($result, 'El correo ya existe, no se puede registrar el usuario');
    }

    public function testLogin()
    {
        $correo = 'usuario@example.com';
        $clave = 'password123';

        // Intentar iniciar sesión con las credenciales válidas
        $result = $this->auth->logear_usuario($correo, $clave);
        $this->assertTrue($result, 'Usuario logeado correctamente');

        // Intentar iniciar sesión con un correo que no existe debería fallar
        $correo = 'no_usuario@example.com';
        $result = $this->auth->logear_usuario($correo, $clave);
        $this->assertFalse($result, 'El correo no existe');

        // Intentar iniciar sesión con una contraseña incorrecta debería fallar
        $correo = 'usuario@example.com';
        $clave = 'password456';
        $result = $this->auth->logear_usuario($correo, $clave);
        $this->assertFalse($result, 'Contraseña incorrecta');

        // Intentar iniciar sesión con un correo y contraseña incorrectos debería fallar
        $correo = 'sin_usuario@example.com';
        $clave = 'password789';
        $result = $this->auth->logear_usuario($correo, $clave);
        $this->assertFalse($result, 'Datos incorrectos');
    }

    public function test_eliminar()
    {
        // Test eliminar usuario
        $correo = 'usuario@example.com';
        $result = $this->auth->eliminar_usuario($correo);
        $this->assertTrue($result, 'Usuario eliminado correctamente');
    }
}