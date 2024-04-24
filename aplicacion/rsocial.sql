-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-04-2024 a las 02:05:38
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
-- Base de datos: `rsocial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_publicacion` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_publicacion`, `id_usuario`, `contenido`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(48, 52, 38, 'Ni hablar de las horas invertidas', '2024-04-23 23:48:42', '2024-04-23 23:48:42'),
(49, 54, 36, 'No leo lloros', '2024-04-23 23:50:31', '2024-04-23 23:50:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `id_usuario`, `contenido`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(48, 36, 'Se logró señores', '2024-04-23 23:40:15', '2024-04-23 23:40:15'),
(52, 37, 'Sin comentarios hermano', '2024-04-23 23:46:24', '2024-04-23 23:46:24'),
(54, 38, 'Cada día más esquizofrénico', '2024-04-23 23:48:56', '2024-04-23 23:48:56'),
(55, 36, 'Esta gente si llora, al final quedó mamalón', '2024-04-23 23:55:10', '2024-04-23 23:55:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expira` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(255) DEFAULT NULL,
  `correo_electronico` varchar(255) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `rol` varchar(50) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `correo_electronico`, `contraseña`, `rol`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(36, 'Marlonsito', 'marlondaza2003@gmail.com', '$2y$10$wstHvdSDwnaY1oU1sZhV7uW4S5M0NULzvDb2pTb4yek6f4NCToVXu', NULL, '2024-04-23 23:38:32', '2024-04-23 23:50:02'),
(37, 'Braulio', 'barchymarch@gmail.com', '$2y$10$t5BhoJlOmXu7IqxxenA9VOOhOxdiQPzu8wkIoI4A12IqrOo/fZLLi', NULL, '2024-04-23 23:45:55', '2024-04-23 23:45:55'),
(38, 'Azarias', 'azariasgallard@gmail.com', '$2y$10$tJ9MlFWeW3BQNzHEtezzMeqa2363sv45ypHElmwgFcrbRF2FvphPq', NULL, '2024-04-23 23:47:34', '2024-04-23 23:47:34');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_publicacion` (`id_publicacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_publicacion`) REFERENCES `publicaciones` (`id`),
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
