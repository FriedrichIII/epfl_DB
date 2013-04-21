-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2013 at 12:17 AM
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
-- Table structure for table `athletes`
--

CREATE TABLE IF NOT EXISTS `athletes` (
  `athleteID` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(128) DEFAULT NULL,
  PRIMARY KEY (`athleteID`),
  UNIQUE KEY `athleteID_2` (`athleteID`),
  UNIQUE KEY `athleteID_3` (`athleteID`),
  KEY `athleteID` (`athleteID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67261 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `name` char(128) DEFAULT NULL,
  `iocCode` char(4) NOT NULL DEFAULT '',
  PRIMARY KEY (`iocCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `disciplines`
--

CREATE TABLE IF NOT EXISTS `disciplines` (
  `disciplineID` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(128) DEFAULT NULL,
  `sportID` int(11) DEFAULT NULL,
  PRIMARY KEY (`disciplineID`),
  KEY `sportID` (`sportID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=791 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `eventID` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(128) DEFAULT NULL,
  `gameID` int(11) NOT NULL,
  `disciplineID` int(11) NOT NULL,
  PRIMARY KEY (`eventID`),
  KEY `gameID` (`gameID`),
  KEY `disciplineID` (`disciplineID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4553 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE IF NOT EXISTS `memberships` (
  `teamID` int(11) NOT NULL DEFAULT '0',
  `athleteID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`teamID`,`athleteID`),
  KEY `athleteID` (`athleteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `seasons`
--

CREATE TABLE IF NOT EXISTS `seasons` (
  `seasonName` char(64) NOT NULL,
  PRIMARY KEY (`seasonName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sports`
--

CREATE TABLE IF NOT EXISTS `sports` (
  `sportID` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(128) DEFAULT NULL,
  PRIMARY KEY (`sportID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=124 ;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `teamID` int(11) NOT NULL AUTO_INCREMENT,
  `iocCode` char(4) NOT NULL,
  `eventID` int(11) NOT NULL,
  `rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`teamID`),
  UNIQUE KEY `eventID` (`eventID`,`rank`),
  KEY `iocCode` (`iocCode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20498 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`iocCode`) REFERENCES `countries` (`iocCode`);

--
-- Constraints for table `disciplines`
--
ALTER TABLE `disciplines`
  ADD CONSTRAINT `disciplines_ibfk_1` FOREIGN KEY (`sportID`) REFERENCES `sports` (`sportID`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`gameID`) REFERENCES `games` (`gameID`),
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`disciplineID`) REFERENCES `disciplines` (`disciplineID`);

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_2` FOREIGN KEY (`cityID`) REFERENCES `cities` (`cityID`),
  ADD CONSTRAINT `games_ibfk_3` FOREIGN KEY (`seasonName`) REFERENCES `seasons` (`seasonName`);

--
-- Constraints for table `memberships`
--
ALTER TABLE `memberships`
  ADD CONSTRAINT `memberships_ibfk_1` FOREIGN KEY (`teamID`) REFERENCES `teams` (`teamID`),
  ADD CONSTRAINT `memberships_ibfk_2` FOREIGN KEY (`athleteID`) REFERENCES `athletes` (`athleteID`);

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`iocCode`) REFERENCES `countries` (`iocCode`),
  ADD CONSTRAINT `teams_ibfk_2` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
