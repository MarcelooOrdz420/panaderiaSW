-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2025 a las 07:53:36
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
-- Base de datos: `panaderio_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'PANES EN VARIEDAD', 'Todo tipo de panes tradicionales '),
(3, 'PASTELES', 'Todo tipo de pasteles tradicionales , entre otros'),
(4, 'KEKES', 'Variedad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id`, `pedido_id`, `producto_id`, `cantidad`, `subtotal`) VALUES
(6, 13, 2, 1, 4.50),
(7, 14, 1, 1, 15.00),
(8, 17, 4, 1, 2.00),
(9, 17, 1, 1, 15.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `estado` enum('pendiente','enviado') DEFAULT 'pendiente',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `metodo_pago`, `usuario_id`, `total`, `estado`, `fecha`) VALUES
(13, 'yape', 1, 4.50, '', '2025-10-27 03:45:10'),
(14, 'transferencia', 1, 15.00, '', '2025-10-27 03:47:42'),
(17, 'yape', 5, 17.00, '', '2025-10-27 04:16:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `imagen` varchar(100) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `categoria_id`) VALUES
(1, 'Keke de chocolate', 'Rico keke de chocolate', 15.00, 'img/prod_68fe8b07d6ddf.png', 4),
(2, 'Delikeik', 'Keke de vainilla con un poco de chocolate ', 4.50, 'img/prod_68fe89c011533.jpg', 4),
(3, 'Chancay', 'Pan', 2.50, 'img/prod_68fed3f0c8a1e.png', 1),
(4, 'Kekon', 'Keke de chocolate', 2.00, 'img/prod_68fed4103f1ce.jpg', 4),
(5, 'Kekon marmoleado', 'kekon de chocolate con vainilla', 5.00, 'img/prod_68fef8900d3a3.png', 4),
(6, 'Pan de hamburguesa especial', 'pan de hamburguesa ', 5.50, 'img/prod_68fef8b5e42f3.png', 1),
(7, 'Pionono moka', 'pionono con sabor a chocolate', 4.50, 'img/prod_68fef8dba720d.png', 4),
(8, 'Pionono vainilla', 'Pionono sabor a vainilla', 4.50, 'img/prod_68fef90873ebf.png', 4),
(9, 'Pan molde integral', 'pan molde integra clasico', 5.50, 'img/prod_68fef94a54ac0.png', 1),
(10, 'Karamanduka', 'karamanduka', 3.50, 'img/prod_68fef9c639105.jpg', 1),
(11, 'Pan chalaco', 'pan chalaco ', 4.50, 'img/prod_68fef9e65f2e1.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('admin','cliente') DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `password`, `rol`) VALUES
(1, 'Marcelo', 'marcelo320@gmail.com', '$2y$10$gPOQHHSNh/aioa1SzkByQOEDWMeygyLIh.v5NsfvghrVpccl7NLo2', 'cliente'),
(2, 'admin2424@gmail.com', NULL, '$2y$10$cgD.QoKWoes.tMAAD1gbFOPhEUvtPm.rHZ9zDUjWht7UjwUPDkKVa', 'cliente'),
(3, 'ADMIN - RAUL', 'admin@delicias.com', '$2y$10$/LSCBmz7YQge.aReGqCVKOTJiO4j.s.hmAKmny5MKGkFMj1ht5yZm', 'admin'),
(4, 'ADMIN - JAIME', 'admin2@delicias.com', '$2y$10$RbKVPkr44Yw.eoDMTPgkyOt3FiTc/mD4AR9zDuqYDlbqxG1GFl2xK', 'admin'),
(5, 'Carlo Ordz', 'ordz20@gmail.com', '$2y$10$BZXe16snvI6JewFhuEJyWu04BwlvWDAfeFD4sYjvc.pHXw7b47cuO', 'cliente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pedidos_usuarios` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
