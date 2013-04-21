-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2013 at 08:24 PM
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
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `cityID` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(128) DEFAULT NULL,
  `iocCode` char(4) DEFAULT NULL,
  PRIMARY KEY (`cityID`),
  KEY `iocCode` (`iocCode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`cityID`, `name`, `iocCode`) VALUES
(1, 'St. Louis', 'USA'),
(2, 'Paris', 'FRA'),
(3, 'Athens', 'GRE'),
(4, 'London', 'GBR'),
(5, 'Lake Placid', 'USA'),
(6, 'Innsbruck', 'AUT'),
(7, 'Rio de Janeiro', 'BRA'),
(8, 'Moscow', 'URS'),
(9, 'Munich', 'WGE'),
(10, 'St. Moritz', 'SUI'),
(11, 'Amsterdam', 'NED'),
(12, 'Sapporo', 'JPN'),
(13, 'Stockholm', 'SWE'),
(14, 'Athens', 'GRE'),
(15, 'Innsbruck', 'AUT'),
(16, 'Los Angeles', 'USA'),
(17, 'Helsinki', 'FIN'),
(18, 'Squaw Valley', 'USA'),
(19, 'Berlin', 'GER'),
(20, 'Vancouver', 'CAN'),
(21, 'Calgary', 'CAN'),
(22, 'Tokyo', 'JPN'),
(23, 'St. Moritz', 'SUI'),
(24, 'Sydney', 'AUS'),
(25, 'Seoul', 'KOR'),
(26, 'Grenoble', 'FRA'),
(27, 'Atlanta', 'USA'),
(28, 'Athens', 'GRE'),
(29, 'Melbourne', 'AUS'),
(30, 'Salt Lake City', 'USA'),
(31, 'Garmisch-Partenkirchen', 'AUT'),
(32, 'Paris', 'FRA'),
(33, 'Cortina d''Ampezzo', 'ITA'),
(34, 'Sochi', 'RUS'),
(35, 'Lake Placid', 'USA'),
(36, 'Montreal', 'CAN'),
(37, 'Rome', 'ITA'),
(38, 'London', 'GBR'),
(39, 'Antwerp', 'NED'),
(40, 'Oslo', 'NOR'),
(41, 'Mexico City', 'MEX'),
(42, 'Barcelona', 'ESP'),
(43, 'Nagano', 'JPN'),
(44, 'Beijing', 'CHN'),
(45, 'Lillehammer', 'NOR'),
(46, 'Albertville', 'FRA'),
(47, 'London', 'GBR'),
(48, 'Turin', 'ITA'),
(49, 'Sarajevo', 'YUG'),
(50, 'Los Angeles', 'USA'),
(51, 'Chamonix', 'FRA');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`iocCode`) REFERENCES `countries` (`iocCode`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
