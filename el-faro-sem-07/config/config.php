<?php
/**
 * config/config.php
 * Configuración global del proyecto El Faro.
 * Define constantes de base de datos, rutas y ajustes generales.
 */

// ── Entorno ───────────────────────────────────────────────
define('ENV',      'development');   // 'development' | 'production'
define('APP_NAME', 'El Faro');
define('BASE_URL', 'http://localhost/elfaro_php/');

// ── Base de datos (MySQL) ─────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'elfaro_db');
define('DB_USER',    'faro_user');
define('DB_PASS',    'AIEP_password_2026-sem06');
define('DB_CHARSET', 'utf8mb4');

// ── Rutas del proyecto ────────────────────────────────────
define('ROOT_PATH',  __DIR__ . '/../');
define('VIEW_PATH',  ROOT_PATH . 'views/');
define('MODEL_PATH', ROOT_PATH . 'models/');
define('CTRL_PATH',  ROOT_PATH . 'controllers/');

// ── Mostrar errores en desarrollo ─────────────────────────
if (ENV === 'development') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
}

// ── Zona horaria ──────────────────────────────────────────
date_default_timezone_set('America/Santiago');
