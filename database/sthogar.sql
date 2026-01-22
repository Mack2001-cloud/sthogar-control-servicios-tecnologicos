CREATE DATABASE IF NOT EXISTS sthogar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sthogar;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'tecnico') NOT NULL DEFAULT 'tecnico',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) DEFAULT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    address VARCHAR(200) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    serial_number VARCHAR(120) DEFAULT NULL,
    location VARCHAR(150) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    type VARCHAR(120) NOT NULL,
    description TEXT DEFAULT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'nuevo',
    scheduled_at DATE DEFAULT NULL,
    amount DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

CREATE TABLE servicio_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    servicio_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    status VARCHAR(50) NOT NULL,
    note VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    servicio_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    method VARCHAR(80) DEFAULT NULL,
    paid_at DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE
);

CREATE TABLE adjuntos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    servicio_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(120) NOT NULL,
    size INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role) VALUES
('Administrador', 'admin@sthogar.test', '$2y$12$JJAhTCxsN6OsPz9/Olxm7OvhPxnyKyMWhGHpu9ytpcsrHw0EYJeL6', 'admin'),
('Tecnico', 'tecnico@sthogar.test', '$2y$12$C9fvXemQk0WL5RCmI8/Q/OCTgnBW9rpUxrEu1e.pzBmtXLGN0fymG', 'tecnico');
