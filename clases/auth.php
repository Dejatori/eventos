<?php
// Incluir la clase de conexión a la base de datos
require_once 'conexion.php';

// Clase para autenticar usuarios
class Auth
{
    // Atributo para la conexión a la base de datos (instancia de la clase Conexion)
    // protected: solo se puede acceder desde la misma clase y desde las clases que heredan de ella
    protected PDO $conexion;

    public function __construct(Conexion $conexion) // __construct: se ejecuta al instanciar la clase y recibe como parámetro una instancia de la clase Conexion
    {
        $this->conexion = $conexion->conectar(); // conectar(): función de la clase Conexion
    }

    // Función para registrar un usuario
    public function registrar_usuario($nombre, $apellido, $correo, $clave): bool // bool: retorna un valor booleano
    {
        $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT); // password_hash(): encripta la contraseña

        // Insertar datos en la tabla usuarios
        $sql = 'INSERT INTO usuarios (Nombre, Apellido, Correo, Clave) 
                VALUES (:nombre, :apellido, :correo, :clave_encriptada)';

        // Preparar la consulta SQL
        $stmt = $this->conexion->prepare($sql);

        // Vincular parámetros
        // bindParam: vincula un parámetro con una variable de la clase PDOStatement (más información en https://www.php.net/manual/es/pdostatement.bindparam.php)
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':clave_encriptada', $clave_encriptada, PDO::PARAM_STR);

        // Ejecutar la consulta SQL
        $stmt->execute();

        // Si la consulta se ejecuta correctamente se retorna true, de lo contrario false
        if ($stmt->execute()) {
            echo 'El usuario ha sido registrado correctamente.';
            return true;
        } else {
            echo 'Error al registrar el usuario.';
            return false;
        }
    }

    // Función para logear un usuario
    public function logear_usuario($correo, $clave): bool // bool: retorna un valor booleano
    {
        $sql = "SELECT * FROM usuarios WHERE Correo = :correo"; // Consulta SQL
        $stmt = $this->conexion->prepare($sql); // Preparar la consulta SQL
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR); // Vincular parámetros
        $stmt->execute(); // Ejecutar la consulta SQL
        $fila = $stmt->fetch(PDO::FETCH_ASSOC); // fetch(): obtiene una fila de un conjunto de resultados asociado al objeto PDOStatement
        $clave_encriptada = $fila['Clave']; // Obtener la contraseña encriptada

        // Verificar si la contraseña coincide con el hash proporcionado (clave_encriptada)
        if (password_verify($clave, $clave_encriptada)) { // password_verify(): verifica que la contraseña coincida con el hash proporcionado
            $_SESSION['usuario_id'] = $fila['ID_Usuario']; // ID del usuario
            $_SESSION['nombre'] = $fila['Nombre']; // Nombre del usuario
            $_SESSION['apellido'] = $fila['Apellido']; // Apellido del usuario
            $_SESSION['correo'] = $fila['Correo']; // Correo del usuario
            return true;
        } else {
            return false;
        }
    }
}
