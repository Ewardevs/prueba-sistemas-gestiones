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

select * from usuarios;
