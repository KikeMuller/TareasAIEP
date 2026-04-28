<?php
/**
 * views/articulos/editar.php
 * Formulario para editar un artículo existente.
 *
 * Recibe de ArticuloController::editar():
 *   $articulo - array con los datos actuales del artículo
 *   $errores  - array de errores de validación
 *   $exito    - bool, true si se guardó correctamente
 */
?>

<section class="ef-section" style="background:#f5f5f5;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <!-- Encabezado con botón volver -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
          <div>
            <p class="section-eyebrow">Panel de redacción</p>
            <h2 class="section-heading mb-0">Editar artículo</h2>
          </div>
          <a href="index.php?page=articulos_lista"
             class="btn btn-sm"
             style="background:transparent;border:1px solid #3a2a18;
                    color:#1a1008;text-decoration:none;font-size:.82rem;">
            <i class="bi bi-arrow-left me-1"></i>Volver al listado
          </a>
        </div>

        <!-- Alerta de éxito -->
        <?php if ($exito): ?>
          <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill" style="font-size:18px;"></i>
            <div>
              <strong>¡Artículo actualizado correctamente!</strong>
              <a href="index.php?page=articulos_lista" class="alert-link ms-2">
                Ver todos los artículos →
              </a>
            </div>
          </div>
        <?php endif; ?>

        <!-- Tarjeta del formulario -->
        <div class="card border-0 shadow-sm overflow-hidden">
          <div class="card-header py-3" style="background:#1a1008;">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-pencil-square text-white" style="font-size:1rem;"></i>
              <h5 class="mb-0 text-white" style="font-family:'Lora',Georgia,serif;">
                Artículo #<?= (int) $articulo['id'] ?>
              </h5>
              <?php if (!empty($articulo['destacado'])): ?>
                <span class="badge ms-auto"
                      style="background:#c9933a;font-size:.72rem;">
                  <i class="bi bi-star-fill me-1"></i>Destacado
                </span>
              <?php endif; ?>
            </div>
          </div>

          <div class="card-body p-4">
            <!-- Formulario POST → ArticuloController::editar() -->
            <form method="POST"
                  action="index.php?page=articulos_editar&id=<?= (int) $articulo['id'] ?>"
                  novalidate>

              <!-- Fila: título + categoría -->
              <div class="row g-3 mb-3">
                <div class="col-md-8">
                  <label for="titulo"
                         class="form-label fw-semibold"
                         style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                    Título *
                  </label>
                  <input type="text"
                         id="titulo"
                         name="titulo"
                         class="form-control <?= isset($errores['titulo']) ? 'is-invalid' : '' ?>"
                         value="<?= htmlspecialchars($articulo['titulo']) ?>"
                         required>
                  <?php if (isset($errores['titulo'])): ?>
                    <div class="invalid-feedback">
                      <?= htmlspecialchars($errores['titulo']) ?>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="col-md-4">
                  <label for="categoria"
                         class="form-label fw-semibold"
                         style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                    Categoría
                  </label>
                  <input type="text"
                         id="categoria"
                         name="categoria"
                         class="form-control"
                         value="<?= htmlspecialchars($articulo['categoria'] ?? '') ?>"
                         placeholder="Ej: Fútbol, Startup...">
                </div>
              </div>

              <!-- Fila: sección + destacado -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="seccion"
                         class="form-label fw-semibold"
                         style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                    Sección
                  </label>
                  <select id="seccion" name="seccion" class="form-select">
                    <option value="inicio"
                      <?= ($articulo['seccion'] ?? '') === 'inicio'   ? 'selected' : '' ?>>
                      Inicio
                    </option>
                    <option value="deporte"
                      <?= ($articulo['seccion'] ?? '') === 'deporte'  ? 'selected' : '' ?>>
                      Deporte
                    </option>
                    <option value="negocios"
                      <?= ($articulo['seccion'] ?? '') === 'negocios' ? 'selected' : '' ?>>
                      Negocios
                    </option>
                  </select>
                </div>

                <div class="col-md-6 d-flex align-items-end pb-1">
                  <div class="form-check">
                    <input type="checkbox"
                           id="destacado"
                           name="destacado"
                           class="form-check-input"
                           value="1"
                           <?= !empty($articulo['destacado']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="destacado">
                      <i class="bi bi-star-fill me-1" style="color:#c9933a;font-size:.85rem;"></i>
                      Artículo destacado (hero)
                    </label>
                  </div>
                </div>
              </div>

              <!-- Cuerpo del artículo -->
              <div class="mb-4">
                <label for="cuerpo"
                       class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Cuerpo del artículo *
                </label>
                <textarea id="cuerpo"
                          name="cuerpo"
                          rows="7"
                          class="form-control <?= isset($errores['cuerpo']) ? 'is-invalid' : '' ?>"
                          required><?= htmlspecialchars($articulo['cuerpo']) ?></textarea>
                <?php if (isset($errores['cuerpo'])): ?>
                  <div class="invalid-feedback">
                    <?= htmlspecialchars($errores['cuerpo']) ?>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Botones de acción -->
              <div class="d-flex gap-2 align-items-center">
                <button type="submit"
                        class="btn fw-bold text-white px-4 py-2"
                        style="background:#b8320a;border-color:#b8320a;">
                  <i class="bi bi-floppy-fill me-2"></i>Guardar cambios
                </button>
                <a href="index.php?page=articulos_lista"
                   class="btn btn-outline-secondary px-4 py-2"
                   style="text-decoration:none;">
                  Cancelar
                </a>
              </div>

            </form>
          </div><!-- /card-body -->
        </div><!-- /card -->

        <!-- Metadatos del artículo -->
        <div class="mt-3 px-1" style="font-size:.78rem;color:#9a8060;">
          <i class="bi bi-info-circle me-1"></i>
          Publicado el
          <?= isset($articulo['fecha_publicacion'])
              ? date('d/m/Y H:i', strtotime($articulo['fecha_publicacion']))
              : '—' ?>
          <?php if (!empty($articulo['autor_nombre'])): ?>
            · Autor original:
            <strong><?= htmlspecialchars($articulo['autor_nombre']) ?></strong>
          <?php endif; ?>
        </div>

      </div><!-- /col -->
    </div><!-- /row -->
  </div><!-- /container -->
</section>
