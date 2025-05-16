-- 88 HOTEL Database Schema
-- This file contains the database structure for the 88 HOTEL Online Reservation System

-- Create database
CREATE DATABASE IF NOT EXISTS `88hotel`;
USE `88hotel`;

-- Users table
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Room types table
CREATE TABLE `room_types` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Rooms table
CREATE TABLE `rooms` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `description` TEXT NOT NULL,
  `max_occupancy` INT NOT NULL,
  `price_per_night` DECIMAL(10,2) NOT NULL,
  `size` INT NOT NULL COMMENT 'Size in square meters',
  `view` VARCHAR(50) DEFAULT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `status` ENUM('Available', 'Unavailable', 'Maintenance') NOT NULL DEFAULT 'Available',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Room images table (for multiple room images)
CREATE TABLE `room_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_id` INT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Amenities table
CREATE TABLE `amenities` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  `icon` VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Room amenities mapping table
CREATE TABLE `room_amenities` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_id` INT NOT NULL,
  `amenity_id` INT NOT NULL,
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`amenity_id`) REFERENCES `amenities`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `room_amenity_unique` (`room_id`, `amenity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Room bookings table
CREATE TABLE `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `reference_number` VARCHAR(20) NOT NULL UNIQUE,
  `user_id` INT NOT NULL,
  `room_id` INT NOT NULL,
  `check_in_date` DATE NOT NULL,
  `check_out_date` DATE NOT NULL,
  `guests` INT NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `special_requests` TEXT,
  `status` ENUM('Pending', 'Confirmed', 'Cancelled', 'Completed') NOT NULL DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Food categories table
CREATE TABLE `food_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Food items table
CREATE TABLE `food_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `is_available` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `food_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Food orders table
CREATE TABLE `food_orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `booking_id` INT,
  `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('Pending', 'Processing', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
  `total_price` DECIMAL(10,2) NOT NULL,
  `notes` TEXT,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Food order items table
CREATE TABLE `food_order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `food_item_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `food_orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`food_item_id`) REFERENCES `food_items`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reviews table
CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `booking_id` INT NOT NULL,
  `rating` INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  `comment` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `user_booking_unique` (`user_id`, `booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data for admin user
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `is_admin`) VALUES
('Admin User', 'admin@88hotel.com', '$2y$10$kJLD0K4OYf/vZBIRd6ixIOGr7aP7G7.PBIF1MQBRHj2qIL5cZH/3W', '1234567890', 1);
-- Password: admin123

-- Insert sample room types
INSERT INTO `room_types` (`name`, `description`) VALUES
('Standard', 'Our standard rooms offer comfort and convenience'),
('Deluxe', 'Spacious rooms with premium amenities'),
('Suite', 'Luxurious suites with separate living area'),
('Executive', 'Premium rooms designed for business travelers'),
('Family', 'Specially designed rooms for families');

-- Insert sample amenities
INSERT INTO `amenities` (`name`, `icon`) VALUES
('Free WiFi', 'wifi'),
('Air Conditioning', 'ac'),
('Flat-screen TV', 'tv'),
('Mini Bar', 'mini-bar'),
('Room Service', 'room-service'),
('Coffee Machine', 'coffee'),
('Safe', 'safe'),
('Bathtub', 'bathtub'),
('Balcony', 'balcony'),
('Ocean View', 'view');

-- Insert sample rooms
INSERT INTO `rooms` (`name`, `type`, `description`, `max_occupancy`, `price_per_night`, `size`, `view`, `image_url`, `is_featured`, `status`) VALUES
('Deluxe King Room', 'Deluxe', 'Spacious room with a king-sized bed, modern amenities, and city views.', 2, 199.99, 35, 'City View', 'assets/images/rooms/deluxe-king.jpg', 1, 'Available'),
('Executive Suite', 'Suite', 'Luxurious suite featuring a separate living area, premium amenities, and panoramic ocean views.', 2, 299.99, 50, 'Ocean View', 'assets/images/rooms/executive-suite.jpg', 1, 'Available'),
('Family Room', 'Family', 'Specially designed room for families, featuring two queen beds and additional space for children.', 4, 249.99, 45, 'Garden View', 'assets/images/rooms/family-room.jpg', 1, 'Available'),
('Standard Double Room', 'Standard', 'Comfortable room with a double bed and essential amenities for a pleasant stay.', 2, 149.99, 30, 'City View', 'assets/images/rooms/standard-double.jpg', 0, 'Available'),
('Deluxe Twin Room', 'Deluxe', 'Modern room with two single beds, perfect for friends or colleagues traveling together.', 2, 189.99, 35, 'Garden View', 'assets/images/rooms/deluxe-twin.jpg', 0, 'Available'),
('Premier Ocean Suite', 'Suite', 'Our most luxurious accommodation featuring a private balcony with breathtaking ocean views.', 2, 399.99, 65, 'Ocean View', 'assets/images/rooms/premier-suite.jpg', 0, 'Available');

-- Insert sample room amenities
INSERT INTO `room_amenities` (`room_id`, `amenity_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5),
(2, 1), (2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 7), (2, 8), (2, 10),
(3, 1), (3, 2), (3, 3), (3, 5), (3, 7),
(4, 1), (4, 2), (4, 3), (4, 5),
(5, 1), (5, 2), (5, 3), (5, 4), (5, 5), (5, 7),
(6, 1), (6, 2), (6, 3), (6, 4), (6, 5), (6, 6), (6, 7), (6, 8), (6, 9), (6, 10);

-- Insert sample food categories
INSERT INTO `food_categories` (`name`, `description`) VALUES
('Breakfast', 'Start your day with our delicious breakfast options'),
('Lunch', 'Midday meals to fuel your adventures'),
('Dinner', 'Exquisite evening dining experience'),
('Desserts', 'Sweet treats to satisfy your cravings'),
('Beverages', 'Refreshing drinks and cocktails');

-- Insert sample food items
INSERT INTO `food_items` (`category_id`, `name`, `description`, `price`, `image_url`, `is_available`) VALUES
(1, 'Continental Breakfast', 'Selection of freshly baked pastries, fruits, yogurt, and coffee or tea.', 12.99, 'assets/images/food/continental-breakfast.jpg', 1),
(1, 'American Breakfast', 'Eggs cooked to your preference, bacon, toast, and hash browns.', 14.99, 'assets/images/food/american-breakfast.jpg', 1),
(1, 'Pancakes Stack', 'Fluffy pancakes served with maple syrup and fresh berries.', 10.99, 'assets/images/food/pancakes.jpg', 1),
(2, 'Club Sandwich', 'Triple-decker sandwich with chicken, bacon, lettuce, tomato, and mayo.', 16.99, 'assets/images/food/club-sandwich.jpg', 1),
(2, 'Caesar Salad', 'Crisp romaine lettuce, parmesan cheese, croutons, and Caesar dressing.', 14.99, 'assets/images/food/caesar-salad.jpg', 1),
(2, 'Beef Burger', 'Juicy beef patty with cheese, lettuce, tomato, and onion on a brioche bun.', 17.99, 'assets/images/food/beef-burger.jpg', 1),
(3, 'Grilled Salmon', 'Fresh salmon fillet grilled to perfection, served with seasonal vegetables.', 24.99, 'assets/images/food/grilled-salmon.jpg', 1),
(3, 'Filet Mignon', 'Prime beef tenderloin cooked to your preference, with mashed potatoes and asparagus.', 32.99, 'assets/images/food/filet-mignon.jpg', 1),
(3, 'Vegetable Pasta', 'Al dente pasta with fresh seasonal vegetables in a light cream sauce.', 18.99, 'assets/images/food/vegetable-pasta.jpg', 1),
(4, 'Chocolate Lava Cake', 'Warm chocolate cake with a molten center, served with vanilla ice cream.', 9.99, 'assets/images/food/chocolate-lava-cake.jpg', 1),
(4, 'New York Cheesecake', 'Creamy cheesecake with a graham cracker crust and berry compote.', 8.99, 'assets/images/food/cheesecake.jpg', 1),
(5, 'Fresh Fruit Smoothie', 'Blend of seasonal fruits with yogurt and honey.', 6.99, 'assets/images/food/fruit-smoothie.jpg', 1),
(5, 'Classic Mojito', 'Refreshing cocktail with rum, mint, lime, sugar, and soda water.', 12.99, 'assets/images/food/mojito.jpg', 1);