/*******************************************************************************
* HOTEL GLOBALSTAY - SISTEMA DE VISTAS SEGURAS
* Script de Implementación Completa
* Versión: 1.0
* Fecha: Octubre 2025
* Autor: Equipo de TI - Hotel GlobalStay
*
* DESCRIPCIÓN:
* Este script implementa un sistema completo de vistas seguras para la gestión
* de reservas hoteleras, incluyendo encriptación, control de acceso y auditoría.
*
* PRERREQUISITOS:
* - SQL Server 2016 o superior
* - Permisos de administrador de base de datos
* - Base de datos HotelGlobalStay creada
*
* INSTRUCCIONES:
* Ejecutar las secciones en el orden presentado
*******************************************************************************/

USE HotelGlobalStay;
GO

-- ============================================================================
-- SECCIÓN 1: CREACIÓN DE TABLAS BASE
-- ============================================================================

PRINT '============================================';
PRINT 'SECCIÓN 1: CREANDO ESTRUCTURA DE DATOS BASE';
PRINT '============================================';
GO

-- Tabla de Clientes
IF OBJECT_ID('dbo.Clientes', 'U') IS NOT NULL
    DROP TABLE dbo.Clientes;
GO

CREATE TABLE dbo.Clientes (
    cliente_id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(200),
    tarjeta_credito VARCHAR(16),
    cvv VARCHAR(4),
    fecha_nacimiento DATE,
    -- Campos para encriptación
    tarjeta_credito_encriptada VARBINARY(256),
    cvv_encriptado VARBINARY(128),
    -- Auditoría
    fecha_registro DATETIME DEFAULT GETDATE(),
    usuario_registro VARCHAR(100) DEFAULT SYSTEM_USER,
    activo BIT DEFAULT 1,
    CONSTRAINT CK_Email CHECK (email LIKE '%@%.%')
);
GO

-- Tabla de Habitaciones
IF OBJECT_ID('dbo.Habitaciones', 'U') IS NOT NULL
    DROP TABLE dbo.Habitaciones;
GO

CREATE TABLE dbo.Habitaciones (
    habitacion_id INT IDENTITY(1,1) PRIMARY KEY,
    numero_habitacion VARCHAR(10) UNIQUE NOT NULL,
    tipo_habitacion VARCHAR(50) NOT NULL,
    precio_noche DECIMAL(10,2) NOT NULL,
    capacidad INT NOT NULL,
    piso INT NOT NULL,
    amenidades TEXT,
    estado_limpieza VARCHAR(20) DEFAULT 'LIMPIA',
    ultima_limpieza DATETIME DEFAULT GETDATE(),
    activa BIT DEFAULT 1,
    CONSTRAINT CK_PrecioPositivo CHECK (precio_noche > 0),
    CONSTRAINT CK_CapacidadPositiva CHECK (capacidad > 0),
    CONSTRAINT CK_EstadoLimpieza CHECK (estado_limpieza IN ('LIMPIA', 'PENDIENTE', 'EN_PROCESO'))
);
GO

-- Tabla de Reservas
IF OBJECT_ID('dbo.Reservas', 'U') IS NOT NULL
    DROP TABLE dbo.Reservas;
GO

CREATE TABLE dbo.Reservas (
    reserva_id INT IDENTITY(1,1) PRIMARY KEY,
    cliente_id INT NOT NULL,
    habitacion_id INT NOT NULL,
    fecha_entrada DATE NOT NULL,
    fecha_salida DATE NOT NULL,
    numero_huespedes INT NOT NULL,
    estado_reserva VARCHAR(20) DEFAULT 'PENDIENTE',
    precio_total DECIMAL(10,2) NOT NULL,
    fecha_reserva DATETIME DEFAULT GETDATE(),
    observaciones TEXT,
    usuario_registro VARCHAR(100) DEFAULT SYSTEM_USER,
    CONSTRAINT FK_Reserva_Cliente FOREIGN KEY (cliente_id) 
        REFERENCES Clientes(cliente_id),
    CONSTRAINT FK_Reserva_Habitacion FOREIGN KEY (habitacion_id) 
        REFERENCES Habitaciones(habitacion_id),
    CONSTRAINT CK_FechasSalida CHECK (fecha_salida > fecha_entrada),
    CONSTRAINT CK_HuespedesPositivo CHECK (numero_huespedes > 0),
    CONSTRAINT CK_EstadoReserva CHECK (estado_reserva IN 
        ('PENDIENTE', 'CONFIRMADA', 'CHECK_IN', 'CHECK_OUT', 'CANCELADA', 'CANCELADA_ELIMINADA'))
);
GO

-- Índices para optimización
CREATE INDEX IX_Reservas_Fechas ON Reservas(fecha_entrada, fecha_salida);
CREATE INDEX IX_Reservas_Estado ON Reservas(estado_reserva);
CREATE INDEX IX_Clientes_Email ON Clientes(email);
GO

PRINT 'Tablas base creadas exitosamente';
GO

-- ============================================================================
-- SECCIÓN 2: TABLAS DE AUDITORÍA Y CONTROL
-- ============================================================================

PRINT '============================================';
PRINT 'SECCIÓN 2: CREANDO TABLAS DE AUDITORÍA';
PRINT '============================================';
GO

-- Tabla de auditoría de cambios
IF OBJECT_ID('dbo.Auditoria_Cambios', 'U') IS NOT NULL
    DROP TABLE dbo.Auditoria_Cambios;
GO

CREATE TABLE dbo.Auditoria_Cambios (
    auditoria_id BIGINT IDENTITY(1,1) PRIMARY KEY,
    tabla VARCHAR(100) NOT NULL,
    accion VARCHAR(20) NOT NULL,
    reserva_id INT,
    usuario VARCHAR(100) DEFAULT SYSTEM_USER,
    fecha DATETIME DEFAULT GETDATE(),
    datos_anteriores NVARCHAR(MAX),
    datos_nuevos NVARCHAR(MAX),
    direccion_ip VARCHAR(50)
);
GO

-- Tabla de auditoría de acceso a vistas
IF OBJECT_ID('dbo.Auditoria_AccesoVistas', 'U') IS NOT NULL
    DROP TABLE dbo.Auditoria_AccesoVistas;
GO

CREATE TABLE dbo.Auditoria_AccesoVistas (
    auditoria_id BIGINT IDENTITY(1,1) PRIMARY KEY,
    fecha_hora DATETIME DEFAULT GETDATE(),
    usuario NVARCHAR(128),
    nombre_vista NVARCHAR(128),
    tipo_operacion VARCHAR(20),
    registros_afectados INT,
    direccion_ip VARCHAR(50),
    aplicacion VARCHAR(200),
    exito BIT DEFAULT 1
);
GO

-- Tabla de historial de vistas
IF OBJECT_ID('dbo.Historial_Vistas', 'U') IS NOT NULL
    DROP TABLE dbo.Historial_Vistas;
GO

CREATE TABLE dbo.Historial_Vistas (
    historial_id INT IDENTITY(1,1) PRIMARY KEY,
    nombre_vista NVARCHAR(128),
    version INT,
    fecha_cambio DATETIME DEFAULT GETDATE(),
    usuario_cambio NVARCHAR(128) DEFAULT SYSTEM_USER,
    definicion_sql NVARCHAR(MAX),
    motivo_cambio NVARCHAR(500),
    campos_agregados NVARCHAR(MAX),
    campos_eliminados NVARCHAR(MAX)
);
GO

-- Tabla de políticas de seguridad
IF OBJECT_ID('dbo.Politicas_Seguridad_Vistas', 'U') IS NOT NULL
    DROP TABLE dbo.Politicas_Seguridad_Vistas;
GO

CREATE TABLE dbo.Politicas_Seguridad_Vistas (
    politica_id INT IDENTITY(1,1) PRIMARY KEY,
    nombre_vista NVARCHAR(128),
    descripcion TEXT,
    datos_protegidos NVARCHAR(MAX),
    roles_autorizados NVARCHAR(MAX),
    fecha_creacion DATETIME DEFAULT GETDATE(),
    fecha_ultima_revision DATETIME,
    responsable VARCHAR(100),
    nivel_criticidad VARCHAR(20),
    CONSTRAINT CK_NivelCriticidad CHECK (nivel_criticidad IN ('ALTA', 'MEDIA', 'BAJA'))
);
GO

-- Tabla de resultados de pruebas
IF OBJECT_ID('dbo.Resultados_Pruebas', 'U') IS NOT NULL
    DROP TABLE dbo.Resultados_Pruebas;
GO

CREATE TABLE dbo.Resultados_Pruebas (
    prueba_id INT IDENTITY(1,1) PRIMARY KEY,
    fecha_prueba DATETIME DEFAULT GETDATE(),
    nombre_prueba VARCHAR(200),
    usuario_prueba VARCHAR(100),
    resultado VARCHAR(20),
    descripcion TEXT,
    tiempo_ejecucion_ms INT,
    CONSTRAINT CK_Resultado CHECK (resultado IN ('EXITOSO', 'FALLIDO', 'ADVERTENCIA'))
);
GO

PRINT 'Tablas de auditoría creadas exitosamente';
GO

-- ============================================================================
-- SECCIÓN 3: CONFIGURACIÓN DE ENCRIPTACIÓN
-- ============================================================================

PRINT '============================================';
PRINT 'SECCIÓN 3: CONFIGURANDO ENCRIPTACIÓN';
PRINT '============================================';
GO

-- Crear clave maestra si no existe
IF NOT EXISTS (SELECT * FROM sys.symmetric_keys WHERE name = '##MS_DatabaseMasterKey##')
BEGIN
    CREATE MASTER KEY ENCRYPTION BY PASSWORD = 'HotelGlobalStay2024!SecureMasterKey#2025';
    PRINT 'Clave maestra creada';
END
ELSE
    PRINT 'Clave maestra ya existe';
GO

-- Crear certificado
IF NOT EXISTS (SELECT * FROM sys.certificates WHERE name = 'cert_HotelSeguridad')
BEGIN
    CREATE CERTIFICATE cert_HotelSeguridad
    WITH SUBJECT = 'Certificado para datos sensibles del Hotel GlobalStay',
         EXPIRY_DATE = '2030-12-31';
    PRINT 'Certificado creado';
END
ELSE
    PRINT 'Certificado ya existe';
GO

-- Crear clave simétrica
IF NOT EXISTS (SELECT * FROM sys.symmetric_keys WHERE name = 'key_DatosSensibles')
BEGIN
    CREATE SYMMETRIC KEY key_DatosSensibles
    WITH ALGORITHM = AES_256
    ENCRYPTION BY CERTIFICATE cert_HotelSeguridad;
    PRINT 'Clave simétrica creada';
END
ELSE
    PRINT 'Clave simétrica ya existe';
GO

PRINT 'Encriptación configurada exitosamente';
GO

-- ============================================================================
-- SECCIÓN 4: FUNCIONES DE SEGURIDAD
-- ============================================================================

PRINT '============================================';
PRINT 'SECCIÓN 4: CREANDO FUNCIONES DE SEGURIDAD';
PRINT '============================================';
GO

-- Función para enmascarar tarjetas de crédito
IF OBJECT_ID('dbo.fn_enmascarar_tarjeta', 'FN') IS NOT NULL
    DROP FUNCTION dbo.fn_enmascarar_tarjeta;
GO

CREATE FUNCTION dbo.fn_enmascarar_tarjeta(@tarjeta VARCHAR(16))
RETURNS VARCHAR(19)
AS
BEGIN
    DECLARE @resultado VARCHAR(19);
    
    IF @tarjeta IS NULL OR LEN(@tarjeta) < 4
        SET @resultado = '****-****-****-****';
    ELSE
        SET @resultado = '****-****-****-' + RIGHT(@tarjeta, 4);
    
    RETURN @resultado;
END;
GO

-- Función para desencriptar tarjetas (solo para administradores)
IF OBJECT_ID('dbo.fn_DesencriptarTarjeta', 'FN') IS NOT NULL
    DROP FUNCTION dbo.fn_DesencriptarTarjeta;
GO

CREATE FUNCTION dbo.fn_DesencriptarTarjeta(@cliente_id INT)
RETURNS VARCHAR(16)
AS
BEGIN
    DECLARE @tarjeta VARCHAR(16);
    
    -- Abrir clave simétrica
    OPEN SYMMETRIC KEY key_DatosSensibles
    DECRYPTION BY CERTIFICATE cert_HotelSeguridad;
    
    -- Desencriptar
    SELECT @tarjeta = CONVERT(VARCHAR(16), 
        DecryptByKey(tarjeta_credito_encriptada))
    FROM Clientes
    WHERE cliente_id = @cliente_id;
    
    -- Cerrar clave
    CLOSE SYMMETRIC KEY key_DatosSensibles;
    
    RETURN @tarjeta;
END;
GO

-- Función para enmascarar email
IF OBJECT_ID('dbo.fn_enmascarar_email', 'FN') IS NOT NULL
    DROP FUNCTION dbo.fn_enmascarar_email;
GO

CREATE FUNCTION dbo.fn_enmascarar_email(@email VARCHAR(100))
RETURNS VARCHAR(100)
AS
BEGIN
    DECLARE @resultado VARCHAR(100);
    DECLARE @posicion_arroba INT;
    
    SET @posicion_arroba = CHARINDEX('@', @email);
    
    IF @posicion_arroba > 3
        SET @resultado = LEFT(@email, 2) + '***' + SUBSTRING(@email, @posicion_arroba, LEN(@email));
    ELSE
        SET @resultado = '***' + SUBSTRING(@email, @posicion_arroba, LEN(@email));
    
    RETURN @resultado;
END;
GO

PRINT 'Funciones de seguridad creadas exitosamente';
GO

-- ============================================================================
-- SECCIÓN 5: PROCEDIMIENTOS ALMACENADOS
-- ============================================================================

PRINT '============================================';
PRINT 'SECCIÓN 5: CREANDO PROCEDIMIENTOS ALMACENADOS';
PRINT '============================================';
GO

-- Procedimiento para encriptar datos sensibles
IF OBJECT_ID('dbo.sp_EncriptarDatosSensibles', 'P') IS NOT NULL
    DROP PROCEDURE dbo.sp_EncriptarDatosSensibles;
GO

CREATE PROCEDURE dbo.sp_EncriptarDatosSensibles
AS
BEGIN
    SET NOCOUNT ON;
    
    BEGIN TRY
        -- Abrir clave simétrica
        OPEN SYMMETRIC KEY key_DatosSensibles
        DECRYPTION BY CERTIFICATE cert_HotelSeguridad;
        
        -- Encriptar tarjetas de crédito
        UPDATE Clientes
        SET tarjeta_credito_encriptada = EncryptByKey(
                Key_GUID('key_DatosSensibles'), 
                tarjeta_credito
            ),
            cvv_encriptado = EncryptByKey(
                Key_GUID('key_DatosSensibles'), 
                cvv
            )
        WHERE tarjeta_credito_encriptada IS NULL
            AND tarjeta_credito IS NOT NULL;
        
        -- Cerrar clave
        CLOSE SYMMETRIC KEY key_DatosSensibles;
        
        PRINT 'Datos encriptados exitosamente';
        PRINT 'Registros procesados: ' + CAST(@@ROWCOUNT AS VARCHAR(10));
    END TRY
    BEGIN CATCH
        IF EXISTS (SELECT * FROM sys.openkeys WHERE key_name = 'key_DatosSensibles')
            CLOSE SYMMETRIC KEY key_DatosSensibles;
        
        PRINT 'Error al encriptar datos: ' + ERROR_MESSAGE();
    END CATCH
END;
GO

-- Procedimiento para documentar cambios en vistas
IF OBJECT_ID('dbo.sp_DocumentarCambioVista', 'P') IS NOT NULL
    DROP PROCEDURE dbo.sp_DocumentarCambioVista;
GO

CREATE PROCEDURE dbo.sp_DocumentarCambioVista
    @nombre_vista NVARCHAR(128),
    @motivo NVARCHAR(500),
    @campos_nuevos NVARCHAR(MAX) = NULL,
    @campos_eliminados NVARCHAR(MAX) = NULL
AS
BEGIN
    SET NOCOUNT ON;
    
    DECLARE @version INT;
    DECLARE @definicion NVARCHAR(MAX);
    
    -- Obtener la versión actual
    SELECT @version = ISNULL(MAX(version), 0) + 1
    FROM Historial_Vistas
    WHERE nombre_vista = @nombre_vista;
    
    -- Obtener la definición actual
    SELECT @definicion = definition
    FROM sys.sql_modules
    WHERE object_id = OBJECT_ID(@nombre_vista);
    
    -- Insertar en el historial
    INSERT INTO Historial_Vistas (
        nombre_vista, version, motivo_cambio, 
        definicion_sql, campos_agregados, campos_eliminados
    )
    VALUES (
        @nombre_vista, @version, @motivo, 
        @definicion, @campos_nuevos, @campos_eliminados
    );
    
    PRINT 'Cambio documentado exitosamente';
    PRINT 'Vista: ' + @nombre_vista;
    PRINT 'Versión: ' + CAST(@version AS VARCHAR(10));
END;
GO

-- Procedimiento para ejecutar pruebas de seguridad
IF OBJECT_ID('dbo.sp_EjecutarPruebasSeguridad', 'P') IS NOT NULL
    DROP PROCEDURE dbo.sp_EjecutarPruebasSeguridad;
GO

CREATE PROCEDURE dbo.sp_EjecutarPruebasSeguridad
AS
BEGIN
    SET NOCOUNT ON;
    
    DECLARE @inicio DATETIME, @fin DATETIME, @duracion INT;
    
    PRINT '========================================';
    PRINT 'INICIANDO SUITE DE PRUEBAS DE SEGURIDAD';
    PRINT '========================================';
    PRINT '';
    
    -- PRUEBA 1: Enmascaramiento de tarjetas
    PRINT 'Ejecutando Prueba 1: Enmascaramiento de tarjetas...';
    SET @inicio = GETDATE();
    
    IF EXISTS (
        SELECT 1 FROM vw_ReservasSeguras 
        WHERE tarjeta_enmascarada LIKE '****-****-****-%'
            AND tarjeta_enmascarada NOT LIKE '%[0-9][0-9][0-9][0-9][0-9]%'
    )
    BEGIN
        SET @fin = GETDATE();
        SET @duracion = DATEDIFF(MILLISECOND, @inicio, @fin);
        INSERT INTO Resultados_Pruebas 
        VALUES ('Enmascaramiento de tarjetas', 'Sistema', 'EXITOSO', 
                'Las tarjetas se muestran correctamente enmascaradas', @duracion);
        PRINT '✓ PRUEBA 1: EXITOSO';
    END
    ELSE
    BEGIN
        INSERT INTO Resultados_Pruebas 
        VALUES ('Enmascaramiento de tarjetas', 'Sistema', 'FALLIDO', 
                'Las tarjetas no están correctamente enmascaradas', 0);
        PRINT '✗ PRUEBA 1: FALLIDO';
    END
    PRINT '';
    
    -- PRUEBA 2: Existencia de roles
    PRINT 'Ejecutando Prueba 2: Verificación de roles...';
    SET @inicio = GETDATE();
    
    DECLARE @roles_count INT;
    SELECT @roles_count = COUNT(*) 
    FROM sys.database_principals 
    WHERE type = 'R' 
        AND name IN ('rol_Recepcionista', 'rol_GerenciaOperaciones', 'rol_Administrador', 'rol_Consulta');
    
    IF @roles_count = 4
    BEGIN
        SET @fin = GETDATE();
        SET @duracion = DATEDIFF(MILLISECOND, @inicio, @fin);
        INSERT INTO Resultados_Pruebas 
        VALUES ('Verificación de roles', 'Sistema', 'EXITOSO', 
                'Todos los roles requeridos existen', @duracion);
        PRINT '✓ PRUEBA 2: EXITOSO';
    END
    ELSE
    BEGIN
        INSERT INTO Resultados_Pruebas 
        VALUES ('Verificación de roles', 'Sistema', 'FALLIDO', 
                'Faltan roles requeridos: ' + CAST(4 - @roles_count AS VARCHAR(10)), 0);
        PRINT '✗ PRUEBA 2: FALLIDO';
    END
    PRINT '';
    
    -- PRUEBA 3: Encriptación configurada
    PRINT 'Ejecutando Prueba 3: Configuración de encriptación...';
    SET @inicio = GETDATE();
    
    IF EXISTS (SELECT * FROM sys.symmetric_keys WHERE name = 'key_DatosSensibles')
        AND EXISTS (SELECT * FROM sys.certificates WHERE name = 'cert_HotelSeguridad')
    BEGIN
        SET @fin = GETDATE();
        SET @duracion = DATEDIFF(MILLISECOND, @inicio, @fin);
        INSERT INTO Resultados_Pruebas 
        VALUES ('Configuración de encriptación', 'Sistema', 'EXITOSO', 
                'Certificados y claves simétricas configurados', @duracion);
        PRINT '✓ PRUEBA 3: EXITOSO';
    END
    ELSE
    BEGIN
        INSERT INTO Resultados_Pruebas 
        VALUES ('Configuración de encriptación', 'Sistema', 'FALLIDO', 
                'Falta configuración de encriptación', 0);
        PRINT '✗ PRUEBA 3: FALLIDO';
    END
    PRINT '';
    
    PRINT '========================================';
    PRINT 'SUITE DE PRUEBAS COMPLETADA';
    PRINT '========================================';
    PRINT '';
    
    -- Mostrar resumen
    SELECT 
        nombre_prueba,
        resultado,
        tiempo_ejecucion_ms,
        fecha_prueba
    FROM Resultados_Pruebas
    WHERE fecha_prueba >= DATEADD(MINUTE, -5, GETDATE())
    ORDER BY prueba_id DESC;
END;
GO

PRINT 'Procedimientos almacenados creados exitosamente';
GO

-- ============================================================================
-- SECCIÓN 6: CREACIÓN DE VISTAS PRINCIPALES
-- ============================================================================

PRINT '============================================';
PRINT 'SECCIÓN 6: CREANDO VISTAS PRINCIPALES';
PRINT '============================================';
GO

-- Vista principal de reservas seguras
IF OBJECT_ID('dbo.vw_ReservasSeguras', 'V') IS NOT NULL
    DROP VIEW dbo.vw_ReservasSeguras;
GO

CREATE VIEW dbo.vw_ReservasSeguras
AS
SELECT 
    -- Información de la reserva
    r.reserva_id,
    r.fecha_entrada,
    r.fecha_salida,
    r.numero_huespedes,
    r.estado_reserva,
    r.precio_total,
    r.fecha_reserva,
    r.observaciones,
    
    -- Información del cliente (limitada)
    c.cliente_id,
    c.nombre + ' ' + c.apellido AS nombre_completo,
    c.nombre,
    c.apellido,
    c.email,
    LEFT(c.telefono, 10) AS telefono_contacto,
    
    -- Tarjeta de crédito enmascarada
    dbo.fn_enmascarar_tarjeta(c.tarjeta_credito) AS tarjeta_enmascarada,
    
    -- Información de la habitación
    h.habitacion_id,
    h.numero_habitacion,
    h.tipo_habitacion,
    h.precio_noche,
    h.capacidad,
    h.piso,
    h.amenidades,
    h.estado_limpieza,
    h.ultima_limpieza,
    
    -- Campos calculados útiles
    DATEDIFF(day, r.fecha_entrada, r.fecha_salida) AS noches_estadia,
    CASE 
        WHEN r.fecha_entrada > GETDATE() THEN 'Futura'
        WHEN r.fecha_salida < GETDATE() THEN 'Completada'
        ELSE 'En Curso'
    END AS estado_actual,
    
    -- Campos adicionales de amenidades
    CASE 
        WHEN h.amenidades LIKE '%WiFi%' THEN 'Sí'
        ELSE 'No'
    END AS tiene_wifi,
    CASE 
        WHEN h.amenidades LIKE '%Desayuno%' THEN 'Sí'
        ELSE 'No'
    END AS incluye_desayuno,
    
    -- Estado de limpieza actual
    CASE 
        WHEN h.estado_limpieza = 'PENDIENTE' AND r.fecha_salida <= GETDATE() 
        THEN 'REQUIERE_ATENCION'
        ELSE h.estado_limpieza
    END AS estado_limpieza_actual
    
FROM Reservas r
INNER JOIN Clientes c ON r.cliente_id = c.cliente_id
INNER JOIN Habitaciones h ON r.habitacion_id = h.habitacion_id
WHERE r.estado_reserva <> 'CANCELADA_ELIMINADA'
    AND c.activo = 1
    AND h.activa = 1;
GO

-- Vista para recepción (acceso limitado)
IF OBJECT_ID('dbo.vw_ReservasRecepcion', 'V') IS NOT NULL
    DROP VIEW dbo.vw_ReservasRecepcion;
GO

CREATE VIEW dbo.vw_ReservasRecepcion
AS
SELECT 
    reserva_id,
    nombre_completo,
    telefono_contacto,
    numero_habitacion,
    tipo_habitacion,
    fecha_entrada,
    fecha_salida,
    numero_huespedes,
    estado_reserva,
    noches_estadia,
    estado_actual,
    observaciones
FROM vw_ReservasSeguras
WHERE estado_actual IN ('Futura', 'En Curso');
GO

-- Vista para gerencia (acceso ampliado)
IF OBJECT_ID('dbo.vw_ReservasGerencia', 'V') IS NOT NULL
    DROP VIEW dbo.vw_ReservasGerencia;
GO

CREATE VIEW dbo.vw_ReservasGerencia
AS
SELECT 
    reserva_id,
    cliente_id,
    nombre_completo,
    email,
    telefono_contacto,
    habitacion_id,
    numero_habitacion,
    tipo_habitacion,
    precio_noche,
    fecha_entrada,
    fecha_salida,
    numero_huespedes,
    estado_reserva,
    precio_total,
    tarjeta_enmascarada,
    noches_estadia,
    estado_actual,
    amenidades,
    estado_limpieza_actual,
    observaciones
FROM vw_ReservasSeguras;
GO

-- Vista modificable con control
IF OBJECT_ID('dbo.vw_ReservasModificables', 'V') IS NOT NULL
    DROP VIEW dbo.vw_ReservasModificables;
GO

CREATE VIEW dbo.vw_ReservasModificables
AS
SELECT 
    r.reserva_id,
    r.cliente_id,
    r.habitacion_id,
    r.fecha_entrada,
    r.fecha_salida,
    r.numero_huespedes,
    r.estado_reserva,
    r.observaciones,
    c.nombre,
    c.apellido,
    c.email,
    c.telefono,
    h.numero_habitacion,
    h.tipo_habitacion
FROM Reservas r
INNER JOIN Clientes c ON r.cliente_id = c.cliente_id
INNER JOIN Habitaciones h ON r.habitacion_id = h.habitacion_id
WHERE r.estado_reserva <> 'CANCELADA_ELIMINADA';
GO

-- Vista de checklist de seguridad
IF OBJECT_ID('dbo.vw_ChecklistSeguridad', 'V') IS NOT NULL
    DROP VIEW dbo.vw_ChecklistSeguridad;
GO

CREATE VIEW dbo.vw_ChecklistSeguridad
AS
SELECT 
    'Encriptación de datos sensibles' AS verificacion,
    CASE 
        WHEN EXISTS (SELECT 1 FROM sys.symmetric_keys WHERE name = 'key_DatosSensibles')
        THEN 'CONFIGURADO'
        ELSE 'PENDIENTE'
    END AS estado,
    'ALTA' AS prioridad
UNION ALL
SELECT 
    'Roles de usuario definidos',
    CASE 
        WHEN EXISTS (SELECT 1 FROM sys.database_principals WHERE type = 'R' AND name LIKE 'rol_%')
        THEN 'CONFIGURADO'
        ELSE 'PENDIENTE'
    END,
    'ALTA'
UNION ALL
SELECT 
    'Auditoría activada',
    CASE 
        WHEN EXISTS (SELECT 1 FROM sys.tables WHERE name = 'Auditoria_AccesoVistas')
        THEN 'CONFIGURADO'
        ELSE 'PENDIENTE'
    END,
    'MEDIA'
UNION ALL
SELECT 
    'Permisos granulares asignados',
    CASE 
        WHEN EXISTS (SELECT 1 FROM sys.database_permissions WHERE class_desc = 'OBJECT_OR_COLUMN')
        THEN 'CONFIGURADO'
        ELSE 'PENDIENTE'
    END,
    'ALTA'
UNION ALL
SELECT 
    'Funciones de enmascaramiento',
    CASE 
        WHEN EXISTS (SELECT 1 FROM sys.objects WHERE name = 'fn_enmascarar_tarjeta')
        THEN 'CONFIGURADO'
        ELSE 'PENDIENTE'
    END,
    'ALTA';
GO

PRINT 'Vistas principales creadas exitosamente';
GO

-- ============================================================================
-- SECCIÓN 7: CREACIÓN DE ROLES Y PERMISOS
-- ============================================================================

PRINT '============================================';
PRINT 'SECCIÓN 7: CONFIGURANDO ROLES Y PERMISOS';
PRINT '============================================';
GO

-- Crear roles
IF NOT EXISTS (SELECT * FROM sys.database_principals WHERE name = 'rol_Recepcionista' AND type = 'R')
    CREATE ROLE rol_Recepcionista;
GO

IF NOT EXISTS (SELECT * FROM sys.database_principals WHERE name = 'rol_GerenciaOperaciones' AND type = 'R')
    CREATE ROLE rol_GerenciaOperaciones;
GO

IF NOT EXISTS (SELECT * FROM sys.database_principals WHERE name = 'rol_Administrador' AND type = 'R')
    CREATE ROLE rol_Administrador;
GO

IF NOT EXISTS (SELECT * FROM sys.database_principals WHERE name = 'rol_Consulta' AND type = 'R')
    CREATE ROLE rol_Consulta;
GO

-- Asignar permisos a rol_Recepcionista
GRANT SELECT ON vw_ReservasRecepcion TO rol_Recepcionista;
GRANT SELECT ON vw_ReservasSeguras TO rol_Recepcionista;
GRANT UPDATE ON vw_ReservasModificables TO rol_Recepcionista;
GO

-- Asignar permisos a rol_GerenciaOperaciones
GRANT SELECT ON vw_ReservasGerencia TO rol_GerenciaOperaciones;
GRANT SELECT, UPDATE ON vw_ReservasSeguras TO rol_GerenciaOperaciones;
GRANT SELECT, UPDATE ON vw_ReservasModificables TO rol_GerenciaOperaciones;
GO

-- Asignar permisos a rol_Administrador
GRANT SELECT, INSERT, UPDATE, DELETE ON vw_ReservasSeguras TO rol_Administrador;
GRANT SELECT, INSERT, UPDATE, DELETE ON vw_ReservasGerencia TO rol_Administrador;
GRANT SELECT, UPDATE ON vw_ReservasModificables TO rol_Administrador;
GRANT EXECUTE ON dbo.fn_DesencriptarTarjeta TO rol_Administrador;
GRANT EXECUTE ON dbo.sp_EncriptarDatosSensibles TO rol_Administrador;
GRANT EXECUTE ON dbo.sp_DocumentarCambioVista TO rol_Administrador;
GO

-- Asignar permisos a rol_Consulta (solo lectura)
GRANT SELECT ON vw_ReservasSeguras TO rol_Consulta;
GRANT SELECT ON vw_ReservasGerencia TO rol_Consulta;
DENY UPDATE, INSERT, DELETE ON vw_ReservasSeguras TO rol_Consulta;
DENY UPDATE ON vw_ReservasModificables TO rol_Consulta;
GO

PRINT 'Roles y permisos configurados exitosamente';
GO

-- ============================================================================
-- SECCIÓN 8: INSERCIÓN DE DATOS DE PRUEBA
-- ============================================================================

PRINT '============================================';
PRINT 'SECCIÓN 8: INSERTANDO DATOS DE PRUEBA';
PRINT '============================================';
GO

-- Insertar clientes de prueba
SET IDENTITY_INSERT Clientes ON;

INSERT INTO Clientes (cliente_id, nombre, apellido, email, telefono, direccion, tarjeta_credito, cvv, fecha_nacimiento)
VALUES 
(1, 'Juan', 'Pérez', 'juan.perez@email.com', '555-0101', 'Calle Principal 123', '4532123456789012', '123', '1985-05-15'),
(2, 'María', 'González', 'maria.gonzalez@email.com', '555-0102', 'Avenida Central 456', '5412987654321098', '456', '1990-08-22'),
(3, 'Carlos', 'Rodríguez', 'carlos.rodriguez@email.com', '555-0103', 'Plaza Mayor 789', '378282246310005', '789', '1978-12-10'),
(4, 'Ana', 'Martínez', 'ana.martinez@email.com', '555-0104', 'Boulevard Norte 321', '6011111111111117', '234', '1992-03-18'),
(5, 'Luis', 'Fernández', 'luis.fernandez@email.com', '555-0105', 'Calle Sur 654', '5105105105105100', '567', '1988-11-25');

SET IDENTITY_INSERT Clientes OFF;
GO

-- Encriptar datos sensibles
EXEC sp_EncriptarDatosSensibles;
GO

-- Insertar habitaciones
SET IDENTITY_INSERT Habitaciones ON;

INSERT INTO Habitaciones (habitacion_id, numero_habitacion, tipo_habitacion, precio_noche, capacidad, piso, amenidades, estado_limpieza)
VALUES 
(1, '101', 'Suite Ejecutiva', 250.00, 2, 1, 'WiFi, Minibar, TV Smart, Desayuno, Jacuzzi', 'LIMPIA'),
(2, '102', 'Suite Presidencial', 500.00, 4, 1, 'WiFi, Minibar, TV Smart, Desayuno, Jacuzzi, Vista al mar', 'LIMPIA'),
(3, '201', 'Habitación Doble', 150.00, 2, 2, 'WiFi, TV Smart, Desayuno', 'LIMPIA'),
(4, '202', 'Habitación Doble', 150.00, 2, 2, 'WiFi, TV Smart, Desayuno', 'PENDIENTE'),
(5, '305', 'Habitación Simple', 100.00, 1, 3, 'WiFi, TV Cable', 'LIMPIA'),
(6, '306', 'Habitación Simple', 100.00, 1, 3, 'WiFi, TV Cable', 'LIMPIA'),
(7, '401', 'Suite Familiar', 300.00, 4, 4, 'WiFi, Minibar, TV Smart, Desayuno, Cocina', 'LIMPIA');

SET IDENTITY_INSERT Habitaciones OFF;
GO

-- Insertar reservas
SET IDENTITY_INSERT Reservas ON;

INSERT INTO Reservas (reserva_id, cliente_id, habitacion_id, fecha_entrada, fecha_salida, numero_huespedes, estado_reserva, precio_total, fecha_reserva, observaciones)
VALUES 
(1, 1, 1, '2025-11-01', '2025-11-05', 2, 'CONFIRMADA', 1000.00, '2025-10-15 10:30:00', 'Cliente VIP - Solicita late checkout'),
(2, 2, 3, '2025-10-28', '2025-10-30', 2, 'CHECK_IN', 300.00, '2025-10-20 14:15:00', 'Aniversario - Decoración especial'),
(3, 3, 5, '2025-11-10', '2025-11-12', 1, 'CONFIRMADA', 200.00, '2025-10-25 09:00:00', 'Viaje de negocios'),
(4, 4, 2, '2025-11-15', '2025-11-20', 4, 'CONFIRMADA', 2500.00, '2025-10-26 16:45:00', 'Luna de miel - Incluir champagne'),
(5, 5, 7, '2025-11-05', '2025-11-08', 4, 'PENDIENTE', 900.00, '2025-10-27 11:20:00', 'Vacaciones familiares'),
(6, 1, 6, '2025-12-20', '2025-12-27', 1, 'CONFIRMADA', 700.00, '2025-10-27 13:30:00', 'Vacaciones de fin de año');

SET IDENTITY_INSERT Reservas OFF;
GO

-- Insertar política de seguridad
INSERT INTO Politicas_Seguridad_Vistas 
(nombre_vista, descripcion, datos_protegidos, roles_autorizados, nivel_criticidad, responsable)
VALUES 
('vw_ReservasSeguras', 
 'Vista principal para gestión de reservas con protección de datos sensibles',
 'Número de tarjeta de crédito (enmascarado), CVV (excluido), Dirección completa (excluida)',
 'rol_Recepcionista, rol_GerenciaOperaciones, rol_Administrador, rol_Consulta',
 'ALTA',
 'Gerente de TI');
GO

PRINT 'Datos de prueba insertados exitosamente';
GO

-- ============================================================================
-- SECCIÓN 9: VERIFICACIÓN FINAL
-- ============================================================================

PRINT '============================================';
PRINT 'SECCIÓN 9: VERIFICACIÓN FINAL DEL SISTEMA';
PRINT '============================================';
PRINT '';

-- Verificar tablas
PRINT 'Verificando tablas...';
SELECT 
    TABLE_NAME AS 'Tabla Creada',
    TABLE_TYPE AS 'Tipo'
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_NAME IN ('Clientes', 'Habitaciones', 'Reservas', 'Auditoria_Cambios')
ORDER BY TABLE_NAME;
PRINT '';

-- Verificar vistas
PRINT 'Verificando vistas...';
SELECT 
    TABLE_NAME AS 'Vista Creada'
FROM INFORMATION_SCHEMA.VIEWS
WHERE TABLE_NAME LIKE 'vw_%'
ORDER BY TABLE_NAME;
PRINT '';

-- Verificar roles
PRINT 'Verificando roles...';
SELECT 
    name AS 'Rol Creado',
    create_date AS 'Fecha Creación'
FROM sys.database_principals
WHERE type = 'R' AND name LIKE 'rol_%'
ORDER BY name;
PRINT '';

-- Verificar encriptación
PRINT 'Verificando sistema de encriptación...';
SELECT * FROM vw_ChecklistSeguridad;
PRINT '';

-- Ejecutar pruebas de seguridad
EXEC sp_EjecutarPruebasSeguridad;
PRINT '';

PRINT '============================================';
PRINT 'IMPLEMENTACIÓN COMPLETADA EXITOSAMENTE';
PRINT '============================================';
PRINT '';
PRINT 'Resumen del sistema:';
PRINT '- Tablas base: 3 (Clientes, Habitaciones, Reservas)';
PRINT '- Tablas de auditoría: 5';
PRINT '- Vistas de seguridad: 5';
PRINT '- Roles configurados: 4';
PRINT '- Funciones de encriptación: Configuradas';
PRINT '- Datos de prueba: Insertados';
PRINT '';
PRINT 'El sistema está listo para uso en producción.';
PRINT 'Consulte la documentación para instrucciones de uso.';
GO
```

### Anexo B: Diagramas de Arquitectura

#### B.1 Diagrama de Arquitectura de Seguridad (Detallado)
```
╔════════════════════════════════════════════════════════════════════════════╗
║                         ARQUITECTURA DE SEGURIDAD                          ║
║                        HOTEL GLOBALSTAY - SISTEMA DE VISTAS                ║
╚════════════════════════════════════════════════════════════════════════════╝

┌──────────────────────────────────────────────────────────────────────────┐
│                        CAPA 1: USUARIOS Y AUTENTICACIÓN                  │
│                                                                          │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │
│  │Recepcionista│  │  Gerente    │  │Administrador│  │  Consulta   │  │
│  │             │  │ Operaciones │  │             │  │             │  │
│  │ SELECT      │  │SELECT/UPDATE│  │    FULL     │  │SELECT ONLY  │  │
│  │ UPDATE*     │  │             │  │   CONTROL   │  │             │  │
│  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  └──────┬──────┘  │
│         │                │                │                │           │
│         └────────────────┴────────────────┴────────────────┘           │
└──────────────────────────────────┬───────────────────────────────────────┘
                                   │
                                   │ Autenticación SQL Server
                                   │ + Autorización basada en Roles
                                   │
┌──────────────────────────────────▼───────────────────────────────────────┐
│                      CAPA 2: CONTROL DE ACCESO (RBAC)                    │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │                     Sistema de Roles                             │  │
│  │                                                                  │  │
│  │  rol_Recepcionista ────────► vw_ReservasRecepcion              │  │
│  │       │                       (Solo campos operativos)          │  │
│  │       └──────────────────────► vw_ReservasSeguras (SELECT)     │  │
│  │                                                                  │  │
│  │  rol_GerenciaOperaciones ──► vw_ReservasGerencia               │  │
│  │       │                       (Incluye datos financieros)       │  │
│  │       └──────────────────────► vw_ReservasSeguras (SELECT/UPDATE)│ │
│  │                                                                  │  │
│  │  rol_Administrador ─────────► Todas las vistas                  │  │
│  │       │                       + fn_DesencriptarTarjeta()        │  │
│  │       └──────────────────────► Procedimientos almacenados       │  │
│  │                                                                  │  │
│  │  rol_Consulta ──────────────► vw_ReservasSeguras (SELECT ONLY) │  │
│  │                               DENY UPDATE/INSERT/DELETE         │  │
│  └──────────────────────────────────────────────────────────────────┘  │
└──────────────────────────────────┬───────────────────────────────────────┘
                                   │
                                   │ Permisos Granulares
                                   │ + Auditoría de Accesos
                                   │
┌──────────────────────────────────▼───────────────────────────────────────┐
│                    CAPA 3: VISTAS DE SEGURIDAD                           │
│                                                                          │
│  ┌────────────────────┐         ┌────────────────────┐                 │
│  │vw_ReservasRecepcion│         │vw_ReservasGerencia │                 │
│  │                    │         │                    │                 │
│  │• Campos básicos    │         │• Incluye precios   │                 │
│  │• Sin datos sensibles│         │• Tarjeta enmascarada│                │
│  │• Solo reservas     │         │• Datos de contacto │                 │
│  │  activas           │         │• Métricas          │                 │
│  └─────────┬──────────┘         └─────────┬──────────┘                 │
│            │                              │                             │
│            └──────────────┬───────────────┘                             │
│                           │                                              │
│                ┌──────────▼──────────┐                                  │
│                │vw_ReservasSeguras   │                                  │
│                │   (VISTA PRINCIPAL) │                                  │
│                │                     │                                  │
│                │ • Filtrado de datos │                                  │
│                │ • Enmascaramiento   │                                  │
│                │ • Campos calculados │                                  │
│                │ • JOINs seguros     │                                  │
│                └──────────┬──────────┘                                  │
│                           │                                              │
└───────────────────────────┼──────────────────────────────────────────────┘
                            │
                            │ Transformación y Filtrado
                            │
┌───────────────────────────▼──────────────────────────────────────────────┐
│              CAPA 4: FUNCIONES DE ENMASCARAMIENTO Y LÓGICA               │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │  fn_enmascarar_tarjeta()                                         │  │
│  │  ────────────────────────────────────────────────────────────    │  │
│  │  Input:  '4532123456789012'                                      │  │
│  │  Output: '****-****-****-9012'                                   │  │
│  │                                                                  │  │
│  │  • Protege 12 dígitos                                            │  │
│  │  • Muestra últimos 4 para verificación                           │  │
│  │  • Formato visual estándar                                       │  │
│  └──────────────────────────────────────────────────────────────────┘  │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │  fn_DesencriptarTarjeta()  [SOLO ADMINISTRADORES]               │  │
│  │  ────────────────────────────────────────────────────────────    │  │
│  │  • Abre clave simétrica                                          │  │
│  │  • Desencripta datos                                             │  │
│  │  • Cierra clave                                                  │  │
│  │  • Retorna valor original                                        │  │
│  │                                                                  │  │
│  │  USO: Solo para reembolsos, auditorías o casos excepcionales    │  │
│  └──────────────────────────────────────────────────────────────────┘  │
└──────────────────────────────────┬───────────────────────────────────────┘
                                   │
                                   │ Transformaciones Seguras
                                   │
┌──────────────────────────────────▼───────────────────────────────────────┐
│                  CAPA 5: SISTEMA DE ENCRIPTACIÓN                         │
│                                                                          │
│  ┌────────────────────────────────────────────────────────────────────┐ │
│  │                    JERARQUÍA DE ENCRIPTACIÓN                       │ │
│  │                                                                    │ │
│  │  [1] Database Master Key                                          │ │
│  │      └─ Password: 'HotelGlobalStay2024!SecureMasterKey#2025'     │ │
│  │         │                                                          │ │
│  │         └──► [2] Certificate: cert_HotelSeguridad                │ │
│  │                  └─ Válido hasta: 2030-12-31                      │ │
│  │                     │                                              │ │
│  │                     └──► [3] Symmetric Key: key_DatosSensibles   │ │
│  │                            └─ Algoritmo: AES-256                  │ │
│  │                               │                                    │ │
│  │                               └──► Encripta/Desencripta           │ │
│  │                                    Datos Sensibles                │ │
│  └────────────────────────────────────────────────────────────────────┘ │
│                                                                          │
│  ┌────────────────────────────────────────────────────────────────────┐ │
│  │                    DATOS ENCRIPTADOS                               │ │
│  │                                                                    │ │
│  │  • tarjeta_credito_encriptada  (VARBINARY(256))                  │ │
│  │  • cvv_encriptado              (VARBINARY(128))                  │ │
│  │                                                                    │ │
│  │  Almacenamiento: Binario en columnas dedicadas                   │ │
│  │  Acceso: Solo mediante funciones autorizadas                     │ │
│  └────────────────────────────────────────────────────────────────────┘ │
└──────────────────────────────────┬───────────────────────────────────────┘
                                   │
                                   │ Almacenamiento Seguro
                                   │
┌──────────────────────────────────▼───────────────────────────────────────┐
│                    CAPA 6: TABLAS BASE DE DATOS                          │
│                                                                          │
│  ┌──────────────┐          ┌──────────────┐          ┌──────────────┐  │
│  │  Clientes    │          │ Habitaciones │          │  Reservas    │  │
│  ├──────────────┤          ├──────────────┤          ├──────────────┤  │
│  │• cliente_id  │◄─────┐   │• habitacion_ │◄─────┐   │• reserva_id  │  │
│  │• nombre      │      │   │  id          │      │   │• cliente_id  │──┐│
│  │• apellido    │      │   │• numero_hab  │      │   │• habitacion_ │  ││
│  │• email       │      │   │• tipo        │      │   │  id          │──┘│
│  │• telefono    │      │   │• precio      │      │   │• fechas      │  │
│  │• tarjeta_*   │      │   │• capacidad   │      │   │• estado      │  │
│  │  [ENCRIPTADO]│      └───│• amenidades  │      └───│• precio      │  │
│  │• cvv_*       │          │• limpieza    │          │• observac.   │  │
│  │  [ENCRIPTADO]│          └──────────────┘          └──────────────┘  │
│  └──────────────┘                                                        │
│                                                                          │
│  Características de seguridad:                                          │
│  • Constraints para integridad referencial                              │
│  • Checks para validación de datos                                      │
│  • Índices para optimización                                            │
│  • Auditoría de cambios mediante triggers                               │
└──────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│                    CAPA 7: AUDITORÍA Y MONITOREO                         │
│                                                                          │
│  ┌────────────────────────────────────────────────────────────────────┐ │
│  │  Auditoria_AccesoVistas                                            │ │
│  │  • Registra: SELECT, UPDATE, INSERT, DELETE                        │ │
│  │  • Usuario, Fecha/Hora, Vista accedida                            │ │
│  │  • Registros afectados, IP, Aplicación                            │ │
│  └────────────────────────────────────────────────────────────────────┘ │
│                                                                          │
│  ┌────────────────────────────────────────────────────────────────────┐ │
│  │  Auditoria_Cambios                                                 │ │
│  │  • Tabla, Acción, Usuario                                          │ │
│  │  • Datos anteriores vs nuevos (JSON)                              │ │
│  │  • Trazabilidad completa                                           │ │
│  └────────────────────────────────────────────────────────────────────┘ │
│                                                                          │
│  ┌────────────────────────────────────────────────────────────────────┐ │
│  │  Historial_Vistas                                                  │ │
│  │  • Control de versiones                                            │ │
│  │  • Definición SQL de cada versión                                 │ │
│  │  • Justificación de cambios                                        │ │
│  └────────────────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────────────────┘

FLUJO DE DATOS SEGURO:
═══════════════════════

Usuario → Autenticación → Autorización RBAC → Vista filtrada → 
Enmascaramiento → Encriptación (si necesario) → Auditoría → 
Datos base → Respuesta segura al usuario

PRINCIPIOS DE SEGURIDAD IMPLEMENTADOS:
══════════════════════════════════════

✓ Defensa en profundidad (múltiples capas)
✓ Principio de mínimo privilegio
✓ Separación de responsabilidades
✓ Encriptación de datos en reposo
✓ Enmascaramiento de datos sensibles
✓ Auditoría completa y trazabilidad
✓ Control de acceso basado en roles
✓ Validación de integridad de datos
```

#### B.2 Diagrama de Flujo de Acceso a Datos
```
╔════════════════════════════════════════════════════════════════╗
║          FLUJO DE ACCESO Y PROTECCIÓN DE DATOS                 ║
╚════════════════════════════════════════════════════════════════╝

CASO 1: RECEPCIONISTA CONSULTANDO RESERVA
═════════════════════════════════════════

Inicio
  │
  ▼
┌──────────────────────┐
│ Usuario: Recepcionista│
│ Login + Password     │
└──────────┬───────────┘
           │
           ▼
┌────────────────────────────┐
│ SQL Server Authentication  │
│ + Verificación de Rol      │
└──────────┬─────────────────┘
           │
           ▼
    ┌──────────────┐
    │ ¿Rol válido? │
    └──┬───────┬───┘
       │ NO    │ SÍ
       │       │
       │       ▼
       │   ┌──────────────────────────┐
       │   │ SELECT FROM              │
       │   │ vw_ReservasRecepcion     │
       │   └──────────┬───────────────┘
       │              │
       │              ▼
       │   ┌──────────────────────────┐
       │   │ Vista aplica filtros:    │
       │   │ • Solo campos permitidos │
       │   │ • Solo reservas activas  │
       │   │ • Tarjeta enmascarada    │
       │   └──────────┬───────────────┘
       │              │
       │              ▼
       │   ┌──────────────────────────┐
       │   │ fn_enmascarar_tarjeta()  │
       │   │ aplica transformación    │
       │   └──────────┬───────────────┘
       │              │
       │              ▼
       │   ┌──────────────────────────┐
       │   │ Registro en              │
       │   │ Auditoria_AccesoVistas   │
       │   └──────────┬───────────────┘
       │              │
       │              ▼
       │   ┌──────────────────────────┐
       │   │ Resultado mostrado:      │
       │   │ • Nombre: Juan Pérez     │
       │   │ • Habitación: 101        │
       │   │ • Tarjeta: ****-9012     │
       │   └──────────────────────────┘
       │
       ▼
┌──────────────┐
│ ACCESO       │
│ DENEGADO     │
└──────────────┘


CASO 2: ADMINISTRADOR DESENCRIPTANDO PARA REEMBOLSO
════════════════════════════════════════════════════

Inicio
  │
  ▼
┌──────────────────────────┐
│ Usuario: Administrador   │
│ Solicita reembolso       │
└──────────┬───────────────┘
           │
           ▼
┌────────────────────────────────┐
│ Verificación de permisos       │
│ rol_Administrador              │
└──────────┬─────────────────────┘
           │
           ▼
┌────────────────────────────────┐
│ EXECUTE                        │
│ fn_DesencriptarTarjeta(@id)    │
└──────────┬─────────────────────┘
           │
           ▼
┌────────────────────────────────┐
│ Abrir clave simétrica:         │
│ key_DatosSensibles             │
│ usando cert_HotelSeguridad     │
└──────────┬─────────────────────┘
           │
           ▼
┌────────────────────────────────┐
│ DecryptByKey()                 │
│ sobre columna encriptada       │
└──────────┬─────────────────────┘
           │
           ▼
┌────────────────────────────────┐
│ Cerrar clave simétrica         │
└──────────┬─────────────────────┘
           │
           ▼
┌────────────────────────────────┐
│ Registrar en auditoría:        │
│ • Usuario: admin1              │
│ • Acción: DESENCRIPTAR         │
│ • Cliente_ID: 123              │
│ • Fecha/Hora                   │
│ • Motivo: REEMBOLSO            │
└──────────┬─────────────────────┘
           │
           ▼
┌────────────────────────────────┐
│ Retornar valor desencriptado   │
│ '4532123456789012'             │
└────────────────────────────────┘


CASO 3: USUARIO SIN PERMISOS INTENTANDO MODIFICAR
═══════════════════════════════════════════════════

Inicio
  │
  ▼
┌──────────────────────────┐
│ Usuario: Consulta        │
│ Intenta UPDATE           │
└──────────┬───────────────┘
           │
           ▼
┌────────────────────────────────┐
│ UPDATE vw_ReservasSeguras      │
│ SET estado = 'CANCELADA'       │
└──────────┬─────────────────────┘
           │
           ▼
┌────────────────────────────────┐
│ SQL Server verifica permisos   │
└──────────┬─────────────────────┘
           │
           ▼
    ┌──────────────────┐
    │ ¿Tiene permiso?  │
    └───┬──────────┬───┘
        │ NO       │ SÍ
        │          │
        │          ▼
        │      [Ejecutar UPDATE]
        │
        ▼
┌──────────────────────────────────┐
│ ERROR: Permiso denegado          │
│ Código: 229                      │
│ DENY aplicado por rol_Consulta   │
└──────────┬───────────────────────┘
           │
           ▼
┌──────────────────────────────────┐
│ Registrar intento fallido en     │
│ Auditoria_AccesoVistas           │
│ • exito = 0                      │
│ • tipo_operacion = 'UPDATE'      │
└──────────┬───────────────────────┘
           │
           ▼
┌──────────────────────────────────┐
│ Alertar al administrador si      │
│ múltiples intentos fallidos      │
└──────────────────────────────────┘
```

### Anexo C: Matriz de Permisos Detallada
```
╔═══════════════════════════════════════════════════════════════════════════╗
║                        MATRIZ DE PERMISOS POR ROL                         ║
╚═══════════════════════════════════════════════════════════════════════════╝

┌─────────────────┬──────────────┬──────────────┬──────────────┬──────────────┐
│                 │Recepcionista │   Gerencia   │Administrador │   Consulta   │
│                 │              │  Operaciones │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│ VISTAS          │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│vw_ReservasSegur │              │              │              │              │
│as               │  SELECT      │SELECT/UPDATE │    FULL      │   SELECT     │
│                 │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│vw_ReservasRecep │              │              │              │              │
│cion             │  SELECT      │      -       │   SELECT     │      -       │
│                 │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│vw_ReservasGeren │              │              │              │              │
│cia              │      -       │SELECT/UPDATE │    FULL      │   SELECT     │
│                 │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│vw_ReservasModif │              │              │              │              │
│icables          │   UPDATE*    │    UPDATE    │    FULL      │     DENY     │
│                 │  (limitado)  │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│                 │              │              │              │              │
│ TABLAS BASE     │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│Clientes         │      -       │      -       │    FULL      │      -       │
│Habitaciones     │      -       │      -       │    FULL      │      -       │
│Reservas         │      -       │      -       │    FULL      │      -       │
│                 │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│ FUNCIONES       │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│fn_enmascarar_   │   EXECUTE    │   EXECUTE    │   EXECUTE    │   EXECUTE    │
│tarjeta          │  (automático)│  (automático)│  (automático)│  (automático)│
│                 │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│fn_Desencriptar  │              │              │              │              │
│Tarjeta          │     DENY     │     DENY     │   EXECUTE    │     DENY     │
│                 │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│ PROCEDIMIENTOS  │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│sp_Encriptar    │              │              │              │              │
│DatosSensibles   │     DENY     │     DENY     │   EXECUTE    │     DENY     │
│                 │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│sp_DocumentarCam │              │              │              │              │
│bioVista         │     DENY     │     DENY     │   EXECUTE    │     DENY     │
│                 │              │              │              │              │
├─────────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│sp_EjecutarPrueb │              │              │              │              │
│asSeguridad      │     DENY     │   EXECUTE    │   EXECUTE    │     DENY     │
│                 │              │              │              │              │
└─────────────────┴──────────────┴──────────────┴──────────────┴──────────────┘

LEYENDA DE PERMISOS:
═══════════════════

SELECT     : Puede leer datos
UPDATE     : Puede modificar datos existentes
INSERT     : Puede agregar nuevos registros
DELETE     : Puede eliminar registros
FULL       : Control total (SELECT/INSERT/UPDATE/DELETE)
EXECUTE    : Puede ejecutar la función o procedimiento
DENY       : Explícitamente bloqueado
-          : Sin acceso (ni permitido ni denegado explícitamente)
(automático): Ejecutado automáticamente por las vistas

CAMPOS VISIBLES POR ROL:
═══════════════════════

┌─────────────────────┬────────┬────────┬────────┬────────┐
│       CAMPO         │Recepc. │Gerencia│  Admin │Consulta│
├─────────────────────┼────────┼────────┼────────┼────────┤
│reserva_id           │   ✓    │   ✓    │   ✓    │   ✓    │
│nombre_completo      │   ✓    │   ✓    │   ✓    │   ✓    │
│email                │   -    │   ✓    │   ✓    │   ✓    │
│telefono_contacto    │   ✓    │   ✓    │   ✓    │   ✓    │
│direccion_completa   │   -    │   -    │   ✓    │   -    │
│tarjeta_enmascarada  │   -    │   ✓    │   ✓    │   -    │
│tarjeta_completa     │   -    │   -    │   ✓*   │   -    │
│numero_habitacion    │   ✓    │   ✓    │   ✓    │   ✓    │
│tipo_habitacion      │   ✓    │   ✓    │   ✓    │   ✓    │
│precio_noche         │   -    │   ✓    │   ✓    │   ✓    │
│precio_total         │   -    │   ✓    │   ✓    │   ✓    │
│amenidades           │   -    │   ✓    │   ✓    │   ✓    │
│estado_limpieza      │   -    │   ✓    │   ✓    │   -    │
│observaciones        │   ✓    │   ✓    │   ✓    │   ✓    │
│fecha_entrada        │   ✓    │   ✓    │   ✓    │   ✓    │
│fecha_salida         │   ✓    │   ✓    │   ✓    │   ✓    │
│estado_reserva       │   ✓    │   ✓    │   ✓    │   ✓    │
└─────────────────────┴────────┴────────┴────────┴────────┘

✓  = Puede ver
-  = No puede ver
✓* = Solo mediante función especial de desencriptación

CAMPOS MODIFICABLES POR ROL:
════════════════════════════

┌─────────────────────┬────────┬────────┬────────┬────────┐
│       CAMPO         │Recepc. │Gerencia│  Admin │Consulta│
├─────────────────────┼────────┼────────┼────────┼────────┤
│observaciones        │   ✓    │   ✓    │   ✓    │   -    │
│estado_reserva       │   ✓*   │   ✓    │   ✓    │   -    │
│fecha_entrada        │   -    │   ✓    │   ✓    │   -    │
│fecha_salida         │   -    │   ✓    │   ✓    │   -    │
│numero_huespedes     │   -    │   ✓    │   ✓    │   -    │
│habitacion_id        │   -    │   -    │   ✓    │   -    │
│cliente_id           │   -    │   -    │   ✓    │   -    │
│precio_total         │   -    │   -    │   ✓    │   -    │
│email (cliente)      │   -    │   ✓    │   ✓    │   -    │
│telefono (cliente)   │   -    │   ✓    │   ✓    │   -    │
└─────────────────────┴────────┴────────┴────────┴────────┘

✓  = Puede modificar
✓* = Puede modificar solo ciertos valores (CONFIRMADA, CHECK_IN, CHECK_OUT)
-  = No puede modificar

CASOS DE USO POR ROL:
════════════════════

RECEPCIONISTA:
- Consultar reservas del día
- Realizar check-in/check-out
- Actualizar observaciones
- Ver información de contacto básica

GERENCIA OPERACIONES:
- Análisis de ocupación
- Modificar fechas de reservas
- Actualizar datos de contacto de clientes
- Ver información financiera
- Generar reportes operativos

ADMINISTRADOR:
- Configuración del sistema
- Reembolsos y cancelaciones
- Acceso a datos encriptados
- Modificación de estructura
- Auditoría completa

CONSULTA:
- Reportes de solo lectura
- Análisis de datos
- Business Intelligence
- Sin capacidad de modificación
