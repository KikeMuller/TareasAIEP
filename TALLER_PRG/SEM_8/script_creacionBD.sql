-- =============================================
-- SCRIPT DE CREACIÓN DE BASE DE DATOS
-- Sistema de Gestión de Clientes - Empresa Logística
-- =============================================

-- Crear base de datos
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'LogisticaDB')
BEGIN
    CREATE DATABASE LogisticaDB;
END
GO

USE LogisticaDB;
GO

-- Eliminar tablas si existen (para recreación)
IF OBJECT_ID('dbo.Direcciones', 'U') IS NOT NULL
    DROP TABLE dbo.Direcciones;
GO

IF OBJECT_ID('dbo.Clientes', 'U') IS NOT NULL
    DROP TABLE dbo.Clientes;
GO

-- =============================================
-- TABLA: Clientes
-- =============================================
CREATE TABLE Clientes (
    ClienteID INT PRIMARY KEY IDENTITY(1,1),
    Nombre NVARCHAR(100) NOT NULL,
    Telefono NVARCHAR(20),
    Email NVARCHAR(100) UNIQUE NOT NULL,
    FechaRegistro DATETIME DEFAULT GETDATE(),
    CONSTRAINT CK_Email CHECK (Email LIKE '%@%.%')
);
GO

-- =============================================
-- TABLA: Direcciones
-- =============================================
CREATE TABLE Direcciones (
    DireccionID INT PRIMARY KEY IDENTITY(1,1),
    ClienteID INT NOT NULL,
    Calle NVARCHAR(200) NOT NULL,
    Ciudad NVARCHAR(100) NOT NULL,
    Pais NVARCHAR(100) NOT NULL,
    CodigoPostal NVARCHAR(20),
    EsPrincipal BIT DEFAULT 0,
    CONSTRAINT FK_Direcciones_Clientes 
        FOREIGN KEY (ClienteID) 
        REFERENCES Clientes(ClienteID) 
        ON DELETE CASCADE
);
GO

-- =============================================
-- ÍNDICES PARA MEJORAR RENDIMIENTO
-- =============================================
CREATE NONCLUSTERED INDEX IX_Clientes_Email 
    ON Clientes(Email);
GO

CREATE NONCLUSTERED INDEX IX_Direcciones_ClienteID 
    ON Direcciones(ClienteID);
GO

-- =============================================
-- DATOS DE EJEMPLO
-- =============================================
INSERT INTO Clientes (Nombre, Telefono, Email) VALUES 
('Juan Pérez', '+56912345678', 'juan.perez@email.com'),
('María González', '+56987654321', 'maria.gonzalez@email.com'),
('Carlos Rodríguez', '+56923456789', 'carlos.rodriguez@email.com'),
('Ana Martínez', '+56934567890', 'ana.martinez@email.com'),
('Luis Fernández', '+56945678901', 'luis.fernandez@email.com');
GO

INSERT INTO Direcciones (ClienteID, Calle, Ciudad, Pais, CodigoPostal, EsPrincipal) VALUES
(1, 'Av. Libertador 1234', 'Santiago', 'Chile', '8320000', 1),
(1, 'Calle Agustinas 567', 'Santiago', 'Chile', '8340000', 0),
(2, 'Paseo Bulnes 890', 'Santiago', 'Chile', '8330000', 1),
(3, 'Av. Providencia 2345', 'Santiago', 'Chile', '7500000', 1),
(4, 'Los Leones 678', 'Santiago', 'Chile', '7510000', 1),
(5, 'Santa Rosa 1456', 'Santiago', 'Chile', '8350000', 1);
GO

-- =============================================
-- PROCEDIMIENTOS ALMACENADOS (OPCIONAL)
-- =============================================

-- Procedimiento para obtener clientes con sus direcciones
CREATE PROCEDURE sp_ObtenerClientesConDirecciones
AS
BEGIN
    SELECT 
        c.ClienteID,
        c.Nombre,
        c.Telefono,
        c.Email,
        c.FechaRegistro,
        d.DireccionID,
        d.Calle,
        d.Ciudad,
        d.Pais,
        d.CodigoPostal,
        d.EsPrincipal
    FROM Clientes c
    LEFT JOIN Direcciones d ON c.ClienteID = d.ClienteID
    ORDER BY c.Nombre, d.EsPrincipal DESC;
END
GO

-- Procedimiento para obtener direcciones de un cliente específico
CREATE PROCEDURE sp_ObtenerDireccionesCliente
    @ClienteID INT
AS
BEGIN
    SELECT 
        DireccionID,
        ClienteID,
        Calle,
        Ciudad,
        Pais,
        CodigoPostal,
        EsPrincipal
    FROM Direcciones
    WHERE ClienteID = @ClienteID
    ORDER BY EsPrincipal DESC, DireccionID;
END
GO

-- =============================================
-- CONSULTAS DE VERIFICACIÓN
-- =============================================
SELECT 'Tabla Clientes creada correctamente' AS Mensaje;
SELECT COUNT(*) AS TotalClientes FROM Clientes;

SELECT 'Tabla Direcciones creada correctamente' AS Mensaje;
SELECT COUNT(*) AS TotalDirecciones FROM Direcciones;
GO
