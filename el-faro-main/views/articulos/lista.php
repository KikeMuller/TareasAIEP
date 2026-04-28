<?php
/**
 * views/articulos/lista.php
 * Panel de gestión de artículos para admin y editor.
 *
 * Recibe de ArticuloController::listar():
 *   $articulos - array con todos los artículos de la BD
 */

// Mapas de colores por sección y etiquetas de sección
$coloresSec = ['inicio' => '#1a4d7c', 'deporte' => '#1b6b3a', 'negocios' => '#c9933a'];
$labelSec   = ['inicio' => 'Inicio',  'deporte' => 'Deporte', 'negocios' => 'Negocios'];
?>

<section class="ef-section" style="background:#f5f5f5;">
  <div class="container">

    <!-- Encabezado -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
      <div>
        <p class="section-eyebrow">Panel de redacción</p>
        <h2 class="section-heading mb-0">Gestionar artículos</h2>
      </div>
      <a href="index.php?page=articulos" class="btn text-white fw-semibold"
         style="background:#b8320a;border:none;text-decoration:none;">
        <i class="bi bi-plus-lg me-1"></i>Nuevo artículo
      </a>
    </div>

    <!-- Tabla de artículos -->
    <?php if (empty($articulos)): ?>
      <div class="text-center py-5">
        <div style="font-size:3rem;margin-bottom:12px;">📭</div>
        <p class="text-muted">No hay artículos publicados todavía.</p>
        <a href="index.php?page=articulos" class="btn text-white"
           style="background:#b8320a;border:none;text-decoration:none;">
          Publicar el primero
        </a>
      </div>

    <?php else: ?>
      <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header py-3 d-flex align-items-center gap-2"
             style="background:#1a1008;">
          <i class="bi bi-newspaper text-white" style="font-size:1rem;"></i>
          <span class="text-white fw-semibold" style="font-size:.85rem;letter-spacing:.08em;">
            <?= count($articulos) ?> artículo<?= count($articulos) !== 1 ? 's' : '' ?> publicados
          </span>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size:.88rem;">
            <thead style="background:#f8f3e8;">
              <tr>
                <th style="width:40px;padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">#</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">Título</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">Sección</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">Categoría</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">Autor</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">Fecha</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;width:80px;">Hero</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;text-align:right;">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($articulos as $art): ?>
                <?php
                $sec    = $art['seccion'] ?? 'inicio';
                $color  = $coloresSec[$sec] ?? '#1a4d7c';
                $label  = $labelSec[$sec]   ?? $sec;
                $fecha  = isset($art['fecha_publicacion'])
                          ? date('d/m/Y', strtotime($art['fecha_publicacion']))
                          : '—';
                $autor  = htmlspecialchars($art['autor_nombre'] ?? 'Sin autor');
                ?>
                <tr>
                  <!-- ID -->
                  <td style="padding:12px 16px;color:#9a8060;font-weight:600;">
                    <?= (int) $art['id'] ?>
                  </td>

                  <!-- Título -->
                  <td style="padding:12px 16px;max-width:280px;">
                    <span style="font-family:'Lora',Georgia,serif;font-weight:600;
                                 color:#1a1008;display:block;white-space:nowrap;
                                 overflow:hidden;text-overflow:ellipsis;max-width:280px;">
                      <?= htmlspecialchars($art['titulo']) ?>
                    </span>
                    <small style="color:#9a8060;font-size:.75rem;">
                      <?= htmlspecialchars(substr($art['cuerpo'], 0, 60)) ?>…
                    </small>
                  </td>

                  <!-- Sección -->
                  <td style="padding:12px 16px;">
                    <span class="badge rounded-pill"
                          style="background:<?= $color ?>;color:#fff;font-size:.72rem;">
                      <?= $label ?>
                    </span>
                  </td>

                  <!-- Categoría -->
                  <td style="padding:12px 16px;color:#6b7280;">
                    <?= htmlspecialchars($art['categoria'] ?? '—') ?>
                  </td>

                  <!-- Autor -->
                  <td style="padding:12px 16px;color:#6b7280;">
                    <i class="bi bi-person-fill me-1" style="font-size:.8rem;"></i>
                    <?= $autor ?>
                  </td>

                  <!-- Fecha -->
                  <td style="padding:12px 16px;color:#9a8060;white-space:nowrap;">
                    <i class="bi bi-calendar3 me-1" style="font-size:.8rem;"></i>
                    <?= $fecha ?>
                  </td>

                  <!-- Hero -->
                  <td style="padding:12px 16px;text-align:center;">
                    <?php if (!empty($art['destacado'])): ?>
                      <i class="bi bi-star-fill" style="color:#c9933a;font-size:1rem;"
                         title="Artículo destacado"></i>
                    <?php else: ?>
                      <i class="bi bi-star" style="color:#d0c8b8;font-size:1rem;"></i>
                    <?php endif; ?>
                  </td>

                  <!-- Acciones -->
                  <td style="padding:12px 16px;text-align:right;white-space:nowrap;">
                    <a href="index.php?page=articulos_editar&id=<?= (int) $art['id'] ?>"
                       class="btn btn-sm"
                       style="background:#1a4d7c;color:#fff;border:none;
                              font-size:.78rem;text-decoration:none;">
                      <i class="bi bi-pencil-fill me-1"></i>Editar
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div><!-- /table-responsive -->
      </div><!-- /card -->
    <?php endif; ?>

  </div><!-- /container -->
</section>
