-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 21, 2025 at 10:40 AM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_id`, `password`) VALUES
(1, 'admin', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
CREATE TABLE IF NOT EXISTS `complaints` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `complaint` text NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `student_id`, `student_name`, `complaint`, `status`, `created_at`) VALUES
(7, 8, 'Akshar Kakadiya', 'Room no. 102 fan not working', 'Pending', '2025-03-21 13:42:45'),
(4, 9, 'test', 'hii', 'Approved', '2025-03-17 21:07:34'),
(6, 9, 'test', 'hello', 'Approved', '2025-03-17 21:10:40');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

DROP TABLE IF EXISTS `fees`;
CREATE TABLE IF NOT EXISTS `fees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `student_id`, `amount`, `date`) VALUES
(24, 3, '18000.00', '2025-02-08'),
(25, 8, '30000.00', '2025-03-21'),
(23, 7, '20000.00', '2025-02-08'),
(27, 12, '30000.00', '2025-03-19');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE IF NOT EXISTS `leave_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `student_id`, `start_date`, `end_date`, `reason`, `status`) VALUES
(4, 9, '2025-03-21', '2025-03-25', 'hello', 'pending'),
(3, 9, '2025-03-24', '2025-03-31', 'hii', 'approved'),
(5, 8, '2025-03-24', '2025-03-31', 'Family function', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `student_id` int DEFAULT NULL,
  `date_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `student_id`, `date_time`, `created_at`) VALUES
(1, 'Fee payment reminder', 'You 2nd semester fees are pending', 8, '2025-03-02 15:22:17', '2025-03-02 20:52:17'),
(4, 'Grand Feast', 'Our hostel Celebrating grand feast on 4th march at our hostel ground', NULL, '2025-03-02 16:06:03', '2025-03-02 21:36:03');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_no` varchar(10) DEFAULT NULL,
  `room_holders` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL,
  `type` enum('AC','Non-AC') NOT NULL DEFAULT 'Non-AC',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_no`, `room_holders`, `room_status`, `type`) VALUES
(31, '102', NULL, NULL, 'AC'),
(30, '101', NULL, NULL, 'Non-AC');

-- --------------------------------------------------------

--
-- Table structure for table `student_log`
--

DROP TABLE IF EXISTS `student_log`;
CREATE TABLE IF NOT EXISTS `student_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `guardian_name` varchar(100) NOT NULL,
  `guardian_mobile` varchar(15) NOT NULL,
  `user_mobile` varchar(15) NOT NULL,
  `course` varchar(100) NOT NULL,
  `college_year` varchar(10) NOT NULL,
  `college_name` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `age` int NOT NULL,
  `address` text NOT NULL,
  `starting_date` date NOT NULL,
  `room_number` varchar(50) DEFAULT NULL,
  `status` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_log`
--

INSERT INTO `student_log` (`id`, `name`, `email`, `password`, `guardian_name`, `guardian_mobile`, `user_mobile`, `course`, `college_year`, `college_name`, `birthday`, `age`, `address`, `starting_date`, `room_number`, `status`) VALUES
(9, 'test', 'test@gmail.com', '1234', 'test', '4444444444', '4444444444', 'test', '1', 'test', '2006-07-26', 18, 'test', '2025-02-26', '102', 1),
(10, 'test1', 'test1@gmail.com', '1234', 'test1', '1111111111', '1111111111', 'test1', '1', 'test1', '2020-11-18', 4, 'test1', '2025-02-28', NULL, 1),
(8, 'Akshar Kakadiya', 'ak@gmail.com', '1234', 'Hareshbhai Kakadiya', '9426879517', '8238538023', 'MCA', '1', 'IIT', '2005-07-28', 19, '63, Mohandeep Society, Ambatalawdi, Karatgam, Surat', '2025-02-19', '102', 1),
(11, 'test2', 'test2@gmail.com', '1234', 'test2', '6666666666', '6666666666', 'test2', '2', 'test2', '2022-06-23', 2, 'test2', '2025-02-25', '101', 1),
(12, 'krish', 'krish@gmail.com', '1234', '', '', '', '', '', '', '0000-00-00', 0, '', '0000-00-00', NULL, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
