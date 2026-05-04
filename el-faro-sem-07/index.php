<?php
/**
 * EL FARO — Periódico Digital
 * index.php — Punto de entrada único (Front Controller EMU2)
 *
 * Toda solicitud HTTP pasa por este archivo.
 * El router lee el parámetro ?page= y delega al controlador
 * correspondiente. Si no existe la página, carga el Home.
 */

// ── Cargar configuración global ──────────────────────────
require_once 'config/config.php';

// ── Autoload simple de clases (models + controllers) ─────
spl_autoload_register(function (string $class): void {
    $paths = [
        __DIR__ . '/models/'      . $class . '.php',
        __DIR__ . '/controllers/' . $class . '.php',
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ── Iniciar sesión PHP ────────────────────────────────────
session_start();

// ── Enrutador básico ─────────────────────────────────────
// Lee el parámetro "page" de la URL: ?page=register
$page = isset($_GET['page']) ? trim($_GET['page']) : 'home';

// Mapa de rutas → [controlador, método]
$routes = [
    'home'      => ['HomeController',     'index'   ],
    'articulos'        => ['ArticuloController', 'index'   ],
    'articulos_lista'   => ['ArticuloController', 'listar'  ],
    'articulos_editar'  => ['ArticuloController', 'editar'  ],
    'contacto'  => ['ContactoController', 'index'   ],
    'login'     => ['AuthController',     'login'   ],
    'register'  => ['AuthController',     'register'],
    'logout'    => ['AuthController',     'logout'  ],
    'cambiar_password' => ['AuthController', 'cambiarPassword'],
    'usuarios'          => ['AuthController', 'usuarios'       ],
    'comics'    => ['ComicController',    'index'   ],  // ← NUEVA RUTA
];

// Resolver ruta: si no existe, volver al home
if (array_key_exists($page, $routes)) {
    [$controllerName, $method] = $routes[$page];
} else {
    [$controllerName, $method] = ['HomeController', 'index'];
}

// Instanciar el controlador y llamar al método
$controller = new $controllerName();
$controller->$method();
