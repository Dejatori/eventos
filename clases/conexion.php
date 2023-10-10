<?php

const DB_HOST = 'localhost'; // Dirección del servidor de la base de datos
const DB_NAME = 'eventos'; // Nombre de la base de datos
const DB_PORT = '3310'; // Puerto de la base de datos
const DB_USER = 'root'; // Usuario de la base de datos
const DB_PASS = ''; // Contraseña de la base de datos

class Conexion
{
    // Atributo para la conexión a la base de datos
    private $conexion; // private: solo se puede acceder desde la misma clase

    // Función para conectar a la base de datos
    public function conectar() // public: se puede acceder desde cualquier clase
    {
        try { // try: intenta ejecutar el código, si hay error, se ejecuta el catch
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT;
            $this->conexion = new PDO($dsn, DB_USER, DB_PASS);

            // Configura el atributo ERRMODE_EXCEPTION para manejar excepciones
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->conexion; // Devuelve la conexión
        } catch (PDOException $e) { // catch: captura el error
            echo 'Error al conectar a la base de datos: ' . $e->getMessage(); // getMessage(): devuelve el mensaje de error
            exit(); // Termina la ejecución del script
        }
    }

    // Función para cerrar la conexión a la base de datos
    public function cerrarConexion(): void
    {
        $this->conexion = null; // null: destruye la conexión
    }
}

?>