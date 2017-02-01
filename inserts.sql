SET FOREIGN_KEY_CHECKS=0;
--  phpMyAdmin SQL Dump
--  version 4.0.10deb1
--  http://www.phpmyadmin.net
-- 
--  Servidor: 127.0.0.1
--  Tiempo de generación: 18-12-2016 a las 20:19:30
--  Versión del servidor: 5.5.53-0ubuntu0.14.04.1
--  Versión de PHP: 5.5.9-1ubuntu4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- 
--  Base de datos: 'sgpi'
-- 

-- 
--  Volcado de datos para la tabla 'sedes_ucc'
-- 

INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(1, 'Apartadó', 'Apartadó', 'Antioquia', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(2, 'Arauca', 'Arauca', 'Arauca', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(3, 'Barrancabermeja', 'Barrancabermeja', 'Santander', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(4, 'Bogotá', 'Bogotá', 'Cundinamarca', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(5, 'Bucaramanga', 'Bucaramanga', 'Santander', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(6, 'Cali', 'Cali', 'Valle del Cauca', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(7, 'Cartago', 'Cartago', 'Valle del Cauca', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(8, 'El Espinal', 'El Espinal', 'Tolima', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(9, 'Ibagué', 'Ibagué', 'Tolima', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(10, 'Medellín', 'Medellín', 'Antioquia', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(11, 'Montería', 'Montería', 'Córdoba', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(12, 'Neiva', 'Neiva', 'Huila', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(13, 'Pasto', 'Pasto', 'Nariño', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(14, 'Pereira', 'Pereira', 'Risaralda', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(15, 'Popayán', 'Popayán', 'Cauca', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(16, 'Quibdó', 'Quibdó', 'Chocó', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(17, 'Santa Marta', 'Santa Marta', 'Magadela', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO sedes_ucc (id, nombre, ciudad, departamento_estado, pais, descripcion, created_at, updated_at, deleted_at) VALUES(18, 'Villavicencio', 'Villavicencio', 'Meta', 'Colombia', NULL, '2016-12-16', '2016-12-16', NULL);

-- 
--  Volcado de datos para la tabla 'facultades_dependencias_ucc'
-- 

-- Apartado
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(1, 1, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(2, 1, 'Facultad de Enfermería', '2016-12-16', '2016-12-16', NULL);

-- Arauca
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(3, 2, 'Facultad de Ciencias Económicas, Administrativas y Contables', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(4, 2, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(5, 2, 'Facultad de Ingeniería de Sistemas', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(6, 2, 'Facultad de Medicina Veterinaria y Zootecnia', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(7, 2, 'Facultad de Psicología', '2016-12-16', '2016-12-16', NULL);

-- Barrancabermeja
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(8, 3, 'Facultad de Administración de Empresas', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(9, 3, 'Facultad de Contaduría Pública', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(10, 3, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(11, 3, 'Facultad de Ingeniera Industrial', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(12, 3, 'Facultad de Psicología', '2016-12-16', '2016-12-16', NULL);

-- Bogotá
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(13, 4, 'Facultad de Ciencias Económicas y Administrativas', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(14, 4, 'Facultad de Ciencias Humanas, Sociales y Educación', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(15, 4, 'Facultad de Contaduría Pública', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(16, 4, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(17, 4, 'Facultad de Ingeniería', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(18, 4, 'Facultad de Odontología', '2016-12-16', '2016-12-16', NULL);

-- Bucaramanga
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(19, 5, 'Facultad de Ciencias Económicas, Administrativas y Contables', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(21, 5, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(22, 5, 'Facultad de Educación', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(23, 5, 'Facultad de Enfermería', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(24, 5, 'Facultad de Ingeniería', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(25, 5, 'Facultad de Medicina Veterinaría Zootecnia', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(26, 5, 'Facultad de Psicología', '2016-12-16', '2016-12-16', NULL);

-- Cali
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(27, 6, 'Facultad de Ciencias Administrativas Económicas y Contables', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(28, 6, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(29, 6, 'Facultad de Ingeniería', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(30, 6, 'Facultad de Psicología', '2016-12-16', '2016-12-16', NULL);

-- Cartago
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(31, 7, 'Facultad de Derecho ', '2016-12-16', '2016-12-16', NULL);

-- El Espinal
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(32, 8, 'Facultad de Administración de Empresas', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(33, 8, 'Facultad de Contaduría pública', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(34, 8, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(35, 8, 'Facultad de Ingeniería de Sistemas', '2016-12-16', '2016-12-16', NULL);

-- Ibagué
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(36, 9, 'Facultad de Ingeniería', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(37, 9, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(38, 9, 'Facultad de Ciencias Administrativas Económicas y Contables', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(39, 9, 'Medicina Veterinaria y Zootecnia', '2016-12-16', '2016-12-16', NULL);

-- Medellín
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(41, 10, 'Facultad de Ciencias de la Comunicación', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(42, 10, 'Facultad de Ciencias Económicas, Administrativas y Afines', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(43, 10, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(44, 10, 'Facultad de Educación', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(45, 10, 'Facultad de Ingenierías', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(46, 10, 'Facultad de Medicina', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(47, 10, 'Facultad de Odontología', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(48, 10, 'Facultad de Psicología', '2016-12-16', '2016-12-16', NULL);

-- Montería
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(49, 11, 'Facultad de Administración de Empresas', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(51, 11, 'Facultad de Contaduría Pública', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(52, 11, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(53, 11, 'Facultad de Ingeniería de Sistemas', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(54, 11, 'Facultad de Psicología', '2016-12-16', '2016-12-16', NULL);

-- Neiva
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(55, 12, 'Facultad de Ingeniería', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(56, 12, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(57, 12, 'Facultad de Contaduría Pública', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(58, 12, 'Facultad de Psicología', '2016-12-16', '2016-12-16', NULL);

-- Pasto
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(59, 13, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(61, 13, 'Facultad de Ingeniería', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(62, 13, 'Facultad de Medicina', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(63, 13, 'Facultad de Odontología', '2016-12-16', '2016-12-16', NULL);

-- Pereira
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(64, 14, 'Facultad de Ciencias Administrativas, Económicas y Contables', '2016-12-16', '2016-12-16', NULL);

-- Popayan
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(65, 15, 'Facultad de Ciencias Administrativas, Económicas y Contables', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(66, 15, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(67, 15, 'Facultad de Ingeniería', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(68, 15, 'Facultad de Psicología', '2016-12-16', '2016-12-16', NULL);

-- Quibdó
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(69, 16, 'Facultad de Contaduría Pública', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(70, 16, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);

-- Santa Marta
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(71, 17, 'Facultad de Ciencias Administrativas, Contables y Comercio Internacional', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(72, 17, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(73, 17, 'Facultad de Enfermería', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(74, 17, 'Facultad de Ingeniería', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(75, 17, 'Facultad de Medicina', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(76, 17, 'Facultad de Psicología', '2016-12-16', '2016-12-16', NULL);

-- Villavicencio
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(77, 18, 'Facultad Administracion de Empresas', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(78, 18, 'Facultad de Contaduria Publica', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(79, 18, 'Facultad de Derecho', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(81, 18, 'Facultad de Ingeniería Civi', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(82, 18, 'Facultad de Ingeniería de Sistemas​', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(83, 18, 'Facultad de Medicina', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(84, 18, 'Facultad de Medicina Veterinaria y Zootecnia', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(85, 18, 'Facultad de Odontología', '2016-12-16', '2016-12-16', NULL);
INSERT INTO facultades_dependencias_ucc (id, id_sede_ucc, nombre, created_at, updated_at, deleted_at) VALUES(86, 18, 'Facultad de Psicología​​', '2016-12-16', '2016-12-16', NULL);

-- 
--  Volcado de datos para la tabla 'gran_areas'
-- 

INSERT INTO gran_areas (id, nombre, created_at, updated_at, deleted_at) VALUES(1, 'Ciencias Agricolas', '2016-12-16 02:47:34', '2016-12-16 02:47:34', NULL);
INSERT INTO gran_areas (id, nombre, created_at, updated_at, deleted_at) VALUES(2, 'Ciencias Medicas y de Salud', '2016-12-16 02:47:34', '2016-12-16 02:47:34', NULL);
INSERT INTO gran_areas (id, nombre, created_at, updated_at, deleted_at) VALUES(3, 'Ciencias Naturales', '2016-12-16 02:47:34', '2016-12-16 02:47:34', NULL);
INSERT INTO gran_areas (id, nombre, created_at, updated_at, deleted_at) VALUES(4, 'Ciencias Sociales', '2016-12-16 02:47:34', '2016-12-16 02:47:34', NULL);
INSERT INTO gran_areas (id, nombre, created_at, updated_at, deleted_at) VALUES(5, 'Ciencias Agricolas', '2016-12-16 02:47:34', '2016-12-16 02:47:34', NULL);
INSERT INTO gran_areas (id, nombre, created_at, updated_at, deleted_at) VALUES(6, 'Ingeniería yTecnología', '2016-12-16 02:47:34', '2016-12-16 02:47:34', NULL);

-- 
--  Volcado de datos para la tabla 'areas'
-- 

INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(1, 1, 'Agricultura,Silvicultura y Pesca', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(2, 1, 'Biotecnología Agricola', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(3, 1, 'Ciencias Animales y Lecheria', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(4, 1, 'Ciencias Veterinarias', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(5, 1, 'Otras Ciencias Agricolas', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(6, 2, 'Biotecnologia en Salud', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(7, 2, 'Ciencias de la Salud', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(8, 2, 'Medicina Basica', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(9, 2, 'Medicina Clinica', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(10, 2, 'Otras Ciencias Medicas', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(11, 3, 'Ciencias Biologicas', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(12, 3, 'Ciencias de la Tierra y Medioambientales', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(13, 3, 'Ciencias Físicas', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(14, 3, 'Ciencias  Químicas', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(15, 3, 'Computacion y Ciencias de la informacion', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(16, 3, 'Matematicas', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(17, 3, 'Otras Ciencias  Naturales', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(18, 4, 'Ciencias de la Educacion', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(19, 4, 'Ciencias Políticas', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(20, 4, 'Derecho', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(21, 4, 'Economia y Negocios', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(22, 4, 'Geografia Social y Economica', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(23, 4, 'Periodismo y Comunicacione', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(24, 4, 'Psicologia', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(25, 4, 'Sociologia', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(26, 4, 'Otras Ciencias Sociales', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(27, 5, 'Arte', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(28, 5, 'Historia y Arqueologia', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(29, 5, 'Idiomas y Literatura', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(30, 5, 'Otras Historias', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(31, 5, 'OtrasHumanidades', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(32, 6, 'Biotecnologia Indutrial', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(33, 6, 'Ingeniería Ambiental', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(34, 6, 'Ingeniería Civil', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(35, 6, 'Ingeniería de los Materiales', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(36, 6, 'Ingeniería Mecánica', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(37, 6, 'Ingeniería Médica', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(38, 6, 'Ingeniería Química', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(39, 6, 'Ingeniería Eléctrica, Electrónica e Informática', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(40, 6, 'Nanotecnologica', '2016-12-16', NULL, '2016-12-16');
INSERT INTO areas (id, id_gran_area, nombre, created_at, deleted_at, updated_at) VALUES(41, 6, 'Otras Ingenierias y Tecnologias', '2016-12-16', NULL, '2016-12-16');

-- 
--  Volcado de datos para la tabla 'categorias_investigadores_ucc'
-- 

INSERT INTO categorias_investigadores (id, nombre, created_at, updated_at, deleted_at) VALUES(1, 'Investigador Senior', '2016-12-16', '2016-12-16', NULL);
INSERT INTO categorias_investigadores (id, nombre, created_at, updated_at, deleted_at) VALUES(2, 'Investigador Asociado', '2016-12-16', '2016-12-16', NULL);
INSERT INTO categorias_investigadores (id, nombre, created_at, updated_at, deleted_at) VALUES(3, 'Investigador Junior', '2016-12-16', '2016-12-16', NULL);
INSERT INTO categorias_investigadores (id, nombre, created_at, updated_at, deleted_at) VALUES(4, 'Integrante Vinculado con Doctorado', '2016-12-16', '2016-12-16', NULL);
INSERT INTO categorias_investigadores (id, nombre, created_at, updated_at, deleted_at) VALUES(5, 'Integrante Vinculado con Maestría', '2016-12-16', '2016-12-16', NULL);
INSERT INTO categorias_investigadores (id, nombre, created_at, updated_at, deleted_at) VALUES(6, 'Jóven Investigador', '2016-12-16', '2016-12-16', NULL);

-- 
--  Volcado de datos para la tabla 'clasificaciones_grupos_investigacion_ucc'
-- 

INSERT INTO clasificaciones_grupos_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(1, 'A', '2016-12-16', '2016-12-16', NULL);
INSERT INTO clasificaciones_grupos_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(2, 'A1', '2016-12-16', '2016-12-16', NULL);
INSERT INTO clasificaciones_grupos_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(3, 'B', '2016-12-16', '2016-12-16', NULL);
INSERT INTO clasificaciones_grupos_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(4, 'C', '2016-12-16', '2016-12-16', NULL);
INSERT INTO clasificaciones_grupos_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(5, 'Reconocido', '2016-12-16', '2016-12-16', NULL);

-- 
--  Volcado de datos para la tabla 'grupos_investigacion_ucc'
-- 

-- grupos_investigacion Cali
INSERT INTO grupos_investigacion_ucc (id, id_facultad_dependencia_ucc, id_area, id_clasificacion_grupo_investigacion, nombre, created_at, updated_at, deleted_at) VALUES(1, 14, 41, 4, 'Ingeniería aplicada', '2016-12-16', '2016-12-16', NULL);
INSERT INTO grupos_investigacion_ucc (id, id_facultad_dependencia_ucc, id_area, id_clasificacion_grupo_investigacion, nombre, created_at, updated_at, deleted_at) VALUES(2, 12, 41, 4, 'El Trueque', '2016-12-16', '2016-12-16', NULL);
INSERT INTO grupos_investigacion_ucc (id, id_facultad_dependencia_ucc, id_area, id_clasificacion_grupo_investigacion, nombre, created_at, updated_at, deleted_at) VALUES(3, 12, 41, 4, 'Mileto: Ambiental y Finanzas', '2016-12-16', '2016-12-16', NULL);
INSERT INTO grupos_investigacion_ucc (id, id_facultad_dependencia_ucc, id_area, id_clasificacion_grupo_investigacion, nombre, created_at, updated_at, deleted_at) VALUES(4, 15, 41, 4, 'Estudios Psicológicos en Educación', '2016-12-16', '2016-12-16', NULL);
INSERT INTO grupos_investigacion_ucc (id, id_facultad_dependencia_ucc, id_area, id_clasificacion_grupo_investigacion, nombre, created_at, updated_at, deleted_at) VALUES(5, 13, 41, 4, 'Solidarios', '2016-12-16', '2016-12-16', NULL);

-- 
--  Volcado de datos para la tabla 'estados'
-- 

INSERT INTO estados (id, nombre, created_at, updated_at, deleted_at) VALUES(1, 'Activo', '2016-12-16', '2016-12-16', NULL);
INSERT INTO estados (id, nombre, created_at, updated_at, deleted_at) VALUES(2, 'Inactivo', '2016-12-16', '2016-12-16', NULL);


-- 
--  Volcado de datos para la tabla 'lineas_grupos_investigacion_ucc'
-- 

INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(1, 5, 1, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(2, 5, 2, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(19, 3, 1, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(20, 3, 2, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(21, 3, 4, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(22, 6, 5, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(23, 7, 6, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(24, 7, 4, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(25, 7, 7, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(26, 8, 8, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(27, 8, 9, '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(28, 9, 7, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(29, 9, 4, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(30, 10, 2, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(31, 10, 3, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(32, 10, 5, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(33, 10, 7, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(34, 10, 6, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(35, 10, 9, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(36, 10, 8, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(37, 10, 4, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(38, 10, 1, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(39, 11, 3, '2016-12-18', '2016-12-18', NULL);
INSERT INTO lineas_grupos_investigacion_ucc (id, id_grupo_investigacion_ucc, id_linea_investigacion, created_at, updated_at, deleted_at) VALUES(40, 4, 10, '2016-12-18', '2016-12-18', NULL);

-- 
--  Volcado de datos para la tabla 'lineas_investigacion'
-- 

INSERT INTO lineas_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(1, 'Mi primera linea', '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(2, 'Mi segunda linea', '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(3, 'otra linea 1', '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(4, 'otra linea 3', '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(5, 'q', '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(6, '0x', '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(7, 'otra linea 4', '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(8, 'otra linea 5', '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(9, 'otra linea 6', '2016-12-16', '2016-12-16', NULL);
INSERT INTO lineas_investigacion (id, nombre, created_at, updated_at, deleted_at) VALUES(10, 'ff', '2016-12-18', '2016-12-18', NULL);

-- 
--  Volcado de datos para la tabla 'personas'
-- 

INSERT INTO personas (id, id_categoria_investigador, id_tipo_identificacion, nombres, apellidos, edad, sexo, identificacion, formacion, foto, created_at, updated_at, deleted_at) VALUES(1, NULL, 1, 'Curly Jack', 'Henao Hollo', 23, 'm', 1547897711, 'Maestría', NULL, '2016-12-16', '2016-12-16', NULL);
INSERT INTO personas (id, id_categoria_investigador, id_tipo_identificacion, nombres, apellidos, edad, sexo, identificacion, formacion, foto, created_at, updated_at, deleted_at) VALUES(2, 1, 1, 'Walter', 'Devia', 23, 'm', 7, 'Maestría', NULL, curdate(), NULL, NULL);

-- 
--  Volcado de datos para la tabla 'roles'
-- 

INSERT INTO roles (id, nombre, created_at, updated_at, deleted_at) VALUES(1, 'Administrador', '2016-12-16', '2016-12-16', NULL);
INSERT INTO roles (id, nombre, created_at, updated_at, deleted_at) VALUES(2, 'Coordinador', '2016-12-16', '2016-12-16', NULL);
INSERT INTO roles (id, nombre, created_at, updated_at, deleted_at) VALUES(3, 'Investigador principal', '2016-12-16', '2016-12-16', NULL);
INSERT INTO roles (id, nombre, created_at, updated_at, deleted_at) VALUES(4, 'Investigador interno', '2016-12-16', '2016-12-16', NULL);
INSERT INTO roles (id, nombre, created_at, updated_at, deleted_at) VALUES(5, 'Investigador externo', '2016-12-16', '2016-12-16', NULL);
INSERT INTO roles (id, nombre, created_at, updated_at, deleted_at) VALUES(6, 'Estudiante', '2016-12-16', '2016-12-16', NULL);

-- 
--  Volcado de datos para la tabla 'tipos_identificacion'
-- 

INSERT INTO tipos_identificacion (id, nombre, acronimo, created_at, updated_at, deleted_at) VALUES(1, 'Cédula de ciudadanía Colombiana', 'CC', '2016-12-16', '2016-12-16', NULL);
INSERT INTO tipos_identificacion (id, nombre, acronimo, created_at, updated_at, deleted_at) VALUES(2, 'Cédula de Extranjería', 'CE', '2016-12-16', '2016-12-16', NULL);
INSERT INTO tipos_identificacion (id, nombre, acronimo, created_at, updated_at, deleted_at) VALUES(3, 'Pasaporte', 'PA', '2016-12-16', '2016-12-16', NULL);
INSERT INTO tipos_identificacion (id, nombre, acronimo, created_at, updated_at, deleted_at) VALUES(4, 'Tarjeta de Identidad', 'TI', '2016-12-16', '2016-12-16', NULL);

-- 
--  Volcado de datos para la tabla 'usuarios'
-- 

INSERT INTO usuarios (id, id_persona, id_rol, id_estado, id_grupo_investigacion_ucc, username, password, email, created_at, updated_at, deleted_at, remember_token) VALUES(1, 1, 1, 1, NULL, 'admin', '$2y$10$a2Zob26boay17xOvlqTW2uCrDHTVBYIZMGWOE1aqt2l9Yk1l0MZty', 'jose_.devia@hotmail.com', '2016-12-16', '2016-12-16', NULL, 'gqqf47wwP1NwoigGdSfK5qiaxb2GtmHns3OjprjjGyuzZMca36Q0r8xnJ788');
INSERT INTO usuarios (id, id_persona, id_rol, id_estado, id_grupo_investigacion_ucc, username, password, email, created_at, updated_at, deleted_at, remember_token) VALUES(2, 2, 3, 1, 1, 'investigador', '$2y$10$a2Zob26boay17xOvlqTW2uCrDHTVBYIZMGWOE1aqt2l9Yk1l0MZty', 'jose_.devia@hotmail.com', curdate(), NULL, NULL, 'gqqf47wwP1NwoigGdSfK5qiaxb2GtmHns3OjprjjGyuzZMca36Q0r8xnJ788');

-- 
--  Volcado de datos para la tabla 'tipos_productos_generales'
-- 

INSERT INTO `tipos_productos_generales` (`id`, `nombre`) VALUES
(1, 'PRODUCTOS DE NUEVO CONOCIMIENTO'),
(2, 'PRODUCTOS DE DESARROLLO TECNOLOGICO E INNOVACION'),
(3, 'PRODUCTOS DE APROPIACION SOCIAL Y CIRCULACION DEL CONOCIMIENTO'),
(4, 'FORMACION DE RECURSO HUMANO');

-- 
--  Volcado de datos para la tabla 'tipos_productos_especificos'
-- 

INSERT INTO `tipos_productos_especificos` (`id`, `id_tipo_producto_general`, `nombre`) VALUES
(1, 1, 'Productos tipo TOP(calidad A1 y A2 -revistas en Q1 y Q2)'),
(2, 1, 'Productos tipo A (calidad B y C - Revistas Q3 y Q4)'),
(3, 1, 'Productos tipo B (Calidad D - revistas indexadas en al menos dos bases bibliograficas reconocidas por Colciencias)'),
(4, 2, 'Software con certificación de la entidad productora en el que se haga claridad sobre el\nnivel de innovación.'),
(5, 2, 'Produccion Tecnica y Tecnologica (Consultoria cientifico tecnologica e informe tecnico)'),
(6, 3, 'Presentacion de ponencia en evento nacional'),
(7, 3, 'Organizacion de evento nacional '),
(8, 3, 'Organizacion de evento internacional'),
(9, 4, 'Proyecto ID+I con formacion (Proyectos ejecutado con investigadores en empresas)'),
(10, 4, 'Actividades de formacion (Trabajos de grado /tutorias de pregrado)preferiblemente con distincion'),
(11, 4, 'Apoyo a programa o curso de formacion de investigadores (Maestrias asociadas al grupo)'),
(12, 4, 'Proyecto de Investigacion y Desarrollo con financiacion externa internacional'),
(13, 4, 'Proyectos de extension y responsabilidad social en CTeI Vinculados en el Grupo');

-- 
--  Volcado de datos para la tabla 'entidades_fuente_presupuesto'
-- 

INSERT INTO `entidades_fuente_presupuesto` (nombre, created_at) VALUES 
('UCC', curdate()),
('CONADI', curdate()),
('Entidad desde BD', curdate());

-- 
--  Volcado de datos para la tabla 'tipos_gastos'
-- 

INSERT INTO `tipos_gastos` (nombre, created_at) VALUES 
('Personal', curdate()),
('Equipos', curdate()),
('Software', curdate()),
('Salidas de campo', curdate()),
('Materiales y suministros', curdate()),
('Servicios técnicos', curdate()),
('Recursos bibliográficos', curdate()),
('Recursos educativos digitales', curdate());

-- 
--  Volcado de datos para la tabla 'tipos_postulaciones_publicaciones'
-- 
INSERT INTO `tipos_postulaciones_publicaciones` (nombre, created_at) VALUES
('Proyectado', curdate()),
('Publicado', curdate());

-- 
--  Volcado de datos para la tabla 'formatos_tipos_documentos'
-- 

INSERT INTO `formatos_tipos_documentos` (nombre, archivo, created_at) VALUES
('Presupuesto', 'FMI6-25-V2_Presupuesto.xlsx', curdate()),
('Presentacion proyecto', 'FMI6-11-V3_Proyecto.docx', curdate()),
('Acta inicio', 'FMI6-1 Acta de Inicio_V5.docx', curdate()),
('Informe de avance', 'FMI6-21-V1_Informe de avance.dotx', curdate()),
('Desembolso', 'FAJ1-6-V3_Solicitud de insumos.xlsx', curdate()),
('Memoria academica', 'FMI6-10-V1 Formato Memoria Academica.dotx', curdate()),
('Acta finalizacion', 'FMI6-2 Acta de Finalización.docx', curdate()),
('Prorroga', NULL, curdate()),
('Aprobacion final proyecto', NULL, curdate()),
('Aprobacion prorroga', NULL, curdate());


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SET FOREIGN_KEY_CHECKS=1;