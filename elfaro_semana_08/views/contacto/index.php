<?php
/**
 * views/contacto/index.php
 * Vista del formulario de contacto.
 *
 * Recibe del ContactoController:
 *   $errores  - array con mensajes de error por campo
 *   $exito    - bool, true si el mensaje fue enviado
 *   $formData - valores del formulario para repoblar en caso de error
 *
 * Envía datos por POST a index.php?page=contacto
 */
?>

<section class="ef-section" style="background:#fff;">
  <div class="container">

    <p class="section-eyebrow">Escríbenos</p>
    <h2 class="section-heading mb-4">Contacto</h2>

    <div class="row g-5 align-items-start">

      <!-- ── Formulario de contacto ── -->
      <div class="col-lg-7">
        <div class="card border-0 shadow-sm overflow-hidden">
          <div class="card-header py-3" style="background:#1a4d7c;">
            <h5 class="mb-0 text-white" style="font-family:'Lora',Georgia,serif;">
              <i class="bi bi-envelope-fill me-2"></i>Envíanos tu mensaje
            </h5>
          </div>
          <div class="card-body p-4">

            <?php if ($exito): ?>
              <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill" style="font-size:20px;"></i>
                <div>
                  <strong>¡Mensaje enviado correctamente!</strong>
                  Te responderemos a la brevedad.
                </div>
              </div>
            <?php endif; ?>

            <!-- Formulario POST → ContactoController::index() -->
            <form method="POST" action="index.php?page=contacto" novalidate>

              <!-- Campo: Nombre -->
              <div class="mb-3">
                <label for="nombre" class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Nombre completo *
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       class="form-control <?= isset($errores['nombre']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($formData['nombre'] ?? '') ?>"
                       placeholder="Escribe tu nombre..."
                       required>
                <?php if (isset($errores['nombre'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errores['nombre']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Campo: Email -->
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Correo electrónico *
                </label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control <?= isset($errores['email']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                       placeholder="tu@correo.cl"
                       required>
                <?php if (isset($errores['email'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errores['email']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Campo: Mensaje -->
              <div class="mb-4">
                <label for="mensaje" class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Mensaje *
                </label>
                <textarea id="mensaje"
                          name="mensaje"
                          rows="5"
                          class="form-control <?= isset($errores['mensaje']) ? 'is-invalid' : '' ?>"
                          placeholder="Escribe tu mensaje, consulta o sugerencia..."
                          required><?= htmlspecialchars($formData['mensaje'] ?? '') ?></textarea>
                <?php if (isset($errores['mensaje'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errores['mensaje']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Botón de envío -->
              <button type="submit" class="btn w-100 fw-bold py-2 text-white"
                      style="background:#1a4d7c;border-color:#1a4d7c;">
                <i class="bi bi-send-fill me-2"></i>Enviar mensaje
              </button>

            </form>

          </div><!-- /card-body -->
        </div><!-- /card -->
      </div><!-- /col formulario -->

      <!-- ── Panel de información de contacto ── -->
      <div class="col-lg-5">
        <div class="rounded-3 p-4" style="background:#1a1008;color:#c8b89a;">
          <h3 style="font-family:'Lora',Georgia,serif;color:#f8f3e8;font-size:1.4rem;margin-bottom:8px;">El Faro</h3>
          <p style="font-size:.88rem;margin-bottom:20px;">
            Fundado en 1979. Comprometidos con la verdad y el rigor periodístico.
          </p>
          <div class="d-flex flex-column gap-3">
            <div class="d-flex gap-3">
              <i class="bi bi-geo-alt-fill mt-1" style="color:#c9933a;font-size:1.1rem;flex-shrink:0;"></i>
              <div>
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;color:#c9933a;margin-bottom:2px;">Dirección</div>
                <div style="font-size:.88rem;">Av. Principal 1200, Of. 305 · Santiago</div>
              </div>
            </div>
            <div class="d-flex gap-3">
              <i class="bi bi-envelope-fill mt-1" style="color:#c9933a;font-size:1.1rem;flex-shrink:0;"></i>
              <div>
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;color:#c9933a;margin-bottom:2px;">Correo</div>
                <div style="font-size:.88rem;">contacto@elfaro.cl</div>
              </div>
            </div>
            <div class="d-flex gap-3">
              <i class="bi bi-telephone-fill mt-1" style="color:#c9933a;font-size:1.1rem;flex-shrink:0;"></i>
              <div>
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;color:#c9933a;margin-bottom:2px;">Teléfono</div>
                <div style="font-size:.88rem;">+56 2 2345 6789</div>
              </div>
            </div>
            <div class="d-flex gap-3">
              <i class="bi bi-clock-fill mt-1" style="color:#c9933a;font-size:1.1rem;flex-shrink:0;"></i>
              <div>
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;color:#c9933a;margin-bottom:2px;">Horario</div>
                <div style="font-size:.88rem;">Lun – Vie: 9:00 – 18:00 hrs</div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /row -->
  </div><!-- /container -->
</section>
