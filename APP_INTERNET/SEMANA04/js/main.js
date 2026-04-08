/**
 * ============================================================
 *  EL FARO v5 — JAVASCRIPT EXTERNO
 *  Archivo: js/main.js
 *  Descripción: Toda la lógica interactiva del sitio.
 *               Nuevas características implementadas:
 *                 1. Reloj en vivo con segundos
 *                 2. Artículos dinámicos con formulario
 *                 3. Formulario de contacto
 *                 4. Contador de artículos
 *                 5. Menú activo según sección visible
 * ============================================================
 */


/* ============================================================
   1. RELOJ EN VIVO — NUEVA CARACTERÍSTICA 1
   Actualiza fecha y hora cada segundo con setInterval().
============================================================ */

/** Formatea números con cero a la izquierda. Ej: pad(8) → "08" */
function pad(n) {
  return n < 10 ? '0' + n : String(n);
}

/**
 * updateClock()
 * Lee la fecha y hora del sistema y actualiza el DOM.
 * Se ejecuta cada segundo via setInterval.
 */
function updateClock() {
  const now   = new Date();
  const dias  = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
  const meses = ['enero','febrero','marzo','abril','mayo','junio',
                 'julio','agosto','septiembre','octubre','noviembre','diciembre'];

  // Formato: "Martes, 24 de marzo de 2026"
  const fechaStr = dias[now.getDay()].charAt(0).toUpperCase()
                 + dias[now.getDay()].slice(1)
                 + ', ' + now.getDate()
                 + ' de ' + meses[now.getMonth()]
                 + ' de ' + now.getFullYear();

  // Formato: "14:35:07"
  const horaStr = pad(now.getHours()) + ':'
                + pad(now.getMinutes()) + ':'
                + pad(now.getSeconds());

  document.getElementById('live-date').textContent = fechaStr;
  document.getElementById('live-time').textContent = horaStr;
}

// Ejecutar al cargar y repetir cada 1 segundo
updateClock();
setInterval(updateClock, 1000);


/* ============================================================
   2. DATOS INICIALES — artículos de cada sección
============================================================ */

const articulosInicio = [
  {
    cat: 'Nacional', catClass: 'cat-general',
    titulo: 'Senado aprueba histórica reforma al sistema de agua potable rural',
    texto: 'La Cámara Alta ratificó por 35 votos la modificación a la Ley de Agua Potable Rural. Beneficiará a más de 400 localidades con fondos de $18.000 millones anuales.',
    fecha: '24 Mar 2026'
  },
  {
    cat: 'Ciencia y Tecnología', catClass: 'cat-general',
    titulo: 'Investigadores de la U. de Chile desarrollan vacuna nasal contra cepa gripal',
    texto: 'Vacuna intranasal con eficacia del 91% frente a la cepa H5N2. Publicada en The Lancet Infectious Diseases. Fase III en el segundo semestre.',
    fecha: '24 Mar 2026'
  },
  {
    cat: 'Regional', catClass: 'cat-general',
    titulo: 'Concepción inaugura el primer corredor verde interurbano del país',
    texto: 'Ciclovía-parque de 18 km evaluada en $9.200 millones, con paneles solares e iluminación LED. Reconocida como modelo nacional de movilidad sostenible.',
    fecha: '2 Mar 2026'
  }
];

const articulosDeporte = [
  {
    cat: 'Fútbol', catClass: 'cat-deporte',
    titulo: 'Colo-Colo golea 3-0 a River Plate y sella pase a semis de la Libertadores',
    texto: 'Con dos goles de Marcos Bolados y uno de Emiliano Amor. Dominaron el 68% del balón y generaron 14 remates a puerta.',
    fecha: '23 Mar 2026'
  },
  {
    cat: 'Tenis', catClass: 'cat-deporte',
    titulo: 'Tabilo avanza a cuartos del Masters 1000 de Miami tras vencer al N° 4 del mundo',
    texto: 'El chileno superó a Andrey Rublev (4° ATP) con parciales de 7-5 y 6-4. Saque dominante con 78% de efectividad.',
    fecha: '24 Mar 2026'
  },
  {
    cat: 'Selección', catClass: 'cat-deporte',
    titulo: 'La Roja Sub-20 clasifica al Mundial de Qatar 2027 con goleada sobre Ecuador',
    texto: 'Chile aseguró su boleto venciendo 3-0 con goles de Nicolás Pino y Darío Osorio Jr. Primer clasificado a dos cupos en 12 años.',
    fecha: '22 Mar 2026'
  }
];

const articulosNegocios = [
  {
    cat: 'Startups', catClass: 'cat-negocios',
    titulo: 'Greenbite recauda US$12M para escalar plataforma de alimentación sostenible',
    texto: 'La startup chilena de foodtech cerró Serie A con Kaszek Ventures. Conecta agricultores con restaurantes reduciendo el desperdicio en 40%.',
    fecha: '24 Mar 2026'
  },
  {
    cat: 'Inmobiliario', catClass: 'cat-negocios',
    titulo: 'Precios de arriendo en Santiago caen 8% por primera vez en 5 años',
    texto: 'Baja generalizada en Providencia, Las Condes y Santiago Centro según el INE. Analistas la atribuyen a nuevas unidades y tasas hipotecarias más competitivas.',
    fecha: '23 Mar 2026'
  },
  {
    cat: 'Energía', catClass: 'cat-negocios',
    titulo: 'SolarAndes firma PPA para abastecer el 30% de energía del norte con solar',
    texto: 'Contrato de 20 años con el Ministerio de Energía para 1.800 GWh anuales. Generará 2.400 empleos directos en Atacama, Coquimbo y Antofagasta.',
    fecha: '22 Mar 2026'
  }
];


/* ============================================================
   CREAR TARJETA DE ARTÍCULO
============================================================ */

/**
 * createCard(articulo, index, tipo)
 * Genera el HTML de una tarjeta (news-card o biz-card).
 */
function createCard(articulo, index, tipo) {
  const num   = String(index).padStart(2, '0');
  const fecha = articulo.fecha || new Date().toLocaleDateString('es-CL', {
    day: 'numeric', month: 'short', year: 'numeric'
  });

  if (tipo === 'negocios') {
    return `
      <article class="biz-card">
        <div class="biz-card-img" style="height:100px;background:linear-gradient(135deg,#1a0f05,#2d1f0e);display:flex;align-items:center;justify-content:center;">
          <span style="font-size:36px;opacity:0.4">📊</span>
        </div>
        <div class="biz-card-body">
          <span class="news-category ${articulo.catClass}">${articulo.cat}</span>
          <h3>${articulo.titulo}</h3>
          <p>${articulo.texto}</p>
        </div>
        <div class="biz-card-footer">
          <span>${fecha}</span>
          <span>→ Leer más</span>
        </div>
      </article>`;
  }

  return `
    <article class="news-card">
      <span class="news-category ${articulo.catClass}">${articulo.cat}</span>
      <div class="card-num">${num}</div>
      <div class="news-title-cell">${articulo.titulo}</div>
      <div class="news-text">${articulo.texto}</div>
      <div class="card-date">${fecha}</div>
    </article>`;
}

/**
 * renderArticles(gridId, articulos, tipo)
 * Inserta todas las tarjetas en el contenedor del DOM.
 */
function renderArticles(gridId, articulos, tipo) {
  const grid = document.getElementById(gridId);
  grid.innerHTML = articulos.map((a, i) => createCard(a, i + 1, tipo)).join('');
}

// Renderizar las tres secciones al cargar
renderArticles('grid-inicio',   articulosInicio,   'normal');
renderArticles('grid-deporte',  articulosDeporte,  'normal');
renderArticles('grid-negocios', articulosNegocios, 'negocios');


/* ============================================================
   FORMULARIO DE ARTÍCULOS — NUEVA CARACTERÍSTICA 2
============================================================ */

/**
 * toggleForm(bodyId, btn)
 * Abre o cierra el formulario de agregar artículo.
 */
function toggleForm(bodyId, btn) {
  const body   = document.getElementById(bodyId);
  const isOpen = body.classList.toggle('open');
  btn.textContent = isOpen ? '− Cerrar' : '+ Agregar';
}

/**
 * addArticle(gridId, catId, tituloId, textoId, counterId, catClass)
 * Lee el formulario, crea una tarjeta nueva y la inserta en el DOM.
 * También actualiza el contador de la sección.
 */
function addArticle(gridId, catId, tituloId, textoId, counterId, catClass) {
  const catInput    = document.getElementById(catId);
  const tituloInput = document.getElementById(tituloId);
  const textoInput  = document.getElementById(textoId);

  const cat    = catInput.value.trim()    || 'General';
  const titulo = tituloInput.value.trim();
  const texto  = textoInput.value.trim();

  // Validación de campos obligatorios
  if (!titulo || !texto) {
    alert('⚠ Por favor, complete el título y la descripción.');
    return;
  }

  const grid     = document.getElementById(gridId);
  const tipo     = gridId === 'grid-negocios' ? 'negocios' : 'normal';
  const newIndex = grid.children.length + 1;
  const fecha    = new Date().toLocaleDateString('es-CL', {
    day: 'numeric', month: 'short', year: 'numeric'
  });

  const articulo = { cat, catClass, titulo, texto, fecha };

  // Crear el nodo HTML de la nueva tarjeta
  const tempDiv = document.createElement('div');
  tempDiv.innerHTML = createCard(articulo, newIndex, tipo);
  const newCard = tempDiv.firstElementChild;

  // Animación de entrada (fade-in desde arriba)
  newCard.style.opacity   = '0';
  newCard.style.transform = 'translateY(-12px)';
  newCard.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
  grid.insertBefore(newCard, grid.firstChild);

  // Forzar reflow para activar la transición CSS
  newCard.getBoundingClientRect();
  newCard.style.opacity   = '1';
  newCard.style.transform = 'translateY(0)';

  // Actualizar el contador de artículos (nueva característica 4)
  const counterEl = document.getElementById(counterId);
  counterEl.textContent = parseInt(counterEl.textContent) + 1;

  // Limpiar campos
  catInput.value    = '';
  tituloInput.value = '';
  textoInput.value  = '';
  tituloInput.focus();

  // Scroll hacia la nueva tarjeta
  newCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}


/* ============================================================
   FORMULARIO DE CONTACTO — NUEVA CARACTERÍSTICA 3
============================================================ */

/**
 * submitContact(e)
 * Maneja el envío del formulario de contacto.
 * Valida, simula el envío y muestra confirmación.
 */
function submitContact(e) {
  e.preventDefault();

  const nombre  = document.getElementById('contact-name').value.trim();
  const mensaje = document.getElementById('contact-message').value.trim();

  if (!nombre || !mensaje) {
    alert('⚠ Por favor, complete el nombre y el mensaje.');
    return;
  }

  // Simular el envío deshabilitando el formulario
  const form = document.getElementById('contact-form');
  form.style.opacity        = '0.5';
  form.style.pointerEvents  = 'none';
  document.getElementById('contact-success').classList.add('visible');

  // Restaurar el formulario después de 5 segundos
  setTimeout(() => {
    form.reset();
    form.style.opacity       = '1';
    form.style.pointerEvents = 'auto';
    document.getElementById('contact-success').classList.remove('visible');
  }, 5000);
}


/* ============================================================
   MENÚ ACTIVO — navegación dinámica según scroll
============================================================ */

const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('nav ul li a');

/**
 * updateActiveLink()
 * Detecta la sección visible y resalta el link del menú.
 */
function updateActiveLink() {
  let current = '';
  sections.forEach(section => {
    if (window.scrollY >= section.offsetTop - 80) {
      current = section.getAttribute('id');
    }
  });
  navLinks.forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('href') === '#' + current) {
      link.classList.add('active');
    }
  });
}

window.addEventListener('scroll', updateActiveLink);
updateActiveLink();

// Navegación suave al hacer clic en links del menú
navLinks.forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    const target = document.querySelector(link.getAttribute('href'));
    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
});
