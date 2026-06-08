/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: color_de_vida
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-2 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `acudientes`
--

DROP TABLE IF EXISTS `acudientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `acudientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_documento` varchar(10) DEFAULT 'CC',
  `numero_documento` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `parentesco` varchar(20) DEFAULT 'Padre',
  `activo` tinyint(1) DEFAULT 1,
  `foto` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_documento` (`numero_documento`),
  UNIQUE KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `acudientes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acudientes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `acudientes` WRITE;
/*!40000 ALTER TABLE `acudientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `acudientes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `calificaciones`
--

DROP TABLE IF EXISTS `calificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `calificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante_id` int(11) NOT NULL,
  `curso_materia_id` int(11) NOT NULL,
  `periodo_id` int(11) NOT NULL,
  `nota` decimal(5,2) DEFAULT 0.00,
  `nota2` decimal(5,2) DEFAULT 0.00,
  `nota3` decimal(5,2) DEFAULT 0.00,
  `definitiva` decimal(5,2) DEFAULT 0.00,
  `observaciones` text DEFAULT NULL,
  `registrado_en` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_est_mat_per` (`estudiante_id`,`curso_materia_id`,`periodo_id`),
  KEY `curso_materia_id` (`curso_materia_id`),
  KEY `periodo_id` (`periodo_id`),
  CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`curso_materia_id`) REFERENCES `curso_materia` (`id`),
  CONSTRAINT `calificaciones_ibfk_3` FOREIGN KEY (`periodo_id`) REFERENCES `periodos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calificaciones`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `calificaciones` WRITE;
/*!40000 ALTER TABLE `calificaciones` DISABLE KEYS */;
INSERT INTO `calificaciones` (`id`, `estudiante_id`, `curso_materia_id`, `periodo_id`, `nota`, `nota2`, `nota3`, `definitiva`, `observaciones`, `registrado_en`) VALUES (1,1,1,1,3.55,3.01,4.38,3.72,NULL,'2026-06-08 12:48:28'),
(2,1,2,1,2.90,3.83,2.96,3.20,NULL,'2026-06-08 12:48:28'),
(3,1,3,1,3.31,2.68,3.48,3.19,NULL,'2026-06-08 12:48:28'),
(4,1,4,1,4.33,3.71,3.07,3.64,NULL,'2026-06-08 12:48:28'),
(5,1,5,1,4.23,4.45,4.55,4.42,NULL,'2026-06-08 12:48:28'),
(6,1,6,1,4.40,3.33,3.46,3.70,NULL,'2026-06-08 12:48:28'),
(7,1,7,1,4.80,3.65,3.83,4.07,NULL,'2026-06-08 12:48:28'),
(8,1,8,1,3.20,4.53,3.03,3.53,NULL,'2026-06-08 12:48:28'),
(9,2,1,1,4.08,3.80,4.28,4.08,NULL,'2026-06-08 12:48:28'),
(10,2,2,1,4.98,4.55,2.83,3.99,NULL,'2026-06-08 12:48:28'),
(11,2,3,1,3.00,4.01,3.54,3.52,NULL,'2026-06-08 12:48:28'),
(12,2,4,1,3.17,2.72,4.10,3.41,NULL,'2026-06-08 12:48:28'),
(13,2,5,1,4.86,4.48,2.81,3.93,NULL,'2026-06-08 12:48:28'),
(14,2,6,1,3.14,4.74,4.30,4.08,NULL,'2026-06-08 12:48:28'),
(15,2,7,1,4.76,3.40,2.71,3.53,NULL,'2026-06-08 12:48:28'),
(16,2,8,1,3.35,3.64,3.16,3.36,NULL,'2026-06-08 12:48:28'),
(17,3,9,1,4.88,4.91,4.91,4.90,NULL,'2026-06-08 12:48:28'),
(18,3,10,1,4.81,4.34,4.75,4.65,NULL,'2026-06-08 12:48:28'),
(19,3,11,1,3.24,4.47,2.61,3.36,NULL,'2026-06-08 12:48:28'),
(20,3,12,1,4.64,2.86,2.87,3.40,NULL,'2026-06-08 12:48:28'),
(21,3,13,1,3.29,2.85,4.37,3.59,NULL,'2026-06-08 12:48:28'),
(22,3,14,1,3.31,3.41,4.65,3.88,NULL,'2026-06-08 12:48:28'),
(23,3,15,1,3.02,3.65,4.20,3.68,NULL,'2026-06-08 12:48:28'),
(24,3,16,1,2.53,2.57,2.76,2.63,NULL,'2026-06-08 12:48:28'),
(25,4,9,1,3.58,4.62,4.87,4.41,NULL,'2026-06-08 12:48:28'),
(26,4,10,1,2.99,2.85,2.78,2.86,NULL,'2026-06-08 12:48:28'),
(27,4,11,1,2.85,3.39,3.42,3.24,NULL,'2026-06-08 12:48:28'),
(28,4,12,1,4.41,4.29,3.22,3.90,NULL,'2026-06-08 12:48:28'),
(29,4,13,1,3.23,3.99,2.75,3.27,NULL,'2026-06-08 12:48:28'),
(30,4,14,1,4.30,3.25,3.36,3.61,NULL,'2026-06-08 12:48:28'),
(31,4,15,1,4.56,2.69,4.80,4.10,NULL,'2026-06-08 12:48:28'),
(32,4,16,1,3.41,2.67,3.09,3.06,NULL,'2026-06-08 12:48:28'),
(33,5,17,1,4.95,2.97,4.99,4.37,NULL,'2026-06-08 12:48:28'),
(34,5,18,1,3.57,2.88,3.68,3.41,NULL,'2026-06-08 12:48:28'),
(35,5,19,1,4.74,2.69,4.20,3.91,NULL,'2026-06-08 12:48:28'),
(36,5,20,1,2.95,4.64,4.37,4.03,NULL,'2026-06-08 12:48:28'),
(37,5,21,1,2.93,4.02,3.82,3.61,NULL,'2026-06-08 12:48:28'),
(38,5,22,1,4.52,3.66,4.76,4.36,NULL,'2026-06-08 12:48:28'),
(39,5,23,1,2.78,4.66,4.93,4.20,NULL,'2026-06-08 12:48:28'),
(40,5,24,1,3.16,3.52,3.13,3.26,NULL,'2026-06-08 12:48:28'),
(41,6,17,1,2.57,3.47,4.65,3.67,NULL,'2026-06-08 12:48:28'),
(42,6,18,1,2.83,2.70,2.52,2.67,NULL,'2026-06-08 12:48:28'),
(43,6,19,1,4.50,4.93,3.66,4.29,NULL,'2026-06-08 12:48:28'),
(44,6,20,1,3.51,4.07,4.81,4.20,NULL,'2026-06-08 12:48:28'),
(45,6,21,1,4.34,4.77,3.35,4.07,NULL,'2026-06-08 12:48:28'),
(46,6,22,1,4.92,4.56,3.06,4.07,NULL,'2026-06-08 12:48:28'),
(47,6,23,1,4.10,3.80,4.23,4.06,NULL,'2026-06-08 12:48:28'),
(48,6,24,1,4.75,3.55,3.49,3.89,NULL,'2026-06-08 12:48:28'),
(49,7,25,1,4.33,3.65,2.75,3.49,NULL,'2026-06-08 12:48:28'),
(50,7,26,1,2.82,3.35,3.28,3.16,NULL,'2026-06-08 12:48:28'),
(51,7,27,1,3.87,4.50,3.40,3.87,NULL,'2026-06-08 12:48:28'),
(52,7,28,1,3.49,4.77,3.36,3.82,NULL,'2026-06-08 12:48:28'),
(53,7,29,1,4.99,4.87,4.40,4.72,NULL,'2026-06-08 12:48:28'),
(54,7,30,1,4.88,3.69,3.81,4.10,NULL,'2026-06-08 12:48:28'),
(55,7,31,1,2.97,3.43,3.25,3.22,NULL,'2026-06-08 12:48:28'),
(56,7,32,1,3.44,4.98,4.55,4.35,NULL,'2026-06-08 12:48:28'),
(57,8,25,1,2.83,3.01,4.07,3.38,NULL,'2026-06-08 12:48:28'),
(58,8,26,1,3.80,4.29,2.57,3.46,NULL,'2026-06-08 12:48:28'),
(59,8,27,1,4.96,4.60,3.10,4.11,NULL,'2026-06-08 12:48:28'),
(60,8,28,1,4.23,4.35,4.05,4.19,NULL,'2026-06-08 12:48:28'),
(61,8,29,1,4.72,3.94,3.05,3.82,NULL,'2026-06-08 12:48:28'),
(62,8,30,1,3.40,2.88,4.21,3.57,NULL,'2026-06-08 12:48:28'),
(63,8,31,1,4.89,4.31,4.39,4.52,NULL,'2026-06-08 12:48:28'),
(64,8,32,1,4.01,4.39,4.92,4.49,NULL,'2026-06-08 12:48:28');
/*!40000 ALTER TABLE `calificaciones` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `curso_materia`
--

DROP TABLE IF EXISTS `curso_materia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `curso_materia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curso_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `profesor_id` int(11) DEFAULT NULL,
  `horario` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cur_mat` (`curso_id`,`materia_id`),
  KEY `materia_id` (`materia_id`),
  KEY `profesor_id` (`profesor_id`),
  CONSTRAINT `curso_materia_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `curso_materia_ibfk_2` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`),
  CONSTRAINT `curso_materia_ibfk_3` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curso_materia`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `curso_materia` WRITE;
/*!40000 ALTER TABLE `curso_materia` DISABLE KEYS */;
INSERT INTO `curso_materia` (`id`, `curso_id`, `materia_id`, `profesor_id`, `horario`) VALUES (1,1,1,1,NULL),
(2,1,2,2,NULL),
(3,1,3,3,NULL),
(4,1,4,3,NULL),
(5,1,5,4,NULL),
(6,1,6,5,NULL),
(7,1,7,2,NULL),
(8,1,8,1,NULL),
(9,2,1,1,NULL),
(10,2,2,2,NULL),
(11,2,3,3,NULL),
(12,2,4,3,NULL),
(13,2,5,4,NULL),
(14,2,6,5,NULL),
(15,2,7,2,NULL),
(16,2,8,1,NULL),
(17,3,1,1,NULL),
(18,3,2,2,NULL),
(19,3,3,3,NULL),
(20,3,4,3,NULL),
(21,3,5,4,NULL),
(22,3,6,5,NULL),
(23,3,7,2,NULL),
(24,3,8,1,NULL),
(25,4,1,1,NULL),
(26,4,2,2,NULL),
(27,4,3,3,NULL),
(28,4,4,3,NULL),
(29,4,5,4,NULL),
(30,4,6,5,NULL),
(31,4,7,2,NULL),
(32,4,8,1,NULL);
/*!40000 ALTER TABLE `curso_materia` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grado_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `anio` int(11) NOT NULL,
  `director_id` int(11) DEFAULT NULL,
  `jornada` varchar(20) DEFAULT 'Manana',
  `salon` varchar(20) DEFAULT NULL,
  `capacidad` int(11) DEFAULT 30,
  `activo` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `grado_id` (`grado_id`),
  KEY `director_id` (`director_id`),
  CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`grado_id`) REFERENCES `grados` (`id`),
  CONSTRAINT `cursos_ibfk_2` FOREIGN KEY (`director_id`) REFERENCES `profesores` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursos`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` (`id`, `grado_id`, `nombre`, `anio`, `director_id`, `jornada`, `salon`, `capacidad`, `activo`, `creado_en`) VALUES (1,3,'Primero A',2026,1,'Manana','101',30,1,'2026-06-08 12:48:28'),
(2,4,'Segundo A',2026,2,'Manana','102',30,1,'2026-06-08 12:48:28'),
(3,5,'Tercero A',2026,1,'Manana','103',30,1,'2026-06-08 12:48:28'),
(4,6,'Cuarto A',2026,3,'Manana','201',30,1,'2026-06-08 12:48:28'),
(5,7,'Quinto A',2026,4,'Manana','202',30,1,'2026-06-08 12:48:28'),
(6,8,'Sexto A',2026,5,'Tarde','301',30,1,'2026-06-08 12:48:28'),
(7,9,'Septimo A',2026,1,'Tarde','302',30,1,'2026-06-08 12:48:28'),
(8,10,'Octavo A',2026,2,'Manana','401',30,1,'2026-06-08 12:48:28'),
(9,11,'Noveno A',2026,3,'Manana','402',30,1,'2026-06-08 12:48:28'),
(10,12,'Decimo A',2026,4,'Tarde','501',30,1,'2026-06-08 12:48:28'),
(11,13,'Once A',2026,5,'Tarde','502',30,1,'2026-06-08 12:48:28');
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `estudiante_acudiente`
--

DROP TABLE IF EXISTS `estudiante_acudiente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiante_acudiente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante_id` int(11) NOT NULL,
  `acudiente_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_est_acu` (`estudiante_id`,`acudiente_id`),
  KEY `acudiente_id` (`acudiente_id`),
  CONSTRAINT `estudiante_acudiente_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estudiante_acudiente_ibfk_2` FOREIGN KEY (`acudiente_id`) REFERENCES `acudientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiante_acudiente`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `estudiante_acudiente` WRITE;
/*!40000 ALTER TABLE `estudiante_acudiente` DISABLE KEYS */;
/*!40000 ALTER TABLE `estudiante_acudiente` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `estudiantes`
--

DROP TABLE IF EXISTS `estudiantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_documento` varchar(10) DEFAULT 'TI',
  `numero_documento` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` varchar(10) DEFAULT NULL,
  `acudiente_nombre` varchar(200) DEFAULT NULL,
  `acudiente_telefono` varchar(20) DEFAULT NULL,
  `acudiente_email` varchar(150) DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `foto` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `numero_documento` (`numero_documento`),
  UNIQUE KEY `usuario_id` (`usuario_id`),
  KEY `curso_id` (`curso_id`),
  CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `estudiantes` WRITE;
/*!40000 ALTER TABLE `estudiantes` DISABLE KEYS */;
INSERT INTO `estudiantes` (`id`, `usuario_id`, `codigo`, `nombre`, `apellido`, `tipo_documento`, `numero_documento`, `email`, `telefono`, `direccion`, `fecha_nacimiento`, `genero`, `acudiente_nombre`, `acudiente_telefono`, `acudiente_email`, `curso_id`, `activo`, `foto`, `creado_en`) VALUES (1,NULL,'EST-001','Juan Pablo','Rodriguez Perez','TI','1001234567','juan.r@est.colorvida.edu.co','5550101','Calle 10 #5-20','2016-03-15','M','Carlos Rodriguez','3001111111',NULL,1,1,NULL,'2026-06-08 12:48:28'),
(2,NULL,'EST-002','Sofia Alejandra','Lopez Martinez','TI','1001234568','sofia.l@est.colorvida.edu.co','5550102','Calle 12 #6-30','2016-07-22','F','Maria Lopez','3002222222',NULL,1,1,NULL,'2026-06-08 12:48:28'),
(3,NULL,'EST-003','Daniel Andres','Garcia Ruiz','TI','1001234569','daniel.g@est.colorvida.edu.co','5550103','Carrera 8 #15-40','2015-11-08','M','Pedro Garcia','3003333333',NULL,2,1,NULL,'2026-06-08 12:48:28'),
(4,NULL,'EST-004','Maria Jose','Hernandez Diaz','TI','1001234570','mariajose.h@est.colorvida.edu.co','5550104','Av 20 #10-15','2015-05-30','F','Ana Hernandez','3004444444',NULL,2,1,NULL,'2026-06-08 12:48:28'),
(5,NULL,'EST-005','Samuel David','Torres Moreno','TI','1001234571','samuel.t@est.colorvida.edu.co','5550105','Calle 25 #12-50','2014-09-12','M','Luis Torres','3005555555',NULL,3,1,NULL,'2026-06-08 12:48:28'),
(6,NULL,'EST-006','Valentina','Ramirez Castro','TI','1001234572','valentina.r@est.colorvida.edu.co','5550106','Carrera 15 #20-10','2014-02-18','F','Carmen Ramirez','3006666666',NULL,3,1,NULL,'2026-06-08 12:48:28'),
(7,NULL,'EST-007','Sebastian','Morales Vargas','TI','1001234573','sebastian.m@est.colorvida.edu.co','5550107','Calle 30 #8-25','2013-08-25','M','Roberto Morales','3007777777',NULL,4,1,NULL,'2026-06-08 12:48:28'),
(8,NULL,'EST-008','Isabella','Rojas Guzman','TI','1001234574','isabella.r@est.colorvida.edu.co','5550108','Av 5 #22-40','2013-12-05','F','Lucia Rojas','3008888888',NULL,4,1,NULL,'2026-06-08 12:48:28'),
(9,NULL,'EST-009','Mateo Nicolas','Vega Ortiz','TI','1001234575','mateo.v@est.colorvida.edu.co','5550109','Carrera 10 #30-12','2012-04-17','M','Andres Vega','3009999999',NULL,5,1,NULL,'2026-06-08 12:48:28'),
(10,NULL,'EST-010','Camila Andrea','Castillo Pena','TI','1001234576','camila.c@est.colorvida.edu.co','5550110','Calle 40 #15-35','2012-10-01','F','Rosa Castillo','3010000000',NULL,5,1,NULL,'2026-06-08 12:48:28'),
(11,NULL,'EST-011','Alejandro','Mendoza Rios','TI','1001234577','alejandro.m@est.colorvida.edu.co','5550111','Carrera 7 #18-20','2011-06-14','M','Jorge Mendoza','3011111111',NULL,6,1,NULL,'2026-06-08 12:48:28'),
(12,NULL,'EST-012','Daniela','Flores Aguilar','TI','1001234578','daniela.f@est.colorvida.edu.co','5550112','Av 12 #25-45','2011-01-28','F','Patricia Flores','3012222222',NULL,6,1,NULL,'2026-06-08 12:48:28'),
(13,NULL,'EST-013','Nicolas','Salazar Contreras','TI','1001234579','nicolas.s@est.colorvida.edu.co','5550113','Calle 50 #9-30','2010-05-09','M','Fernando Salazar','3013333333',NULL,7,1,NULL,'2026-06-08 12:48:28'),
(14,NULL,'EST-014','Gabriela','Acosta Reyes','TI','1001234580','gabriela.a@est.colorvida.edu.co','5550114','Carrera 20 #14-60','2010-11-23','F','Elena Acosta','3014444444',NULL,7,1,NULL,'2026-06-08 12:48:28'),
(15,NULL,'EST-015','Diego Felipe','Navarro Gil','TI','1001234581','diego.n@est.colorvida.edu.co','5550115','Av 8 #35-10','2009-03-07','M','Ricardo Navarro','3015555555',NULL,8,1,NULL,'2026-06-08 12:48:28');
/*!40000 ALTER TABLE `estudiantes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `grados`
--

DROP TABLE IF EXISTS `grados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `grados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `nivel` varchar(20) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grados`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `grados` WRITE;
/*!40000 ALTER TABLE `grados` DISABLE KEYS */;
INSERT INTO `grados` (`id`, `nombre`, `descripcion`, `nivel`, `activo`) VALUES (1,'Jardin','Jardin infantil','Preescolar',1),
(2,'Transicion','Transicion','Preescolar',1),
(3,'Primero','Grado 1','Primaria',1),
(4,'Segundo','Grado 2','Primaria',1),
(5,'Tercero','Grado 3','Primaria',1),
(6,'Cuarto','Grado 4','Primaria',1),
(7,'Quinto','Grado 5','Primaria',1),
(8,'Sexto','Grado 6','Secundaria',1),
(9,'Septimo','Grado 7','Secundaria',1),
(10,'Octavo','Grado 8','Secundaria',1),
(11,'Noveno','Grado 9','Secundaria',1),
(12,'Decimo','Grado 10','Media',1),
(13,'Once','Grado 11','Media',1);
/*!40000 ALTER TABLE `grados` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `materias`
--

DROP TABLE IF EXISTS `materias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `materias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `intensidad_horaria` int(11) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materias`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `materias` WRITE;
/*!40000 ALTER TABLE `materias` DISABLE KEYS */;
INSERT INTO `materias` (`id`, `nombre`, `area`, `intensidad_horaria`, `activo`) VALUES (1,'Matematicas','Matematicas',5,1),
(2,'Espanol','Lengua Castellana',5,1),
(3,'Ingles','Idiomas',3,1),
(4,'Ciencias Naturales','Ciencias',4,1),
(5,'Ciencias Sociales','Ciencias Sociales',3,1),
(6,'Educacion Fisica','Deportes',2,1),
(7,'Educacion Artistica','Artes',2,1),
(8,'Tecnologia','Tecnologia',2,1),
(9,'Etica y Valores','Humanidades',1,1),
(10,'Religion','Humanidades',1,1),
(11,'Informatica','Tecnologia',2,1);
/*!40000 ALTER TABLE `materias` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `matriculas`
--

DROP TABLE IF EXISTS `matriculas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `matriculas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `fecha_matricula` date DEFAULT curdate(),
  `estado` varchar(20) DEFAULT 'Activa',
  `observaciones` text DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_est_anio` (`estudiante_id`,`anio`),
  KEY `curso_id` (`curso_id`),
  CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matriculas`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `matriculas` WRITE;
/*!40000 ALTER TABLE `matriculas` DISABLE KEYS */;
INSERT INTO `matriculas` (`id`, `estudiante_id`, `curso_id`, `anio`, `fecha_matricula`, `estado`, `observaciones`, `creado_en`) VALUES (1,1,1,2026,'2026-06-08','Suspendida',NULL,'2026-06-08 12:48:28'),
(2,2,1,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(3,3,2,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(4,4,2,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(5,5,3,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(6,6,3,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(7,7,4,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(8,8,4,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(9,9,5,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(10,10,5,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(11,11,6,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(12,12,6,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(13,13,7,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(14,14,7,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28'),
(15,15,8,2026,'2026-06-08','Activa',NULL,'2026-06-08 12:48:28');
/*!40000 ALTER TABLE `matriculas` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `periodos`
--

DROP TABLE IF EXISTS `periodos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `periodos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `anio` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `activo` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `periodos`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `periodos` WRITE;
/*!40000 ALTER TABLE `periodos` DISABLE KEYS */;
INSERT INTO `periodos` (`id`, `nombre`, `anio`, `numero`, `fecha_inicio`, `fecha_fin`, `activo`) VALUES (1,'Periodo 1',2026,1,'2026-01-20','2026-03-20',1),
(2,'Periodo 2',2026,2,'2026-03-23','2026-06-23',0),
(3,'Periodo 3',2026,3,'2026-07-15','2026-10-15',0),
(4,'Periodo 4',2026,4,'2026-10-20','2026-12-10',0);
/*!40000 ALTER TABLE `periodos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `profesores`
--

DROP TABLE IF EXISTS `profesores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `profesores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_documento` varchar(10) DEFAULT 'CC',
  `documento` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `especialidad` varchar(200) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_ingreso` date DEFAULT curdate(),
  `activo` tinyint(1) DEFAULT 1,
  `foto` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `documento` (`documento`),
  UNIQUE KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profesores`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `profesores` WRITE;
/*!40000 ALTER TABLE `profesores` DISABLE KEYS */;
INSERT INTO `profesores` (`id`, `usuario_id`, `codigo`, `nombre`, `apellido`, `tipo_documento`, `documento`, `email`, `telefono`, `direccion`, `especialidad`, `fecha_nacimiento`, `fecha_ingreso`, `activo`, `foto`, `creado_en`) VALUES (1,NULL,'PROF-001','Carlos Andres','Rodriguez Lopez','CC','1023456789','carlos.rodriguez@colorvida.edu.co','3001234567',NULL,'Matematicas',NULL,'2026-06-08',1,NULL,'2026-06-08 12:48:28'),
(2,NULL,'PROF-002','Maria Fernanda','Lopez Martinez','CC','1034567890','maria.lopez@colorvida.edu.co','3012345678',NULL,'Espanol',NULL,'2026-06-08',1,NULL,'2026-06-08 12:48:28'),
(3,NULL,'PROF-003','Pedro Antonio','Martinez Ruiz','CC','1045678901','pedro.martinez@colorvida.edu.co','3023456789',NULL,'Ciencias Naturales',NULL,'2026-06-08',1,NULL,'2026-06-08 12:48:28'),
(4,NULL,'PROF-004','Ana Sofia','Garcia Ruiz','CC','1056789012','ana.garcia@colorvida.edu.co','3034567890',NULL,'Ciencias Sociales',NULL,'2026-06-08',1,NULL,'2026-06-08 12:48:28'),
(5,NULL,'PROF-005','Luis Eduardo','Hernandez Diaz','CC','1067890123','luis.hernandez@colorvida.edu.co','3045678901',NULL,'Educacion Fisica',NULL,'2026-06-08',1,NULL,'2026-06-08 12:48:28');
/*!40000 ALTER TABLE `profesores` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES (1,'admin','Administrador del sistema'),
(2,'docente','Profesor/Docente'),
(3,'estudiante','Estudiante'),
(4,'acudiente','Acudiente/Padre de familia');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL DEFAULT 3,
  `foto` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `email` (`email`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `usuario`, `password`, `rol_id`, `foto`, `activo`, `creado_en`) VALUES (1,'Administrador','Sistema','admin@colorvida.edu.co','admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,NULL,1,'2026-06-08 12:48:28');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Dumping routines for database 'color_de_vida'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-06-08 15:35:05
