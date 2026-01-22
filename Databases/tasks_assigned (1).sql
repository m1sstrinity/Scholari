-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 10:25 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scholari_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks_assigned`
--

CREATE TABLE `tasks_assigned` (
  `id` int(11) NOT NULL,
  `assigned_work` varchar(255) NOT NULL,
  `due_date` date NOT NULL,
  `tasked` varchar(255) NOT NULL,
  `turned_in` int(11) DEFAULT 0,
  `status` varchar(20) DEFAULT NULL,
  `department` varchar(10) DEFAULT NULL,
  `project_task` varchar(10) DEFAULT NULL,
  `assignee_section` varchar(10) DEFAULT NULL,
  `educator` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tasks_assigned`
--

INSERT INTO `tasks_assigned` (`id`, `assigned_work`, `due_date`, `tasked`, `turned_in`, `status`, `department`, `project_task`, `assignee_section`, `educator`) VALUES
(1, 'Science Project', '2025-06-01', '30', 12, 'Ongoing', 'CCS', 'Project', '3D', 'Juan Dela Cruz'),
(2, 'English Essay', '2025-06-05', '25', 18, 'Accomplished', 'COED', 'Task', '3A', 'Juan Dela Cruz'),
(3, 'Math Quiz', '2025-06-03', '28', 20, 'Ongoing', 'CON', 'Task', '3C', 'Juan Dela Cruz'),
(4, 'HCI Prototype', '0000-00-00', '', 0, 'Ongoing', 'CCS', 'Project', '3B', 'Lorence Gerona');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks_assigned`
--
ALTER TABLE `tasks_assigned`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks_assigned`
--
ALTER TABLE `tasks_assigned`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
