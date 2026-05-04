<?php
/**
 * views/home/index.php
 * Vista principal del portal El Faro.
 *
 * Recibe del HomeController:
 *   $articulosInicio   - array de artículos sección inicio
 *   $articulosDeporte  - array de artículos sección deporte
 *   $articulosNegocios - array de artículos sección negocios
 *   $planes            - array con los planes de suscripción
 *
 * Función auxiliar renderHeroCard() y renderArticleCard()
 * evitan repetir HTML entre secciones.
 */

/**
 * Renderizar la tarjeta hero (artículo más reciente/destacado).
 * @param array  $art   Datos del artículo
 * @param string $color Color de acento de la sección
 */
function renderHeroCard(array $art, string $color = '#b8320a'): void
{
    $titulo    = htmlspecialchars($art['titulo']);
    $cuerpo    = htmlspecialchars($art['cuerpo']);
    $categoria = htmlspecialchars($art['categoria']);
    $fecha     = htmlspecialchars($art['fecha_publicacion']);
    $autor     = htmlspecialchars($art['autor_nombre'] ?? 'Redacción El Faro');
    ?>
    <div class="hero-card mb-4">
      <div style="position:absolute;inset:0;background:linear-gradient(135deg,#1a1008cc,<?= $color ?>88);"></div>
      <div class="hero-card-body">
        <span class="badge rounded-pill mb-2" style="background:<?= $color ?>;color:#fff;font-size:.72rem;letter-spacing:.1em;">
          <?= $categoria ?>
        </span>
        <h2 style="font-family:'Lora',Georgia,serif;font-size:clamp(1.3rem,3vw,2rem);font-weight:700;line-height:1.25;margin-bottom:12px;">
          <?= $titulo ?>
        </h2>
        <p style="font-size:.9rem;color:#c8b89a;margin-bottom:16px;"><?= $cuerpo ?></p>
        <div class="d-flex align-items-center justify-content-between">
          <small style="color:#a89070;"><i class="bi bi-calendar3 me-1"></i><?= $fecha ?> &bull; <?= $autor ?></small>
          <a href="#" class="btn btn-sm" style="background:<?= $color ?>;color:#fff;border:none;text-decoration:none;">
            Leer más <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
    <?php
}

/**
 * Renderizar una tarjeta de artículo secundario (small card).
 * @param array  $art      Datos del artículo
 * @param int    $index    Número de la tarjeta (para mostrar como 01, 02...)
 * @param string $catColor Color del badge de categoría
 */
function renderArticleCard(array $art, int $index, string $catColor = '#1a4d7c'): void
{
    $titulo    = htmlspecialchars($art['titulo']);
    $cuerpo    = htmlspecialchars($art['cuerpo']);
    $categoria = htmlspecialchars($art['categoria']);
    $fecha     = htmlspecialchars($art['fecha_publicacion']);
    $num       = str_pad($index, 2, '0', STR_PAD_LEFT);
    ?>
    <div class="col">
      <div class="news-card card border-0 h-100">
        <div class="card-num"><?= $num ?></div>
        <div class="card-body">
          <span class="badge rounded-pill mb-2"
                style="background:<?= $catColor ?>;color:#fff;font-size:.7rem;letter-spacing:.1em;">
            <?= $categoria ?>
          </span>
          <h6 class="card-title"><?= $titulo ?></h6>
          <p class="card-text"><?= $cuerpo ?></p>
        </div>
        <div class="card-footer-bar">
          <span class="card-date"><i class="bi bi-calendar3 me-1"></i><?= $fecha ?></span>
          <span>→ Leer más</span>
        </div>
      </div>
    </div>
    <?php
}
?>

<!-- ═══════════════════════════════════════════════════
     SECCIÓN INICIO
═══════════════════════════════════════════════════ -->
<section id="inicio" class="ef-section">
  <div class="container">
    <p class="section-eyebrow">Hoy en El Faro</p>
    <h2 class="section-heading mb-2">Noticias de Portada</h2>
    <div class="article-counter mb-4" aria-live="polite">
      <span class="ac-num"><?= count($articulosInicio) ?></span>
      <span class="ac-lbl">artículos publicados</span>
    </div>

    <?php if (!empty($articulosInicio)): ?>
      <?php renderHeroCard($articulosInicio[0], '#b8320a'); ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach (array_slice($articulosInicio, 1) as $i => $art): ?>
          <?php renderArticleCard($art, $i + 1, '#1a4d7c'); ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-muted">No hay artículos disponibles.</p>
    <?php endif; ?>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     SECCIÓN DEPORTE
═══════════════════════════════════════════════════ -->
<section id="deporte" class="ef-section" style="background:linear-gradient(180deg,#f0f7f0 0%,#f5f5f5 100%);">
  <div class="container">
    <p class="section-eyebrow">Sección deportiva</p>
    <h2 class="section-heading mb-2" style="--ef-acent:#1b6b3a;">Deporte</h2>
    <div class="article-counter mb-4" aria-live="polite">
      <span class="ac-num" style="color:#1b6b3a;"><?= count($articulosDeporte) ?></span>
      <span class="ac-lbl">artículos deportivos</span>
    </div>

    <?php if (!empty($articulosDeporte)): ?>
      <?php renderHeroCard($articulosDeporte[0], '#1b6b3a'); ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach (array_slice($articulosDeporte, 1) as $i => $art): ?>
          <?php renderArticleCard($art, $i + 1, '#1b6b3a'); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     SECCIÓN NEGOCIOS
═══════════════════════════════════════════════════ -->
<section id="negocios" class="ef-section">
  <div class="container">
    <p class="section-eyebrow">Economía y emprendimiento</p>
    <h2 class="section-heading mb-2">Negocios</h2>
    <div class="article-counter mb-4" aria-live="polite">
      <span class="ac-num" style="color:#c9933a;"><?= count($articulosNegocios) ?></span>
      <span class="ac-lbl">artículos de negocios</span>
    </div>

    <?php if (!empty($articulosNegocios)): ?>
      <?php renderHeroCard($articulosNegocios[0], '#c9933a'); ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach (array_slice($articulosNegocios, 1) as $i => $art): ?>
          <?php renderArticleCard($art, $i + 1, '#c9933a'); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════
     BANNER SUSCRIPCIÓN — Planes del periódico
═══════════════════════════════════════════════════ -->
<section class="ef-section" style="background:#f8f3e8;">
  <div class="container">
    <p class="section-eyebrow">Acceso premium</p>
    <h2 class="section-heading mb-4">Planes de Suscripción</h2>
    <div class="row g-4 justify-content-center">
      <?php foreach ($planes as $key => $plan): ?>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 text-center <?= $key === 'premium' ? 'border-warning' : '' ?>">
            <?php if ($key === 'premium'): ?>
              <div class="card-header" style="background:#c9933a;color:#fff;font-weight:600;">
                ⭐ Más popular
              </div>
            <?php endif; ?>
            <div class="card-body py-4">
              <h5 style="font-family:'Lora',Georgia,serif;"><?= htmlspecialchars($plan['nombre']) ?></h5>
              <div class="my-3">
                <span style="font-size:2.2rem;font-weight:700;color:#1a1008;">
                  <?= $plan['precio'] > 0 ? '$' . number_format($plan['precio'],0,',','.') : 'Gratis' ?>
                </span>
                <?php if ($plan['precio'] > 0): ?><small class="text-muted">/mes</small><?php endif; ?>
              </div>
              <p class="text-muted" style="font-size:.9rem;"><?= htmlspecialchars($plan['descripcion']) ?></p>
              <a href="index.php?page=register&plan=<?= $key ?>" class="btn btn-sm w-100 mt-2"
                 style="background:#b8320a;color:#fff;border:none;text-decoration:none;">
                Seleccionar <?= htmlspecialchars($plan['nombre']) ?>
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
