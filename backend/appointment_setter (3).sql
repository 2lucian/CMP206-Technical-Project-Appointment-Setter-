-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2026 at 07:31 AM
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
-- Database: `appointment_setter`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `first_name`, `last_name`, `email`, `password`, `phone_number`) VALUES
('001', 'John', 'Pork', 'porkinit@gmail.com', '$2y$10$ydwq41e20b3T1.Bwzpmtae12UT1YlblzsYBrT4SsceMw1Eo3WD4Mm', '17580085647');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` varchar(255) NOT NULL,
  `patient_id` varchar(255) NOT NULL,
  `doctor_id` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `appointment_duration` time NOT NULL,
  `appointment_notes` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `appointment_duration`, `appointment_notes`, `service`) VALUES
('appt_6a0f986da54b50.95603704', 'pat_6a0e14b3b6a871.61562380', '1', '2026-09-09', '21:30:00', '00:30:00', 'dih owie on foenem', 'Dental'),
('appt_6a0fd4a4ba7593.82907415', 'pat_6a0e14b3b6a871.61562380', '1', '2026-05-12', '15:30:00', '00:30:00', 'head hurt', 'General Consultation'),
('appt_6a0fd6006d63f6.98551435', 'pat_6a0e14b3b6a871.61562380', '1', '2026-05-12', '14:22:00', '00:30:00', 'efefge', 'General Consultation'),
('appt_6a0fda0b248777.55037538', 'pat_6a0e14b3b6a871.61562380', '1', '2026-05-12', '14:22:00', '00:30:00', 'efefge', 'Dental'),
('appt_6a0fda56f19306.09710169', 'pat_6a0e14b3b6a871.61562380', '1', '2026-05-12', '14:22:00', '00:30:00', 'dgvgsgh', 'General Consultation'),
('appt_6a0fdd3e228508.34937986', 'pat_6a0e14b3b6a871.61562380', '1', '2026-05-12', '14:22:00', '00:30:00', 'dgvgsgh', 'Dental'),
('appt_6a0fe274316bc7.87248615', 'pat_6a0fe2207a2af8.72398414', '1', '2026-05-12', '07:30:00', '00:30:00', 'knee hurt', 'General Consultation'),
('appt_6a0fe3dc200573.02834362', 'pat_6a0e14b3b6a871.61562380', '1', '2026-05-12', '15:32:00', '00:30:00', 'dfvdvB', 'Dental'),
('appt_6a0fe81260cae4.78426891', 'pat_6a0e14b3b6a871.61562380', '1', '2026-05-01', '14:22:00', '00:30:00', 'rgheheh', 'General Consultation');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_date`
--

CREATE TABLE `appointment_date` (
  `doctor_id` varchar(2255) NOT NULL,
  `appointment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_date`
--

INSERT INTO `appointment_date` (`doctor_id`, `appointment_date`) VALUES
('1', '2026-05-12'),
('1', '2026-05-01');

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `bill_id` varchar(255) NOT NULL,
  `patient_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` int(11) NOT NULL,
  `appointment_id` varchar(255) NOT NULL,
  `appointment_time` time NOT NULL,
  `appointment_duration` time NOT NULL,
  `appointment_date` date NOT NULL,
  `service` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `card_number` int(11) NOT NULL,
  `cvv` int(11) NOT NULL,
  `exp_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `first_name`, `last_name`, `email`, `password`, `phone_number`) VALUES
('1', 'Jarvics', 'Junovish', 'jvish@gmail.com', '$2y$10$9qy0L8dSEn5uU27H2UqDWePsdPke.EcD4VaShXKpXQzv941K5q8a6', '758457883'),
('doc_6a0fb442ef3fa7.50762987', 'Simone', 'Jones', 'sjalai@gmail.com', '$2y$10$vjWzA.YXXLTvNOIQ/3ifKu7/bwBJ.OgLE/m3cKC2.WJEMDM7dtaAO', '17584554545'),
('doc_6a0fcace30dc15.82572115', 'pussyclat', 'rassclat', 'bomboclat@gmail.com', '$2y$10$nABQHKkFd5jgBqJHFg.UnO7DWyBzODWXNggKLt2ra8IPa6fLEp2Ju', '17581234567');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `dob` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `first_name`, `last_name`, `email`, `password`, `phone_number`, `gender`, `dob`) VALUES
('pat_6a0e14b3b6a871.61562380', 'John', 'Joe', 'johnjoe@gmail.com', '$2y$10$pjA3c0h9oz4fq7vhBwHeSeXTS.xQjMPds/U.u5Vo94cChuvAtYGQO', '1758292934', 'Male', '2001-11-11'),
('pat_6a0e16913d1120.68420738', 'Jane', 'Doe', 'janedoe@gmail.com', '$2y$10$2Jxptw25AAZBn7XbjAN42uhZWMFFKbhBJSCYfiMje1R3iHo17WpMC', '0', 'Female', '2002-02-02'),
('pat_6a0fb3ccd85a81.88681827', 'Meye', 'Peet', 'meyepeet@gmail.com', '$2y$10$FH7WIZypSbOW9F00ZQJICe5SKyMAjz8YCiFylFc8KqwJW/m0gYtcC', '17587545565', 'Male', '2004-02-03'),
('pat_6a0fb4faed7e10.21352627', 'Yoe', 'Derek', 'yoderek@outlook.com', '$2y$10$qTLww0514VvbpjLf.gBSA.6vUWT27Ox40Yn0llVCEGW1WoV4wSPxm', '17582235567', 'Female', '1999-07-16'),
('pat_6a0fe2207a2af8.72398414', 'Jack', 'Joseph', 'jjoseph@gmail.com', '$2y$10$5t8js0gIkFJPIkisN48C3.B38C.QT55YJp2iQte8dqCSyHnLT8/3q', '17587166911', 'Male', '2000-12-31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patent_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`);

--
-- Constraints for table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `billing_ibfk_2` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
