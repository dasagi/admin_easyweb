-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 27-10-2014 a las 13:06:29
-- Versión del servidor: 5.5.9
-- Versión de PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `easywebCorporativa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posicio` int(1) NOT NULL DEFAULT '0',
  `titol` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ruta` varchar(255) NOT NULL,
  `actiu` int(1) NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `banners`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoritzacio`
--

CREATE TABLE `categoritzacio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tipo` varchar(100) NOT NULL,
  `idioma` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `categoritzacio`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `corporativa`
--

CREATE TABLE `corporativa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa` varchar(255) NOT NULL,
  `adreca` varchar(255) NOT NULL,
  `cp` int(11) NOT NULL,
  `poblacio` varchar(255) NOT NULL,
  `telefon` varchar(255) NOT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `movil` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `web` varchar(255) NOT NULL,
  `legal` text,
  `gmaps` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `corporativa`
--

INSERT INTO `corporativa` VALUES(1, 'Easyweb Corp 2.0', 'Lorem ipsum dirección', 10101, 'Internet', '902 026 964', '', '', 'info@easywebcorporativa.com', 'www.easywebcorporativa.com', 'Textos legales', 'Maps');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `titol` varchar(255) DEFAULT NULL,
  `ruta` varchar(255) NOT NULL,
  `entrada_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entrada_id` (`entrada_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `documents`
--

INSERT INTO `documents` VALUES(0, '0000-00-00 00:00:00', 'No tiene documento ', 'No tiene documento ', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrades`
--

CREATE TABLE `entrades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `titol` varchar(180) NOT NULL,
  `text` text,
  `galeria_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `publicar` int(1) NOT NULL DEFAULT '0',
  `idioma` varchar(2) NOT NULL,
  `tipo` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `galeria_id` (`galeria_id`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `entrades`
--

INSERT INTO `entrades` VALUES(0, '0000-00-00 00:00:00', '', '', NULL, NULL, 0, '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeries`
--

CREATE TABLE `galeries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `titol` varchar(255) DEFAULT NULL,
  `descripcio` text,
  `idioma` varchar(2) DEFAULT NULL,
  `publicar` int(1) NOT NULL,
  `tipo` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `galeries`
--

INSERT INTO `galeries` VALUES(0, '0000-00-00 00:00:00', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idiomes`
--

CREATE TABLE `idiomes` (
  `nom` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `codi` varchar(2) NOT NULL,
  KEY `codi` (`codi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `idiomes`
--

INSERT INTO `idiomes` VALUES('Catala', 'ca');
INSERT INTO `idiomes` VALUES('Español', 'es');
INSERT INTO `idiomes` VALUES('English', 'en');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imatges`
--

CREATE TABLE `imatges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `titol` varchar(255) DEFAULT NULL,
  `ruta` varchar(255) NOT NULL,
  `descripcio` varchar(255) DEFAULT NULL,
  `galeria_id` int(11) DEFAULT '0',
  `entrada_id` int(11) DEFAULT '0',
  `ordre` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `galeria_id` (`galeria_id`),
  KEY `entrada_id` (`entrada_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `imatges`
--

INSERT INTO `imatges` VALUES(0, '0000-00-00 00:00:00', '-1', 'No tiene foto de portada', 'NULL', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL,
  `titol` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `entrada_id` int(11) DEFAULT '0',
  `galeria_id` int(11) DEFAULT NULL,
  `menu_zonas_id` int(11) DEFAULT NULL,
  `menu_parent` int(11) DEFAULT '0',
  `publicar` int(1) NOT NULL DEFAULT '0',
  `idioma` varchar(2) NOT NULL,
  `ordre` int(2) DEFAULT NULL,
  `predet` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `entrada_id` (`entrada_id`),
  KEY `galeria_id` (`galeria_id`),
  KEY `menu_zonas_id` (`menu_zonas_id`),
  KEY `menu_parent` (`menu_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `menus`
--

INSERT INTO `menus` VALUES(0, '0000-00-00 00:00:00', '', '', 0, NULL, 1, 0, 0, 'ca', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus_zona`
--

CREATE TABLE `menus_zona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `menus_zona`
--

INSERT INTO `menus_zona` VALUES(1, 'Menú Vertical');
INSERT INTO `menus_zona` VALUES(2, 'Menú Horizontal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metatags`
--

CREATE TABLE `metatags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tittle` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `robots` varchar(10) NOT NULL,
  `autor` varchar(180) NOT NULL,
  `idioma` varchar(2) NOT NULL,
  `entrada_id` int(11) DEFAULT NULL,
  `galeria_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entrada_id` (`entrada_id`,`galeria_id`),
  KEY `galeria_id` (`galeria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `metatags`
--

INSERT INTO `metatags` VALUES(0, '', '', '', '', '', 'ca', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portada`
--

CREATE TABLE `portada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entrada_id` int(11) DEFAULT NULL,
  `galeria_id` int(11) DEFAULT NULL,
  `document_id` int(11) DEFAULT NULL,
  `usuari_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `tipus` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `entrada_id` (`entrada_id`),
  KEY `galeria_id` (`galeria_id`),
  KEY `imatge_id` (`document_id`,`usuari_id`,`menu_id`),
  KEY `document_id` (`document_id`),
  KEY `usuaris_id` (`usuari_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `portada`
--

INSERT INTO `portada` VALUES(0, 0, 0, NULL, NULL, NULL, '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL DEFAULT '0000-00-00',
  `nom` varchar(180) NOT NULL,
  `text` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `entrada_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `entrada_id` (`entrada_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `post`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relacio_cat`
--

CREATE TABLE `relacio_cat` (
  `entrada_id` int(11) NOT NULL,
  `etiqueta_id` int(11) NOT NULL,
  `valor` int(1) NOT NULL,
  KEY `entrada_id` (`entrada_id`),
  KEY `etiqueta_id` (`etiqueta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `relacio_cat`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuaris`
--

CREATE TABLE `usuaris` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nom` varchar(180) NOT NULL,
  `cognoms` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mail` varchar(40) NOT NULL,
  `avatar` varchar(80) CHARACTER SET utf8mb4 NOT NULL,
  `categoria` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `usuaris`
--

INSERT INTO `usuaris` VALUES(1, '2014-07-18 00:00:00', 'easyweb', 'corporativa', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'info@easywebcorporativa.com', 'avatar.jpg', 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `xsocial`
--

CREATE TABLE `xsocial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `codi` text NOT NULL,
  `icon` varchar(255) NOT NULL,
  `corporativa_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `corporativa_id` (`corporativa_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Volcar la base de datos para la tabla `xsocial`
--

INSERT INTO `xsocial` VALUES(1, 'Icon Facebook negro', '', '', 'facebook_64_black.png', NULL);
INSERT INTO `xsocial` VALUES(2, 'Icon Facebook blanco', '', '', 'facebook_64_white.png', NULL);
INSERT INTO `xsocial` VALUES(3, 'Google Plus negro', '', '', 'gplus_64_black.png', NULL);
INSERT INTO `xsocial` VALUES(4, 'Google Plus blanco', '', '', 'gplus_64_white.png', NULL);
INSERT INTO `xsocial` VALUES(5, 'Instagram icon negro', '', '', 'instagram_64_black.png', NULL);
INSERT INTO `xsocial` VALUES(6, 'Instagram icon blanco', '', '', 'instagram_64_white.png', NULL);
INSERT INTO `xsocial` VALUES(7, 'Linked In icon negro', '', '', 'linkedin_64_black.png', NULL);
INSERT INTO `xsocial` VALUES(8, 'Linked In icon blanco', '', '', 'linkedin_64_white.png', NULL);
INSERT INTO `xsocial` VALUES(9, 'Pinterest icon negro', '', '', 'pinterest_64_black.png', NULL);
INSERT INTO `xsocial` VALUES(10, 'Pinterest icon blanco', '', '', 'pinterest_64_white.png', NULL);
INSERT INTO `xsocial` VALUES(11, 'Twitter icon negro', '', '', 'twitter_64_black.png', NULL);
INSERT INTO `xsocial` VALUES(12, 'Twitter icon blanco', '', '', 'twitter_64_white.png', NULL);
INSERT INTO `xsocial` VALUES(13, 'Youtube icon negro', '', '', 'youtube_64_black.png', NULL);
INSERT INTO `xsocial` VALUES(14, 'Youtube icon blanco', '', '', 'youtube_64_white.png', NULL);

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`entrada_id`) REFERENCES `entrades` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `entrades`
--
ALTER TABLE `entrades`
  ADD CONSTRAINT `entrades_ibfk_18` FOREIGN KEY (`galeria_id`) REFERENCES `galeries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entrades_ibfk_21` FOREIGN KEY (`categoria_id`) REFERENCES `categoritzacio` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `imatges`
--
ALTER TABLE `imatges`
  ADD CONSTRAINT `imatges_ibfk_4` FOREIGN KEY (`entrada_id`) REFERENCES `entrades` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `imatges_ibfk_5` FOREIGN KEY (`galeria_id`) REFERENCES `galeries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_3` FOREIGN KEY (`menu_zonas_id`) REFERENCES `menus_zona` (`id`),
  ADD CONSTRAINT `menus_ibfk_5` FOREIGN KEY (`galeria_id`) REFERENCES `galeries` (`id`),
  ADD CONSTRAINT `menus_ibfk_6` FOREIGN KEY (`entrada_id`) REFERENCES `entrades` (`id`),
  ADD CONSTRAINT `menus_ibfk_7` FOREIGN KEY (`menu_parent`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `metatags`
--
ALTER TABLE `metatags`
  ADD CONSTRAINT `metatags_ibfk_3` FOREIGN KEY (`entrada_id`) REFERENCES `entrades` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `metatags_ibfk_4` FOREIGN KEY (`galeria_id`) REFERENCES `galeries` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `portada`
--
ALTER TABLE `portada`
  ADD CONSTRAINT `portada_ibfk_10` FOREIGN KEY (`galeria_id`) REFERENCES `galeries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `portada_ibfk_12` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `portada_ibfk_13` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `portada_ibfk_14` FOREIGN KEY (`usuari_id`) REFERENCES `usuaris` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `portada_ibfk_9` FOREIGN KEY (`entrada_id`) REFERENCES `entrades` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `relacio_cat`
--
ALTER TABLE `relacio_cat`
  ADD CONSTRAINT `relacio_cat_ibfk_1` FOREIGN KEY (`entrada_id`) REFERENCES `entrades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `xsocial`
--
ALTER TABLE `xsocial`
  ADD CONSTRAINT `xsocial_ibfk_1` FOREIGN KEY (`corporativa_id`) REFERENCES `corporativa` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
