-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2026 a las 03:44:38
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
-- Base de datos: `smart_city`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alternativa_consulta`
--

CREATE TABLE `alternativa_consulta` (
  `id_alternativa` int(11) NOT NULL,
  `orden_alternativa` int(11) NOT NULL,
  `texto_alternativa` varchar(100) NOT NULL,
  `id_consulta_votacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area_municipal`
--

CREATE TABLE `area_municipal` (
  `id_area` int(11) NOT NULL,
  `nombre_area` varchar(100) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `id_municipalidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `area_municipal`
--

INSERT INTO `area_municipal` (`id_area`, `nombre_area`, `descripcion`, `id_municipalidad`) VALUES
(1, 'Departamento de ComunicacionDepartamento de Comunicacion', 'unidad encargada ssde planificar, coordinar y ejecutar la difusión de información entre el gobierno local y la comunidad. Su objetivo principal es promover la transparencia, gestionar la imagen institucional y garantizar el acceso a las noticias de interés público.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_publicacion`
--

CREATE TABLE `categoria_publicacion` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `categoria_publicacion`
--

INSERT INTO `categoria_publicacion` (`id_categoria`, `nombre`, `id_funcionario`) VALUES
(1, 'Medio Ambiente', 1),
(2, 'Tránsito', 1),
(3, 'Ornamento', 1),
(7, 'Vivienda y urbanismo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_reporte`
--

CREATE TABLE `categoria_reporte` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `id_area_municipal` int(11) NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `categoria_reporte`
--

INSERT INTO `categoria_reporte` (`id_categoria`, `nombre_categoria`, `id_area_municipal`, `id_funcionario`) VALUES
(1, 'Ornamento', 1, 1),
(4, 'Plaza de armas', 1, 1),
(5, 'Deporte y Bienesttar', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comenta`
--

CREATE TABLE `comenta` (
  `rut_usuario` int(11) NOT NULL,
  `id_comentario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE `comentario` (
  `id_comentario` int(11) NOT NULL,
  `comentario` varchar(255) NOT NULL,
  `fecha_comentario` datetime NOT NULL,
  `id_publicacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta_votacion`
--

CREATE TABLE `consulta_votacion` (
  `id_consulta` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_termino` datetime NOT NULL,
  `pregunta` varchar(100) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `tipo_consulta` enum('consulta','votacion') NOT NULL,
  `tipo_estado` enum('activa','finalizada') NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exporta`
--

CREATE TABLE `exporta` (
  `id_log_exportacion` int(11) NOT NULL,
  `tipo_formato` enum('pdf','excel') NOT NULL,
  `fecha` datetime NOT NULL,
  `id_funcionario` int(11) NOT NULL,
  `id_reporte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcionario_municipal`
--

CREATE TABLE `funcionario_municipal` (
  `id_funcionario` int(11) NOT NULL,
  `rut_usuario` int(11) NOT NULL,
  `id_area_municipal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `funcionario_municipal`
--

INSERT INTO `funcionario_municipal` (`id_funcionario`, `rut_usuario`, `id_area_municipal`) VALUES
(1, 123459876, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipalidad`
--

CREATE TABLE `municipalidad` (
  `id_municipalidad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `comuna` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `municipalidad`
--

INSERT INTO `municipalidad` (`id_municipalidad`, `nombre`, `comuna`, `region`, `direccion`, `telefono`) VALUES
(1, 'Municipalidad de Concepción', 'Concepción', 'Biobío', 'O\'Higgins 525', 412266500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `negocio_local`
--

CREATE TABLE `negocio_local` (
  `id_negocio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` int(11) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `redes_sociales` varchar(100) DEFAULT NULL,
  `horario_atencion` varchar(100) NOT NULL,
  `imagenes` varchar(100) NOT NULL,
  `tipo_estado` enum('pendiente a aprobacion','rechazado','aprobado') NOT NULL,
  `id_revision` int(11) NOT NULL,
  `id_rubro` int(11) NOT NULL,
  `id_sector` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participa`
--

CREATE TABLE `participa` (
  `id_participacion` int(11) NOT NULL,
  `rut_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participacion`
--

CREATE TABLE `participacion` (
  `id_participacion` int(11) NOT NULL,
  `fecha_participacion` datetime NOT NULL,
  `id_voto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `nombre_permiso` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posee`
--

CREATE TABLE `posee` (
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicacion`
--

CREATE TABLE `publicacion` (
  `id_publicacion` int(11) NOT NULL,
  `contenido` varchar(255) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_evento` datetime DEFAULT NULL,
  `tipo_estado` enum('activa','desactivada') NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `visitas` int(11) DEFAULT NULL,
  `id_funcionario` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `publicacion`
--

INSERT INTO `publicacion` (`id_publicacion`, `contenido`, `titulo`, `fecha`, `fecha_evento`, `tipo_estado`, `lugar`, `imagen`, `visitas`, `id_funcionario`, `id_categoria`) VALUES
(1, 'La Municipalidad informa a la comunidad que este sábado 20 de junio se realizará un operativo de reciclaje electrónico en la Plaza de Armas desde las 09:00 hasta las 14:00 horas.\n', 'Operativo de reciclaje electrónico este sábado', '2026-06-18 16:00:00', '2026-06-20 09:00:00', 'activa', 'Plaza de armas', 'medio_ambiente.jpg', NULL, 1, 1),
(2, 'Se informa a los vecinos que el próximo domingo se realizarán cortes de tránsito temporales en las calles principales debido al desfile aniversario de la comuna.', 'Corte programado de tránsito por desfile comunal', '2026-06-18 16:00:00', NULL, 'activa', 'Calles principales', 'corte_transito.jpg', NULL, 1, 2),
(3, 'Publicacion de prueba , Incremento 3 de Tis.', 'Publicacion Prueba', '2026-07-06 01:09:19', '2026-07-12 21:09:00', 'activa', 'UCSC', 'imagen_1.jpg', 0, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reaccion`
--

CREATE TABLE `reaccion` (
  `id_reaccion` int(11) NOT NULL,
  `tipo_reaccion` enum('me gusta','me encanta','no me gusta','me divierte') NOT NULL,
  `id_publicacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `reaccion`
--

INSERT INTO `reaccion` (`id_reaccion`, `tipo_reaccion`, `id_publicacion`) VALUES
(1, 'me gusta', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reacciona`
--

CREATE TABLE `reacciona` (
  `rut_usuario` int(11) NOT NULL,
  `id_reaccion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte`
--

CREATE TABLE `reporte` (
  `id_reporte` int(11) NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `latitud` varchar(100) NOT NULL,
  `longitud` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `tipo_estado` enum('pendiente','rechazado','en proceso','resuelto') NOT NULL DEFAULT 'pendiente',
  `rut_usuario` int(11) NOT NULL,
  `id_categoria_reporte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `reporte`
--

INSERT INTO `reporte` (`id_reporte`, `imagen`, `fecha`, `latitud`, `longitud`, `descripcion`, `tipo_estado`, `rut_usuario`, `id_categoria_reporte`) VALUES
(1, 'imagen_1.jpg', '2026-07-06 16:09:55', '', '', 'Perro callejero mordio a una persona', 'en proceso', 20630531, 4),
(2, 'imagen_2.jpg', '2026-07-06 16:12:31', '', '', 'Te a mordido un perro', 'rechazado', 20630531, 4),
(3, 'dsada', '2026-07-06 16:12:51', '', '', 'Te amordido un poerro', 'pendiente', 20630531, 4),
(4, 'asdasda', '2026-07-06 16:17:37', '', '', 'AAAhhh', 'pendiente', 20630531, 5),
(5, 'imagen_2.jpg', '2026-07-06 16:19:14', '-36.8335', '-73.0487', 'Ahora si', 'resuelto', 20630531, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `revision_negocio`
--

CREATE TABLE `revision_negocio` (
  `id_revision` int(11) NOT NULL,
  `tipo_estado` enum('aprobado','rechazado') NOT NULL,
  `observacion` varchar(100) DEFAULT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`) VALUES
(3, 'Administrador Municipal'),
(1, 'Ciudadano'),
(2, 'Encargado de Comunicaciones');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubro`
--

CREATE TABLE `rubro` (
  `id_rubro` int(11) NOT NULL,
  `nombre_rubro` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sector`
--

CREATE TABLE `sector` (
  `id_sector` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_municipalidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `sector`
--

INSERT INTO `sector` (`id_sector`, `nombre`, `id_municipalidad`) VALUES
(1, 'Sector Sur', 1),
(2, 'Sector Villa Cruz', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento_reporte`
--

CREATE TABLE `seguimiento_reporte` (
  `id_seguimiento` int(11) NOT NULL,
  `observacion` varchar(100) NOT NULL,
  `imagen_evidencia` varchar(100) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo_estado` enum('en proceso','resuelto','rechazado') NOT NULL,
  `id_funcionario` int(11) NOT NULL,
  `id_reporte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `seguimiento_reporte`
--

INSERT INTO `seguimiento_reporte` (`id_seguimiento`, `observacion`, `imagen_evidencia`, `fecha`, `tipo_estado`, `id_funcionario`, `id_reporte`) VALUES
(1, 'Hemos observado y no haremos nada', 'XD', '2026-07-07 20:38:07', 'rechazado', 1, 4),
(2, 'Hola', 'XD', '2026-07-07 20:38:58', 'resuelto', 1, 2),
(3, 'as', 'as', '2026-07-07 20:45:50', 'rechazado', 1, 2),
(4, 'Efectivamente , la persona murio de rabia', 'XD', '2026-07-07 20:46:11', 'en proceso', 1, 1),
(5, 'as', 'XD', '2026-07-07 20:46:43', '', 1, 5),
(6, 'sa', 'as', '2026-07-07 20:46:51', 'en proceso', 1, 5),
(7, 'as', 'as', '2026-07-07 20:46:59', 'rechazado', 1, 5),
(8, 'as', 'as', '2026-07-07 20:47:10', 'resuelto', 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesion`
--

CREATE TABLE `sesion` (
  `id_sesion` int(11) NOT NULL,
  `token_sesion` varchar(100) NOT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_termino` datetime DEFAULT NULL,
  `tipo_sesion` enum('activa','inactiva') NOT NULL,
  `rut_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `rut` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `contrasenha` varchar(100) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_sector` int(11) NOT NULL,
  `id_negocio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`rut`, `nombre`, `apellido`, `correo`, `direccion`, `contrasenha`, `id_rol`, `id_sector`, `id_negocio`) VALUES
(123456789, 'Pedro', 'Sanchez', 'usuario@gmail.com', 'Av. Pedro de Valdivia 995', 'user1234', 1, 1, NULL),
(123459876, 'Diego', 'Muñoz', 'admin@gmail.com', 'Av. Pedro de Valdivia 995', 'admin1234', 3, 1, NULL),
(987654321, 'Juan', 'Fernandez', 'funcionario@gmail.com', 'Av. Pedro de Valdivia 995', 'funcionario1234', 2, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voto`
--

CREATE TABLE `voto` (
  `id_voto` int(11) NOT NULL,
  `fecha_voto` datetime NOT NULL,
  `id_alternativa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alternativa_consulta`
--
ALTER TABLE `alternativa_consulta`
  ADD PRIMARY KEY (`id_alternativa`),
  ADD KEY `alternativa_consulta_consulta_votacion_FK` (`id_consulta_votacion`);

--
-- Indices de la tabla `area_municipal`
--
ALTER TABLE `area_municipal`
  ADD PRIMARY KEY (`id_area`),
  ADD UNIQUE KEY `area_municipal_unique` (`nombre_area`),
  ADD KEY `area_municipal_municipalidad_FK` (`id_municipalidad`);

--
-- Indices de la tabla `categoria_publicacion`
--
ALTER TABLE `categoria_publicacion`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `categoria_publicacion_unique` (`nombre`),
  ADD KEY `categoria_publicacion_funcionario_municipal_FK` (`id_funcionario`);

--
-- Indices de la tabla `categoria_reporte`
--
ALTER TABLE `categoria_reporte`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `categoria_reporte_unique` (`nombre_categoria`),
  ADD KEY `categoria_reporte_funcionario_municipal_FK` (`id_funcionario`),
  ADD KEY `categoria_reporte_area_municipal_FK` (`id_area_municipal`);

--
-- Indices de la tabla `comenta`
--
ALTER TABLE `comenta`
  ADD PRIMARY KEY (`rut_usuario`,`id_comentario`),
  ADD KEY `comenta_comentario_FK` (`id_comentario`);

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `comentario_publicacion_FK` (`id_publicacion`);

--
-- Indices de la tabla `consulta_votacion`
--
ALTER TABLE `consulta_votacion`
  ADD PRIMARY KEY (`id_consulta`),
  ADD KEY `consulta_votacion_funcionario_municipal_FK` (`id_funcionario`);

--
-- Indices de la tabla `exporta`
--
ALTER TABLE `exporta`
  ADD PRIMARY KEY (`id_log_exportacion`),
  ADD KEY `exporta_reporte_FK` (`id_reporte`),
  ADD KEY `exporta_funcionario_municipal_FK` (`id_funcionario`);

--
-- Indices de la tabla `funcionario_municipal`
--
ALTER TABLE `funcionario_municipal`
  ADD PRIMARY KEY (`id_funcionario`),
  ADD KEY `funcionario_municipal_usuario_FK` (`rut_usuario`),
  ADD KEY `funcionario_municipal_area_municipal_FK` (`id_area_municipal`);

--
-- Indices de la tabla `municipalidad`
--
ALTER TABLE `municipalidad`
  ADD PRIMARY KEY (`id_municipalidad`);

--
-- Indices de la tabla `negocio_local`
--
ALTER TABLE `negocio_local`
  ADD PRIMARY KEY (`id_negocio`),
  ADD KEY `negocio_local_revision_negocio_FK` (`id_revision`),
  ADD KEY `negocio_local_rubro_FK` (`id_rubro`),
  ADD KEY `negocio_local_sector_FK` (`id_sector`);

--
-- Indices de la tabla `participa`
--
ALTER TABLE `participa`
  ADD PRIMARY KEY (`id_participacion`,`rut_usuario`),
  ADD KEY `participa_usuario_FK` (`rut_usuario`);

--
-- Indices de la tabla `participacion`
--
ALTER TABLE `participacion`
  ADD PRIMARY KEY (`id_participacion`),
  ADD KEY `participacion_voto_FK` (`id_voto`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id_permiso`),
  ADD UNIQUE KEY `NewTable_UNIQUE` (`nombre_permiso`);

--
-- Indices de la tabla `posee`
--
ALTER TABLE `posee`
  ADD PRIMARY KEY (`id_rol`,`id_permiso`),
  ADD KEY `posee_permiso_FK` (`id_permiso`);

--
-- Indices de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD PRIMARY KEY (`id_publicacion`),
  ADD KEY `publicacion_funcionario_municipal_FK` (`id_funcionario`),
  ADD KEY `publicacion_categoria_publicacion_FK` (`id_categoria`);

--
-- Indices de la tabla `reaccion`
--
ALTER TABLE `reaccion`
  ADD PRIMARY KEY (`id_reaccion`),
  ADD KEY `reaccion_publicacion_FK` (`id_publicacion`);

--
-- Indices de la tabla `reacciona`
--
ALTER TABLE `reacciona`
  ADD PRIMARY KEY (`rut_usuario`,`id_reaccion`),
  ADD KEY `reacciona_reaccion_FK` (`id_reaccion`);

--
-- Indices de la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `reporte_categoria_reporte_FK` (`id_categoria_reporte`);

--
-- Indices de la tabla `revision_negocio`
--
ALTER TABLE `revision_negocio`
  ADD PRIMARY KEY (`id_revision`),
  ADD KEY `revision_negocio_funcionario_municipal_FK` (`id_funcionario`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `rol_unique` (`nombre_rol`);

--
-- Indices de la tabla `rubro`
--
ALTER TABLE `rubro`
  ADD PRIMARY KEY (`id_rubro`);

--
-- Indices de la tabla `sector`
--
ALTER TABLE `sector`
  ADD PRIMARY KEY (`id_sector`),
  ADD UNIQUE KEY `sector_unique` (`nombre`),
  ADD KEY `sector_municipalidad_FK` (`id_municipalidad`);

--
-- Indices de la tabla `seguimiento_reporte`
--
ALTER TABLE `seguimiento_reporte`
  ADD PRIMARY KEY (`id_seguimiento`),
  ADD KEY `seguimiento_reporte_funcionario_municipal_FK` (`id_funcionario`),
  ADD KEY `seguimiento_reporte_reporte_FK` (`id_reporte`);

--
-- Indices de la tabla `sesion`
--
ALTER TABLE `sesion`
  ADD PRIMARY KEY (`id_sesion`),
  ADD KEY `sesion_usuario_FK` (`rut_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`rut`),
  ADD UNIQUE KEY `usuario_unique` (`correo`),
  ADD KEY `usuario_sector_FK` (`id_sector`),
  ADD KEY `usuario_rol_FK` (`id_rol`),
  ADD KEY `usuario_negocio_local_FK` (`id_negocio`);

--
-- Indices de la tabla `voto`
--
ALTER TABLE `voto`
  ADD PRIMARY KEY (`id_voto`),
  ADD KEY `voto_alternativa_consulta_FK` (`id_alternativa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alternativa_consulta`
--
ALTER TABLE `alternativa_consulta`
  MODIFY `id_alternativa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `area_municipal`
--
ALTER TABLE `area_municipal`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categoria_publicacion`
--
ALTER TABLE `categoria_publicacion`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `categoria_reporte`
--
ALTER TABLE `categoria_reporte`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `consulta_votacion`
--
ALTER TABLE `consulta_votacion`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `exporta`
--
ALTER TABLE `exporta`
  MODIFY `id_log_exportacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `funcionario_municipal`
--
ALTER TABLE `funcionario_municipal`
  MODIFY `id_funcionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `municipalidad`
--
ALTER TABLE `municipalidad`
  MODIFY `id_municipalidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `negocio_local`
--
ALTER TABLE `negocio_local`
  MODIFY `id_negocio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `participacion`
--
ALTER TABLE `participacion`
  MODIFY `id_participacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  MODIFY `id_publicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reaccion`
--
ALTER TABLE `reaccion`
  MODIFY `id_reaccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reporte`
--
ALTER TABLE `reporte`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `revision_negocio`
--
ALTER TABLE `revision_negocio`
  MODIFY `id_revision` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rubro`
--
ALTER TABLE `rubro`
  MODIFY `id_rubro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sector`
--
ALTER TABLE `sector`
  MODIFY `id_sector` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `seguimiento_reporte`
--
ALTER TABLE `seguimiento_reporte`
  MODIFY `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sesion`
--
ALTER TABLE `sesion`
  MODIFY `id_sesion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `voto`
--
ALTER TABLE `voto`
  MODIFY `id_voto` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alternativa_consulta`
--
ALTER TABLE `alternativa_consulta`
  ADD CONSTRAINT `alternativa_consulta_consulta_votacion_FK` FOREIGN KEY (`id_consulta_votacion`) REFERENCES `consulta_votacion` (`id_consulta`);

--
-- Filtros para la tabla `area_municipal`
--
ALTER TABLE `area_municipal`
  ADD CONSTRAINT `area_municipal_municipalidad_FK` FOREIGN KEY (`id_municipalidad`) REFERENCES `municipalidad` (`id_municipalidad`);

--
-- Filtros para la tabla `categoria_publicacion`
--
ALTER TABLE `categoria_publicacion`
  ADD CONSTRAINT `categoria_publicacion_funcionario_municipal_FK` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario_municipal` (`id_funcionario`);

--
-- Filtros para la tabla `categoria_reporte`
--
ALTER TABLE `categoria_reporte`
  ADD CONSTRAINT `categoria_reporte_area_municipal_FK` FOREIGN KEY (`id_area_municipal`) REFERENCES `area_municipal` (`id_area`),
  ADD CONSTRAINT `categoria_reporte_funcionario_municipal_FK` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario_municipal` (`id_funcionario`);

--
-- Filtros para la tabla `comenta`
--
ALTER TABLE `comenta`
  ADD CONSTRAINT `comenta_comentario_FK` FOREIGN KEY (`id_comentario`) REFERENCES `comentario` (`id_comentario`),
  ADD CONSTRAINT `comenta_usuario_FK` FOREIGN KEY (`rut_usuario`) REFERENCES `usuario` (`rut`);

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `comentario_publicacion_FK` FOREIGN KEY (`id_publicacion`) REFERENCES `publicacion` (`id_publicacion`);

--
-- Filtros para la tabla `consulta_votacion`
--
ALTER TABLE `consulta_votacion`
  ADD CONSTRAINT `consulta_votacion_funcionario_municipal_FK` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario_municipal` (`id_funcionario`);

--
-- Filtros para la tabla `exporta`
--
ALTER TABLE `exporta`
  ADD CONSTRAINT `exporta_funcionario_municipal_FK` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario_municipal` (`id_funcionario`),
  ADD CONSTRAINT `exporta_reporte_FK` FOREIGN KEY (`id_reporte`) REFERENCES `reporte` (`id_reporte`);

--
-- Filtros para la tabla `funcionario_municipal`
--
ALTER TABLE `funcionario_municipal`
  ADD CONSTRAINT `funcionario_municipal_area_municipal_FK` FOREIGN KEY (`id_area_municipal`) REFERENCES `area_municipal` (`id_area`),
  ADD CONSTRAINT `funcionario_municipal_usuario_FK` FOREIGN KEY (`rut_usuario`) REFERENCES `usuario` (`rut`);

--
-- Filtros para la tabla `negocio_local`
--
ALTER TABLE `negocio_local`
  ADD CONSTRAINT `negocio_local_revision_negocio_FK` FOREIGN KEY (`id_revision`) REFERENCES `revision_negocio` (`id_revision`),
  ADD CONSTRAINT `negocio_local_rubro_FK` FOREIGN KEY (`id_rubro`) REFERENCES `rubro` (`id_rubro`),
  ADD CONSTRAINT `negocio_local_sector_FK` FOREIGN KEY (`id_sector`) REFERENCES `sector` (`id_sector`);

--
-- Filtros para la tabla `participa`
--
ALTER TABLE `participa`
  ADD CONSTRAINT `participa_participacion_FK` FOREIGN KEY (`id_participacion`) REFERENCES `participacion` (`id_participacion`),
  ADD CONSTRAINT `participa_usuario_FK` FOREIGN KEY (`rut_usuario`) REFERENCES `usuario` (`rut`);

--
-- Filtros para la tabla `participacion`
--
ALTER TABLE `participacion`
  ADD CONSTRAINT `participacion_voto_FK` FOREIGN KEY (`id_voto`) REFERENCES `voto` (`id_voto`);

--
-- Filtros para la tabla `posee`
--
ALTER TABLE `posee`
  ADD CONSTRAINT `posee_permiso_FK` FOREIGN KEY (`id_permiso`) REFERENCES `permiso` (`id_permiso`),
  ADD CONSTRAINT `posee_rol_FK` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);

--
-- Filtros para la tabla `publicacion`
--
ALTER TABLE `publicacion`
  ADD CONSTRAINT `publicacion_categoria_publicacion_FK` FOREIGN KEY (`id_categoria`) REFERENCES `categoria_publicacion` (`id_categoria`),
  ADD CONSTRAINT `publicacion_funcionario_municipal_FK` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario_municipal` (`id_funcionario`);

--
-- Filtros para la tabla `reaccion`
--
ALTER TABLE `reaccion`
  ADD CONSTRAINT `reaccion_publicacion_FK` FOREIGN KEY (`id_publicacion`) REFERENCES `publicacion` (`id_publicacion`);

--
-- Filtros para la tabla `reacciona`
--
ALTER TABLE `reacciona`
  ADD CONSTRAINT `reacciona_reaccion_FK` FOREIGN KEY (`id_reaccion`) REFERENCES `reaccion` (`id_reaccion`),
  ADD CONSTRAINT `reacciona_usuario_FK` FOREIGN KEY (`rut_usuario`) REFERENCES `usuario` (`rut`);

--
-- Filtros para la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD CONSTRAINT `reporte_categoria_reporte_FK` FOREIGN KEY (`id_categoria_reporte`) REFERENCES `categoria_reporte` (`id_categoria`);

--
-- Filtros para la tabla `revision_negocio`
--
ALTER TABLE `revision_negocio`
  ADD CONSTRAINT `revision_negocio_funcionario_municipal_FK` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario_municipal` (`id_funcionario`);

--
-- Filtros para la tabla `sector`
--
ALTER TABLE `sector`
  ADD CONSTRAINT `sector_municipalidad_FK` FOREIGN KEY (`id_municipalidad`) REFERENCES `municipalidad` (`id_municipalidad`);

--
-- Filtros para la tabla `seguimiento_reporte`
--
ALTER TABLE `seguimiento_reporte`
  ADD CONSTRAINT `seguimiento_reporte_funcionario_municipal_FK` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario_municipal` (`id_funcionario`),
  ADD CONSTRAINT `seguimiento_reporte_reporte_FK` FOREIGN KEY (`id_reporte`) REFERENCES `reporte` (`id_reporte`);

--
-- Filtros para la tabla `sesion`
--
ALTER TABLE `sesion`
  ADD CONSTRAINT `sesion_usuario_FK` FOREIGN KEY (`rut_usuario`) REFERENCES `usuario` (`rut`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_negocio_local_FK` FOREIGN KEY (`id_negocio`) REFERENCES `negocio_local` (`id_negocio`),
  ADD CONSTRAINT `usuario_rol_FK` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`),
  ADD CONSTRAINT `usuario_sector_FK` FOREIGN KEY (`id_sector`) REFERENCES `sector` (`id_sector`);

--
-- Filtros para la tabla `voto`
--
ALTER TABLE `voto`
  ADD CONSTRAINT `voto_alternativa_consulta_FK` FOREIGN KEY (`id_alternativa`) REFERENCES `alternativa_consulta` (`id_alternativa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
