-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2026 at 11:09 AM
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
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permit_id` int(10) UNSIGNED NOT NULL,
  `action` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `permit_id`, `action`, `details`, `user_id`, `ip_address`, `created_at`) VALUES
(1, 1, 'CREATED', 'New permit created', 12, '::1', '2026-05-09 22:14:31'),
(2, 1, 'UPDATED', 'Permit information updated', 12, '::1', '2026-05-09 22:16:35'),
(3, 1, 'Updated', 'Permit details updated', 12, '::1', '2026-05-09 22:57:45'),
(4, 1, 'Updated', 'Permit details updated', 12, '::1', '2026-05-09 22:58:50'),
(5, 2, 'Created', 'Permit application submitted', 12, '::1', '2026-05-09 23:05:31'),
(6, 2, 'Updated', 'Permit details updated', 12, '::1', '2026-05-09 23:05:42'),
(7, 2, 'Updated', 'Permit details updated', 12, '::1', '2026-05-09 23:05:49'),
(8, 2, 'Approved', 'Application approved by Admin', 12, '::1', '2026-05-09 23:12:08'),
(12, 1, 'Approved', 'Application approved by Admin', 12, '::1', '2026-05-10 04:19:35'),
(13, 4, 'Created', 'Permit application submitted', 12, '::1', '2026-05-10 04:24:57'),
(14, 4, 'Updated', 'Permit details updated', 12, '::1', '2026-05-10 04:25:11'),
(15, 4, 'Updated', 'Permit details updated', 12, '::1', '2026-05-10 04:25:22'),
(16, 4, 'Approved', 'Application approved by Admin', 12, '::1', '2026-05-10 04:25:29'),
(17, 4, 'Updated', 'Permit details updated', 12, '::1', '2026-05-10 04:30:09'),
(18, 5, 'Created', 'Permit application submitted', 12, '::1', '2026-05-10 04:30:26'),
(19, 4, 'Paid', 'Fees of ₱5,000.00 received', 12, '::1', '2026-05-10 04:30:52'),
(20, 4, 'Activated', 'Permit marked as Active', 12, '::1', '2026-05-10 04:31:04'),
(21, 4, 'Printed', 'Permit document generated', 12, '::1', '2026-05-10 04:33:02'),
(22, 2, 'Paid', 'Fees of ₱20,000.00 received', 12, '::1', '2026-05-10 04:41:25'),
(23, 2, 'Printed', 'Permit document generated', 12, '::1', '2026-05-10 04:41:41'),
(24, 2, 'Activated', 'Permit marked as Active', 12, '::1', '2026-05-10 04:41:47'),
(25, 1, 'Paid', 'Fees of ₱20,000.00 received', 12, '::1', '2026-05-10 05:11:59'),
(26, 1, 'Activated', 'Permit marked as Active', 12, '::1', '2026-05-10 05:12:18'),
(27, 5, 'Updated', 'Permit details updated', 12, '::1', '2026-05-10 05:14:49'),
(28, 5, 'Approved', 'Approved by Admin', 12, '::1', '2026-05-10 05:15:23'),
(29, 5, 'Paid', 'Fees of ₱20,000.00 received', 12, '::1', '2026-05-10 05:15:42'),
(30, 5, 'Activated', 'Permit marked as Active', 12, '::1', '2026-05-10 05:16:10'),
(31, 6, 'Created', 'Permit application submitted', 12, '::1', '2026-05-10 09:38:45'),
(32, 5, 'Deleted', 'Permit removed from active records', 12, '::1', '2026-05-10 09:38:52'),
(33, 6, 'Approved', 'Approved by Admin', 12, '::1', '2026-05-10 09:38:59'),
(34, 6, 'Paid', 'Fees of ₱5,000.00 received', 12, '::1', '2026-05-10 09:39:18'),
(35, 4, 'Deleted', 'Permit removed from active records', 12, '::1', '2026-05-11 23:34:43'),
(36, 6, 'Activated', 'Permit marked as Active', 12, '::1', '2026-05-11 23:34:52'),
(37, 6, 'Deleted', 'Permit removed from active records', 12, '::1', '2026-05-11 23:34:58'),
(38, 2, 'Updated', 'Permit details updated', 12, '::1', '2026-05-12 15:19:35');

-- --------------------------------------------------------

--
-- Table structure for table `barangay_events`
--

CREATE TABLE `barangay_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT 0.00,
  `status` varchar(50) DEFAULT 'Draft',
  `participants` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `poster_image` varchar(255) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `qr_code` varchar(50) DEFAULT NULL,
  `sms_sent` tinyint(1) DEFAULT 0,
  `color` varchar(30) DEFAULT 'primary',
  `icon` varchar(50) DEFAULT 'fa-calendar',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_events`
--

INSERT INTO `barangay_events` (`id`, `title`, `description`, `venue`, `event_date`, `event_time`, `end_date`, `end_time`, `budget`, `status`, `participants`, `notes`, `poster_image`, `is_public`, `qr_code`, `sms_sent`, `color`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Barangay Assembly', NULL, NULL, '2026-05-13', NULL, NULL, NULL, 0.00, 'Scheduled', NULL, NULL, NULL, 0, NULL, 0, 'primary', 'fa-users', '2026-05-06 12:06:46', '2026-05-12 12:26:01'),
(2, 'Health Outreach Program', NULL, NULL, '2026-05-20', NULL, NULL, NULL, 0.00, 'Completed', NULL, NULL, NULL, 0, NULL, 0, 'success', 'fa-heartbeat', '2026-05-06 12:06:46', '2026-05-12 21:22:58'),
(5, 'basketball league', 'Basket Ball League', NULL, '2026-05-08', NULL, NULL, NULL, 0.00, 'Draft', NULL, NULL, NULL, 0, NULL, 0, 'success', 'fa-sports', '2026-05-08 03:49:45', '2026-05-08 08:04:13'),
(6, 'sumbaganay', 'suntukan na', NULL, '2026-05-09', NULL, NULL, NULL, 0.00, 'Draft', NULL, NULL, NULL, 0, NULL, 0, 'primary', 'fa-sports', '2026-05-08 08:28:13', '2026-05-08 08:28:13'),
(7, 'Barangay Assembly', 'Wah balo', 'Baranggay Hal', '2026-05-12', '00:30:00', '2026-05-12', '17:00:00', 5000.00, 'Completed', 500, 'adsaasdasd', NULL, 1, 'EVT-2026-1DAF06', 0, 'primary', 'fa-users', '2026-05-12 09:55:45', '2026-05-12 09:56:18');

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
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_officials`
--

INSERT INTO `barangay_officials` (`id`, `first_name`, `middle_name`, `last_name`, `position`, `term_start`, `term_end`, `contact_number`, `email`, `address`, `status`, `created_at`, `updated_at`, `deleted_at`, `photo`) VALUES
(8, 'Cymone John', 'belnas', 'Masilac', 'Barangay Captain', '2026-05-03', '0000-00-00', '09772056562', 'zzharichz@gmail.com', 'Purok 4 ermita st. binohan', 'Active', '2026-05-05 06:11:04', NULL, NULL, NULL),
(9, 'Ryniebel zharich', 'belnas', 'Masilac', 'SK Chairman', '2026-05-05', '2027-05-05', '09772056562', 'zzharichz@gmail.com', 'Purok 4 ermita st. binohan', 'Active', '2026-05-05 06:27:07', NULL, NULL, NULL),
(14, 'Ryniebel zharich', 'belnas', 'Masilac', 'Barangay Captain', '2026-05-12', '2027-05-03', '09772056562', 'zzharichz@gmail.com', '', 'Active', '2026-05-13 10:17:19', NULL, NULL, '1778638639_51b39ec38f0e1930d931.png');

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
  `clearance_id` int(11) UNSIGNED NOT NULL,
  `control_number` varchar(50) NOT NULL,
  `resident_id` int(11) UNSIGNED NOT NULL,
  `clearance_type_id` int(11) UNSIGNED NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `request_date` date NOT NULL,
  `issued_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Released','Rejected','Expired') NOT NULL DEFAULT 'Pending',
  `fee_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `or_number` varchar(50) DEFAULT NULL COMMENT 'Official Receipt Number',
  `remarks` text DEFAULT NULL,
  `processed_by` int(11) UNSIGNED DEFAULT NULL,
  `signed_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clearances`
--

INSERT INTO `clearances` (`clearance_id`, `control_number`, `resident_id`, `clearance_type_id`, `purpose`, `request_date`, `issued_date`, `expiry_date`, `status`, `fee_amount`, `or_number`, `remarks`, `processed_by`, `signed_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '0123245', 6, 1, 'applying work', '2026-05-07', '2026-05-10', '2027-05-07', 'Approved', 500.00, '024456', 'kewkew', 1, NULL, '2026-05-07 02:16:47', '2026-05-12 21:33:36', '2026-05-12 21:33:36'),
(2, '56126', 6, 5, 'employment', '2026-05-08', '2026-05-12', '2027-06-22', 'Released', 500.00, '9562', NULL, 1, NULL, '2026-05-08 04:03:57', '2026-05-12 21:33:29', '2026-05-12 21:33:29'),
(3, 'CLR-2026-13740', 6, 1, 'apply scholar', '2026-05-12', '2026-05-12', '2026-11-12', 'Released', 500.00, '0R-2026-001', 'hadadnadas', NULL, NULL, '2026-05-12 21:33:56', '2026-05-12 21:42:15', NULL),
(4, 'CLR-2026-64839', 6, 1, 'Apply work', '2026-05-12', '2026-05-12', '2026-11-12', 'Released', 500.00, '0R-2026-002', 'AADADSAD', NULL, NULL, '2026-05-12 21:43:20', '2026-05-13 04:38:09', '2026-05-13 04:38:09');

-- --------------------------------------------------------

--
-- Table structure for table `clearance_types`
--

CREATE TABLE `clearance_types` (
  `clearance_type_id` int(11) UNSIGNED NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `fee_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `validity_days` int(11) NOT NULL DEFAULT 365 COMMENT 'How many days the clearance is valid',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clearance_types`
--

INSERT INTO `clearance_types` (`clearance_type_id`, `type_name`, `description`, `fee_amount`, `validity_days`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Barangay Clearance', 'General barangay clearance for various purposes', 50.00, 365, 1, '2026-05-07 10:13:49', '2026-05-07 10:13:49'),
(2, 'Certificate of Residency', 'Certifies that the person is a resident of the barangay', 50.00, 365, 1, '2026-05-07 10:13:49', '2026-05-07 10:13:49'),
(3, 'Good Moral Character', 'Certifies good moral standing of the resident', 50.00, 365, 1, '2026-05-07 10:13:49', '2026-05-07 10:13:49'),
(4, 'Certificate of Indigency', 'For indigent residents needing financial assistance', 0.00, 365, 1, '2026-05-07 10:13:49', '2026-05-07 10:13:49'),
(5, 'Business Clearance', 'Clearance for business operations within the barangay', 100.00, 365, 1, '2026-05-07 10:13:49', '2026-05-07 10:13:49'),
(6, 'Travel Clearance', 'Clearance for travel purposes', 50.00, 180, 1, '2026-05-07 10:13:49', '2026-05-07 10:13:49');

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
(1, 'ryan masilac', 'Purok 4', '4', 'ISIO', 'CAUAYAN', 'NEGROS OCCIDENTAL', '6112', 1, 'Active', '2026-05-04 05:04:50', '2026-05-04 05:04:50', NULL),
(2, 'JOHN DOE', 'MANTAMILOK', '6', 'ISIO', 'CAUAYAN', 'NEGROS OCCIDENTAL', '6112', 10, 'Active', '2026-05-07 01:52:03', '2026-05-07 01:52:03', NULL),
(3, 'Ryniebel zharich belnas Masilac', 'Purok 4 ermita st. binohan', '7A', 'isio', 'CAUAYAN', 'NEGROS OCCIDENTAL', '6112', 10, 'Active', '2026-05-07 02:25:10', '2026-05-07 02:25:10', NULL),
(4, 'jobelle belnas Masilac', 'Purok 4 ermita st. binohan', '7B', 'isio', 'CAUAYAN', 'NEGROS OCCIDENTAL', '6112', 1, 'Active', '2026-05-07 07:26:53', '2026-05-07 07:26:53', NULL),
(5, 'Cj masilac', 'Talangnan', '7B', 'Isio', 'Cauayan', 'Negros Occidental', '6126', 15, 'Relocated', '2026-05-07 07:36:44', '2026-05-07 22:10:08', NULL),
(6, 'kent gregas masilac', 'binohan', '4', 'Isio', 'Cauayan', 'Negros Occidental', '6112', 15, 'Active', '2026-05-09 03:08:26', '2026-05-09 03:08:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `indigents`
--

CREATE TABLE `indigents` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) DEFAULT NULL,
  `indigency_category` varchar(100) NOT NULL,
  `assistance_type` varchar(100) DEFAULT NULL,
  `assistance_amount` decimal(10,2) DEFAULT 0.00,
  `status` varchar(50) DEFAULT 'Pending Assessment',
  `purpose` text DEFAULT NULL,
  `date_assessed` date DEFAULT NULL,
  `date_provided` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `rejected_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `indigents`
--

INSERT INTO `indigents` (`id`, `resident_id`, `indigency_category`, `assistance_type`, `assistance_amount`, `status`, `purpose`, `date_assessed`, `date_provided`, `remarks`, `rejected_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, '4Ps Family', 'Financial', 5000.00, 'Completed', '5162', '2026-05-12', '2026-05-12', '', NULL, '2026-05-12 08:00:19', '2026-05-12 12:13:05', NULL),
(2, NULL, '4Ps Family', 'Financial', 5000.00, 'Completed', 'adsadas', '2026-05-13', '2026-05-13', 'adasdsa', NULL, '2026-05-13 00:14:10', '2026-05-13 00:15:10', NULL);

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

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2024-01-01-000001', 'App\\Database\\Migrations\\CreateIndigentsTable', 'default', 'App', 1778627655, 1),
(4, '2026-05-04-000000', 'App\\Database\\Migrations\\AddOfficialPhotoToBarangayOfficials', 'default', 'App', 1778627866, 2),
(6, '2026-05-13-000001', 'App\\Database\\Migrations\\CreateBlotterTables', 'default', 'App', 1778628654, 3);

-- --------------------------------------------------------

--
-- Table structure for table `permits`
--

CREATE TABLE `permits` (
  `id` int(10) UNSIGNED NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `owner_resident_id` int(10) UNSIGNED DEFAULT NULL,
  `business_address` text NOT NULL,
  `business_type` varchar(100) DEFAULT NULL,
  `permit_type` enum('New','Renewal','Amendment') DEFAULT 'New',
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('Pending','Approved','Paid','Active','Expired','Rejected') DEFAULT 'Pending',
  `fees_paid` decimal(10,2) DEFAULT 0.00,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `paid_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permits`
--

INSERT INTO `permits` (`id`, `business_name`, `owner_name`, `owner_resident_id`, `business_address`, `business_type`, `permit_type`, `issue_date`, `expiry_date`, `status`, `fees_paid`, `approved_by`, `approved_date`, `paid_date`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'XIN-PAT Sari2 store ', 'LAGULAO', NULL, 'ISIO,CAUAYAN,NEGROS,OCCIDENTAL.', 'SARI SARI STORE', 'New', '2026-05-10', '2027-05-02', 'Active', 20000.00, NULL, NULL, NULL, '', '2026-05-09 22:14:31', '2026-05-10 05:12:18', NULL),
(2, 'shabuhan', 'kewkwe', NULL, 'talangnan,isio,cauayan ', 'eatery', 'New', '2026-05-09', '2026-05-10', 'Active', 20000.00, NULL, NULL, NULL, '', '2026-05-09 23:05:31', '2026-05-12 15:19:35', NULL),
(4, 'papingan', 'cymone', NULL, '', 'suyopan', 'Amendment', '2026-05-10', '2027-05-10', 'Active', 5000.00, NULL, NULL, NULL, '', '2026-05-10 04:24:57', '2026-05-11 23:34:43', '2026-05-11 23:34:43'),
(5, 'XIN-PAT Sari2 store ', 'cymone', NULL, 'Isio,Cauayan,Negros Occidental', 'suyopan', 'New', '2026-05-10', '2026-05-10', 'Active', 20000.00, NULL, NULL, NULL, 'hala bira', '2026-05-10 04:30:26', '2026-05-10 09:38:52', '2026-05-10 09:38:52'),
(6, 'XIN-PAT Sari2 store ', 'cymone', NULL, '', 'suyopan', 'New', '2026-05-10', '2026-05-10', 'Active', 5000.00, NULL, NULL, NULL, '', '2026-05-10 09:38:45', '2026-05-11 23:34:58', '2026-05-11 23:34:58');

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
  `contact_number` varchar(20) DEFAULT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `is_blacklisted` tinyint(1) DEFAULT 0,
  `blacklist_reason` text DEFAULT NULL,
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

INSERT INTO `residents` (`id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `gender`, `civil_status`, `is_voter`, `voter_id`, `contact_number`, `photo`, `is_blacklisted`, `blacklist_reason`, `household_id`, `address_line1`, `barangay`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(24, 'Cymone John', 'Belnas', 'Masilac', '', '2005-05-02', 'Male', 'Single', 1, '0123456', '09944521855', NULL, 0, NULL, 9999, 'Purok 1', 'Isio', 'Active', '2026-05-13 14:53:55', NULL, NULL),
(25, 'Ryniebel ', 'Belnas', 'Masilac', '', '2006-12-16', 'Female', 'Single', 1, '23456', '09772056562', NULL, 0, NULL, 1111, 'Purok 2', 'Isio', 'Active', '2026-05-13 14:55:19', NULL, NULL),
(26, 'jobelle', 'Belnas', 'masilac', '', '1994-06-23', 'Female', 'Married', 1, '3456789', '09948602184', NULL, 0, NULL, 2222, 'Purok 3', 'Isio', 'Active', '2026-05-13 14:56:29', NULL, NULL),
(27, 'Ryan Zurielle', 'Belnas', 'masilac', '', '2012-08-28', 'Male', 'Single', 0, '4567789', '09123456', NULL, 0, NULL, 4444, 'Purok 4', 'Isio', 'Active', '2026-05-13 14:58:11', NULL, NULL),
(28, 'kent bryan ', 'Gregas', 'Masilac', 'Jr.', '2008-12-16', 'Male', 'Single', 1, '56789', '0923456', NULL, 0, NULL, 5555, 'Purok 5', 'Isio', 'Active', '2026-05-13 15:00:40', NULL, NULL),
(29, 'John Gabriel', 'Gregas', 'Masilac', 'Sr.', '2006-07-08', 'Male', 'Single', 1, '678912', '093456', NULL, 0, NULL, 6666, 'Purok 6', 'Isio', 'Active', '2026-05-13 15:01:58', NULL, NULL),
(30, 'john henry', 'aguilar', 'pabilona', 'II', '2006-06-25', 'Male', 'Single', 1, '79123', '094456789', NULL, 0, NULL, 7777, 'Purok 7A', 'Isio', 'Active', '2026-05-13 15:03:25', NULL, NULL),
(31, 'John Arwel', 'Gregas', 'Masilac', 'III', '2013-09-20', 'Male', 'Single', 0, '1234589', '09789456', NULL, 0, NULL, 284956, 'Purok 7B', 'Isio', 'Active', '2026-05-13 15:05:21', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `setting_type` enum('text','number','email','tel','textarea','image','boolean','json') DEFAULT 'text',
  `description` varchar(255) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `setting_type`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'barangay_name', 'ISIO', 'general', 'text', 'Name of the barangay', 0, NULL, NULL),
(2, 'municipality', 'Cauayan', 'general', 'text', 'Municipality name', 0, NULL, NULL),
(3, 'province', 'Negros Occidental', 'general', 'text', 'Province name', 0, NULL, NULL),
(4, 'barangay_captain', 'HON. JEROME AGUSTIN', 'general', 'text', 'Name of the barangay captain', 0, NULL, NULL),
(5, 'contact_number', '', 'general', 'tel', 'Official contact number', 0, NULL, NULL),
(6, 'email_address', '', 'general', 'email', 'Official email address', 0, NULL, NULL),
(7, 'address', '', 'general', 'textarea', 'Full barangay address', 0, NULL, NULL),
(8, 'logo_path', '', 'branding', 'image', 'Path to barangay logo', 0, NULL, NULL),
(9, 'seal_path', '', 'branding', 'image', 'Path to official seal', 0, NULL, NULL),
(10, 'signature_path', '', 'branding', 'image', 'Path to captain signature', 0, NULL, NULL),
(11, 'print_header', '', 'document', 'textarea', 'Default print header text', 0, NULL, NULL),
(12, 'print_footer', '', 'document', 'textarea', 'Default print footer text', 0, NULL, NULL),
(13, 'qr_enabled', '1', 'document', 'boolean', 'Enable QR code verification', 0, NULL, NULL),
(14, 'auto_numbering', '1', 'document', 'boolean', 'Enable auto document numbering', 0, NULL, NULL),
(15, 'default_fee', '50.00', 'fees', 'number', 'Default clearance fee', 0, NULL, NULL),
(16, 'backup_enabled', '1', 'system', 'boolean', 'Enable automatic backups', 0, NULL, NULL),
(17, 'maintenance_mode', '0', 'system', 'boolean', 'Enable maintenance mode', 0, NULL, NULL),
(18, 'dark_mode_default', '0', 'system', 'boolean', 'Default to dark mode', 0, NULL, NULL);

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
(43, '12', 'Login: Cymone', '2026-05-06', '14:39:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(44, '12', 'Login: Cymone', '2026-05-06', '21:41:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(45, '12', 'Login: Cymone', '2026-05-07', '09:47:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(46, '12', 'Login: Cymone', '2026-05-07', '14:55:14', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(47, '12', 'Login: Cymone', '2026-05-07', '21:11:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(48, '12', 'Login: Cymone', '2026-05-08', '05:43:37', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(49, '12', 'Login: Cymone', '2026-05-08', '09:41:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(50, '12', 'Login: Cymone', '2026-05-08', '11:03:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(51, '12', 'Logout', '2026-05-08', '12:59:34', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGOUT'),
(52, '12', 'Login: Cymone', '2026-05-08', '12:59:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(53, '12', 'Login: Cymone', '2026-05-08', '15:49:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(54, '12', 'Login: Cymone', '2026-05-08', '19:30:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(55, '12', 'Login: Cymone', '2026-05-08', '21:47:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(56, '12', 'Login: Cymone', '2026-05-09', '05:21:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(57, '12', 'Login: Cymone', '2026-05-09', '09:26:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(58, '12', 'Login: Cymone', '2026-05-09', '10:42:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(59, '12', 'Logout', '2026-05-09', '12:10:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGOUT'),
(60, '12', 'Login: Cymone', '2026-05-09', '12:10:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(61, '12', 'Login: Cymone', '2026-05-09', '16:20:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(62, '12', 'Login: Cymone', '2026-05-09', '18:27:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(63, '12', 'Login: Cymone', '2026-05-09', '22:17:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(64, '12', 'Login: Cymone', '2026-05-10', '05:54:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Cymone', 'LOGIN'),
(65, '12', 'Login: Cymone', '2026-05-10', '12:14:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(66, '12', 'Login: Cymone', '2026-05-10', '17:25:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(67, '12', 'Login: Cymone', '2026-05-10', '20:04:50', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(68, '12', 'Login: Cymone', '2026-05-11', '05:52:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(69, '12', 'Login: Cymone', '2026-05-11', '18:16:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(70, '12', 'Login: Cymone', '2026-05-11', '20:56:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(71, '12', 'Login: Cymone', '2026-05-12', '05:57:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(72, '12', 'Logout', '2026-05-12', '11:01:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGOUT'),
(73, '12', 'Login: Cymone', '2026-05-12', '11:01:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'Cymone', 'LOGIN'),
(74, '12', 'Login: Cymone', '2026-05-12', '11:12:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(75, '12', 'Login: Cymone', '2026-05-12', '14:13:27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(76, '12', 'Login: Cymone', '2026-05-12', '20:15:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(77, '12', 'Login: Cymone', '2026-05-13', '05:21:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(78, '12', 'Logout', '2026-05-13', '07:22:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGOUT'),
(79, '12', 'Login: Cymone', '2026-05-13', '07:22:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'Cymone', 'LOGIN'),
(80, '12', 'Login: Cymone', '2026-05-13', '07:22:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN'),
(81, '12', 'Logout', '2026-05-13', '16:49:07', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGOUT'),
(82, '12', 'Login: Cymone', '2026-05-13', '16:49:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Cymone', 'LOGIN');

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

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_activity_log_details`
-- (See below for the actual view)
--
CREATE TABLE `v_activity_log_details` (
`id` bigint(20) unsigned
,`permit_id` int(10) unsigned
,`action` varchar(50)
,`details` text
,`user_id` int(10) unsigned
,`ip_address` varchar(45)
,`created_at` datetime
,`business_name` varchar(255)
,`owner_name` varchar(255)
,`permit_status` enum('Pending','Approved','Paid','Active','Expired','Rejected')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_permits_expiring_soon`
-- (See below for the actual view)
--
CREATE TABLE `v_permits_expiring_soon` (
`id` int(10) unsigned
,`business_name` varchar(255)
,`owner_name` varchar(255)
,`expiry_date` date
,`status` enum('Pending','Approved','Paid','Active','Expired','Rejected')
,`days_remaining` int(7)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_permits_statistics`
-- (See below for the actual view)
--
CREATE TABLE `v_permits_statistics` (
`total_permits` bigint(21)
,`pending_count` decimal(22,0)
,`approved_count` decimal(22,0)
,`paid_count` decimal(22,0)
,`active_count` decimal(22,0)
,`expired_count` decimal(22,0)
,`rejected_count` decimal(22,0)
,`total_fees_collected` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_permits_summary`
-- (See below for the actual view)
--
CREATE TABLE `v_permits_summary` (
`id` int(10) unsigned
,`business_name` varchar(255)
,`owner_name` varchar(255)
,`business_address` text
,`business_type` varchar(100)
,`permit_type` enum('New','Renewal','Amendment')
,`issue_date` date
,`expiry_date` date
,`status` enum('Pending','Approved','Paid','Active','Expired','Rejected')
,`fees_paid` decimal(10,2)
,`created_at` datetime
,`actual_status` varchar(8)
,`days_until_expiry` int(7)
);

-- --------------------------------------------------------

--
-- Structure for view `v_activity_log_details`
--
DROP TABLE IF EXISTS `v_activity_log_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_activity_log_details`  AS SELECT `al`.`id` AS `id`, `al`.`permit_id` AS `permit_id`, `al`.`action` AS `action`, `al`.`details` AS `details`, `al`.`user_id` AS `user_id`, `al`.`ip_address` AS `ip_address`, `al`.`created_at` AS `created_at`, `p`.`business_name` AS `business_name`, `p`.`owner_name` AS `owner_name`, `p`.`status` AS `permit_status` FROM (`activity_logs` `al` join `permits` `p` on(`p`.`id` = `al`.`permit_id`)) ORDER BY `al`.`created_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `v_permits_expiring_soon`
--
DROP TABLE IF EXISTS `v_permits_expiring_soon`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_permits_expiring_soon`  AS SELECT `p`.`id` AS `id`, `p`.`business_name` AS `business_name`, `p`.`owner_name` AS `owner_name`, `p`.`expiry_date` AS `expiry_date`, `p`.`status` AS `status`, to_days(`p`.`expiry_date`) - to_days(curdate()) AS `days_remaining` FROM `permits` AS `p` WHERE `p`.`deleted_at` is null AND `p`.`status` = 'Active' AND `p`.`expiry_date` between curdate() and curdate() + interval 30 day ORDER BY `p`.`expiry_date` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_permits_statistics`
--
DROP TABLE IF EXISTS `v_permits_statistics`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_permits_statistics`  AS SELECT count(0) AS `total_permits`, sum(case when `permits`.`status` = 'Pending' then 1 else 0 end) AS `pending_count`, sum(case when `permits`.`status` = 'Approved' then 1 else 0 end) AS `approved_count`, sum(case when `permits`.`status` = 'Paid' then 1 else 0 end) AS `paid_count`, sum(case when `permits`.`status` = 'Active' then 1 else 0 end) AS `active_count`, sum(case when `permits`.`status` = 'Expired' then 1 else 0 end) AS `expired_count`, sum(case when `permits`.`status` = 'Rejected' then 1 else 0 end) AS `rejected_count`, sum(`permits`.`fees_paid`) AS `total_fees_collected` FROM `permits` WHERE `permits`.`deleted_at` is null ;

-- --------------------------------------------------------

--
-- Structure for view `v_permits_summary`
--
DROP TABLE IF EXISTS `v_permits_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_permits_summary`  AS SELECT `p`.`id` AS `id`, `p`.`business_name` AS `business_name`, `p`.`owner_name` AS `owner_name`, `p`.`business_address` AS `business_address`, `p`.`business_type` AS `business_type`, `p`.`permit_type` AS `permit_type`, `p`.`issue_date` AS `issue_date`, `p`.`expiry_date` AS `expiry_date`, `p`.`status` AS `status`, `p`.`fees_paid` AS `fees_paid`, `p`.`created_at` AS `created_at`, CASE WHEN `p`.`status` = 'Active' AND `p`.`expiry_date` < curdate() THEN 'Expired' ELSE `p`.`status` END AS `actual_status`, to_days(`p`.`expiry_date`) - to_days(curdate()) AS `days_until_expiry` FROM `permits` AS `p` WHERE `p`.`deleted_at` is null ORDER BY `p`.`created_at` DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_permit_id` (`permit_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`);

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
-- Indexes for table `clearances`
--
ALTER TABLE `clearances`
  ADD PRIMARY KEY (`clearance_id`),
  ADD UNIQUE KEY `uq_control_number` (`control_number`),
  ADD KEY `idx_resident_id` (`resident_id`),
  ADD KEY `idx_clearance_type_id` (`clearance_type_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `clearance_types`
--
ALTER TABLE `clearance_types`
  ADD PRIMARY KEY (`clearance_type_id`),
  ADD UNIQUE KEY `uq_type_name` (`type_name`);

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
  ADD KEY `resident_id` (`resident_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_resident` (`resident_id`),
  ADD KEY `idx_date_assessed` (`date_assessed`);

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
  ADD KEY `idx_business_name` (`business_name`),
  ADD KEY `idx_owner_name` (`owner_name`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_expiry_date` (`expiry_date`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_is_blacklisted` (`is_blacklisted`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

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
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `barangay_events`
--
ALTER TABLE `barangay_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `clearances`
--
ALTER TABLE `clearances`
  MODIFY `clearance_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `clearance_types`
--
ALTER TABLE `clearance_types`
  MODIFY `clearance_type_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `households`
--
ALTER TABLE `households`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `indigents`
--
ALTER TABLE `indigents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permits`
--
ALTER TABLE `permits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  MODIFY `LOGID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`permit_id`) REFERENCES `permits` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `indigents`
--
ALTER TABLE `indigents`
  ADD CONSTRAINT `indigents_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
