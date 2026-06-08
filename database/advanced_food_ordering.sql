-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2026 at 11:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `advanced_food_ordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `fullname`, `email`, `created_at`) VALUES
(4, 'admin', '$2y$10$8TEhzoyqFvsFXvABsk35X.EXTef..PNgnSN8WO4wduxjOONkQjk2O', 'Main Admin', 'admin@email.com', '2025-08-17 22:05:06');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `payment_type` varchar(50) NOT NULL CHECK (`payment_type` in ('Now','Later'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `created_at`) VALUES
(1, 'Kisinia', '2025-08-19 13:18:39'),
(4, 'Beef', '2026-06-07 13:17:08'),
(5, 'Burger', '2026-06-07 13:17:18'),
(6, 'Flatbread', '2026-06-07 13:17:31'),
(7, 'Chicken', '2026-06-07 13:17:40'),
(8, 'Juice', '2026-06-07 13:17:50'),
(9, 'Corn', '2026-06-07 13:17:58'),
(10, 'Crunchy', '2026-06-07 13:18:07'),
(11, 'Fish', '2026-06-07 13:18:14'),
(12, 'Pilau', '2026-06-07 13:18:55'),
(13, 'Pizza', '2026-06-07 13:19:04'),
(14, 'Samonsa', '2026-06-07 13:19:10'),
(15, 'Sausage', '2026-06-07 13:19:18'),
(16, 'Seafood', '2026-06-07 13:19:33'),
(17, 'Shrines', '2026-06-07 13:19:43'),
(18, 'Spaghetti', '2026-06-07 13:19:57'),
(19, 'Ugali', '2026-06-07 13:20:05'),
(20, 'Biryan', '2026-06-07 13:28:57'),
(21, 'MIHOGO YA KUCHEMSHA', '2026-06-07 15:03:06');

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `food_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`food_id`, `name`, `price`, `image_url`, `category_id`, `created_at`, `updated_at`, `is_active`) VALUES
(56, 'CHICKEN PILAU', 6000, 'images/food_6a2570918fa704.85657736.jpg', 12, '2026-06-07 06:22:25', '2026-06-07 06:22:25', 1),
(57, 'BEEF', 10000, 'images/food_6a25717fbdc129.96009992.jpg', 4, '2026-06-07 06:26:23', '2026-06-07 06:26:23', 1),
(58, 'BEEF', 10000, 'images/food_6a2571b39ec1d7.27131794.jpg', 4, '2026-06-07 06:27:15', '2026-06-07 06:27:15', 1),
(59, 'BEEF', 10000, 'images/food_6a2571cdb99b77.75554010.jpg', 4, '2026-06-07 06:27:41', '2026-06-07 06:27:41', 1),
(60, 'BIRYANI', 6000, 'images/food_6a257245e67846.15305185.jpg', 20, '2026-06-07 06:29:41', '2026-06-07 06:29:41', 1),
(61, 'BURGER', 15000, 'images/food_6a257410eb4710.74241614.jpg', 5, '2026-06-07 06:37:20', '2026-06-07 06:37:20', 1),
(62, 'BURGER', 10000, 'images/food_6a2574631bf731.79888791.jpg', 5, '2026-06-07 06:38:43', '2026-06-07 06:38:43', 1),
(63, 'CHAPATI', 2000, 'images/food_6a2574dade7ba8.17994494.jpg', 6, '2026-06-07 06:40:42', '2026-06-07 06:40:42', 1),
(64, 'CHICKEN', 7000, 'images/food_6a257642b39469.64864022.jpg', 7, '2026-06-07 06:46:42', '2026-06-07 06:46:42', 1),
(65, 'JUICE', 3000, 'images/food_6a257673437d62.32030361.jpg', 8, '2026-06-07 06:47:31', '2026-06-07 06:47:31', 1),
(66, 'CORN', 2000, 'images/food_6a257697a222a6.56214300.jpg', 9, '2026-06-07 06:48:07', '2026-06-07 06:48:07', 1),
(67, 'FISH', 12000, 'images/food_6a2576bdac4139.05480733.jpg', 11, '2026-06-07 06:48:45', '2026-06-07 06:48:45', 1),
(68, 'CHRUNCHY', 15000, 'images/food_6a257732223656.10506064.jpg', 10, '2026-06-07 06:50:42', '2026-06-07 06:50:42', 1),
(69, 'CHRUNCHY', 15000, 'images/food_6a257732428f19.85803832.jpg', 10, '2026-06-07 06:50:42', '2026-06-07 06:50:42', 1),
(70, 'Chapati na Roast', 10000, 'images/food_6a2588cbb32437.48276664.jpg', 14, '2026-06-07 08:05:47', '2026-06-07 08:05:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `timestamp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `timestamp`) VALUES
(1, 2, 'Payment pending for order #11.', '2025-08-11 12:00:07'),
(2, 2, 'Payment successful for order #12.', '2025-08-11 12:22:38'),
(3, 2, 'Payment successful for order #13.', '2025-08-11 12:36:10'),
(4, 2, 'Payment successful for order #16.', '2025-08-11 12:55:25'),
(5, 2, 'Payment pending for order #17.', '2025-08-11 12:57:49'),
(6, 2, 'Payment successful for order #25.', '2025-08-11 15:46:38'),
(7, 2, 'Payment successful for order #26.', '2025-08-11 15:50:10'),
(8, 7, 'Payment successful for order #27.', '2025-08-11 17:23:45'),
(9, 11, 'Payment successful for order #28.', '2025-08-13 11:50:23'),
(10, 11, 'Payment successful for order #29.', '2025-08-13 12:41:19'),
(11, 12, 'Payment successful for order #30.', '2025-08-13 12:45:11'),
(12, 13, 'Payment successful for order #31.', '2025-08-16 11:55:42');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `deadline` varchar(50) DEFAULT NULL,
  `total_amount` int(11) DEFAULT 0,
  `customer_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `delivery_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_method` varchar(100) NOT NULL,
  `notes` varchar(100) NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL,
  `service_fee` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `promo_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_time` varchar(50) NOT NULL,
  `payment_type` varchar(50) NOT NULL CHECK (`payment_type` in ('Now','Later')),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(100) DEFAULT NULL,
  `payment_account` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `user_id`, `amount`, `payment_time`, `payment_type`, `payment_status`, `payment_method`, `payment_account`) VALUES
(1, 2, 40000, '2025-08-11 11:24:01', 'Now', 'pending', NULL, NULL),
(2, 2, 20000, '2025-08-11 11:26:48', 'Later', 'pending', NULL, NULL),
(3, 2, 35000, '2025-08-11 12:00:07', 'Later', 'pending', NULL, NULL),
(4, 2, 75000, '2025-08-11 12:22:38', 'Now', 'pending', NULL, NULL),
(5, 2, 19999, '2025-08-11 12:36:10', 'Now', 'pending', NULL, NULL),
(6, 2, 55000, '2025-08-11 12:55:25', 'Now', 'paid', 'Tigo Pesa', NULL),
(7, 2, 19992, '2025-08-11 12:57:49', 'Later', 'pending', 'Airtel Money', NULL),
(8, 2, 20000, '2025-08-11 15:46:38', 'Now', 'paid', 'Tigo Pesa', NULL),
(9, 2, 20000, '2025-08-11 15:50:10', 'Now', 'paid', 'Tigo Pesa', NULL),
(10, 7, 20000, '2025-08-11 17:23:45', 'Now', 'paid', 'Airtel Money', NULL),
(11, 11, 20000, '2025-08-13 11:50:23', 'Now', 'paid', 'Tigo Pesa', NULL),
(12, 11, 35000, '2025-08-13 12:41:19', 'Now', 'paid', 'Tigo Pesa', NULL),
(13, 12, 60000, '2025-08-13 12:45:11', 'Now', 'paid', 'Airtel Money', NULL),
(14, 13, 2000, '2025-08-16 11:55:42', 'Now', 'paid', 'Tigo Pesa', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `promo_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount_type` enum('amount','percent') NOT NULL DEFAULT 'amount',
  `discount_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone`, `password`, `is_active`, `status`) VALUES
(1, 'anna', 'anna@gmail.com', NULL, '$2y$10$eyaHxa55Gt37wjWI.3JqK.Odx9UP3p3810HMHFA2xyknqh.Fmoe.m', 1, 'active'),
(2, 'issa', 'issa@gmail.com', NULL, '$2y$10$ItoKGtYN/N5I.txSo2F.Z.5xx/9tqFQthzu8UKfnlxmhS17yai/uW', 1, 'active'),
(3, 'blezi', 'blezite@gmail.com', NULL, '$2y$10$yISTj6jG/kZzkp7E.3toXOII0o68lMQfx7bBf/FMmJJhPOaEst2va', 1, 'active'),
(7, 'ally', 'ally@gmail.com', NULL, '$2y$10$GGCIyEmN.O9h.BnZD515O.zxTbjF8xyr8Gob7A5OKVbIufA/Lljay', 1, 'active'),
(11, 'ratifa amrani', 'ratifaamrani123@gmail.com', NULL, '$2y$10$sru7QIFNCXRjImIcq4/n9OmWq4IopC7x5vprkElglXoA5KcMQQdqa', 1, 'active'),
(12, 'james', 'james@gmail.com', NULL, '$2y$10$vp53WTZGDelw4SGsRawUD.P2CF7nqH9wyG1H9Pvm4dp6lvfK7ke3O', 1, 'active'),
(13, 'leila', 'leila@gmai.com', NULL, '$2y$10$fLI16/iWqDuZaOXOmnSCIOGd4Ave606gZrh0TR3CrM88ipGpvnr8e', 1, 'active'),
(14, 'utha', 'utha@gmail.com', NULL, '$2y$10$FjegGFWzqQFzXk4bknmESOT8sMiG4Gv0Emsfm7LlqMSzOgMF2A3sq', 1, 'active'),
(15, 'irene', 'irene@gmail.com', NULL, '$2y$10$chUtuOHqThXh9YfjYPpid.DRU4XRa4r25kTHEm/mcJ7H3vhFnKWuG', 1, 'active'),
(16, 'isha', 'isha@gmail.com', NULL, '$2y$10$EPVf0NoZy7iE3Hd6Ipxs3.J179m2DvvhcIhwJi/hQHPzZP.WoPoHS', 1, 'active'),
(17, 'zuta', 'zuta@gmail.com', NULL, '$2y$10$qDxaeP9rHtkxl6zaUuCc2eiPzcwxDA7XKpgFvTfAVZpcHh1gKq8Ze', 1, 'active'),
(18, 'kihatara', 'kihatara@gmail.com', '0678844434', '$2y$10$V7HZA9KRLConB40krnfIiOAFzzQPVWf0Fx0pPihoz4KXgrAXEv07i', 1, 'active'),
(19, 'mwaj', 'mwaj@gmail.com', '0678844434', '$2y$10$e3ObABlE/Gwk2k6Qb3e7e.iJnN2N5xA/BUASoKhlnXfRUmys.umWe', 1, 'active'),
(20, 'grace', 'grace@gmail.com', '0678844444', '$2y$10$0ICc5dRouotWXUG2RV/9be.MmDjpFNlmK8PRsTCAOwr6qEUxNBdje', 1, 'active'),
(21, 'juma juma', 'ratifaamrani12345@gmail.com', '0678844434', '$2y$10$qLjmpj20vzKpVyZ9GqY7DOjGmZ8/J9q9Oiov8UQVIRY6C/3PG2Yzi', 1, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`food_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`promo_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `promo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `food_items` (`food_id`) ON DELETE CASCADE;

--
-- Constraints for table `food_items`
--
ALTER TABLE `food_items`
  ADD CONSTRAINT `fk_food_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `food_items` (`food_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
