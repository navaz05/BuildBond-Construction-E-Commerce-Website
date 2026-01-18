-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 05:16 AM
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
-- Database: `auth`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(16, 2, 27, 1, '2025-04-27 16:44:09');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `discount` decimal(5,2) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `description`, `discount`, `expiry_date`, `created_at`) VALUES
(1, 'AAA', 'KSAKDKB', 50.00, '2025-05-12', '2025-05-06 08:36:05'),
(2, 'HARSH', 'Great', 70.00, '2025-05-31', '2025-04-27 13:24:38');

-- --------------------------------------------------------

--
-- Table structure for table `earnings`
--

CREATE TABLE `earnings` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `feedback` text NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `discount` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT 'Cash on Delivery',
  `status` enum('Pending','Processing','Shipped','Delivered','Cancelled','Returned') DEFAULT 'Pending',
  `razorpay_order_id` varchar(255) DEFAULT NULL,
  `razorpay_payment_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `payment_method`, `status`, `razorpay_order_id`, `razorpay_payment_id`, `created_at`, `order_date`) VALUES
(1, 1, 420.00, 'Razorpay', 'Returned', 'order_QO47ONwYtN882G', 'pay_QO47VZcXqLjnVN', '2025-04-27 11:28:54', '2025-04-27 16:58:54'),
(2, 2, 780.00, 'Razorpay', 'Returned', 'order_QO4EO10fWYDf1d', 'pay_QO4EYhHNdZFwiJ', '2025-04-27 11:35:36', '2025-04-27 17:05:36'),
(3, 2, 450.00, 'Razorpay', 'Delivered', 'order_QO4GATNyU1l5eE', 'pay_QO4GIXuGkJPrl9', '2025-04-27 11:37:16', '2025-04-27 17:07:16'),
(4, 2, 1060.00, 'Razorpay', 'Returned', 'order_QO4I4rHqukbCKX', 'pay_QO4IZnpClOMO50', '2025-04-27 11:39:25', '2025-04-27 17:09:25'),
(5, 2, 680.00, 'Razorpay', 'Processing', 'order_QO4McTxUdcrNdH', 'pay_QO4Mois44hnWQF', '2025-04-27 11:43:26', '2025-04-27 17:13:26'),
(6, 1, 450.00, 'Razorpay', 'Returned', 'order_QO4Pfqix7IUOLh', 'pay_QO4PorDhT8hJyP', '2025-04-27 11:46:17', '2025-04-27 17:16:17'),
(7, 2, 450.00, 'Razorpay', 'Returned', 'order_QO4VmziWOuCisL', 'pay_QO4VwXXKVpJjDO', '2025-04-27 11:52:03', '2025-04-27 17:22:03'),
(8, 2, 1100.00, 'Razorpay', 'Delivered', 'order_QO9LuAxYhZ1nUM', 'pay_QO9M8cm8pJAymW', '2025-04-27 16:36:22', '2025-04-27 22:06:22'),
(9, 3, 680.00, 'Razorpay', 'Returned', 'order_QO9oudVqU9bPbO', 'pay_QO9p7V8xS8BKXS', '2025-04-27 17:03:52', '2025-04-27 22:33:52'),
(10, 3, 3300.00, 'Razorpay', 'Processing', 'order_QPHo0MskTZvKzD', 'pay_QPHoDwJEvjxKpd', '2025-04-30 13:31:26', '2025-04-30 19:01:26'),
(11, 3, 945.00, 'COD', 'Pending', NULL, NULL, '2025-04-30 13:38:53', '2025-04-30 19:08:53');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(7, 31, 23, 1, 300.00),
(8, 32, 23, 1, 300.00),
(11, 35, 22, 1, 420.00),
(12, 36, 23, 1, 300.00),
(13, 37, 22, 2, 420.00),
(14, 37, 24, 1, 360.00),
(15, 38, 22, 1, 420.00),
(16, 39, 23, 1, 300.00),
(17, 1, 22, 1, 420.00),
(18, 2, 22, 1, 420.00),
(19, 2, 24, 1, 360.00),
(20, 3, 29, 1, 450.00),
(21, 4, 23, 1, 300.00),
(22, 4, 24, 1, 360.00),
(23, 4, 25, 1, 400.00),
(24, 5, 27, 1, 680.00),
(25, 6, 29, 1, 450.00),
(26, 7, 29, 1, 450.00),
(27, 8, 27, 1, 680.00),
(28, 8, 22, 1, 420.00),
(29, 9, 27, 1, 680.00),
(30, 10, 22, 3, 420.00),
(31, 10, 27, 3, 680.00),
(32, 11, 29, 7, 450.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `average_rating` float DEFAULT 0,
  `total_reviews` int(11) DEFAULT 0,
  `selling_price` decimal(10,2) NOT NULL,
  `mrp` decimal(10,2) NOT NULL,
  `discount_percent` varchar(10) GENERATED ALWAYS AS (concat(round((`mrp` - `selling_price`) / `mrp` * 100,2),'% OFF')) STORED,
  `stock` int(11) NOT NULL DEFAULT 0,
  `sold` int(11) NOT NULL DEFAULT 0,
  `category` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `average_rating`, `total_reviews`, `selling_price`, `mrp`, `stock`, `sold`, `category`, `image`, `created_at`) VALUES
(22, 'WHITE CEMENT BASED LEVEL PUTTY(20KG)', 'Exterior & Interior Use\r\nNET WT: 20KG', 0, 0, 420.00, 450.00, 49, 0, 'Product', '1743876743_1.jpg', '2025-04-05 12:21:32'),
(23, 'BROWN STRUCTRED(10)', 'simple and clean look', 0, 0, 300.00, 350.00, 499, 0, 'design', '1743876619_1.jpg', '2025-04-05 12:29:14'),
(24, 'brown random(10)', '10 patter price', 0, 0, 360.00, 390.00, 500, 0, 'design', '1743876627_2.jpg', '2025-04-05 12:39:39'),
(25, 'white 10', 'asbdakjb', 0, 0, 400.00, 500.00, 500, 0, 'design', '1743876655_3.jpg', '2025-04-05 12:40:55'),
(26, 'white with patterns (10)', 'random pattern', 0, 0, 650.00, 900.00, 500, 0, 'design', '1743876693_4.jpg', '2025-04-05 12:41:33'),
(27, 'TEXTURE', 'NET WT. 25KGS', 0, 0, 680.00, 700.00, 300, 0, 'Product', '1743876805_2.jpg', '2025-04-05 12:43:25'),
(28, 'Panno', 'build bond', 0, 0, 450.00, 500.00, 500, 0, 'Product', '1743876844_3.jpg', '2025-04-05 12:44:04'),
(29, 'acrylic bonding agent ', 'basdkabs', 0, 0, 450.00, 500.00, 200, 0, 'Product', '1743876890_4.jpg', '2025-04-05 12:44:50');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_name`, `rating`, `comment`, `created_at`) VALUES
(1, 22, 'yashhhh', 5, 'good', '2025-04-27 11:29:23'),
(2, 22, 'Priya Nair', 4, 'Works well for interior use.', '2025-04-27 07:30:00'),
(3, 23, 'Rohit Mehra', 5, 'Simple and clean look as promised!', '2025-04-27 08:30:00'),
(4, 23, 'Sneha Kapoor', 3, 'Color was slightly different than expected.', '2025-04-27 09:30:00'),
(5, 24, 'Vikas Yadav', 4, 'Good pattern and finish.', '2025-04-27 10:30:00'),
(6, 25, 'Neha Sinha', 2, 'Didn’t match my decor, average quality.', '2025-04-27 11:30:00'),
(7, 26, 'Rahul Jain', 5, 'Beautiful patterns, loved it!', '2025-04-27 12:30:00'),
(8, 26, 'Kriti Verma', 4, 'Looks great, but a little expensive.', '2025-04-27 13:30:00'),
(9, 27, 'Manoj Kumar', 5, 'High-quality texture, easy to apply.', '2025-04-27 14:30:00'),
(10, 28, 'Pooja Reddy', 3, 'Decent bond, expected more strength.', '2025-04-27 15:30:00'),
(11, 29, 'Sandeep Singh', 5, 'Perfect bonding agent for my work!', '2025-04-27 16:30:00'),
(13, 22, 'Amit Sharma', 5, 'Very good quality putty, smooth finish!', '2025-04-27 06:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `reset_token` varchar(6) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `otp_code`, `otp_expiry`, `reset_token`, `token_expiry`) VALUES
(1, 'Harsh', 'harshsolanki8909@gmail.com', '$2y$10$SjHKXEth6lZdfbNDTqBs8ec.1HDKO4JHQh5/R7EvRtf7cvS/yJ3ay', 'admin', '2025-04-27 11:28:13', NULL, NULL, NULL, NULL),
(2, 'yash vyas', 'ybvyas786@gmail.com', '$2y$10$hT2CCZhfbkJ2LleRKLVggOLmlqwo9kcMRZZvFileH4Wza0q19hea2', 'user', '2025-04-27 11:34:29', NULL, NULL, NULL, NULL),
(3, 'harsh solanki', 'harsh992599@gmail.com', '$2y$10$St.VxMyX0cNFwJ7z4pS8Tu5FX3NWvYFzaHGXyWyOm/0R8qPwPSBv2', 'user', '2025-04-27 17:02:18', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `full_name`, `phone`, `address`, `city`, `state`, `pincode`, `created_at`, `updated_at`) VALUES
(1, 2, 'harsh solanki', '9925992669', 'BUDDHAM , SHIVKRUPA NAGAR 1\r\nVISHALMALL SRTEET , NEAR DHARTIHONDA SHOWROOM', 'Jetpur', 'Gujarat', '360370', '2025-04-27 16:32:19', '2025-04-27 16:32:19'),
(2, 3, 'harsh solanki', '9925992669', 'BUDDHAM , SHIVKRUPA NAGAR 1\r\nVISHALMALL SRTEET , NEAR DHARTIHONDA SHOWROOM', 'Jetpur', 'Gujarat', '360370', '2025-04-27 17:02:58', '2025-04-27 17:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `visit_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `ip_address`, `user_id`, `visit_time`) VALUES
(1, '::1', NULL, '2025-04-27 16:00:37'),
(2, '::1', NULL, '2025-04-27 16:00:42'),
(3, '::1', NULL, '2025-04-27 16:00:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `earnings`
--
ALTER TABLE `earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `earnings`
--
ALTER TABLE `earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `earnings`
--
ALTER TABLE `earnings`
  ADD CONSTRAINT `earnings_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `fk_user_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `visits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
