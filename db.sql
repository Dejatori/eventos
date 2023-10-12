-- Creación de la base de datos

CREATE
DATABASE IF NOT EXISTS `eventos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE
`eventos`;

-- Tabla de usuarios

CREATE TABLE usuarios
(
    ID_Usuario         INT                                NOT NULL AUTO_INCREMENT,
    Cod_Usuario        VARCHAR(10)                        NOT NULL UNIQUE,
    Nombre             VARCHAR(200)                       NOT NULL,
    Apellido           VARCHAR(200)                       NOT NULL,
    Correo             VARCHAR(150)                       NOT NULL UNIQUE,
    Clave              CHAR(60)                           NOT NULL,
    Fecha_De_Registro  DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (ID_Usuario),
    INDEX              idx_usuarios_correo (Correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Disparador para el código de usuario

DELIMITER
$$
CREATE TRIGGER before_insert_usuarios
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
END;$$
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
    INDEX              idx_eventos_nombre (Nombre_Evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Crear 10 eventos

INSERT INTO eventos (Nombre_Evento, Descripcion_Evento, Fecha_De_Registro, Lugar, Fecha_Y_Hora)
    VALUES
    ('Evento1', 'Gallinas 2x1', '2023-10-01 06:00:00', 'Galpon 1', '2023-10-01 07:10:55'),
    ('Evento2', 'Amarillo amarillo platano', '2023-10-02 06:10:55', 'Palmas 1', '2023-10-06 13:10:55'),
    ('Evento3', 'Salmones y truchas a la fuga', '2023-10-03 07:10:55', 'Estanque 1', '2023-10-15 18:10:55'),
    ('Evento4', 'Fiesta en el galpón', '2023-10-04 06:10:55', 'Galpon 7', '2023-10-20 19:10:55'),
    ('Evento5', 'Cumple de la gallina curuleca', '2023-10-05 00:10:55', 'Galpon 3', '2023-10-08 09:10:55'),
    ('Evento6', 'Aceite de palma 2x1', '2023-10-10 12:10:55', 'Palmas 3', '2023-10-30 04:10:55'),
    ('Evento7', 'El pasante borro la base de datos', '2023-10-10 16:10:55', 'Oficina de Payares', '2023-10-25 10:10:55'),
    ('Evento8', 'Mauricio presidente', '2023-10-10 14:10:55', 'Imaginación de Mauricio', '2023-10-13 15:10:55'),
    ('Evento9', 'Huevo fest', '2023-10-10 16:10:55', 'Galpon 2', '2023-10-14 07:10:55'),
    ('Evento10', 'Bajos niveles de oxigeno', '2023-10-10 17:10:55', 'Estanque 10', '2023-10-10 12:21:55');

-- Ordenar eventos por fecha

SELECT * FROM eventos ORDER BY Fecha_Y_Hora;

-- Tabla de eventos eliminados

CREATE TABLE eventos_eliminados
(
    ID_Evento               INT                                NOT NULL,
    Nombre_Evento           VARCHAR(200)                       NOT NULL,
    Descripcion_Evento      VARCHAR(500)                       NOT NULL,
    Fecha_De_Eliminacion    DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Lugar                   VARCHAR(200)                       NOT NULL,
    Fecha_Y_Hora            DATETIME                           NOT NULL,
    PRIMARY KEY (ID_Evento),
    INDEX              idx_eventos_nombre (Nombre_Evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;