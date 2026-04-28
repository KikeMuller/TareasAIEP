<?php
/**
 * views/auth/register.php
 * Vista del formulario de registro de cuenta.
 *
 * Recibe del AuthController:
 *   $errores  - array de mensajes de error por campo
 *   $exito    - bool, true si el registro fue exitoso
 *   $formData - array con los datos del formulario (para repoblar)
 *   $planes   - array con los planes disponibles
 *
 * Envía datos por POST al mismo controlador (index.php?page=register).
 * Usa Bootstrap 5.3 + validación visual con is-invalid / is-valid.
 */

// Helper para mostrar la clase de error de Bootstrap en un campo
function fieldClass(array $errores, string $campo): string
{
    return isset($errores[$campo]) ? 'is-invalid' : '';
}
?>

<section class="ef-section" style="background:#f5f5f5;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7 col-md-9">

        <!-- Cabecera -->
        <div class="text-center mb-4">
          <h2 style="font-family:'Lora',Georgia,serif;font-weight:700;">
            Crear cuenta en <span style="color:#b8320a;">El Faro</span>
          </h2>
          <p class="text-muted">Accede a contenido exclusivo y personaliza tu experiencia lectora.</p>
        </div>

        <?php if ($exito): ?>
          <!-- Mensaje de éxito tras el registro -->
          <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill" style="font-size:18px;"></i>
            <div>
              <strong>¡Cuenta creada exitosamente!</strong>
              Bienvenido/a a El Faro.
              <a href="index.php" class="alert-link ms-2">Ir al inicio →</a>
            </div>
          </div>
        <?php endif; ?>

        <!-- Tarjeta del formulario -->
        <div class="card border-0 shadow-sm overflow-hidden">
          <div class="card-header py-3" style="background:#1a1008;">
            <h5 class="mb-0 text-white" style="font-family:'Lora',Georgia,serif;">
              <i class="bi bi-person-plus-fill me-2"></i>Formulario de Registro
            </h5>
          </div>
          <div class="card-body p-4">

            <!-- Formulario POST → AuthController::register() -->
            <form method="POST" action="index.php?page=register" novalidate>

              <!-- Campo: Nombre completo -->
              <div class="mb-3">
                <label for="nombre" class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Nombre completo *
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       class="form-control <?= fieldClass($errores, 'nombre') ?>"
                       value="<?= htmlspecialchars($formData['nombre'] ?? '') ?>"
                       placeholder="Tu nombre completo..."
                       maxlength="100"
                       required>
                <?php if (isset($errores['nombre'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errores['nombre']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Campo: Correo electrónico -->
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Correo electrónico *
                </label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control <?= fieldClass($errores, 'email') ?>"
                       value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                       placeholder="tu@correo.cl"
                       required>
                <?php if (isset($errores['email'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errores['email']) ?></div>
                <?php endif; ?>
              </div>

              <!-- Fila: contraseña + confirmación -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="password" class="form-label fw-semibold"
                         style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                    Contraseña *
                  </label>
                  <input type="password"
                         id="password"
                         name="password"
                         class="form-control <?= fieldClass($errores, 'password') ?>"
                         placeholder="Mínimo 8 caracteres"
                         minlength="8"
                         required>
                  <?php if (isset($errores['password'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errores['password']) ?></div>
                  <?php endif; ?>
                </div>
                <div class="col-md-6">
                  <label for="confirma_password" class="form-label fw-semibold"
                         style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                    Confirmar contraseña *
                  </label>
                  <input type="password"
                         id="confirma_password"
                         name="confirma_password"
                         class="form-control <?= fieldClass($errores, 'confirma') ?>"
                         placeholder="Repetir contraseña"
                         required>
                  <?php if (isset($errores['confirma'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errores['confirma']) ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Plan de suscripción -->
              <div class="mb-4">
                <label class="form-label fw-semibold d-block"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Plan de suscripción *
                </label>
                <div class="row g-3">
                  <?php foreach ($planes as $key => $plan): ?>
                    <div class="col-md-4">
                      <div class="form-check border rounded-3 p-3 h-100
                           <?= ($formData['plan'] ?? 'gratuito') === $key ? 'border-danger bg-light' : '' ?>">
                        <input class="form-check-input"
                               type="radio"
                               name="plan"
                               id="plan_<?= $key ?>"
                               value="<?= $key ?>"
                               <?= ($formData['plan'] ?? 'gratuito') === $key ? 'checked' : '' ?>>
                        <label class="form-check-label w-100" for="plan_<?= $key ?>">
                          <strong style="display:block;font-size:.95rem;"><?= htmlspecialchars($plan['nombre']) ?></strong>
                          <span style="font-size:1.1rem;font-weight:700;color:#b8320a;">
                            <?= $plan['precio'] > 0 ? '$' . number_format($plan['precio'],0,',','.') . '/mes' : 'Gratis' ?>
                          </span>
                          <small class="d-block text-muted mt-1" style="font-size:.78rem;">
                            <?= htmlspecialchars($plan['descripcion']) ?>
                          </small>
                        </label>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- Botón enviar -->
              <button type="submit" class="btn w-100 fw-bold py-2 text-white"
                      style="background:#b8320a;border-color:#b8320a;">
                <i class="bi bi-person-check-fill me-2"></i>Crear mi cuenta
              </button>

              <p class="text-center text-muted mt-3" style="font-size:.85rem;">
                ¿Ya tienes cuenta?
                <a href="index.php?page=login" style="color:#b8320a;">Inicia sesión aquí</a>
              </p>

            </form><!-- /form registro -->
          </div><!-- /card-body -->
        </div><!-- /card -->

      </div><!-- /col -->
    </div><!-- /row -->
  </div><!-- /container -->
</section>
