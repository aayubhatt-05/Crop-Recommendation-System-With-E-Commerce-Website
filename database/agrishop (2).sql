-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 10:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agrishop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$riXRUp/WjXoXwozWu2Cy0edVn20D.JIsdA5ZVV5BifhSyJGqfVnc2', '2025-04-14 18:09:11');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `farmer_id`, `product_id`, `quantity`, `created_at`) VALUES
(17, 2, 2, 1, '2025-04-12 18:34:54'),
(18, 2, 1, 2, '2025-04-12 18:39:33');

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Utsav Modi', 'utsav@gmail.com', 'i have query', '2025-04-12 16:52:07'),
(2, 'aayushi', 'aayushi@gmail.com', 'i have doubt', '2025-04-14 12:36:45');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `farm_name` varchar(100) NOT NULL,
  `farm_location` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `fullname`, `email`, `phone`, `farm_name`, `farm_location`, `password`, `created_at`) VALUES
(1, 'Priya', 'priya24@gmail.com', '1234567891', 'Hindustan', 'Gujarat', '$2y$10$p4hSZ59ImS0eWJa5TnDFpOJyY5Y7Ojcf8/.Hm40wEBuhW1SQV7Mji', '2025-04-11 09:05:15'),
(2, 'Ramesh', 'ramesh@gmail.com', '9632587410', 'India', 'Gujarat', '$2y$10$68N3FJS/wKbFfF3Gbo3lQOXp/t/Eui4rH5amNNxuXV2PH5TsD9v3m', '2025-04-11 13:21:53'),
(3, 'Aayushi Trivedi', 'aayushi@gmail.com', '7043822411', 'Land', 'Punjab', '$2y$10$72Fcln/pknKawRw7qT22WeA59rvfIQAE/OS4ANdjlUF/g67pISoya', '2025-04-11 13:28:33');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `image`, `description`, `created_at`) VALUES
(1, 'Weed Killer Pro', 'herbicides', 25.00, 'herbicide1.jpg', 'Effective herbicide for weed control.', '2025-04-11 08:49:50'),
(2, 'Crop Boost', 'growth-promoters', 15.00, 'growth1.jpg', 'Promotes healthy plant growth.', '2025-04-11 08:49:50'),
(3, 'Fungus Shield', 'fungicides', 22.00, 'fungicide1.jpg', 'Protects crops from fungal diseases.', '2025-04-11 08:49:50'),
(4, 'Tomato Seeds', 'vegetables', 5.00, 'vegetable1.jpg', 'High-quality tomato seeds.', '2025-04-11 08:49:50'),
(5, 'Apple Seeds', 'fruit-seeds', 6.00, 'fruit1.jpg', 'Seeds for apple trees.', '2025-04-11 08:49:50'),
(6, 'Mini Tractor', 'farm-machinery', 1500.00, 'machine1.jpg', 'Compact tractor for small farms.', '2025-04-11 08:49:50'),
(7, 'Nitrogen Mix', 'nutrients', 20.00, 'nutrient1.jpg', 'Essential nutrient supplement.', '2025-04-11 08:49:50'),
(8, 'Rose Seeds', 'flower-seeds', 4.00, 'flower1.jpg', 'Beautiful rose seeds.', '2025-04-11 08:49:50'),
(9, 'Bug Blaster', 'insecticides', 18.00, 'insecticide1.jpg', 'Powerful insecticide.', '2025-04-11 08:49:50'),
(10, 'Organic Compost', 'organic-farming', 12.00, 'organic1.jpg', 'Natural compost for organic farming.', '2025-04-11 08:49:50'),
(11, 'Cattle Feed', 'animal-husbandry', 35.00, 'animal1.jpg', 'Nutritious feed for livestock.', '2025-04-11 08:49:50'),
(13, 'Utsav Modi', 'animal-husbandry', 10000.00, 'WhatsApp Image 2025-03-14 at 00.31.08_2661cbb3.jpg', 'jkrsghjrhb', '2025-04-14 17:37:26'),
(14, 'DAA Quiz', 'herbicides', 10000.00, 'WhatsApp Image 2025-03-14 at 00.31.08_2661cbb3.jpg', 'jhgh jlvj jh j', '2025-04-14 19:35:08');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `farmer_id`, `product_id`, `created_at`) VALUES
(1, 1, 6, '2025-04-11 09:44:14'),
(2, 2, 1, '2025-04-12 18:07:49'),
(3, 2, 6, '2025-04-14 18:06:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
