-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 08:45 AM
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
-- Database: `crud_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangay_events`
--

CREATE TABLE `barangay_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `color` varchar(30) DEFAULT 'primary',
  `icon` varchar(50) DEFAULT 'fa-calendar',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_events`
--

INSERT INTO `barangay_events` (`id`, `title`, `description`, `event_date`, `color`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Barangay Assembly', NULL, '2026-05-13', 'primary', 'fa-users', '2026-05-06 12:06:46', NULL),
(2, 'Health Outreach Program', NULL, '2026-05-20', 'success', 'fa-heartbeat', '2026-05-06 12:06:46', NULL),
(3, 'Senior Citizen Meeting', NULL, '2026-05-27', 'warning', 'fa-user-clock', '2026-05-06 12:06:46', NULL),
(4, 'Year-End Report', NULL, '2026-06-05', 'danger', 'fa-file-alt', '2026-05-06 12:06:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `barangay_officials`
--

CREATE TABLE `barangay_officials` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `term_start` date DEFAULT NULL,
  `term_end` date DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_officials`
--

INSERT INTO `barangay_officials` (`id`, `first_name`, `middle_name`, `last_name`, `position`, `term_start`, `term_end`, `contact_number`, `email`, `address`, `status`, `photo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(8, 'Cymone John', 'belnas', 'Masilac', 'Treasurer', '2026-05-03', '0000-00-00', '09772056562', 'zzharichz@gmail.com', 'Purok 4 ermita st. binohan', 'Active', '1777933304_5b7d8f3a19862926d4b7.png', '2026-05-05 06:11:04', NULL, NULL),
(9, 'Ryniebel zharich', 'belnas', 'Masilac', 'SK Chairman', '2026-05-05', '2027-05-05', '09772056562', 'zzharichz@gmail.com', 'Purok 4 ermita st. binohan', 'Active', '1777933627_4300b3a222fcd5abced3.png', '2026-05-05 06:27:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blotter`
--

CREATE TABLE `blotter` (
  `id` int(11) NOT NULL,
  `case_number` varchar(50) DEFAULT NULL,
  `incident_type` varchar(100) NOT NULL,
  `incident_date` date NOT NULL,
  `complainant_name` varchar(150) NOT NULL,
  `respondent_name` varchar(150) NOT NULL,
  `incident_location` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Ongoing',
  `narrative` text DEFAULT NULL,
  `action_taken` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blotter`
--

INSERT INTO `blotter` (`id`, `case_number`, `incident_type`, `incident_date`, `complainant_name`, `respondent_name`, `incident_location`, `status`, `narrative`, `action_taken`, `created_at`, `updated_at`) VALUES
(2, '203923', 'Physical Assault', '2005-05-02', 'jobelle masilac', 'ryanmasilac', 'Talangnan, Isio,Cauayan ,Neg Occ', 'Ongoing', 'sumbaganay', 'tambagan', '2026-04-30 12:08:25', '2026-04-30 12:08:25'),
(6, '12345', 'Trespassing', '2026-05-03', 'jobelle masilac', 'ryan masilac', 'Himamaylan City', 'Referred to Court', 'dasdsadas', '', '2026-05-05 10:08:12', '2026-05-05 10:08:12'),
(7, '203923', 'Physical Assault', '2026-05-06', 'Vince ', 'John Henry Pabilona', 'Himamaylan City', 'Ongoing', 'BAGANAY SILA DUWA', 'LABLAB NA ULIT ', '2026-05-06 05:03:19', '2026-05-06 05:03:19');

-- --------------------------------------------------------

--
-- Table structure for table `clearances`
--

CREATE TABLE `clearances` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `clearance_type` varchar(100) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `date_issued` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `households`
--

CREATE TABLE `households` (
  `id` int(11) NOT NULL,
  `head_name` varchar(150) DEFAULT NULL,
  `address_line1` text DEFAULT NULL,
  `purok` varchar(150) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `city_municipality` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `total_members` int(11) DEFAULT 1,
  `status` varchar(50) DEFAULT 'Active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `households`
--

INSERT INTO `households` (`id`, `head_name`, `address_line1`, `purok`, `barangay`, `city_municipality`, `province`, `zip_code`, `total_members`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ryan masilac', 'Purok 4', '4', 'ISIO', 'CAUAYAN', 'NEGROS OCCIDENTAL', '6112', 1, 'Active', '2026-05-04 05:04:50', '2026-05-04 05:04:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `indigents`
--

CREATE TABLE `indigents` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `resident_name` varchar(255) GENERATED ALWAYS AS (concat(`first_name`,' ',coalesce(nullif(trim(`middle_name`),''),''),' ',`last_name`)) STORED,
  `indigency_category` varchar(100) DEFAULT NULL,
  `assistance_type` varchar(100) DEFAULT NULL,
  `assistance_amount` decimal(10,2) DEFAULT NULL,
  `date_assessed` date DEFAULT NULL,
  `date_provided` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempt_time` datetime NOT NULL,
  `user_agent` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `email`, `ip_address`, `attempt_time`, `user_agent`) VALUES
(36, 'glennazuelo1@gmail.com', '::142432432', '2025-04-15 13:15:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permits`
--

CREATE TABLE `permits` (
  `id` int(11) NOT NULL,
  `business_name` varchar(150) DEFAULT NULL,
  `owner_name` varchar(150) DEFAULT NULL,
  `owner_resident_id` int(11) DEFAULT NULL,
  `business_address` text DEFAULT NULL,
  `business_type` varchar(100) DEFAULT NULL,
  `permit_type` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `fees_paid` decimal(10,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `civil_status` varchar(50) DEFAULT NULL,
  `is_voter` tinyint(1) DEFAULT 0,
  `voter_id` varchar(100) DEFAULT NULL,
  `household_id` int(11) DEFAULT NULL,
  `address_line1` text DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `gender`, `civil_status`, `is_voter`, `voter_id`, `household_id`, `address_line1`, `barangay`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Ryniebel zharich', 'belnas', 'Masilac', '', '2006-12-16', 'Female', 'Single', 1, '452984', 1242, 'ISIO,BINOHAN,CAUAYAN,NEGROS OCCIDENTAL', 'ISIO', 'Active', '2026-04-25 22:51:51', NULL, NULL),
(6, 'Cymone John', 'belnas', 'Masilac', 'jr', '2005-05-02', 'Male', 'Married', 0, '452984', 1242, 'ISIO,BINOHAN,CAUAYAN,NEGROS OCCIDENTAL', 'ISIO', 'Active', '2026-04-25 23:14:41', NULL, NULL),
(7, 'RYAN', 'EVANGELIO', 'MASILAC', '', '1993-05-21', 'Male', 'Married', 1, '215659', 1242, 'ISIO,BINOHAN,CAUAYAN,NEGROS OCCIDENTAL', 'ISIO', 'Active', '2026-04-25 23:46:58', NULL, NULL),
(10, 'Ryan Zurielle', 'belnas', 'Masilac', '', '2013-08-28', 'Male', 'Single', 0, '01654623', 12345688, 'Purok 4 ermita st. binohan', 'ISIO', 'Inactive', '2026-05-01 06:16:29', NULL, NULL),
(11, 'Sittie Jaynah', 'belnas', 'Pangandaman', '', '1915-02-25', 'Female', 'Married', 1, '065651', 248045, 'PUROK 7-B TALANGNAN', 'ISIO', 'Active', '2026-05-01 06:21:40', NULL, NULL),
(12, 'SAMANTHA JANE', 'BELNAS', 'ENCIA', '', '2003-12-31', 'Female', 'Single', 1, '51554', 1848541, 'PUROK 7-B TALANGNAN', 'ISIO', 'Active', '2026-05-01 06:22:56', NULL, NULL),
(13, 'John Gabriel', 'Gregas', 'Masilac', '', '2006-12-01', 'Male', 'Single', 1, '0120221', 1231521, 'Purok 4 ermita st. binohan', 'ISIO', 'Active', '2026-05-01 06:24:06', NULL, NULL),
(14, 'kent bryan ', 'Gregas', 'Masilac', '', '1910-06-23', 'Male', 'Single', 0, '084616', 4562, 'Purok 4 ermita st. binohan', 'ISIO', 'Active', '2026-05-01 06:24:47', NULL, NULL),
(15, 'lester', 'Gregas', 'Masilac', '', '1910-06-23', 'Male', 'Single', 0, '084616', 4562, 'Purok 4 ermita st. binohan', 'ISIO', 'Active', '2026-05-01 06:25:13', NULL, NULL),
(16, 'cymone', 'BELNAS', 'masilac', '', '2003-12-31', 'Male', 'Single', 1, '51554', 1848541, 'PUROK 7-B TALANGNAN', 'ISIO', 'Active', '2026-05-01 06:25:53', NULL, NULL),
(17, 'John Rowell', 'EVANGELIO', 'Masilac', '', '1956-01-12', 'Male', 'Married', 0, '562', 0, 'Purok 4 ermita st. binohan', '', 'Active', '2026-05-01 06:27:43', NULL, NULL),
(18, 'john henry', 'aguilar', 'pabilona', '', '2006-02-06', 'Male', 'Single', 1, '2541651', 21418, 'himamaylan city ', 'uno', 'Active', '2026-05-05 18:05:24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_logs`
--

CREATE TABLE `tbl_logs` (
  `LOGID` int(11) NOT NULL,
  `USERID` varchar(30) DEFAULT NULL,
  `ACTION` text DEFAULT NULL,
  `DATELOG` varchar(30) DEFAULT NULL,
  `TIMELOG` varchar(30) DEFAULT NULL,
  `user_ip_address` text DEFAULT NULL,
  `device_used` text DEFAULT NULL,
  `USER_NAME` varchar(100) DEFAULT NULL,
  `identifier` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_logs`
--

INSERT INTO `tbl_logs` (`LOGID`, `USERID`, `ACTION`, `DATELOG`, `TIMELOG`, `user_ip_address`, `device_used`, `USER_NAME`, `identifier`) VALUES
(1, '1', 'New User has been apdated: Glenn Azuelo', '2025-07-21', '20:11:13', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', NULL, 'UPDATED'),
(2, '1', 'Logout', '2025-07-21', '20:12:03', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', NULL, 'LOGOUT'),
(3, '1', 'Login: Glenn Azuelo', '2025-07-21', '20:12:16', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', NULL, 'LOGIN'),
(4, '1', 'Logout', '2025-07-21', '20:14:42', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', NULL, 'LOGOUT'),
(5, '10', 'Login: Cherry Ann Grandia', '2025-07-21', '20:14:47', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', NULL, 'LOGIN'),
(6, '10', 'New User has been apdated: Glenn Azuelo', '2025-07-21', '20:18:03', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', '10', 'UPDATED'),
(7, '10', 'New User has been apdated: Cherry Ann Grandia', '2025-07-21', '20:19:17', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', NULL, 'UPDATED'),
(8, '10', 'Logout', '2025-07-21', '20:19:18', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', NULL, 'LOGOUT'),
(9, '1', 'Login: Glenn Azuelo', '2025-07-21', '20:19:23', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'Glenn Azuelo', 'LOGIN'),
(10, '1', 'Logout', '2025-07-21', '20:19:56', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'Glenn Azuelo', 'LOGOUT'),
(11, '1', 'Login: Glenn Azuelo', '2025-07-21', '20:21:27', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'Glenn Azuelo', 'LOGIN'),
(12, '1', 'New User has been added: xxx', '2025-07-21', '20:32:39', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'Glenn Azuelo', 'ADD'),
(13, '1', 'Delete user', '2025-07-21', '20:32:44', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'Glenn Azuelo', 'DELETED'),
(14, '1', 'Login: Glenn Azuelo', '2026-04-25', '11:03:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Glenn Azuelo', 'LOGIN'),
(15, '1', 'New User has been added: Cymone', '2026-04-25', '11:29:03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Glenn Azuelo', 'ADD'),
(16, '1', 'Logout', '2026-04-25', '11:32:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Glenn Azuelo', 'LOGOUT'),
(17, '12', 'Login: Cymone', '2026-04-25', '11:32:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(18, '12', 'Login: Cymone', '2026-04-25', '22:45:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(19, '12', 'Logout', '2026-04-26', '00:01:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGOUT'),
(20, '12', 'Login: Cymone', '2026-04-26', '00:06:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(21, '12', 'Login: Cymone', '2026-04-26', '18:01:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(22, '12', 'Login: Cymone', '2026-04-30', '19:51:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(23, '12', 'Logout', '2026-04-30', '20:50:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGOUT'),
(24, '12', 'Login: Cymone', '2026-04-30', '20:57:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(25, '12', 'Login: Cymone', '2026-05-01', '06:05:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(26, '12', 'Login: Cymone', '2026-05-01', '21:09:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(27, '12', 'Login: Cymone', '2026-05-03', '07:00:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(28, '12', 'Login: Cymone', '2026-05-04', '11:49:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(29, '12', 'Delete user', '2026-05-04', '13:24:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'DELETED'),
(30, '12', 'Delete user', '2026-05-04', '13:24:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'DELETED'),
(31, '12', 'Delete user', '2026-05-04', '13:24:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'DELETED'),
(32, '12', 'Login: Cymone', '2026-05-04', '19:28:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(33, '12', 'Logout', '2026-05-04', '20:04:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGOUT'),
(34, '12', 'Login: Cymone', '2026-05-04', '20:04:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(35, '12', 'Login: Cymone', '2026-05-05', '05:26:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(36, '12', 'Login: Cymone', '2026-05-05', '12:09:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(37, '12', 'New User has been added: ryan', '2026-05-05', '12:20:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'ADD'),
(38, '13', 'Login: ryan', '2026-05-05', '12:20:53', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.118.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'ryan', 'LOGIN'),
(39, '12', 'Login: Cymone', '2026-05-05', '17:33:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(40, '12', 'Login: Cymone', '2026-05-06', '05:44:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(41, '12', 'Login: Cymone', '2026-05-06', '11:12:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(42, '12', 'Logout', '2026-05-06', '14:39:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGOUT'),
(43, '12', 'Login: Cymone', '2026-05-06', '14:39:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(100) DEFAULT 'user',
  `status` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `email`, `password`, `role`, `status`, `name`, `phone`, `created_at`, `updated_at`, `deleted_at`) VALUES
(12, NULL, 'ceejay05022005@gmail.com', '$2y$10$a3vT.kyL9DqmsVudt84xredzW/Z15ji/DvU/i0gwbbwpNB8L3IhUa', 'Admin', 'Active', 'Cymone', '09944521855', '2026-04-25 03:29:03', '2026-04-24 19:29:03', '2026-04-24 19:29:03'),
(13, NULL, 'ryan@gmail.com', '$2y$10$dwb.WfOcwH9d1.ManQv4q.Ttg6O62yyaLNVeBgPKJAbbELHAV1lB2', 'User', 'Active', 'ryan', '09948602184', '2026-05-05 04:20:34', '2026-05-04 20:20:34', '2026-05-04 20:20:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangay_events`
--
ALTER TABLE `barangay_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blotter`
--
ALTER TABLE `blotter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clearances`
--
ALTER TABLE `clearances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- Indexes for table `households`
--
ALTER TABLE `households`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `indigents`
--
ALTER TABLE `indigents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permits`
--
ALTER TABLE `permits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_resident_id` (`owner_resident_id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  ADD PRIMARY KEY (`LOGID`),
  ADD KEY `USERID` (`USERID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangay_events`
--
ALTER TABLE `barangay_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `blotter`
--
ALTER TABLE `blotter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clearances`
--
ALTER TABLE `clearances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `households`
--
ALTER TABLE `households`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `indigents`
--
ALTER TABLE `indigents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permits`
--
ALTER TABLE `permits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  MODIFY `LOGID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clearances`
--
ALTER TABLE `clearances`
  ADD CONSTRAINT `clearances_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
