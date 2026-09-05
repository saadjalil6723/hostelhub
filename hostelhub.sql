-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2026 at 10:05 AM
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
-- Database: `hostelhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'HostelHub Administrator', 'admin@hostelhub.test', '$2y$12$36bIYVCHxtEExNve/HsJG.06.FyJAu7ef/vvS0sluPbjmY9MW0QBy', 1, NULL, '2026-08-27 17:26:04', '2026-08-27 17:26:04');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Fahad Iqbal', 'fahad.iqbal@example.com', NULL, 'Room availability', 'Hi, do you have any single rooms available for next month? I need a move-in date around the 5th.', 'unread', '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(2, 'Mariam Yousaf', 'mariam.y@example.com', NULL, 'Visiting hours', 'Could you let me know your visiting hours policy for family members?', 'unread', '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(3, 'Kamran Sheikh', 'kamran.sheikh@example.com', '03211234567', 'Pricing for triple sharing', 'What is the monthly rate for a triple sharing room, and is a security deposit required?', 'read', '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(4, 'Nadia Chaudhry', 'nadia.c@example.com', NULL, 'Wi-Fi speed', 'What internet speed can residents expect? I work remotely and need something reliable.', 'read', '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(5, 'Talha Aslam', NULL, '03451234567', NULL, 'Is there parking available for residents with motorbikes?', 'unread', '2026-08-27 17:26:09', '2026-08-27 17:26:09');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `image`, `description`, `created_at`, `updated_at`) VALUES
(4, 'Dining Hall', 'placeholders/dining-hall-wttbog.svg', NULL, '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(5, 'Rooftop Seating', 'placeholders/rooftop-seating-eXTNMO.svg', NULL, '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(6, 'Reception', 'placeholders/reception-OU6XiI.svg', NULL, '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(7, 'Hallway', 'placeholders/hallway-B3pCP9.svg', NULL, '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(8, 'Laundry Area', 'placeholders/laundry-area-1vHJkN.svg', NULL, '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(9, 'Common Room', 'gallery/tjA5nTXnXZsgvAIIpWterAhlXJhA5LQhx82nSVIO.webp', NULL, '2026-08-27 17:30:51', '2026-08-27 17:30:51'),
(10, 'Study Loung', 'gallery/hwJ2bO6w4ArOKniwkubjvt47Hxv3S0G9K5et0XkJ.jpg', NULL, '2026-08-27 17:33:18', '2026-08-27 17:33:18'),
(11, 'Room Interior', 'gallery/nFq9ytr1EoVnDHMSrQUkKlYMiGDIlQWkBxAwL3qO.webp', NULL, '2026-08-27 17:36:25', '2026-08-27 17:36:25'),
(12, 'Dining Hall', 'gallery/UipJWFdpBqdDdses1xBBOwZ9YUvjsgzFjzNEfeFB.jpg', NULL, '2026-08-27 17:38:23', '2026-08-27 17:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_01_01_000001_create_admins_table', 1),
(2, '2026_01_01_000002_create_rooms_table', 1),
(3, '2026_01_01_000003_create_residents_table', 1),
(4, '2026_01_01_000004_create_room_allocations_table', 1),
(5, '2026_01_01_000005_create_services_table', 1),
(6, '2026_01_01_000006_create_gallery_table', 1),
(7, '2026_01_01_000007_create_contacts_table', 1),
(8, '2026_01_01_000008_create_payments_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resident_id` bigint(20) UNSIGNED NOT NULL,
  `room_allocation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `for_month` varchar(255) NOT NULL,
  `method` enum('cash','bank_transfer','card','mobile_wallet','other') NOT NULL DEFAULT 'cash',
  `reference_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `resident_id`, `room_allocation_id`, `amount`, `payment_date`, `for_month`, `method`, `reference_number`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 18000.00, '2026-05-03', '2026-05', 'cash', '6A9010BFED261', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(2, 1, 1, 18000.00, '2026-06-01', '2026-06', 'cash', '6A9010BFEFF91', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(3, 1, 1, 18000.00, '2026-07-05', '2026-07', 'mobile_wallet', '6A9010BFF062C', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(4, 1, 1, 18000.00, '2026-08-01', '2026-08', 'mobile_wallet', '6A9010BFF0C5D', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(5, 2, 2, 18000.00, '2026-07-01', '2026-07', 'cash', '6A9010C0004AB', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(6, 2, 2, 18000.00, '2026-08-04', '2026-08', 'cash', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(7, 3, 3, 13000.00, '2026-07-02', '2026-07', 'bank_transfer', '6A9010C0036D2', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(8, 3, 3, 13000.00, '2026-08-05', '2026-08', 'mobile_wallet', '6A9010C004F68', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(9, 4, 4, 13000.00, '2026-07-01', '2026-07', 'mobile_wallet', '6A9010C0070AF', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(10, 4, 4, 13000.00, '2026-08-03', '2026-08', 'mobile_wallet', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(11, 5, 5, 13000.00, '2026-07-01', '2026-07', 'bank_transfer', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(12, 5, 5, 13000.00, '2026-08-05', '2026-08', 'cash', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(13, 6, 6, 13000.00, '2026-05-02', '2026-05', 'bank_transfer', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(14, 6, 6, 13000.00, '2026-06-05', '2026-06', 'mobile_wallet', '6A9010C00DEDB', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(15, 6, 6, 13000.00, '2026-07-03', '2026-07', 'bank_transfer', '6A9010C00E6E3', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(16, 6, 6, 13000.00, '2026-08-03', '2026-08', 'bank_transfer', '6A9010C00EE46', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(17, 7, 7, 9500.00, '2026-07-03', '2026-07', 'mobile_wallet', '6A9010C011FA4', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(18, 7, 7, 9500.00, '2026-08-01', '2026-08', 'cash', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(19, 8, 8, 9500.00, '2026-07-04', '2026-07', 'mobile_wallet', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(20, 8, 8, 9500.00, '2026-08-04', '2026-08', 'mobile_wallet', '6A9010C015CE1', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(21, 9, 9, 9500.00, '2026-07-05', '2026-07', 'bank_transfer', '6A9010C018761', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(22, 9, 9, 9500.00, '2026-08-03', '2026-08', 'cash', '6A9010C018CE0', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(23, 10, 10, 9500.00, '2026-05-03', '2026-05', 'mobile_wallet', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(24, 10, 10, 9500.00, '2026-06-02', '2026-06', 'mobile_wallet', '6A9010C01C366', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(25, 10, 10, 9500.00, '2026-07-03', '2026-07', 'cash', '6A9010C01C9C6', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(26, 10, 10, 9500.00, '2026-08-03', '2026-08', 'bank_transfer', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(27, 11, 11, 9500.00, '2026-05-01', '2026-05', 'cash', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(28, 11, 11, 9500.00, '2026-06-02', '2026-06', 'cash', '6A9010C02088A', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(29, 11, 11, 9500.00, '2026-07-03', '2026-07', 'mobile_wallet', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(30, 11, 11, 9500.00, '2026-08-01', '2026-08', 'cash', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(31, 12, 12, 9500.00, '2026-06-04', '2026-06', 'mobile_wallet', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(32, 12, 12, 9500.00, '2026-07-04', '2026-07', 'cash', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(33, 12, 12, 9500.00, '2026-08-03', '2026-08', 'bank_transfer', '6A9010C025208', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(34, 13, 13, 14000.00, '2026-05-02', '2026-05', 'cash', '6A9010C0287BA', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(35, 13, 13, 14000.00, '2026-06-01', '2026-06', 'mobile_wallet', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(36, 13, 13, 14000.00, '2026-07-01', '2026-07', 'bank_transfer', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(37, 13, 13, 14000.00, '2026-08-01', '2026-08', 'bank_transfer', '6A9010C029A9D', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(38, 14, 14, 14000.00, '2026-07-05', '2026-07', 'mobile_wallet', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(39, 14, 14, 14000.00, '2026-08-05', '2026-08', 'mobile_wallet', '6A9010C02D189', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(40, 15, 15, 19500.00, '2026-07-03', '2026-07', 'cash', '6A9010C030282', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(41, 15, 15, 19500.00, '2026-08-04', '2026-08', 'bank_transfer', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(42, 16, 16, 8000.00, '2026-06-01', '2026-06', 'mobile_wallet', '6A9010C033A8D', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(43, 16, 16, 8000.00, '2026-07-05', '2026-07', 'cash', '6A9010C03400F', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(44, 16, 16, 8000.00, '2026-08-03', '2026-08', 'bank_transfer', '6A9010C034580', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(45, 17, 17, 8000.00, '2026-06-04', '2026-06', 'mobile_wallet', '6A9010C037A1C', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(46, 17, 17, 8000.00, '2026-07-01', '2026-07', 'bank_transfer', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(47, 17, 17, 8000.00, '2026-08-04', '2026-08', 'mobile_wallet', '6A9010C03852B', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(48, 18, 18, 8000.00, '2026-05-05', '2026-05', 'cash', '6A9010C03B839', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(49, 18, 18, 8000.00, '2026-06-02', '2026-06', 'bank_transfer', '6A9010C03BE32', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(50, 18, 18, 8000.00, '2026-07-04', '2026-07', 'mobile_wallet', '6A9010C03C3A8', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(51, 18, 18, 8000.00, '2026-08-01', '2026-08', 'cash', '6A9010C03C8EB', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(52, 19, 19, 8000.00, '2026-07-01', '2026-07', 'mobile_wallet', '6A9010C03F9CB', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(53, 19, 19, 8000.00, '2026-08-04', '2026-08', 'mobile_wallet', NULL, NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(54, 20, 20, 8000.00, '2026-07-01', '2026-07', 'cash', '6A9010C043555', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(55, 20, 20, 8000.00, '2026-08-01', '2026-08', 'cash', '6A9010C043ADF', NULL, '2026-08-27 17:26:08', '2026-08-27 17:26:08');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `identification_number` varchar(255) DEFAULT NULL,
  `id_type` enum('cnic','passport','other') NOT NULL DEFAULT 'cnic',
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `status` enum('active','checked_out','inactive') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `name`, `guardian_name`, `identification_number`, `id_type`, `phone`, `email`, `address`, `emergency_contact`, `check_in_date`, `check_out_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Ahmed Raza', 'Muhammad Raza', '35201-1234567-1', 'cnic', '03001234567', 'ahmed.raza@example.com', 'Lahore, Punjab, Pakistan', '03119343980', '2026-06-27', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(2, 'Bilal Hussain', 'Iftikhar Hussain', '35202-2345678-2', 'cnic', '03011234567', 'bilal.hussain@example.com', 'Lahore, Punjab, Pakistan', '03115557306', '2026-06-27', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(3, 'Zainab Fatima', 'Tariq Mehmood', '35203-3456789-3', 'cnic', '03021234567', 'zainab.fatima@example.com', 'Lahore, Punjab, Pakistan', '03115639619', '2026-06-27', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(4, 'Hassan Ali', 'Anwar Ali', '35204-4567890-4', 'cnic', '03031234567', 'hassan.ali@example.com', 'Lahore, Punjab, Pakistan', '03119843650', '2026-07-27', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(5, 'Sara Khan', 'Imran Khan', '35205-5678901-5', 'cnic', '03041234567', 'sara.khan@example.com', 'Lahore, Punjab, Pakistan', '03111208322', '2026-07-27', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(6, 'John Miller', NULL, 'US4487213', 'passport', '03051234567', 'john.miller@example.com', 'Lahore, Punjab, Pakistan', '03118944691', '2026-04-27', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(7, 'Ayesha Siddiqui', 'Rashid Siddiqui', '35206-6789012-6', 'cnic', '03061234567', 'ayesha.siddiqui@example.com', 'Lahore, Punjab, Pakistan', '03115307178', '2026-04-27', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(8, 'Umer Farooq', 'Farooq Ahmed', '35207-7890123-7', 'cnic', '03071234567', 'umer.farooq@example.com', 'Lahore, Punjab, Pakistan', '03113281122', '2026-03-27', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(9, 'Eleanore Connelly', 'Antonia Raynor', '00001-0001001-1', 'cnic', '03000000001', 'fjohnson@example.com', '43004 Sawayn Underpass Suite 415\nAyanahaven, ME 62578-1237', '03100000001', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(10, 'Dr. Stanton Larkin I', 'Joey Bergnaum', '00002-0001002-2', 'cnic', '03000000002', 'hope76@example.net', '4981 Keon Inlet Apt. 117\nGuyhaven, SC 18259-1567', '03100000002', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(11, 'Dr. Muhammad Luettgen', 'Dr. Janick Ritchie', '00003-0001003-3', 'cnic', '03000000003', 'oschumm@example.net', '581 Christ Shore\nMacejkovictown, AL 41003', '03100000003', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(12, 'Mr. Walker Cassin PhD', 'Dr. Michaela King', '00004-0001004-4', 'cnic', '03000000004', 'noemie.waters@example.net', '21780 Delbert Wells\nChristianfort, MT 07407', '03100000004', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(13, 'Barbara Tromp Jr.', 'Prof. Lucio Hammes', '00005-0001005-5', 'cnic', '03000000005', 'denesik.geovanny@example.org', '768 Pete Vista\nWillmschester, TX 63832-6704', '03100000005', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(14, 'Katrine Schulist', 'Barton Nicolas', '00006-0001006-6', 'cnic', '03000000006', 'qsmith@example.org', '765 River Dam Apt. 535\nSouth Alessiashire, NJ 61382-7909', '03100000006', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(15, 'Imelda Simonis', 'Leatha Frami III', '00007-0001007-7', 'cnic', '03000000007', 'lewis.cronin@example.com', '643 Alan Burgs Apt. 297\nWest Kirsten, UT 74055-3831', '03100000007', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(16, 'Ruthe Kiehn', 'Tristin Koss', '00008-0001008-8', 'cnic', '03000000008', 'gleichner.shannon@example.org', '2506 Archibald Falls\nEast Alyshaburgh, OK 60641-5036', '03100000008', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(17, 'Kailee Swaniawski', 'Kaylah Kub', '00009-0001009-9', 'cnic', '03000000009', 'langosh.alexandrine@example.com', '65452 Timmy Mews Apt. 027\nPort Antonio, KY 10472-8640', '03100000009', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(18, 'Daphne Bergstrom Sr.', 'Miss Mia VonRueden', '00010-0001010-0', 'cnic', '03000000010', 'eloy.predovic@example.net', '809 Koelpin Rapid Apt. 281\nLehnerfurt, HI 84874-5151', '03100000010', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(19, 'Edna Koss', 'Ralph Steuber', '00011-0001011-1', 'cnic', '03000000011', 'mcclure.jabari@example.com', '63040 Krajcik Parkway\nHaagshire, WI 50085', '03100000011', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(20, 'Dr. Everette Hoeger', 'Mrs. Sibyl Deckow', '00012-0001012-2', 'cnic', '03000000012', 'lebsack.darius@example.net', '70743 Homenick Passage Apt. 136\nBechtelarchester, KS 69974', '03100000012', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(21, 'Naomi Mante III', 'Carlotta McDermott', '00013-0001013-3', 'cnic', '03000000013', 'jasen44@example.org', '98967 Karina Camp Suite 445\nPort Jaycee, WY 45443', '03100000013', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(22, 'Janessa Pfannerstill II', 'Ricardo Lebsack', '00014-0001014-4', 'cnic', '03000000014', 'michaela16@example.com', '798 Oberbrunner Cove\nLake Tyresefurt, NJ 95130-4886', '03100000014', '2026-08-17', NULL, 'active', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(23, 'Drew Towne III', 'Monica Cremin II', '00015-0001015-5', 'cnic', '03000000015', 'hauck.judd@example.org', '182 Abelardo Key Suite 833\nVonRuedenfurt, FL 19094-4131', '03100000015', '2025-12-27', '2026-08-07', 'checked_out', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(24, 'Tanya Pagac', 'Jacques Hayes', '00016-0001016-6', 'cnic', '03000000016', 'elton36@example.com', '542 Trudie Point\nNoraburgh, CT 77651-5294', '03100000016', '2025-12-27', '2026-08-07', 'checked_out', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(25, 'Dolly Hahn', 'Clark Greenfelder', '00017-0001017-7', 'cnic', '03000000017', 'okub@example.com', '9847 Darwin Falls\nLake Leonormouth, MO 86865', '03100000017', '2025-12-27', '2026-08-07', 'checked_out', NULL, '2026-08-27 17:26:07', '2026-08-27 17:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_number` varchar(255) NOT NULL,
  `floor` varchar(255) DEFAULT NULL,
  `room_type` varchar(255) NOT NULL,
  `capacity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `current_occupancy` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('available','partially_occupied','full','maintenance') NOT NULL DEFAULT 'available',
  `facilities` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `floor`, `room_type`, `capacity`, `current_occupancy`, `price`, `status`, `facilities`, `description`, `created_at`, `updated_at`) VALUES
(1, 'A101', 'Ground', 'Single', 1, 1, 18000.00, 'full', 'AC, Attached Bath, Wi-Fi', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(2, 'A102', 'Ground', 'Single', 1, 1, 18000.00, 'full', 'AC, Attached Bath, Wi-Fi', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(3, 'A103', 'Ground', 'Double Sharing', 2, 2, 13000.00, 'full', 'Fan, Shared Bath, Wi-Fi', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:08'),
(4, 'A104', 'Ground', 'Double Sharing', 2, 2, 13000.00, 'full', 'Fan, Shared Bath, Wi-Fi', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:08'),
(5, 'B201', '1st', 'Triple Sharing', 3, 3, 9500.00, 'full', 'Fan, Shared Bath', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:08'),
(6, 'B202', '1st', 'Triple Sharing', 3, 3, 9500.00, 'full', 'Fan, Shared Bath', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:08'),
(7, 'B203', '1st', 'Double Sharing', 2, 2, 14000.00, 'full', 'AC, Attached Bath, Wi-Fi', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:08'),
(8, 'B204', '1st', 'Single', 1, 1, 19500.00, 'full', 'AC, Attached Bath, Wi-Fi, Balcony', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:08'),
(9, 'C301', '2nd', 'Quad Sharing', 4, 4, 8000.00, 'full', 'Fan, Shared Bath', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:08'),
(10, 'C302', '2nd', 'Quad Sharing', 4, 1, 8000.00, 'partially_occupied', 'Fan, Shared Bath', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:08'),
(11, 'C303', '2nd', 'Double Sharing', 2, 0, 13500.00, 'available', 'AC, Shared Bath, Wi-Fi', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(12, 'C304', '2nd', 'Single', 1, 0, 20000.00, 'maintenance', 'AC, Attached Bath, Wi-Fi', 'Comfortable, well-ventilated room with regular housekeeping.', '2026-08-27 17:26:07', '2026-08-27 17:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `room_allocations`
--

CREATE TABLE `room_allocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resident_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `bed_number` varchar(255) DEFAULT NULL,
  `allocation_date` date NOT NULL,
  `checkout_date` date DEFAULT NULL,
  `status` enum('active','ended') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_allocations`
--

INSERT INTO `room_allocations` (`id`, `resident_id`, `room_id`, `bed_number`, `allocation_date`, `checkout_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'A', '2026-06-27', NULL, 'active', '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(2, 2, 2, 'A', '2026-06-27', NULL, 'active', '2026-08-27 17:26:07', '2026-08-27 17:26:07'),
(3, 3, 3, 'A', '2026-06-27', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(4, 4, 3, 'B', '2026-07-27', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(5, 5, 4, 'A', '2026-07-27', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(6, 6, 4, 'B', '2026-04-27', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(7, 7, 5, 'A', '2026-04-27', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(8, 8, 5, 'B', '2026-03-27', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(9, 9, 5, 'C', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(10, 10, 6, 'A', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(11, 11, 6, 'B', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(12, 12, 6, 'C', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(13, 13, 7, 'A', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(14, 14, 7, 'B', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(15, 15, 8, 'A', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(16, 16, 9, 'A', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(17, 17, 9, 'B', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(18, 18, 9, 'C', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(19, 19, 9, 'D', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(20, 20, 10, 'A', '2026-08-17', NULL, 'active', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(21, 23, 1, 'A', '2025-12-27', '2026-08-07', 'ended', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(22, 24, 2, 'A', '2025-12-27', '2026-08-07', 'ended', '2026-08-27 17:26:08', '2026-08-27 17:26:08'),
(23, 25, 3, 'A', '2025-12-27', '2026-08-07', 'ended', '2026-08-27 17:26:08', '2026-08-27 17:26:08');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'High-Speed Wi-Fi', 'Fibre-backed wireless internet available throughout the building, including all common areas.', 'placeholders/high-speed-wi-fi-bbBLci.svg', 1, '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(2, 'Laundry Service', 'Twice-weekly laundry pickup and delivery included in every room package.', 'placeholders/laundry-service-HLqIDu.svg', 1, '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(3, '24/7 Security', 'CCTV coverage and on-site security staff around the clock.', 'placeholders/247-security-QHcRpp.svg', 1, '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(4, 'Dining Hall', 'Three meals a day, with a rotating weekly menu and dietary options.', 'placeholders/dining-hall-10ZlvR.svg', 1, '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(5, 'Study Lounge', 'A quiet, well-lit common area for studying or working, open late.', 'placeholders/study-lounge-lVe9QX.svg', 1, '2026-08-27 17:26:09', '2026-08-27 17:26:09'),
(6, 'Housekeeping', 'Regular room cleaning and common-area upkeep, scheduled weekly.', 'placeholders/housekeeping-oUBcIJ.svg', 1, '2026-08-27 17:26:09', '2026-08-27 17:26:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_status_index` (`status`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_room_allocation_id_foreign` (`room_allocation_id`),
  ADD KEY `payments_resident_id_for_month_index` (`resident_id`,`for_month`),
  ADD KEY `payments_payment_date_index` (`payment_date`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `residents_identification_number_unique` (`identification_number`),
  ADD KEY `residents_status_index` (`status`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rooms_room_number_unique` (`room_number`),
  ADD KEY `rooms_status_index` (`status`);

--
-- Indexes for table `room_allocations`
--
ALTER TABLE `room_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_allocations_room_id_status_index` (`room_id`,`status`),
  ADD KEY `room_allocations_resident_id_status_index` (`resident_id`,`status`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `room_allocations`
--
ALTER TABLE `room_allocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`),
  ADD CONSTRAINT `payments_room_allocation_id_foreign` FOREIGN KEY (`room_allocation_id`) REFERENCES `room_allocations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `room_allocations`
--
ALTER TABLE `room_allocations`
  ADD CONSTRAINT `room_allocations_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`),
  ADD CONSTRAINT `room_allocations_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
