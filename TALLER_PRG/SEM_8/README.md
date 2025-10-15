# Sistema de Gestión de Clientes - Logística Global

## 📋 Descripción del Proyecto

Sistema de escritorio desarrollado en C# con Windows Forms para la gestión integral de clientes y direcciones de una empresa de logística global. Implementa operaciones CRUD completas con SQL Server, arquitectura en capas y manejo robusto de excepciones.

## 🏗️ Arquitectura del Sistema

```
┌─────────────────────────────────────────┐
│   CAPA DE PRESENTACIÓN                  │
│   (Windows Forms - LogisticaUI)         │
│   - FormPrincipal.cs                    │
│   - FormClientes.cs                     │
│   - FormDirecciones.cs                  │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│   CAPA DE LÓGICA DE NEGOCIO             │
│   (Biblioteca de Clases - LogisticaBLL) │
│   ├── Entidades/                        │
│   │   ├── Cliente.cs                    │
│   │   └── Direccion.cs                  │
│   ├── DataAccess/                       │
│   │   ├── ClienteDAL.cs                 │
│   │   └── DireccionDAL.cs               │
│   └── Configuracion/                    │
│       └── Conexion.cs                   │
└──────────────┬──────────────────────────┘
               │
┌──────────────▼──────────────────────────┐
│   CAPA DE DATOS                         │
│   (SQL Server - LogisticaDB)            │
│   ├── Tabla: Clientes                   │
│   └── Tabla: Direcciones                │
└─────────────────────────────────────────┘
```

## 🗄️ Modelo Entidad-Relación

```
┌─────────────────────────┐
│       CLIENTES          │
├─────────────────────────┤
│ PK ClienteID (INT)      │
│    Nombre (NVARCHAR)    │
│    Telefono (NVARCHAR)  │
│    Email (NVARCHAR) UK  │
│    FechaRegistro (DT)   │
└───────────┬─────────────┘
            │ 1
            │
            │ N
┌───────────▼─────────────┐
│      DIRECCIONES        │
├─────────────────────────┤
│ PK DireccionID (INT)    │
│ FK ClienteID (INT)      │
│    Calle (NVARCHAR)     │
│    Ciudad (NVARCHAR)    │
│    Pais (NVARCHAR)      │
│    CodigoPostal (NVAR)  │
│    EsPrincipal (BIT)    │
└─────────────────────────┘

Relación: Un Cliente puede tener múltiples Direcciones (1:N)
Integridad: ON DELETE CASCADE
```

## 🛠️ Tecnologías Utilizadas

- **Lenguaje**: C# (.NET Framework 4.7.2)
- **IDE**: Visual Studio 2022
- **UI**: Windows Forms
- **Base de Datos**: SQL Server / SQL Server Express
- **ORM**: ADO.NET (sin ORM, acceso nativo)
- **Paquetes NuGet**:
  - System.Data.SqlClient (para conexión a SQL Server)
  - System.Configuration.ConfigurationManager (para App.config)

## 📦 Requisitos Previos

### Software Necesario
1. **Visual Studio 2022** (Community Edition o superior)
   - Carga de trabajo: Desarrollo de escritorio de .NET
2. **SQL Server 2019+** o **SQL Server Express**
3. **SQL Server Management Studio (SSMS)** 18.0 o superior
4. **.NET Framework 4.7.2** (incluido con Visual Studio 2022)

### Conocimientos Requeridos
- Programación Orientada a Objetos en C#
- SQL básico (DDL y DML)
- Windows Forms
- ADO.NET

## 🚀 Instalación y Configuración

### Paso 1: Clonar o Descargar el Proyecto

```bash
# Si usas Git
git clone [URL_DEL_REPOSITORIO]

# O descarga el ZIP y extráelo
```

### Paso 2: Configurar SQL Server

1. Abrir **SQL Server Management Studio (SSMS)**
2. Conectarse a tu instancia de SQL Server
3. Abrir el archivo `Script_CreacionBaseDatos.sql`
4. Ejecutar todo el script (F5)
5. Verificar que se creó la base de datos **LogisticaDB** con sus tablas

### Paso 3: Configurar la Cadena de Conexión

1. Abrir el proyecto en Visual Studio 2022
2. Localizar el archivo `App.config` en el proyecto LogisticaUI
3. Modificar la cadena de conexión según tu configuración:

```xml
<!-- Para Windows Authentication (Recomendado) -->
<add name="LogisticaDB" 
     connectionString="Server=localhost;Database=LogisticaDB;Integrated Security=true;TrustServerCertificate=true;" 
     providerName="System.Data.SqlClient" />

<!-- Para SQL Server Express -->
<add name="LogisticaDB" 
     connectionString="Server=localhost\SQLEXPRESS;Database=LogisticaDB;Integrated Security=true;TrustServerCertificate=true;" 
     providerName="System.Data.SqlClient" />

<!-- Para SQL Authentication -->
<add name="LogisticaDB" 
     connectionString="Server=localhost;Database=LogisticaDB;User Id=tu_usuario;Password=tu_contraseña;TrustServerCertificate=true;" 
     providerName="System.Data.SqlClient" />
```

### Paso 4: Instalar Paquetes NuGet

1. En Visual Studio, ir a **Herramientas → Administrador de paquetes NuGet → Administrar paquetes NuGet para la solución**
2. Instalar los siguientes paquetes:
   - `System.Data.SqlClient` (última versión estable)
   - `System.Configuration.ConfigurationManager`

O usar la Consola del Administrador de Paquetes:

```powershell
Install-Package System.Data.SqlClient
Install-Package System.Configuration.ConfigurationManager
```

### Paso 5: Compilar y Ejecutar

1. Configurar **LogisticaUI** como proyecto de inicio (clic derecho → Establecer como proyecto de inicio)
2. Compilar la solución: **Ctrl + Shift + B**
3. Ejecutar: **F5** o **Ctrl + F5**

## 📱 Uso de la Aplicación

### Gestión de Clientes
1. Desde el menú principal, seleccionar **Clientes → Gestionar Clientes**
2. **Nuevo**: Crear un nuevo cliente
3. **Modificar**: Editar cliente seleccionado
4. **Eliminar**: Borrar cliente (eliminará también sus direcciones)
5. **Buscar**: Filtrar clientes por nombre o email

### Gestión de Direcciones
1. Desde el menú principal, seleccionar **Direcciones → Gestionar Direcciones**
2. **Filtrar por Cliente**: Ver solo direcciones de un cliente específico
3. **Nuevo**: Agregar nueva dirección a un cliente
4. **Modificar**: Editar dirección seleccionada
5. **Eliminar**: Borrar dirección
6. **Dirección Principal**: Marcar checkbox para indicar dirección principal

### Validaciones Implementadas
- ✅ Campos obligatorios (Nombre, Email, Calle, Ciudad, País)
- ✅ Formato de email válido
- ✅ Email único (no duplicados)
- ✅ Longitudes mínimas y máximas de campos
- ✅ Validación de selección de cliente en direcciones

## 🧪 Pruebas y Manejo de Excepciones

### Casos de Prueba Implementados

#### 1. **Prueba de Conexión Interrumpida**
```
Acción: Detener SQL Server mientras la aplicación está ejecutándose
Resultado Esperado: Mensaje de error amigable
Manejo: SqlException capturada y mensaje personalizado
```

#### 2. **Prueba de Datos Duplicados**
```
Acción: Intentar insertar cliente con email existente
Resultado Esperado: Error de violación de clave única
Manejo: Captura de SqlException 2627/2601 con mensaje claro
```

#### 3. **Prueba de Campos Vacíos**
```
Acción: Intentar guardar sin completar campos obligatorios
Resultado Esperado: Validación antes de enviar a BD
Manejo: Método EsValido() en entidades
```

#### 4. **Prueba de Eliminación con Relaciones**
```
Acción: Eliminar cliente que tiene direcciones
Resultado Esperado: Eliminación en cascada de direcciones
Manejo: ON DELETE CASCADE en BD
```

#### 5. **Prueba de Formato Inválido**
```
Acción: Ingresar email sin formato válido
Resultado Esperado: Validación de formato con Regex
Manejo: Método EsEmailValido() con expresión regular
```

### Excepciones Manejadas

```csharp
try
{
    // Operación de base de datos
}
catch (SqlException ex)
{
    // Errores específicos de SQL Server
    switch (ex.Number)
    {
        case 2:    // No conecta al servidor
        case 53:   // Servidor no encontrado
        case 4060: // Base de datos no existe
        case 18456: // Error de autenticación
        case 2627: // Clave única duplicada
        case 547:  // Violación de FK
    }
}
catch (ArgumentException ex)
{
    // Validaciones de negocio
}
catch (Exception ex)
{
    // Otros errores generales
}
```

## 📊 Estructura del Proyecto

```
SolucionLogistica/
│
├── LogisticaBLL/                    # Biblioteca de Clases
│   ├── Entidades/
│   │   ├── Cliente.cs
│   │   └── Direccion.cs
│   ├── DataAccess/
│   │   ├── ClienteDAL.cs
│   │   └── DireccionDAL.cs
│   └── Configuracion/
│       └── Conexion.cs
│
├── LogisticaUI/                     # Aplicación Windows Forms
│   ├── Forms/
│   │   ├── FormPrincipal.cs
│   │   ├── FormClientes.cs
│   │   └── FormDirecciones.cs
│   ├── Program.cs
│   └── App.config
│
└── Scripts/
    └── Script_CreacionBaseDatos.sql
```

## 🔒 Seguridad

- ✅ Uso de parámetros SQL para prevenir inyección SQL
- ✅ Validación de entrada en capa de presentación y negocio
- ✅ Cadena de conexión en archivo de configuración (no en código)
- ✅ Manejo de excepciones sin exponer información sensible
- ⚠️ **Recomendación**: Para producción, cifrar cadenas de conexión

## 📈 Mejoras Futuras

1. **Autenticación de Usuarios**: Sistema de login con roles
2. **Auditoría**: Registro de cambios (quién, cuándo, qué)
3. **Reportes**: Exportación a PDF/Excel
4. **Búsqueda Avanzada**: Filtros múltiples y paginación
5. **Validación Asíncrona**: Verificación de email único en tiempo real
6. **Unit Tests**: Suite de pruebas unitarias con NUnit/xUnit
7. **Logging Avanzado**: Implementar NLog o Serilog
8. **Migration to .NET**: Actualizar a .NET 8 y EF Core

## 🐛 Solución de Problemas Comunes

### Error: "Cannot open database LogisticaDB"
**Solución**: Ejecutar el script SQL de creación de base de datos

### Error: "A network-related or instance-specific error"
**Solución**: 
- Verificar que SQL Server esté ejecutándose
- Revisar el nombre del servidor en la cadena de conexión
- Habilitar TCP/IP en SQL Server Configuration Manager

### Error: "Login failed for user"
**Solución**:
- Verificar credenciales en cadena de conexión
- Usar Windows Authentication si es posible
- Verificar permisos del usuario en SQL Server

### Error: "Could not load file or assembly System.Data.SqlClient"
**Solución**: Reinstalar el paquete NuGet System.Data.SqlClient


**¡Gracias por usar el Sistema de Gestión de Clientes!** 🚀
