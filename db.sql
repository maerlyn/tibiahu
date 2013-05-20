-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 20, 2013 at 06:17 PM
-- Server version: 5.5.31
-- PHP Version: 5.4.6-1ubuntu1.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `tibiahu_minimal`
--

-- --------------------------------------------------------

--
-- Table structure for table `tibiahu_character`
--

CREATE TABLE IF NOT EXISTS `tibiahu_character` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_hungarian_ci NOT NULL,
  `vocation` varchar(16) COLLATE utf8_hungarian_ci NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tibiahu_character`
--

INSERT INTO `tibiahu_character` (`id`, `name`, `vocation`, `level`) VALUES
(1, 'Tele von Zsinor', 'Knight', 53);
