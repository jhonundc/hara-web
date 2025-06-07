-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 07-06-2025 a las 03:05:04
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda hara bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

DROP TABLE IF EXISTS `carrito`;
CREATE TABLE IF NOT EXISTS `carrito` (
  `id_carrito` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_carrito`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_items`
--

DROP TABLE IF EXISTS `carrito_items`;
CREATE TABLE IF NOT EXISTS `carrito_items` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `id_carrito` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`id_item`),
  KEY `id_carrito` (`id_carrito`),
  KEY `id_producto` (`id_producto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`id_categoria`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

DROP TABLE IF EXISTS `pagos`;
CREATE TABLE IF NOT EXISTS `pagos` (
  `id_pago` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `estado_pago` enum('pendiente','completado','fallido') DEFAULT 'pendiente',
  PRIMARY KEY (`id_pago`),
  KEY `id_pedido` (`id_pedido`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `fecha_pedido` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('pendiente','pagado','enviado','entregado','cancelado') DEFAULT 'pendiente',
  `total` decimal(10,2) DEFAULT NULL,
  `direccion_envio` text,
  PRIMARY KEY (`id_pedido`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

DROP TABLE IF EXISTS `pedido_detalle`;
CREATE TABLE IF NOT EXISTS `pedido_detalle` (
  `id_detalle` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_producto` (`id_producto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `categoria_id` int DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `imagen` varchar(255) NOT NULL,
  `ingredientes` text,
  `beneficios` text,
  `tipo` varchar(50) NOT NULL DEFAULT 'jabon',
  PRIMARY KEY (`id_producto`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `categoria_id`, `imagen_url`, `activo`, `imagen`, `ingredientes`, `beneficios`, `tipo`) VALUES
(11, 'Jabón de Lavanda Relajante', 'Jabón artesanal elaborado con aceites naturales, ideal para piel sensible.', 35.00, 20, NULL, 'imagenes/lavanda.jpg', 1, 'lavanda.jpg', 'Aceite de oliva virgen extra, aceite de coco orgánico, manteca de karité, flores secas de lavanda, aceite esencial de lavanda.', 'Calma los sentidos, reduce el estrés, alivia la tensión muscular y promueve un sueño reparador.', 'jabon'),
(12, 'Jabón de Carbón Activado Detox', 'Jabón purificante ideal para piel grasa y con tendencia al acné.', 8.00, 25, NULL, 'imagenes/carbon.jpg', 1, 'carbon.jpg', 'Carbón activado vegetal, aceite de oliva, aceite de coco, manteca de cacao, aceite esencial de árbol de té.', 'Limpieza profunda, reduce imperfecciones, elimina impurezas y equilibra la producción de grasa.', 'jabon'),
(13, 'Jabón de Avena y Miel Suavizante', 'Jabón artesanal para piel sensible y seca con propiedades nutritivas.', 6.50, 18, NULL, 'imagenes/avena_miel.jpg', 1, 'avena_miel.jpg', 'Avena molida, miel pura de abeja, aceite de almendras dulces, manteca de karité, aceite de coco.', 'Hidrata intensamente, alivia la irritación, suaviza la piel y mejora la textura natural.', 'jabon'),
(14, 'Jabón de Rosas Regenerador', 'Jabón delicado y aromático con extracto de rosas.', 7.00, 15, NULL, 'imagenes/rosas.jpg', 1, 'rosas.jpg', 'Aceite de rosa mosqueta, pétalos de rosa seca, glicerina vegetal, aceite esencial de rosa, manteca de cacao.', 'Regenera la piel, ayuda a reducir cicatrices, promueve la elasticidad y proporciona una fragancia relajante.', 'jabon'),
(15, 'Jabón de Café Exfoliante', 'Jabón estimulante con partículas de café para exfoliación natural.', 8.00, 20, NULL, 'imagenes/cafe.jpg', 1, 'cafe.jpg', 'Café molido orgánico, aceite de oliva, aceite de coco, aceite esencial de canela, manteca de karité.', 'Activa la circulación, reduce celulitis, elimina células muertas y energiza la piel.', 'jabon'),
(16, 'Jabón de Aloe Vera Calmante', 'Ideal para pieles irritadas o expuestas al sol.', 10.00, 22, NULL, 'imagenes/aloe_vera.jpg', 1, 'aloe_vera.jpg', 'Gel de aloe vera puro, aceite de jojoba, aceite de coco, glicerina vegetal, aceite esencial de manzanilla.', 'Refresca la piel, alivia quemaduras, hidrata profundamente y reduce la inflamación.', 'jabon'),
(17, 'Jabón de Cúrcuma Iluminador', 'Jabón antioxidante que unifica el tono de la piel.', 12.00, 16, NULL, 'imagenes/curcuma.jpg', 1, 'curcuma.jpg', 'Polvo de cúrcuma natural, aceite de oliva, aceite de coco, manteca de cacao, aceite esencial de naranja dulce.', 'Atenúa manchas, combate radicales libres, mejora el brillo natural de la piel.', 'jabon'),
(18, 'Jabón de Eucalipto Respiratorio', 'Jabón refrescante con aroma revitalizante.', 15.00, 20, NULL, 'imagenes/eucalipto.jpg', 1, 'eucalipto.jpg', 'Aceite esencial de eucalipto, aceite de ricino, aceite de coco, manteca de karité, mentol natural.', 'Descongestiona vías respiratorias, estimula los sentidos, ideal para duchas revitalizantes.', 'jabon'),
(19, 'Jabón de Chocolate Nutritivo', 'Jabón suave y cremoso con cacao natural.', 9.00, 14, NULL, 'imagenes/chocolate.jpg', 1, 'chocolate.jpg', 'Manteca de cacao, cacao en polvo puro, aceite de coco, aceite de almendras, esencia natural de vainilla.', 'Nutre la piel seca, deja una sensación sedosa, aporta antioxidantes y un aroma delicioso.', 'jabon'),
(20, 'Jabón de Menta Energizante', 'Jabón fresco y vigorizante para comenzar el día.', 8.50, 19, NULL, 'imagenes/menta.jpg', 1, 'menta.jpg', 'Aceite esencial de menta, aceite de oliva, aceite de coco, manteca de karité, cristales de mentol.', 'Estimula la circulación, refresca el cuerpo, ideal para el uso matutino o después del ejercicio.', 'jabon'),
(21, 'Vela de Lavanda', NULL, 24.50, 10, NULL, 'imagenes/vela_lavanda.jpg', 1, 'vela_lavanda.jpg\r\n', 'Cera de soja, aceite esencial de lavanda, mecha de algodón', 'Relaja el sistema nervioso, ideal para dormir, aroma floral calmante', 'vela'),
(22, 'Vela de Vainilla', NULL, 23.00, 12, NULL, 'imagenes/vela_vainilla.jpg', 1, 'vela_vainilla.jpg', 'Cera vegetal, esencia de vainilla, mecha ecológica', 'Ambiente cálido, ayuda a reducir el estrés, aroma dulce', 'vela'),
(23, 'Vela de Canela y Naranja', NULL, 25.00, 8, NULL, 'imagenes/vela_canela_naranja.jpg', 1, 'vela_canela_naranja.jpg', 'Cera de soja, aceites esenciales de canela y naranja', 'Energizante, reconfortante, ideal para otoño e invierno', 'vela'),
(24, 'Vela de Rosas', NULL, 26.50, 9, NULL, 'imagenes/vela_rosas.jpg', 1, 'vela_rosas.jpg', 'Cera de soja, aceite esencial de rosas, pétalos naturales', 'Romántica, mejora el estado de ánimo, aroma floral intenso', 'vela'),
(25, 'Vela de Menta y Eucalipto', NULL, 24.00, 11, NULL, 'imagenes/vela_menta_eucalipto.jpg', 1, 'vela_menta_eucalipto.jpg', 'Cera vegetal, aceites esenciales de menta y eucalipto', 'Refrescante, despeja vías respiratorias, ideal para días calurosos', 'vela'),
(26, 'Vela de Coco y Lima', NULL, 23.90, 10, NULL, 'imagenes/vela_coco_lima.jpg', 1, 'vela_coco_lima.jpg', 'Cera de soja, esencias de coco y lima, mecha sin plomo', 'Aroma tropical, revitalizante, ideal para ambientes relajados', 'vela'),
(27, 'Vela de Cedro y Sándalo', NULL, 27.00, 7, NULL, 'imagenes/vela_cedro.jpg', 1, 'vela_cedro.jpg', 'Cera de soja, aceites esenciales de cedro y sándalo', 'Relajante, aroma amaderado, ideal para meditación', 'vela'),
(28, 'Vela de Jazmín', NULL, 25.50, 13, NULL, 'imagenes/vela_jazmin.jpg', 1, 'vela_jazmin.jpg', 'Cera vegetal, esencia natural de jazmín', 'Elevador del ánimo, calmante, ideal para noches románticas', 'vela'),
(29, 'Vela de Café', NULL, 22.50, 14, NULL, 'imagenes/vela_cafe.jpg', 1, 'vela_cafe.jpg', 'Cera de soja, esencia de café tostado', 'Estimula los sentidos, ideal para oficinas y estudios', 'vela'),
(30, 'Vela de Té Verde y Limón', NULL, 24.80, 6, NULL, 'imagenes/vela_te_verde_limon.jpg', 1, 'vela_te_verde_limon.jpg', 'Cera vegetal, esencias de té verde y limón', 'Aroma limpio, purificador del ambiente, energizante', 'vela');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reseñas`
--

DROP TABLE IF EXISTS `reseñas`;
CREATE TABLE IF NOT EXISTS `reseñas` (
  `id_reseña` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `comentario` text,
  `calificacion` int DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_reseña`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_producto` (`id_producto`)
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `rol` enum('cliente','admin') DEFAULT 'cliente',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `email`, `contraseña`, `telefono`, `direccion`, `fecha_registro`, `rol`) VALUES
(1, 'jhon', NULL, '651651@gmail.com', '$2y$12$gnCvxqoOzLHw1e5ph6L4memJzAdV.17riHx2S33IFNtjsEW6GOoiu', NULL, NULL, '2025-06-02 02:22:32', 'cliente'),
(2, 'jhon', NULL, '654654@gmail.com', '$2y$12$F1kS8EaYMKAyS79eZ.j5X.8XH7MV.P9evMf2c.x.yHV4k4/G0Q/Tq', NULL, NULL, '2025-06-02 02:24:41', 'cliente'),
(3, 'juan', NULL, 'juan@gmail.com', '$2y$12$ome6..nGBlN6icDRrFJtJOIe16W02sUEvyM8LL8hgPZZUChE2.Kci', NULL, NULL, '2025-06-02 02:25:09', 'cliente'),
(4, 'luis', NULL, 'luis@gmail.com', '$2y$12$89a01T3pV7QVWrX2CCHtxemDlzxNl3hXbDgxDNYG3mxkbiB8f9HrK', NULL, NULL, '2025-06-02 02:26:42', 'cliente'),
(5, 'juanito', NULL, 'juanito@gmail.com', '$2y$12$e1Vl8agFiPIMJbu8PtVm8OdQqBqoBAfb5HjJlupZ9azlT4BCAU9v2', NULL, NULL, '2025-06-02 02:39:52', 'cliente');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
