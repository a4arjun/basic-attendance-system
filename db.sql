-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2021 at 04:53 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kav`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_log`
--

CREATE TABLE `attendance_log` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `punch_time` datetime(6) NOT NULL,
  `punch_type` varchar(11) NOT NULL,
  `gate_number` varchar(255) NOT NULL,
  `punch_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance_log`
--

INSERT INTO `attendance_log` (`id`, `employee_id`, `punch_time`, `punch_type`, `gate_number`, `punch_date`) VALUES
(1, 'EMP0001', '2021-05-11 06:58:50.000000', 'IN', '1', '2021-05-11'),
(2, 'EMP0001', '2021-05-11 20:31:55.000000', 'OUT', '2', '2021-05-11'),
(143, 'EMP0002', '2021-05-11 18:16:47.000000', 'IN', '2', '2021-05-11'),
(145, 'EMP0002', '2021-05-11 20:32:33.000000', 'OUT', '2', '2021-05-11'),
(146, 'EMP0002', '2021-05-12 06:06:23.000000', 'IN', '2', '2021-05-12'),
(148, 'EMP0001', '2021-05-12 07:05:23.000000', 'IN', '1', '2021-05-12'),
(149, 'EMP0001', '2021-05-12 20:02:48.000000', 'OUT', '1', '2021-05-12'),
(150, 'EMP0003', '2021-05-12 07:24:51.000000', 'IN', '1', '2021-05-12'),
(151, 'EMP0003', '2021-05-12 16:09:48.000000', 'OUT', '1', '2021-05-12'),
(152, 'EMP0002', '2021-05-12 19:52:11.000000', 'OUT', '1', '2021-05-12');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_role` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `employee_name`, `employee_role`, `password`, `address`) VALUES
(1, 'EMP0001', 'Harry', 'manager', '5f4dcc3b5aa765d61d8327deb882cf99', 'Address Line 1'),
(2, 'EMP0002', 'Hermione', 'employee', '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee Address'),
(3, 'EMP0003', 'Ronald', 'employee', '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee Address'),
(4, 'EMP0004', 'Nevile', 'employee', '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee Address'),
(5, 'EMP0005', 'Malfoy', 'employee', '5f4dcc3b5aa765d61d8327deb882cf99', 'Employee Address');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_log`
--
ALTER TABLE `attendance_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_log`
--
ALTER TABLE `attendance_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
