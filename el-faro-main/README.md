# SEMANA 06 / El Faro v6 — PHP MVC

Periódico digital desarrollado con PHP 8, Bootstrap 5.3 y arquitectura MVC.

## Stack tecnológico

- **PHP 8.1+** — lógica de servidor y POO
- **Bootstrap 5.3** — Framework UI (CDN)
- **MySQL 8** — base de datos relacional
- **PDO** — acceso seguro con prepared statements
- **Arquitectura MVC** — Modelo · Vista · Controlador

---

## Estructura del proyecto

```
elfaro_php/
│
├── index.php                    ← Front Controller + Router
│
├── config/
│   ├── config.php               ← Constantes globales (DB, rutas, ENV)
│   ├── Database.php             ← Singleton PDO
│   └── schema.sql               ← Esquema MySQL + datos de prueba
│
├── models/                      ← CAPA MODELO
│   ├── Model.php                ← Clase base abstracta (CRUD, sanitize)
│   ├── Usuario.php              ← Registro, autenticación, hash password
│   ├── Articulo.php             ← Publicación y listado por sección
│   ├── Suscripcion.php          ← Planes gratuito, básico y premium
│   ├── Contacto.php             ← Validación y guardado de mensajes
│   └── Comic.php                ← Comics de War and Peas (referencias externas)
│
├── controllers/                 ← CAPA CONTROLADOR
│   ├── Controller.php           ← Clase base: render(), redirect(), post()
│   ├── HomeController.php       ← Portada con las 3 secciones de noticias
│   ├── AuthController.php       ← Login, registro y logout
│   ├── ArticuloController.php   ← Publicar artículo (requiere sesión)
│   ├── ContactoController.php   ← Formulario de contacto
│   └── ComicController.php      ← Galería de comics con filtro por categoría
│
├── views/                       ← CAPA VISTA (PHP + Bootstrap 5.3)
│   ├── layouts/
│   │   ├── header.php           ← Alert, masthead, ticker, navbar
│   │   └── footer.php           ← Footer 5 columnas + scripts
│   ├── home/
│   │   └── index.php            ← Hero + grilla artículos + planes suscripción
│   ├── auth/
│   │   ├── login.php            ← Formulario de inicio de sesión
│   │   └── register.php         ← Formulario de registro con planes
│   ├── contacto/
│   │   └── index.php            ← Formulario de contacto con validación PHP
│   ├── articulos/
│   │   └── nuevo.php            ← Formulario publicar artículo (editores)
│   └── comics/
│       └── index.php            ← Galería War and Peas con modal Bootstrap
│
└── public/
    ├── css/
    │   └── styles.css           ← Estilos de marca (complementan Bootstrap)
    └── js/
        └── main.js              ← Reloj en vivo, smooth scroll
```

---

## Instalación

```bash
# 1. Copiar proyecto al servidor web
cp -r elfaro_php/ /var/www/html/

# 2. Crear la base de datos y tablas
mysql -u root -p < config/schema.sql

# 3. Ajustar credenciales en config/config.php
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');

# 4. Abrir en el navegador
http://localhost/elfaro_php/
```

---

## Rutas disponibles

| URL                        | Controlador           | Descripción                        |
|----------------------------|-----------------------|------------------------------------|
| `index.php`                | HomeController        | Portada del periódico              |
| `index.php?page=login`     | AuthController        | Inicio de sesión                   |
| `index.php?page=register`  | AuthController        | Registro de cuenta                 |
| `index.php?page=logout`    | AuthController        | Cerrar sesión                      |
| `index.php?page=contacto`  | ContactoController    | Formulario de contacto             |
| `index.php?page=articulos` | ArticuloController    | Publicar artículo (sesión activa)  |
| `index.php?page=comics`    | ComicController       | Galería de comics War and Peas     |
| `index.php?page=comics&cat=humor negro` | ComicController | Comics filtrados por categoría |

---

## Entidades del sistema

| Clase          | Tabla            | Descripción                               |
|----------------|------------------|-------------------------------------------|
| `Usuario`      | `usuarios`       | Lectores, editores, admins                |
| `Articulo`     | `articulos`      | Noticias publicadas por sección           |
| `Suscripcion`  | `suscripciones`  | Planes gratuito, básico y premium         |
| `Contacto`     | `contactos`      | Mensajes del formulario de contacto       |
| `Comic`        | `comics`         | Referencias a comics de War and Peas      |

---

## Sección Comics

La sección Comics muestra una galería de historietas de
[War and Peas](https://warandpeas.com/) como contenido de entretenimiento editorial.

**Características:**
- Grid responsivo Bootstrap (3 cols desktop / 2 tablet / 1 móvil)
- Filtro por categoría mediante pills (`?cat=nombre`)
- Modal Bootstrap con imagen ampliada al hacer clic
- Enlace directo al post original en warandpeas.com
- Funciona sin base de datos (datos de demostración en el modelo)

**Créditos:** © Elizabeth Pich & Jonathan Kunz — [warandpeas.com](https://warandpeas.com/)
