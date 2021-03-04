-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2021 at 05:43 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mr_spock`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(99) NOT NULL,
  `email` varchar(99) NOT NULL,
  `fname` varchar(99) DEFAULT NULL,
  `lname` varchar(99) DEFAULT NULL,
  `pass` varchar(99) NOT NULL,
  `reset_pass_token` varchar(99) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `fname`, `lname`, `pass`, `reset_pass_token`) VALUES
(9, 'thematthaslem@gmail.com', 'Matt', 'Haslem', '$2y$10$3B3Gtv11tT2s5JrG01HdjOdQYUMVOfETfUdTqq6qxA6tT2GyYQgdm', 'd76521bbd88f1bcf52837d1cacff3233'),
(10, 'joey@gmail.com', NULL, NULL, '$2y$10$tqTbsGR2s1LBo5NlkykhfeKwLb.3KwTkX/E5R/mjlUDo8v53SD21C', NULL),
(11, 'matt@matt.matt', NULL, NULL, '$2y$10$5YKdHCo3uZY7giFHPfE1Ce0GVjMmG8cV4otC4v.qubHN4BZGgktHi', NULL),
(12, 'punk@yahoo.com', NULL, NULL, '$2y$10$NZKcT/cLRWrYKidu2CLXNuQ/gVRjRm80ktzh2YMaJzsrZ6QHJQpRm', NULL),
(13, 'kels@ks.com', NULL, NULL, '$2y$10$FxmAbHl2pJFdmOOYND5tmuD6By/TDEo1El4mzznKtbVU7HtMhI1R.', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(99) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
