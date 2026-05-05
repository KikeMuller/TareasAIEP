<?php
/**
 * views/articulos/nuevo.php
 * Vista para publicar un nuevo artículo (requiere sesión).
 */
?>
<section class="ef-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <p class="section-eyebrow">Redacción</p>
        <h2 class="section-heading mb-4">Publicar nuevo artículo</h2>

        <?php if ($exito): ?>
          <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><strong>¡Artículo publicado!</strong></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm overflow-hidden">
          <div class="card-header py-3" style="background:#1a1008;">
            <h5 class="mb-0 text-white" style="font-family:'Lora',Georgia,serif;">
              <i class="bi bi-pencil-fill me-2"></i>Nuevo artículo
            </h5>
          </div>
          <div class="card-body p-4">
            <form method="POST" action="index.php?page=articulos">
              <div class="row g-3 mb-3">
                <div class="col-md-8">
                  <label class="form-label fw-semibold" style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">Título *</label>
                  <input type="text" name="titulo" class="form-control <?= isset($errores['titulo']) ? 'is-invalid' : '' ?>" placeholder="Título del artículo..." required>
                  <?php if (isset($errores['titulo'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['titulo']) ?></div><?php endif; ?>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-semibold" style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">Categoría</label>
                  <input type="text" name="categoria" class="form-control" placeholder="Ej: Fútbol, Startup...">
                </div>
              </div>
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold" style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">Sección</label>
                  <select name="seccion" class="form-select">
                    <option value="inicio">Inicio</option>
                    <option value="deporte">Deporte</option>
                    <option value="negocios">Negocios</option>
                  </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                  <div class="form-check">
                    <input type="checkbox" name="destacado" id="destacado" class="form-check-input" value="1">
                    <label class="form-check-label" for="destacado">Artículo destacado (hero)</label>
                  </div>
                </div>
              </div>
              <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">Cuerpo del artículo *</label>
                <textarea name="cuerpo" rows="6" class="form-control <?= isset($errores['cuerpo']) ? 'is-invalid' : '' ?>" placeholder="Escribe el contenido del artículo..." required></textarea>
                <?php if (isset($errores['cuerpo'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['cuerpo']) ?></div><?php endif; ?>
              </div>
              <button type="submit" class="btn fw-bold text-white px-4 py-2" style="background:#b8320a;border-color:#b8320a;">
                <i class="bi bi-send-fill me-2"></i>Publicar artículo
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
