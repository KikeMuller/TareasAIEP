-- ═══════════════════════════════════════════════════════
-- El Faro v6 — Procedimientos Almacenados (Stored Procedures)
-- Archivo: config/stored_procedures.sql
--
-- Requisito 4: Encapsular al menos una consulta y una inserción
-- como procedimiento almacenado en MySQL.
--
-- Cómo ejecutar (solo la primera vez, o al actualizar):
--   mysql -u root -p elfaro_db < config/stored_procedures.sql
--
-- Procedimientos incluidos:
--   1. sp_insertar_contacto   → INSERT en tabla contactos
--   2. sp_articulos_por_seccion → SELECT con JOIN en tabla articulos
-- ═══════════════════════════════════════════════════════

USE elfaro_db;

-- ── Eliminar procedimientos si ya existen (para poder recrearlos) ──
DROP PROCEDURE IF EXISTS sp_insertar_contacto;
DROP PROCEDURE IF EXISTS sp_articulos_por_seccion;


-- ══════════════════════════════════════════════════════════════════
-- PROCEDIMIENTO 1: sp_insertar_contacto
-- Descripción : Registra un nuevo mensaje del formulario de contacto.
-- Corresponde a: RF-C — Registrar datos del formulario de contacto.
--
-- Parámetros de ENTRADA:
--   p_nombre  VARCHAR(100) — Nombre del remitente
--   p_email   VARCHAR(150) — Correo del remitente
--   p_mensaje TEXT         — Contenido del mensaje
--
-- Parámetro de SALIDA:
--   p_nuevo_id INT UNSIGNED — ID del registro insertado (0 si falló)
--
-- Uso desde MySQL:
--   CALL sp_insertar_contacto('Ana López', 'ana@ejemplo.cl', 'Consulta...', @id);
--   SELECT @id;
-- ══════════════════════════════════════════════════════════════════
DELIMITER $$

CREATE PROCEDURE sp_insertar_contacto(
    IN  p_nombre   VARCHAR(100),
    IN  p_email    VARCHAR(150),
    IN  p_mensaje  TEXT,
    OUT p_nuevo_id INT UNSIGNED
)
BEGIN
    -- Bloque de manejo de errores
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Si ocurre cualquier error SQL, devolver 0 como ID
        SET p_nuevo_id = 0;
        ROLLBACK;
    END;

    START TRANSACTION;

    -- Insertar el mensaje de contacto
    INSERT INTO contactos (nombre, email, mensaje, leido)
    VALUES (p_nombre, p_email, p_mensaje, 0);

    -- Devolver el ID del registro recién insertado
    SET p_nuevo_id = LAST_INSERT_ID();

    COMMIT;
END$$

DELIMITER ;


-- ══════════════════════════════════════════════════════════════════
-- PROCEDIMIENTO 2: sp_articulos_por_seccion
-- Descripción : Obtiene artículos de una sección específica con
--               el nombre del autor, ordenados por destacado y fecha.
-- Corresponde a: RF-B — Presentar artículos por página.
--
-- Parámetros de ENTRADA:
--   p_seccion VARCHAR(20) — Sección: 'inicio', 'deporte' o 'negocios'
--   p_limite  INT         — Máximo de artículos a retornar
--
-- Uso desde MySQL:
--   CALL sp_articulos_por_seccion('deporte', 10);
-- ══════════════════════════════════════════════════════════════════
DELIMITER $$

CREATE PROCEDURE sp_articulos_por_seccion(
    IN p_seccion VARCHAR(20),
    IN p_limite  INT
)
BEGIN
    -- Validar que p_limite sea positivo; si no, usar 10 por defecto
    IF p_limite <= 0 THEN
        SET p_limite = 10;
    END IF;

    -- Consultar artículos con JOIN al nombre del autor
    SELECT
        a.id,
        a.titulo,
        a.cuerpo,
        a.categoria,
        a.seccion,
        a.autor_id,
        a.fecha_publicacion,
        a.destacado,
        COALESCE(u.nombre, 'Redacción El Faro') AS autor_nombre
    FROM articulos a
    LEFT JOIN usuarios u ON a.autor_id = u.id
    WHERE a.seccion = p_seccion
    ORDER BY
        a.destacado DESC,         -- Los artículos hero (destacado=1) van primero
        a.fecha_publicacion DESC  -- Luego los más recientes
    LIMIT p_limite;
END$$

DELIMITER ;


-- ── Verificar que los procedimientos fueron creados correctamente ──
SELECT
    ROUTINE_NAME        AS procedimiento,
    ROUTINE_TYPE        AS tipo,
    CREATED             AS creado,
    LAST_ALTERED        AS modificado
FROM information_schema.ROUTINES
WHERE ROUTINE_SCHEMA = 'elfaro_db'
  AND ROUTINE_TYPE   = 'PROCEDURE'
ORDER BY ROUTINE_NAME;
