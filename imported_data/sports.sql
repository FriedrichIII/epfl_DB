-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2013 at 07:02 PM
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
-- Table structure for table `sports`
--

CREATE TABLE IF NOT EXISTS `sports` (
  `sportID` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(128) DEFAULT NULL,
  PRIMARY KEY (`sportID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=124 ;

--
-- Dumping data for table `sports`
--

INSERT INTO `sports` (`sportID`, `name`) VALUES
(1, 'Olympic trap'),
(2, 'Korfball'),
(3, 'Boules'),
(4, 'Rowing'),
(5, 'Longue paume'),
(6, 'Paralympic alpine skiing'),
(7, 'Rhythmic gymnastics'),
(8, 'Field handball'),
(9, 'Golf'),
(10, 'Rugby union'),
(11, 'Judo'),
(12, 'Racquetball'),
(13, 'Nordic combined'),
(14, 'Freestyle wrestling'),
(15, 'Racquets'),
(16, 'Powerboating'),
(17, 'Weightlifting'),
(18, 'Bowling'),
(19, 'Wheelchair racing'),
(20, 'Softball'),
(21, 'Synchronized swimming'),
(22, 'Paralympic nordic skiing'),
(23, 'Pilota'),
(24, 'Archery'),
(25, 'Dominican Republic'),
(26, 'Budo'),
(27, 'American football'),
(28, 'Netball'),
(29, 'Wushu'),
(30, 'Speed skiing'),
(31, 'Cross-country skiing'),
(32, 'Sport climbing'),
(33, 'Curling'),
(34, 'Cycling'),
(35, 'Dressage'),
(36, 'Mountaineering'),
(37, 'Equestrian vaulting'),
(38, 'Speed skating'),
(39, 'Skeleton'),
(40, 'Karate'),
(41, 'Ski jumping'),
(42, 'Hot air ballooning'),
(43, 'Motorcycle racing'),
(44, 'Boxing'),
(45, 'Cross country running'),
(46, 'Polo'),
(47, 'Glima'),
(48, 'DanceSport'),
(49, 'Bandy'),
(50, 'Frisian handball'),
(51, 'Short track speed skating'),
(52, 'BMX racing'),
(53, 'Gliding'),
(54, 'Cray Shooting'),
(55, 'Fencing'),
(56, 'Figure skating'),
(57, 'Chess'),
(58, 'Jeu de paume'),
(59, 'Baseball'),
(60, 'Artistic gymnastics'),
(61, 'Canoe Sprint'),
(62, 'Road cycling'),
(63, 'Sumo'),
(64, 'Team handball'),
(65, 'Winter Pentathalon'),
(66, 'Modern pentathlon'),
(67, 'Canoeing'),
(68, 'Roque'),
(69, 'Squash'),
(70, 'Roller sport'),
(71, 'Greco-Roman wrestling'),
(72, 'Diving'),
(73, 'Sailing'),
(74, 'Trampoline'),
(75, 'Savate'),
(76, 'Tug of war'),
(77, 'Contract bridge'),
(78, 'Show jumping'),
(79, 'Field hockey'),
(80, 'Orienteering'),
(81, 'Canne de combat'),
(82, 'Water polo'),
(83, 'Skijoring'),
(84, 'Race walking'),
(85, 'Track cycling'),
(86, 'Air sports'),
(87, 'Lifesaving'),
(88, 'Cricket'),
(89, 'Mountain biking'),
(90, 'Ice Hockey'),
(91, 'Taekwondo'),
(92, 'Alpine skiing'),
(93, 'Water skiing'),
(94, 'Volleyball'),
(95, 'Snowboarding'),
(96, 'Shooting sports'),
(97, 'Motorsport'),
(98, 'Swimming'),
(99, 'Luge'),
(100, 'Military patrol'),
(101, 'Dogsled racing'),
(102, 'Bobsleigh'),
(103, 'Basketball'),
(104, 'Australian rules football'),
(105, 'Pesäpallo'),
(106, 'Triathlon'),
(107, 'Table tennis'),
(108, 'Badminton'),
(109, 'Roller hockey'),
(110, 'Biathlon'),
(111, 'Ice stock sport'),
(112, 'Road running'),
(113, 'Billiards'),
(114, 'Croquet'),
(115, 'Freestyle skiing'),
(116, 'Beach volleyball'),
(117, 'Canoe Slalom'),
(118, 'Eventing'),
(119, 'Track and field athletics'),
(120, 'Surfing'),
(121, 'Lacrosse'),
(122, 'Tennis'),
(123, 'Football');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
