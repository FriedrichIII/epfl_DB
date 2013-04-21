-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2013 at 09:35 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

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
(14, 'Los Angeles', 'USA'),
(15, 'Helsinki', 'FIN'),
(16, 'Squaw Valley', 'USA'),
(17, 'Berlin', 'GER'),
(18, 'Vancouver', 'CAN'),
(19, 'Calgary', 'CAN'),
(20, 'Tokyo', 'JPN'),
(21, 'Sydney', 'AUS'),
(22, 'Seoul', 'KOR'),
(23, 'Grenoble', 'FRA'),
(24, 'Atlanta', 'USA'),
(25, 'Melbourne', 'AUS'),
(26, 'Salt Lake City', 'USA'),
(27, 'Garmisch-Partenkirchen', 'AUT'),
(28, 'Cortina d''Ampezzo', 'ITA'),
(29, 'Sochi', 'RUS'),
(30, 'Montreal', 'CAN'),
(31, 'Rome', 'ITA'),
(32, 'Antwerp', 'NED'),
(33, 'Oslo', 'NOR'),
(34, 'Mexico City', 'MEX'),
(35, 'Barcelona', 'ESP'),
(36, 'Nagano', 'JPN'),
(37, 'Beijing', 'CHN'),
(38, 'Lillehammer', 'NOR'),
(39, 'Albertville', 'FRA'),
(40, 'Turin', 'ITA'),
(41, 'Sarajevo', 'YUG'),
(42, 'Chamonix', 'FRA');

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
