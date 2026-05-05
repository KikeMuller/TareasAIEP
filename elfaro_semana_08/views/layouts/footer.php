<?php // views/layouts/footer.php ?>

<!-- ═══════════════════════════════════════════════════════
     FOOTER AMPLIADO — 5 columnas Bootstrap Grid
     Colores de marca: #c9d2d0 fondo · #31231e texto
═══════════════════════════════════════════════════════ -->
<footer id="mainFooter" role="contentinfo">
  <div class="container">
    <div class="row g-5">

      <!-- Columna 1 — Identidad de marca -->
      <div class="col-lg-3 col-md-6">
        <div class="brand-name mb-2">El <span>Faro</span></div>
        <p>Fundado en 1979. Periódico digital independiente comprometido con la verdad y el rigor periodístico.</p>
        <p class="mt-3" style="font-size:.82rem;">
          <i class="bi bi-geo-alt-fill me-1" style="color:#c9933a;font-size:14px;"></i>Av. Principal 1200, Of. 305 — Santiago<br>
          <i class="bi bi-envelope-fill me-1 mt-1" style="color:#c9933a;font-size:14px;"></i>contacto@elfaro.cl<br>
          <i class="bi bi-telephone-fill me-1 mt-1" style="color:#c9933a;font-size:14px;"></i>+56 2 2345 6789
        </p>
      </div>

      <!-- Columna 2 — Secciones -->
      <div class="col-lg-2 col-md-6">
        <h5>Secciones</h5>
        <ul>
          <li><a href="index.php"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i>Inicio</a></li>
          <li><a href="index.php"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i>Deporte</a></li>
          <li><a href="index.php"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i>Negocios</a></li>
          <li><a href="index.php?page=contacto"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i>Contacto</a></li>
          <li><a href="index.php?page=register"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i>Suscribirse</a></li>
        </ul>
      </div>

      <!-- Columna 3 — Últimas noticias -->
      <div class="col-lg-3 col-md-6">
        <h5>Últimas noticias</h5>
        <ul style="display:flex;flex-direction:column;gap:10px;">
          <li style="border-bottom:1px solid rgba(49,35,30,.12);padding-bottom:8px;">
            <a href="index.php" style="font-size:.85rem;display:block;margin-bottom:2px;">Colo-Colo sella pase a semis de la Libertadores</a>
            <span style="font-size:.72rem;color:#7a6040;"><i class="bi bi-calendar3 me-1"></i>23 Mar 2026</span>
          </li>
          <li style="border-bottom:1px solid rgba(49,35,30,.12);padding-bottom:8px;">
            <a href="index.php" style="font-size:.85rem;display:block;margin-bottom:2px;">Tabilo avanza a cuartos del Masters de Miami</a>
            <span style="font-size:.72rem;color:#7a6040;"><i class="bi bi-calendar3 me-1"></i>24 Mar 2026</span>
          </li>
          <li>
            <a href="index.php" style="font-size:.85rem;display:block;margin-bottom:2px;">Greenbite levanta US$12M en ronda Serie A</a>
            <span style="font-size:.72rem;color:#7a6040;"><i class="bi bi-calendar3 me-1"></i>24 Mar 2026</span>
          </li>
        </ul>
      </div>

      <!-- Columna 4 — Redes sociales -->
      <div class="col-lg-2 col-md-6">
        <h5>Síguenos</h5>
        <div class="d-flex flex-column gap-2">
          <a href="#" class="footer-social-btn"><i class="bi bi-twitter-x me-2"></i>X / Twitter</a>
          <a href="#" class="footer-social-btn"><i class="bi bi-instagram me-2"></i>Instagram</a>
          <a href="#" class="footer-social-btn"><i class="bi bi-facebook me-2"></i>Facebook</a>
          <a href="#" class="footer-social-btn"><i class="bi bi-youtube me-2"></i>YouTube</a>
        </div>
      </div>

      <!-- Columna 5 — Newsletter -->
      <div class="col-lg-2 col-md-12">
        <h5>Newsletter</h5>
        <p style="font-size:.85rem;margin-bottom:12px;">Recibe noticias importantes en tu correo.</p>
        <form action="index.php?page=contacto" method="get">
          <div class="d-flex flex-column gap-2">
            <input type="email" name="newsletter_email" class="newsletter-input" placeholder="tu@correo.cl">
            <button type="submit" class="newsletter-btn">
              <i class="bi bi-send-fill me-1"></i>Suscribirse
            </button>
          </div>
        </form>
        <p style="font-size:.72rem;color:#7a6040;margin-top:8px;">Al suscribirte aceptas nuestra política de privacidad.</p>
      </div>

    </div><!-- /row -->
  </div><!-- /container -->

  <!-- Barra inferior -->
  <div class="footer-bottom-bar mt-4">
    <div class="container d-flex justify-content-between flex-wrap gap-2" style="font-size:.8rem;">
      <span>© <?= date('Y') ?> Diario El Faro · Todos los derechos reservados</span>
      <span style="color:#7a6040;">Desarrollado con PHP 8 · Bootstrap 5.3 · Arquitectura MVC</span>
    </div>
  </div>
</footer>

<!-- Bootstrap 5 JS Bundle (Popper incluido) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<!-- Script principal -->
<script src="public/js/main.js"></script>
</body>
</html>
