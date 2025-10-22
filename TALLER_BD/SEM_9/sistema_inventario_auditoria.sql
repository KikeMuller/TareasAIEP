-- =============================================
-- SISTEMA DE GESTIÓN DE INVENTARIO CON AUDITORÍA
-- Incluye: Tablas, Procedimientos Almacenados y Triggers
-- =============================================

USE master;
GO

-- Crear base de datos si no existe
IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = 'InventarioDB')
BEGIN
    CREATE DATABASE InventarioDB;
END
GO

USE InventarioDB;
GO

-- =============================================
-- PASO 1: CREACIÓN DE TABLAS
-- =============================================

-- Eliminar tablas si existen (para recrear el esquema)
IF OBJECT_ID('AuditoriaInventario', 'U') IS NOT NULL
    DROP TABLE AuditoriaInventario;
GO

IF OBJECT_ID('Productos', 'U') IS NOT NULL
    DROP TABLE Productos;
GO

-- Tabla principal de Productos
CREATE TABLE Productos (
    IdProducto INT PRIMARY KEY IDENTITY(1,1),
    Nombre NVARCHAR(100) NOT NULL,
    Stock INT NOT NULL CHECK (Stock >= 0),
    Precio DECIMAL(10,2) NOT NULL CHECK (Precio >= 0),
    FechaCreacion DATETIME DEFAULT GETDATE()
);
GO

-- Tabla de Auditoría
CREATE TABLE AuditoriaInventario (
    IdAuditoria INT PRIMARY KEY IDENTITY(1,1),
    IdProducto INT NOT NULL,
    Accion NVARCHAR(10) NOT NULL,
    Fecha DATETIME DEFAULT GETDATE(),
    Usuario NVARCHAR(100) DEFAULT SYSTEM_USER
);
GO

PRINT '✓ Tablas creadas exitosamente';
GO

-- =============================================
-- PASO 2: PROCEDIMIENTO ALMACENADO - ALTA DE PRODUCTO
-- =============================================

IF OBJECT_ID('sp_AltaProducto', 'P') IS NOT NULL
    DROP PROCEDURE sp_AltaProducto;
GO

CREATE PROCEDURE sp_AltaProducto
    @Nombre NVARCHAR(100),
    @Stock INT,
    @Precio DECIMAL(10,2)
AS
BEGIN
    SET NOCOUNT ON;
    
    BEGIN TRY
        -- Validaciones adicionales
        IF @Nombre IS NULL OR LTRIM(RTRIM(@Nombre)) = ''
        BEGIN
            PRINT 'Error: El nombre del producto no puede estar vacío.';
            RETURN;
        END
        
        IF @Stock < 0
        BEGIN
            PRINT 'Error: El stock no puede ser negativo.';
            RETURN;
        END
        
        IF @Precio <= 0
        BEGIN
            PRINT 'Error: El precio debe ser mayor a 0.';
            RETURN;
        END
        
        -- Insertar el producto
        INSERT INTO Productos (Nombre, Stock, Precio)
        VALUES (@Nombre, @Stock, @Precio);
        
        PRINT 'Producto insertado exitosamente. ID: ' + CAST(SCOPE_IDENTITY() AS NVARCHAR(10));
        
    END TRY
    BEGIN CATCH
        PRINT 'Error al insertar el producto: ' + ERROR_MESSAGE();
        PRINT 'Error Número: ' + CAST(ERROR_NUMBER() AS NVARCHAR(10));
        PRINT 'Error Línea: ' + CAST(ERROR_LINE() AS NVARCHAR(10));
    END CATCH
END;
GO

PRINT '✓ Procedimiento sp_AltaProducto creado exitosamente';
GO

-- =============================================
-- PASO 3: TRIGGER - AUDITORÍA DESPUÉS DE INSERT
-- =============================================

IF OBJECT_ID('trg_Auditoria_AltaProducto', 'TR') IS NOT NULL
    DROP TRIGGER trg_Auditoria_AltaProducto;
GO

CREATE TRIGGER trg_Auditoria_AltaProducto
ON Productos
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    
    BEGIN TRY
        INSERT INTO AuditoriaInventario (IdProducto, Accion)
        SELECT IdProducto, 'INSERT'
        FROM inserted;
        
        PRINT 'Auditoría de INSERT registrada para ' + 
              CAST((SELECT COUNT(*) FROM inserted) AS NVARCHAR(10)) + ' producto(s)';
    END TRY
    BEGIN CATCH
        PRINT 'Error en trigger de auditoría INSERT: ' + ERROR_MESSAGE();
    END CATCH
END;
GO

PRINT '✓ Trigger trg_Auditoria_AltaProducto creado exitosamente';
GO

-- =============================================
-- PASO 4: TRIGGER - AUDITORÍA DESPUÉS DE DELETE
-- =============================================

IF OBJECT_ID('trg_Auditoria_BajaProducto', 'TR') IS NOT NULL
    DROP TRIGGER trg_Auditoria_BajaProducto;
GO

CREATE TRIGGER trg_Auditoria_BajaProducto
ON Productos
AFTER DELETE
AS
BEGIN
    SET NOCOUNT ON;
    
    BEGIN TRY
        INSERT INTO AuditoriaInventario (IdProducto, Accion)
        SELECT IdProducto, 'DELETE'
        FROM deleted;
        
        PRINT 'Auditoría de DELETE registrada para ' + 
              CAST((SELECT COUNT(*) FROM deleted) AS NVARCHAR(10)) + ' producto(s)';
    END TRY
    BEGIN CATCH
        PRINT 'Error en trigger de auditoría DELETE: ' + ERROR_MESSAGE();
    END CATCH
END;
GO

PRINT '✓ Trigger trg_Auditoria_BajaProducto creado exitosamente';
GO

-- =============================================
-- PASO 5: PROCEDIMIENTO ALMACENADO - BAJA DE PRODUCTO
-- =============================================

IF OBJECT_ID('sp_BajaProducto', 'P') IS NOT NULL
    DROP PROCEDURE sp_BajaProducto;
GO

CREATE PROCEDURE sp_BajaProducto
    @IdProducto INT
AS
BEGIN
    SET NOCOUNT ON;
    
    BEGIN TRY
        -- Verificar si el producto existe
        IF EXISTS (SELECT 1 FROM Productos WHERE IdProducto = @IdProducto)
        BEGIN
            -- Mostrar información del producto antes de eliminarlo
            DECLARE @NombreProducto NVARCHAR(100);
            SELECT @NombreProducto = Nombre FROM Productos WHERE IdProducto = @IdProducto;
            
            -- Eliminar el producto
            DELETE FROM Productos WHERE IdProducto = @IdProducto;
            
            PRINT 'Producto eliminado exitosamente: ' + @NombreProducto + ' (ID: ' + CAST(@IdProducto AS NVARCHAR(10)) + ')';
        END
        ELSE
        BEGIN
            PRINT 'Error: Producto no existe. ID solicitado: ' + CAST(@IdProducto AS NVARCHAR(10));
        END
    END TRY
    BEGIN CATCH
        PRINT 'Error al eliminar el producto: ' + ERROR_MESSAGE();
        PRINT 'Error Número: ' + CAST(ERROR_NUMBER() AS NVARCHAR(10));
        PRINT 'Error Línea: ' + CAST(ERROR_LINE() AS NVARCHAR(10));
    END CATCH
END;
GO

PRINT '✓ Procedimiento sp_BajaProducto creado exitosamente';
GO

-- =============================================
-- PASO 6: SCRIPTS DE VERIFICACIÓN Y PRUEBAS
-- =============================================

PRINT '';
PRINT '=============================================';
PRINT 'INICIANDO PRUEBAS DEL SISTEMA';
PRINT '=============================================';
GO

-- Prueba 1: Insertar productos de prueba
PRINT '';
PRINT '--- PRUEBA 1: Insertar productos ---';
EXEC sp_AltaProducto @Nombre = 'Laptop Dell XPS 15', @Stock = 10, @Precio = 1299.99;
EXEC sp_AltaProducto @Nombre = 'Mouse Logitech MX Master', @Stock = 50, @Precio = 79.99;
EXEC sp_AltaProducto @Nombre = 'Teclado Mecánico Corsair', @Stock = 25, @Precio = 149.99;
EXEC sp_AltaProducto @Nombre = 'Monitor Samsung 27"', @Stock = 15, @Precio = 349.99;
EXEC sp_AltaProducto @Nombre = 'Webcam Logitech C920', @Stock = 30, @Precio = 89.99;
GO

-- Prueba 2: Verificar productos insertados
PRINT '';
PRINT '--- PRUEBA 2: Productos en inventario ---';
SELECT * FROM Productos;
GO

-- Prueba 3: Verificar auditoría de inserciones
PRINT '';
PRINT '--- PRUEBA 3: Auditoría de inserciones ---';
SELECT 
    a.IdAuditoria,
    a.IdProducto,
    p.Nombre,
    a.Accion,
    a.Fecha,
    a.Usuario
FROM AuditoriaInventario a
LEFT JOIN Productos p ON a.IdProducto = p.IdProducto
WHERE a.Accion = 'INSERT'
ORDER BY a.Fecha DESC;
GO

-- Prueba 4: Eliminar un producto válido
PRINT '';
PRINT '--- PRUEBA 4: Eliminar producto ID 2 ---';
EXEC sp_BajaProducto @IdProducto = 2;
GO

-- Prueba 5: Intentar eliminar un producto inexistente
PRINT '';
PRINT '--- PRUEBA 5: Intentar eliminar producto inexistente (ID 999) ---';
EXEC sp_BajaProducto @IdProducto = 999;
GO

-- Prueba 6: Verificar productos restantes
PRINT '';
PRINT '--- PRUEBA 6: Productos restantes en inventario ---';
SELECT * FROM Productos;
GO

-- Prueba 7: Verificar auditoría completa
PRINT '';
PRINT '--- PRUEBA 7: Auditoría completa (INSERT y DELETE) ---';
SELECT 
    a.IdAuditoria,
    a.IdProducto,
    CASE 
        WHEN p.Nombre IS NOT NULL THEN p.Nombre
        ELSE '(Producto eliminado)'
    END AS Nombre,
    a.Accion,
    a.Fecha,
    a.Usuario
FROM AuditoriaInventario a
LEFT JOIN Productos p ON a.IdProducto = p.IdProducto
ORDER BY a.Fecha DESC;
GO

-- Prueba 8: Validaciones de negocio
PRINT '';
PRINT '--- PRUEBA 8: Validaciones de negocio ---';
PRINT 'Intentar insertar producto con nombre vacío:';
EXEC sp_AltaProducto @Nombre = '', @Stock = 10, @Precio = 100;

PRINT 'Intentar insertar producto con stock negativo:';
EXEC sp_AltaProducto @Nombre = 'Producto Test', @Stock = -5, @Precio = 100;

PRINT 'Intentar insertar producto con precio cero:';
EXEC sp_AltaProducto @Nombre = 'Producto Test', @Stock = 10, @Precio = 0;
GO

-- =============================================
-- COMANDOS DE VERIFICACIÓN DEL SISTEMA
-- =============================================

PRINT '';
PRINT '=============================================';
PRINT 'COMANDOS DE VERIFICACIÓN DEL SISTEMA';
PRINT '=============================================';
PRINT '';
PRINT '-- Ver triggers de la tabla Productos:';
PRINT 'EXEC sp_helptrigger ''Productos'';';
PRINT '';
PRINT '-- Ver definición del procedimiento sp_AltaProducto:';
PRINT 'EXEC sp_helptext ''sp_AltaProducto'';';
PRINT '';
PRINT '-- Ver definición del procedimiento sp_BajaProducto:';
PRINT 'EXEC sp_helptext ''sp_BajaProducto'';';
PRINT '';
PRINT '-- Ver definición del trigger de INSERT:';
PRINT 'EXEC sp_helptext ''trg_Auditoria_AltaProducto'';';
PRINT '';
PRINT '-- Ver definición del trigger de DELETE:';
PRINT 'EXEC sp_helptext ''trg_Auditoria_BajaProducto'';';
GO

-- Ejecutar comandos de verificación
PRINT '';
PRINT '--- Triggers de la tabla Productos ---';
EXEC sp_helptrigger 'Productos';
GO

-- =============================================
-- PROCEDIMIENTOS ADICIONALES ÚTILES
-- =============================================

-- Procedimiento para consultar auditoría por producto
IF OBJECT_ID('sp_ConsultarAuditoria', 'P') IS NOT NULL
    DROP PROCEDURE sp_ConsultarAuditoria;
GO

CREATE PROCEDURE sp_ConsultarAuditoria
    @IdProducto INT = NULL
AS
BEGIN
    SET NOCOUNT ON;
    
    IF @IdProducto IS NULL
    BEGIN
        -- Mostrar toda la auditoría
        SELECT 
            a.IdAuditoria,
            a.IdProducto,
            ISNULL(p.Nombre, '(Producto eliminado)') AS Nombre,
            a.Accion,
            a.Fecha,
            a.Usuario
        FROM AuditoriaInventario a
        LEFT JOIN Productos p ON a.IdProducto = p.IdProducto
        ORDER BY a.Fecha DESC;
    END
    ELSE
    BEGIN
        -- Mostrar auditoría de un producto específico
        SELECT 
            a.IdAuditoria,
            a.IdProducto,
            ISNULL(p.Nombre, '(Producto eliminado)') AS Nombre,
            a.Accion,
            a.Fecha,
            a.Usuario
        FROM AuditoriaInventario a
        LEFT JOIN Productos p ON a.IdProducto = p.IdProducto
        WHERE a.IdProducto = @IdProducto
        ORDER BY a.Fecha DESC;
    END
END;
GO

PRINT '✓ Procedimiento sp_ConsultarAuditoria creado exitosamente';
GO

-- Procedimiento para actualizar stock
IF OBJECT_ID('sp_ActualizarStock', 'P') IS NOT NULL
    DROP PROCEDURE sp_ActualizarStock;
GO

CREATE PROCEDURE sp_ActualizarStock
    @IdProducto INT,
    @NuevoStock INT
AS
BEGIN
    SET NOCOUNT ON;
    
    BEGIN TRY
        IF NOT EXISTS (SELECT 1 FROM Productos WHERE IdProducto = @IdProducto)
        BEGIN
            PRINT 'Error: Producto no existe.';
            RETURN;
        END
        
        IF @NuevoStock < 0
        BEGIN
            PRINT 'Error: El stock no puede ser negativo.';
            RETURN;
        END
        
        UPDATE Productos
        SET Stock = @NuevoStock
        WHERE IdProducto = @IdProducto;
        
        PRINT 'Stock actualizado exitosamente para el producto ID: ' + CAST(@IdProducto AS NVARCHAR(10));
    END TRY
    BEGIN CATCH
        PRINT 'Error al actualizar stock: ' + ERROR_MESSAGE();
    END CATCH
END;
GO

PRINT '✓ Procedimiento sp_ActualizarStock creado exitosamente';
GO

-- =============================================
-- RESUMEN FINAL
-- =============================================

PRINT '';
PRINT '=============================================';
PRINT '✓✓✓ SISTEMA COMPLETAMENTE IMPLEMENTADO ✓✓✓';
PRINT '=============================================';
PRINT '';
PRINT 'Objetos creados:';
PRINT '  • Tablas: Productos, AuditoriaInventario';
PRINT '  • Procedimientos: sp_AltaProducto, sp_BajaProducto, sp_ConsultarAuditoria, sp_ActualizarStock';
PRINT '  • Triggers: trg_Auditoria_AltaProducto, trg_Auditoria_BajaProducto';
PRINT '';
PRINT 'El sistema está listo para usar.';
PRINT '';
GO
