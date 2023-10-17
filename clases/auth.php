<?php
// Incluir la clase de conexión a la base de datos
require_once 'conexion.php';

// Clase para autenticar usuarios
class Auth
{
    /**
     * Atributo para la conexión a la base de datos (instancia de la clase Conexion)
     * @var PDO $conexion
     * protected: solo se puede acceder desde la misma clase y desde las clases que heredan de ella
     */
    protected PDO $conexion;

    /**
     * Constructor de la clase Auth
     * @param Conexion $conexion instancia de la clase Conexion
     */
    public function __construct(Conexion $conexion) // __construct: se ejecuta al instanciar la clase y recibe como parámetro una instancia de la clase Conexion
    {
        $this->conexion = $conexion->conectar(); // conectar(): función de la clase Conexion
    }

    // Función para verificar si el correo ya existe en la base de datos
    public function verificar_correo(string $correo): bool // bool: retorna un valor booleano
    {
        $sqlVerificarCorreo = $this->conexion->prepare('SELECT ID_Usuario, Nombre, Apellido, Correo, Clave, Cod_Usuario FROM usuarios WHERE Correo = :correo');
        $sqlVerificarCorreo->bindParam(':correo', $correo, PDO::PARAM_STR);
        $sqlVerificarCorreo->execute();
        return $sqlVerificarCorreo->fetch() !== false;
    }

    // Función para registrar un usuario
    public function registrar_usuario($nombre, $apellido, $correo, $clave): bool // bool: retorna un valor booleano
    {
        $check_stmt = $this->verificar_correo($correo); // verificar_correo(): función de la clase Auth

        if ($check_stmt === true) {
            // El correo ya existe
            $_SESSION['register_message'] = 2;
            //mostrar_mensaje_registro();
            return false;
        } else {
            // El correo no existe, se procede a registrar el usuario
            $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT); // password_hash(): encripta la contraseña

            /** @noinspection SqlInsertValues */
            // Preparar la consulta SQL
            $stmt = $this->conexion->prepare('INSERT INTO usuarios (Nombre, Apellido, Correo, Clave) VALUES (:nombre, :apellido, :correo, :clave_encriptada)');
            // Vincular parámetros
            // bindParam: vincula un parámetro con una variable de la clase PDOStatement (más información en https://www.php.net/manual/es/pdostatement.bindparam.php)
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':clave_encriptada', $clave_encriptada, PDO::PARAM_STR);

            // Si la consulta se ejecuta correctamente se retorna true, de lo contrario false
            if ($stmt->execute()) {
                // Mensaje de éxito al registrar un usuario
                $_SESSION['register_message'] = 3;
                //mostrar_mensaje_registro();
                return true;
            } else {
                // Mensaje de error al registrar un usuario (no se pudo ejecutar la consulta SQL)
                $_SESSION['register_message'] = 1;
                //mostrar_mensaje_registro();
                return false;
            }
        }
    }

    // Función para logear un usuario
    public function logear_usuario($correo, $clave): bool // bool: retorna un valor booleano
    {
        $stmt = $this->verificar_correo($correo); // verificar_correo(): función de la clase Auth

        if ($stmt === false) {
            // El correo no existe
            $_SESSION['login_message'] = 1;
            return false;
        } else {
            // El correo existe, se procede a logear el usuario
            $stmt = $this->conexion->prepare('SELECT * FROM usuarios WHERE Correo = :correo'); // Preparar la consulta SQL
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR); // Vincular parámetros
            $stmt->execute(); // Ejecutar la consulta SQL
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // fetch(): obtiene la siguiente fila de un conjunto de resultados (más información en https://www.php.net/manual/es/pdostatement.fetch.php)
            $clave_encriptada = $usuario['Clave']; // Obtener la contraseña encriptada del usuario
            if (!password_verify($clave, $clave_encriptada)) {
                $_SESSION['login_message'] = 2;
                return false;
            } elseif (password_verify($clave, $clave_encriptada)) { // password_verify(): verifica que la contraseña coincida con un hash (más información en https://www.php.net/manual/es/function.password-verify.php)
                $_SESSION['usuario_id'] = $usuario['ID_Usuario']; // ID del usuario
                $_SESSION['nombre'] = $usuario['Nombre']; // Nombre del usuario
                $_SESSION['apellido'] = $usuario['Apellido']; // Apellido del usuario
                $_SESSION['correo'] = $usuario['Correo']; // Correo del usuario
                $_SESSION['login_message'] = 3;
                //header('location: inicio.php');
                return true;
            } else {
                $_SESSION['login_message'] = 4;
                return false;
            }
        }
    }

    // Función para eliminar un usuario
    public function eliminar_usuario($correo): bool // bool: retorna un valor booleano
    {
        $stmt = $this->conexion->prepare('DELETE FROM usuarios WHERE Correo = :correo'); // Preparar la consulta SQL
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR); // Vincular parámetros
        if ($stmt->execute()) { // Si la consulta se ejecuta correctamente se retorna true, de lo contrario false
            return true;
        } else {
            return false;
        }
    }
}