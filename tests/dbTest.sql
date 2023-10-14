-- Caso de prueba 1: Insertar un nuevo usuario (debería tener éxito y el Cod_Usuario debería ser COD-10000 por el Trigger)
INSERT INTO usuarios (Nombre, Apellido, Correo, Clave)
VALUES ('Deivi', 'Rico', 'deivi@adso.com', 'contraseña123');

-- Caso de prueba 2: Intentar insertar un usuario con el mismo correo que el primer usuario (debería fallar)
INSERT INTO usuarios (Nombre, Apellido, Correo, Clave)
VALUES ('David', 'Toscano', 'deivi@adso.com', 'contraseña456');

-- Caso de prueba 3: Intentar insertar un usuario con un correo único (debería tener éxito)
INSERT INTO usuarios (Nombre, Apellido, Correo, Clave)
VALUES ('Pallares', 'Costeño', 'pallares@adso.com', 'contraseña789');

-- Caso de prueba 4: Seleccionar todos los usuarios
SELECT *
FROM usuarios;

-- Caso de prueba 5: Seleccionar un usuario por correo
SELECT *
FROM usuarios
WHERE Correo = 'deivi@adso.com';

-- Caso de prueba 6: Insertar 10 eventos de ejemplo (deberían insertarse correctamente)
INSERT INTO eventos (Nombre_Evento, Descripcion_Evento, Fecha_De_Registro, Lugar, Fecha_Y_Hora)
VALUES ('Evento1', 'Gallinas 2x1', '2023-10-01 06:00:00', 'Galpon 1', '2023-10-01 07:10:55'),
       ('Evento2', 'Amarillo amarillo platano', '2023-10-02 06:10:55', 'Palmas 1', '2023-10-06 13:10:55'),
       ('Evento3', 'Salmones y truchas a la fuga', '2023-10-03 07:10:55', 'Estanque 1', '2023-10-15 18:10:55'),
       ('Evento4', 'Fiesta en el galpón', '2023-10-04 06:10:55', 'Galpon 7', '2023-10-20 19:10:55'),
       ('Evento5', 'Cumple de la gallina curuleca', '2023-10-05 00:10:55', 'Galpon 3', '2023-10-08 09:10:55'),
       ('Evento6', 'Aceite de palma 2x1', '2023-10-10 12:10:55', 'Palmas 3', '2023-10-30 04:10:55'),
       ('Evento7', 'El pasante borro la base de datos', '2023-10-10 16:10:55', 'Oficina de Payares',
        '2023-10-25 10:10:55'),
       ('Evento8', 'Mauricio presidente', '2023-10-10 14:10:55', 'Imaginación de Mauricio', '2023-10-13 15:10:55'),
       ('Evento9', 'Huevo fest', '2023-10-10 16:10:55', 'Galpon 2', '2023-10-14 07:10:55'),
       ('Evento10', 'Bajos niveles de oxigeno', '2023-10-10 17:10:55', 'Estanque 10', '2023-10-10 12:21:55');

-- Caso de prueba 6: Insertar un nuevo evento
INSERT INTO eventos (Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora)
VALUES ('Concierto de rock', 'Concierto de rock en vivo', 'Estadio Nacional', '2022-05-15 20:00:00');

-- Caso de prueba 7: Intentar insertar un evento con el mismo nombre que el primer evento (debería tener éxito)
INSERT INTO eventos (Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora)
VALUES ('Concierto de rock', 'Concierto de rock en vivo', 'Estadio Nacional', '2022-05-15 20:00:00');

-- Caso de prueba 8: Intentar insertar un ID_Evento repetido (debería fallar)
INSERT INTO eventos (ID_Evento, Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora)
VALUES ('1', 'Feria de tecnología', 'Feria de tecnología y gadgets', 'Centro de convenciones', '2022-07-20 10:00:00');

-- Caso de prueba 9: Seleccionar todos los eventos
SELECT *
FROM eventos;

-- Caso de prueba 10: Seleccionar un evento por nombre
SELECT *
FROM eventos
WHERE Nombre_Evento = 'Concierto de rock';

-- Caso de prueba 11: Eliminar eventos duplicados (Debería quedar solo un evento con el mismo nombre, descripción, lugar y fecha)
DELETE
FROM eventos
WHERE ID_Evento NOT IN
      (SELECT MIN(ID_Evento) FROM eventos GROUP BY Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora);

-- Caso de prueba 12: Reiniciar el valor de autoincremento de la columna ID_Evento (el siguiente evento debería tener ID_Evento = 11)
-- Paso 1: Encuentra el valor máximo actual de la columna autoincremental.
SELECT MAX(ID_Evento)
INTO @max_id
FROM eventos;

-- Paso 2: Establece el valor autoincremental a un número más alto que el valor máximo encontrado.
SET @sql = CONCAT('ALTER TABLE eventos AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Caso de prueba 13: Ordenar eventos por fecha
SELECT *
FROM eventos
ORDER BY Fecha_Y_Hora;

-- Caso de prueba 14: Eliminar un evento y verificar que se haya eliminado correctamente
-- Paso 1: Insertar un evento de prueba
INSERT INTO eventos (Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora)
VALUES ('Evento de prueba', 'Descripción del evento de prueba', 'Lugar de prueba', '2022-12-31 23:59:59');

-- Paso 2: Eliminar el evento de prueba
DELETE
FROM eventos
WHERE Nombre_Evento = 'Evento de prueba';

-- Paso 3: Verificar que el evento de prueba haya sido eliminado correctamente
SELECT *
FROM eventos
WHERE Nombre_Evento = 'Evento de prueba';

-- Caso de prueba 15: Insertar un evento con un nombre muy largo y verificar que se haya insertado correctamente
-- Paso 1: Insertar un evento con un nombre muy largo
INSERT INTO eventos (Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora)
VALUES ('Evento con un nombre muy largo que debería insertarse correctamente a pesar de su longitud',
        'Descripción del evento con un nombre muy largo', 'Lugar del evento con un nombre muy largo',
        '2022-12-31 23:59:59');

-- Paso 2: Verificar que el evento con un nombre muy largo haya sido insertado correctamente
SELECT *
FROM eventos
WHERE Nombre_Evento = 'Evento con un nombre muy largo que debería insertarse correctamente a pesar de su longitud';

-- Caso de prueba 16: Insertar un evento con una descripción muy larga y verificar que se haya insertado correctamente
-- Paso 1: Insertar un evento con una descripción muy larga
INSERT INTO eventos (Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora)
VALUES ('Evento con una descripción muy larga',
        'Descripción del evento con una descripción muy larga que debería insertarse correctamente a pesar de su longitud',
        'Lugar del evento con una descripción muy larga', '2022-12-31 23:59:59');

-- Paso 2: Verificar que el evento con una descripción muy larga haya sido insertado correctamente
SELECT *
FROM eventos
WHERE Nombre_Evento = 'Evento con una descripción muy larga';

-- Caso de prueba 17: Insertar un evento con un lugar muy largo y verificar que se haya insertado correctamente
-- Paso 1: Insertar un evento con un lugar muy largo
INSERT INTO eventos (Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora)
VALUES ('Evento con un lugar muy largo', 'Descripción del evento con un lugar muy largo',
        'Lugar del evento con un lugar muy largo que debería insertarse correctamente a pesar de su longitud',
        '2022-12-31 23:59:59');

-- Paso 2: Verificar que el evento con un lugar muy largo haya sido insertado correctamente
SELECT *
FROM eventos
WHERE Nombre_Evento = 'Evento con un lugar muy largo';

-- Caso de prueba 18: Insertar un evento con una fecha y hora en el pasado y verificar que se haya insertado correctamente
-- Paso 1: Insertar un evento con una fecha y hora en el pasado
INSERT INTO eventos (Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora)
VALUES ('Evento en el pasado', 'Descripción del evento en el pasado', 'Lugar del evento en el pasado',
        '2020-01-01 00:00:00');

-- Paso 2: Verificar que el evento en el pasado haya sido insertado correctamente
SELECT *
FROM eventos
WHERE Nombre_Evento = 'Evento en el pasado';

-- Caso de prueba 19: Insertar un evento con una fecha y hora en el futuro y verificar que se haya insertado correctamente
-- Paso 1: Insertar un evento con una fecha y hora en el futuro
INSERT INTO eventos (Nombre_Evento, Descripcion_Evento, Lugar, Fecha_Y_Hora)
VALUES ('Evento en el futuro', 'Descripción del evento en el futuro', 'Lugar del evento en el futuro',
        '2024-01-01 00:00:00');

-- Paso 2: Verificar que el evento en el futuro haya sido insertado correctamente
SELECT *
FROM eventos
WHERE Nombre_Evento = 'Evento en el futuro';