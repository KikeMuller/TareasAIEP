# El Faro v5 — Periódico Digital
## Proyecto Académico — HTML5 + CSS + JavaScript

---

## Estructura de carpetas

```
SEMANA04/
│
├── index.html          ← Archivo principal (todo integrado)
│
├── css/
│   └── styles.css      ← Hoja de estilos externa (respaldo)
│
├── js/
│   └── main.js         ← JavaScript externo (respaldo)
│
└── README.md           ← Este archivo
```

> **Nota:** El archivo `index.html` es la versión **autocontenida** del sitio
> (CSS y JS integrados en línea). Los archivos en `/css` y `/js` son la versión
> separada, útil para proyectos más grandes.

---

## Nuevas características implementadas (v5)

### 1. Fecha y hora en vivo (`main.js: updateClock()`)
- Muestra la fecha actual en formato "Martes, 24 de marzo de 2026"
- Muestra la hora con segundos en tiempo real: "14:35:07"
- Se actualiza cada segundo mediante `setInterval(updateClock, 1000)`
- Ubicada en la barra superior del header

### 2. Artículos dinámicos con formulario
- Cada sección (Inicio, Deporte, Negocios) tiene un formulario
- Campos: Categoría, Título, Descripción
- Los artículos se cargan desde arrays de datos iniciales
- Al enviar, el artículo aparece en la grilla con animación suave
- El formulario se puede abrir/cerrar con el botón "+ Agregar"

### 3. Formulario de contacto
- Nueva sección `#contacto` con formulario completo
- Campos: Nombre, Email (opcional), Mensaje
- Panel lateral con información de contacto
- Mensaje de confirmación tras el envío
- El formulario se restaura automáticamente después de 5 segundos

### 4. Contador de artículos
- Cada sección muestra el número de artículos actuales
- El contador se actualiza al agregar un nuevo artículo
- Usa `aria-live="polite"` para accesibilidad

---

## Tecnologías utilizadas

| Tecnología    | Uso                                          |
|---------------|----------------------------------------------|
| HTML5         | Estructura semántica del sitio               |
| CSS3          | Estilos, CSS Grid, media queries, variables  |
| JavaScript    | DOM, setInterval, eventos, manipulación      |
| Google Fonts  | Tipografías: Lora + Inter                    |
| SVG inline    | Logo del faro creado sin imágenes externas   |

---

## Compatibilidad

Probado en: Chrome 120+, Firefox 120+, Safari 17+, Edge 120+

---

## Créditos

Periódico "El Faro" — Proyecto académico de Desarrollo Web 2026
