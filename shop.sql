-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 14-05-2010 a las 20:48:00
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `shop`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `catid` int(4) NOT NULL AUTO_INCREMENT,
  `catname` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `catdesc` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=12 ;

--
-- Volcar la base de datos para la tabla `categories`
--

INSERT INTO `categories` (`catid`, `catname`, `catdesc`) VALUES
(1, 'Web Templates', 'web templates'),
(9, 'WordPress Templates', 'wordpress templates'),
(11, 'XHTML Templates', 'xhtml templates');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `short` text NOT NULL,
  `long` text NOT NULL,
  `posted` text NOT NULL,
  `hits` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Volcar la base de datos para la tabla `news`
--

INSERT INTO `news` (`id`, `title`, `short`, `long`, `posted`, `hits`) VALUES
(3, '<h1> Welcome </h1>', '\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent aliquam,  justo convallis luctus rutrum, erat nulla fermentum diam, at nonummy quam  ante ac quam. Maecenas urna purus, fermentum id, molestie in, commodo  porttitor, felis. Nam blandit quam ut lacus. </p><p>Quisque ornare risus quis  ligula. Phasellus tristique purus a augue condimentum adipiscing. Aenean  sagittis. Etiam leo pede, rhoncus venenatis, tristique in, vulputate at,  odio. Donec et ipsum et sapien vehicula nonummy. Suspendisse potenti. Fusce  varius urna id quam. Sed neque mi, varius eget, tincidunt nec, suscipit id,  libero. In eget purus. Vestibulum ut nisl. Donec eu mi sed turpis feugiat  feugiat. Integer turpis arcu, pellentesque eget, cursus et, fermentum ut,  sapien. Fusce metus mi, eleifend sollicitudin, molestie id, varius et, nibh.  Donec nec libero.</p><h2>Gomez Shopping Cart v2.0</h2><p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent aliquam,  justo convallis luctus rutrum, erat nulla fermentum diam, at nonummy quam  ante ac quam. Maecenas urna purus, fermentum id, molestie in, commodo  porttitor, felis. Nam blandit quam ut lacus. Quisque ornare risus quis  ligula. Phasellus tristique purus a augue condimentum adipiscing. Aenean  sagittis. Etiam leo pede, rhoncus venenatis, tristique in, vulputate at, odio.</p>', '', 'http://www.gomezstudio.com', '1'),
(4, 'TEST', 'this is a test', '', 'test', ''),
(13, '', '', '', '', ''),
(14, '', '', '', '', ''),
(15, 'aaaa', '', '', '', ''),
(16, 'aa', '', '', '', ''),
(17, 'aaaaaaaa', '', '', '', ''),
(18, 'aaaaa', '', '', '', ''),
(19, 'a', '', '', '', ''),
(20, '', '', '', '', ''),
(21, '', '', '', '', ''),
(22, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `process`
--

CREATE TABLE IF NOT EXISTS `process` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `country` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `genre` tinytext COLLATE latin1_general_ci NOT NULL,
  `address` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `phone` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `CardNumber` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `total` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `total_products` int(10) NOT NULL,
  `products` text COLLATE latin1_general_ci NOT NULL,
  `status` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `process`
--

INSERT INTO `process` (`id`, `session_id`, `name`, `email`, `country`, `genre`, `address`, `phone`, `CardNumber`, `total`, `total_products`, `products`, `status`) VALUES
(3, '127.0.0.1', 'test', 'test', 'Albania', 'Boy', 'test', 'test', '3400 0000 0000 009', '30', 2, 'wp-premium,wp-premium', 'pending');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE latin1_general_ci NOT NULL,
  `image` text COLLATE latin1_general_ci NOT NULL,
  `shortdesc` tinytext COLLATE latin1_general_ci NOT NULL,
  `longdesc` text COLLATE latin1_general_ci NOT NULL,
  `cost` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `cat` varchar(200) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=132 ;

--
-- Volcar la base de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `title`, `image`, `shortdesc`, `longdesc`, `cost`, `cat`) VALUES
(105, 'wp-premium', 'wp-premium', 'wp-premium', 'wp-premium', '40.00', 'WordPress Templates');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `session_id` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `title` text COLLATE latin1_general_ci NOT NULL,
  `cost` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `type` varchar(30) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `stock`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `useremail` varchar(100) NOT NULL,
  `userpassword` varchar(50) NOT NULL,
  `template` varchar(250) NOT NULL,
  `seo` tinyint(5) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `tbl_user`
--

INSERT INTO `tbl_user` (`userid`, `username`, `useremail`, `userpassword`, `template`, `seo`) VALUES
(1, 'admin', 'admin@localhost', 'e10adc3949ba59abbe56e057f20f883e', 'green', 0);
