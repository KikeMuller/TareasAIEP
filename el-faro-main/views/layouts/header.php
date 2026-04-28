<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= isset($titulo) ? htmlspecialchars($titulo) : 'El Faro — Periódico Digital' ?></title>

  <!-- Favicon emoji 📍 — codificado como SVG inline para máxima compatibilidad -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔮</text></svg>">

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet" crossorigin="anonymous">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet" crossorigin="anonymous">
  <!-- Google Fonts: Lora + Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
  <!-- Estilos propios -->
  <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>

<?php
// ── Barra de avisos (Bootstrap Alert dismissible) ─────────
?>
<div class="alert ef-alert-bar mb-0 alert-dismissible fade show" role="alert">
  <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2 py-0">
    <div class="d-flex align-items-center gap-2" style="font-size:.82rem;">
      <span id="live-date" style="color:#c8b89a;">cargando...</span>
      <span style="width:1px;height:14px;background:#5a4030;display:inline-block;"></span>
      <span id="live-time" style="color:#c9933a;font-weight:600;font-variant-numeric:tabular-nums;font-family:monospace;font-size:.9rem;">00:00:00</span>
    </div>
    <div class="text-center flex-grow-1" style="font-size:.82rem;">
      <i class="bi bi-megaphone-fill me-1" style="color:#c9933a;font-size:14px;"></i>
      <strong style="color:#c9933a;">Aviso:</strong>
      Nueva edición disponible — Edición N° 1.848 · elfaro.cl
    </div>
    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="alert" aria-label="Cerrar aviso"></button>
  </div>
</div>

<?php // ── Masthead ─────────────────────────────────────────── ?>
<header class="ef-masthead">
  <div class="container">
    <div class="ef-ornament mb-2">✦</div>
    <div class="d-flex align-items-center justify-content-center gap-4 flex-wrap">
      <!-- Logo SVG inline -->
      <svg width="64" height="64" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Logo El Faro">
        <rect x="30" y="58" width="20" height="18" fill="#c9933a"/>
        <rect x="26" y="56" width="28" height="4" rx="1" fill="#c9933a"/>
        <polygon points="36,14 44,14 46,56 34,56" fill="#f8f3e8"/>
        <polygon points="36,14 44,14 44.5,24 35.5,24" fill="#b8320a"/>
        <polygon points="35.2,34 44.8,34 45.3,44 34.7,44" fill="#b8320a"/>
        <ellipse cx="40" cy="14" rx="7" ry="4" fill="#c9933a"/>
        <rect x="33" y="10" width="14" height="6" rx="2" fill="#c9933a"/>
        <ellipse cx="40" cy="11" rx="5" ry="3" fill="#fff3a0" opacity=".9"/>
        <line x1="40" y1="11" x2="18" y2="4"  stroke="#fff3a0" stroke-width="1.2" opacity=".7"/>
        <line x1="40" y1="11" x2="62" y2="4"  stroke="#fff3a0" stroke-width="1.2" opacity=".7"/>
        <path d="M4,74 Q14,70 24,74 Q34,78 44,74 Q54,70 64,74 Q74,78 80,74" fill="none" stroke="#1a4d7c" stroke-width="2.2"/>
        <path d="M0,78 Q10,74 20,78 Q30,82 40,78 Q50,74 60,78 Q70,82 80,78" fill="none" stroke="#1a4d7c" stroke-width="1.5" opacity=".6"/>
      </svg>
      <div class="text-center">
        <h1 class="paper-name mb-0">El <span>Faro</span></h1>
        <p class="tagline mb-0 mt-1">Iluminando la verdad desde 1979 · Periódico Digital Independiente</p>
      </div>
    </div>
    <div class="ef-ornament mt-2">✦</div>
  </div>
</header>

<?php // ── Ticker ────────────────────────────────────────────── ?>
<div class="ef-ticker" role="marquee" aria-label="Últimas noticias">
  <div class="et-label">Última hora</div>
  <div class="et-track">
    <div class="et-inner">
      <span>Colo-Colo golea 3-0 a River Plate y sella pase a semis de la Libertadores</span>
      <span>Banco Central mantiene tasa de interés en 4,5%</span>
      <span>La Roja Sub-20 clasifica al Mundial de Qatar 2027</span>
      <span>Startup Greenbite levanta US$12M en ronda Serie A</span>
    </div>
  </div>
</div>

<?php // ── Navbar Bootstrap con dropdowns ────────────────────── ?>
<nav id="mainNav" class="navbar navbar-expand-lg sticky-top" role="navigation" aria-label="Menú principal">
  <div class="container">
    <a class="navbar-brand d-lg-none" href="index.php">El Faro</a>
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navContent"
            aria-controls="navContent" aria-expanded="false" aria-label="Abrir menú">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navContent">
      <ul class="navbar-nav mx-auto">

        <!-- Inicio -->
        <li class="nav-item">
          <a class="nav-link <?= (!isset($_GET['page']) || $_GET['page']==='home') ? 'active' : '' ?>"
             href="index.php"><i class="bi bi-house-fill me-1"></i>Inicio</a>
        </li>

        <!-- Deporte -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= (isset($_GET['page']) && $_GET['page']==='articulos') ? 'active' : '' ?>"
             href="index.php?page=articulos" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-trophy-fill me-1"></i>Deporte
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="index.php">Fútbol Nacional</a></li>
            <li><a class="dropdown-item" href="index.php">Tenis ATP/WTA</a></li>
            <li><a class="dropdown-item" href="index.php">Selección Chilena</a></li>
          </ul>
        </li>

        <!-- Negocios -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-graph-up-arrow me-1"></i>Negocios
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="index.php">Startups &amp; Tecnología</a></li>
            <li><a class="dropdown-item" href="index.php">Mercado Inmobiliario</a></li>
            <li><a class="dropdown-item" href="index.php">Energías Renovables</a></li>
          </ul>
        </li>

        <!-- ══ PUBLICAR — solo visible para admin y editor ══ -->
        <?php if (!empty($_SESSION['usuario']) && in_array($_SESSION['usuario']['tipo'], ['admin', 'editor'], true)): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= (isset($_GET['page']) && str_starts_with($_GET['page'],'articulos')) ? 'active' : '' ?>"
             href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-pencil-fill me-1"></i>Redacción
          </a>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="index.php?page=articulos">
                <i class="bi bi-plus-circle me-2"></i>Nuevo artículo
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="index.php?page=articulos_lista">
                <i class="bi bi-list-ul me-2"></i>Gestionar artículos
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>

        <!-- ══ COMICS — ítem nuevo ══ -->
        <li class="nav-item">
          <a class="nav-link <?= (isset($_GET['page']) && $_GET['page']==='comics') ? 'active' : '' ?>"
             href="index.php?page=comics">
            <i class="bi bi-book-fill me-1"></i>Comics
          </a>
        </li>

        <!-- Contacto -->
        <li class="nav-item">
          <a class="nav-link <?= (isset($_GET['page']) && $_GET['page']==='contacto') ? 'active' : '' ?>"
             href="index.php?page=contacto"><i class="bi bi-envelope-fill me-1"></i>Contacto</a>
        </li>

      </ul>

      <?php // ── Botones de sesión ─────────────────────────────── ?>
      <div class="d-flex gap-2 align-items-center">
        <?php if (!empty($_SESSION['usuario'])): ?>
          <div class="dropdown">
            <button class="btn btn-sm dropdown-toggle"
                    style="background:transparent;color:#d4c5a9;border:1px solid #3a2a18;font-size:.78rem;"
                    data-bs-toggle="dropdown">
              <i class="bi bi-person-fill me-1"></i>
              <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <span class="dropdown-item-text" style="font-size:.75rem;color:#9a8060;">
                  <?= htmlspecialchars($_SESSION['usuario']['email']) ?>
                  <span class="badge ms-1" style="background:#1a4d7c;font-size:.65rem;">
                    <?= htmlspecialchars($_SESSION['usuario']['tipo']) ?>
                  </span>
                </span>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item" href="index.php?page=cambiar_password" style="font-size:.82rem;">
                  <i class="bi bi-key-fill me-2"></i>Cambiar contraseña
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item text-danger" href="index.php?page=logout" style="font-size:.82rem;">
                  <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                </a>
              </li>
            </ul>
          </div>
        <?php else: ?>
          <a href="index.php?page=login" class="btn btn-sm"
             style="background:transparent;color:#d4c5a9;border:1px solid #3a2a18;font-size:.78rem;">
            <i class="bi bi-box-arrow-in-right me-1"></i>Ingresar
          </a>
          <a href="index.php?page=register" class="btn btn-sm"
             style="background:#b8320a;color:#fff;border:none;font-size:.78rem;">
            <i class="bi bi-person-plus-fill me-1"></i>Registrarse
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<?php // El cuerpo de cada vista se inyecta aquí (entre header y footer)

// Mostrar mensaje de acceso denegado si viene de requireRole()
if (!empty($_SESSION['error_acceso'])): ?>
  <div class="alert alert-warning alert-dismissible fade show mb-0 rounded-0" role="alert"
       style="background:#fff3cd;border:none;border-bottom:3px solid #c9933a;">
    <div class="container">
      <i class="bi bi-shield-exclamation-fill me-2" style="color:#b8320a;"></i>
      <strong style="color:#b8320a;">Acceso restringido:</strong>
      <?= htmlspecialchars($_SESSION['error_acceso']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  </div>
<?php
  unset($_SESSION['error_acceso']);
endif;
?>
