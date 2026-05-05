# El Faro v6 — PHP MVC

Periódico digital desarrollado con PHP 8, Bootstrap 5.3 y arquitectura MVC.

## Stack tecnológico

- **PHP 8.1+** — lógica de servidor y POO
- **Bootstrap 5.3** — Framework UI (CDN)
- **MySQL 8.0.45** — base de datos relacional con stored procedures
- **PDO** — acceso seguro con prepared statements
- **Arquitectura MVC** — Modelo · Vista · Controlador
- **Patrón Singleton** — conexión PDO única compartida por todos los modelos

---

## Estructura del proyecto

```
elfaro_php/
│
├── index.php                      ← Front Controller + Router
│
├── config/
│   ├── config.php                 ← Constantes globales (DB, rutas, ENV)
│   ├── Database.php               ← Singleton PDO — clase única de conexión
│   ├── schema.sql                 ← Esquema MySQL + datos de prueba
│   └── stored_procedures.sql      ← Procedimientos almacenados MySQL ★ NUEVO
│
├── models/                        ← CAPA MODELO
│   ├── Model.php                  ← Clase base abstracta (CRUD, sanitize)
│   ├── Usuario.php                ← Registro, autenticación, cambiarPassword()
│   ├── Articulo.php               ← publicar(), porSeccion() ←SP, actualizar()
│   ├── Suscripcion.php            ← Planes gratuito, básico y premium
│   ├── Contacto.php               ← validar(), guardar() ←SP
│   └── Comic.php                  ← Comics War and Peas (referencias externas)
│
├── controllers/                   ← CAPA CONTROLADOR
│   ├── Controller.php             ← render(), redirect(), post(), requireRole()
│   ├── HomeController.php         ← Portada — lee artículos desde BD
│   ├── AuthController.php         ← Login, registro, cambiarPassword, usuarios
│   ├── ArticuloController.php     ← index(), listar(), editar()
│   ├── ContactoController.php     ← Formulario de contacto
│   └── ComicController.php        ← Galería con filtro por categoría
│
├── views/                         ← CAPA VISTA (PHP + Bootstrap 5.3)
│   ├── layouts/
│   │   ├── header.php             ← Navbar con roles + favicon 🔮
│   │   └── footer.php             ← Footer 5 columnas + scripts
│   ├── home/index.php             ← Hero + grilla artículos + planes
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── cambiar_password.php   ← ★ NUEVO
│   │   └── usuarios.php           ← ★ NUEVO — RF-D lista usuarios (admin)
│   ├── contacto/index.php
│   ├── articulos/
│   │   ├── nuevo.php
│   │   ├── lista.php              ← ★ NUEVO — gestión de artículos
│   │   └── editar.php             ← ★ NUEVO — edición de artículos
│   └── comics/index.php
│
└── public/
    ├── css/styles.css
    └── js/main.js
```

---

## Instalación

```bash
# 1. Copiar proyecto al servidor web
cp -r elfaro_php/ /var/www/html/

# 2. Crear la base de datos y tablas
mysql -u root -p < config/schema.sql

# 3. Crear los stored procedures (NUEVO)
mysql -u root -p elfaro_db < config/stored_procedures.sql

# 4. Ajustar credenciales en config/config.php
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');

# 5. Abrir en el navegador
http://localhost/elfaro_php/
```

---

## Clase única de conexión — Database.php (Singleton)

`config/Database.php` implementa el patrón **Singleton**: una sola instancia PDO
compartida por todos los modelos del sistema.

```php
// Uso desde cualquier modelo:
$this->db = Database::getInstance()->getConnection();
```

Ventajas:
- Una sola conexión PDO activa durante toda la petición
- Parámetros de conexión centralizados en `config.php`
- Reutilizable por todas las clases que extienden `Model`

---

## Clase base Model — métodos compartidos

Todos los modelos extienden `Model.php`, que provee:

| Método | Tipo | Descripción |
|--------|------|-------------|
| `all()` | Consulta | `SELECT *` de la tabla |
| `find(id)` | Consulta | `SELECT` por clave primaria |
| `create(data)` | Inserción | `INSERT` dinámico con PDO |
| `sanitize(v)` | Seguridad | `htmlspecialchars + strip_tags` |

---

## Sentencias preparadas — PDO

Todas las consultas del proyecto usan `prepare()` con parámetros nombrados,
previniendo SQL Injection:

```php
$stmt = $this->db->prepare(
    "SELECT * FROM articulos WHERE seccion = :seccion LIMIT :limite"
);
$stmt->bindValue(':seccion', $seccion, PDO::PARAM_STR);
$stmt->bindValue(':limite',  $limite,  PDO::PARAM_INT);
$stmt->execute();
```

---

## Stored Procedures — MySQL

Archivo: `config/stored_procedures.sql`

| Procedimiento | Tipo | Método PHP que lo llama |
|---------------|------|-------------------------|
| `sp_insertar_contacto` | `INSERT` con transacción y parámetro `OUT` | `Contacto::guardar()` |
| `sp_articulos_por_seccion` | `SELECT` con `LEFT JOIN` y `ORDER BY` | `Articulo::porSeccion()` |

```sql
-- Verificar que los SP están creados:
SELECT ROUTINE_NAME, ROUTINE_TYPE, CREATED
FROM information_schema.ROUTINES
WHERE ROUTINE_SCHEMA = 'elfaro_db' AND ROUTINE_TYPE = 'PROCEDURE';
```

Ambos métodos PHP incluyen un **fallback automático** al SQL directo si los
stored procedures no han sido ejecutados aún.

---

## Rutas disponibles

| URL | Controlador | Rol mínimo | Descripción |
|-----|-------------|-----------|-------------|
| `index.php` | HomeController | — | Portada del periódico |
| `index.php?page=login` | AuthController | — | Inicio de sesión |
| `index.php?page=register` | AuthController | — | Registro de cuenta |
| `index.php?page=logout` | AuthController | lector | Cerrar sesión |
| `index.php?page=cambiar_password` | AuthController | lector | Cambiar contraseña |
| `index.php?page=contacto` | ContactoController | — | Formulario de contacto |
| `index.php?page=articulos` | ArticuloController | editor/admin | Publicar artículo |
| `index.php?page=articulos_lista` | ArticuloController | editor/admin | Gestionar artículos |
| `index.php?page=articulos_editar&id=N` | ArticuloController | editor/admin | Editar artículo |
| `index.php?page=usuarios` | AuthController | admin | Ver usuarios registrados |
| `index.php?page=comics` | ComicController | — | Galería War and Peas |
| `index.php?page=comics&cat=nombre` | ComicController | — | Comics por categoría |

---

## Entidades del sistema

| Clase | Tabla | Métodos destacados |
|-------|-------|-------------------|
| `Usuario` | `usuarios` | `registrar()`, `autenticar()`, `cambiarPassword()`, `listarActivos()` |
| `Articulo` | `articulos` | `publicar()`, `porSeccion()` ←SP, `listarTodos()`, `actualizar()` |
| `Suscripcion` | `suscripciones` | `suscribir()`, `obtenerActiva()`, `getPlanes()` |
| `Contacto` | `contactos` | `validar()`, `guardar()` ←SP, `getErrores()` |
| `Comic` | `comics` | `datosDemostracion()`, `listar()`, `getCategorias()` |

---

## Control de acceso por roles

| Rol | Puede hacer |
|-----|-------------|
| `lector` | Ver noticias, comics, contacto, cambiar su contraseña |
| `editor` | Todo lo anterior + publicar, listar y editar artículos |
| `admin` | Todo lo anterior + ver usuarios registrados |

---

## Requisitos funcionales implementados

| RF | Descripción | Implementación |
|----|-------------|----------------|
| RF-A | Registrar nuevo artículo | `ArticuloController` → `Articulo::publicar()` → `INSERT` |
| RF-B | Presentar artículos por página | `HomeController` → `Articulo::porSeccion()` ← `CALL sp_articulos_por_seccion` |
| RF-C | Registrar formulario de contacto | `ContactoController` → `Contacto::guardar()` ← `CALL sp_insertar_contacto` |
| RF-D | Presentar usuarios registrados | `AuthController::usuarios()` → `Usuario::listarActivos()` → `SELECT` |

---


## Sección Comics

Galería de [War and Peas](https://warandpeas.com/) con grid responsivo Bootstrap,
filtros por categoría, modal de imagen ampliada y fallback sin base de datos.

**Créditos:** © Elizabeth Pich & Jonathan Kunz — [warandpeas.com](https://warandpeas.com/)

---

## Credenciales de prueba

| Email | Contraseña | Tipo |
|-------|-----------|------|
| `admin@elfaro.cl` | _(ver BD)_ | admin |
| `redactor@elfaro.cl` | _(ver BD)_ | editor |
| `admin.blow@gmail.com` | _(ver BD)_ | lector |
