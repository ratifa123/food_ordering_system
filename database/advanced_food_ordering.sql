-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 02:39 PM
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
-- Database: `advanced_food_ordering_fixed`
--
CREATE DATABASE IF NOT EXISTS `advanced_food_ordering_fixed` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `advanced_food_ordering_fixed`;

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
(4, 'admin', '$2y$10$K8RTijg/Xy7.MjJqEsGgI.FmRcAQDjfHcltGLVZGyRGbLlnk72Ivm', 'Main Admin', 'admin@email.com', '2025-08-17 22:05:06');

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
(1, 'Kisinia', '2025-08-19 13:18:39');

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
(10, 'BURGER', 7000, 'images/burger2.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(11, 'CHIPS', 2000, 'images/chips1.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(12, 'BREAD', 5000, 'images/bread.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(13, 'NOODLESS', 5000, 'images/noodless.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(14, 'SPAGHETTI', 5000, 'images/spaghetti.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(15, 'FISH', 10000, 'images/seafood.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(16, 'HOT DOG', 5000, 'images/hotdog.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(17, 'PIZZA', 8000, 'images/pizza.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(18, 'BIRYAN', 8000, 'images/biryan.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(19, 'CHICKEN RICE', 8000, 'images/chicken.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(20, 'JUICE', 2000, 'images/juice1.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(21, 'COCOMELON JUICE', 2000, 'images/cocomelon.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(22, 'ORANGE JUICE', 2000, 'images/juice2.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(23, 'LEMON JUICE', 2000, 'images/juice3.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(24, 'MIXER ORANGE JUICE', 2000, 'images/juice4.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(25, 'BBQ', 8000, 'images/beef3.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(26, 'BAKED CHICKEN', 12000, 'images/chicken4.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(27, 'GRILLED BEEF', 10000, 'images/beef2.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(28, 'CRUNCHY CHICKEN', 12000, 'images/crunchy.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(29, 'ROAST KITCHEN', 9000, 'images/chicken2.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(30, 'GRILLED KITCHEN', 9000, 'images/chicken3.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(31, 'BEEF UGALI', 6000, 'images/ugali1.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(32, 'UGALI WITH GREEN LEAFY VEGETABLES', 6000, 'images/ugali2.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(34, 'PILAU', 5000, 'images/pilau1.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(35, 'BEEF PILAU', 8000, 'images/pilau2.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(36, 'CHICKEN PILAU', 8000, 'images/chicken3.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(38, 'SHRINES', 8000, 'images/shrines.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(39, 'FRIED FISH', 9000, 'images/fish1.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(40, 'FRIED FISH WITH PLANTAINS', 10000, 'images/fish2.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(41, 'FRIED FISH WITH CHIPS', 10000, 'images/chips3.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(42, 'SAMOSA', 1000, 'images/samosa.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(43, 'CHIPS SKEWERS', 7000, 'images/chips2.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(48, 'OREO', 4000, 'images/oreo.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(49, 'KISINIA', 50000, 'images/kisinia2.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(50, 'KISINIA CHOMA', 50000, 'images/kisinia1.jpg', NULL, '2025-08-19 12:16:18', '2025-08-19 12:16:18', 1),
(52, 'SKEWERS', 7000, 'images/food_68a47516370286.28530631.jpg', NULL, '2025-08-19 15:28:34', '2025-08-19 15:59:02', 1),
(53, 'breaad', 5000, 'images/food_68a476350046c2.82675278.jpg', NULL, '2025-08-19 16:03:49', '2025-08-19 16:03:49', 1);

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

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `status`, `deadline`, `total_amount`, `customer_name`, `email`, `phone`, `delivery_address`, `delivery_time`, `payment_method`, `notes`, `delivery_fee`, `service_fee`, `tax_amount`, `discount`, `promo_code`) VALUES
(31, 13, '2025-08-16 10:55:19', 'delivered', NULL, 2000, NULL, NULL, NULL, NULL, '2025-08-19 09:08:46', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(32, 15, '2025-08-16 12:58:57', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(33, 15, '2025-08-16 13:08:19', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(34, 15, '2025-08-16 13:18:19', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(35, 15, '2025-08-16 13:23:55', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(36, 15, '2025-08-16 13:26:46', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(37, 15, '2025-08-16 13:37:00', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(38, 15, '2025-08-16 13:39:30', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(39, 15, '2025-08-16 13:46:32', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(40, 15, '2025-08-16 13:50:26', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(41, 15, '2025-08-16 13:56:05', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(42, 15, '2025-08-16 13:57:28', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(43, 15, '2025-08-16 14:03:48', 'pending', NULL, 0, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(45, 16, '2025-08-16 17:43:31', 'pending', NULL, 16000, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(46, 16, '2025-08-16 17:47:31', 'pending', NULL, 5000, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(47, 16, '2025-08-16 17:48:29', 'pending', NULL, 5000, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(48, 16, '2025-08-16 17:49:09', 'pending', NULL, 5000, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(49, 16, '2025-08-16 18:02:05', 'pending', NULL, 2000, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(50, 16, '2025-08-16 18:05:33', 'pending', NULL, 2000, NULL, NULL, NULL, NULL, '2025-08-16 16:30:45', '', '', 0.00, 0.00, 0.00, NULL, NULL),
(54, 18, '2025-08-16 20:49:00', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 20:49:00', 'mpesa', '', 2500.00, 1500.00, 2880.00, 0.00, NULL),
(55, 18, '2025-08-16 20:50:04', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 20:50:04', 'mpesa', '', 2500.00, 1500.00, 2880.00, 0.00, NULL),
(56, 18, '2025-08-16 20:50:11', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 20:50:11', 'mpesa', '', 2500.00, 1500.00, 2880.00, 0.00, NULL),
(57, 18, '2025-08-16 20:52:30', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 20:52:30', 'mpesa', '', 2500.00, 1500.00, 2880.00, 0.00, NULL),
(58, 18, '2025-08-16 22:16:40', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 22:16:40', 'mpesa', '', 2500.00, 1500.00, 2880.00, 0.00, NULL),
(59, 18, '2025-08-16 22:27:54', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 22:27:54', 'mpesa', '', 2500.00, 1500.00, 2880.00, NULL, NULL),
(60, 18, '2025-08-16 22:37:27', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 22:37:27', 'mpesa', '', 2500.00, 1500.00, 2880.00, NULL, NULL),
(61, 18, '2025-08-16 22:44:03', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 22:44:03', 'mpesa', '', 2500.00, 1500.00, 2880.00, NULL, NULL),
(62, 18, '2025-08-16 22:44:09', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 22:44:09', 'mpesa', '', 2500.00, 1500.00, 2880.00, NULL, NULL),
(63, 18, '2025-08-16 22:44:15', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 22:44:15', 'mpesa', '', 2500.00, 1500.00, 2880.00, NULL, NULL),
(64, 18, '2025-08-16 22:45:13', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 22:45:13', 'mpesa', '', 2500.00, 1500.00, 2880.00, NULL, NULL),
(65, 18, '2025-08-16 22:46:31', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 22:46:31', 'mpesa', '', 2500.00, 1500.00, 2880.00, NULL, NULL),
(66, 18, '2025-08-16 22:50:56', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 22:50:56', 'mpesa', '', 2500.00, 1500.00, 2880.00, NULL, NULL),
(67, 18, '2025-08-16 22:55:44', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 22:55:44', 'mpesa', '', 2500.00, 1500.00, 2880.00, 0.00, NULL),
(68, 18, '2025-08-16 23:02:14', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 23:02:14', 'mpesa', '', 2500.00, 1500.00, 2880.00, 0.00, NULL),
(69, 18, '2025-08-16 23:02:26', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 23:02:26', 'mpesa', '', 2500.00, 1500.00, 2880.00, 0.00, NULL),
(70, 18, '2025-08-16 23:03:18', 'pending', NULL, 22880, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 23:03:18', 'mpesa', '', 2500.00, 1500.00, 2880.00, 0.00, NULL),
(71, 18, '2025-08-16 23:17:41', 'pending', NULL, 12260, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mu', '2025-08-16 23:17:41', 'mpesa', '', 2500.00, 1500.00, 1260.00, 0.00, NULL),
(72, 18, '2025-08-16 23:41:10', 'pending', NULL, 9900, 'kihatara', 'kihatara@gmail.com', '0678844434', 'mzumbe', '2025-08-16 23:41:10', 'halopesa', '', 2500.00, 1500.00, 900.00, 0.00, NULL),
(73, 15, '2025-08-17 11:37:28', 'pending', NULL, 26420, 'irene', 'irene@gmail.com', '0678844434', 'mu', '2025-08-17 11:37:28', 'tigopesa', '', 2500.00, 1500.00, 3420.00, 0.00, NULL),
(74, 15, '2025-08-17 18:55:49', 'pending', NULL, 26420, 'irene', 'irene@gmail.com', '0678844434', 'mzumbe', '2025-08-17 18:55:49', 'mpesa', '', 2500.00, 1500.00, 3420.00, 0.00, NULL),
(75, 15, '2025-08-17 20:08:40', 'pending', NULL, 9900, 'irene', 'irene@gmail.com', '0678844434', 'mzumbe', '2025-08-17 20:08:40', 'mpesa', '', 2500.00, 1500.00, 900.00, 0.00, NULL),
(76, 15, '2025-08-17 20:15:12', 'pending', NULL, 6000, 'irene', 'irene@gmail.com', '0678844434', 'mzumbe', '2025-08-17 20:15:12', 'mpesa', '', 2500.00, 1500.00, 0.00, 0.00, NULL),
(77, 15, '2025-08-17 20:25:55', 'pending', NULL, 32000, 'irene', 'irene@gmail.com', '0678844434', 'mzumbe', '2025-08-17 20:25:55', 'mpesa', '', 2500.00, 1500.00, 0.00, 0.00, NULL),
(78, 15, '2025-08-17 20:29:26', 'pending', NULL, 9000, 'irene', 'irene@gmail.com', '0678844434', 'mzumbe', '2025-08-17 20:29:26', 'tigopesa', '', 2500.00, 1500.00, 0.00, 0.00, NULL),
(79, 15, '2025-08-17 20:40:20', 'pending', NULL, 4500, 'irene', 'irene@gmail.com', '0678844434', 'mzumbe', '2025-08-17 20:40:20', 'mpesa', '', 2500.00, 0.00, 0.00, 0.00, NULL),
(80, 15, '2025-08-17 20:55:52', 'inprogress', NULL, 9500, 'irene', 'ratifaamrani85@gmail.com', '0678844434', 'mzumbe', '2025-08-19 09:08:25', 'tigopesa', '', 2500.00, 0.00, 0.00, 0.00, NULL),
(81, 19, '2025-08-17 21:20:48', 'pending', NULL, 7500, 'mwaj', 'mwaj@gmail.com', '0678844434', 'mzumbe', '2025-08-17 21:20:48', 'mpesa', '', 2500.00, 0.00, 0.00, 0.00, NULL),
(82, 20, '2025-08-18 15:45:44', 'pending', NULL, 9500, 'grace', 'grace@gmail.com', '0678844444', 'mzumbe', '2025-08-18 15:45:44', 'mpesa', '', 2500.00, 0.00, 0.00, 0.00, NULL),
(83, 15, '2025-08-18 15:53:05', 'inprogress', NULL, 18500, 'irene', 'irene@gmail.com', '0678844434', 'mzumbe', '2025-08-19 09:07:42', 'airtelmoney', '', 2500.00, 0.00, 0.00, 0.00, NULL),
(84, 15, '2025-08-19 12:10:20', 'pending', NULL, 7500, 'irene', 'irene@gmail.com', '0678844434', 'mzumbe', '2025-08-19 12:10:20', 'mpesa', '', 2500.00, 0.00, 0.00, 0.00, NULL);

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

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `food_id`, `quantity`, `price`) VALUES
(39, 31, 11, 1, 0.00),
(40, 32, 12, 1, 0.00),
(41, 32, 11, 1, 0.00),
(42, 33, 12, 1, 0.00),
(43, 33, 11, 1, 0.00),
(44, 33, 10, 1, 0.00),
(47, 33, 14, 1, 0.00),
(48, 34, 13, 1, 0.00),
(49, 35, 12, 1, 0.00),
(50, 36, 13, 1, 0.00),
(51, 37, 11, 1, 0.00),
(52, 38, 13, 1, 0.00),
(53, 38, 11, 1, 0.00),
(54, 38, 10, 1, 0.00),
(57, 39, 11, 1, 0.00),
(58, 40, 13, 1, 0.00),
(59, 41, 12, 1, 0.00),
(60, 42, 11, 3, 0.00),
(63, 42, 10, 1, 0.00),
(64, 42, 12, 1, 0.00),
(65, 42, 13, 1, 0.00),
(66, 42, 14, 1, 0.00),
(67, 42, 21, 1, 0.00),
(68, 42, 20, 1, 0.00),
(69, 43, 12, 1, 0.00),
(70, 43, 11, 1, 0.00),
(71, 43, 10, 1, 0.00),
(74, 43, 14, 1, 0.00),
(75, 43, 13, 1, 0.00),
(76, 45, 13, 1, 5000.00),
(77, 45, 14, 1, 5000.00),
(78, 45, 24, 1, 2000.00),
(79, 45, 23, 1, 2000.00),
(80, 45, 22, 1, 2000.00),
(81, 46, 12, 1, 5000.00),
(82, 47, 12, 1, 5000.00),
(83, 48, 12, 1, 5000.00),
(84, 49, 11, 1, 2000.00),
(85, 50, 11, 1, 2000.00),
(87, 71, 11, 1, 2000.00),
(88, 71, 12, 1, 5000.00),
(89, 72, 12, 1, 5000.00),
(90, 73, 40, 1, 10000.00),
(91, 73, 14, 1, 5000.00),
(93, 74, 28, 1, 12000.00),
(94, 74, 10, 1, 7000.00),
(95, 75, 13, 1, 5000.00),
(96, 76, 11, 1, 2000.00),
(98, 77, 10, 1, 7000.00),
(100, 77, 11, 1, 2000.00),
(101, 77, 12, 1, 5000.00),
(102, 77, 13, 1, 5000.00),
(103, 78, 12, 1, 5000.00),
(104, 79, 11, 1, 2000.00),
(105, 80, 11, 1, 2000.00),
(106, 80, 12, 1, 5000.00),
(107, 81, 12, 1, 5000.00),
(108, 82, 10, 1, 7000.00),
(109, 83, 11, 1, 2000.00),
(110, 83, 10, 2, 7000.00),
(111, 84, 12, 1, 5000.00);

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
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone`, `password`, `is_active`) VALUES
(1, 'anna', 'anna@gmail.com', NULL, '$2y$10$eyaHxa55Gt37wjWI.3JqK.Odx9UP3p3810HMHFA2xyknqh.Fmoe.m', 1),
(2, 'issa', 'issa@gmail.com', NULL, '$2y$10$ItoKGtYN/N5I.txSo2F.Z.5xx/9tqFQthzu8UKfnlxmhS17yai/uW', 1),
(3, 'blezi', 'blezite@gmail.com', NULL, '$2y$10$yISTj6jG/kZzkp7E.3toXOII0o68lMQfx7bBf/FMmJJhPOaEst2va', 1),
(7, 'ally', 'ally@gmail.com', NULL, '$2y$10$GGCIyEmN.O9h.BnZD515O.zxTbjF8xyr8Gob7A5OKVbIufA/Lljay', 1),
(11, 'ratifa amrani', 'ratifaamrani123@gmail.com', NULL, '$2y$10$sru7QIFNCXRjImIcq4/n9OmWq4IopC7x5vprkElglXoA5KcMQQdqa', 1),
(12, 'james', 'james@gmail.com', NULL, '$2y$10$vp53WTZGDelw4SGsRawUD.P2CF7nqH9wyG1H9Pvm4dp6lvfK7ke3O', 1),
(13, 'leila', 'leila@gmai.com', NULL, '$2y$10$fLI16/iWqDuZaOXOmnSCIOGd4Ave606gZrh0TR3CrM88ipGpvnr8e', 1),
(14, 'utha', 'utha@gmail.com', NULL, '$2y$10$FjegGFWzqQFzXk4bknmESOT8sMiG4Gv0Emsfm7LlqMSzOgMF2A3sq', 1),
(15, 'irene', 'irene@gmail.com', NULL, '$2y$10$chUtuOHqThXh9YfjYPpid.DRU4XRa4r25kTHEm/mcJ7H3vhFnKWuG', 1),
(16, 'isha', 'isha@gmail.com', NULL, '$2y$10$EPVf0NoZy7iE3Hd6Ipxs3.J179m2DvvhcIhwJi/hQHPzZP.WoPoHS', 1),
(17, 'zuta', 'zuta@gmail.com', NULL, '$2y$10$qDxaeP9rHtkxl6zaUuCc2eiPzcwxDA7XKpgFvTfAVZpcHh1gKq8Ze', 1),
(18, 'kihatara', 'kihatara@gmail.com', '0678844434', '$2y$10$V7HZA9KRLConB40krnfIiOAFzzQPVWf0Fx0pPihoz4KXgrAXEv07i', 1),
(19, 'mwaj', 'mwaj@gmail.com', '0678844434', '$2y$10$e3ObABlE/Gwk2k6Qb3e7e.iJnN2N5xA/BUASoKhlnXfRUmys.umWe', 1),
(20, 'grace', 'grace@gmail.com', '0678844444', '$2y$10$0ICc5dRouotWXUG2RV/9be.MmDjpFNlmK8PRsTCAOwr6qEUxNBdje', 1);

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
--
-- Database: `patient_registration_system`
--
CREATE DATABASE IF NOT EXISTS `patient_registration_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `patient_registration_system`;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Receptionist') DEFAULT 'Receptionist',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'System Administrator', 'admin', 'admin123', 'Admin', '2026-06-04 11:34:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-06-07 12:38:39', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `student_attendance_system`
--
CREATE DATABASE IF NOT EXISTS `student_attendance_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `student_attendance_system`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', '2026-06-05 11:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Absent') NOT NULL,
  `marked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `class_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecturers`
--

CREATE TABLE `lecturers` (
  `lecturer_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `idx_class` (`class_id`),
  ADD KEY `idx_date` (`attendance_date`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `course_code` (`course_code`);

--
-- Indexes for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`lecturer_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `reg_no` (`reg_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `lecturer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`lecturer_id`) ON DELETE CASCADE;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
