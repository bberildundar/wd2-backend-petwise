-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jun 23, 2023 at 01:58 PM
-- Server version: 10.11.2-MariaDB-1:10.11.2+maria~ubu2204
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `petwise-wd2`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(1000) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `role` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `role`) VALUES
(5, 'mark@email.com', '$2y$10$/XSKRG95BmTGTwpPURrkIeM5d.X.L1tJe54Sr452DVz8.1hA.Ho/O', 1),
(7, 'beth@email.com', '$2y$10$QTPfBUhrAqPGuqci4pSP9.JGf/lrY1Wc./..GT5WmWAa2MvyKRyUq', 0),
(25, 'user@email.com', '$2y$10$zqRfZ5g1y8iY5KFl6pYeIuNX5rAsNsSWKmm/4fMEU3H.NpAhdLRxO', 0);

-- --------------------------------------------------------

--
-- Table structure for table `vet`
--

CREATE TABLE `vet` (
  `id` int(11) NOT NULL,
  `firstName` varchar(1000) DEFAULT NULL,
  `lastName` varchar(1000) DEFAULT NULL,
  `specialization` varchar(1000) DEFAULT NULL,
  `imageURL` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vet`
--

INSERT INTO `vet` (`id`, `firstName`, `lastName`, `specialization`, `imageURL`) VALUES
(1, 'Samantha', 'Patel', 'Canine Oncology', '/images/vet-2.jpg'),
(2, 'Michael', 'Rodriguez', 'Small Animal Surgery', '/images/vet-4.jpg'),
(3, 'Emily', 'Nguyen', 'Orthopedic Surgery', '/images/vet-1.jpg'),
(4, 'Eric', 'Chavez', 'Feline Medicine', '/images/vet-3.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vet`
--
ALTER TABLE `vet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `vet`
--
ALTER TABLE `vet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
