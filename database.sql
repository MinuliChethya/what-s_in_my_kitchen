-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 26, 2026 at 08:31 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kitchen_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'Test User', 'test@example.com', 'This is a test message for the contact form.', '2026-03-22 16:28:35');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

DROP TABLE IF EXISTS `recipes`;
CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `ingredients` text NOT NULL,
  `steps` text NOT NULL,
  `time` varchar(50) NOT NULL,
  `difficulty` enum('Easy','Medium','Hard') NOT NULL,
  `image_url` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `user_id`, `name`, `ingredients`, `steps`, `time`, `difficulty`, `image_url`, `created_at`) VALUES
(1, 1, 'Spanish Omelette', 'Onion, Potato', 'Slice potatoes and onions, Fry them in olive oil, Add beaten eggs, Cook until golden', '15 min', 'Easy', 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d', '2026-03-22 16:28:35'),
(2, 1, 'Garlic Tomato Pasta', 'Garlic, Tomato', 'Boil pasta, Saute garlic, Add tomato sauce, Mix pasta with sauce', '25 min', 'Medium', 'https://i.pinimg.com/736x/f6/8f/f4/f68ff44d5f58e8e8743375da6543fca9.jpg', '2026-03-22 16:28:35'),
(3, 1, 'Veggie Curry', 'Potato, Curry powder', 'Cut vegetables, Cook with curry powder, Add water and simmer, Serve hot', '20 min', 'Easy', 'https://i.pinimg.com/1200x/db/bd/b4/dbbdb4b8a786f423e75f15e023b63819.jpg', '2026-03-22 16:28:35'),
(4, 1, 'Shakshuka', 'Tomato, Onion', 'Cook onions, Add tomato sauce, Crack eggs into sauce, Cook until eggs set', '30 min', 'Medium', 'https://i.pinimg.com/1200x/94/a1/99/94a199d724da80151a71389355bb52c0.jpg', '2026-03-22 16:28:35'),
(5, 1, 'Creamy Mac & Veggie Pasta', 'Carrot, Cheddar', 'Cook pasta and drain, Mix in mashed veggies cheese and milk, Stir on low heat until creamy, Add more milk for smoother texture', '25 min', 'Easy', 'https://i.pinimg.com/1200x/5c/21/61/5c2161770d5032a8f65e6c3944ae636f.jpg', '2026-03-22 16:28:35'),
(6, 1, 'Creamy Oats with Banana', 'Banana, Oats, Milk', 'Cook oats with milk until soft, Stir in mashed banana and cinnamon', '10 min', 'Easy', 'https://i.pinimg.com/736x/43/c8/97/43c8973b1ff425d3b72c4e0491953435.jpg', '2026-03-22 16:28:35'),
(7, 1, 'Fish Curry', 'Fish, Curry powder, Onion', 'Marinate fish with spices, Cook onions until golden, Add curry powder and water, Add fish and simmer', '30 min', 'Medium', 'https://i.pinimg.com/736x/f6/e8/31/f6e8316a80c7e683ebf9aa2abba7f101.jpg', '2026-03-22 16:28:35'),
(8, 1, 'Cheesy Potato Bake', 'Potato, Cheddar, Milk', 'Slice potatoes thinly, Layer with cheese and milk, Bake until golden, Let rest before serving', '45 min', 'Medium', 'https://i.pinimg.com/1200x/6b/91/68/6b9168eb8cb2f8ba78acebbf9abc3c45.jpg', '2026-03-22 16:28:35');

-- --------------------------------------------------------

--
-- Table structure for table `saved_recipes`
--

DROP TABLE IF EXISTS `saved_recipes`;
CREATE TABLE IF NOT EXISTS `saved_recipes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `recipe_id` int NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_saved` (`user_id`,`recipe_id`),
  KEY `recipe_id` (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@kitchen.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-03-22 16:28:35'),
(2, 'user123', 'minulichethya@gmail.com', '$2y$10$Np4uhGT40KlsoGbE/TaeLuKFbiGAfPtHtPuDZRXvSJQtla8uxXu6q', '2026-03-22 17:08:35');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_recipes`
--
ALTER TABLE `saved_recipes`
  ADD CONSTRAINT `saved_recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_recipes_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
