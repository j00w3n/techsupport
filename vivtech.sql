-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2025 at 11:29 AM
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
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `state` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`id`, `name`, `state`) VALUES
(1, 'Mercure Living', 'Selangor'),
(2, 'Imperial Regency', 'Kuala Lumpur'),
(3, 'Wyndham Grand Bangsar', 'Kuala Lumpur'),
(16, 'Aloft', 'Kuala Lumpur');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_person`
--

CREATE TABLE `hotel_person` (
  `picid` int(11) NOT NULL,
  `picname` varchar(255) NOT NULL,
  `email` varchar(45) NOT NULL,
  `hotel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_person`
--

INSERT INTO `hotel_person` (`picid`, `picname`, `email`, `hotel_id`) VALUES
(1, 'aza', 'aza@imperial.com', 2),
(2, 'haziq', 'haziq@gmail.com', 2);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_id`, `stock_quantity`) VALUES
(2, 2, 39),
(3, 3, 10);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'DVB Tuner', '2025-07-11 03:24:27', '2025-07-11 03:24:27'),
(3, 'Souka Modulator', '2025-07-11 03:28:37', '2025-07-11 03:28:37');

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
  `person_id` int(11) DEFAULT NULL,
  `task_type` enum('troubleshoot','installation','dismantle') NOT NULL DEFAULT 'troubleshoot',
  `troubleshoot_note` text DEFAULT NULL,
  `install_note` text DEFAULT NULL,
  `dismantle_note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobsheet`
--

INSERT INTO `jobsheet` (`id`, `date`, `time`, `complaint`, `fault`, `repair`, `hotel_id`, `person_id`, `task_type`, `troubleshoot_note`, `install_note`, `dismantle_note`) VALUES
(1, '2025-07-08', '14:19:00', 'news not running', 'modulator hang', 'reboot the modulator', 1, NULL, 'troubleshoot', NULL, NULL, NULL),
(4, '2025-07-11', '14:33:00', '-', '-', '-', 2, 1, 'troubleshoot', NULL, NULL, NULL),
(5, '2025-07-17', '17:06:00', '-', '-', '-', 2, 1, 'troubleshoot', NULL, NULL, NULL),
(6, '2025-07-12', '14:31:00', '', '', '', 2, 1, 'troubleshoot', NULL, NULL, NULL),
(7, '2025-07-19', '17:09:00', '-', '-', '-', 1, NULL, 'troubleshoot', NULL, NULL, NULL),
(8, '0000-00-00', '00:00:00', '', '-', '-', 2, 1, 'troubleshoot', NULL, NULL, NULL),
(9, '0000-00-00', '00:00:00', '-', '-', '-', 2, 2, 'troubleshoot', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobsheet_items`
--

CREATE TABLE `jobsheet_items` (
  `id` int(11) NOT NULL,
  `jobsheet_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobsheet_items`
--

INSERT INTO `jobsheet_items` (`id`, `jobsheet_id`, `item_id`, `quantity`) VALUES
(1, 4, 2, 3),
(2, 5, 2, 3),
(3, 5, 3, 2),
(4, 6, 2, 15);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_person`
--
ALTER TABLE `hotel_person`
  ADD PRIMARY KEY (`picid`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_id` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobsheet`
--
ALTER TABLE `jobsheet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jobsheet_hotel` (`hotel_id`),
  ADD KEY `fk_jobsheet_person` (`person_id`);

--
-- Indexes for table `jobsheet_items`
--
ALTER TABLE `jobsheet_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobsheet_id` (`jobsheet_id`),
  ADD KEY `item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `hotel_person`
--
ALTER TABLE `hotel_person`
  MODIFY `picid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobsheet`
--
ALTER TABLE `jobsheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jobsheet_items`
--
ALTER TABLE `jobsheet_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hotel_person`
--
ALTER TABLE `hotel_person`
  ADD CONSTRAINT `hotel_person_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jobsheet`
--
ALTER TABLE `jobsheet`
  ADD CONSTRAINT `fk_jobsheet_hotel` FOREIGN KEY (`hotel_id`) REFERENCES `hotel` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jobsheet_person` FOREIGN KEY (`person_id`) REFERENCES `hotel_person` (`picid`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `jobsheet_items`
--
ALTER TABLE `jobsheet_items`
  ADD CONSTRAINT `jobsheet_items_ibfk_1` FOREIGN KEY (`jobsheet_id`) REFERENCES `jobsheet` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jobsheet_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
