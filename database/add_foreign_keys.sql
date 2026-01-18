-- Add foreign key constraints to existing tables

-- First, ensure all tables have proper primary keys
ALTER TABLE `cart` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `coupons` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `earnings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `feedback` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `offers` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `orders` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `order_items` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `products` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `reviews` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `visits` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Add unique constraints
ALTER TABLE `users` ADD UNIQUE KEY `email` (`email`);
ALTER TABLE `coupons` ADD UNIQUE KEY `code` (`code`);
ALTER TABLE `cart` ADD UNIQUE KEY `unique_user_product` (`user_id`, `product_id`);

-- Add foreign key constraints to cart table
ALTER TABLE `cart`
ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

-- Add foreign key constraint to earnings table
ALTER TABLE `earnings`
ADD CONSTRAINT `earnings_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

-- Add foreign key constraint to feedback table
ALTER TABLE `feedback`
ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Add foreign key constraints to order_items table
ALTER TABLE `order_items`
ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

-- Add foreign key constraint to orders table
ALTER TABLE `orders`
ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Add foreign key constraint to reviews table
ALTER TABLE `reviews`
ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

-- Add foreign key constraint to visits table
ALTER TABLE `visits`
ADD CONSTRAINT `visits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Add 'Returned' status to orders table if it doesn't exist
ALTER TABLE `orders` MODIFY COLUMN `status` enum('Pending','Processing','Shipped','Delivered','Cancelled','Returned') DEFAULT 'Pending';

-- Add Razorpay payment fields to orders table if they don't exist
ALTER TABLE `orders` 
  ADD COLUMN IF NOT EXISTS `razorpay_order_id` varchar(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `razorpay_payment_id` varchar(255) DEFAULT NULL; 