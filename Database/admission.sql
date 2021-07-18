-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2021 at 10:05 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admission`
--

-- --------------------------------------------------------

--
-- Table structure for table `student_data`
--

CREATE TABLE `student_data` (
  `id` int(10) NOT NULL,
  `u_card` varchar(12) NOT NULL,
  `u_f_name` text NOT NULL,
  `u_l_name` text NOT NULL,
  `u_father` text NOT NULL,
  `u_aadhar` varchar(12) NOT NULL,
  `u_birthday` text NOT NULL,
  `u_gender` varchar(6) NOT NULL,
  `u_email` text NOT NULL,
  `u_phone` varchar(10) NOT NULL,
  `u_state` varchar(12) NOT NULL,
  `u_dist` text NOT NULL,
  `u_village` text NOT NULL,
  `u_police` text NOT NULL,
  `u_pincode` text NOT NULL,
  `file` longblob NOT NULL,
  `u_mother` varchar(30) NOT NULL,
  `u_family` text NOT NULL,
  `staff_id` varchar(12) NOT NULL,
  `image` varchar(150) NOT NULL,
  `uploaded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_data`
--

INSERT INTO `student_data` (`id`, `u_card`, `u_f_name`, `u_l_name`, `u_father`, `u_aadhar`, `u_birthday`, `u_gender`, `u_email`, `u_phone`, `u_state`, `u_dist`, `u_village`, `u_police`, `u_pincode`, `file`, `u_mother`, `u_family`, `staff_id`, `image`, `uploaded`) VALUES
(104, 'ABCD12345677', 'Kush ', 'Singh', '', 'xxxxxxxxxxxx', '2021-01-22', 'Male', 'emmyluckytech@gmail.com', '1234567890', 'Himachal Pra', 'Rohtas', 'Dehri', 'Dehri', '111111111', '', '', '', '111111111111', 'Webp.net-resizeimage.jpg', '2021-01-16 13:06:03'),
(105, '123123213131', 'asdljf', 'aslkdjfl', 'adskljf', '', '', 'Choose', 'emmyluckytech@gmail.com', '8873651128', 'Choose...', 'Dehri', '', '', '', '', 'asdklj;', '', '', 'Lavkush Kumar.jpg', '2021-07-17 17:32:40'),
(107, 'sdfgsdfg', '', '', '', '', '', 'Choose', '', 'sfdgsdfg', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:15:55'),
(108, 'fsdfgsdfg', 'sdfgsdfgsdfg', 'sdf', 'sdfgsgf', '', '', 'Choose', '', 'sdfgsdfg', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:16:58'),
(109, 'f546456', 'dsfgsadfg45', 'sdf45g45', 'sdf45g', '', '', 'Choose', '', '564654654', 'Choose...', '', '', '', '', '', 'sdfg', '', '', '', '2021-07-18 12:19:02'),
(110, 'lavkush k', 'lavkush', ' kumar', 'sinhgh', '', '', 'Choose', '', 'lavkush', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:20:38'),
(111, '564654646', 'lavkush singh', 'singh', '', '', '', 'Choose', '', '4564654gf', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:22:23'),
(112, 'lavkush k', 'lavkush', ' kumar', 'sinhgh', '', '', 'Choose', '', 'lavkush', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:23:46'),
(113, 'asdfasdf', 'adfasdfasdfasdf', 'asdfasdfasf', '', '', '', 'Choose', '', 'adsfasdfas', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:23:58'),
(114, 'asdfasdf', 'adfasdfasdfasdf', 'asdfasdfasf', '', '', '', 'Choose', '', 'adsfasdfas', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:25:14'),
(115, 'asdf', 'asdf', '', '', '', '', 'Choose', '', 'asdf', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:25:22'),
(116, 'asdf', 'asdf', '', '', '', '', 'Choose', '', 'asdf', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:28:01'),
(117, 'asdf', 'asdf', '', '', '', '', 'Choose', '', 'asdf', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:28:38'),
(118, 'asdf', 'asdf', '', '', '', '', 'Choose', '', 'asdf', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:29:05'),
(119, 'asdf', 'asdf', '', '', '', '', 'Choose', '', 'asdf', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:29:24'),
(120, 'asdf', 'asdf', '', '', '', '', 'Choose', '', 'asdf', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:29:36'),
(121, 'asdf', 'asdf', '', '', '', '', 'Choose', '', 'asdf', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:30:55'),
(122, '564654646', 'lavkush singh', 'singh', '', '', '', 'Choose', '', '4564654gf', 'Choose...', '', '', '', '', '', '', '', '', '', '2021-07-18 12:32:31'),
(123, 'STD523696355', 'Coding ', 'Cush', 'Demo Data ', 'xxxxxxxxxxxx', '2021-12-12', 'Male', 'info@lexacademy.in', '1234567898', 'Uttar Prades', 'Gautam Buddha nagar', 'Harola', 'Sector - 16', '201301', '', 'Test Data', 'dummy text lorem ipsum ', 'STAFF1231233', 'lavkush-kumar-freelancer.png', '2021-07-18 12:44:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(5, 'kushkush', '$2y$10$pkgNOc0r6DaiDnCTIVT/VubRm0LqncpPgipzdARaH/9wZto.zmYLu', '2021-05-22 00:30:03'),
(6, '123123', '$2y$10$AwA0obkWAdzF6Z6zCqZ3Xu5QinFNWhL89iAUde8YYfYorruaxOjCm', '2021-07-17 16:49:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student_data`
--
ALTER TABLE `student_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student_data`
--
ALTER TABLE `student_data`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
