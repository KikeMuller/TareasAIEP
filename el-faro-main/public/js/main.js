/**
 * public/js/main.js
 * JavaScript del sitio El Faro v6 PHP.
 * Funciones: reloj en vivo, menú activo por scroll.
 */

// ── Reloj en vivo (actualiza cada 1 segundo) ──────────────
function pad(n) { return n < 10 ? '0' + n : String(n); }

function updateClock() {
    const now   = new Date();
    const dias  = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
    const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    const fecha = dias[now.getDay()].charAt(0).toUpperCase() + dias[now.getDay()].slice(1)
                + ', ' + now.getDate() + ' de ' + meses[now.getMonth()] + ' de ' + now.getFullYear();
    const hora  = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
    const dateEl = document.getElementById('live-date');
    const timeEl = document.getElementById('live-time');
    if (dateEl) dateEl.textContent = fecha;
    if (timeEl) timeEl.textContent = hora;
}
updateClock();
setInterval(updateClock, 1000);

// ── Smooth scroll para anclas del nav ─────────────────────
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const tgt = document.querySelector(a.getAttribute('href'));
        if (!tgt) return;
        e.preventDefault();
        tgt.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
