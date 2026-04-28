<?php
/**
 * views/auth/cambiar_password.php
 * Formulario para cambiar la contraseña del usuario autenticado.
 *
 * Recibe de AuthController::cambiarPassword():
 *   $errores - array de mensajes de error por campo
 *   $exito   - bool, true si se cambió correctamente
 */
?>

<section class="ef-section" style="background:#f5f5f5;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">

        <div class="text-center mb-4">
          <h2 style="font-family:'Lora',Georgia,serif;font-weight:700;">
            Cambiar contraseña
          </h2>
          <p class="text-muted" style="font-size:.9rem;">
            Hola, <strong><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></strong>.
            Ingresa tu contraseña actual y luego la nueva.
          </p>
        </div>

        <?php if ($exito): ?>
          <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill" style="font-size:20px;"></i>
            <div>
              <strong>¡Contraseña actualizada correctamente!</strong>
              <a href="index.php" class="alert-link ms-2">Ir al inicio →</a>
            </div>
          </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm overflow-hidden">
          <div class="card-header py-3" style="background:#1a1008;">
            <h5 class="mb-0 text-white" style="font-family:'Lora',Georgia,serif;">
              <i class="bi bi-key-fill me-2"></i>Nueva contraseña
            </h5>
          </div>
          <div class="card-body p-4">

            <form method="POST" action="index.php?page=cambiar_password" novalidate>

              <!-- Contraseña actual -->
              <div class="mb-3">
                <label for="password_actual"
                       class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Contraseña actual *
                </label>
                <input type="password"
                       id="password_actual"
                       name="password_actual"
                       class="form-control <?= isset($errores['actual']) ? 'is-invalid' : '' ?>"
                       placeholder="Tu contraseña actual"
                       required>
                <?php if (isset($errores['actual'])): ?>
                  <div class="invalid-feedback">
                    <?= htmlspecialchars($errores['actual']) ?>
                  </div>
                <?php endif; ?>
              </div>

              <hr style="border-color:#f0e0d0;margin:20px 0;">

              <!-- Nueva contraseña -->
              <div class="mb-3">
                <label for="password_nueva"
                       class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Nueva contraseña *
                </label>
                <input type="password"
                       id="password_nueva"
                       name="password_nueva"
                       class="form-control <?= isset($errores['nueva']) ? 'is-invalid' : '' ?>"
                       placeholder="Mínimo 8 caracteres"
                       minlength="8"
                       required>
                <?php if (isset($errores['nueva'])): ?>
                  <div class="invalid-feedback">
                    <?= htmlspecialchars($errores['nueva']) ?>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Confirmar nueva contraseña -->
              <div class="mb-4">
                <label for="password_confirma"
                       class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Confirmar nueva contraseña *
                </label>
                <input type="password"
                       id="password_confirma"
                       name="password_confirma"
                       class="form-control <?= isset($errores['confirma']) ? 'is-invalid' : '' ?>"
                       placeholder="Repetir nueva contraseña"
                       required>
                <?php if (isset($errores['confirma'])): ?>
                  <div class="invalid-feedback">
                    <?= htmlspecialchars($errores['confirma']) ?>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Botones -->
              <div class="d-flex gap-2">
                <button type="submit"
                        class="btn flex-grow-1 fw-bold py-2 text-white"
                        style="background:#b8320a;border-color:#b8320a;">
                  <i class="bi bi-floppy-fill me-2"></i>Guardar nueva contraseña
                </button>
                <a href="index.php"
                   class="btn btn-outline-secondary py-2"
                   style="text-decoration:none;">
                  Cancelar
                </a>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>
