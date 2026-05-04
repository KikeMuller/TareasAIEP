<?php
/**
 * views/auth/login.php
 * Vista del formulario de inicio de sesión.
 *
 * Recibe del AuthController:
 *   $error - string con el mensaje de error (vacío si no hay error)
 *
 * Envía datos por POST a index.php?page=login
 */
?>

<section class="ef-section" style="background:#f5f5f5;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">

        <div class="text-center mb-4">
          <h2 style="font-family:'Lora',Georgia,serif;font-weight:700;">
            Iniciar sesión en <span style="color:#b8320a;">El Faro</span>
          </h2>
          <p class="text-muted">Accede a tu cuenta para leer sin restricciones.</p>
        </div>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill" style="font-size:16px;"></i>
            <div><?= htmlspecialchars($error) ?></div>
          </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm overflow-hidden">
          <div class="card-header py-3" style="background:#1a1008;">
            <h5 class="mb-0 text-white" style="font-family:'Lora',Georgia,serif;">
              <i class="bi bi-box-arrow-in-right me-2"></i>Acceder a mi cuenta
            </h5>
          </div>
          <div class="card-body p-4">

            <!-- Formulario POST → AuthController::login() -->
            <form method="POST" action="index.php?page=login">

              <div class="mb-3">
                <label for="email" class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Correo electrónico
                </label>
                <input type="email" id="email" name="email"
                       class="form-control" placeholder="tu@correo.cl" required>
              </div>

              <div class="mb-4">
                <label for="password" class="form-label fw-semibold"
                       style="font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;">
                  Contraseña
                </label>
                <input type="password" id="password" name="password"
                       class="form-control" placeholder="Tu contraseña" required>
              </div>

              <button type="submit" class="btn w-100 fw-bold py-2 text-white"
                      style="background:#b8320a;border-color:#b8320a;">
                <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
              </button>

              <p class="text-center text-muted mt-3" style="font-size:.85rem;">
                ¿No tienes cuenta?
                <a href="index.php?page=register" style="color:#b8320a;">Regístrate gratis</a>
              </p>

            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
