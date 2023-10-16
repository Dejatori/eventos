-- El siguiente script SQL crea una base de datos llamada "eventos" y tres tablas: "usuarios", "eventos" y "eventos_eliminados".
-- La tabla "usuarios" contiene información de usuarios, incluyendo un código de usuario único generado por un disparador.
-- La tabla "eventos" contiene información sobre eventos, incluyendo el nombre del evento, la descripción, la ubicación y la fecha y hora.
-- La tabla se llena con 10 eventos de ejemplo y se ordena por fecha y hora.
-- La tabla "eventos_eliminados" contiene información sobre eventos eliminados, incluyendo el nombre del evento, la descripción, la ubicación y la fecha y hora de eliminación.

-- Creación de la base de datos y selección de la misma
CREATE DATABASE IF NOT EXISTS `eventos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `eventos`;
CREATE TABLE usuarios
(
    ID_Usuario        INT                                NOT NULL AUTO_INCREMENT,
    Cod_Usuario       VARCHAR(10)                        NOT NULL UNIQUE,
    Nombre            VARCHAR(200)                       NOT NULL,
    Apellido          VARCHAR(200)                       NOT NULL,
    Correo            VARCHAR(150)                       NOT NULL UNIQUE,
    Clave             CHAR(60)                           NOT NULL,
    Fecha_De_Registro DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (ID_Usuario),
    INDEX idx_usuarios_correo (Correo)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

-- Disparador para el código de usuario
DELIMITER
$$
CREATE TRIGGER Cod_Usuario
    BEFORE INSERT
    ON usuarios
    FOR EACH ROW
BEGIN
    DECLARE next_val INT;
    SET next_val = (SELECT MAX(RIGHT(Cod_Usuario, 5)) + 1 FROM usuarios WHERE Cod_Usuario LIKE 'COD-%');
    IF next_val IS NULL THEN
        SET next_val = 10000;
    END IF;
    SET
        NEW.Cod_Usuario = CONCAT('COD-', LPAD(next_val, 5, '0'));
END;
$$
DELIMITER ;

-- Tabla de eventos
CREATE TABLE eventos
(
    ID_Evento          INT                                NOT NULL AUTO_INCREMENT,
    Nombre_Evento      VARCHAR(200)                       NOT NULL,
    Descripcion_Evento VARCHAR(500)                       NOT NULL,
    Fecha_De_Registro  DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Lugar              VARCHAR(200)                       NOT NULL,
    Fecha_Y_Hora       DATETIME                           NOT NULL,
    PRIMARY KEY (ID_Evento),
    INDEX idx_eventos_nombre (Nombre_Evento)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

-- Tabla de eventos eliminados
CREATE TABLE eventos_eliminados
(
    ID_Evento            INT                                NOT NULL,
    Nombre_Evento        VARCHAR(200)                       NOT NULL,
    Descripcion_Evento   VARCHAR(500)                       NOT NULL,
    Fecha_De_Eliminacion DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Lugar                VARCHAR(200)                       NOT NULL,
    Fecha_Y_Hora         DATETIME                           NOT NULL,
    PRIMARY KEY (ID_Evento),
    INDEX idx_eventos_nombre (Nombre_Evento)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;