-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2025 at 06:55 PM
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
-- Database: `socialmedia`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `email`, `password`) VALUES
('Ghrayeb', 'ghrayeb@gmail.com', '123'),
('Rami', 'rami.hobballah2003@gmail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

CREATE TABLE `download` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `post_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `following_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `follower_id`, `following_id`, `created_at`) VALUES
(5, 8, 5, '2025-01-01 16:23:12');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `post_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES
(3, 5, 9),
(4, 5, 10),
(6, 5, 11),
(7, 5, 35),
(8, 5, 38),
(10, 8, 42),
(11, 8, 41);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `audio_file` varchar(255) NOT NULL,
  `music_title` varchar(255) NOT NULL,
  `artist_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `image`, `created_at`, `audio_file`, `music_title`, `artist_name`) VALUES
(37, 5, 'uploads/images/677563b92763c-1.jpg', '2025-01-01 15:48:09', 'uploads/audio/677563b927287-1.mp3', '1', '1'),
(38, 5, 'uploads/images/677563c9c186b-2.jpg', '2025-01-01 15:48:25', 'uploads/audio/677563c9c1687-2.mp3', '2', '2'),
(39, 5, 'uploads/images/677567a1488c4-3.jpg', '2025-01-01 16:04:49', 'uploads/audio/677567a148441-59f68f3e96233e3c352659053dd48039790cd9651.mp3', '3', '3'),
(40, 5, 'uploads/images/677567aed2439-4.jpg', '2025-01-01 16:05:02', 'uploads/audio/677567aed1e49-34231118fa980913824d4ccf78be931e999f69f11.mp3', '4', '4'),
(41, 5, 'uploads/images/67756801b5714-5.jpg', '2025-01-01 16:06:25', 'uploads/audio/67756801b5118-34231118fa980913824d4ccf78be931e999f69f13.mp3', '5', '5'),
(42, 5, 'uploads/images/67756811b9564-6.jpg', '2025-01-01 16:06:41', 'uploads/audio/67756811b9157-34231118fa980913824d4ccf78be931e999f69f15.mp3', '6', '6'),
(43, 8, 'uploads/images/67756be33bffc-7.jpg', '2025-01-01 16:22:59', 'uploads/audio/67756be33b323-59f68f3e96233e3c352659053dd48039790cd9651.mp3', 'as', 'as');

-- --------------------------------------------------------

--
-- Table structure for table `saved`
--

CREATE TABLE `saved` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `post_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved`
--

INSERT INTO `saved` (`id`, `user_id`, `post_id`) VALUES
(11, 5, 38),
(12, 5, 37),
(15, 5, 40),
(16, 5, 39),
(19, 8, 42);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `bod` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `terms_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `country`, `bod`, `email`, `phone`, `gender`, `address`, `username`, `password`, `terms_accepted`, `registration_date`) VALUES
(1, 'eden', 'hazard', 'lebanon', '2000-02-01', 'Darinhobballah20@gmail.com', '7010010', 'Male', 'chaqra, lebanon', 'edenhazard10', 'd4001af693c99d6811b53412855d2e49', 0, '2024-12-23 10:26:35'),
(2, 'darin', 'hobballah', 'lebanon', '2000-01-02', 'Watchasvip23@ostories.me', '1110202304', 'Female', 'Kawthariyet al siyad, nabatiye', 'rami', 'd4001af693c99d6811b53412855d2e49', 0, '2024-12-23 11:16:52'),
(3, 'Rami', 'Hobballah', 'Lebanon', '2003-11-20', 'rami.hobballah2003@gmail.com', '70157588', 'Male', 'chaqra', 'ramiii', 'd4001af693c99d6811b53412855d2e49', 0, '2024-12-23 11:18:38'),
(4, 'Rami', 'Hobballah', 'Lebanon', '2003-11-20', 'rami.hobballah@gmail.com', '70157588', 'Male', 'chaqra', 'Edenhobballah10', '23c035949ac036cf3ce10a4ec6eeafdb', 0, '2024-12-23 11:21:56'),
(5, 'Moustafa', 'ghrayeb', 'Lebanon', '1997-01-01', 'ghrayebmoustafa@gmail.com', '78964185', 'Male', 'Lebanon', 'mostafa', '7c134817a6aa24e650ffec956e703677', 0, '2024-12-25 16:48:05'),
(6, 'Moustafa', 'ghrayeb', 'Lebanon', '1995-01-01', 'moustafaghrayeb313@gmail.com', '78964185', 'Male', 'Lebanon', 'm10', '7c134817a6aa24e650ffec956e703677', 0, '2024-12-30 18:10:48'),
(7, 'nasser', 'mortada', 'Lebanon', '1995-01-01', 'nasser@gmail.com', '03123456', 'Male', 'Lebanon', 'nasser', '15a1f5dc11bf87d1baaf2111fafdb44a', 0, '2025-01-01 16:19:29'),
(8, 'mhmd', 'mhmd', 'lebaon', '1998-12-12', 'mhmd@gmail.com', '76123456', 'Male', 'lebanon', 'mhmd', '793377e0a1784d0b639023463dedb467', 0, '2025-01-01 16:22:34');

-- --------------------------------------------------------

--
-- Table structure for table `user_media`
--

CREATE TABLE `user_media` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `media_type` varchar(50) DEFAULT NULL,
  `media_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_media`
--

INSERT INTO `user_media` (`id`, `user_id`, `media_type`, `media_url`, `created_at`) VALUES
(1, 5, 'profile_picture', 'uploads/8.jpg', '2025-01-01 14:48:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `download`
--
ALTER TABLE `download`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `follower_id` (`follower_id`,`following_id`),
  ADD KEY `following_id` (`following_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `saved`
--
ALTER TABLE `saved`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_media`
--
ALTER TABLE `user_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `download`
--
ALTER TABLE `download`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `saved`
--
ALTER TABLE `saved`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_media`
--
ALTER TABLE `user_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved`
--
ALTER TABLE `saved`
  ADD CONSTRAINT `saved_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `saved_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `user_media`
--
ALTER TABLE `user_media`
  ADD CONSTRAINT `user_media_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
