-- Desarrollado por Dev Jean Carlos Sandoval Rosas – Codexuspro
-- Esquema de base de datos: Sistema de Gestión de Inventario

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS inventario_codexuspro
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE inventario_codexuspro;

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo      VARCHAR(50)  NOT NULL UNIQUE,
    nombre      VARCHAR(255) NOT NULL,
    descripcion TEXT         NULL,
    categoria   VARCHAR(100) NULL,
    precio      DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    stock       INT UNSIGNED NOT NULL DEFAULT 0,
    stock_minimo INT UNSIGNED NOT NULL DEFAULT 0,
    activo      TINYINT(1)   NOT NULL DEFAULT 1,
    creado_en   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado_en DATETIME  NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_codigo (codigo),
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo (opcional)
INSERT INTO productos (codigo, nombre, descripcion, categoria, precio, stock, stock_minimo) VALUES
('ART-001', 'Laptop Dell Inspiron 15', 'Laptop 15 pulgadas, 8GB RAM', 'Computación', 12500.00, 10, 2),
('ART-002', 'Teclado mecánico RGB', 'Teclado mecánico switches azules', 'Periféricos', 850.00, 25, 5),
('ART-003', 'Mouse inalámbrico', 'Mouse ergonómico 1600 DPI', 'Periféricos', 320.00, 50, 10);

SET FOREIGN_KEY_CHECKS = 1;
