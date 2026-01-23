CREATE DATABASE IF NOT EXISTS sthogar_servicios
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE sthogar_servicios;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  pass_hash VARCHAR(255) NOT NULL,
  rol ENUM('admin','tecnico') NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE tecnicos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL UNIQUE,
  telefono VARCHAR(50) NOT NULL,
  direccion VARCHAR(200) NOT NULL,
  especialidad ENUM('CCTV','AUTOMATIZACION','RED','SOPORTE','POS','GENERAL') NOT NULL DEFAULT 'GENERAL',
  fecha_ingreso DATE DEFAULT NULL,
  notas TEXT DEFAULT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_tecnicos_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  telefono VARCHAR(50) NOT NULL,
  email VARCHAR(150) DEFAULT NULL,
  direccion VARCHAR(200) NOT NULL,
  referencia VARCHAR(200) DEFAULT NULL,
  tecnico_id INT DEFAULT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_clientes_telefono (telefono),
  INDEX idx_clientes_tecnico (tecnico_id),
  CONSTRAINT fk_clientes_tecnico
    FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE servicios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  folio VARCHAR(50) NOT NULL UNIQUE,
  cliente_id INT NOT NULL,
  tecnico_id INT DEFAULT NULL,
  categoria ENUM('CCTV','AUTOMATIZACION','RED','SOPORTE','POS','VENTA') NOT NULL,
  tipo ENUM('instalacion','mantenimiento','soporte','venta') NOT NULL,
  descripcion TEXT NOT NULL,
  prioridad ENUM('baja','media','alta') NOT NULL,
  estatus ENUM('pendiente','proceso','finalizado','cancelado') NOT NULL,
  fecha_programada DATE DEFAULT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_servicios_folio (folio),
  INDEX idx_servicios_estatus (estatus),
  INDEX idx_servicios_categoria (categoria),
  INDEX idx_servicios_fecha_programada (fecha_programada),
  INDEX idx_servicios_cliente (cliente_id),
  INDEX idx_servicios_tecnico (tecnico_id),
  CONSTRAINT fk_servicios_cliente
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_servicios_tecnico
    FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE equipos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  servicio_id INT DEFAULT NULL,
  categoria_equipo VARCHAR(120) NOT NULL,
  marca VARCHAR(120) NOT NULL,
  modelo VARCHAR(120) NOT NULL,
  serie VARCHAR(120) DEFAULT NULL,
  ubicacion VARCHAR(150) DEFAULT NULL,
  notas TEXT DEFAULT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_equipos_cliente (cliente_id),
  INDEX idx_equipos_servicio (servicio_id),
  CONSTRAINT fk_equipos_cliente
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_equipos_servicio
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE pagos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  servicio_id INT NOT NULL,
  monto DECIMAL(10,2) NOT NULL,
  metodo ENUM('efectivo','transferencia','tarjeta') NOT NULL,
  referencia_pago VARCHAR(120) DEFAULT NULL,
  fecha_pago DATE NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_pagos_servicio (servicio_id),
  CONSTRAINT fk_pagos_servicio
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE bitacora_servicio (
  id INT AUTO_INCREMENT PRIMARY KEY,
  servicio_id INT NOT NULL,
  usuario_id INT NOT NULL,
  comentario TEXT NOT NULL,
  estatus_nuevo ENUM('pendiente','proceso','finalizado','cancelado') NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_bitacora_servicio (servicio_id),
  INDEX idx_bitacora_usuario (usuario_id),
  CONSTRAINT fk_bitacora_servicio
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_bitacora_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE adjuntos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  servicio_id INT NOT NULL,
  nombre_original VARCHAR(255) NOT NULL,
  nombre_guardado VARCHAR(255) NOT NULL,
  ruta VARCHAR(255) NOT NULL,
  tipo_mime VARCHAR(120) NOT NULL,
  tamano INT NOT NULL,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_adjuntos_servicio (servicio_id),
  CONSTRAINT fk_adjuntos_servicio
    FOREIGN KEY (servicio_id) REFERENCES servicios(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO usuarios (nombre, email, pass_hash, rol, activo) VALUES
  ('Admin Sthogar', 'admin@sthogar.test', '$2y$12$BFgP/4jqzs9a/4NHcWFDh.FnsHtW7rkXPRd8DMG1RvSLkfYp/UawO', 'admin', 1),
  ('Carlos Rivera', 'carlos.rivera@sthogar.test', '$2y$12$qq1sxyZ4sTfLLYWXCyamZOqeIHsMfh4MIRj.AYOSbS3mxaWBKERPK', 'tecnico', 1),
  ('Lucia Morales', 'lucia.morales@sthogar.test', '$2y$12$qq1sxyZ4sTfLLYWXCyamZOqeIHsMfh4MIRj.AYOSbS3mxaWBKERPK', 'tecnico', 1);

-- tecnicos.id = 1 (Carlos), tecnicos.id = 2 (Lucia)
INSERT INTO tecnicos (usuario_id, telefono, direccion, especialidad, fecha_ingreso, notas, activo) VALUES
  (2, '55-1000-2000', 'Av. Reforma 123, CDMX', 'CCTV', '2022-04-10', 'Especialista en CCTV residencial.', 1),
  (3, '55-3000-4000', 'Calle Norte 456, CDMX', 'RED', '2023-01-18', 'Soporte en redes y cableado estructurado.', 1);

INSERT INTO clientes (nombre, telefono, email, direccion, referencia) VALUES
  ('Comercial Nova', '55-1111-2222', 'contacto@comercialnova.mx', 'Insurgentes 890, CDMX', 'Frente a parque central'),
  ('Residencial Las Palmas', '55-3333-4444', 'admin@laspalmas.mx', 'Av. Palmas 1200, CDMX', 'Torre B, piso 3'),
  ('Ferreteria El Martillo', '55-5555-6666', NULL, 'Calzada Sur 77, CDMX', 'Esquina con Av. Juarez');

INSERT INTO servicios (folio, cliente_id, tecnico_id, categoria, tipo, descripcion, prioridad, estatus, fecha_programada) VALUES
  ('STH-2024-0001', 1, 1, 'CCTV', 'instalacion', 'Instalacion de 8 camaras con DVR.', 'alta', 'proceso', '2024-06-12'),
  ('STH-2024-0002', 1, NULL, 'RED', 'mantenimiento', 'Revision de red y reemplazo de switches.', 'media', 'pendiente', '2024-06-18'),
  ('STH-2024-0003', 2, 2, 'SOPORTE', 'soporte', 'Soporte a sistema de acceso controlado.', 'media', 'proceso', '2024-06-15'),
  ('STH-2024-0004', 2, NULL, 'POS', 'soporte', 'Ajuste de terminales POS.', 'baja', 'pendiente', NULL),
  ('STH-2024-0005', 3, 1, 'AUTOMATIZACION', 'instalacion', 'Automatizacion de porton principal.', 'alta', 'finalizado', '2024-05-28'),
  ('STH-2024-0006', 3, NULL, 'VENTA', 'venta', 'Venta de camaras adicionales.', 'baja', 'cancelado', NULL);

INSERT INTO pagos (servicio_id, monto, metodo, referencia_pago, fecha_pago) VALUES
  (1, 8500.00, 'transferencia', 'TRX-001-2024', '2024-06-10'),
  (3, 2200.00, 'efectivo', NULL, '2024-06-14'),
  (5, 15600.00, 'tarjeta', 'TARJ-9981', '2024-05-30');

INSERT INTO bitacora_servicio (servicio_id, usuario_id, comentario, estatus_nuevo) VALUES
  (1, 1, 'Servicio creado y asignado a tecnico.', 'pendiente'),
  (1, 2, 'Inicio de instalacion de camaras.', 'proceso'),
  (2, 1, 'Servicio creado sin tecnico asignado.', 'pendiente'),
  (3, 3, 'Diagnostico inicial completado.', 'proceso'),
  (3, 1, 'Solicitud de refacciones.', 'proceso'),
  (4, 1, 'Servicio en espera de confirmacion.', 'pendiente'),
  (5, 2, 'Instalacion finalizada y pruebas completas.', 'finalizado'),
  (6, 1, 'Servicio cancelado por cliente.', 'cancelado');

INSERT INTO adjuntos (servicio_id, nombre_original, nombre_guardado, ruta, tipo_mime, tamano) VALUES
  (1, 'evidencia_inicio.jpg', 'srv1_inicio_20240612.jpg', 'uploads/servicios/1/', 'image/jpeg', 245812),
  (1, 'planos.pdf', 'srv1_planos_20240612.pdf', 'uploads/servicios/1/', 'application/pdf', 480120),
  (3, 'diagnostico.txt', 'srv3_diagnostico_20240615.txt', 'uploads/servicios/3/', 'text/plain', 1820);
