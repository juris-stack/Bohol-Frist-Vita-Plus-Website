-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2019 at 02:38 PM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vitaplus-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `ID` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `status` enum('unread','read') NOT NULL DEFAULT 'unread',
  `rel_ID` bigint(20) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`ID`, `type`, `message`, `status`, `rel_ID`, `date_added`) VALUES
(182, 'order', 'Cancelled order', 'read', 116, '2019-11-06 12:17:03'),
(181, 'order', 'New order recieved', 'read', 116, '2019-11-06 12:16:13'),
(180, 'order', 'New order recieved', 'read', 106, '2019-10-30 09:35:08'),
(152, 'order', 'New order recieved', 'read', 12, '2019-10-11 12:48:45'),
(151, 'order', 'New order recieved', 'read', 10, '2019-10-10 09:24:16'),
(150, 'order', 'New order recieved', 'read', 9, '2019-10-09 11:10:05'),
(149, 'order', 'New order recieved', 'read', 8, '2019-10-09 08:54:33'),
(148, 'order', 'New order recieved', 'read', 7, '2019-10-08 18:59:22'),
(147, 'order', 'New order recieved', 'read', 6, '2019-10-07 21:42:50'),
(146, 'order', 'New order recieved', 'read', 5, '2019-10-07 20:36:35'),
(145, 'order', 'New order recieved', 'read', 4, '2019-10-07 14:49:24'),
(144, 'order', 'New order recieved', 'read', 3, '2019-10-07 13:19:40'),
(143, 'order', 'New order recieved', 'read', 2, '2019-10-07 11:16:12'),
(142, 'order', 'New order recieved', 'read', 1, '2019-10-07 11:12:22'),
(178, 'order', 'New order recieved', 'read', 73, '2019-10-23 21:27:19'),
(179, 'order', 'New order recieved', 'read', 78, '2019-10-28 19:24:23'),
(183, 'order', 'New order recieved', 'read', 119, '2019-12-01 17:21:10'),
(184, 'order', 'New order recieved', 'read', 120, '2019-12-01 17:49:54'),
(185, 'order', 'New order recieved', 'read', 121, '2019-12-02 19:00:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
