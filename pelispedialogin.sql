-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-11-2023 a las 16:12:46
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pelispedialogin`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios_peliculas`
--

CREATE TABLE `comentarios_peliculas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_pelicula_api` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comentarios_peliculas`
--

INSERT INTO `comentarios_peliculas` (`id`, `id_usuario`, `id_pelicula_api`, `comentario`, `fecha`) VALUES
(66, 16, 389, 'Pelicula vieja, pero muy buena ', '2023-11-15 21:23:43'),
(67, 21, 389, 'media mala', '2023-11-15 21:24:36'),
(68, 20, 389, 'franco no tiene idea de lo que dice', '2023-11-15 21:24:54'),
(69, 19, 389, 'martin tampoco, es buenisima', '2023-11-15 21:25:26'),
(87, 26, 389, 'linda pelicula', '2023-11-22 22:21:55'),
(88, 26, 1075794, 'Muy buena comedia!', '2023-11-22 22:24:23'),
(89, 25, 1075794, 'Muy corta', '2023-11-22 22:24:48'),
(94, 28, 389, 'Tremendo drama!!', '2023-11-23 14:43:40'),
(95, 28, 872585, 'Buena!', '2023-11-23 21:33:53'),
(96, 29, 872585, 'Dura mucho...', '2023-11-27 15:40:13'),
(97, 30, 671, 'Buena!', '2023-11-27 16:47:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios_series`
--

CREATE TABLE `comentarios_series` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_serie_api` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comentarios_series`
--

INSERT INTO `comentarios_series` (`id`, `id_usuario`, `id_serie_api`, `comentario`, `fecha`) VALUES
(5, 16, 1429, 'buena', '2023-11-15 21:39:33'),
(11, 22, 94605, 'muy buena', '2023-11-17 06:01:32'),
(12, 16, 134095, 'parece buena', '2023-11-17 07:17:01'),
(13, 16, 113988, 'buenisima', '2023-11-17 07:17:39'),
(14, 23, 84958, 'esta buena', '2023-11-20 23:08:59'),
(15, 16, 65494, 'horrible', '2023-11-20 23:39:32'),
(19, 16, 204082, 'Mala', '2023-11-22 21:25:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contraseñas`
--

CREATE TABLE `contraseñas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `contraseñas`
--

INSERT INTO `contraseñas` (`id`, `id_usuario`, `contraseña`) VALUES
(2, 16, '$2y$10$Jb5p.OJj7TjytSiYNEs9NeHaMKDiPY9St0cDdk4fMIY2KTyyxiTUS'),
(4, 18, '$2y$10$U3ARyLan6NS0jBIRVwKoDuRdFQ6eysDnAiN6j8rELZlVs7mQOZaDy'),
(5, 19, '$2y$10$2iVNfn/qtIZL4JV46/15Rez9Jc7qfq6lN5qaoULcDpimjzp.VZ5Oi'),
(6, 20, '$2y$10$yMvpw2AYx2DHdXtPwggXGegVzy5k0QvBlxxQQBtxfrAWW2F6DrNF6'),
(7, 21, '$2y$10$9LYvswH03pgXS8IOnABQmOEM.mNB4aoBhh3UeMLUPHK3vBWaAcWW6'),
(8, 22, '$2y$10$UzZbT3sY3F5kFFcyJof3GecW9V.i7zh533fAtINGrMLlv3EW.Qrw2'),
(9, 23, '$2y$10$R7CE4u.KWH3HEgo3jmkmFOxDhxkv91YUZ64MlHqqcC99QNywJfRc.'),
(10, 24, '$2y$10$z/FxWmUfimqWuoxL8ebbbe/xaNkng4HO5CWZ.BzuTMvme40dbkuoS'),
(11, 25, '$2y$10$RCkPIl8pGyK.EfQ8RT9KZentSxuFWBJp01L/4LsP7rxMXU.koFfXm'),
(12, 26, '$2y$10$jGkIBw6qGOi92Tq/FZlQzeyuhghKZCsufdsHI99LH/GVuZY/Zaqxi'),
(13, 27, '$2y$10$Xyc6QIT6fi4Ymlw9YLpbRuIQs.ehZ3smazWrkfC0gznvHkDnYPS7u'),
(14, 28, '$2y$10$7R.EVuTBm4WVT3vMSE7ZfOnDU5mH/Xk1EMzatn9Jy2U87TendktO2'),
(15, 29, '$2y$10$GQnlADSXP0GuJWNZPs5FtOBnZWgb6xSW7egrJih/vtHrXwOuFfdB6'),
(16, 30, '$2y$10$G/SixrnpYXpcPFcPTZnad.xaFk9DpANSyL7HHHCZLESME6mn8za82');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_pelicula_api` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`id`, `id_usuario`, `id_pelicula_api`) VALUES
(150, 16, 9339),
(156, 16, 670292),
(163, 24, 872585),
(174, 16, 1075794),
(184, 30, 507089);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos_series`
--

CREATE TABLE `favoritos_series` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_serie_api` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `favoritos_series`
--

INSERT INTO `favoritos_series` (`id`, `id_usuario`, `id_serie_api`) VALUES
(74, 16, 1429),
(75, 16, 37854),
(77, 16, 33907),
(78, 16, 213895);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`) VALUES
(16, 'franco'),
(18, 'silvia'),
(19, 'roberto'),
(20, 'martin'),
(21, 'francoemontalbano'),
(22, 'nicolas'),
(23, 'maxirodriguez'),
(24, 'nicolass'),
(25, 'tester'),
(26, 'agustin'),
(27, 'tesster'),
(28, 'ttester'),
(29, 'testerr'),
(30, 'marcelabotta');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios_peliculas`
--
ALTER TABLE `comentarios_peliculas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `comentarios_series`
--
ALTER TABLE `comentarios_series`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `contraseñas`
--
ALTER TABLE `contraseñas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `favoritos_series`
--
ALTER TABLE `favoritos_series`
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
-- AUTO_INCREMENT de la tabla `comentarios_peliculas`
--
ALTER TABLE `comentarios_peliculas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT de la tabla `comentarios_series`
--
ALTER TABLE `comentarios_series`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `contraseñas`
--
ALTER TABLE `contraseñas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT de la tabla `favoritos_series`
--
ALTER TABLE `favoritos_series`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios_peliculas`
--
ALTER TABLE `comentarios_peliculas`
  ADD CONSTRAINT `comentarios_peliculas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `comentarios_series`
--
ALTER TABLE `comentarios_series`
  ADD CONSTRAINT `comentarios_series_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `contraseñas`
--
ALTER TABLE `contraseñas`
  ADD CONSTRAINT `contraseñas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `favoritos_series`
--
ALTER TABLE `favoritos_series`
  ADD CONSTRAINT `favoritos_series_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
