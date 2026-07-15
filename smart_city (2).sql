-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 15-07-2026 a las 19:46:06
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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
(1, 'Departamento de Comunicacion', 'unidad encargada de planificar, coordinar y ejecutar la difusión de información entre el gobierno local y la comunidad. Su objetivo principal es promover la transparencia, gestionar la imagen institucional y garantizar el acceso a las noticias de interés público.', 1),
(2, 'Departamento de Comercio Local', 'El espacio ideal para encontrar todo lo que necesitas a solo unos pasos. Apoya a los emprendedores locales, disfruta de una atención cálida y personalizada, y sé parte activa del crecimiento de tu comunidad.', 1),
(3, 'Departamento de Reportes Ciudadanos', 'El Área de Reportes Ciudadanos es el canal oficial de la administración dedicado a recibir, canalizar y dar seguimiento a las solicitudes de la comunidad. Nuestro compromiso es garantizar una respuesta oportuna a los requerimientos sobre servicios públicos, infraestructura y seguridad, fomentando la participación activa y la transparencia.', 1),
(4, 'Departamento de Participación Ciudadana', 'Promovemos el involucramiento activo y la incidencia de la comunidad en la gestión pública. Somos el puente entre las instituciones y los vecinos, garantizando espacios de diálogo, consultas ciudadanas y rendición de cuentas para que las decisiones reflejen las necesidades reales de la sociedad civil.', 1);

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
(7, 'Vivienda y urbanismo', 1),
(8, '123', 1);

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
(1, 'Infraestructura y Vialidad Urbana', 1, 1),
(4, 'Medio Ambiente, Aseo y Ornato', 1, 1),
(5, 'Salud Pública y Tenencia Responsable', 1, 1),
(6, 'Fiscalización y Ordenamiento Comunitario', 1, 1),
(7, 'Seguridad Ciudadana', 1, 1),
(8, 'Emergencias Sanitarias y Aguas', 1, 1),
(9, 'Tránsito y Movilidad Urbana', 1, 1),
(10, 'Infraestructura Comunitaria y Patrimonio', 1, 1),
(11, 'Comercio y Rentas (Fiscalización)', 1, 1),
(12, 'Gestión de Riesgos y Desastres (Preventivo)', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comenta`
--

CREATE TABLE `comenta` (
  `rut_usuario` int(11) NOT NULL,
  `id_comentario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `comenta`
--

INSERT INTO `comenta` (`rut_usuario`, `id_comentario`) VALUES
(123456789, 1);

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

--
-- Volcado de datos para la tabla `comentario`
--

INSERT INTO `comentario` (`id_comentario`, `comentario`, `fecha_comentario`, `id_publicacion`) VALUES
(1, 'asdsda', '2026-07-10 00:44:17', 1);

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
-- Estructura de tabla para la tabla `imagenes_negocios`
--

CREATE TABLE `imagenes_negocios` (
  `id_image` int(11) NOT NULL,
  `id_negocio` int(11) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `imagenes_negocios`
--

INSERT INTO `imagenes_negocios` (`id_image`, `id_negocio`, `ruta_imagen`) VALUES
(11, 20, '1784132605_0.jpg'),
(12, 20, '1784132605_1.jpeg'),
(13, 21, '1784132738_0.jpeg'),
(14, 21, '1784132738_1.jpg'),
(15, 22, '1784132883_0.webp'),
(16, 22, '1784132883_1.jpeg');

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
  `descripcion` varchar(255) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `whatsapp` varchar(100) DEFAULT NULL,
  `dias_abierto` varchar(100) NOT NULL,
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL,
  `tipo_estado` enum('pendiente a aprobacion','rechazado','aprobado') NOT NULL DEFAULT 'pendiente a aprobacion',
  `id_revision` int(11) DEFAULT NULL,
  `id_rubro` int(11) NOT NULL,
  `id_sector` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `negocio_local`
--

INSERT INTO `negocio_local` (`id_negocio`, `nombre`, `descripcion`, `direccion`, `correo_electronico`, `instagram`, `facebook`, `whatsapp`, `dias_abierto`, `hora_apertura`, `hora_cierre`, `tipo_estado`, `id_revision`, `id_rubro`, `id_sector`) VALUES
(20, 'Café Altura', 'Café de especialidad tostado en grano, pastelería artesanal con opciones veganas y el mejor ambiente para trabajar o compartir con amigos.', 'O\'Higgins 450, Local 3', 'contacto@cafealtura.cl', 'cafe.altura', '', '912345678', 'Lunes a Sábado', '08:30:00', '21:00:00', 'aprobado', 1, 1, 1),
(21, 'Almacén El Vecino', 'Tu almacén de barrio con frutas y verduras frescas todos los días, abarrotes, pan batido calientito por las tardes y artículos de aseo para el hogar.', 'Av. Los Alerces 1240', 'almacenelvecino@gmail.com', '', 'AlmacenElVecino', '987654321', 'Lunes a Domingo', '09:00:00', '22:00:00', 'aprobado', 2, 2, 2),
(22, 'Pizzería Bella Noche', 'Pizzas artesanales a la piedra preparadas con masa madre. Ingredientes seleccionados, ricas combinaciones y despacho a domicilio rápido en toda la comuna.', 'Arturo Prat 890', 'ventas@bellanoche.cl', 'bellanoche.pizza', 'PizzeriaBellaNoche', '955443322', 'Lunes a Sábado', '18:00:00', '22:30:00', 'pendiente a aprobacion', NULL, 3, 1),
(23, 'PetShop Huellitas', 'Alimentos premium para perros y gatos, accesorios, juguetes, ropa y farmacia veterinaria. ¡Atendido por profesionales amantes de los animales!', 'Maipú 315', 'contacto@huellitaspet.cl', 'huellitas_petshop', '', '966778899', 'Lunes a Viernes', '10:00:00', '18:00:00', 'pendiente a aprobacion', NULL, 4, 2);

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
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id_reset` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `nombre_permiso` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id_permiso`, `nombre_permiso`) VALUES
(32, 'admin_total'),
(30, 'control_comercio'),
(28, 'control_reportes'),
(31, 'gestion_emprendedor'),
(27, 'gestion_publicaciones'),
(29, 'gestion_votaciones'),
(24, 'interaccion_ciudadana'),
(33, 'modulo_comercio_local'),
(25, 'reportes_ciudadanos'),
(26, 'votar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posee`
--

CREATE TABLE `posee` (
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `posee`
--

INSERT INTO `posee` (`id_rol`, `id_permiso`) VALUES
(1, 24),
(1, 26),
(1, 30),
(2, 24),
(2, 27),
(3, 24),
(3, 32),
(4, 24),
(4, 26),
(4, 31),
(5, 24),
(5, 28),
(6, 24),
(6, 29),
(7, 24),
(7, 30);

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
(3, 'Publicacion de prueba , Incremento 3 de Tis.', 'Publicacion Prueba', '2026-07-15 15:20:06', '2026-07-22 17:19:00', 'activa', 'UCSCC', 'imagen_1.jpg', 0, 1, 2);

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
(0, 'me encanta', 1),
(4, 'me encanta', 3);

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
  `id_categoria_reporte` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `reporte`
--

INSERT INTO `reporte` (`id_reporte`, `imagen`, `fecha`, `latitud`, `longitud`, `descripcion`, `tipo_estado`, `rut_usuario`, `id_categoria_reporte`, `titulo`) VALUES
(1, 'imagen_1.jpg', '2026-07-06 16:09:55', '12', '13', 'Perro callejero mordio a una persona', 'pendiente', 20630531, 5, 'perro callejero suelto'),
(2, 'imagen_2.jpg', '2026-07-06 16:12:31', '12', '13', 'jauria de perros', 'pendiente', 20630531, 5, 'arbol en peligro de caer'),
(3, 'imagen_3.jpg', '2026-07-06 16:12:51', '12', '13', 'arbol en peligro de caer se encuentra al lado de la calle', 'rechazado', 20630531, 12, 'arbol peligroso'),
(4, 'imagen_4.jpg', '2026-07-06 16:17:37', '12', '13', 'auto mal estacionado no permite el paso de peatones', 'en proceso', 20630531, 1, 'auto mal estacionado'),
(5, 'imagen_5.jpg', '2026-07-06 16:19:14', '-37.8335', '-29.0387', 'personas sospechosas en auto van dando vueltas ya llevan 50 minutos', 'en proceso', 20630531, 7, 'personas sospechosas');

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

--
-- Volcado de datos para la tabla `revision_negocio`
--

INSERT INTO `revision_negocio` (`id_revision`, `tipo_estado`, `observacion`, `id_funcionario`) VALUES
(1, 'aprobado', NULL, 1),
(2, 'aprobado', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(100) NOT NULL,
  `tipo_interfaz` enum('externo','interno') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`, `tipo_interfaz`) VALUES
(1, 'Ciudadano', 'externo'),
(2, 'Encargado de Comunicaciones', 'interno'),
(3, 'Administrador Municipal', 'interno'),
(4, 'Emprendedor', 'externo'),
(5, 'Encargado de Reportes Municipales', 'interno'),
(6, 'Encargado de Participación Ciudadana', 'interno'),
(7, 'Encargado de Comercio Local', 'interno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubro`
--

CREATE TABLE `rubro` (
  `id_rubro` int(11) NOT NULL,
  `nombre_rubro` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `rubro`
--

INSERT INTO `rubro` (`id_rubro`, `nombre_rubro`) VALUES
(1, 'Cafetería y Pasteleria'),
(2, 'MiniMarket'),
(3, 'Restaurante'),
(4, 'Veterinaria y Mascotas');

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
(11, 'se ha observado y se trabajara en ello', 'imagen_1.jpg', '2026-07-09 04:23:34', 'en proceso', 1, 5),
(12, 'se ha llamado a la grua para retirar dicho vehiculo', 'imagen_1.jpg', '2026-07-09 04:25:19', 'en proceso', 1, 4),
(13, 'no se considera que el arbol represente algun peligro para la ciudadania', 'imagen_1.jpg', '2026-07-09 04:25:55', 'rechazado', 1, 3);

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

--
-- Volcado de datos para la tabla `sesion`
--

INSERT INTO `sesion` (`id_sesion`, `token_sesion`, `fecha_inicio`, `fecha_termino`, `tipo_sesion`, `rut_usuario`) VALUES
(1, '7436f929a68408af894cfb7911941eb1f4e41bf35857adb1cd9d8338e3a45c23', '2026-07-09 22:40:25', '2026-08-08 22:40:25', 'activa', 123459876),
(2, '8a4da72c80bb6668c4d595ca57a22cc6c1b3bb919670f9ebc104e88373fb62c6', '2026-07-15 16:55:16', '2026-08-14 16:55:16', 'activa', 123459876),
(3, '75c0c20bf36da77b6c172037df5f36371acf098981faed03ba4393acde3885e2', '2026-07-15 17:37:59', '2026-08-14 17:37:59', 'activa', 123459876),
(4, '5f60feea750391bc7f257859b975e55492b9406ec1766cb9f5c2721633099f3f', '2026-07-15 18:29:46', '2026-08-14 18:29:46', 'activa', 123459876);

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
  `id_negocio` int(11) DEFAULT NULL,
  `email_verificado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`rut`, `nombre`, `apellido`, `correo`, `direccion`, `contrasenha`, `id_rol`, `id_sector`, `id_negocio`, `email_verificado`) VALUES
(21053105, 'Benjam�n', 'Antonio Cisternas Villarroel', 'bcisternas@ing.ucsc.cl', 'Los carrera, 1234', '$2y$10$zRc0DYjhnWP5mwzFMlQMvuAGIs7KJPwKN4asuUM2wrO8eLodQqzc6', 1, 1, NULL, 0),
(123456789, 'Pedro', 'Sanchez', 'usuario@gmail.com', 'Av. Pedro de Valdivia 995', 'user1234', 1, 1, NULL, 0),
(123459876, 'Diego', 'Muñoz', 'beenjaacv@gmail.com', 'Av. Pedro de Valdivia 995', 'admin1234', 3, 1, NULL, 1);

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
-- Indices de la tabla `imagenes_negocios`
--
ALTER TABLE `imagenes_negocios`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `imagenes_negocios_negocio_local_FK` (`id_negocio`);

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
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id_reset`),
  ADD KEY `password_resets_correo_index` (`correo`);

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
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `categoria_publicacion`
--
ALTER TABLE `categoria_publicacion`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `categoria_reporte`
--
ALTER TABLE `categoria_reporte`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `id_funcionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `imagenes_negocios`
--
ALTER TABLE `imagenes_negocios`
  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `municipalidad`
--
ALTER TABLE `municipalidad`
  MODIFY `id_municipalidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `negocio_local`
--
ALTER TABLE `negocio_local`
  MODIFY `id_negocio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `participacion`
--
ALTER TABLE `participacion`
  MODIFY `id_participacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id_reset` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `publicacion`
--
ALTER TABLE `publicacion`
  MODIFY `id_publicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reaccion`
--
ALTER TABLE `reaccion`
  MODIFY `id_reaccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `reporte`
--
ALTER TABLE `reporte`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `revision_negocio`
--
ALTER TABLE `revision_negocio`
  MODIFY `id_revision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `rubro`
--
ALTER TABLE `rubro`
  MODIFY `id_rubro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sector`
--
ALTER TABLE `sector`
  MODIFY `id_sector` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `seguimiento_reporte`
--
ALTER TABLE `seguimiento_reporte`
  MODIFY `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `sesion`
--
ALTER TABLE `sesion`
  MODIFY `id_sesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Filtros para la tabla `imagenes_negocios`
--
ALTER TABLE `imagenes_negocios`
  ADD CONSTRAINT `imagenes_negocios_negocio_local_FK` FOREIGN KEY (`id_negocio`) REFERENCES `negocio_local` (`id_negocio`);

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
