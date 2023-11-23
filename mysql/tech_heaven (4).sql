-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2023 at 01:14 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tech_heaven`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_username` varchar(255) DEFAULT NULL,
  `admin_email` varchar(255) DEFAULT NULL,
  `admin_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_username`, `admin_email`, `admin_password`) VALUES
(1, 'techadmin', 'techheaven@gmail.com', 'tech2380');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`) VALUES
(1, 'dyson'),
(2, 'SONY'),
(3, 'DJI'),
(4, 'Apple');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'hairdryer'),
(2, 'TELEVISION'),
(3, 'Vacum Cleaner'),
(4, 'Drone'),
(5, 'phone');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`order_item_id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(20, 6507, 1, 1, 2148.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `payment_status`, `status`) VALUES
(6507, 1, '2023-11-10', 2148.00, 'Pending', 'Order Received');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `image_url`, `brand_id`, `category_id`) VALUES
(1, 'Dyson Supersonic™ hair dryer (Prussian Blue/Rich Copper)', 'Four styling attachments. Including the new Flyaway smoother.\r\n\r\nIntelligent heat control.\r\n\r\nPowered by Dyson digital motor V9.\r\n\r\nComes with Dyson-designed prussian blue presentation case', 2148.00, 'product_pic/605J-Prussian-attachments-case-v2.png', 1, 1),
(2, 'Dyson V11 Absolute+ (Nickel/Blue)', 'Dyson’s most powerful, intelligent cordless vacuum¹\r\n\r\nIntelligently optimises power and run time. Deep cleans anywhere.\r\n\r\nUp to 60 minutes of powerful floor cleaning.4\r\n\r\nComplimentary Dyson V11 DokTM worth RM699', 2699.00, 'product_pic/V11_blue-attachments-dok-min.png\r\n', 1, 3),
(3, 'DJI AIR 2S FLY MORE COMBO - 5.4K PROFESSIONAL AERIAL DRONE', 'Featuring a 1-inch CMOS sensor, powerful autonomous functions, and a compact body weighing less than 600 g, DJI Air 2S is the ultimate drone for aerial photographers on the move. ', 6100.00, 'product_pic/6139be1e23d2880ecc4a6e3b_dji-air-2s-fly-more-combo-54k-professional-aerial-drone.png', 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `product_id`, `rating`, `comment`, `review_date`) VALUES
(10, 1, 1, 3, 'tthjfggjgjg', '2023-11-10 15:09:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_num` varchar(255) DEFAULT NULL,
  `registration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `profile_pic`, `address`, `phone_num`, `registration_date`) VALUES
(1, 'Taanes2380', 'tmselvadurai@gmail.com', '$2y$10$.O3SS8r5WRGC4oKHuroqsOlXBUCH6eQ5Bw/dp6lLExm4cvqA4RLdW', '', 'no 2057, Lorong mak yong 4, taman ria jaya, 08000, sungai petani', '0187704305', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `activity_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `searches` text DEFAULT NULL,
  `seen_products` text DEFAULT NULL,
  `activity_time` time DEFAULT NULL,
  `activity_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`activity_id`, `user_id`, `searches`, `seen_products`, `activity_time`, `activity_date`) VALUES
(28, 1, '', NULL, '18:25:33', '2023-11-16'),
(29, 1, '', NULL, '14:02:15', '2023-11-17'),
(30, 1, 'dyson', NULL, '14:02:19', '2023-11-17'),
(31, 1, NULL, 'Dyson Supersonic™ hair dryer (Prussian Blue/Rich Copper)', '14:02:24', '2023-11-17'),
(32, 1, '', NULL, '14:41:14', '2023-11-17'),
(33, 1, NULL, 'DJI AIR 2S FLY MORE COMBO - 5.4K PROFESSIONAL AERIAL DRONE', '14:41:17', '2023-11-17'),
(34, 1, '', NULL, '14:41:27', '2023-11-17'),
(35, 1, NULL, 'DJI AIR 2S FLY MORE COMBO - 5.4K PROFESSIONAL AERIAL DRONE', '14:41:29', '2023-11-17');

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` timestamp NULL DEFAULT NULL,
  `logout_time` timestamp NULL DEFAULT NULL,
  `duration` time DEFAULT NULL,
  `date` date DEFAULT NULL,
  `access_token` varchar(255) NOT NULL DEFAULT concat('token_',substr(md5(rand()),1,8))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`log_id`, `user_id`, `login_time`, `logout_time`, `duration`, `date`, `access_token`) VALUES
(3, 1, '2023-10-22 08:01:26', NULL, NULL, '2023-10-22', 'token_fd1c0f19'),
(4, 1, '0000-00-00 00:00:00', NULL, NULL, '2023-10-22', 'token_fd1c0f19'),
(5, 1, '0000-00-00 00:00:00', NULL, NULL, '2023-10-22', 'token_fd1c0f19'),
(6, 1, '2016-08-14 16:00:00', NULL, NULL, '2023-10-22', 'token_fd1c0f19'),
(7, 1, '0000-00-00 00:00:00', NULL, NULL, '2023-10-22', 'token_fd1c0f19'),
(8, 1, '0000-00-00 00:00:00', NULL, NULL, '2023-10-22', 'token_fd1c0f19'),
(9, 1, '2023-10-22 08:15:59', NULL, NULL, '2023-10-22', 'token_fd1c0f19'),
(10, 1, '2023-10-22 08:21:01', '0000-00-00 00:00:00', '00:01:41', '2023-10-22', 'token_fd1c0f19'),
(11, 1, '2023-10-22 08:23:54', '2023-10-22 08:24:02', '00:00:08', '2023-10-22', 'token_fd1c0f19'),
(18, 1, '2023-10-25 06:18:06', '2023-10-25 06:18:22', '00:00:16', '2023-10-25', 'token_fd1c0f19'),
(19, 1, '2023-10-26 08:02:25', '2023-10-26 08:17:31', '00:15:06', '2023-10-26', 'token_fd1c0f19'),
(21, 1, '2023-11-10 02:36:43', '2023-11-10 15:13:40', '12:36:57', '2023-11-10', 'token_fd1c0f19'),
(22, 1, '2023-11-10 15:14:25', '2023-11-10 15:23:29', '00:09:04', '2023-11-10', 'token_2ea30bb3'),
(23, 1, '2023-11-10 15:23:38', '2023-11-10 15:27:40', '00:04:02', '2023-11-10', 'token_fad7cf17'),
(24, 1, '2023-11-10 15:32:25', '2023-11-10 15:42:01', '00:09:36', '2023-11-10', 'token_5d96c534'),
(25, 1, '2023-11-10 15:44:04', '2023-11-10 15:49:13', '00:05:09', '2023-11-10', 'token_72c59155'),
(26, 1, '2023-11-10 15:49:37', '2023-11-10 15:50:40', '00:01:03', '2023-11-10', 'token_79d15c23'),
(27, 1, '2023-11-10 15:51:05', '2023-11-16 08:12:22', '16:21:17', '2023-11-10', 'token_b72b614e'),
(28, 1, '2023-11-16 08:12:55', '2023-11-16 10:21:44', '02:08:49', '2023-11-16', 'token_a5d0e922'),
(29, 1, '2023-11-16 10:25:29', '2023-11-16 10:26:39', '00:01:10', '2023-11-16', 'token_98c84d12'),
(30, 1, '2023-11-17 06:02:11', '2023-11-17 06:03:25', '00:01:14', '2023-11-17', 'token_9cceadd6'),
(31, 1, '2023-11-17 06:41:11', '2023-11-17 06:42:32', '00:01:21', '2023-11-17', 'token_7f82d2b3');

-- --------------------------------------------------------

--
-- Table structure for table `warrantyclaims`
--

CREATE TABLE `warrantyclaims` (
  `claim_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `claim_type` varchar(20) DEFAULT NULL,
  `claim_reason` text DEFAULT NULL,
  `claim_status` varchar(20) DEFAULT NULL,
  `claim_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `prove_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `date_added` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `orderitems_ibfk_1` (`order_id`),
  ADD KEY `orderitems_ibfk_2` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_ibfk_1` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `products_ibfk_1` (`brand_id`),
  ADD KEY `products_ibfk_2` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `reviews_ibfk_1` (`user_id`),
  ADD KEY `reviews_ibfk_2` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `user_activity_ibfk_1` (`user_id`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_log_user_fk` (`user_id`);

--
-- Indexes for table `warrantyclaims`
--
ALTER TABLE `warrantyclaims`
  ADD PRIMARY KEY (`claim_id`),
  ADD KEY `warrantyclaims_ibfk_1` (`user_id`),
  ADD KEY `warrantyclaims_ibfk_2` (`product_id`),
  ADD KEY `warrantyclaims_ibfk_3` (`order_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `wishlist_ibfk_1` (`user_id`),
  ADD KEY `wishlist_ibfk_2` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6508;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `warrantyclaims`
--
ALTER TABLE `warrantyclaims`
  MODIFY `claim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_activity_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_log`
--
ALTER TABLE `user_log`
  ADD CONSTRAINT `user_log_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `warrantyclaims`
--
ALTER TABLE `warrantyclaims`
  ADD CONSTRAINT `warrantyclaims_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warrantyclaims_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warrantyclaims_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
