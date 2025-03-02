-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 02, 2025 at 06:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ems_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`) VALUES
(1, 'ajay', 'upadhyay', 'ajayupadhyay@99notes.in', '$2y$10$hAzUREpYtPtrB0jLWnm5u.Nu.OY8uN6P.Gg78xl/W2xECWNTF2FcS', '2025-02-13 16:37:38');

-- --------------------------------------------------------

--
-- Table structure for table `assigned_tasks`
--

CREATE TABLE `assigned_tasks` (
  `id` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `task_description` text NOT NULL,
  `status` enum('Pending','Processing','Completed','Rejected') DEFAULT 'Pending',
  `issue` text DEFAULT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deadline` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `punch_in` datetime DEFAULT NULL,
  `punch_out` datetime DEFAULT NULL,
  `work_hours` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `punch_in`, `punch_out`, `work_hours`) VALUES
(8, 1, '2025-02-13 22:24:29', '2025-02-13 22:25:11', 0.01),
(11, 1, '2025-02-15 15:28:30', '2025-02-15 15:28:35', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `created_at`) VALUES
(3, 'SEO', '2025-02-24 17:33:19'),
(4, 'Web Developer', '2025-02-24 17:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `department_id`, `title`, `created_at`) VALUES
(2, 3, 'SEO Executive', '2025-02-24 17:33:38'),
(3, 3, 'SEO Intern', '2025-02-24 17:33:49'),
(4, 4, 'Wordpress Developer', '2025-02-24 17:34:21');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `employee_contract` enum('Full Time','Part Time') NOT NULL,
  `shift` enum('Morning Shift','Night Shift') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `department_id` int(11) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `date_of_joining` date NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Trans') NOT NULL,
  `marital_status` enum('Single','Married','Divorced','Widowed') NOT NULL,
  `pay_scale` varchar(50) NOT NULL,
  `emergency_contact` varchar(15) NOT NULL,
  `work_location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `middle_name`, `last_name`, `address`, `zip_code`, `contact_number`, `email`, `password`, `employee_contract`, `shift`, `created_at`, `department_id`, `designation_id`, `date_of_joining`, `date_of_birth`, `gender`, `marital_status`, `pay_scale`, `emergency_contact`, `work_location`) VALUES
(1, 'Ajay ', '', 'Upadhyay', 'shiv vihar', '110094', '9354316007', 'ajayupadhyay@99notes.in', '$2y$10$dBUK.eEzeHCwPcZ3u..vU.S7QM61xrKnfsunPz7S3MCTNwgp4tkM6', 'Full Time', 'Morning Shift', '2025-02-12 16:23:33', 0, 0, '0000-00-00', '0000-00-00', 'Male', 'Single', '', '', ''),
(18, 'Pulakit', '', 'bharti', 'banaras', '4646', '74754765788', 'pulakitbharti@gmail.com', '$2y$10$WdmyqrFmlhhgrHeOAyRxxuZ86kZ4jILGWT4BYvC5C.0BAMCU6qVim', 'Full Time', 'Morning Shift', '2025-02-15 09:36:24', 0, 0, '0000-00-00', '0000-00-00', 'Male', 'Single', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `ip_restrictions`
--

CREATE TABLE `ip_restrictions` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `ip_restriction_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ip_restrictions`
--

INSERT INTO `ip_restrictions` (`id`, `ip_address`, `ip_restriction_enabled`, `created_at`) VALUES
(2, '106.215.88.169', 1, '2025-03-02 17:19:13'),
(3, '205.254.176.140', 1, '2025-03-02 17:19:26'),
(5, '122.162.145.234', 1, '2025-03-02 17:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `restricted_users`
--

CREATE TABLE `restricted_users` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `task_description` text NOT NULL,
  `task_date` date DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `employee_id`, `task_description`, `task_date`, `created_at`) VALUES
(1, 1, 'testing 123\r\n', '2025-02-12', '2025-02-12 16:46:01'),
(2, 1, 'testing 2', '2025-02-12', '2025-02-12 17:08:05'),
(3, 1, '<ul>\r\n<li>task1</li>\r\n<li>guihkhkl</li>\r\n<li>jbkjbkj</li>\r\n<li>jbkjbk</li>\r\n<li>hii</li>\r\n</ul>', '2025-02-13', '2025-02-13 08:22:53'),
(4, 1, '<ul>\r\n<li>homepage</li>\r\n<li>giubkk</li>\r\n<li>klnkln</li>\r\n</ul>', '2025-02-13', '2025-02-13 16:56:10'),
(5, 1, '<p>working on website</p>\r\n<ol>\r\n<li>vjgkj</li>\r\n<li>jkgkjgkj<br><br></li>\r\n</ol>', '2025-02-15', '2025-02-15 07:22:06'),
(6, 1, '<p>hii</p>', '2025-02-15', '2025-02-15 09:33:41'),
(7, 1, '', '2025-02-15', '2025-02-15 09:43:40'),
(8, 1, '<p>hhkh</p>', '2025-02-15', '2025-02-15 09:47:54'),
(9, 1, '<p>hihiihbgj</p>', '2025-02-15', '2025-02-15 09:49:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `assigned_tasks`
--
ALTER TABLE `assigned_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_by` (`assigned_by`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contact_number` (`contact_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ip_restrictions`
--
ALTER TABLE `ip_restrictions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_address` (`ip_address`);

--
-- Indexes for table `restricted_users`
--
ALTER TABLE `restricted_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assigned_tasks`
--
ALTER TABLE `assigned_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `ip_restrictions`
--
ALTER TABLE `ip_restrictions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `restricted_users`
--
ALTER TABLE `restricted_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned_tasks`
--
ALTER TABLE `assigned_tasks`
  ADD CONSTRAINT `assigned_tasks_ibfk_1` FOREIGN KEY (`assigned_by`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assigned_tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `designation`
--
ALTER TABLE `designation`
  ADD CONSTRAINT `designation_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `restricted_users`
--
ALTER TABLE `restricted_users`
  ADD CONSTRAINT `restricted_users_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
