-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2025 at 08:01 AM
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
-- Database: `machine_and_applicator`
--

-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `machine_and_applicator`;
USE `machine_and_applicator`;

--
-- Table structure for table `applicators`
--

CREATE TABLE `applicators` (
  `applicator_id` int(11) NOT NULL,
  `hp_no` varchar(50) NOT NULL,
  `terminal_no` varchar(50) NOT NULL,
  `description` enum('SIDE','END','CLAMP','STRIP AND CRIMP') NOT NULL,
  `wire` enum('BIG','SMALL') NOT NULL,
  `terminal_maker` varchar(50) NOT NULL,
  `applicator_maker` varchar(50) NOT NULL,
  `serial_no` varchar(50) DEFAULT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `last_encoded` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicator_outputs`
--

CREATE TABLE `applicator_outputs` (
  `applicator_output_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `applicator_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `total_output` int(11) NOT NULL,
  `wire_crimper` int(11) NOT NULL,
  `wire_anvil` int(11) NOT NULL,
  `insulation_crimper` int(11) NOT NULL,
  `insulation_anvil` int(11) NOT NULL,
  `slide_cutter` int(11) DEFAULT NULL,
  `cutter_holder` int(11) DEFAULT NULL,
  `shear_blade` int(11) DEFAULT NULL,
  `cutter_a` int(11) DEFAULT NULL,
  `cutter_b` int(11) DEFAULT NULL,
  `custom_parts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_parts`))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicator_part_limits`
--

CREATE TABLE `applicator_part_limits` (
  `applicator_limit_id` int(11) NOT NULL,
  `applicator_id` int(11) NOT NULL,
  `applicator_part` varchar(50) NOT NULL,
  `part_limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicator_reset`
--

CREATE TABLE `applicator_reset` (
  `reset_id` int(11) NOT NULL,
  `applicator_id` int(11) NOT NULL,
  `reset_by` int(11) NOT NULL,
  `part_reset` varchar(50) NOT NULL,
  `previous_value` int(11) NOT NULL,
  `reset_time` datetime DEFAULT current_timestamp(),
  `undone_by` int(11) DEFAULT NULL,
  `undone_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_part_definitions`
--

CREATE TABLE `custom_part_definitions` (
  `part_id` int(11) NOT NULL,
  `equipment_type` enum('MACHINE','APPLICATOR') NOT NULL,
  `part_name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `machine_id` int(11) NOT NULL,
  `control_no` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `maker` varchar(50) NOT NULL,
  `serial_no` varchar(50) DEFAULT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `last_encoded` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machine_outputs`
--

CREATE TABLE `machine_outputs` (
  `machine_output_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `total_machine_output` int(11) NOT NULL,
  `cut_blade` int(11) NOT NULL,
  `strip_blade_a` int(11) NOT NULL,
  `strip_blade_b` int(11) NOT NULL,
  `custom_parts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_parts`))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machine_part_limits`
--

CREATE TABLE `machine_part_limits` (
  `machine_limit_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `machine_part` varchar(50) NOT NULL,
  `part_limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machine_reset`
--

CREATE TABLE `machine_reset` (
  `reset_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `reset_by` int(11) NOT NULL,
  `part_reset` varchar(50) NOT NULL,
  `previous_value` int(11) NOT NULL,
  `reset_time` datetime DEFAULT current_timestamp(),
  `undone_by` int(11) DEFAULT NULL,
  `undone_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monitor_applicator`
--

CREATE TABLE `monitor_applicator` (
  `monitor_id` int(11) NOT NULL,
  `applicator_id` int(11) NOT NULL,
  `total_output` int(11) DEFAULT 0,
  `wire_crimper_output` int(11) DEFAULT 0,
  `wire_anvil_output` int(11) DEFAULT 0,
  `insulation_crimper_output` int(11) DEFAULT 0,
  `insulation_anvil_output` int(11) DEFAULT 0,
  `slide_cutter_output` int(11) DEFAULT 0,
  `cutter_holder_output` int(11) DEFAULT 0,
  `shear_blade_output` int(11) DEFAULT 0,
  `cutter_a_output` int(11) DEFAULT 0,
  `cutter_b_output` int(11) DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `custom_parts_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_parts_output`)),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monitor_machine`
--

CREATE TABLE `monitor_machine` (
  `monitor_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `total_machine_output` int(11) DEFAULT 0,
  `cut_blade_output` int(11) DEFAULT 0,
  `strip_blade_a_output` int(11) DEFAULT 0,
  `strip_blade_b_output` int(11) DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `custom_parts_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_parts_output`)),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `record_id` int(11) NOT NULL,
  `shift` enum('1st','2nd','NIGHT') NOT NULL,
  `machine_id` int(11) NOT NULL,
  `applicator1_id` int(11) NOT NULL,
  `applicator2_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `date_inspected` date NOT NULL,
  `date_encoded` datetime NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `user_type` enum('DEFAULT','TOOLKEEPER','ADMIN') DEFAULT 'DEFAULT'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicators`
--
ALTER TABLE `applicators`
  ADD PRIMARY KEY (`applicator_id`),
  ADD UNIQUE KEY `hp_no` (`hp_no`),
  ADD KEY `idx_hp_no` (`hp_no`),
  ADD KEY `idx_terminal_no` (`terminal_no`),
  ADD KEY `idx_terminal_maker` (`terminal_maker`),
  ADD KEY `idx_last_encoded` (`last_encoded`);

--
-- Indexes for table `applicator_outputs`
--
ALTER TABLE `applicator_outputs`
  ADD PRIMARY KEY (`applicator_output_id`),
  ADD KEY `idx_record_id` (`record_id`),
  ADD KEY `idx_applicator_id` (`applicator_id`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `applicator_part_limits`
--
ALTER TABLE `applicator_part_limits`
  ADD PRIMARY KEY (`applicator_limit_id`),
  ADD KEY `idx_applicator_id` (`applicator_id`);

--
-- Indexes for table `applicator_reset`
--
ALTER TABLE `applicator_reset`
  ADD PRIMARY KEY (`reset_id`),
  ADD KEY `reset_by` (`reset_by`),
  ADD KEY `idx_applicator_id` (`applicator_id`),
  ADD KEY `idx_reset_time` (`reset_time`),
  ADD KEY `undone_by` (`undone_by`);

--
-- Indexes for table `custom_part_definitions`
--
ALTER TABLE `custom_part_definitions`
  ADD PRIMARY KEY (`part_id`),
  ADD UNIQUE KEY `part_name` (`part_name`),
  ADD UNIQUE KEY `unique_equipment_part` (`equipment_type`,`part_name`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_equipment_type` (`equipment_type`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`machine_id`),
  ADD UNIQUE KEY `control_no` (`control_no`),
  ADD KEY `idx_control_no` (`control_no`),
  ADD KEY `idx_maker` (`maker`),
  ADD KEY `idx_last_encoded` (`last_encoded`);

--
-- Indexes for table `machine_outputs`
--
ALTER TABLE `machine_outputs`
  ADD PRIMARY KEY (`machine_output_id`),
  ADD KEY `idx_record_id` (`record_id`),
  ADD KEY `idx_machine_id` (`machine_id`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `machine_part_limits`
--
ALTER TABLE `machine_part_limits`
  ADD PRIMARY KEY (`machine_limit_id`),
  ADD KEY `idx_machine_id` (`machine_id`);

--
-- Indexes for table `machine_reset`
--
ALTER TABLE `machine_reset`
  ADD PRIMARY KEY (`reset_id`),
  ADD KEY `reset_by` (`reset_by`),
  ADD KEY `idx_machine_id` (`machine_id`),
  ADD KEY `idx_reset_time` (`reset_time`),
  ADD KEY `undone_by` (`undone_by`);

--
-- Indexes for table `monitor_applicator`
--
ALTER TABLE `monitor_applicator`
  ADD PRIMARY KEY (`monitor_id`),
  ADD UNIQUE KEY `applicator_id` (`applicator_id`),
  ADD KEY `idx_applicator_id` (`applicator_id`),
  ADD KEY `idx_last_updated` (`last_updated`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_active_applicator_id` (`is_active`,`applicator_id`);

--
-- Indexes for table `monitor_machine`
--
ALTER TABLE `monitor_machine`
  ADD PRIMARY KEY (`monitor_id`),
  ADD UNIQUE KEY `machine_id` (`machine_id`),
  ADD KEY `idx_machine_id` (`machine_id`),
  ADD KEY `idx_last_updated` (`last_updated`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_active_machine_id` (`is_active`,`machine_id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `idx_machine_id` (`machine_id`),
  ADD KEY `idx_created_by` (`created_by`),
  ADD KEY `idx_date_encoded` (`date_encoded`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_applicator1_id` (`applicator1_id`),
  ADD KEY `idx_applicator2_id` (`applicator2_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicators`
--
ALTER TABLE `applicators`
  MODIFY `applicator_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicator_outputs`
--
ALTER TABLE `applicator_outputs`
  MODIFY `applicator_output_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicator_part_limits`
--
ALTER TABLE `applicator_part_limits`
  MODIFY `applicator_limit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicator_reset`
--
ALTER TABLE `applicator_reset`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_part_definitions`
--
ALTER TABLE `custom_part_definitions`
  MODIFY `part_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `machine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_outputs`
--
ALTER TABLE `machine_outputs`
  MODIFY `machine_output_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_part_limits`
--
ALTER TABLE `machine_part_limits`
  MODIFY `machine_limit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machine_reset`
--
ALTER TABLE `machine_reset`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monitor_applicator`
--
ALTER TABLE `monitor_applicator`
  MODIFY `monitor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monitor_machine`
--
ALTER TABLE `monitor_machine`
  MODIFY `monitor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applicator_outputs`
--
ALTER TABLE `applicator_outputs`
  ADD CONSTRAINT `applicator_outputs_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `records` (`record_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applicator_outputs_ibfk_2` FOREIGN KEY (`applicator_id`) REFERENCES `applicators` (`applicator_id`);

--
-- Constraints for table `applicator_part_limits`
--
ALTER TABLE `applicator_part_limits`
  ADD CONSTRAINT `fk_applicator_part_limits_applicator` FOREIGN KEY (`applicator_id`) REFERENCES `applicators` (`applicator_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `applicator_reset`
--
ALTER TABLE `applicator_reset`
  ADD CONSTRAINT `applicator_reset_ibfk_1` FOREIGN KEY (`applicator_id`) REFERENCES `applicators` (`applicator_id`),
  ADD CONSTRAINT `applicator_reset_ibfk_2` FOREIGN KEY (`reset_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `applicator_reset_ibfk_3` FOREIGN KEY (`undone_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `custom_part_definitions`
--
ALTER TABLE `custom_part_definitions`
  ADD CONSTRAINT `custom_part_definitions_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `machine_outputs`
--
ALTER TABLE `machine_outputs`
  ADD CONSTRAINT `machine_outputs_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `records` (`record_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `machine_outputs_ibfk_2` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`machine_id`);

--
-- Constraints for table `machine_part_limits`
--
ALTER TABLE `machine_part_limits`
  ADD CONSTRAINT `fk_machine_part_limits_machine` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`machine_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `machine_reset`
--
ALTER TABLE `machine_reset`
  ADD CONSTRAINT `machine_reset_ibfk_1` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`machine_id`),
  ADD CONSTRAINT `machine_reset_ibfk_2` FOREIGN KEY (`reset_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `machine_reset_ibfk_3` FOREIGN KEY (`undone_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `monitor_applicator`
--
ALTER TABLE `monitor_applicator`
  ADD CONSTRAINT `monitor_applicator_ibfk_1` FOREIGN KEY (`applicator_id`) REFERENCES `applicators` (`applicator_id`) ON DELETE CASCADE;

--
-- Constraints for table `monitor_machine`
--
ALTER TABLE `monitor_machine`
  ADD CONSTRAINT `monitor_machine_ibfk_1` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`machine_id`) ON DELETE CASCADE;

--
-- Constraints for table `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `records_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `records_ibfk_2` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`machine_id`),
  ADD CONSTRAINT `records_ibfk_3` FOREIGN KEY (`applicator1_id`) REFERENCES `applicators` (`applicator_id`),
  ADD CONSTRAINT `records_ibfk_4` FOREIGN KEY (`applicator2_id`) REFERENCES `applicators` (`applicator_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
