<?php
/**
 * views/comics/index.php
 * Vista de la sección Comics — El Faro.
 *
 * Muestra una galería de comics de War and Peas con:
 *  - Filtro por categoría (Bootstrap pills)
 *  - Grid responsivo de tarjetas (3 cols desktop / 2 tablet / 1 móvil)
 *  - Modal de imagen ampliada (Bootstrap Modal)
 *  - Enlace a la fuente original en warandpeas.com
 *  - Créditos al autor
 *
 * Recibe del ComicController:
 *   $comics          - array de comics (filtrados o todos)
 *   $categorias      - array de categorías únicas
 *   $categoriaActiva - string, categoría seleccionada (vacío = todas)
 *   $totalComics     - int, total sin filtrar
 */
?>

<!-- ═══════════════════════════════════════════════════════
     SECCIÓN COMICS — Hero header
═══════════════════════════════════════════════════════ -->
<section style="background:var(--ef-ink);padding:52px 0 44px;border-bottom:5px solid #6d2e46;">
  <div class="container text-center">
    <div class="mb-3">
      <span style="font-size:3rem;line-height:1;">🎭</span>
    </div>
    <p style="font-size:.72rem;letter-spacing:.4em;text-transform:uppercase;color:#9673a6;margin-bottom:6px;">
      Entretenimiento editorial
    </p>
    <h2 style="font-family:'Lora',Georgia,serif;font-size:2.8rem;font-weight:700;color:#f8f3e8;margin-bottom:10px;">
      Comics
    </h2>
    <p style="font-size:.95rem;color:#c8b89a;max-width:560px;margin:0 auto 20px;">
      Humor inteligente, absurdo y existencial para la mente del lector moderno.
      Selección editorial de <strong style="color:#e1d5e7;">War and Peas</strong>.
    </p>
    <div class="d-inline-flex align-items-center gap-3 px-4 py-2 rounded-3"
         style="background:rgba(109,46,70,.3);border:1px solid rgba(109,46,70,.5);">
      <span style="font-size:.82rem;color:#d4c5a9;">
        Fuente:
        <a href="https://warandpeas.com/" target="_blank" rel="noopener noreferrer"
           style="color:#e1d5e7;font-weight:600;">warandpeas.com</a>
      </span>
      <span style="width:1px;height:14px;background:#6d2e46;display:inline-block;"></span>
      <span style="font-size:.82rem;color:#d4c5a9;">
        © Elizabeth Pich &amp; Jonathan Kunz
      </span>
    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════════
     FILTROS DE CATEGORÍA — Bootstrap Nav pills
═══════════════════════════════════════════════════════ -->
<section style="background:#f0e8f5;padding:20px 0;border-bottom:1px solid #d8c5e0;">
  <div class="container">
    <div class="d-flex align-items-center gap-3 flex-wrap">

      <div class="article-counter me-2" aria-live="polite">
        <span class="ac-num" style="color:#6d2e46;"><?= count($comics) ?></span>
        <span class="ac-lbl">
          <?= $categoriaActiva !== '' ? 'resultados' : 'comics disponibles' ?>
        </span>
      </div>

      <div class="d-flex gap-2 flex-wrap">
        <a href="index.php?page=comics"
           class="badge rounded-pill text-decoration-none px-3 py-2"
           style="font-size:.8rem;
                  background:<?= $categoriaActiva==='' ? '#6d2e46' : 'rgba(109,46,70,.15)' ?>;
                  color:<?= $categoriaActiva==='' ? '#fff' : '#6d2e46' ?>;
                  border:1px solid #9673a6;">
          Todos (<?= $totalComics ?>)
        </a>

        <?php foreach ($categorias as $cat): ?>
          <a href="index.php?page=comics&cat=<?= urlencode($cat) ?>"
             class="badge rounded-pill text-decoration-none px-3 py-2"
             style="font-size:.8rem;
                    background:<?= strtolower($categoriaActiva)===strtolower($cat) ? '#6d2e46' : 'rgba(109,46,70,.15)' ?>;
                    color:<?= strtolower($categoriaActiva)===strtolower($cat) ? '#fff' : '#6d2e46' ?>;
                    border:1px solid #9673a6;">
            <?= htmlspecialchars($cat) ?>
          </a>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════════
     GALERÍA DE COMICS — Bootstrap Grid responsivo
═══════════════════════════════════════════════════════ -->
<section class="ef-section" style="background:#f5f0fa;">
  <div class="container">

    <?php if (empty($comics)): ?>
      <div class="text-center py-5">
        <div style="font-size:3.5rem;margin-bottom:16px;">🔍</div>
        <h4 style="font-family:'Lora',Georgia,serif;color:#6d2e46;">
          No hay comics en esta categoría
        </h4>
        <p class="text-muted mb-4">Prueba otra categoría o vuelve a ver todos.</p>
        <a href="index.php?page=comics" class="btn"
           style="background:#6d2e46;color:#fff;border:none;">
          Ver todos los comics
        </a>
      </div>

    <?php else: ?>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($comics as $i => $comic): ?>
          <?php
          $modalId   = 'modal-comic-' . $comic['id'];
          $titulo    = htmlspecialchars($comic['titulo']);
          $imagenUrl = htmlspecialchars($comic['imagen_url']);
          $enlace    = htmlspecialchars($comic['enlace_original']);
          $desc      = htmlspecialchars($comic['descripcion']);
          $cat       = htmlspecialchars($comic['categoria']);
          $fecha     = htmlspecialchars($comic['fecha_publicacion']);
          $tags      = $comic['tags'] ?? [];
          $num       = str_pad($i + 1, 2, '0', STR_PAD_LEFT);

          // ── CORRECCIÓN: usar strlen/substr en lugar de mb_strlen/mb_substr
          // mb_string puede no estar habilitado en todos los servidores
          $descCorta = strlen($desc) > 100 ? substr($desc, 0, 100) . '…' : $desc;
          ?>

          <div class="col">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius:12px;overflow:hidden;
                        transition:transform .2s, box-shadow .2s;cursor:pointer;"
                 onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 12px 30px rgba(109,46,70,.18)'"
                 onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">

              <!-- Número del comic -->
              <div style="position:absolute;top:12px;left:12px;z-index:2;
                          background:#6d2e46;color:#fff;border-radius:50%;
                          width:32px;height:32px;display:flex;align-items:center;
                          justify-content:center;font-family:'Lora',Georgia,serif;
                          font-size:.85rem;font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.2);">
                <?= $num ?>
              </div>

              <!-- Imagen (clic abre modal) -->
              <div data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>"
                   style="overflow:hidden;background:#f8f0fc;max-height:340px;
                          display:flex;align-items:center;justify-content:center;">
                <img src="<?= $imagenUrl ?>"
                     alt="<?= $titulo ?> — War and Peas"
                     loading="lazy"
                     style="width:100%;object-fit:contain;transition:transform .3s;"
                     onmouseover="this.style.transform='scale(1.03)'"
                     onmouseout="this.style.transform='scale(1)'"
                     onerror="this.src='https://placehold.co/400x500/6d2e46/ffffff?text=Comic+no+disponible'">
              </div>

              <!-- Cuerpo -->
              <div class="card-body d-flex flex-column" style="padding:16px 18px 12px;">
                <span class="badge rounded-pill mb-2"
                      style="background:rgba(109,46,70,.12);color:#6d2e46;
                             font-size:.7rem;letter-spacing:.08em;width:fit-content;">
                  <?= $cat ?>
                </span>

                <h5 class="card-title mb-1"
                    style="font-family:'Lora',Georgia,serif;font-size:1.05rem;
                           font-weight:700;color:#1a1008;line-height:1.3;">
                  <?= $titulo ?>
                </h5>

                <!-- Descripción corta — sin mb_strlen ni mb_substr -->
                <p class="card-text flex-grow-1 mb-3"
                   style="font-size:.83rem;color:#6b7280;line-height:1.55;">
                  <?= $descCorta ?>
                </p>

                <!-- Tags -->
                <?php if (!empty($tags)): ?>
                  <div class="d-flex flex-wrap gap-1 mb-3">
                    <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
                      <span style="font-size:.68rem;padding:2px 8px;border-radius:3px;
                                   background:#f0e8f5;color:#6d2e46;border:1px solid #d8c5e0;">
                        #<?= htmlspecialchars($tag) ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>

                <!-- Footer de la tarjeta -->
                <div class="d-flex align-items-center justify-content-between mt-auto pt-2"
                     style="border-top:1px solid #f0e8f5;">
                  <small style="color:#9a8060;font-size:.72rem;">
                    <i class="bi bi-calendar3 me-1"></i><?= $fecha ?>
                  </small>
                  <div class="d-flex gap-2">
                    <button type="button"
                            class="btn btn-sm"
                            style="background:#6d2e46;color:#fff;border:none;
                                   font-size:.75rem;padding:4px 10px;"
                            data-bs-toggle="modal"
                            data-bs-target="#<?= $modalId ?>">
                      <i class="bi bi-zoom-in me-1"></i>Ver
                    </button>
                    <a href="<?= $enlace ?>" target="_blank" rel="noopener noreferrer"
                       class="btn btn-sm"
                       style="background:transparent;color:#6d2e46;
                              border:1px solid #9673a6;font-size:.75rem;
                              padding:4px 10px;text-decoration:none;">
                      <i class="bi bi-box-arrow-up-right me-1"></i>Original
                    </a>
                  </div>
                </div>

              </div><!-- /card-body -->
            </div><!-- /card -->
          </div><!-- /col -->


          <!-- MODAL Bootstrap -->
          <div class="modal fade" id="<?= $modalId ?>"
               tabindex="-1" aria-labelledby="<?= $modalId ?>-label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content border-0" style="border-radius:14px;overflow:hidden;">

                <div class="modal-header border-0"
                     style="background:#6d2e46;padding:14px 20px;">
                  <div>
                    <h5 class="modal-title text-white mb-0"
                        id="<?= $modalId ?>-label"
                        style="font-family:'Lora',Georgia,serif;">
                      <?= $titulo ?>
                    </h5>
                    <small style="color:#e1d5e7;font-size:.75rem;">
                      War and Peas · <?= $fecha ?>
                    </small>
                  </div>
                  <button type="button" class="btn-close btn-close-white ms-auto"
                          data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body p-0 text-center" style="background:#f8f0fc;">
                  <img src="<?= $imagenUrl ?>"
                       alt="<?= $titulo ?> — War and Peas"
                       style="max-width:100%;max-height:75vh;object-fit:contain;"
                       onerror="this.src='https://placehold.co/600x750/6d2e46/ffffff?text=<?= urlencode($titulo) ?>'">
                </div>

                <div class="modal-footer border-0"
                     style="background:#f8f0fc;padding:12px 20px;">
                  <div class="flex-grow-1">
                    <p class="mb-1" style="font-size:.85rem;color:#3d2030;">
                      <?= $desc ?>
                    </p>
                    <small style="color:#9673a6;">
                      <i class="bi bi-tag-fill me-1"></i>
                      Categoría: <strong><?= $cat ?></strong>
                      &nbsp;·&nbsp;
                      <i class="bi bi-palette-fill me-1"></i>
                      © Elizabeth Pich &amp; Jonathan Kunz
                    </small>
                  </div>
                  <div class="d-flex gap-2 ms-3">
                    <a href="<?= $enlace ?>" target="_blank" rel="noopener noreferrer"
                       class="btn btn-sm"
                       style="background:#6d2e46;color:#fff;border:none;text-decoration:none;">
                      <i class="bi bi-box-arrow-up-right me-1"></i>Ver en War and Peas
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">
                      Cerrar
                    </button>
                  </div>
                </div>

              </div>
            </div>
          </div><!-- /modal -->

        <?php endforeach; ?>
      </div><!-- /row -->

    <?php endif; ?>

  </div>
</section>


<!-- CRÉDITOS -->
<section style="background:#3d1a2a;padding:32px 0;">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-md-8">
        <h5 style="font-family:'Lora',Georgia,serif;color:#f8f3e8;margin-bottom:8px;">
          Sobre War and Peas
        </h5>
        <p style="font-size:.88rem;color:#c8b89a;margin-bottom:0;line-height:1.7;">
          War and Peas es un webcomic de humor oscuro, absurdo y existencial creado por
          <strong style="color:#e1d5e7;">Elizabeth Pich</strong> y
          <strong style="color:#e1d5e7;">Jonathan Kunz</strong> desde Saarbrücken, Alemania.
          Sus historietas exploran temas como la muerte, la tecnología, la naturaleza humana
          y las absurdidades de la vida cotidiana con un estilo minimalista e ingenioso.
          Todos los derechos reservados a sus autores.
        </p>
      </div>
      <div class="col-md-4 text-md-end">
        <a href="https://warandpeas.com/" target="_blank" rel="noopener noreferrer"
           class="btn"
           style="background:#9673a6;color:#fff;border:none;text-decoration:none;
                  padding:10px 24px;font-weight:600;">
          <i class="bi bi-box-arrow-up-right me-2"></i>Visitar warandpeas.com
        </a>
        <div class="mt-2">
          <a href="https://www.instagram.com/warandpeas/" target="_blank" rel="noopener"
             style="color:#c8b89a;font-size:.8rem;text-decoration:none;margin-right:14px;">
            <i class="bi bi-instagram me-1"></i>Instagram
          </a>
          <a href="https://www.patreon.com/warandpeas" target="_blank" rel="noopener"
             style="color:#c8b89a;font-size:.8rem;text-decoration:none;">
            <i class="bi bi-heart-fill me-1" style="color:#9673a6;"></i>Patreon
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
