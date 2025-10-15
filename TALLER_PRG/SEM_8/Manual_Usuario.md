# MANUAL DE USUARIO
## Sistema de Gestión de Clientes - Logística Global

---

## ÍNDICE

1. [Introducción](#1-introducción)
2. [Requisitos del Sistema](#2-requisitos-del-sistema)
3. [Inicio de la Aplicación](#3-inicio-de-la-aplicación)
4. [Interfaz Principal](#4-interfaz-principal)
5. [Gestión de Clientes](#5-gestión-de-clientes)
6. [Gestión de Direcciones](#6-gestión-de-direcciones)
7. [Solución de Problemas](#7-solución-de-problemas)

---

## 1. INTRODUCCIÓN

### ¿Qué es este sistema?

El **Sistema de Gestión de Clientes** es una aplicación de escritorio diseñada para empresas de logística que necesitan administrar eficientemente la información de sus clientes y sus direcciones de entrega.

### Funcionalidades Principales

✅ **Registro de Clientes**: Almacene información completa de cada cliente  
✅ **Gestión de Direcciones**: Múltiples direcciones por cliente  
✅ **Búsqueda Rápida**: Encuentre clientes por nombre o email  
✅ **Validaciones Automáticas**: El sistema verifica los datos ingresados  
✅ **Interfaz Intuitiva**: Diseño amigable y fácil de usar

---

## 2. REQUISITOS DEL SISTEMA

### Requisitos Mínimos

- **Sistema Operativo**: Windows 10 o superior
- **Memoria RAM**: 4 GB mínimo
- **Espacio en Disco**: 500 MB libres
- **Resolución de Pantalla**: 1024x768 o superior
- **Software Adicional**: .NET Framework 4.7.2 (se instala automáticamente)

### Requisitos de Red

- Conexión a servidor SQL Server (local o remoto)
- Permisos de lectura/escritura en la base de datos

---

## 3. INICIO DE LA APLICACIÓN

### 3.1 Abrir la Aplicación

1. Localice el ícono **"Sistema Logística"** en su escritorio
2. Haga doble clic para iniciar
3. Espere unos segundos mientras la aplicación se carga

### 3.2 Verificación de Conexión

Al iniciar, el sistema verifica automáticamente la conexión con la base de datos.

**Si la conexión es exitosa:**
```
✅ La ventana principal se abrirá sin problemas
```

**Si hay problemas de conexión:**
```
⚠️ Aparecerá un mensaje de advertencia
   "No se pudo conectar a la base de datos"
   
   Soluciones:
   1. Verifique que SQL Server esté ejecutándose
   2. Contacte al administrador del sistema
   3. Revise la configuración de red
```

---

## 4. INTERFAZ PRINCIPAL

### 4.1 Descripción de la Pantalla

```
<img width="1134" height="955" alt="image" src="https://github.com/user-attachments/assets/b48f8067-3fcb-4228-8ef6-35847ca2d8dd" />

```

### 4.2 Componentes de la Interfaz

#### Barra de Menú
- **Clientes**: Acceso a gestión de clientes
- **Direcciones**: Acceso a gestión de direcciones
- **Herramientas**: Utilidades del sistema
- **Salir**: Cerrar la aplicación

#### Botones Principales
- **Gestionar Clientes** (Azul): Abrir módulo de clientes
- **Gestionar Direcciones** (Verde): Abrir módulo de direcciones

#### Barra de Estado
- **Listo**: Estado actual del sistema
- **Fecha/Hora**: Información temporal

### 4.3 Atajos de Teclado

| Atajo | Función |
|-------|---------|
| Ctrl + C | Abrir Gestión de Clientes |
| Ctrl + D | Abrir Gestión de Direcciones |
| Alt + F4 | Salir de la aplicación |
| F1 | Ayuda (si está disponible) |

---

## 5. GESTIÓN DE CLIENTES

### 5.1 Acceder al Módulo

**Opción 1**: Clic en botón **"Gestionar Clientes"**  
**Opción 2**: Menú **Clientes → Gestionar Clientes**  
**Opción 3**: Atajo **Ctrl + C**

### 5.2 Pantalla de Gestión de Clientes

```
╔═══════════════════════════════════════════════════════════════╗
║ Gestión de Clientes                                     [X]   ║
╠═══════════════════════════════════════════════════════════════╣
║ Buscar: [________________] [🔍 Buscar] [Mostrar Todos]       ║
╠═══════════════════════════════════════════════════════════════╣
║ LISTA DE CLIENTES                      ║ DATOS DEL CLIENTE   ║
║ ═══════════════════════════════════════╬═════════════════════║
║ ID │ Nombre      │ Email      │ Tel   ║                     ║
║ ───┼─────────────┼────────────┼────── ║ Nombre:             ║
║ 1  │ Juan Pérez  │ juan@...   │ +56.. ║ [_______________]   ║
║ 2  │ María G.    │ maria@...  │ +56.. ║                     ║
║ 3  │ Carlos R.   │ carlos@... │ +56.. ║ Teléfono:           ║
║ 4  │ Ana M.      │ ana@...    │ +56.. ║ [_______________]   ║
║                                        ║                     ║
║                                        ║ Email:              ║
║                                        ║ [_______________]   ║
║                                        ║                     ║
║                                        ║ [➕ Nuevo]          ║
║                                        ║ [💾 Guardar]        ║
║                                        ║ [✏️ Modificar]      ║
║                                        ║ [🗑️ Eliminar]       ║
║                                        ║ [❌ Cancelar]       ║
╚════════════════════════════════════════╩═════════════════════╝
```

### 5.3 Agregar Nuevo Cliente

#### Paso 1: Hacer clic en "➕ Nuevo"
- Los campos de texto se habilitan
- El formulario se limpia
- El cursor se posiciona en "Nombre"

#### Paso 2: Completar la Información

**Campos Obligatorios** (marcados con *):
- **Nombre***: Nombre completo del cliente
  - Mínimo 3 caracteres
  - Máximo 100 caracteres
  - Ejemplo: "Juan Pablo Pérez González"

- **Email***: Correo electrónico
  - Debe tener formato válido (usuario@dominio.com)
  - Debe ser único (no puede repetirse)
  - Ejemplo: "juan.perez@email.com"

**Campos Opcionales**:
- **Teléfono**: Número de contacto
  - Puede incluir código de país
  - Máximo 20 caracteres
  - Ejemplo: "+56912345678" o "912345678"

#### Paso 3: Guardar

1. Hacer clic en **"💾 Guardar"**
2. El sistema valida los datos
3. Si todo está correcto:
   ```
   ✅ "Cliente creado exitosamente con ID: 15"
   ```
4. Si hay errores:
   ```
   ⚠️ Se muestra mensaje específico del problema
   ```

### 5.4 Buscar Cliente

#### Método de Búsqueda

1. Escribir criterio en campo **"Buscar"**
2. Hacer clic en **"🔍 Buscar"**
3. La tabla se filtra mostrando solo coincidencias

**Búsqueda por**:
- Nombre (total o parcial)
- Email (total o parcial)

**Ejemplos**:
- Buscar: "juan" → Muestra todos los clientes con "juan" en su nombre
- Buscar: "@gmail.com" → Muestra clientes con email de Gmail
- Dejar vacío y clic en "Mostrar Todos" → Muestra todos los clientes

### 5.5 Modificar Cliente

#### Paso 1: Seleccionar Cliente
- Hacer clic en una fila de la tabla
- Los datos se cargan automáticamente en el formulario

#### Paso 2: Activar Edición
- Hacer clic en **"✏️ Modificar"**
- Los campos se habilitan para edición

#### Paso 3: Realizar Cambios
- Modificar los campos necesarios
- Los mismos campos obligatorios aplican

#### Paso 4: Guardar Cambios
1. Hacer clic en **"💾 Guardar"**
2. Confirmar los cambios
3. Mensaje de éxito:
   ```
   ✅ "Cliente actualizado exitosamente"
   ```

### 5.6 Eliminar Cliente

#### ⚠️ ADVERTENCIA IMPORTANTE
Al eliminar un cliente, **TODAS SUS DIRECCIONES** también se eliminan automáticamente.

#### Pasos para Eliminar

1. Seleccionar cliente en la tabla
2. Hacer clic en **"🗑️ Eliminar"**
3. Aparece mensaje de confirmación:
   ```
   ⚠️ ¿Está seguro que desea eliminar este cliente?
      Esta acción también eliminará todas sus direcciones.
      
      [Sí]  [No]
   ```
4. Si confirma con **[Sí]**:
   ```
   ✅ "Cliente eliminado exitosamente"
   ```
5. Si selecciona **[No]**:
   - La operación se cancela
   - No se elimina nada

### 5.7 Mensajes de Validación

| Situación | Mensaje |
|-----------|---------|
| Nombre vacío | ⚠️ "El nombre del cliente es obligatorio." |
| Nombre muy corto | ⚠️ "El nombre debe tener al menos 3 caracteres." |
| Email vacío | ⚠️ "El email es obligatorio." |
| Email inválido | ⚠️ "El formato del email no es válido." |
| Email duplicado | ⚠️ "Ya existe un cliente registrado con ese email." |

---

## 6. GESTIÓN DE DIRECCIONES

### 6.1 Acceder al Módulo

**Opción 1**: Clic en botón **"Gestionar Direcciones"**  
**Opción 2**: Menú **Direcciones → Gestionar Direcciones**  
**Opción 3**: Atajo **Ctrl + D**

### 6.2 Pantalla de Gestión de Direcciones

```
╔══════════════════════════════════════════════════════════════════╗
║ Gestión de Direcciones                                    [X]   ║
╠══════════════════════════════════════════════════════════════════╣
║ Filtrar por Cliente: [-- Todos --  ▼] [Mostrar Todas]          ║
╠══════════════════════════════════════════════════════════════════╣
║ LISTA DE DIRECCIONES                   ║ DATOS DE DIRECCIÓN    ║
║ ═══════════════════════════════════════╬═══════════════════════║
║ ID │Cliente    │Calle│Ciudad│Principal ║                       ║
║ ───┼───────────┼─────┼──────┼───────── ║ Cliente:              ║
║ 1  │Juan Pérez │Av...│Stgo  │   ✓     ║ [Juan Pérez      ▼]  ║
║ 2  │Juan Pérez │C...│Stgo  │         ║                       ║
║ 3  │María G.   │Pas..│Stgo  │   ✓     ║ Calle:                ║
║                                        ║ [________________]    ║
║                                        ║                       ║
║                                        ║ Ciudad:               ║
║                                        ║ [________________]    ║
║                                        ║                       ║
║                                        ║ País:                 ║
║                                        ║ [________________]    ║
║                                        ║                       ║
║                                        ║ Código Postal:        ║
║                                        ║ [________]            ║
║                                        ║                       ║
║                                        ║ [✓] Dirección Principal║
║                                        ║                       ║
║                                        ║ [➕ Nuevo]            ║
║                                        ║ [💾 Guardar]          ║
║                                        ║ [✏️ Modificar]        ║
║                                        ║ [🗑️ Eliminar]         ║
║                                        ║ [❌ Cancelar]         ║
╚════════════════════════════════════════╩═══════════════════════╝
```

### 6.3 Agregar Nueva Dirección

#### Paso 1: Hacer clic en "➕ Nuevo"

#### Paso 2: Seleccionar Cliente
- Abrir lista desplegable **"Cliente"**
- Seleccionar el cliente para el cual agregar la dirección
- **Importante**: No puede crear dirección sin cliente

#### Paso 3: Completar Información

**Campos Obligatorios** (marcados con *):

- **Cliente***: Seleccionar de la lista
  - Formato: "ID - Nombre del Cliente"
  - Ejemplo: "5 - Juan Pérez"

- **Calle***: Dirección completa
  - Mínimo 5 caracteres
  - Máximo 200 caracteres
  - Ejemplo: "Av. Libertador Bernardo O'Higgins 1234, Depto 505"

- **Ciudad***: Ciudad de la dirección
  - Mínimo 2 caracteres
  - Máximo 100 caracteres
  - Ejemplo: "Santiago"

- **País***: País de la dirección
  - Mínimo 2 caracteres
  - Máximo 100 caracteres
  - Ejemplo: "Chile"

**Campos Opcionales**:

- **Código Postal**: Código postal o ZIP
  - Máximo 20 caracteres
  - Ejemplo: "8320000" o "75001"

- **Dirección Principal**: Checkbox
  - Marcar si es la dirección principal del cliente
  - Solo puede haber UNA dirección principal por cliente

#### Paso 4: Guardar

1. Hacer clic en **"💾 Guardar"**
2. Validación automática
3. Mensaje de confirmación:
   ```
   ✅ "Dirección creada exitosamente con ID: 23"
   ```

### 6.4 Filtrar Direcciones por Cliente

#### Propósito
Ver solo las direcciones de un cliente específico

#### Pasos

1. Hacer clic en **"Filtrar por Cliente"**
2. Seleccionar cliente de la lista
3. La tabla muestra solo direcciones de ese cliente
4. Para ver todas nuevamente: Seleccionar "-- Todos --" o clic en "Mostrar Todas"

**Ejemplo de Uso**:
```
Seleccionar: "1 - Juan Pérez"
Resultado: Muestra solo las 2 direcciones de Juan Pérez
```

### 6.5 Dirección Principal

#### ¿Qué es una Dirección Principal?

La dirección principal es la **dirección predeterminada** para entregas de ese cliente.

#### Características

- ✅ Cada cliente puede tener **solo UNA** dirección principal
- ✅ Se marca con checkmark [✓] en la tabla
- ✅ Al marcar una nueva como principal, la anterior se desmarca automáticamente
- ⚠️ Un cliente puede tener direcciones sin ninguna marcada como principal

#### Cómo Establecer

**Método 1**: Al crear o editar
- Marcar checkbox **"Dirección Principal"** antes de guardar

**Método 2**: Desde la lista
- Seleccionar dirección
- Modificar
- Marcar checkbox
- Guardar

### 6.6 Modificar Dirección

Similar al proceso de clientes:

1. Seleccionar dirección en la tabla
2. Clic en **"✏️ Modificar"**
3. Realizar cambios
4. **Nota**: No se puede cambiar el cliente de una dirección existente
5. Guardar cambios

### 6.7 Eliminar Dirección

1. Seleccionar dirección
2. Clic en **"🗑️ Eliminar"**
3. Confirmar:
   ```
   ⚠️ ¿Está seguro que desea eliminar esta dirección?
      
      [Sí]  [No]
   ```
4. Si confirma:
   ```
   ✅ "Dirección eliminada exitosamente"
   ```

### 6.8 Mensajes de Validación

| Situación | Mensaje |
|-----------|---------|
| Sin cliente | ⚠️ "Debe seleccionar un cliente válido." |
| Calle vacía | ⚠️ "La calle es obligatoria." |
| Calle muy corta | ⚠️ "La calle debe tener al menos 5 caracteres." |
| Ciudad vacía | ⚠️ "La ciudad es obligatoria." |
| País vacío | ⚠️ "El país es obligatorio." |

---

## 7. SOLUCIÓN DE PROBLEMAS

### Problema 1: "No se puede conectar al servidor"

**Síntomas**:
- Error al abrir la aplicación
- No carga lista de clientes
- Mensaje: "No se puede conectar al servidor"

**Soluciones**:

1. **Verificar SQL Server**
   - Abrir "Servicios" de Windows
   - Buscar "SQL Server (MSSQLSERVER)"
   - Debe estar "Ejecutándose"
   - Si no: Clic derecho → Iniciar

2. **Verificar Nombre del Servidor**
   - Contactar al administrador
   - Verificar si es `localhost`, `.\SQLEXPRESS`, o IP del servidor

3. **Verificar Firewall**
   - El puerto 1433 debe estar abierto
   - Permitir conexiones a SQL Server

4. **Reiniciar Aplicación**
   - Cerrar completamente
   - Volver a abrir

### Problema 2: "Ya existe un cliente con ese email"

**Síntomas**:
- Error al guardar cliente nuevo
- Mensaje: "Ya existe un cliente registrado con ese email"

**Solución**:
1. Verificar que el email sea correcto
2. Buscar si el cliente ya existe
3. Si es el mismo cliente, modificarlo en lugar de crear uno nuevo
4. Si es cliente diferente, usar email diferente

### Problema 3: "Error al eliminar cliente"

**Síntomas**:
- Error al intentar eliminar

**Causas Posibles**:
- El cliente tiene registros relacionados en otras tablas

**Solución**:
1. Primero elimine las direcciones del cliente
2. Luego elimine el cliente
3. O use la función "Eliminar" que lo hace automáticamente

### Problema 4: La aplicación se congela

**Soluciones**:

1. **Esperar**
   - Operaciones grandes pueden tardar
   - Esperar 30 segundos antes de forzar cierre

2. **Cerrar y Reiniciar**
   - Ctrl + Alt + Supr → Administrador de Tareas
   - Finalizar tarea "LogisticaUI.exe"
   - Reiniciar aplicación

3. **Verificar Conexión**
   - Probar conexión desde Herramientas → Probar Conexión

### Problema 5: No aparecen los datos

**Verificar**:
1. Filtros aplicados (puede estar filtrando y no mostrar todos)
2. Hacer clic en "Mostrar Todos"
3. Verificar que hay datos en la base de datos

---

## CONTACTO Y SOPORTE

Para soporte adicional:

📧 **Email**: soporte@logisticaglobal.com  
📞 **Teléfono**: +56 2 1234 5678  
🕐 **Horario**: Lunes a Viernes, 9:00 - 18:00

---

## GLOSARIO

- **CRUD**: Create, Read, Update, Delete (Crear, Leer, Actualizar, Eliminar)
- **FK**: Foreign Key (Clave Foránea)
- **SQL**: Structured Query Language
- **UI**: User Interface (Interfaz de Usuario)
- **Validación**: Verificación de que los datos son correctos
- **Cascada**: Eliminación automática de registros relacionados

---

**Manual elaborado para facilitar el uso del sistema**  
**Versión**: 1.0  
**Fecha**: Octubre 2025
