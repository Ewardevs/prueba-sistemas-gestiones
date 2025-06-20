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

