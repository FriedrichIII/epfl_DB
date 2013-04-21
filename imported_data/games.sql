-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2013 at 10:48 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `olympics`
--

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `gameID` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(11) unsigned NOT NULL DEFAULT '1900',
  `seasonName` varchar(64) NOT NULL,
  `cityID` int(11) DEFAULT NULL,
  PRIMARY KEY (`gameID`),
  KEY `cityID` (`cityID`),
  KEY `seasonName` (`seasonName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`gameID`, `year`, `seasonName`, `cityID`) VALUES
(1, 1896, 'Summer', 3),
(2, 1900, 'Summer', 2),
(3, 1904, 'Summer', 1),
(4, 1906, 'Summer', 3),
(5, 1908, 'Summer', 4),
(6, 1912, 'Summer', 13),
(7, 1920, 'Summer', 32),
(8, 1924, 'Summer', 2),
(9, 1924, 'Winter', 42),
(10, 1928, 'Winter', 10),
(11, 1928, 'Summer', 11),
(12, 1932, 'Winter', 5),
(13, 1932, 'Summer', 14),
(14, 1936, 'Summer', 17),
(15, 1936, 'Winter', 27),
(16, 1948, 'Summer', 4),
(17, 1948, 'Winter', 10),
(18, 1952, 'Summer', 15),
(19, 1952, 'Winter', 33),
(20, 1956, 'Summer', 25),
(21, 1960, 'Winter', 16),
(22, 1960, 'Summer', 31),
(23, 1964, 'Winter', 6),
(24, 1964, 'Summer', 20),
(25, 1968, 'Winter', 23),
(26, 1968, 'Summer', 34),
(27, 1972, 'Summer', 9),
(28, 1972, 'Winter', 12),
(29, 1976, 'Winter', 6),
(30, 1976, 'Summer', 30),
(31, 1980, 'Winter', 5),
(32, 1980, 'Summer', 8),
(33, 1984, 'Summer', 14),
(34, 1984, 'Winter', 41),
(35, 1988, 'Winter', 19),
(36, 1988, 'Summer', 22),
(37, 1992, 'Summer', 35),
(38, 1992, 'Winter', 39),
(39, 1994, 'Winter', 38),
(40, 1996, 'Summer', 24),
(41, 1998, 'Winter', 36),
(42, 2000, 'Summer', 21),
(43, 2002, 'Winter', 26),
(44, 2004, 'Summer', 3),
(45, 2006, 'Winter', 40),
(46, 2008, 'Summer', 37),
(47, 2010, 'Winter', 18),
(48, 2012, 'Summer', 4),
(49, 2014, 'Winter', 29),
(50, 2016, 'Summer', 7);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_2` FOREIGN KEY (`cityID`) REFERENCES `cities` (`cityID`),
  ADD CONSTRAINT `games_ibfk_3` FOREIGN KEY (`seasonName`) REFERENCES `seasons` (`seasonName`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
