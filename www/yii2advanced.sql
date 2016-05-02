-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2016 at 10:14 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yii2advanced`
--

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
`id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `name`) VALUES
(1, 'Surat'),
(2, 'Vadodara');

-- --------------------------------------------------------

--
-- Table structure for table `city_area`
--

CREATE TABLE IF NOT EXISTS `city_area` (
`id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `city_area`
--

INSERT INTO `city_area` (`id`, `city_id`, `name`) VALUES
(1, 1, 'City Light'),
(2, 1, 'Nanpura'),
(3, 2, 'Atladara'),
(4, 1, 'Sargam'),
(5, 2, 'Op Road'),
(6, 1, 'Piplod');

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1461157536),
('m130524_201442_init', 1461157550);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
`id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `address` text NOT NULL,
  `mobile` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `name`, `address`, `mobile`, `date`) VALUES
(12, 'Kamlesh', 'klklaf', 64, '0000-00-00 00:00:00'),
(13, 'nirav parekh', 'baroda', 2147483647, '2016-04-27 11:06:23'),
(14, 'Pratik ', 'sdgsdg', 2147483647, '2016-04-27 12:42:40');

-- --------------------------------------------------------

--
-- Table structure for table `order_stall`
--

CREATE TABLE IF NOT EXISTS `order_stall` (
`id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `stall_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_stall`
--

INSERT INTO `order_stall` (`id`, `order_id`, `stall_id`) VALUES
(15, 12, 2),
(16, 12, 3),
(17, 13, 11),
(18, 13, 12),
(19, 14, 5),
(20, 14, 7);

-- --------------------------------------------------------

--
-- Table structure for table `stall`
--

CREATE TABLE IF NOT EXISTS `stall` (
`id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stall`
--

INSERT INTO `stall` (`id`, `area_id`, `name`) VALUES
(1, 1, 'Jaani Farsan'),
(2, 1, 'Kamlesh Paan'),
(3, 1, 'Amoul Ice'),
(4, 1, 'Rahul Mithai'),
(5, 2, 'Sainath'),
(6, 2, 'Gopinath Farsan'),
(7, 2, 'Kailash'),
(8, 2, 'Kalu Paan'),
(9, 5, 'Alka Restorant'),
(10, 5, 'Raju omlate'),
(11, 3, 'Nand Restorant'),
(12, 3, 'Nrayanwadi'),
(13, 5, 'Mahakali Sevusal'),
(14, 3, 'Premvati');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `city`
--
ALTER TABLE `city`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city_area`
--
ALTER TABLE `city_area`
 ADD PRIMARY KEY (`id`), ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
 ADD PRIMARY KEY (`version`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_stall`
--
ALTER TABLE `order_stall`
 ADD PRIMARY KEY (`id`), ADD KEY `order_id` (`order_id`,`stall_id`), ADD KEY `stall_id` (`stall_id`);

--
-- Indexes for table `stall`
--
ALTER TABLE `stall`
 ADD PRIMARY KEY (`id`), ADD KEY `area_id` (`area_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD UNIQUE KEY `email` (`email`), ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `city_area`
--
ALTER TABLE `city_area`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `order_stall`
--
ALTER TABLE `order_stall`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `stall`
--
ALTER TABLE `stall`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `city_area`
--
ALTER TABLE `city_area`
ADD CONSTRAINT `city_area_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `order_stall`
--
ALTER TABLE `order_stall`
ADD CONSTRAINT `order_stall_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `order_stall_ibfk_2` FOREIGN KEY (`stall_id`) REFERENCES `stall` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `stall`
--
ALTER TABLE `stall`
ADD CONSTRAINT `stall_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `city_area` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
