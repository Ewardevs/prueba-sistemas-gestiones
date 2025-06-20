CREATE DATABASE tareas;


CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tareas (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    estado BOOLEAN DEFAULT FALSE, -- FALSE = pendiente, TRUE = completada
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE CASCADE
);

DO $$
BEGIN
  FOR i IN 1..50 LOOP
    INSERT INTO usuarios (nombre, correo, fecha_creacion)
    VALUES (
      'Usuario_' || i,
      'usuario_' || i || '@ejemplo.com',
      NOW() - (TRUNC(RANDOM() * 90) || ' days')::INTERVAL
    );
  END LOOP;
END
$$;

select * from tareas;

DO $$
BEGIN
  FOR i IN 1..30 LOOP
    INSERT INTO tareas (titulo, descripcion, estado, fecha_creacion, usuario_id)
    VALUES (
      'Tarea #' || i,
      'Descripción de la tarea número ' || i,
      (CASE WHEN random() < 0.5 THEN false ELSE true END),
      NOW() - (TRUNC(RANDOM() * 60) || ' days')::INTERVAL,
      (SELECT id FROM usuarios ORDER BY RANDOM() LIMIT 1) -- asigna usuario aleatorio
    );
  END LOOP;
END
$$;

--ejercicios

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





