-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 11:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vivtech`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobsheet`
--

CREATE TABLE `jobsheet` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `complaint` text DEFAULT NULL,
  `fault` text DEFAULT NULL,
  `repair` text DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobsheet`
--

INSERT INTO `jobsheet` (`id`, `date`, `time`, `complaint`, `fault`, `repair`, `hotel_id`, `person_id`) VALUES
(1, '2025-07-08', '14:19:00', 'news not running', 'modulator hang', 'reboot the modulator', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobsheet`
--
ALTER TABLE `jobsheet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jobsheet_hotel` (`hotel_id`),
  ADD KEY `fk_jobsheet_person` (`person_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobsheet`
--
ALTER TABLE `jobsheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobsheet`
--
ALTER TABLE `jobsheet`
  ADD CONSTRAINT `fk_jobsheet_hotel` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jobsheet_person` FOREIGN KEY (`person_id`) REFERENCES `hotel_person` (`picid`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
