-- ============================================================================
-- EJERCICIO: VISTAS CON ENCRIPTACIÓN DE DATOS SENSIBLES
-- Base de Datos: SQL Server
-- Estudiante: [Tu Nombre]
-- ============================================================================

-- PASO 0: Preparación del entorno
-- ============================================================================
USE master;
GO

-- Crear base de datos para el ejercicio
IF EXISTS (SELECT * FROM sys.databases WHERE name = 'EmpresaGlobal')
    DROP DATABASE EmpresaGlobal;
GO

CREATE DATABASE EmpresaGlobal;
GO

USE EmpresaGlobal;
GO

-- Crear tabla de Clientes
CREATE TABLE Clientes (
    ClienteID INT PRIMARY KEY IDENTITY(1,1),
    Nombre NVARCHAR(50) NOT NULL,
    Apellido NVARCHAR(50) NOT NULL,
    Correo NVARCHAR(100) NOT NULL,
    FechaNacimiento DATE
);
GO

-- Insertar datos de ejemplo
INSERT INTO Clientes (Nombre, Apellido, Correo, FechaNacimiento) VALUES
('Carlos', 'Pérez', 'carlos.perez@email.com', '1985-03-15'),
('María', 'González', 'maria.gonzalez@email.com', '1990-07-22'),
('Luis', 'Rodríguez', 'luis.rodriguez@email.com', '1988-11-30'),
('Ana', 'Martínez', 'ana.martinez@email.com', '1992-05-18'),
('Pedro', 'López', 'pedro.lopez@email.com', '1987-09-10');
GO

-- Verificar datos insertados
SELECT * FROM Clientes;
GO

-- ============================================================================
-- PASO 1: CREAR CLAVE MAESTRA Y CLAVE SIMÉTRICA
-- ============================================================================

-- Crear clave maestra de base de datos (DMK - Database Master Key)
-- Esta es necesaria para proteger las claves simétricas
CREATE MASTER KEY ENCRYPTION BY PASSWORD = 'MasterKey2024!Segura';
GO

-- Crear clave simétrica para encriptar correos
CREATE SYMMETRIC KEY ClaveCorreo
WITH ALGORITHM = AES_256
ENCRYPTION BY PASSWORD = 'MiContraseñaSegura!2024';
GO

-- Verificar que la clave fue creada
SELECT name, algorithm_desc, key_length 
FROM sys.symmetric_keys 
WHERE name = 'ClaveCorreo';
GO

-- ============================================================================
-- PASO 2: CREAR VISTA CON CORREO ENCRIPTADO
-- ============================================================================

CREATE VIEW VistaClientesEncrip AS
SELECT 
    ClienteID,
    Nombre,
    Apellido,
    EncryptByKey(Key_GUID('ClaveCorreo'), Correo) AS CorreoEncriptado
FROM Clientes;
GO

-- ============================================================================
-- PASO 3: CONSULTAR LA VISTA (Datos Encriptados)
-- ============================================================================

-- Abrir la clave simétrica para usar encriptación
OPEN SYMMETRIC KEY ClaveCorreo 
DECRYPTION BY PASSWORD = 'MiContraseñaSegura!2024';

-- Consultar vista con datos encriptados
SELECT * FROM VistaClientesEncrip;

-- BONUS: Desencriptar para visualizar los correos originales
SELECT 
    ClienteID,
    Nombre,
    Apellido,
    CorreoEncriptado,
    CONVERT(NVARCHAR(100), DecryptByKey(CorreoEncriptado)) AS CorreoDesencriptado
FROM VistaClientesEncrip;

-- Cerrar la clave simétrica
CLOSE SYMMETRIC KEY ClaveCorreo;
GO

-- ============================================================================
-- PASO 4: ACTUALIZAR DATOS A TRAVÉS DE LA VISTA
-- ============================================================================

-- Actualizar el nombre de un cliente
UPDATE VistaClientesEncrip 
SET Nombre = 'Juan' 
WHERE Apellido = 'Pérez';
GO

-- Verificar la actualización
SELECT * FROM Clientes WHERE Apellido = 'Pérez';
GO

-- ============================================================================
-- PASO 5: MODIFICAR LA VISTA (Agregar campo FechaNacimiento)
-- ============================================================================

ALTER VIEW VistaClientesEncrip AS
SELECT 
    ClienteID,
    Nombre,
    Apellido,
    EncryptByKey(Key_GUID('ClaveCorreo'), Correo) AS CorreoEncriptado,
    FechaNacimiento
FROM Clientes;
GO

-- ============================================================================
-- PASO 6: CONSULTAR LA VISTA MODIFICADA
-- ============================================================================

-- Abrir la clave simétrica
OPEN SYMMETRIC KEY ClaveCorreo 
DECRYPTION BY PASSWORD = 'MiContraseñaSegura!2024';

-- Consultar vista modificada con nuevo campo
SELECT * FROM VistaClientesEncrip;

-- Vista completa con desencriptación
SELECT 
    ClienteID,
    Nombre,
    Apellido,
    CorreoEncriptado,
    CONVERT(NVARCHAR(100), DecryptByKey(CorreoEncriptado)) AS CorreoDesencriptado,
    FechaNacimiento,
    DATEDIFF(YEAR, FechaNacimiento, GETDATE()) AS Edad
FROM VistaClientesEncrip;

-- Cerrar la clave simétrica
CLOSE SYMMETRIC KEY ClaveCorreo;
GO

-- ============================================================================
-- CONSULTAS ADICIONALES PARA DEMOSTRACIÓN
-- ============================================================================

-- Mostrar estructura de la vista
EXEC sp_helptext 'VistaClientesEncrip';
GO

-- Listar todas las vistas de la base de datos
SELECT TABLE_NAME 
FROM INFORMATION_SCHEMA.VIEWS 
WHERE TABLE_SCHEMA = 'dbo';
GO

-- ============================================================================
-- LIMPIEZA (Opcional - Ejecutar al finalizar)
-- ============================================================================
/*
USE master;
GO
DROP DATABASE EmpresaGlobal;
GO
*/
