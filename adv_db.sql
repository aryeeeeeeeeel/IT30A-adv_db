-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 11:05 AM
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
-- Database: `adv_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_department` varchar(100) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `course_department`, `course_code`, `created_at`, `updated_at`) VALUES
(1, 'BSIT', 'CCS', '1001', '2024-10-24 13:05:23', '2024-10-25 05:31:59'),
(2, 'BSBA', 'BA', '1002', '2024-10-25 04:25:37', '2024-10-25 05:32:03'),
(3, 'BSEd', 'TEP', '1003', '2024-10-25 04:26:09', '2024-10-25 05:32:07');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`, `created_at`, `updated_at`) VALUES
(1, 'A', '2024-10-27 05:33:02', '2024-10-27 05:33:02'),
(2, 'B', '2024-10-27 05:33:11', '2024-10-27 05:33:11'),
(3, 'C', '2024-10-27 05:33:19', '2024-10-27 05:33:19');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `year` enum('1st','2nd','3rd','4th') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(255) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `gender`, `section_id`, `course_id`, `year`, `created_at`, `updated_at`, `email`, `instructor_id`) VALUES
(1, 'Ariel', 'Sumantin', 'Male', 1, 1, '4th', '2024-10-24 15:48:53', '2024-10-26 08:01:08', '20211276@nbsc.edu.ph', 2),
(2, 'Reynan Jhay', 'Busano', 'Male', 2, 1, '4th', '2024-10-24 15:56:01', '2024-10-26 02:19:45', '20201086@nbsc.edu.ph', 3),
(3, 'Christian', 'Margallo', 'Male', 3, 1, '4th', '2024-10-24 16:02:23', '2024-10-26 02:19:47', '20201078@nbsc.edu.ph', 4),
(4, 'Roland', 'Duites', 'Male', 1, 2, '4th', '2024-10-26 07:54:08', '2024-10-27 05:48:17', '202041001@nbsc.edu.ph', 5),
(5, 'Tristan', 'Miculob', 'Male', 2, 2, '4th', '2024-10-26 08:01:43', '2024-10-27 05:48:21', '20241002@nbsc.edu.ph', 6),
(6, 'Kylle Mark', 'Albaño', 'Male', 3, 2, '4th', '2024-10-26 08:09:09', '2024-10-27 05:48:35', '20241003@nbsc.edu.ph', 7),
(7, 'Kristine Joy', 'Onahon', 'Female', 1, 3, '4th', '2024-10-26 13:22:41', '2024-10-27 05:50:15', '20241004@nbsc.edu.ph', 8),
(8, 'Jester', 'Castaña', 'Male', 2, 3, '4th', '2024-11-03 07:32:07', '2024-11-03 07:56:57', '20241005@nbsc.edu.ph', 9),
(9, 'Elena', 'Realisan', 'Female', 3, 3, '4th', '2024-11-03 07:58:42', '2024-11-03 07:58:54', '20241006@nbsc.edu.ph', 10),
(20, 'ashds', 'sjdkaj', 'Male', 1, 1, '3rd', '2024-11-11 01:01:56', '2024-11-11 01:01:56', 'aushdui@nbsc', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `department` enum('CCS','BA','TEP') DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','instructor') NOT NULL DEFAULT 'instructor',
  `reset_token` varchar(100) DEFAULT NULL,
  `token_expiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `department`, `email`, `password`, `created_at`, `role`, `reset_token`, `token_expiration`) VALUES
(1, 'admin', NULL, 'admin@gmail.com', '$2y$10$sCos8jTuL5XHzHdut23vEulA.MTzSq/jhJ2ZMSmq/8nu7LCT3WLi.', '2024-10-24 13:45:34', 'admin', 'ed672d39436721e6f425d951b5361eda9a8d0db9c1827beaf3a91d6c26df10dbb396a5bda27c9d69ad3a9a7cbaa6f602ebea', '2024-11-03 11:08:11'),
(2, 'Marchilyn Abunda', 'CCS', '20240001@nbsc.edu.ph', '$2y$10$JUiI0lQXO8PHwoGAYMwRHuyFz/jPV/6PwQS2c25uynYxd017pFXqC', '2024-10-24 15:39:54', 'instructor', NULL, NULL),
(3, 'Roland Justine Partos', 'CCS', '20240002@nbsc.edu.ph', '$2y$10$fMczZ5HOI.YrVNbL4A5ydelAbjaqk8ae11Kutk4IcxmcTiU/5OU2C', '2024-10-24 15:51:59', 'instructor', NULL, NULL),
(4, 'Cliff Amadeus Evangelio', 'CCS', '20240003@nbsc.edu.ph', '$2y$10$yYkqKvMY5OgFzobG3JlMH.ioSba3JsjqCe4MqnqF/1L6jVzIM2rVy', '2024-10-24 15:58:25', 'instructor', NULL, NULL),
(5, 'Franco Ceasar Agbalog', 'BA', '20240004@nbsc.edu.ph', '$2y$10$6JvaaoHQoum0.5GRZpfU/.NmuJnJYNkKMEJ0827oMQpdpBd9Phy1W', '2024-10-26 07:43:27', 'instructor', NULL, NULL),
(6, 'John Micheal Ganzan', 'BA', '20240005@nbsc.edu.ph', '$2y$10$WTPGqEh.MuV/d.rzKc6Hg.otPrezu0bk6NDafdzDum5tOIngB/Wg6', '2024-10-26 07:58:49', 'instructor', NULL, NULL),
(7, 'Alven Gomez', 'BA', '20240006@nbsc.edu.ph', '$2y$10$u1yYf3G90DmIXLfXy73/E.7Z2MCX1OcFG92X.nMkybQ23z7TU5sG2', '2024-10-26 08:07:55', 'instructor', NULL, NULL),
(8, 'Rowena Bagongon', 'TEP', '20240007@nbsc.edu.ph', '$2y$10$q.H8boK6SHtTrjX1Nj.VSuTWpu.MN85XXe887fUqYqRn5lV/iCfzG', '2024-10-26 12:51:34', 'instructor', NULL, NULL),
(9, 'Johnyets Rolan', 'TEP', '20240008@nbsc.edu.ph', '$2y$10$FQZG9csdU0Vd0JZI8T/pLeUSNdZ2sSkCAT7a8cvirq.uFBTQk.TpW', '2024-10-26 13:08:11', 'instructor', NULL, NULL),
(10, 'Anthony Sanchez', 'TEP', '20240009@nbsc.edu.ph', '$2y$10$tvnHg6mtQfz1P59/5fOXp.J4gdE/5FoSk3iA.g0bHuRdNM3OXaLXW', '2024-11-03 07:46:16', 'instructor', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `fk_instructor` (`instructor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
