-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2025 at 01:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

-- Drop the database if existing (WARNING!!! WILL DELETE ALL DATA PERTAINING TO PREVIOUS DATABASE!)
-- DROP DATABASE IF EXISTS machine_and_applicator;

-- Proceed with import
CREATE DATABASE IF NOT EXISTS machine_and_applicator;
USE machine_and_applicator;

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

--
-- Dumping data for table `applicators`
--

INSERT INTO `applicators` (`applicator_id`, `hp_no`, `terminal_no`, `description`, `wire`, `terminal_maker`, `applicator_maker`, `serial_no`, `invoice_no`, `last_encoded`, `is_active`) VALUES
(1, 'HP001', 'TRM-001', 'SIDE', 'BIG', 'TYCO', 'KOMAX', 'SN001', 'INV001', '2024-01-15 08:30:00', 1),
(2, 'HP002', 'TRM-002', 'END', 'SMALL', 'NYOLEX', 'SCHLEUNIGER', 'SN002', 'INV002', '2024-01-16 09:15:00', 1),
(3, 'HP003', 'TRM-003', 'SIDE', 'BIG', 'JST', 'KOMAX', 'SN003', 'INV003', '2024-01-17 10:45:00', 1),
(4, 'HP004', 'TRM-004', 'STRIP AND CRIMP', 'SMALL', 'TYCO', 'SCHLEUNIGER', 'SN004', 'INV004', '2024-01-18 11:20:00', 1),
(5, 'HP005', 'TRM-005', 'SIDE', 'BIG', 'MOLEX', 'KOMAX', 'SN005', 'INV005', '2024-01-19 13:30:00', 1),
(6, 'HP006', 'TRM-006', 'END', 'SMALL', 'JST', 'SCHLEUNIGER', 'SN006', 'INV006', '2024-01-20 14:15:00', 1),
(7, 'HP007', 'TRM-007', 'CLAMP', 'BIG', 'TYCO', 'KOMAX', 'SN007', 'INV007', '2024-01-21 15:45:00', 1),
(8, 'HP008', 'TRM-008', 'STRIP AND CRIMP', 'SMALL', 'MOLEX', 'SCHLEUNIGER', 'SN008', 'INV008', '2024-01-22 16:20:00', 1),
(9, 'HP009', 'TRM-009', 'SIDE', 'BIG', 'JST', 'KOMAX', 'SN009', 'INV009', '2024-01-23 08:00:00', 1),
(10, 'HP010', 'TRM-010', 'END', 'SMALL', 'TYCO', 'SCHLEUNIGER', 'SN010', 'INV010', '2024-01-24 09:30:00', 1),
(11, 'HP011', 'TRM-011', 'CLAMP', 'BIG', 'MOLEX', 'KOMAX', 'SN011', 'INV011', '2024-01-25 10:15:00', 1),
(12, 'HP012', 'TRM-012', 'STRIP AND CRIMP', 'SMALL', 'JST', 'SCHLEUNIGER', 'SN012', 'INV012', '2024-01-26 11:45:00', 1),
(13, 'HP013', 'TRM-013', 'SIDE', 'BIG', 'TYCO', 'KOMAX', 'SN013', 'INV013', '2024-01-27 12:30:00', 1),
(14, 'HP014', 'TRM-014', 'END', 'SMALL', 'MOLEX', 'SCHLEUNIGER', 'SN014', 'INV014', '2024-01-28 13:15:00', 1),
(15, 'HP015', 'TRM-015', 'CLAMP', 'BIG', 'JST', 'KOMAX', 'SN015', 'INV015', '2024-01-29 14:45:00', 1),
(16, 'HP016', 'TRM-016', 'STRIP AND CRIMP', 'SMALL', 'TYCO', 'SCHLEUNIGER', 'SN016', 'INV016', '2024-01-30 15:30:00', 1),
(17, 'HP017', 'TRM-017', 'SIDE', 'BIG', 'MOLEX', 'KOMAX', 'SN017', 'INV017', '2024-01-31 16:15:00', 1),
(18, 'HP018', 'TRM-018', 'END', 'SMALL', 'JST', 'SCHLEUNIGER', 'SN018', 'INV018', '2024-02-01 08:45:00', 1),
(19, 'HP019', 'TRM-019', 'CLAMP', 'BIG', 'TYCO', 'KOMAX', 'SN019', 'INV019', '2024-02-02 09:30:00', 1),
(20, 'HP020', 'TRM-020', 'STRIP AND CRIMP', 'SMALL', 'MOLEX', 'SCHLEUNIGER', 'SN020', 'INV020', '2024-02-03 10:15:00', 1),
(21, 'FASASF', 'DSFSADFA', 'SIDE', 'BIG', 'ASDFSDAF', 'ASDFSDAF', 'ASDFSDAF', 'ASDFSDAF', NULL, 0),
(22, 'EYEBOLLORD', 'DSFSADFA', 'SIDE', 'BIG', 'ASDFSDAF', 'ASDFSDAF', 'ASDFSADF', 'ASDFSDAF', NULL, 0);

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

--
-- Dumping data for table `applicator_outputs`
--

INSERT INTO `applicator_outputs` (`applicator_output_id`, `record_id`, `applicator_id`, `is_active`, `total_output`, `wire_crimper`, `wire_anvil`, `insulation_crimper`, `insulation_anvil`, `slide_cutter`, `cutter_holder`, `shear_blade`, `cutter_a`, `cutter_b`, `custom_parts`) VALUES
(1, 1, 1, 1, 1500, 250, 220, 180, 175, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 45, \"tension_spring\": 35}'),
(2, 1, 2, 1, 1400, 240, 210, 170, 165, 145, 135, 125, 115, 105, '{\"alignment_pin\": 25, \"pressure_pad\": 40}'),
(3, 2, 3, 1, 1600, 260, 230, 190, 185, 160, 150, 140, 130, 120, '{\"special_guide_plate\": 50, \"tension_spring\": 30}'),
(4, 2, 4, 1, 1550, 255, 225, 185, 180, 155, 145, 135, 125, 115, '{\"alignment_pin\": 28, \"pressure_pad\": 42}'),
(5, 3, 5, 1, 1450, 245, 215, 175, 170, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 48, \"tension_spring\": 32}'),
(6, 3, 6, 1, 1350, 235, 205, 165, 160, 140, 130, 120, 110, 100, '{\"alignment_pin\": 22, \"pressure_pad\": 38}'),
(7, 4, 7, 1, 1700, 270, 240, 200, 195, 170, 160, 150, 140, 130, '{\"special_guide_plate\": 52, \"tension_spring\": 28}'),
(8, 4, 8, 1, 1650, 265, 235, 195, 190, 165, 155, 145, 135, 125, '{\"alignment_pin\": 30, \"pressure_pad\": 45}'),
(9, 5, 9, 1, 1500, 250, 220, 180, 175, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 46, \"tension_spring\": 34}'),
(10, 5, 10, 1, 1400, 240, 210, 170, 165, 145, 135, 125, 115, 105, '{\"alignment_pin\": 26, \"pressure_pad\": 41}'),
(11, 6, 11, 1, 1550, 255, 225, 185, 180, 155, 145, 135, 125, 115, '{\"special_guide_plate\": 49, \"tension_spring\": 31}'),
(12, 6, 12, 1, 1600, 260, 230, 190, 185, 160, 150, 140, 130, 120, '{\"alignment_pin\": 29, \"pressure_pad\": 43}'),
(13, 7, 13, 1, 1450, 245, 215, 175, 170, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 47, \"tension_spring\": 33}'),
(14, 7, 14, 1, 1650, 265, 235, 195, 190, 165, 155, 145, 135, 125, '{\"alignment_pin\": 31, \"pressure_pad\": 44}'),
(15, 8, 15, 1, 1500, 250, 220, 180, 175, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 45, \"tension_spring\": 35}'),
(16, 8, 16, 1, 1400, 240, 210, 170, 165, 145, 135, 125, 115, 105, '{\"alignment_pin\": 25, \"pressure_pad\": 40}'),
(17, 9, 17, 1, 1700, 270, 240, 200, 195, 170, 160, 150, 140, 130, '{\"special_guide_plate\": 53, \"tension_spring\": 27}'),
(18, 9, 18, 1, 1550, 255, 225, 185, 180, 155, 145, 135, 125, 115, '{\"alignment_pin\": 28, \"pressure_pad\": 42}'),
(19, 10, 19, 1, 1600, 260, 230, 190, 185, 160, 150, 140, 130, 120, '{\"special_guide_plate\": 51, \"tension_spring\": 29}'),
(20, 10, 20, 1, 1450, 245, 215, 175, 170, 150, 140, 130, 120, 110, '{\"alignment_pin\": 24, \"pressure_pad\": 39}'),
(21, 11, 1, 1, 1500, 250, 220, 180, 175, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 46, \"tension_spring\": 34}'),
(22, 11, 3, 1, 1350, 235, 205, 165, 160, 140, 130, 120, 110, 100, '{\"alignment_pin\": 23, \"pressure_pad\": 37}'),
(23, 12, 2, 1, 1400, 240, 210, 170, 165, 145, 135, 125, 115, 105, '{\"special_guide_plate\": 44, \"tension_spring\": 36}'),
(24, 12, 4, 1, 1650, 265, 235, 195, 190, 165, 155, 145, 135, 125, '{\"alignment_pin\": 32, \"pressure_pad\": 46}'),
(25, 13, 5, 1, 1550, 255, 225, 185, 180, 155, 145, 135, 125, 115, '{\"special_guide_plate\": 48, \"tension_spring\": 32}'),
(26, 13, 7, 1, 1600, 260, 230, 190, 185, 160, 150, 140, 130, 120, '{\"alignment_pin\": 27, \"pressure_pad\": 41}'),
(27, 14, 6, 1, 1450, 245, 215, 175, 170, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 47, \"tension_spring\": 33}'),
(28, 14, 8, 1, 1500, 250, 220, 180, 175, 150, 140, 130, 120, 110, '{\"alignment_pin\": 26, \"pressure_pad\": 40}'),
(29, 15, 9, 1, 1400, 240, 210, 170, 165, 145, 135, 125, 115, 105, '{\"special_guide_plate\": 45, \"tension_spring\": 35}'),
(30, 15, 11, 1, 1700, 270, 240, 200, 195, 170, 160, 150, 140, 130, '{\"alignment_pin\": 33, \"pressure_pad\": 47}'),
(31, 16, 10, 1, 1550, 255, 225, 185, 180, 155, 145, 135, 125, 115, '{\"special_guide_plate\": 49, \"tension_spring\": 31}'),
(32, 16, 12, 1, 1600, 260, 230, 190, 185, 160, 150, 140, 130, 120, '{\"alignment_pin\": 29, \"pressure_pad\": 43}'),
(33, 17, 13, 1, 1450, 245, 215, 175, 170, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 47, \"tension_spring\": 33}'),
(34, 17, 15, 1, 1650, 265, 235, 195, 190, 165, 155, 145, 135, 125, '{\"alignment_pin\": 31, \"pressure_pad\": 44}'),
(35, 18, 14, 1, 1500, 250, 220, 180, 175, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 46, \"tension_spring\": 34}'),
(36, 18, 16, 1, 1400, 240, 210, 170, 165, 145, 135, 125, 115, 105, '{\"alignment_pin\": 25, \"pressure_pad\": 40}'),
(37, 19, 17, 1, 1700, 270, 240, 200, 195, 170, 160, 150, 140, 130, '{\"special_guide_plate\": 52, \"tension_spring\": 28}'),
(38, 19, 19, 1, 1550, 255, 225, 185, 180, 155, 145, 135, 125, 115, '{\"alignment_pin\": 28, \"pressure_pad\": 42}'),
(39, 20, 18, 1, 1600, 260, 230, 190, 185, 160, 150, 140, 130, 120, '{\"special_guide_plate\": 50, \"tension_spring\": 30}'),
(40, 20, 20, 1, 1450, 245, 215, 175, 170, 150, 140, 130, 120, 110, '{\"alignment_pin\": 24, \"pressure_pad\": 39}'),
(41, 21, 1, 1, 1500, 250, 220, 180, 175, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 45, \"tension_spring\": 35}'),
(42, 22, 2, 1, 1400, 240, 210, 170, 165, 145, 135, 125, 115, 105, '{\"alignment_pin\": 25, \"pressure_pad\": 40}'),
(43, 23, 3, 1, 1600, 260, 230, 190, 185, 160, 150, 140, 130, 120, '{\"special_guide_plate\": 51, \"tension_spring\": 29}'),
(44, 24, 4, 1, 1550, 255, 225, 185, 180, 155, 145, 135, 125, 115, '{\"alignment_pin\": 28, \"pressure_pad\": 42}'),
(45, 25, 5, 1, 1450, 245, 215, 175, 170, 150, 140, 130, 120, 110, '{\"special_guide_plate\": 47, \"tension_spring\": 33}'),
(49, 28, 1, 1, 5, 5, 5, 5, 5, 5, 5, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(50, 28, 2, 1, 5, 5, 5, 5, 5, NULL, NULL, 5, 5, 5, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(51, 29, 1, 1, 5, 5, 5, 5, 5, 5, 5, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(52, 29, 2, 1, 5, 5, 5, 5, 5, NULL, NULL, 5, 5, 5, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(53, 30, 1, 1, 6, 6, 6, 6, 6, 6, 6, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":6},{\"name\":\"tension_spring\",\"value\":6},{\"name\":\"alignment_pin\",\"value\":6},{\"name\":\"pressure_pad\",\"value\":6}]'),
(54, 30, 2, 1, 6, 6, 6, 6, 6, NULL, NULL, 6, 6, 6, '[{\"name\":\"special_guide_plate\",\"value\":6},{\"name\":\"tension_spring\",\"value\":6},{\"name\":\"alignment_pin\",\"value\":6},{\"name\":\"pressure_pad\",\"value\":6}]'),
(55, 31, 1, 1, 7, 7, 7, 7, 7, 7, 7, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":7},{\"name\":\"tension_spring\",\"value\":7},{\"name\":\"alignment_pin\",\"value\":7},{\"name\":\"pressure_pad\",\"value\":7}]'),
(56, 31, 2, 1, 7, 7, 7, 7, 7, NULL, NULL, 7, 7, 7, '[{\"name\":\"special_guide_plate\",\"value\":7},{\"name\":\"tension_spring\",\"value\":7},{\"name\":\"alignment_pin\",\"value\":7},{\"name\":\"pressure_pad\",\"value\":7}]'),
(57, 32, 1, 1, 5, 5, 5, 5, 5, 5, 5, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(58, 32, 2, 1, 5, 5, 5, 5, 5, NULL, NULL, 5, 5, 5, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(59, 33, 1, 1, 5, 5, 5, 5, 5, 5, 5, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(60, 33, 2, 1, 5, 5, 5, 5, 5, NULL, NULL, 5, 5, 5, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(61, 34, 1, 1, 5, 5, 5, 5, 5, 5, 5, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(62, 34, 2, 1, 5, 5, 5, 5, 5, NULL, NULL, 5, 5, 5, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(63, 35, 1, 1, 5, 5, 5, 5, 5, 5, 5, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(64, 35, 2, 1, 5, 5, 5, 5, 5, NULL, NULL, 5, 5, 5, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(65, 36, 1, 1, 59, 59, 59, 59, 59, 59, 59, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":59},{\"name\":\"tension_spring\",\"value\":59},{\"name\":\"alignment_pin\",\"value\":59},{\"name\":\"pressure_pad\",\"value\":59}]'),
(66, 36, 2, 1, 59, 59, 59, 59, 59, NULL, NULL, 59, 59, 59, '[{\"name\":\"special_guide_plate\",\"value\":59},{\"name\":\"tension_spring\",\"value\":59},{\"name\":\"alignment_pin\",\"value\":59},{\"name\":\"pressure_pad\",\"value\":59}]'),
(67, 37, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(68, 37, 2, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(69, 38, 1, 1, 5, 5, 5, 5, 5, 5, 5, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(70, 38, 2, 1, 5, 5, 5, 5, 5, NULL, NULL, 5, 5, 5, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(71, 44, 1, 1, 5, 5, 5, 5, 5, 5, 5, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(72, 44, 2, 1, 5, 5, 5, 5, 5, NULL, NULL, 5, 5, 5, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(73, 45, 1, 1, 11, 11, 11, 11, 11, 11, 11, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":11},{\"name\":\"tension_spring\",\"value\":11},{\"name\":\"alignment_pin\",\"value\":11},{\"name\":\"pressure_pad\",\"value\":11}]'),
(74, 45, 2, 1, 11, 11, 11, 11, 11, NULL, NULL, 11, 11, 11, '[{\"name\":\"special_guide_plate\",\"value\":11},{\"name\":\"tension_spring\",\"value\":11},{\"name\":\"alignment_pin\",\"value\":11},{\"name\":\"pressure_pad\",\"value\":11}]'),
(75, 50, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(76, 50, 2, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(80, 67, 1, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(162, 149, 1, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(163, 150, 2, 1, 21, 21, 21, 21, 21, NULL, NULL, 21, 21, 21, '[{\"name\":\"special_guide_plate\",\"value\":21},{\"name\":\"tension_spring\",\"value\":21},{\"name\":\"alignment_pin\",\"value\":21},{\"name\":\"pressure_pad\",\"value\":21}]'),
(164, 151, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(165, 152, 4, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(166, 153, 5, 1, 300, 300, 300, 300, 300, 300, 300, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":300},{\"name\":\"tension_spring\",\"value\":300},{\"name\":\"alignment_pin\",\"value\":300},{\"name\":\"pressure_pad\",\"value\":300}]'),
(167, 154, 6, 1, 600, 600, 600, 600, 600, NULL, NULL, 600, 600, 600, '[{\"name\":\"special_guide_plate\",\"value\":600},{\"name\":\"tension_spring\",\"value\":600},{\"name\":\"alignment_pin\",\"value\":600},{\"name\":\"pressure_pad\",\"value\":600}]'),
(168, 155, 7, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(169, 156, 8, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(170, 157, 9, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(171, 158, 10, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(172, 159, 11, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(173, 160, 12, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(174, 161, 13, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(175, 161, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(176, 162, 14, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(177, 162, 4, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(178, 163, 15, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(179, 163, 5, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(180, 164, 16, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(181, 164, 6, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(182, 165, 17, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(183, 165, 7, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(184, 166, 19, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(185, 166, 8, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(186, 167, 19, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(187, 167, 9, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(188, 168, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(189, 168, 10, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(190, 169, 1, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(191, 169, 11, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(192, 170, 2, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(193, 170, 12, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(194, 171, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(195, 171, 13, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(196, 172, 1, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(197, 173, 2, 1, 21, 21, 21, 21, 21, NULL, NULL, 21, 21, 21, '[{\"name\":\"special_guide_plate\",\"value\":21},{\"name\":\"tension_spring\",\"value\":21},{\"name\":\"alignment_pin\",\"value\":21},{\"name\":\"pressure_pad\",\"value\":21}]'),
(198, 174, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(199, 175, 4, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(200, 176, 5, 1, 300, 300, 300, 300, 300, 300, 300, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":300},{\"name\":\"tension_spring\",\"value\":300},{\"name\":\"alignment_pin\",\"value\":300},{\"name\":\"pressure_pad\",\"value\":300}]'),
(201, 177, 6, 1, 600, 600, 600, 600, 600, NULL, NULL, 600, 600, 600, '[{\"name\":\"special_guide_plate\",\"value\":600},{\"name\":\"tension_spring\",\"value\":600},{\"name\":\"alignment_pin\",\"value\":600},{\"name\":\"pressure_pad\",\"value\":600}]'),
(202, 178, 7, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(203, 179, 8, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(204, 180, 9, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(205, 181, 10, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(206, 182, 11, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(207, 183, 12, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(208, 184, 13, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(209, 184, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(210, 185, 14, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(211, 185, 4, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(212, 186, 15, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(213, 186, 5, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(214, 187, 16, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(215, 187, 6, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(216, 188, 17, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(217, 188, 7, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(218, 189, 18, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(219, 189, 8, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(220, 190, 19, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(221, 190, 9, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(222, 191, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(223, 191, 10, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(224, 192, 1, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(225, 192, 11, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(226, 193, 2, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(227, 193, 12, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(228, 194, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(229, 194, 13, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(230, 195, 1, 1, 5, 5, 5, 5, 5, 5, 5, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(231, 195, 2, 1, 5, 5, 5, 5, 5, NULL, NULL, 5, 5, 5, '[{\"name\":\"special_guide_plate\",\"value\":5},{\"name\":\"tension_spring\",\"value\":5},{\"name\":\"alignment_pin\",\"value\":5},{\"name\":\"pressure_pad\",\"value\":5}]'),
(232, 196, 1, 1, 12, 12, 12, 12, 12, 12, 12, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":12},{\"name\":\"tension_spring\",\"value\":12},{\"name\":\"alignment_pin\",\"value\":12},{\"name\":\"pressure_pad\",\"value\":12}]'),
(233, 196, 2, 1, 12, 12, 12, 12, 12, NULL, NULL, 12, 12, 12, '[{\"name\":\"special_guide_plate\",\"value\":12},{\"name\":\"tension_spring\",\"value\":12},{\"name\":\"alignment_pin\",\"value\":12},{\"name\":\"pressure_pad\",\"value\":12}]'),
(234, 197, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(235, 197, 2, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(236, 198, 1, 1, 1500, 1500, 1500, 1500, 1500, 1500, 1500, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":1500},{\"name\":\"tension_spring\",\"value\":1500},{\"name\":\"alignment_pin\",\"value\":1500},{\"name\":\"pressure_pad\",\"value\":1500}]'),
(237, 198, 2, 1, 1500, 1500, 1500, 1500, 1500, NULL, NULL, 1500, 1500, 1500, '[{\"name\":\"special_guide_plate\",\"value\":1500},{\"name\":\"tension_spring\",\"value\":1500},{\"name\":\"alignment_pin\",\"value\":1500},{\"name\":\"pressure_pad\",\"value\":1500}]'),
(238, 199, 1, 1, 1511, 1511, 1511, 1511, 1511, 1511, 1511, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":1511},{\"name\":\"tension_spring\",\"value\":1511},{\"name\":\"alignment_pin\",\"value\":1511},{\"name\":\"pressure_pad\",\"value\":1511}]'),
(239, 199, 2, 1, 1511, 1511, 1511, 1511, 1511, NULL, NULL, 1511, 1511, 1511, '[{\"name\":\"special_guide_plate\",\"value\":1511},{\"name\":\"tension_spring\",\"value\":1511},{\"name\":\"alignment_pin\",\"value\":1511},{\"name\":\"pressure_pad\",\"value\":1511}]'),
(240, 200, 1, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(241, 201, 7, 1, 78, 78, 78, 78, 78, NULL, NULL, 78, 78, 78, '[{\"name\":\"special_guide_plate\",\"value\":78},{\"name\":\"tension_spring\",\"value\":78},{\"name\":\"alignment_pin\",\"value\":78},{\"name\":\"pressure_pad\",\"value\":78}]'),
(242, 202, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(243, 203, 4, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(244, 204, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(245, 205, 6, 1, 600, 600, 600, 600, 600, NULL, NULL, 600, 600, 600, '[{\"name\":\"special_guide_plate\",\"value\":600},{\"name\":\"tension_spring\",\"value\":600},{\"name\":\"alignment_pin\",\"value\":600},{\"name\":\"pressure_pad\",\"value\":600}]'),
(246, 206, 7, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(247, 207, 8, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(248, 208, 20, 1, 20, 20, 20, 20, 20, 344, 344, 20, 20, 20, '[{\"name\":\"special_guide_plate\",\"value\":20},{\"name\":\"tension_spring\",\"value\":20},{\"name\":\"alignment_pin\",\"value\":20},{\"name\":\"pressure_pad\",\"value\":20}]'),
(249, 209, 10, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(250, 210, 11, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(251, 211, 12, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(252, 212, 13, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(253, 212, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(254, 213, 14, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(255, 213, 4, 1, 1, 1, 1, 1, 1, NULL, NULL, 1, 1, 1, '[{\"name\":\"special_guide_plate\",\"value\":1},{\"name\":\"tension_spring\",\"value\":1},{\"name\":\"alignment_pin\",\"value\":1},{\"name\":\"pressure_pad\",\"value\":1}]'),
(256, 214, 15, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(257, 214, 5, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(258, 215, 16, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(259, 215, 6, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(260, 216, 17, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(261, 216, 7, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(262, 217, 18, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(263, 217, 8, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(264, 218, 19, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(265, 218, 9, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(266, 219, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(267, 219, 10, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(268, 220, 1, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(269, 220, 11, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(270, 221, 2, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(271, 221, 12, 1, 344, 344, 344, 344, 344, NULL, NULL, 344, 344, 344, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(272, 222, 3, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(273, 222, 13, 1, 344, 344, 344, 344, 344, 344, 344, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":344},{\"name\":\"tension_spring\",\"value\":344},{\"name\":\"alignment_pin\",\"value\":344},{\"name\":\"pressure_pad\",\"value\":344}]'),
(276, 225, 1, 1, 2, 2, 2, 2, 2, 2, 2, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":2},{\"name\":\"tension_spring\",\"value\":2},{\"name\":\"alignment_pin\",\"value\":2},{\"name\":\"pressure_pad\",\"value\":2}]'),
(277, 225, 2, 1, 3, 3, 3, 3, 3, NULL, NULL, 3, 3, 3, '[{\"name\":\"special_guide_plate\",\"value\":3},{\"name\":\"tension_spring\",\"value\":3},{\"name\":\"alignment_pin\",\"value\":3},{\"name\":\"pressure_pad\",\"value\":3}]'),
(288, 203, 5, 1, 2, 2, 2, 2, 2, 2, 2, NULL, NULL, NULL, '[{\"name\":\"special_guide_plate\",\"value\":2},{\"name\":\"tension_spring\",\"value\":2},{\"name\":\"alignment_pin\",\"value\":2},{\"name\":\"pressure_pad\",\"value\":2}]'),
(289, 204, 2, 1, 2, 2, 2, 2, 2, NULL, NULL, 2, 2, 2, '[{\"name\":\"special_guide_plate\",\"value\":2},{\"name\":\"tension_spring\",\"value\":2},{\"name\":\"alignment_pin\",\"value\":2},{\"name\":\"pressure_pad\",\"value\":2}]'),
(290, 208, 19, 1, 19, 19, 19, 19, 19, NULL, NULL, 19, 19, 19, '[{\"name\":\"special_guide_plate\",\"value\":19},{\"name\":\"tension_spring\",\"value\":19},{\"name\":\"alignment_pin\",\"value\":19},{\"name\":\"pressure_pad\",\"value\":19}]'),
(291, 201, 8, 1, 8, 8, 8, 8, 8, NULL, NULL, 8, 8, 8, '[{\"name\":\"special_guide_plate\",\"value\":8},{\"name\":\"tension_spring\",\"value\":8},{\"name\":\"alignment_pin\",\"value\":8},{\"name\":\"pressure_pad\",\"value\":8}]'),
(292, 202, 2, 1, 1500, 1500, 1500, 1500, 1500, NULL, NULL, 1500, 1500, 1500, '[{\"name\":\"special_guide_plate\",\"value\":1500},{\"name\":\"tension_spring\",\"value\":1500},{\"name\":\"alignment_pin\",\"value\":1500},{\"name\":\"pressure_pad\",\"value\":1500}]');

-- --------------------------------------------------------

--
-- Table structure for table `applicator_reset`
--

CREATE TABLE `applicator_reset` (
  `reset_id` int(11) NOT NULL,
  `applicator_id` int(11) NOT NULL,
  `reset_by` int(11) NOT NULL,
  `part_reset` varchar(50) NOT NULL,
  `reset_time` datetime DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `applicator_reset`
--

INSERT INTO `applicator_reset` (`reset_id`, `applicator_id`, `reset_by`, `part_reset`, `reset_time`, `remarks`) VALUES
(1, 1, 2, 'wire_crimper', '2025-07-29 10:28:34', 'Crimper jaw replacement'),
(2, 2, 3, 'insulation_anvil', '2025-07-29 10:28:34', 'Anvil surface refinishing'),
(3, 3, 2, 'special_guide_plate', '2025-07-29 10:28:34', 'Custom part alignment reset'),
(4, 4, 3, 'wire_anvil', '2025-07-29 10:28:34', 'Routine maintenance reset'),
(5, 5, 2, 'tension_spring', '2025-07-29 10:28:34', 'Custom part tension adjustment'),
(6, 6, 3, 'slide_cutter', '2025-07-29 10:28:34', 'Cutter blade replacement'),
(7, 7, 2, 'alignment_pin', '2025-07-29 10:28:34', 'Custom part repositioning'),
(8, 8, 3, 'insulation_crimper', '2025-07-29 10:28:34', 'Crimper adjustment'),
(9, 9, 2, 'pressure_pad', '2025-07-29 10:28:34', 'Custom part pressure reset'),
(10, 10, 3, 'cutter_holder', '2025-07-29 10:28:34', 'Holder mechanism reset'),
(11, 11, 2, 'shear_blade', '2025-07-29 10:28:34', 'Blade sharpening reset'),
(12, 12, 3, 'special_guide_plate', '2025-07-29 10:28:34', 'Custom part cleaning reset'),
(13, 13, 2, 'wire_crimper', '2025-07-29 10:28:34', 'Performance optimization'),
(14, 14, 3, 'tension_spring', '2025-07-29 10:28:34', 'Custom part spring replacement'),
(15, 15, 2, 'cutter_a', '2025-07-29 10:28:34', 'Precision adjustment reset');

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

--
-- Dumping data for table `custom_part_definitions`
--

INSERT INTO `custom_part_definitions` (`part_id`, `equipment_type`, `part_name`, `is_active`, `created_by`, `created_at`) VALUES
(1, 'APPLICATOR', 'special_guide_plate', 1, 1, '2025-07-29 10:28:33'),
(2, 'APPLICATOR', 'tension_spring', 1, 1, '2025-07-29 10:28:33'),
(3, 'APPLICATOR', 'alignment_pin', 1, 1, '2025-07-29 10:28:33'),
(4, 'APPLICATOR', 'pressure_pad', 1, 1, '2025-07-29 10:28:33'),
(5, 'MACHINE', 'feed_roller', 1, 1, '2025-07-29 10:28:33'),
(6, 'MACHINE', 'guide_tube', 1, 1, '2025-07-29 10:28:33'),
(7, 'MACHINE', 'sensor_bracket', 1, 1, '2025-07-29 10:28:33'),
(8, 'MACHINE', 'cooling_fan', 1, 1, '2025-07-29 10:28:33');

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

--
-- Dumping data for table `machines`
--

INSERT INTO `machines` (`machine_id`, `control_no`, `description`, `model`, `maker`, `serial_no`, `invoice_no`, `last_encoded`, `is_active`) VALUES
(1, 'CTRL001', 'AUTOMATIC', 'WPM-2000', 'KOMAX', 'MSN002', 'MINV001', '2024-01-15 08:30:00', 1),
(2, 'CTRL002', 'SEMI-AUTOMATIC', 'WPM-3000', 'SCHLEUNIGER', 'MSN002', 'MINV002', '2024-01-16 09:15:00', 1),
(3, 'CTRL003', 'AUTOMATIC', 'CAM-1500', 'KOMAKS', 'MSN003', 'MINV003', '2024-01-17 10:45:00', 1),
(4, 'CTRL004', 'SEMI-AUTOMATIC', 'TCM-500', 'SCHLEUNIGER', 'MSN004', 'MINV004', '2024-01-18 11:20:00', 1),
(5, 'CTRL005', 'AUTOMATIC', 'WSM-800', 'KOMAX', 'MSN005', 'MINV005', '2024-01-19 13:30:00', 1),
(6, 'CTRL006', 'SEMI-AUTOMATIC', 'HSC-1200', 'SCHLEUNIGER', 'MSN006', 'MINV006', '2024-01-20 14:15:00', 1),
(7, 'CTRL007', 'AUTOMATIC', 'MCP-2500', 'KOMAX', 'MSN007', 'MINV007', '2024-01-21 15:45:00', 1),
(8, 'CTRL008', 'SEMI-AUTOMATIC', 'PC-600', 'SCHLEUNIGER', 'MSN008', 'MINV008', '2024-01-22 16:20:00', 1),
(9, 'CTRL009', 'AUTOMATIC', 'AS-900', 'KOMAX', 'MSN009', 'MINV009', '2024-01-23 08:00:00', 1),
(10, 'CTRL010', 'SEMI-AUTOMATIC', 'CTU-300', 'SCHLEUNIGER', 'MSN010', 'MINV010', '2024-01-24 09:30:00', 1),
(11, 'CTRL011', 'AUTOMATIC', 'WHM-1800', 'KOMAX', 'MSN011', 'MINV011', '2024-01-25 10:15:00', 1),
(12, 'CTRL012', 'SEMI-AUTOMATIC', 'TIU-750', 'SCHLEUNIGER', 'MSN012', 'MINV012', '2024-01-26 11:45:00', 1),
(13, 'CTRL013', 'AUTOMATIC', 'CPC-2200', 'KOMAX', 'MSN013', 'MINV013', '2024-01-27 12:30:00', 1),
(14, 'CTRL014', 'SEMI-AUTOMATIC', 'WPS-1100', 'SCHLEUNIGER', 'MSN014', 'MINV014', '2024-01-28 13:15:00', 1),
(15, 'CTRL015', 'AUTOMATIC', 'MWP-1600', 'KOMAX', 'MSN015', 'MINV015', '2024-01-29 14:45:00', 1),
(16, 'CTRL016', 'SEMI-AUTOMATIC', 'CC-400', 'SCHLEUNIGER', 'MSN016', 'MINV016', '2024-01-30 15:30:00', 1),
(17, 'CTRL017', 'AUTOMATIC', 'IWC-850', 'KOMAX', 'MSN017', 'MINV017', '2024-01-31 16:15:00', 1),
(18, 'CTRL018', 'SEMI-AUTOMATIC', 'HDS-950', 'SCHLEUNIGER', 'MSN018', 'MINV018', '2024-02-01 08:45:00', 1),
(19, 'CTRL019', 'AUTOMATIC', 'CAL-2800', 'KOMAX', 'MSN019', 'MINV019', '2024-02-02 09:30:00', 1),
(20, 'CTRL020', 'SEMI-AUTOMATIC', 'WPU-1300', 'SCHLEUNIGER', 'MSN020', 'MINV020', '2024-02-03 10:15:00', 1),
(21, 'FASASF', 'AUTOMATIC', 'ASDFASDF', 'ASDFSADF', 'ASDFSADF', 'ASDFSDAF', NULL, 0),
(33, 'EYEBOLLORD', 'AUTOMATIC', 'ASDFASDF', 'ASDFSADF', 'ASDFSADF', 'ASDFSDAF', NULL, 1),
(34, 'DIGITIDOG', 'SEMI-AUTOMATIC', 'ASDFASDF', 'ASDFSADF', 'ASDFSADF', 'ASDFSDAF', NULL, 0),
(35, '11111', 'AUTOMATIC', 'ASDFASDF', 'ASDFSADF', 'ASDFSADF', 'ASDFSDAF', NULL, 0);

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

--
-- Dumping data for table `machine_outputs`
--

INSERT INTO `machine_outputs` (`machine_output_id`, `record_id`, `machine_id`, `is_active`, `total_machine_output`, `cut_blade`, `strip_blade_a`, `strip_blade_b`, `custom_parts`) VALUES
(1, 1, 1, 1, 3000, 500, 450, 400, '{\"feed_roller\": 75, \"guide_tube\": 60}'),
(2, 2, 2, 1, 2800, 480, 430, 380, '{\"sensor_bracket\": 55, \"cooling_fan\": 40}'),
(3, 3, 3, 1, 3200, 520, 470, 420, '{\"feed_roller\": 80, \"guide_tube\": 65}'),
(4, 4, 4, 1, 3100, 510, 460, 410, '{\"sensor_bracket\": 58, \"cooling_fan\": 42}'),
(5, 5, 5, 1, 2900, 490, 440, 390, '{\"feed_roller\": 77, \"guide_tube\": 62}'),
(6, 6, 6, 1, 2700, 470, 420, 370, '{\"sensor_bracket\": 52, \"cooling_fan\": 38}'),
(7, 7, 7, 1, 3400, 540, 490, 440, '{\"feed_roller\": 85, \"guide_tube\": 70}'),
(8, 8, 8, 1, 3300, 530, 480, 430, '{\"sensor_bracket\": 62, \"cooling_fan\": 45}'),
(9, 9, 9, 1, 3000, 500, 450, 400, '{\"feed_roller\": 75, \"guide_tube\": 60}'),
(10, 10, 10, 1, 2800, 480, 430, 380, '{\"sensor_bracket\": 55, \"cooling_fan\": 40}'),
(11, 11, 11, 1, 3100, 510, 460, 410, '{\"feed_roller\": 78, \"guide_tube\": 63}'),
(12, 12, 12, 1, 3200, 520, 470, 420, '{\"sensor_bracket\": 60, \"cooling_fan\": 43}'),
(13, 13, 13, 1, 2900, 490, 440, 390, '{\"feed_roller\": 76, \"guide_tube\": 61}'),
(14, 14, 14, 1, 3300, 530, 480, 430, '{\"sensor_bracket\": 61, \"cooling_fan\": 44}'),
(15, 15, 15, 1, 3000, 500, 450, 400, '{\"feed_roller\": 75, \"guide_tube\": 60}'),
(16, 16, 16, 1, 2800, 480, 430, 380, '{\"sensor_bracket\": 54, \"cooling_fan\": 39}'),
(17, 17, 17, 1, 3400, 540, 490, 440, '{\"feed_roller\": 86, \"guide_tube\": 71}'),
(18, 18, 18, 1, 3100, 510, 460, 410, '{\"sensor_bracket\": 59, \"cooling_fan\": 43}'),
(19, 19, 19, 1, 3200, 520, 470, 420, '{\"feed_roller\": 81, \"guide_tube\": 66}'),
(20, 20, 20, 1, 2900, 490, 440, 390, '{\"sensor_bracket\": 56, \"cooling_fan\": 41}'),
(21, 21, 1, 1, 3000, 500, 450, 400, '{\"feed_roller\": 75, \"guide_tube\": 60}'),
(22, 22, 2, 1, 2800, 480, 430, 380, '{\"sensor_bracket\": 55, \"cooling_fan\": 40}'),
(23, 23, 3, 1, 3200, 520, 470, 420, '{\"feed_roller\": 80, \"guide_tube\": 65}'),
(24, 24, 4, 1, 3100, 510, 460, 410, '{\"sensor_bracket\": 58, \"cooling_fan\": 42}'),
(25, 25, 5, 1, 2900, 490, 440, 390, '{\"feed_roller\": 77, \"guide_tube\": 62}'),
(26, 28, 1, 1, 5, 5, 5, 5, '[{\"name\":\"feed_roller\",\"value\":5},{\"name\":\"guide_tube\",\"value\":5},{\"name\":\"sensor_bracket\",\"value\":5},{\"name\":\"cooling_fan\",\"value\":5}]'),
(27, 29, 1, 1, 5, 5, 5, 5, '[{\"name\":\"feed_roller\",\"value\":5},{\"name\":\"guide_tube\",\"value\":5},{\"name\":\"sensor_bracket\",\"value\":5},{\"name\":\"cooling_fan\",\"value\":5}]'),
(28, 30, 1, 1, 6, 6, 6, 6, '[{\"name\":\"feed_roller\",\"value\":6},{\"name\":\"guide_tube\",\"value\":6},{\"name\":\"sensor_bracket\",\"value\":6},{\"name\":\"cooling_fan\",\"value\":6}]'),
(29, 31, 1, 1, 7, 7, 7, 7, '[{\"name\":\"feed_roller\",\"value\":7},{\"name\":\"guide_tube\",\"value\":7},{\"name\":\"sensor_bracket\",\"value\":7},{\"name\":\"cooling_fan\",\"value\":7}]'),
(30, 32, 1, 1, 5, 5, 5, 5, '[{\"name\":\"feed_roller\",\"value\":5},{\"name\":\"guide_tube\",\"value\":5},{\"name\":\"sensor_bracket\",\"value\":5},{\"name\":\"cooling_fan\",\"value\":5}]'),
(31, 33, 1, 1, 5, 5, 5, 5, '[{\"name\":\"feed_roller\",\"value\":5},{\"name\":\"guide_tube\",\"value\":5},{\"name\":\"sensor_bracket\",\"value\":5},{\"name\":\"cooling_fan\",\"value\":5}]'),
(32, 34, 1, 1, 5, 5, 5, 5, '[{\"name\":\"feed_roller\",\"value\":5},{\"name\":\"guide_tube\",\"value\":5},{\"name\":\"sensor_bracket\",\"value\":5},{\"name\":\"cooling_fan\",\"value\":5}]'),
(33, 35, 1, 1, 5, 5, 5, 5, '[{\"name\":\"feed_roller\",\"value\":5},{\"name\":\"guide_tube\",\"value\":5},{\"name\":\"sensor_bracket\",\"value\":5},{\"name\":\"cooling_fan\",\"value\":5}]'),
(34, 36, 1, 1, 59, 59, 59, 59, '[{\"name\":\"feed_roller\",\"value\":59},{\"name\":\"guide_tube\",\"value\":59},{\"name\":\"sensor_bracket\",\"value\":59},{\"name\":\"cooling_fan\",\"value\":59}]'),
(35, 37, 1, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(36, 38, 1, 1, 5, 5, 5, 5, '[{\"name\":\"feed_roller\",\"value\":5},{\"name\":\"guide_tube\",\"value\":5},{\"name\":\"sensor_bracket\",\"value\":5},{\"name\":\"cooling_fan\",\"value\":5}]'),
(37, 44, 1, 1, 5, 5, 5, 5, '[{\"name\":\"feed_roller\",\"value\":5},{\"name\":\"guide_tube\",\"value\":5},{\"name\":\"sensor_bracket\",\"value\":5},{\"name\":\"cooling_fan\",\"value\":5}]'),
(38, 45, 1, 1, 11, 11, 11, 11, '[{\"name\":\"feed_roller\",\"value\":11},{\"name\":\"guide_tube\",\"value\":11},{\"name\":\"sensor_bracket\",\"value\":11},{\"name\":\"cooling_fan\",\"value\":11}]'),
(39, 50, 1, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(43, 67, 1, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(125, 149, 1, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(126, 150, 2, 1, 21, 21, 21, 21, '[{\"name\":\"feed_roller\",\"value\":21},{\"name\":\"guide_tube\",\"value\":21},{\"name\":\"sensor_bracket\",\"value\":21},{\"name\":\"cooling_fan\",\"value\":21}]'),
(127, 151, 3, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(128, 152, 4, 1, 3100, 3100, 3100, 3100, '[{\"name\":\"feed_roller\",\"value\":3100},{\"name\":\"guide_tube\",\"value\":3100},{\"name\":\"sensor_bracket\",\"value\":3100},{\"name\":\"cooling_fan\",\"value\":3100}]'),
(129, 153, 5, 1, 300, 300, 300, 300, '[{\"name\":\"feed_roller\",\"value\":300},{\"name\":\"guide_tube\",\"value\":300},{\"name\":\"sensor_bracket\",\"value\":300},{\"name\":\"cooling_fan\",\"value\":300}]'),
(130, 154, 6, 1, 600, 600, 600, 600, '[{\"name\":\"feed_roller\",\"value\":600},{\"name\":\"guide_tube\",\"value\":600},{\"name\":\"sensor_bracket\",\"value\":600},{\"name\":\"cooling_fan\",\"value\":600}]'),
(131, 155, 7, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(132, 156, 8, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(133, 157, 9, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(134, 158, 10, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(135, 159, 11, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(136, 160, 12, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(137, 161, 13, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(138, 162, 14, 1, 3300, 3300, 3300, 3300, '[{\"name\":\"feed_roller\",\"value\":3300},{\"name\":\"guide_tube\",\"value\":3300},{\"name\":\"sensor_bracket\",\"value\":3300},{\"name\":\"cooling_fan\",\"value\":3300}]'),
(139, 163, 15, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(140, 164, 16, 1, 2800, 2800, 2800, 2800, '[{\"name\":\"feed_roller\",\"value\":2800},{\"name\":\"guide_tube\",\"value\":2800},{\"name\":\"sensor_bracket\",\"value\":2800},{\"name\":\"cooling_fan\",\"value\":2800}]'),
(141, 165, 1, 1, 3000, 3000, 3000, 3000, '[{\"name\":\"feed_roller\",\"value\":3000},{\"name\":\"guide_tube\",\"value\":3000},{\"name\":\"sensor_bracket\",\"value\":3000},{\"name\":\"cooling_fan\",\"value\":3000}]'),
(142, 166, 2, 1, 2800, 2800, 2800, 2800, '[{\"name\":\"feed_roller\",\"value\":2800},{\"name\":\"guide_tube\",\"value\":2800},{\"name\":\"sensor_bracket\",\"value\":2800},{\"name\":\"cooling_fan\",\"value\":2800}]'),
(143, 167, 3, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(144, 168, 4, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(145, 169, 5, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(146, 170, 6, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(147, 171, 7, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(148, 172, 1, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(149, 173, 2, 1, 21, 21, 21, 21, '[{\"name\":\"feed_roller\",\"value\":21},{\"name\":\"guide_tube\",\"value\":21},{\"name\":\"sensor_bracket\",\"value\":21},{\"name\":\"cooling_fan\",\"value\":21}]'),
(150, 174, 3, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(151, 175, 4, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(152, 176, 5, 1, 300, 300, 300, 300, '[{\"name\":\"feed_roller\",\"value\":300},{\"name\":\"guide_tube\",\"value\":300},{\"name\":\"sensor_bracket\",\"value\":300},{\"name\":\"cooling_fan\",\"value\":300}]'),
(153, 177, 6, 1, 600, 600, 600, 600, '[{\"name\":\"feed_roller\",\"value\":600},{\"name\":\"guide_tube\",\"value\":600},{\"name\":\"sensor_bracket\",\"value\":600},{\"name\":\"cooling_fan\",\"value\":600}]'),
(154, 178, 7, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(155, 179, 8, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(156, 180, 9, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(157, 181, 10, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(158, 182, 11, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(159, 183, 12, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(160, 184, 13, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(161, 185, 14, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(162, 186, 15, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(163, 187, 16, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(164, 188, 1, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(165, 189, 2, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(166, 190, 3, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(167, 191, 4, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(168, 192, 5, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(169, 193, 6, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(170, 194, 7, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(171, 195, 1, 1, 1500, 1500, 1500, 1500, '[{\"name\":\"feed_roller\",\"value\":1500},{\"name\":\"guide_tube\",\"value\":1500},{\"name\":\"sensor_bracket\",\"value\":1500},{\"name\":\"cooling_fan\",\"value\":1500}]'),
(172, 196, 1, 1, 12, 12, 12, 12, '[{\"name\":\"feed_roller\",\"value\":12},{\"name\":\"guide_tube\",\"value\":12},{\"name\":\"sensor_bracket\",\"value\":12},{\"name\":\"cooling_fan\",\"value\":12}]'),
(173, 197, 1, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(174, 198, 1, 1, 3000, 3000, 3000, 3000, '[{\"name\":\"feed_roller\",\"value\":3000},{\"name\":\"guide_tube\",\"value\":3000},{\"name\":\"sensor_bracket\",\"value\":3000},{\"name\":\"cooling_fan\",\"value\":3000}]'),
(175, 199, 1, 1, 3000, 3000, 3000, 3000, '[{\"name\":\"feed_roller\",\"value\":3000},{\"name\":\"guide_tube\",\"value\":3000},{\"name\":\"sensor_bracket\",\"value\":3000},{\"name\":\"cooling_fan\",\"value\":3000}]'),
(176, 200, 1, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(177, 201, 9, 1, 9, 9, 9, 9, '[{\"name\":\"feed_roller\",\"value\":9},{\"name\":\"guide_tube\",\"value\":9},{\"name\":\"sensor_bracket\",\"value\":9},{\"name\":\"cooling_fan\",\"value\":9}]'),
(178, 202, 3, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(179, 203, 6, 1, 3, 3, 3, 3, '[{\"name\":\"feed_roller\",\"value\":3},{\"name\":\"guide_tube\",\"value\":3},{\"name\":\"sensor_bracket\",\"value\":3},{\"name\":\"cooling_fan\",\"value\":3}]'),
(180, 204, 1, 1, 3, 3, 3, 3, '[{\"name\":\"feed_roller\",\"value\":3},{\"name\":\"guide_tube\",\"value\":3},{\"name\":\"sensor_bracket\",\"value\":3},{\"name\":\"cooling_fan\",\"value\":3}]'),
(181, 205, 6, 1, 2700, 2700, 2700, 2700, '[{\"name\":\"feed_roller\",\"value\":2700},{\"name\":\"guide_tube\",\"value\":2700},{\"name\":\"sensor_bracket\",\"value\":2700},{\"name\":\"cooling_fan\",\"value\":2700}]'),
(182, 206, 7, 1, 3400, 3400, 3400, 3400, '[{\"name\":\"feed_roller\",\"value\":3400},{\"name\":\"guide_tube\",\"value\":3400},{\"name\":\"sensor_bracket\",\"value\":3400},{\"name\":\"cooling_fan\",\"value\":3400}]'),
(183, 207, 8, 1, 3300, 3300, 3300, 3300, '[{\"name\":\"feed_roller\",\"value\":3300},{\"name\":\"guide_tube\",\"value\":3300},{\"name\":\"sensor_bracket\",\"value\":3300},{\"name\":\"cooling_fan\",\"value\":3300}]'),
(184, 208, 9, 1, 18, 18, 18, 18, '[{\"name\":\"feed_roller\",\"value\":18},{\"name\":\"guide_tube\",\"value\":18},{\"name\":\"sensor_bracket\",\"value\":18},{\"name\":\"cooling_fan\",\"value\":18}]'),
(185, 209, 10, 1, 2800, 2800, 2800, 2800, '[{\"name\":\"feed_roller\",\"value\":2800},{\"name\":\"guide_tube\",\"value\":2800},{\"name\":\"sensor_bracket\",\"value\":2800},{\"name\":\"cooling_fan\",\"value\":2800}]'),
(186, 210, 11, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(187, 211, 12, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(188, 212, 13, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(189, 213, 14, 1, 1, 1, 1, 1, '[{\"name\":\"feed_roller\",\"value\":1},{\"name\":\"guide_tube\",\"value\":1},{\"name\":\"sensor_bracket\",\"value\":1},{\"name\":\"cooling_fan\",\"value\":1}]'),
(190, 214, 15, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(191, 215, 16, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(192, 216, 1, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(193, 217, 2, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(194, 218, 3, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(195, 219, 4, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(196, 220, 5, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(197, 221, 6, 1, 344, 344, 344, 344, '[{\"name\":\"feed_roller\",\"value\":344},{\"name\":\"guide_tube\",\"value\":344},{\"name\":\"sensor_bracket\",\"value\":344},{\"name\":\"cooling_fan\",\"value\":344}]'),
(198, 222, 7, 1, 3400, 3400, 3400, 3400, '[{\"name\":\"feed_roller\",\"value\":3400},{\"name\":\"guide_tube\",\"value\":3400},{\"name\":\"sensor_bracket\",\"value\":3400},{\"name\":\"cooling_fan\",\"value\":3400}]'),
(201, 225, 1, 1, 4, 4, 4, 4, '[{\"name\":\"feed_roller\",\"value\":4},{\"name\":\"guide_tube\",\"value\":4},{\"name\":\"sensor_bracket\",\"value\":4},{\"name\":\"cooling_fan\",\"value\":4}]');

-- --------------------------------------------------------

--
-- Table structure for table `machine_reset`
--

CREATE TABLE `machine_reset` (
  `reset_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `reset_by` int(11) NOT NULL,
  `part_reset` varchar(50) NOT NULL,
  `reset_time` datetime DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `machine_reset`
--

INSERT INTO `machine_reset` (`reset_id`, `machine_id`, `reset_by`, `part_reset`, `reset_time`, `remarks`) VALUES
(1, 1, 2, 'cut_blade', '2025-07-29 10:28:34', 'Blade dulled after heavy use'),
(2, 2, 3, 'strip_blade_a', '2025-07-29 10:28:34', 'Routine maintenance replacement'),
(3, 3, 2, 'feed_roller', '2025-07-29 10:28:34', 'Custom part showing wear'),
(4, 4, 3, 'strip_blade_b', '2025-07-29 10:28:34', 'Scheduled replacement'),
(5, 5, 2, 'guide_tube', '2025-07-29 10:28:34', 'Custom part alignment issue'),
(6, 6, 3, 'cut_blade', '2025-07-29 10:28:34', 'Emergency replacement due to damage'),
(7, 7, 2, 'sensor_bracket', '2025-07-29 10:28:34', 'Custom part calibration reset'),
(8, 8, 3, 'strip_blade_a', '2025-07-29 10:28:34', 'Quality control reset'),
(9, 9, 2, 'cooling_fan', '2025-07-29 10:28:34', 'Custom part maintenance'),
(10, 10, 3, 'cut_blade', '2025-07-29 10:28:34', 'Preventive maintenance'),
(11, 11, 2, 'strip_blade_b', '2025-07-29 10:28:34', 'Performance optimization'),
(12, 12, 3, 'feed_roller', '2025-07-29 10:28:34', 'Custom part reset after cleaning'),
(13, 13, 2, 'guide_tube', '2025-07-29 10:28:34', 'Alignment adjustment reset'),
(14, 14, 3, 'strip_blade_a', '2025-07-29 10:28:34', 'Post-repair reset'),
(15, 15, 2, 'sensor_bracket', '2025-07-29 10:28:34', 'Custom part recalibration');

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
  `custom_parts_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_parts_output`)),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `monitor_applicator`
--

INSERT INTO `monitor_applicator` (`monitor_id`, `applicator_id`, `total_output`, `wire_crimper_output`, `wire_anvil_output`, `insulation_crimper_output`, `insulation_anvil_output`, `slide_cutter_output`, `cutter_holder_output`, `shear_blade_output`, `cutter_a_output`, `cutter_b_output`, `custom_parts_output`, `last_updated`) VALUES
(1, 1, 13034, 6784, 6634, 6434, 6409, 6284, 6234, 650, 600, 550, '{\"special_guide_plate\":5759,\"tension_spring\":5709,\"alignment_pin\":5534,\"pressure_pad\":5534}', '2025-08-08 13:30:02'),
(2, 2, 11221, 5421, 5271, 5071, 825, 725, 675, 625, 4796, 4746, '{\"alignment_pin\":4346,\"pressure_pad\":4421,\"special_guide_plate\":4221,\"tension_spring\":4221}', '2025-08-08 13:30:02'),
(3, 3, 12128, 5428, 5278, 5078, 5053, 4928, 4878, 700, 650, 600, '{\"special_guide_plate\":4378,\"tension_spring\":4278,\"alignment_pin\":4128,\"pressure_pad\":4128}', '2025-08-08 12:40:36'),
(4, 4, 7756, 1281, 1131, 931, 900, 775, 725, 675, 631, 581, '{\"alignment_pin\":146,\"pressure_pad\":216,\"special_guide_plate\":6,\"tension_spring\":6}', '2025-08-08 12:40:35'),
(5, 5, 9182, 3157, 3007, 2807, 2782, 2682, 2632, 650, 600, 550, '{\"special_guide_plate\":2172,\"tension_spring\":2092,\"alignment_pin\":1932,\"pressure_pad\":1932}', '2025-08-08 12:40:35'),
(6, 6, 9582, 4007, 3857, 3657, 800, 700, 650, 600, 3382, 3332, '{\"alignment_pin\":2942,\"pressure_pad\":3022,\"special_guide_plate\":2832,\"tension_spring\":2832}', '2025-08-08 12:40:35'),
(7, 7, 9535, 2385, 2235, 2035, 975, 850, 800, 750, 1735, 1685, '{\"special_guide_plate\":1295,\"tension_spring\":1175,\"alignment_pin\":1035,\"pressure_pad\":1035}', '2025-08-08 12:40:35'),
(8, 8, 10314, 3389, 3239, 3039, 950, 825, 775, 725, 2739, 2689, '{\"alignment_pin\":2214,\"pressure_pad\":2289,\"special_guide_plate\":2064,\"tension_spring\":2064}', '2025-08-08 12:40:35'),
(9, 9, 9564, 3314, 3164, 2964, 2939, 2814, 2764, 650, 600, 550, '{\"special_guide_plate\":2294,\"tension_spring\":2234,\"alignment_pin\":2064,\"pressure_pad\":2064}', '2025-08-08 12:40:35'),
(10, 10, 9064, 3264, 3114, 2914, 825, 725, 675, 625, 2639, 2589, '{\"alignment_pin\":2194,\"pressure_pad\":2269,\"special_guide_plate\":2064,\"tension_spring\":2064}', '2025-08-08 12:40:35'),
(11, 11, 8785, 2310, 2160, 1960, 900, 775, 725, 675, 1660, 1610, '{\"special_guide_plate\":1280,\"tension_spring\":1190,\"alignment_pin\":1035,\"pressure_pad\":1035}', '2025-08-08 12:40:35'),
(12, 12, 10064, 3364, 3214, 3014, 925, 800, 750, 700, 2714, 2664, '{\"alignment_pin\":2209,\"pressure_pad\":2279,\"special_guide_plate\":2064,\"tension_spring\":2064}', '2025-08-08 12:40:36'),
(13, 13, 9314, 3289, 3139, 2939, 2914, 2814, 2764, 650, 600, 550, '{\"special_guide_plate\":2299,\"tension_spring\":2229,\"alignment_pin\":2064,\"pressure_pad\":2064}', '2025-08-08 12:40:36'),
(14, 14, 8253, 1328, 1178, 978, 950, 825, 775, 725, 678, 628, '{\"alignment_pin\":158,\"pressure_pad\":223,\"special_guide_plate\":3,\"tension_spring\":3}', '2025-08-08 12:40:35'),
(15, 15, 8532, 2282, 2132, 1932, 875, 750, 700, 650, 1632, 1582, '{\"special_guide_plate\":1257,\"tension_spring\":1207,\"alignment_pin\":1032,\"pressure_pad\":1032}', '2025-08-08 12:40:35'),
(16, 16, 8032, 2232, 2082, 1882, 825, 725, 675, 625, 1607, 1557, '{\"alignment_pin\":1157,\"pressure_pad\":1232,\"special_guide_plate\":1032,\"tension_spring\":1032}', '2025-08-08 12:40:35'),
(17, 17, 9532, 2382, 2232, 2032, 2007, 1882, 1832, 750, 700, 650, '{\"special_guide_plate\":1297,\"tension_spring\":1167,\"alignment_pin\":1032,\"pressure_pad\":1032}', '2025-08-08 12:40:35'),
(18, 18, 8782, 2307, 2157, 1957, 900, 775, 725, 675, 1657, 1607, '{\"alignment_pin\":1172,\"pressure_pad\":1242,\"special_guide_plate\":1032,\"tension_spring\":1032}', '2025-08-08 12:40:35'),
(19, 19, 9032, 2332, 2182, 1982, 925, 800, 750, 700, 1682, 1632, '{\"special_guide_plate\":1287,\"tension_spring\":1177,\"alignment_pin\":1032,\"pressure_pad\":1032}', '2025-08-08 12:40:35'),
(20, 20, 7250, 1225, 1075, 875, 850, 750, 700, 650, 600, 550, '{\"alignment_pin\": 120, \"pressure_pad\": 195}', '2025-07-29 10:28:34');

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
  `custom_parts_output` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_parts_output`)),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `monitor_machine`
--

INSERT INTO `monitor_machine` (`monitor_id`, `machine_id`, `total_machine_output`, `cut_blade_output`, `strip_blade_a_output`, `strip_blade_b_output`, `custom_parts_output`, `last_updated`) VALUES
(1, 1, 20534, 8034, 7784, 7534, '{\"feed_roller\":5909,\"guide_tube\":5834,\"sensor_bracket\":5534,\"cooling_fan\":5534}', '2025-08-08 13:30:02'),
(2, 2, 15095, 3495, 3245, 2995, '{\"sensor_bracket\":1370,\"cooling_fan\":1295,\"feed_roller\":1095,\"guide_tube\":1095}', '2025-08-08 12:40:35'),
(3, 3, 18064, 4664, 4414, 4164, '{\"feed_roller\":2464,\"guide_tube\":2389,\"sensor_bracket\":2064,\"cooling_fan\":2064}', '2025-08-08 12:40:35'),
(4, 4, 16535, 3585, 3335, 3085, '{\"sensor_bracket\":1325,\"cooling_fan\":1245,\"feed_roller\":1035,\"guide_tube\":1035}', '2025-08-08 12:40:35'),
(5, 5, 16432, 4382, 4132, 3882, '{\"feed_roller\":2317,\"guide_tube\":2242,\"sensor_bracket\":1932,\"cooling_fan\":1932}', '2025-08-08 12:40:35'),
(6, 6, 16332, 5182, 4932, 4682, '{\"sensor_bracket\":3092,\"cooling_fan\":3022,\"feed_roller\":2832,\"guide_tube\":2832}', '2025-08-08 12:40:36'),
(7, 7, 18035, 3735, 3485, 3235, '{\"feed_roller\":1460,\"guide_tube\":1385,\"sensor_bracket\":1035,\"cooling_fan\":1035}', '2025-08-08 12:40:36'),
(8, 8, 17532, 3682, 3432, 3182, '{\"sensor_bracket\":1342,\"cooling_fan\":1257,\"feed_roller\":1032,\"guide_tube\":1032}', '2025-08-08 12:40:35'),
(9, 9, 16032, 3532, 3282, 3032, '{\"feed_roller\":1407,\"guide_tube\":1332,\"sensor_bracket\":1032,\"cooling_fan\":1032}', '2025-08-08 12:40:35'),
(10, 10, 15032, 3432, 3182, 2932, '{\"sensor_bracket\":1307,\"cooling_fan\":1232,\"feed_roller\":1032,\"guide_tube\":1032}', '2025-08-08 12:40:35'),
(11, 11, 15503, 2553, 2303, 2053, '{\"feed_roller\":393,\"guide_tube\":318,\"sensor_bracket\":3,\"cooling_fan\":3}', '2025-08-08 12:40:35'),
(12, 12, 17032, 3632, 3382, 3132, '{\"sensor_bracket\":1332,\"cooling_fan\":1247,\"feed_roller\":1032,\"guide_tube\":1032}', '2025-08-08 12:40:35'),
(13, 13, 15532, 3482, 3232, 2982, '{\"feed_roller\":1412,\"guide_tube\":1337,\"sensor_bracket\":1032,\"cooling_fan\":1032}', '2025-08-08 12:40:35'),
(14, 14, 16503, 2653, 2403, 2153, '{\"sensor_bracket\":308,\"cooling_fan\":223,\"feed_roller\":3,\"guide_tube\":3}', '2025-08-08 12:40:35'),
(15, 15, 16032, 3532, 3282, 3032, '{\"feed_roller\":1407,\"guide_tube\":1332,\"sensor_bracket\":1032,\"cooling_fan\":1032}', '2025-08-08 12:40:35'),
(16, 16, 15032, 3432, 3182, 2932, '{\"sensor_bracket\":1302,\"cooling_fan\":1227,\"feed_roller\":1032,\"guide_tube\":1032}', '2025-08-08 12:40:35'),
(17, 17, 17000, 2700, 2450, 2200, '{\"feed_roller\": 430, \"guide_tube\": 355}', '2025-07-29 10:28:34'),
(18, 18, 15500, 2550, 2300, 2050, '{\"sensor_bracket\": 295, \"cooling_fan\": 215}', '2025-07-29 10:28:34'),
(19, 19, 16000, 2600, 2350, 2100, '{\"feed_roller\": 405, \"guide_tube\": 330}', '2025-07-29 10:28:34'),
(20, 20, 14500, 2450, 2200, 1950, '{\"sensor_bracket\": 280, \"cooling_fan\": 205}', '2025-07-29 10:28:34');

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

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`record_id`, `shift`, `machine_id`, `applicator1_id`, `applicator2_id`, `created_by`, `date_inspected`, `date_encoded`, `is_active`, `last_updated`) VALUES
(1, '1st', 1, 1, 2, 4, '2024-07-01', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(2, '2nd', 2, 3, 4, 5, '2024-07-01', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(3, 'NIGHT', 3, 5, 6, 6, '2024-07-01', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(4, '1st', 4, 7, 8, 7, '2024-07-02', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(5, '2nd', 5, 9, 10, 8, '2024-07-02', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(6, 'NIGHT', 6, 11, 12, 9, '2024-07-02', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(7, '1st', 7, 13, 14, 10, '2024-07-03', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(8, '2nd', 8, 15, 16, 11, '2024-07-03', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(9, 'NIGHT', 9, 17, 18, 12, '2024-07-03', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(10, '1st', 10, 19, 20, 13, '2024-07-04', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(11, '2nd', 11, 1, 3, 14, '2024-07-04', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(12, 'NIGHT', 12, 2, 4, 15, '2024-07-04', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(13, '1st', 13, 5, 7, 16, '2024-07-05', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(14, '2nd', 14, 6, 8, 17, '2024-07-05', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(15, 'NIGHT', 15, 9, 11, 18, '2024-07-05', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(16, '1st', 16, 10, 12, 4, '2024-07-06', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(17, '2nd', 17, 13, 15, 5, '2024-07-06', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(18, 'NIGHT', 18, 14, 16, 6, '2024-07-06', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(19, '1st', 19, 17, 19, 7, '2024-07-07', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(20, '2nd', 20, 18, 20, 8, '2024-07-07', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(21, 'NIGHT', 1, 1, NULL, 9, '2024-07-07', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(22, '1st', 2, 2, NULL, 10, '2024-07-08', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(23, '2nd', 3, 3, NULL, 11, '2024-07-08', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(24, 'NIGHT', 4, 4, NULL, 12, '2024-07-08', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(25, '1st', 5, 5, NULL, 13, '2024-07-09', '2025-07-29 10:28:33', 1, '2025-08-07 08:41:15'),
(26, '1st', 1, 1, 2, 21, '2025-07-30', '2025-07-30 09:12:40', 1, '2025-08-07 08:41:15'),
(27, '1st', 1, 1, 2, 21, '2025-07-30', '2025-07-30 09:52:57', 1, '2025-08-07 08:41:15'),
(28, '1st', 1, 1, 2, 21, '2025-07-30', '2025-07-30 09:55:44', 1, '2025-08-07 08:41:15'),
(29, '1st', 1, 1, 2, 21, '2025-07-30', '2025-07-30 13:34:47', 1, '2025-08-07 08:41:15'),
(30, '1st', 1, 1, 2, 21, '2025-07-30', '2025-07-30 13:36:40', 1, '2025-08-07 08:41:15'),
(31, '1st', 1, 1, 2, 21, '2025-07-30', '2025-07-30 13:38:30', 1, '2025-08-07 08:41:15'),
(32, '1st', 1, 1, 2, 21, '2025-07-30', '2025-07-30 13:47:37', 1, '2025-08-07 08:41:15'),
(33, '2nd', 1, 1, 2, 21, '2025-07-30', '2025-07-30 13:47:56', 1, '2025-08-07 08:41:15'),
(34, '1st', 1, 1, 2, 21, '2025-07-30', '2025-07-30 13:50:13', 1, '2025-08-07 08:41:15'),
(35, '2nd', 1, 1, 2, 21, '2025-07-30', '2025-07-30 13:50:26', 1, '2025-08-07 08:41:15'),
(36, 'NIGHT', 1, 1, 2, 21, '2025-07-30', '2025-07-30 13:51:19', 1, '2025-08-07 08:41:15'),
(37, '1st', 1, 1, 2, 21, '2025-07-30', '2025-07-30 13:52:10', 1, '2025-08-07 08:41:15'),
(38, '1st', 1, 1, 2, 24, '2025-08-01', '2025-08-01 12:32:08', 1, '2025-08-07 08:41:15'),
(44, '1st', 1, 1, NULL, 21, '2025-08-05', '2025-08-05 11:08:31', 1, '2025-08-07 08:41:15'),
(45, '1st', 1, 1, NULL, 21, '2025-08-05', '2025-08-05 11:09:18', 1, '2025-08-07 08:41:15'),
(48, 'NIGHT', 1, 1, NULL, 21, '2025-08-05', '2025-08-05 11:18:49', 1, '2025-08-07 08:41:15'),
(49, '1st', 1, 1, NULL, 21, '2025-08-05', '2025-08-05 11:20:16', 1, '2025-08-07 08:41:15'),
(50, '1st', 1, 1, NULL, 21, '2025-08-05', '2025-08-05 11:21:31', 1, '2025-08-07 08:41:15'),
(67, '1st', 1, 1, NULL, 21, '2025-07-01', '2025-08-05 12:56:06', 1, '2025-08-07 08:41:15'),
(149, '1st', 1, 1, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(150, '1st', 2, 2, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(151, '1st', 3, 3, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(152, '', 4, 4, 5, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-08 07:49:30'),
(153, '2nd', 5, 5, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(154, '2nd', 6, 6, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(155, '1st', 7, 7, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(156, '1st', 8, 8, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(157, '1st', 9, 9, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 0, '2025-08-08 09:37:53'),
(158, 'NIGHT', 10, 10, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(159, 'NIGHT', 11, 11, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(160, 'NIGHT', 12, 12, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(161, 'NIGHT', 13, 13, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(162, 'NIGHT', 14, 14, 15, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-08 07:50:23'),
(163, 'NIGHT', 15, 15, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 0, '2025-08-08 09:39:03'),
(164, 'NIGHT', 16, 16, 1, 21, '2025-07-01', '2025-08-05 14:09:49', 0, '2025-08-08 09:05:43'),
(165, 'NIGHT', 1, 17, 2, 21, '2025-07-01', '2025-08-05 14:09:49', 0, '2025-08-08 09:38:52'),
(166, 'NIGHT', 2, 18, 19, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-08 12:17:42'),
(167, 'NIGHT', 3, 19, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 0, '2025-08-08 10:10:09'),
(168, 'NIGHT', 4, 3, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(169, 'NIGHT', 5, 1, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(170, 'NIGHT', 6, 2, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(171, 'NIGHT', 7, 3, NULL, 21, '2025-07-01', '2025-08-05 14:09:49', 1, '2025-08-07 08:41:15'),
(172, '1st', 1, 1, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(173, '1st', 2, 2, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(174, '1st', 3, 3, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(175, '2nd', 4, 4, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(176, '2nd', 5, 5, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(177, '2nd', 6, 6, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(178, '1st', 7, 7, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(179, '1st', 8, 8, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(180, '1st', 9, 9, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(181, 'NIGHT', 10, 10, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(182, 'NIGHT', 11, 11, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(183, 'NIGHT', 12, 12, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(184, 'NIGHT', 13, 13, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(185, 'NIGHT', 14, 14, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(186, 'NIGHT', 15, 15, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(187, 'NIGHT', 16, 16, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(188, 'NIGHT', 1, 17, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(189, 'NIGHT', 2, 18, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(190, 'NIGHT', 3, 19, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(191, 'NIGHT', 4, 3, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(192, 'NIGHT', 5, 1, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(193, 'NIGHT', 6, 2, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(194, 'NIGHT', 7, 3, NULL, 21, '2025-07-01', '2025-08-06 07:08:03', 1, '2025-08-07 08:41:15'),
(195, '1st', 1, 1, NULL, 21, '2025-08-06', '2025-08-06 08:37:30', 1, '2025-08-07 08:41:15'),
(196, '2nd', 1, 1, NULL, 21, '2025-08-06', '2025-08-06 09:07:39', 0, '2025-08-07 08:41:15'),
(197, '1st', 1, 1, NULL, 21, '2025-08-06', '2025-08-06 09:08:18', 0, '2025-08-07 08:41:15'),
(198, '', 1, 1, 2, 21, '2025-08-07', '2025-08-07 14:35:32', 1, '2025-08-08 07:38:38'),
(199, '', 1, 1, 2, 21, '2025-08-07', '2025-08-07 14:37:31', 1, '2025-08-08 07:36:44'),
(200, '1st', 1, 1, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(201, '', 9, 7, 8, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 14:24:13'),
(202, '', 3, 3, 2, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-11 07:42:26'),
(203, '', 6, 4, 5, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 14:18:37'),
(204, '', 1, 1, 2, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 14:08:42'),
(205, '', 6, 6, 2, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:47:37'),
(206, '', 7, 7, 8, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 13:55:56'),
(207, '', 8, 8, 2, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 13:54:00'),
(208, '', 9, 20, 19, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 13:59:46'),
(209, 'NIGHT', 10, 10, 10, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 13:28:47'),
(210, 'NIGHT', 11, 11, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(211, 'NIGHT', 12, 12, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(212, 'NIGHT', 13, 13, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(213, 'NIGHT', 14, 14, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(214, 'NIGHT', 15, 15, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(215, 'NIGHT', 16, 16, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(216, 'NIGHT', 1, 17, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(217, 'NIGHT', 2, 18, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(218, 'NIGHT', 3, 19, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(219, 'NIGHT', 4, 3, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(220, 'NIGHT', 5, 1, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(221, 'NIGHT', 6, 2, NULL, 21, '2025-07-01', '2025-08-08 12:40:35', 1, '2025-08-08 12:40:35'),
(222, 'NIGHT', 7, 3, 2, 21, '2025-07-01', '2025-08-08 12:40:36', 1, '2025-08-08 12:47:16'),
(225, '', 1, 1, 2, 21, '2025-08-08', '2025-08-08 13:30:01', 1, '2025-08-08 13:55:22');

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `user_type`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John', 'Admin', 'ADMIN'),
(2, 'toolkeeper1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria', 'Santos', 'TOOLKEEPER'),
(3, 'toolkeeper2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jose', 'Cruz', 'TOOLKEEPER'),
(4, 'operator1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana', 'Reyes', 'DEFAULT'),
(5, 'operator2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos', 'Lopez', 'DEFAULT'),
(6, 'operator3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lisa', 'Garcia', 'DEFAULT'),
(7, 'operator4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Miguel', 'Torres', 'DEFAULT'),
(8, 'operator5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah', 'Johnson', 'DEFAULT'),
(9, 'operator6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Robert', 'Wilson', 'DEFAULT'),
(10, 'operator7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Elena', 'Martinez', 'DEFAULT'),
(11, 'operator8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'David', 'Brown', 'DEFAULT'),
(12, 'operator9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Grace', 'Lee', 'DEFAULT'),
(13, 'operator10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kevin', 'Davis', 'DEFAULT'),
(14, 'operator11', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jessica', 'Miller', 'DEFAULT'),
(15, 'operator12', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Antonio', 'Rivera', 'DEFAULT'),
(16, 'operator13', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Michelle', 'Taylor', 'DEFAULT'),
(17, 'operator14', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Frank', 'Anderson', 'DEFAULT'),
(18, 'operator15', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carmen', 'Hernandez', 'DEFAULT'),
(19, 'supervisor1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Richard', 'Thompson', 'TOOLKEEPER'),
(20, 'supervisor2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Linda', 'White', 'TOOLKEEPER'),
(21, 'earl_batumbakal', '$2y$10$mIGfKKMS4ETvbHtKethcceyxU8nO3xQz8PYNh0Jc82zZKG80MPjcS', 'Earl', 'Batumbakal', 'DEFAULT'),
(22, 'sdf asdgasdfs', '$2y$10$sy0oEaq.t.q9Ymc7VMfrjePm3uujaPDdFJHU5E1oHu1ayIDLCsDqq', 'asdfsadfsda', 'asdfsadgafsdhdaf', 'DEFAULT'),
(23, 'ASDFHASHGFHAGASFHASD HGF', '$2y$10$9feeeU9gjOd.cSxH7602TuuWtHCl2ZJDjcwdp/o36Aivh5Kpjv4LO', 'asdfsadfsda', 'asdfsadgafsdhdaf', 'DEFAULT'),
(24, 'DFASGAFHDAGASGBSDFTGAGA', '$2y$10$jehT1QDy5337JhQ0G9VV5eh6bn57ooBjZV4JjlmL7imaKEdelFT2m', 'DSAFSADFSAD', 'FASDFSADF', 'DEFAULT');

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
-- Indexes for table `applicator_reset`
--
ALTER TABLE `applicator_reset`
  ADD PRIMARY KEY (`reset_id`),
  ADD KEY `reset_by` (`reset_by`),
  ADD KEY `idx_applicator_id` (`applicator_id`),
  ADD KEY `idx_reset_time` (`reset_time`);

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
-- Indexes for table `machine_reset`
--
ALTER TABLE `machine_reset`
  ADD PRIMARY KEY (`reset_id`),
  ADD KEY `reset_by` (`reset_by`),
  ADD KEY `idx_machine_id` (`machine_id`),
  ADD KEY `idx_reset_time` (`reset_time`);

--
-- Indexes for table `monitor_applicator`
--
ALTER TABLE `monitor_applicator`
  ADD PRIMARY KEY (`monitor_id`),
  ADD UNIQUE KEY `applicator_id` (`applicator_id`),
  ADD KEY `idx_applicator_id` (`applicator_id`),
  ADD KEY `idx_last_updated` (`last_updated`);

--
-- Indexes for table `monitor_machine`
--
ALTER TABLE `monitor_machine`
  ADD PRIMARY KEY (`monitor_id`),
  ADD UNIQUE KEY `machine_id` (`machine_id`),
  ADD KEY `idx_machine_id` (`machine_id`),
  ADD KEY `idx_last_updated` (`last_updated`);

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
  MODIFY `applicator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `applicator_outputs`
--
ALTER TABLE `applicator_outputs`
  MODIFY `applicator_output_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;

--
-- AUTO_INCREMENT for table `applicator_reset`
--
ALTER TABLE `applicator_reset`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `custom_part_definitions`
--
ALTER TABLE `custom_part_definitions`
  MODIFY `part_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `machine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `machine_outputs`
--
ALTER TABLE `machine_outputs`
  MODIFY `machine_output_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `machine_reset`
--
ALTER TABLE `machine_reset`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `monitor_applicator`
--
ALTER TABLE `monitor_applicator`
  MODIFY `monitor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT for table `monitor_machine`
--
ALTER TABLE `monitor_machine`
  MODIFY `monitor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- Constraints for table `applicator_reset`
--
ALTER TABLE `applicator_reset`
  ADD CONSTRAINT `applicator_reset_ibfk_1` FOREIGN KEY (`applicator_id`) REFERENCES `applicators` (`applicator_id`),
  ADD CONSTRAINT `applicator_reset_ibfk_2` FOREIGN KEY (`reset_by`) REFERENCES `users` (`user_id`);

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
-- Constraints for table `machine_reset`
--
ALTER TABLE `machine_reset`
  ADD CONSTRAINT `machine_reset_ibfk_1` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`machine_id`),
  ADD CONSTRAINT `machine_reset_ibfk_2` FOREIGN KEY (`reset_by`) REFERENCES `users` (`user_id`);

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
