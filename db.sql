-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 24, 2013 at 10:30 AM
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
  `is_online` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tibiahu_character`
--

INSERT INTO `tibiahu_character` (`id`, `name`, `vocation`, `level`, `is_online`) VALUES
(1, 'Tele von Zsinor', 'Knight', 53, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tibiahu_levelhistory`
--

CREATE TABLE IF NOT EXISTS `tibiahu_levelhistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `is_death` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `character_id` (`character_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tibiahu_levelhistory`
--

INSERT INTO `tibiahu_levelhistory` (`id`, `character_id`, `level`, `date`, `is_death`) VALUES
(1, 1, 53, '2013-05-22 10:51:02', 1),
(2, 1, 54, '2013-05-22 10:51:03', 0),
(3, 1, 53, '2013-05-22 11:01:09', 0),
(4, 1, 53, '2013-05-22 11:10:52', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tibiahu_levelhistory`
--
ALTER TABLE `tibiahu_levelhistory`
ADD CONSTRAINT `tibiahu_levelhistory_ibfk_1` FOREIGN KEY (`character_id`) REFERENCES `tibiahu_character` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
