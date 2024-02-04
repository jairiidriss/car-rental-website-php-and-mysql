-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2024 at 02:09 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(3) NOT NULL,
  `car_name` varchar(30) NOT NULL,
  `brand_id` int(3) NOT NULL,
  `type_id` int(3) NOT NULL,
  `color` varchar(20) NOT NULL,
  `model` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `car_name`, `brand_id`, `type_id`, `color`, `model`, `description`) VALUES
(1, 'Porsche Boxster', 6, 4, 'Red', '2017', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent venenatis in odio quis cursus. Pel'),
(2, 'Audi A5', 1, 4, 'Red', '2017', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent venenatis in odio quis cursus. Pel'),
(3, 'Mercedes-Benz CLS', 4, 4, 'Blue', '2019', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent venenatis in odio quis cursus. Pel'),
(4, 'Audi A7', 1, 6, 'Blue', '2019', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent venenatis in odio quis cursus. Pel');

-- --------------------------------------------------------

--
-- Table structure for table `car_brands`
--

CREATE TABLE `car_brands` (
  `brand_id` int(3) NOT NULL,
  `brand_name` varchar(50) NOT NULL,
  `brand_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `car_brands`
--

INSERT INTO `car_brands` (`brand_id`, `brand_name`, `brand_image`) VALUES
(1, 'Audi', 'Audi-A4-Avant-1.jpg'),
(2, 'BMW', 'bmw-3-series-sedan.jpg'),
(3, 'Lexus', '2016-Lexus-RX-350-BM-01.jpg'),
(4, 'Mercedes Benz', 'Mercedes-C-Class-Estate-1.jpg'),
(5, 'MINI', '2016-MINI-Cooper-S-Clubman-ALL4.jpg'),
(6, 'Porsche', 'P14_0596_a4_rgb-1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `car_types`
--

CREATE TABLE `car_types` (
  `type_id` int(3) NOT NULL,
  `type_label` varchar(50) NOT NULL,
  `type_description` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `car_types`
--

INSERT INTO `car_types` (`type_id`, `type_label`, `type_description`) VALUES
(1, 'Sedan', 'A sedan has four doors and a traditional trunk.'),
(4, 'Coupe', 'A coupe has historically been considered a two-door car with a trunk and a solid roof.'),
(6, 'HATCHBACK', 'Traditionally, the term \"hatchback\" has meant a compact or subcompact sedan with a squared-off roof and a rear flip-up hatch door that provides access to the vehicle\'s cargo area instead of a conventional trunk.');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(10) NOT NULL,
  `full_name` varchar(30) NOT NULL,
  `client_email` varchar(100) NOT NULL,
  `client_phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `full_name`, `client_email`, `client_phone`) VALUES
(5, 'John Doe', 'john_doe@gmail.com', '0123456789');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(10) NOT NULL,
  `client_id` int(10) NOT NULL,
  `car_id` int(3) NOT NULL,
  `pickup_date` date NOT NULL,
  `return_date` date NOT NULL,
  `pickup_location` varchar(50) NOT NULL,
  `return_location` varchar(50) NOT NULL,
  `canceled` tinyint(1) NOT NULL DEFAULT 0,
  `cancellation_reason` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `client_id`, `car_id`, `pickup_date`, `return_date`, `pickup_location`, `return_location`, `canceled`, `cancellation_reason`) VALUES
(1, 1, 1, '2021-05-11', '2021-05-17', 'Agadir', 'Agadir', 0, NULL),
(2, 2, 3, '2021-04-30', '2021-05-06', 'Agadir', 'Agadir', 0, NULL),
(3, 3, 4, '2021-04-30', '2021-05-06', 'Agadir', 'Agadir', 1, 'Changed my mind! Sorry'),
(4, 4, 4, '2021-04-29', '2021-05-02', 'Casablanca', 'Agadir', 0, NULL),
(5, 5, 4, '2024-03-02', '2024-03-05', 'Paris', 'Paris', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `group_id` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `user_email`, `full_name`, `password`, `group_id`) VALUES
(1, 'admin', 'admin.admin@gmail.com', 'Admin Admin', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_brands`
--
ALTER TABLE `car_brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `car_types`
--
ALTER TABLE `car_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `car_brands`
--
ALTER TABLE `car_brands`
  MODIFY `brand_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `car_types`
--
ALTER TABLE `car_types`
  MODIFY `type_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
