--Obtener usuarios registrados en los últimos 30 días.
SELECT *
FROM usuarios
WHERE fecha_creacion >= NOW() - INTERVAL '30 days';

--Actualizar el nombre de un usuario por ID.
UPDATE usuarios
SET nombre = 'NuevoNombre'
WHERE id = 113;

--Eliminar usuarios con más de un año de antigüedad.
DELETE FROM usuarios
WHERE fecha_creacion < NOW() - INTERVAL '1 year';


