-- Sistema de Estacionamiento de Vehiculos
-- Base de datos MySQL para XAMPP (phpMyAdmin > Importar)
-- Usuarios de prueba:
--   admin@estacionamiento.com       / admin123       (Administrador)
--   operador@estacionamiento.com    / operador123    (Operador)
--   supervisor@estacionamiento.com  / supervisor123  (Supervisor)

CREATE DATABASE IF NOT EXISTS `estacionamiento_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `estacionamiento_db`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `boletos`;
DROP TABLE IF EXISTS `configuracion`;
DROP TABLE IF EXISTS `metodos_pago`;
DROP TABLE IF EXISTS `espacios_estacionamiento`;
DROP TABLE IF EXISTS `vehiculos`;
DROP TABLE IF EXISTS `tipos_vehiculo`;
DROP TABLE IF EXISTS `clientes`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `roles`;
SET FOREIGN_KEY_CHECKS = 1;

-- Roles y usuarios
CREATE TABLE `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `usuarios` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `correo` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `id_rol` BIGINT UNSIGNED NOT NULL,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_correo_unique` (`correo`),
  CONSTRAINT `usuarios_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Clientes, tipos de vehiculo y vehiculos
CREATE TABLE `clientes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `telefono` VARCHAR(20) DEFAULT NULL,
  `correo` VARCHAR(100) DEFAULT NULL,
  `es_suscriptor` TINYINT(1) NOT NULL DEFAULT 0,
  `fecha_vencimiento` DATE DEFAULT NULL,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tipos_vehiculo` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `tarifa_hora` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `vehiculos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `placa` VARCHAR(15) NOT NULL,
  `id_cliente` BIGINT UNSIGNED DEFAULT NULL,
  `id_tipo_vehiculo` BIGINT UNSIGNED NOT NULL,
  `marca` VARCHAR(50) DEFAULT NULL,
  `modelo` VARCHAR(50) DEFAULT NULL,
  `color` VARCHAR(30) DEFAULT NULL,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehiculos_placa_unique` (`placa`),
  CONSTRAINT `vehiculos_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`),
  CONSTRAINT `vehiculos_id_tipo_vehiculo_foreign` FOREIGN KEY (`id_tipo_vehiculo`) REFERENCES `tipos_vehiculo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Espacios, metodos de pago y configuracion
CREATE TABLE `espacios_estacionamiento` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `piso` TINYINT UNSIGNED NOT NULL,
  `sector` VARCHAR(10) NOT NULL,
  `numero` SMALLINT UNSIGNED NOT NULL,
  `esta_ocupado` TINYINT(1) NOT NULL DEFAULT 0,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `espacios_estacionamiento_piso_sector_numero_unique` (`piso`, `sector`, `numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `metodos_pago` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `configuracion` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_estacionamiento` VARCHAR(100) NOT NULL,
  `logo` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Boletos (entradas y salidas)
CREATE TABLE `boletos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_vehiculo` BIGINT UNSIGNED NOT NULL,
  `id_espacio_estacionamiento` BIGINT UNSIGNED NOT NULL,
  `id_usuario` BIGINT UNSIGNED NOT NULL,
  `hora_entrada` DATETIME NOT NULL,
  `hora_salida` DATETIME DEFAULT NULL,
  `total_tiempo_minutos` INT UNSIGNED DEFAULT NULL,
  `id_metodo_pago` BIGINT UNSIGNED DEFAULT NULL,
  `monto_total` DECIMAL(8,2) DEFAULT NULL,
  `esta_pagado` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `boletos_id_vehiculo_foreign` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id`),
  CONSTRAINT `boletos_id_espacio_foreign` FOREIGN KEY (`id_espacio_estacionamiento`) REFERENCES `espacios_estacionamiento` (`id`),
  CONSTRAINT `boletos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `boletos_id_metodo_pago_foreign` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pago` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================= DATOS DE EJEMPLO =======================

INSERT INTO `roles` (`nombre`) VALUES ('Administrador'), ('Operador'), ('Supervisor');

INSERT INTO `usuarios` (`nombre`, `correo`, `password`, `id_rol`, `estado`, `created_at`, `updated_at`) VALUES
('Administrador', 'admin@estacionamiento.com', '$2y$10$MYbx8dxvVav9boE11sOPueE2H3rgu4xz1Nocx/4f239BcWFRl6HwG', 1, 1, NOW(), NOW()),
('Carlos Operador', 'operador@estacionamiento.com', '$2y$10$PukZ1e7mn131.HA1YL2p9.OmsY40RgI9h6O8VDGUJOON5TC4Qgva.', 2, 1, NOW(), NOW()),
('Sofía Supervisora', 'supervisor@estacionamiento.com', '$2y$10$Vrbung.41a8Jdp8QwKZ.POJdQyCH5oX9z3wPqpb1pUTVquGBhxdR6', 3, 1, NOW(), NOW());

INSERT INTO `tipos_vehiculo` (`nombre`, `tarifa_hora`) VALUES ('Auto', 5.00), ('Moto', 2.00), ('Camioneta', 7.00);

INSERT INTO `metodos_pago` (`nombre`) VALUES ('Efectivo'), ('QR');

INSERT INTO `espacios_estacionamiento` (`piso`, `sector`, `numero`, `esta_ocupado`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'A', 1, 0, 1, NOW(), NOW()),
(1, 'A', 2, 0, 1, NOW(), NOW()),
(1, 'A', 3, 0, 1, NOW(), NOW()),
(1, 'A', 4, 0, 1, NOW(), NOW()),
(1, 'B', 1, 1, 1, NOW(), NOW()),
(1, 'B', 2, 0, 1, NOW(), NOW()),
(1, 'B', 3, 0, 1, NOW(), NOW()),
(1, 'B', 4, 0, 1, NOW(), NOW()),
(2, 'A', 1, 0, 1, NOW(), NOW()),
(2, 'A', 2, 0, 1, NOW(), NOW()),
(2, 'A', 3, 0, 1, NOW(), NOW()),
(2, 'A', 4, 0, 1, NOW(), NOW()),
(2, 'B', 1, 0, 1, NOW(), NOW()),
(2, 'B', 2, 0, 1, NOW(), NOW()),
(2, 'B', 3, 0, 1, NOW(), NOW()),
(2, 'B', 4, 0, 1, NOW(), NOW());

INSERT INTO `configuracion` (`nombre_estacionamiento`, `logo`, `created_at`, `updated_at`) VALUES ('Estacionamiento Central', NULL, NOW(), NOW());

INSERT INTO `clientes` (`nombre`, `telefono`, `correo`, `es_suscriptor`, `fecha_vencimiento`, `estado`, `created_at`, `updated_at`) VALUES
('Juan Pérez', '70112233', 'juan@correo.com', 1, DATE_ADD(CURDATE(), INTERVAL 1 MONTH), 1, NOW(), NOW()),
('María Gómez', '71445566', 'maria@correo.com', 0, NULL, 1, NOW(), NOW());

INSERT INTO `vehiculos` (`placa`, `id_cliente`, `id_tipo_vehiculo`, `marca`, `modelo`, `color`, `estado`, `created_at`, `updated_at`) VALUES
('1234-ABC', 1, 1, 'Toyota', 'Yaris', 'Rojo', 1, NOW(), NOW()),
('4521-XYZ', 2, 2, 'Honda', 'CB125', 'Negro', 1, NOW(), NOW()),
('3300-KLM', NULL, 3, 'Nissan', 'Frontier', 'Blanco', 1, NOW(), NOW()),
('2345-QWE', 1, 1, 'Suzuki', 'Swift', 'Gris', 1, NOW(), NOW());

INSERT INTO `boletos` (`id_vehiculo`, `id_espacio_estacionamiento`, `id_usuario`, `hora_entrada`, `hora_salida`, `total_tiempo_minutos`, `id_metodo_pago`, `monto_total`, `esta_pagado`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NOW() - INTERVAL 6 DAY - INTERVAL 75 MINUTE, NOW() - INTERVAL 6 DAY - INTERVAL 30 MINUTE, 45, 1, 5.00, 1, NOW(), NOW()),
(2, 2, 2, NOW() - INTERVAL 6 DAY - INTERVAL 160 MINUTE, NOW() - INTERVAL 6 DAY - INTERVAL 30 MINUTE, 130, 2, 6.00, 1, NOW(), NOW()),
(3, 3, 2, NOW() - INTERVAL 5 DAY - INTERVAL 125 MINUTE, NOW() - INTERVAL 5 DAY - INTERVAL 30 MINUTE, 95, 1, 14.00, 1, NOW(), NOW()),
(1, 1, 2, NOW() - INTERVAL 4 DAY - INTERVAL 230 MINUTE, NOW() - INTERVAL 4 DAY - INTERVAL 30 MINUTE, 200, 1, 20.00, 1, NOW(), NOW()),
(4, 4, 2, NOW() - INTERVAL 3 DAY - INTERVAL 90 MINUTE, NOW() - INTERVAL 3 DAY - INTERVAL 30 MINUTE, 60, 2, 5.00, 1, NOW(), NOW()),
(2, 2, 2, NOW() - INTERVAL 2 DAY - INTERVAL 60 MINUTE, NOW() - INTERVAL 2 DAY - INTERVAL 30 MINUTE, 30, 1, 2.00, 1, NOW(), NOW()),
(1, 1, 2, NOW() - INTERVAL 2 DAY - INTERVAL 340 MINUTE, NOW() - INTERVAL 2 DAY - INTERVAL 30 MINUTE, 310, 1, 30.00, 1, NOW(), NOW()),
(3, 3, 2, NOW() - INTERVAL 1 DAY - INTERVAL 180 MINUTE, NOW() - INTERVAL 1 DAY - INTERVAL 30 MINUTE, 150, 2, 21.00, 1, NOW(), NOW()),
(4, 4, 2, NOW() - INTERVAL 1 DAY - INTERVAL 105 MINUTE, NOW() - INTERVAL 1 DAY - INTERVAL 30 MINUTE, 75, 1, 10.00, 1, NOW(), NOW()),
(1, 1, 2, NOW() - INTERVAL 0 DAY - INTERVAL 120 MINUTE, NOW() - INTERVAL 0 DAY - INTERVAL 30 MINUTE, 90, 1, 10.00, 1, NOW(), NOW()),
(2, 5, 2, NOW() - INTERVAL 40 MINUTE, NULL, NULL, NULL, NULL, 0, NOW(), NOW());
