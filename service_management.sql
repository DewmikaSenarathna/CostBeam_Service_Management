-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2025 at 07:29 PM
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
-- Database: `service_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `agreement_rates`
--

CREATE TABLE `agreement_rates` (
  `rate_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `rate_name` varchar(100) NOT NULL,
  `rate_value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agreement_rates`
--

INSERT INTO `agreement_rates` (`rate_id`, `project_id`, `rate_name`, `rate_value`) VALUES
(4, 2, 'Rate 01', 500.00),
(5, 2, 'Rate 02', 1000.00),
(6, 2, 'Rate 04', 10.00),
(7, 3, 'Rate 01', 10.00),
(8, 3, 'Rate 01', 200.00),
(9, 3, 'Rate 03', 100.00),
(10, 3, 'Rate 04', 120.00),
(11, 3, 'Rate 05', 1000.00),
(12, 3, 'Rate 06', 10.00),
(13, 4, 'Rate 01', 100.00),
(14, 4, 'Rate 01', 120.00),
(15, 4, 'Rate 03', 50.00),
(16, 4, 'Rate 06', 600.00),
(17, 5, 'Rate 01', 100.00),
(18, 5, 'Rate 03', 120.00),
(19, 5, 'Rate 02', 1200.00),
(20, 6, 'Rate 05', 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contractor`
--

CREATE TABLE `contractor` (
  `contractor_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contractor`
--

INSERT INTO `contractor` (`contractor_id`, `name`, `email`) VALUES
(10, 'User 02', 'user02@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_entries`
--

CREATE TABLE `inventory_entries` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `main_cat` varchar(50) NOT NULL,
  `sub_cat` varchar(50) DEFAULT NULL,
  `unit_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `total_cost` decimal(12,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_entries`
--

INSERT INTO `inventory_entries` (`id`, `project_id`, `main_cat`, `sub_cat`, `unit_id`, `quantity`, `total_cost`, `description`, `created_at`) VALUES
(10, 5, 'Tools and Instruments', NULL, 1, 100.00, 100000.00, 'Project 03', '2025-08-24 10:21:59');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_usage`
--

CREATE TABLE `inventory_usage` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `main_cat` varchar(50) NOT NULL,
  `sub_cat` varchar(50) DEFAULT NULL,
  `unit_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `used_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_usage`
--

INSERT INTO `inventory_usage` (`id`, `project_id`, `main_cat`, `sub_cat`, `unit_id`, `quantity`, `used_at`) VALUES
(9, 5, 'Tools and Instruments', NULL, 1, 10.00, '2025-08-24 10:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `manager_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`manager_id`, `name`, `email`) VALUES
(11, 'User 03', 'user03@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `owner_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`owner_id`, `name`, `email`) VALUES
(9, 'User 01', 'user01@gmail.com'),
(15, 'User', 'user07@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_name` varchar(100) DEFAULT NULL,
  `contract_number` varchar(100) DEFAULT NULL,
  `contractor` varchar(100) DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `consultancy` varchar(100) DEFAULT NULL,
  `estimated_value` varchar(100) DEFAULT NULL,
  `started_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `status` enum('active','completed') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `user_id`, `project_name`, `contract_number`, `contractor`, `employer`, `consultancy`, `estimated_value`, `started_date`, `completion_date`, `description`, `status`) VALUES
(2, 9, 'Project 01', 'ED/7/99/02/134', 'Ganepalla', 'Provincial council', 'Provincial council', '12 million', '2025-08-20', '2025-09-20', '', 'completed'),
(3, 10, 'Project 01', 'ED/7/99/02/134', 'Ganepalla', 'Provincial council', 'Provincial council', '10 million', '2025-08-20', '2025-09-20', '', 'active'),
(4, 9, 'Project 02', 'ED/7/99/02/135', 'Ganepalla', 'Provincial council', 'Provincial council', '10 million', '2025-08-21', '2025-08-22', '', 'completed'),
(5, 9, 'Project 03', 'ED/7/99/02/134', 'Ganepalla', 'Provincial council', 'Provincial council', '12 million', '2025-08-23', '2025-09-23', 'Project 03', 'active'),
(6, 15, 'Project 03', 'ED/7/99/02/1355', 'Ganepalla', 'Provincial council', 'Provincial council', '10 million', '2025-08-24', '2026-10-24', '', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `qs`
--

CREATE TABLE `qs` (
  `qs_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qs`
--

INSERT INTO `qs` (`qs_id`, `name`, `email`) VALUES
(14, 'User 04', 'user04@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `spendings`
--

CREATE TABLE `spendings` (
  `spend_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `spend_value` decimal(10,2) NOT NULL,
  `quantity_id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Records spend entries tied to specific work quantities and rates';

--
-- Dumping data for table `spendings`
--

INSERT INTO `spendings` (`spend_id`, `project_id`, `spend_value`, `quantity_id`, `rate_id`, `date`) VALUES
(5, 3, 120.00, 7, 12, '2025-08-21'),
(6, 2, 120.00, 8, 6, '2025-08-21'),
(8, 4, 500.00, 10, 13, '2025-08-23'),
(9, 4, 800.00, 11, 15, '2025-08-19'),
(10, 4, 1500.00, 12, 16, '2025-08-01'),
(11, 5, 1200.00, 13, 17, '2025-08-23'),
(13, 5, 12000.00, 15, 19, '2025-08-24');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_name`) VALUES
(1, 'Sum'),
(2, 'Item'),
(3, 'Sqm'),
(4, 'm'),
(5, 'Cube'),
(6, 'Ft'),
(7, 'Sq.ft'),
(8, 'Cu.ft'),
(9, 'Kg'),
(10, 'Nos'),
(11, 'Mt'),
(12, 'set'),
(13, 'Pairs'),
(14, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('owner','contractor','manager','qs') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`) VALUES
(9, 'User 01', 'user01@gmail.com', '$2y$10$Vs8a5V08MNanEEnxNSZxBOZYTgoekIKYazBB1QoV5HQyBR8u4S7zy', 'owner'),
(10, 'User 02', 'user02@gmail.com', '$2y$10$V247gYcgsJPOrHgXEI1JjuCbjngh5PzLxB2sxFP6gAdRi6kaWy//.', 'contractor'),
(11, 'User 03', 'user03@gmail.com', '$2y$10$a2lJkXRYuPHP1RrVKb1W/ems8Y8.liDjobpYcGlMzrEd95CL2bx2q', 'manager'),
(14, 'User 04', 'user04@gmail.com', '$2y$10$OyjtKn9lCBShRg06e9BAZedXC6pm7LZA1mT4rzV01yJpY62.tJ/6m', 'qs'),
(15, 'User', 'user07@gmail.com', '$2y$10$jMFJmk01ryW3N6XNs0ySg.lX3vO9A.1/CaMw0LXMSkjq0F3aqaLxq', 'owner');

-- --------------------------------------------------------

--
-- Table structure for table `work_quantities`
--

CREATE TABLE `work_quantities` (
  `quantity_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `work_name` varchar(255) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores quantities of work with associated rate, unit, and work name';

--
-- Dumping data for table `work_quantities`
--

INSERT INTO `work_quantities` (`quantity_id`, `project_id`, `rate_id`, `quantity`, `work_name`, `unit_id`, `date`) VALUES
(7, 3, 12, 120.00, 'work04', 1, '2025-08-21'),
(8, 2, 4, 10.00, 'work01', 2, '2025-08-21'),
(10, 4, 13, 12.00, 'work01', 4, '2025-08-23'),
(11, 4, 15, 10.00, 'w6', 5, '2025-08-20'),
(12, 4, 16, 20.00, 'w1', 8, '2025-08-01'),
(13, 5, 17, 200.00, 'Work01', 1, '2025-08-23'),
(15, 5, 19, 120.00, 'work03', 4, '2025-08-24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agreement_rates`
--
ALTER TABLE `agreement_rates`
  ADD PRIMARY KEY (`rate_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `contractor`
--
ALTER TABLE `contractor`
  ADD PRIMARY KEY (`contractor_id`);

--
-- Indexes for table `inventory_entries`
--
ALTER TABLE `inventory_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `inventory_usage`
--
ALTER TABLE `inventory_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`manager_id`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`owner_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `qs`
--
ALTER TABLE `qs`
  ADD PRIMARY KEY (`qs_id`);

--
-- Indexes for table `spendings`
--
ALTER TABLE `spendings`
  ADD PRIMARY KEY (`spend_id`),
  ADD KEY `idx_spend_project` (`project_id`),
  ADD KEY `idx_spend_quantity` (`quantity_id`),
  ADD KEY `idx_spend_rate` (`rate_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `work_quantities`
--
ALTER TABLE `work_quantities`
  ADD PRIMARY KEY (`quantity_id`),
  ADD KEY `idx_project` (`project_id`),
  ADD KEY `idx_rate` (`rate_id`),
  ADD KEY `idx_unit` (`unit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agreement_rates`
--
ALTER TABLE `agreement_rates`
  MODIFY `rate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_entries`
--
ALTER TABLE `inventory_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `inventory_usage`
--
ALTER TABLE `inventory_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `spendings`
--
ALTER TABLE `spendings`
  MODIFY `spend_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `work_quantities`
--
ALTER TABLE `work_quantities`
  MODIFY `quantity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agreement_rates`
--
ALTER TABLE `agreement_rates`
  ADD CONSTRAINT `agreement_rates_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_entries`
--
ALTER TABLE `inventory_entries`
  ADD CONSTRAINT `inventory_entries_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_entries_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`);

--
-- Constraints for table `inventory_usage`
--
ALTER TABLE `inventory_usage`
  ADD CONSTRAINT `inventory_usage_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_usage_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `spendings`
--
ALTER TABLE `spendings`
  ADD CONSTRAINT `fk_spend_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_spend_quantity` FOREIGN KEY (`quantity_id`) REFERENCES `work_quantities` (`quantity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_spend_rate` FOREIGN KEY (`rate_id`) REFERENCES `agreement_rates` (`rate_id`) ON UPDATE CASCADE;

--
-- Constraints for table `work_quantities`
--
ALTER TABLE `work_quantities`
  ADD CONSTRAINT `fk_wq_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wq_rate` FOREIGN KEY (`rate_id`) REFERENCES `agreement_rates` (`rate_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wq_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
