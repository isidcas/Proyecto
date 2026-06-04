-- Crear base de datos
CREATE DATABASE conversor_divisas
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE conversor_divisas;


-- =====================================
-- TABLA USUARIOS
-- =====================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =====================================
-- TABLA HISTORIAL DE CONVERSIONES
-- =====================================

CREATE TABLE historial (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    moneda_origen VARCHAR(10) NOT NULL,
    moneda_destino VARCHAR(10) NOT NULL,
    cantidad DECIMAL(12,2) NOT NULL,
    resultado DECIMAL(12,2) NOT NULL,
    tasa_cambio DECIMAL(12,6) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_historial_usuario
    FOREIGN KEY (usuario_id)
    REFERENCES usuarios(id)
    ON DELETE CASCADE
);


-- =====================================
-- TABLA FAVORITOS (DIVISAS GUARDADAS)
-- =====================================

CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    moneda VARCHAR(10) NOT NULL,
    fecha_guardado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_favoritos_usuario
    FOREIGN KEY (usuario_id)
    REFERENCES usuarios(id)
    ON DELETE CASCADE
);


-- =====================================
-- TABLA CONTACTO
-- =====================================

CREATE TABLE contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    email VARCHAR(150) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_contacto_usuario
    FOREIGN KEY (usuario_id)
    REFERENCES usuarios(id)
    ON DELETE SET NULL
);


-- =====================================
-- TABLA DIVISAS DISPONIBLES
-- (opcional pero recomendable)
-- =====================================

CREATE TABLE divisas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    simbolo VARCHAR(10),
    activa BOOLEAN DEFAULT TRUE
);


-- =====================================
-- DATOS INICIALES DIVISAS
-- =====================================

INSERT INTO divisas (codigo, nombre, simbolo) VALUES
('EUR', 'Euro', '€'),
('USD', 'Dólar estadounidense', '$'),
('GBP', 'Libra esterlina', '£'),
('JPY', 'Yen japonés', '¥'),
('CHF', 'Franco suizo', 'CHF'),
('CAD', 'Dólar canadiense', '$'),
('AUD', 'Dólar australiano', '$');


-- =====================================
-- DATOS DE PRUEBA USUARIO
-- =====================================

INSERT INTO usuarios (nombre, email, password)
VALUES (
    'Admin',
    'admin@admin.com',
    '$2y$10$abcdefghijklmnopqrstuv'
);