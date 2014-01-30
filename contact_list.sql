-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 31, 2014 at 06:46 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `contact_list`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `category` varchar(30) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category`) VALUES
(1, 'Family'),
(2, 'Friend'),
(3, 'Office'),
(4, 'Dating'),
(5, 'Client');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `category_id` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `address`, `category_id`) VALUES
(1, 'lorem', 'lorem@sample.com', '08787878', 'lorem address', 1),
(3, 'Lorem', 'Lorem@sample.com', '', 'Address for Lorem', 3),
(4, 'ipsum', 'ipsum@sample.com', '', 'Address for ipsum', 1),
(5, 'dolor', 'dolor@sample.com', '', 'Address for dolor', 4),
(6, 'sit', 'sit@sample.com', '', 'Address for sit', 3),
(7, 'amet,', 'amet,@sample.com', '', 'Address for amet,', 5),
(8, 'consectetur', 'consectetur@sample.com', '', 'Address for consectetur', 2),
(9, 'adipisicing', 'adipisicing@sample.com', '', 'Address for adipisicing', 1),
(10, 'elit,', 'elit,@sample.com', '', 'Address for elit,', 2),
(11, 'sed', 'sed@sample.com', '', 'Address for sed', 5),
(12, 'do', 'do@sample.com', '', 'Address for do', 2),
(13, 'eiusmod\ntempor', 'eiusmod\ntempor@sample.com', '', 'Address for eiusmod\ntempor', 1),
(14, 'incididunt', 'incididunt@sample.com', '', 'Address for incididunt', 4),
(15, 'ut', 'ut@sample.com', '', 'Address for ut', 1),
(16, 'labore', 'labore@sample.com', '', 'Address for labore', 1),
(17, 'et', 'et@sample.com', '', 'Address for et', 2),
(18, 'dolore', 'dolore@sample.com', '', 'Address for dolore', 4),
(19, 'magna', 'magna@sample.com', '', 'Address for magna', 3),
(20, 'aliqua', 'aliqua.@sample.com', '', 'Address for aliqua.', 4),
(21, 'Ut', 'Ut@sample.com', '', 'Address for Ut', 2),
(22, 'enim', 'enim@sample.com', '', 'Address for enim', 4),
(23, 'ad', 'ad@sample.com', '', 'Address for ad', 5),
(24, 'minim', 'minim@sample.com', '', 'Address for minim', 5),
(25, 'veniam,\nquis', 'veniam,\nquis@sample.com', '', 'Address for veniam,\nquis', 3),
(26, 'nostrud', 'nostrud@sample.com', '', 'Address for nostrud', 4),
(27, 'exercitation', 'exercitation@sample.com', '', 'Address for exercitation', 1),
(28, 'ullamco', 'ullamco@sample.com', '', 'Address for ullamco', 5),
(29, 'laboris', 'laboris@sample.com', '', 'Address for laboris', 4),
(30, 'nisi', 'nisi@sample.com', '', 'Address for nisi', 3),
(31, 'ut', 'ut@sample.com', '', 'Address for ut', 5),
(32, 'aliquip', 'aliquip@sample.com', '', 'Address for aliquip', 3),
(33, 'ex', 'ex@sample.com', '', 'Address for ex', 3),
(34, 'ea', 'ea@sample.com', '', 'Address for ea', 2),
(35, 'commodo\nconsequat', 'commodo\nconsequat.@sample.com', '', 'Address for commodo\nconsequat.', 2),
(36, 'Duis', 'Duis@sample.com', '', 'Address for Duis', 5),
(37, 'aute', 'aute@sample.com', '', 'Address for aute', 2),
(38, 'irure', 'irure@sample.com', '', 'Address for irure', 3),
(39, 'dolor', 'dolor@sample.com', '', 'Address for dolor', 5),
(40, 'in', 'in@sample.com', '', 'Address for in', 4),
(41, 'reprehenderit', 'reprehenderit@sample.com', '', 'Address for reprehenderit', 5),
(42, 'in', 'in@sample.com', '', 'Address for in', 2),
(43, 'voluptate', 'voluptate@sample.com', '', 'Address for voluptate', 5),
(44, 'velit', 'velit@sample.com', '', 'Address for velit', 2),
(45, 'esse\ncillum', 'esse\ncillum@sample.com', '', 'Address for esse\ncillum', 4),
(46, 'dolore', 'dolore@sample.com', '', 'Address for dolore', 3),
(47, 'eu', 'eu@sample.com', '', 'Address for eu', 4),
(48, 'fugiat', 'fugiat@sample.com', '', 'Address for fugiat', 2),
(49, 'nulla', 'nulla@sample.com', '', 'Address for nulla', 3),
(50, 'pariatur', 'pariatur.@sample.com', '', 'Address for pariatur.', 1),
(51, 'Excepteur', 'Excepteur@sample.com', '', 'Address for Excepteur', 3),
(52, 'sint', 'sint@sample.com', '', 'Address for sint', 5),
(53, 'occaecat', 'occaecat@sample.com', '', 'Address for occaecat', 4),
(54, 'cupidatat', 'cupidatat@sample.com', '', 'Address for cupidatat', 3),
(55, 'non\nproident,', 'non\nproident,@sample.com', '', 'Address for non\nproident,', 5),
(56, 'sunt', 'sunt@sample.com', '', 'Address for sunt', 4),
(57, 'in', 'in@sample.com', '', 'Address for in', 5),
(58, 'culpa', 'culpa@sample.com', '', 'Address for culpa', 4),
(59, 'qui', 'qui@sample.com', '', 'Address for qui', 1),
(60, 'officia', 'officia@sample.com', '', 'Address for officia', 2),
(61, 'deserunt', 'deserunt@sample.com', '', 'Address for deserunt', 1),
(62, 'mollit', 'mollit@sample.com', '', 'Address for mollit', 2),
(63, 'anim', 'anim@sample.com', '', 'Address for anim', 3),
(64, 'id', 'id@sample.com', '', 'Address for id', 1),
(65, 'est', 'est@sample.com', '', 'Address for est', 2),
(66, 'laborum', 'laborum.@sample.com', '', 'Address for laborum.', 4);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
