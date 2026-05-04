-- ═══════════════════════════════════════════════════════
-- El Faro v6 — Esquema de Base de Datos MySQL
-- Archivo: config/schema.sql
-- Ejecutar: mysql -u root -p elfaro_db < config/schema.sql
-- ═══════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS elfaro_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE elfaro_db;

-- ── Tabla: usuarios ──────────────────────────────────────
-- Entidad principal que gestiona los lectores y editores del sitio.
CREATE TABLE IF NOT EXISTS usuarios (
    id             INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    nombre         VARCHAR(100)     NOT NULL,
    email          VARCHAR(150)     NOT NULL UNIQUE,
    password_hash  VARCHAR(255)     NOT NULL,
    tipo           ENUM('lector','editor','admin') NOT NULL DEFAULT 'lector',
    fecha_registro TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    activo         TINYINT(1)       NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Tabla: suscripciones ─────────────────────────────────
-- Vincula usuarios con su plan de acceso al diario.
CREATE TABLE IF NOT EXISTS suscripciones (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuario_id    INT UNSIGNED NOT NULL,
    plan          ENUM('gratuito','basico','premium') NOT NULL DEFAULT 'gratuito',
    fecha_inicio  DATE         NOT NULL,
    fecha_fin     DATE         NULL,
    activa        TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Tabla: articulos ─────────────────────────────────────
-- Almacena las noticias publicadas en el periódico.
CREATE TABLE IF NOT EXISTS articulos (
    id                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titulo             VARCHAR(300) NOT NULL,
    cuerpo             TEXT         NOT NULL,
    categoria          VARCHAR(60)  NOT NULL,
    seccion            ENUM('inicio','deporte','negocios') NOT NULL DEFAULT 'inicio',
    autor_id           INT UNSIGNED NULL,
    fecha_publicacion  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    destacado          TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    INDEX idx_seccion (seccion),
    INDEX idx_destacado (destacado),
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Tabla: contactos ─────────────────────────────────────
-- Almacena los mensajes enviados mediante el formulario de contacto.
CREATE TABLE IF NOT EXISTS contactos (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre      VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL,
    mensaje     TEXT         NOT NULL,
    fecha_envio TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    leido       TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Tabla: comics ────────────────────────────────────────
-- Almacena referencias a comics externos (War and Peas).
-- No guarda imágenes localmente: solo la URL de la fuente original.
-- Fuente: https://warandpeas.com/ — © Elizabeth Pich & Jonathan Kunz
CREATE TABLE IF NOT EXISTS comics (
    id                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titulo            VARCHAR(200) NOT NULL,
    imagen_url        VARCHAR(500) NOT NULL,
    enlace_original   VARCHAR(500) NOT NULL,
    descripcion       TEXT         NULL,
    categoria         VARCHAR(80)  NOT NULL DEFAULT 'humor',
    fecha_publicacion TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    activo            TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    INDEX idx_categoria (categoria),
    INDEX idx_activo    (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Datos de prueba ───────────────────────────────────────
-- Usuario administrador de prueba (password: Admin1234)
INSERT INTO usuarios (nombre, email, password_hash, tipo) VALUES
('Administrador El Faro', 'admin@elfaro.cl',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Juan Redactor', 'redactor@elfaro.cl',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'editor');

INSERT INTO suscripciones (usuario_id, plan, fecha_inicio) VALUES (1, 'premium', CURDATE()), (2, 'basico', CURDATE());

-- ── Comics de War and Peas (datos de referencia) ─────────
INSERT INTO comics (titulo, imagen_url, enlace_original, descripcion, categoria) VALUES
(
  'Artefacts',
  'https://warandpeas.com/wp-content/uploads/2026/04/war-and-peas-artefacts-768x960.jpg',
  'https://warandpeas.com/2026/04/19/artefacts/',
  'Dos astronautas alienígenas descubren un artefacto humano llamado Internet y encuentran un video de un gato. El comandante llora de emoción.',
  'Ciencia ficción'
),
(
  'No Work Today',
  'https://warandpeas.com/wp-content/uploads/2026/04/war-and-peas-no-work-today-768x960.jpg',
  'https://warandpeas.com/2026/04/12/no-work-today/',
  'Un castor llega al trabajo y descubre que los humanos construyeron una represa enorme en su lugar. Solicita subsidio de cesantía.',
  'Humor negro'
),
(
  'Still Pissed',
  'https://warandpeas.com/wp-content/uploads/2026/04/war-and-peas-still-pissed-768x960.jpg',
  'https://warandpeas.com/2026/04/05/still-pissed/',
  'Jesús en terapia discutiendo su fantasía de resurrección con un terapeuta escéptico.',
  'Humor existencial'
);
