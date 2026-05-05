<?php
/**
 * views/auth/usuarios.php
 * RF-D: Presentar los usuarios que se han registrado en la plataforma.
 *
 * Recibe de AuthController::usuarios():
 *   $usuarios - array con todos los usuarios activos de la BD
 *
 * Solo accesible para rol: admin
 */

// Colores por tipo de usuario
$colores = [
    'admin'  => '#B8320A',
    'editor' => '#1A4D7C',
    'lector' => '#1B6B3A',
];
?>

<section class="ef-section" style="background:#f5f5f5;">
  <div class="container">

    <!-- Encabezado -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
      <div>
        <p class="section-eyebrow">Panel de administración</p>
        <h2 class="section-heading mb-0">Usuarios registrados</h2>
      </div>
      <!-- Contador total -->
      <div class="article-counter" aria-live="polite">
        <span class="ac-num" style="color:#b8320a;"><?= count($usuarios) ?></span>
        <span class="ac-lbl">usuario<?= count($usuarios) !== 1 ? 's' : '' ?> registrado<?= count($usuarios) !== 1 ? 's' : '' ?></span>
      </div>
    </div>

    <?php if (empty($usuarios)): ?>
      <!-- Estado vacío -->
      <div class="text-center py-5">
        <div style="font-size:3rem;margin-bottom:12px;">👤</div>
        <p class="text-muted">No hay usuarios registrados todavía.</p>
      </div>

    <?php else: ?>

      <!-- Tarjetas resumen por tipo -->
      <?php
      $totales = ['admin' => 0, 'editor' => 0, 'lector' => 0];
      foreach ($usuarios as $u) {
          $tipo = $u['tipo'] ?? 'lector';
          if (isset($totales[$tipo])) $totales[$tipo]++;
      }
      ?>
      <div class="row g-3 mb-4">
        <?php foreach ($totales as $tipo => $total): ?>
          <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3" style="border-top: 4px solid <?= $colores[$tipo] ?? '#888' ?> !important;">
              <div style="font-size:1.8rem;font-weight:700;color:<?= $colores[$tipo] ?? '#888' ?>;font-family:'Lora',Georgia,serif;">
                <?= $total ?>
              </div>
              <div style="font-size:.8rem;text-transform:uppercase;letter-spacing:.12em;color:#6b7280;font-weight:600;">
                <?= ucfirst($tipo) . ($total !== 1 ? 'es' : '') ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Tabla principal -->
      <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header py-3 d-flex align-items-center gap-2"
             style="background:#1a1008;">
          <i class="bi bi-people-fill text-white"></i>
          <span class="text-white fw-semibold" style="font-size:.85rem;letter-spacing:.08em;">
            Listado completo de usuarios — BD: elfaro_db · tabla: usuarios
          </span>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size:.88rem;">
            <thead style="background:#f8f3e8;">
              <tr>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;width:50px;">#</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">Nombre</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">Correo electrónico</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">Rol</th>
                <th style="padding:12px 16px;font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#6b7280;">Fecha de registro</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($usuarios as $i => $usuario): ?>
                <?php
                $tipo  = $usuario['tipo'] ?? 'lector';
                $color = $colores[$tipo] ?? '#888888';
                $fecha = isset($usuario['fecha_registro'])
                         ? date('d/m/Y  H:i', strtotime($usuario['fecha_registro']))
                         : '—';
                ?>
                <tr>
                  <!-- ID -->
                  <td style="padding:12px 16px;color:#9a8060;font-weight:600;">
                    <?= (int) $usuario['id'] ?>
                  </td>

                  <!-- Nombre -->
                  <td style="padding:12px 16px;">
                    <div class="d-flex align-items-center gap-2">
                      <!-- Avatar inicial -->
                      <div style="width:34px;height:34px;border-radius:50%;
                                  background:<?= $color ?>;color:#fff;
                                  display:flex;align-items:center;justify-content:center;
                                  font-weight:700;font-size:.95rem;flex-shrink:0;">
                        <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                      </div>
                      <span style="font-weight:600;color:#1a1008;">
                        <?= htmlspecialchars($usuario['nombre']) ?>
                      </span>
                    </div>
                  </td>

                  <!-- Email -->
                  <td style="padding:12px 16px;color:#6b7280;">
                    <i class="bi bi-envelope me-1" style="font-size:.8rem;"></i>
                    <?= htmlspecialchars($usuario['email']) ?>
                  </td>

                  <!-- Rol badge -->
                  <td style="padding:12px 16px;">
                    <span class="badge"
                          style="background:<?= $color ?>;color:#fff;
                                 font-size:.75rem;padding:4px 10px;
                                 letter-spacing:.06em;">
                      <?= htmlspecialchars(ucfirst($tipo)) ?>
                    </span>
                  </td>

                  <!-- Fecha de registro -->
                  <td style="padding:12px 16px;color:#9a8060;white-space:nowrap;">
                    <i class="bi bi-calendar3 me-1" style="font-size:.8rem;"></i>
                    <?= $fecha ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div><!-- /table-responsive -->

        <!-- Pie de tabla -->
        <div class="card-footer py-2 px-3" style="background:#f8f3e8;font-size:.78rem;color:#9a8060;">
          <i class="bi bi-info-circle me-1"></i>
          Mostrando <?= count($usuarios) ?> usuario<?= count($usuarios) !== 1 ? 's' : '' ?> activo<?= count($usuarios) !== 1 ? 's' : '' ?>.
          Usuarios desactivados (activo = 0) no aparecen en esta vista.
          Consulta: <code>SELECT id, nombre, email, tipo, fecha_registro FROM usuarios WHERE activo = 1 ORDER BY fecha_registro DESC</code>
        </div>

      </div><!-- /card -->
    <?php endif; ?>

  </div><!-- /container -->
</section>
