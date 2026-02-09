-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-02-2026 a las 23:22:37
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cursoscp`
--
CREATE DATABASE IF NOT EXISTS `cursoscp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cursoscp`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `dni` varchar(9) NOT NULL,
  `pass` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`dni`, `pass`) VALUES
('00000000Z', '1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

DROP TABLE IF EXISTS `cursos`;
CREATE TABLE `cursos` (
  `codigo` smallint(6) NOT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `abierto` tinyint(1) NOT NULL DEFAULT 1,
  `numeroplazas` smallint(6) NOT NULL DEFAULT 20,
  `plazoinscripcion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`codigo`, `nombre`, `abierto`, `numeroplazas`, `plazoinscripcion`) VALUES
(1, 'Gestión de Residuos Sólidos Urbanos', 0, 15, '2025-12-15'),
(2, 'Sistemas de Energía Solar Fotovoltaica', 1, 30, '2026-05-10'),
(3, 'Permacultura y Diseño de Huertos Urbanos', 1, 20, '2026-04-20'),
(4, 'Conservación de la Biodiversidad Local', 1, 25, '2026-06-01'),
(5, 'Análisis de Ciclo de Vida y Ecodiseño', 0, 10, '2025-11-30'),
(6, 'Técnicas de Reforestación y Silvicultura', 1, 20, '2026-03-15'),
(7, 'Monitor de Educación Ambiental', 1, 40, '2026-07-10'),
(8, 'Auditoría Energética en Edificios', 1, 12, '2026-02-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitantes`
--

DROP TABLE IF EXISTS `solicitantes`;
CREATE TABLE `solicitantes` (
  `dni` varchar(9) NOT NULL DEFAULT '',
  `apellidos` varchar(40) NOT NULL DEFAULT '',
  `nombre` varchar(20) NOT NULL DEFAULT '',
  `telefono` varchar(12) NOT NULL DEFAULT '',
  `correo` varchar(50) NOT NULL DEFAULT '',
  `codcen` varchar(8) NOT NULL DEFAULT '',
  `coordinadortic` tinyint(1) NOT NULL DEFAULT 0,
  `grupotic` tinyint(1) NOT NULL DEFAULT 0,
  `nomgrupo` varchar(25) NOT NULL DEFAULT '',
  `pbilin` tinyint(1) NOT NULL DEFAULT 0,
  `cargo` tinyint(1) NOT NULL DEFAULT 0,
  `nombrecargo` varchar(30) NOT NULL,
  `situacion` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fechanac` date DEFAULT NULL,
  `especialidad` varchar(50) NOT NULL DEFAULT '',
  `puntos` tinyint(3) UNSIGNED DEFAULT 0,
  `pass` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `solicitantes`
--

INSERT INTO `solicitantes` (`dni`, `apellidos`, `nombre`, `telefono`, `correo`, `codcen`, `coordinadortic`, `grupotic`, `nomgrupo`, `pbilin`, `cargo`, `nombrecargo`, `situacion`, `fechanac`, `especialidad`, `puntos`, `pass`) VALUES
('00112233T', 'Carrero Fraile', 'Andres', '699111333', 'andrescarrero@gmail.com', 'CEN005', 0, 0, 'Primaria-A', 0, 0, 'Interina', 'activo', '1996-11-11', 'Primaria', 2, 'prim00'),
('11228844M', 'Luz Solar', 'Elena', '633445566', 'elena.luz@renovables.com', 'CEN022', 0, 1, 'Energía', 1, 2, 'Jefa Estudios', 'activo', '1984-02-20', 'Física', 10, 'solar84'),
('55443322Z', 'Robles Encinas', 'Laura', '655123456', 'l.robles@ecocentro.es', 'CEN020', 1, 1, 'EcoEscuela', 1, 1, 'Directora', 'activo', '1989-05-14', 'Biología', 15, 'verde89'),
('99887766R', 'Río Bravo', 'Marcos', '688987654', 'marcos.rio@nature.org', 'CEN021', 0, 0, 'Agua', 0, 0, 'Técnico Campo', 'activo', '1993-10-02', 'Ambientales', 5, 'agua93');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

DROP TABLE IF EXISTS `solicitudes`;
CREATE TABLE `solicitudes` (
  `dni` varchar(9) NOT NULL DEFAULT '',
  `codigocurso` smallint(6) NOT NULL DEFAULT 0,
  `fechasolicitud` date NOT NULL,
  `admitido` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`dni`, `codigocurso`, `fechasolicitud`, `admitido`) VALUES
('00112233T', 2, '2026-02-09', 1),
('00112233T', 6, '2026-02-09', 0),
('11228844M', 8, '2026-02-07', 1),
('55443322Z', 7, '2026-02-05', 1),
('99887766R', 1, '2026-01-20', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `solicitantes`
--
ALTER TABLE `solicitantes`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`dni`,`codigocurso`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `codigo` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
