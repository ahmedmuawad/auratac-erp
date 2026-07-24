-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 24, 2026 at 03:24 PM
-- Server version: 8.0.43-0ubuntu0.22.04.1
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auratac-splus`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('aura-tac-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1782725520),
('aura-tac-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1782725520;', 1782725520),
('aura-tac-cache-setting_footer_text', 's:30:\"تصميم وتطوير S-Plus\";', 2097486160),
('aura-tac-cache-setting_logo_path', 's:61:\"storage/branding/19IEqFAZoHn3WNUJ7rJnpS36FR7Nno3BqQ8PkX4B.png\";', 2097486160),
('aura-tac-cache-setting_sms_mode', 's:4:\"test\";', 2097686522),
('aura-tac-cache-setting_system_name', 's:8:\"AURA TAC\";', 2097486160),
('aura-tac-cache-setting_system_name_en', 's:18:\"Maintenance System\";', 2097486160),
('aura-tac-cache-setting_terms_conditions', 's:237:\"1. المركز غير مسؤول عن أي ذخيرة متبقية في السلاح.\\n2. يتم استلام السلاح بموجب هذا الكرت.\\n3. أقصى مدة بقاء للسلاح بعد الإصلاح هي 30 يوماً.\";', 2097486187),
('aura-tac-cache-setting_twilio_from', 's:0:\"\";', 2097686522),
('aura-tac-cache-setting_twilio_sid', 's:0:\"\";', 2097686522),
('aura-tac-cache-setting_twilio_token', 's:0:\"\";', 2097686522),
('aura-tac-cache-setting_whatsapp_api_key', 's:21:\"Ahmed_Strong_Key_2026\";', 2097486187),
('aura-tac-cache-setting_whatsapp_api_url', 's:27:\"https://wa.stop4web.online/\";', 2097486187),
('aura-tac-cache-setting_whatsapp_country_code', 's:3:\"966\";', 2097486187),
('aura-tac-cache-setting_whatsapp_enabled', 's:1:\"1\";', 2097486187),
('aura-tac-cache-setting_whatsapp_instance', 's:17:\"tenant_stop-group\";', 2097486187),
('aura-tac-cache-setting_whatsapp_min_gap_seconds', 'i:4;', 2097486187),
('aura-tac-cache-setting_whatsapp_token', 's:36:\"896BCA0C-CEA5-4CFA-8E9C-705CEC505E9D\";', 2097486187),
('aura-tac-cache-whatsapp:last_sent', 'd:1784368832.89492;', 1784368952);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `national_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `national_id`, `phone`, `address`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'محمد بن عبدالله الغامدي', '2839714088', '0501234500', 'جدة - حي الشاطئ', '', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(2, 'فهد بن سعد المالكي', '2992295327', '0501234501', 'بريدة - حي الروضة', '', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(3, 'تركي بن ناصر العنزي', '1326676765', '0501234502', 'تبوك - حي الروضة', '', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(4, 'عبدالرحمن بن علي الزهراني', '2717144525', '0501234503', 'مكة المكرمة - حي العزيزية', 'عميل دائم', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(5, 'سلطان بن فهد القرني', '2135286421', '0501234504', 'الدمام - حي الروضة', 'لديه أكثر من قطعة', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(6, 'بندر بن خالد السبيعي', '2006391366', '0501234505', 'المدينة المنورة - حي الروضة', 'عميل دائم', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(7, 'ناصر بن محمد الحربي', '1317804160', '0501234506', 'تبوك - حي الملقا', 'لديه أكثر من قطعة', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(8, 'يوسف بن إبراهيم الشهري', '2714365793', '0501234507', 'أبها - حي الملقا', 'يفضل التواصل مساءً', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(9, 'عبدالله بن سعود المطيري', '2665363856', '0501234508', 'الخبر - حي النرجس', 'عميل دائم', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(10, 'فيصل بن تركي البقمي', '2734848407', '0501234509', 'الرياض - حي الشاطئ', '', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(11, 'ماجد بن عبدالعزيز الرشيدي', '1123343528', '0501234510', 'بريدة - حي الشاطئ', 'لديه أكثر من قطعة', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(12, 'سعود بن منصور الخالدي', '1067424878', '0501234511', 'حائل - حي الياسمين', '', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(13, 'راكان بن وليد الجهني', '1306073383', '0501234512', 'حائل - حي الروضة', 'يفضل التواصل مساءً', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(14, 'عمر بن صالح الأحمدي', '1985471838', '0501234513', 'تبوك - حي الروضة', 'لديه أكثر من قطعة', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(15, 'خالد بن عبدالمحسن الفيفي', '1338525519', '0501234514', 'مكة المكرمة - حي الياسمين', '', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(16, 'احمد معوض', '123456798', '01030889618', 'تست', 'تست', '2026-06-22 10:14:50', '2026-06-22 10:16:23');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `item_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufacturer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specs` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `customer_id`, `item_number`, `type`, `manufacturer`, `license_number`, `specs`, `created_at`, `updated_at`) VALUES
(1, 1, 'SN-1001', 'كاربين', 'Remington', 'LIC-88520', 'عيار 9mm', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(2, 1, 'SN-1002', 'مسدس', 'Glock', 'LIC-62288', 'عيار 5.56', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(3, 2, 'SN-1003', 'شوزن', 'Beretta', 'LIC-37712', 'عيار 9mm', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(4, 3, 'SN-1004', 'شوزن', 'Remington', 'LIC-22690', 'عيار 12G', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(5, 3, 'SN-1005', 'بندقية صيد', 'Heckler & Koch', 'LIC-99267', 'عيار .22', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(6, 4, 'SN-1006', 'بندقية صيد', 'Browning', 'LIC-70758', 'عيار 5.56', '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(7, 4, 'SN-1007', 'شوزن', 'Heckler & Koch', 'LIC-72551', 'عيار .22', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(8, 5, 'SN-1008', 'كاربين', 'Taurus', 'LIC-11421', 'عيار .45', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(9, 5, 'SN-1009', 'بندقية صيد', 'Heckler & Koch', 'LIC-25804', 'عيار .22', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(10, 6, 'SN-1010', 'شوزن', 'Sig Sauer', 'LIC-54282', 'عيار 9mm', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(11, 6, 'SN-1011', 'شوزن', 'Benelli', 'LIC-73814', 'عيار 5.56', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(12, 7, 'SN-1012', 'بندقية', 'Browning', 'LIC-77379', 'عيار 5.56', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(13, 8, 'SN-1013', 'بندقية صيد', 'Sig Sauer', 'LIC-46926', 'عيار 12G', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(14, 9, 'SN-1014', 'بندقية صيد', 'Glock', 'LIC-61171', 'عيار 5.56', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(15, 10, 'SN-1015', 'كاربين', 'Beretta', 'LIC-38867', 'عيار 5.56', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(16, 11, 'SN-1016', 'شوزن', 'Beretta', 'LIC-60371', 'عيار .45', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(17, 11, 'SN-1017', 'بندقية', 'CZ', 'LIC-80338', 'عيار .22', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(18, 12, 'SN-1018', 'بندقية', 'Benelli', 'LIC-76398', 'عيار 5.56', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(19, 12, 'SN-1019', 'بندقية', 'Glock', 'LIC-36311', 'عيار 5.56', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(20, 13, 'SN-1020', 'كاربين', 'Heckler & Koch', 'LIC-31930', 'عيار .22', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(21, 14, 'SN-1021', 'شوزن', 'Sig Sauer', 'LIC-96791', 'عيار 9mm', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(22, 15, 'SN-1022', 'شوزن', 'Remington', 'LIC-54682', 'عيار 9mm', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(23, 15, 'SN-1023', 'بندقية صيد', 'Benelli', 'LIC-15970', 'عيار 9mm', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(26, 16, 'SN-1234', 'مسدس', 'test whatsapp', 'LIC-1234', 'test whatsapp', '2026-06-22 10:34:50', '2026-06-22 10:34:50'),
(27, 16, 'test-00001', 'test', 'test', 'test', NULL, '2026-07-18 09:55:51', '2026-07-18 09:55:51');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_cards`
--

CREATE TABLE `maintenance_cards` (
  `id` bigint UNSIGNED NOT NULL,
  `card_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `item_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_id` bigint UNSIGNED NOT NULL,
  `repair_requests` json DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `expected_cost_labor` decimal(10,2) NOT NULL DEFAULT '0.00',
  `expected_cost_parts` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','in_progress','waiting_parts','ready_for_qa','ready','delivered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `final_labor_cost` decimal(10,2) DEFAULT NULL,
  `final_parts_cost` decimal(10,2) DEFAULT NULL,
  `final_total_cost` decimal(10,2) DEFAULT NULL,
  `delivery_notes` text COLLATE utf8mb4_unicode_ci,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `remaining_amount` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenance_cards`
--

INSERT INTO `maintenance_cards` (`id`, `card_number`, `customer_id`, `item_id`, `item_image`, `receiver_id`, `repair_requests`, `admin_notes`, `expected_cost_labor`, `expected_cost_parts`, `total_cost`, `status`, `delivered_at`, `created_at`, `updated_at`, `final_labor_cost`, `final_parts_cost`, `final_total_cost`, `delivery_notes`, `payment_status`, `paid_amount`, `remaining_amount`) VALUES
(1, 'BRQ-2026-1001', 9, 14, NULL, 2, '[\"حفر اسم العميل مع الشعار\"]', '', 550.00, 1500.00, 2050.00, 'pending', NULL, '2026-05-02 09:31:00', '2026-05-02 09:31:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(2, 'BRQ-2026-1002', 13, 20, NULL, 2, '[\"صيانة وتنظيف شامل\", \"تركيب اكسسوارات\", \"تغيير مقابض\"]', 'العميل مستعجل', 100.00, 1200.00, 1300.00, 'pending', NULL, '2026-06-09 09:38:00', '2026-06-09 09:38:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(3, 'BRQ-2026-1003', 4, 6, NULL, 2, '[\"تغيير مقابض\"]', 'العميل مستعجل', 700.00, 750.00, 1450.00, 'pending', NULL, '2026-04-30 17:43:00', '2026-04-30 17:43:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(4, 'BRQ-2026-1004', 5, 8, NULL, 2, '[\"حفر اسم العميل مع الشعار\", \"معايرة الماسورة\"]', 'القطعة بحالة جيدة', 750.00, 850.00, 1600.00, 'pending', NULL, '2026-05-29 17:25:00', '2026-05-29 17:25:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(5, 'BRQ-2026-1005', 15, 23, NULL, 2, '[\"تركيب اكسسوارات\", \"تغيير مقابض\", \"حفر اسم العميل مع الشعار\"]', 'يوجد صدأ بسيط', 500.00, 950.00, 1450.00, 'in_progress', NULL, '2026-06-14 18:02:00', '2026-06-14 18:02:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(6, 'BRQ-2026-1006', 6, 10, NULL, 2, '[\"صيانة وتنظيف شامل\", \"تركيب اكسسوارات\", \"تغيير مقابض\"]', '', 300.00, 500.00, 800.00, 'in_progress', NULL, '2026-05-04 11:54:00', '2026-05-04 11:54:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(7, 'BRQ-2026-1007', 5, 9, NULL, 2, '[\"صيانة وتنظيف شامل\", \"تغيير مقابض\", \"حفر اسم العميل مع الشعار\"]', '', 750.00, 1050.00, 1800.00, 'in_progress', NULL, '2026-06-02 16:37:00', '2026-06-02 16:37:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(8, 'BRQ-2026-1008', 5, 9, NULL, 2, '[\"حفر اسم العميل مع الشعار\"]', '', 500.00, 200.00, 700.00, 'in_progress', NULL, '2026-05-14 17:43:00', '2026-05-14 17:43:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(9, 'BRQ-2026-1009', 1, 1, NULL, 2, '[\"تركيب اكسسوارات\"]', 'العميل مستعجل', 700.00, 950.00, 1650.00, 'ready_for_qa', NULL, '2026-06-14 12:14:00', '2026-06-24 18:38:16', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(10, 'BRQ-2026-1010', 11, 17, NULL, 2, '[\"تركيب اكسسوارات\", \"تغيير مقابض\", \"حفر اسم العميل مع الشعار\"]', 'القطعة بحالة جيدة', 700.00, 1250.00, 1950.00, 'waiting_parts', NULL, '2026-06-12 17:17:00', '2026-06-12 17:17:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(11, 'BRQ-2026-1011', 5, 8, NULL, 2, '[\"تركيب اكسسوارات\", \"تغيير مقابض\", \"حفر اسم العميل مع الشعار\", \"استبدال نابض\"]', '', 300.00, 1100.00, 1400.00, 'waiting_parts', NULL, '2026-05-16 17:19:00', '2026-05-16 17:19:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(12, 'BRQ-2026-1012', 10, 15, NULL, 2, '[\"تغيير مقابض\", \"حفر اسم العميل مع الشعار\"]', 'يوجد صدأ بسيط', 450.00, 400.00, 850.00, 'waiting_parts', NULL, '2026-05-07 17:24:00', '2026-05-07 17:24:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(13, 'BRQ-2026-1013', 4, 7, NULL, 2, '[\"تركيب اكسسوارات\"]', '', 550.00, 450.00, 1000.00, 'ready_for_qa', NULL, '2026-04-22 16:40:00', '2026-04-22 16:40:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(14, 'BRQ-2026-1014', 5, 9, NULL, 2, '[\"صيانة وتنظيف شامل\"]', '', 750.00, 1050.00, 1800.00, 'ready_for_qa', NULL, '2026-04-23 10:44:00', '2026-04-23 10:44:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(15, 'BRQ-2026-1015', 12, 18, NULL, 2, '[\"صيانة وتنظيف شامل\", \"تركيب اكسسوارات\", \"حفر اسم العميل مع الشعار\"]', '', 400.00, 1050.00, 1450.00, 'ready_for_qa', NULL, '2026-05-19 16:14:00', '2026-05-19 16:14:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(16, 'BRQ-2026-1016', 15, 23, NULL, 2, '[\"تركيب اكسسوارات\", \"حفر اسم العميل مع الشعار\", \"ضبط مؤشر التصويب\"]', 'العميل مستعجل', 450.00, 700.00, 1150.00, 'ready_for_qa', NULL, '2026-05-17 18:05:00', '2026-05-17 18:05:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(17, 'BRQ-2026-1017', 15, 22, NULL, 2, '[\"تركيب اكسسوارات\", \"حفر اسم العميل مع الشعار\"]', 'يوجد صدأ بسيط', 200.00, 650.00, 850.00, 'delivered', '2026-06-22 10:11:50', '2026-05-27 09:01:00', '2026-06-22 10:11:50', 200.00, 650.00, 850.00, NULL, 'paid', 850.00, 0.00),
(18, 'BRQ-2026-1018', 15, 22, NULL, 2, '[\"تغيير مقابض\"]', 'العميل مستعجل', 400.00, 650.00, 1050.00, 'ready', NULL, '2026-06-17 17:52:00', '2026-06-17 17:52:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(19, 'BRQ-2026-1019', 1, 1, NULL, 2, '[\"تغيير مقابض\"]', '', 450.00, 550.00, 1000.00, 'ready', NULL, '2026-06-15 11:59:00', '2026-06-15 11:59:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(20, 'BRQ-2026-1020', 8, 13, NULL, 2, '[\"صيانة وتنظيف شامل\", \"تغيير مقابض\", \"تلميع وتزييت\"]', 'القطعة بحالة جيدة', 600.00, 1300.00, 1900.00, 'ready', NULL, '2026-05-22 13:49:00', '2026-05-22 13:49:00', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(21, 'BRQ-2026-1021', 11, 16, NULL, 2, '[\"تركيب اكسسوارات\", \"حفر اسم العميل مع الشعار\"]', '', 800.00, 100.00, 900.00, 'delivered', '2026-06-13 09:08:00', '2026-06-03 09:08:00', '2026-07-18 10:05:19', 800.00, 200.00, 1000.00, '', 'paid', 1000.00, 0.00),
(22, 'BRQ-2026-1022', 12, 19, NULL, 2, '[\"حفر اسم العميل مع الشعار\", \"استبدال نابض\"]', 'العميل مستعجل', 550.00, 400.00, 950.00, 'delivered', '2026-05-24 15:40:00', '2026-05-18 15:40:00', '2026-06-21 15:47:55', 500.00, 350.00, 850.00, 'ضمان 30 يوم', 'paid', 850.00, 0.00),
(23, 'BRQ-2026-1023', 1, 2, NULL, 2, '[\"صيانة وتنظيف شامل\", \"تغيير مقابض\"]', 'العميل مستعجل', 800.00, 1300.00, 2100.00, 'delivered', '2026-05-22 12:25:00', '2026-05-20 12:25:00', '2026-06-21 15:47:55', 900.00, 1400.00, 2300.00, 'تم التسليم للعميل', 'partial', 1150.00, 1150.00),
(24, 'BRQ-2026-1024', 3, 5, NULL, 2, '[\"تركيب اكسسوارات\", \"تغيير مقابض\", \"حفر اسم العميل مع الشعار\"]', 'القطعة بحالة جيدة', 100.00, 1400.00, 1500.00, 'delivered', '2026-05-29 14:02:00', '2026-05-20 14:02:00', '2026-07-18 10:05:33', 50.00, 1350.00, 1400.00, 'ضمان 30 يوم', 'paid', 1400.00, 0.00),
(25, 'BRQ-2026-1025', 5, 8, NULL, 2, '[\"تركيب اكسسوارات\"]', 'العميل مستعجل', 400.00, 200.00, 600.00, 'delivered', '2026-05-02 17:07:00', '2026-04-29 17:07:00', '2026-06-21 15:47:55', 500.00, 100.00, 600.00, '', 'paid', 600.00, 0.00),
(26, 'BRQ-2026-1026', 11, 17, NULL, 2, '[\"صيانة وتنظيف شامل\", \"تركيب اكسسوارات\", \"تغيير مقابض\"]', '', 150.00, 0.00, 150.00, 'delivered', '2026-05-30 11:15:00', '2026-05-23 11:15:00', '2026-06-21 15:47:55', 150.00, 0.00, 150.00, 'تم التسليم للعميل', 'paid', 150.00, 0.00),
(27, 'BRQ-2026-1027', 3, 5, NULL, 2, '[\"تركيب اكسسوارات\", \"حفر اسم العميل مع الشعار\", \"معايرة الماسورة\"]', 'العميل مستعجل', 250.00, 850.00, 1100.00, 'delivered', '2026-05-23 17:25:00', '2026-05-15 17:25:00', '2026-06-21 15:47:55', 250.00, 950.00, 1200.00, 'تم التسليم للعميل', 'paid', 1200.00, 0.00),
(28, 'BRQ-2026-1028', 6, 11, NULL, 2, '[\"تغيير مقابض\", \"حفر اسم العميل مع الشعار\"]', '', 500.00, 800.00, 1300.00, 'delivered', '2026-06-09 18:32:00', '2026-05-31 18:32:00', '2026-06-21 15:57:54', 550.00, 850.00, 1400.00, 'تم التسليم للعميل', 'paid', 1400.00, 0.00),
(29, 'BRQ-2026-1029', 6, 10, NULL, 2, '[\"تركيب اكسسوارات\"]', '', 450.00, 1050.00, 1500.00, 'delivered', '2026-06-19 12:48:00', '2026-06-09 12:48:00', '2026-07-18 09:23:00', 400.00, 1150.00, 1550.00, '', 'paid', 1550.00, 0.00),
(32, 'BRQ-2026-1030', 16, 26, NULL, 1, '[\"صيانة وتنظيف شامل\", \"تركيب اكسسوارات\", \"تست\"]', '', 10.00, 10.00, 20.00, 'delivered', '2026-06-22 10:54:25', '2026-06-22 10:35:10', '2026-06-22 11:02:46', 10.00, 10.00, 20.00, NULL, 'paid', 20.00, 0.00),
(33, 'BRQ-2026-1031', 16, 26, NULL, 1, '[\"تركيب اكسسوارات\", \"تغيير مقابض\", \"تست\"]', '', 0.00, 0.00, 0.00, 'ready', NULL, '2026-06-22 11:03:07', '2026-06-22 11:03:45', NULL, NULL, NULL, NULL, 'unpaid', 0.00, 0.00),
(34, 'BRQ-2026-1032', 16, 27, NULL, 1, '[\"صيانة وتنظيف شامل\", \"تركيب اكسسوارات\"]', NULL, 100.00, 300.00, 400.00, 'delivered', '2026-07-18 10:00:32', '2026-07-18 09:55:51', '2026-07-18 10:00:32', 100.00, 300.00, 400.00, NULL, 'paid', 400.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_08_060624_create_customers_table', 1),
(5, '2026_04_08_060625_create_items_table', 1),
(6, '2026_04_08_060626_create_maintenance_cards_table', 1),
(7, '2026_04_08_060628_create_repair_tasks_table', 1),
(8, '2026_04_08_060629_create_qa_inspections_table', 1),
(9, '2026_04_08_070801_add_repair_requests_to_maintenance_cards_table', 1),
(10, '2026_04_08_075024_create_roles_and_permissions_table', 1),
(11, '2026_04_08_075821_add_delivery_fields_to_maintenance_cards_table', 1),
(12, '2026_04_08_081144_add_financial_tracking_to_maintenance_cards_table', 1),
(13, '2026_04_08_084142_create_settings_table', 1),
(14, '2026_04_08_093113_add_item_image_to_maintenance_cards', 1),
(15, '2026_06_21_100000_add_ready_for_qa_status_to_maintenance_cards', 1),
(16, '2026_06_21_110000_make_national_id_nullable_on_customers', 1),
(17, '2026_06_21_120000_create_notifications_table', 2),
(18, '2026_06_21_130000_create_whatsapp_logs_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0840ac96-f7d3-4ad8-b73c-85eb5b50ba4b', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 5, '{\"card_id\":30,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', NULL, '2026-06-22 10:33:33', '2026-06-22 10:33:33'),
('1530c741-6dda-4cef-9dde-3ad3ff320e18', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 5, '{\"card_id\":32,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', NULL, '2026-06-22 10:54:14', '2026-06-22 10:54:14'),
('1c5f431d-b18f-482d-938b-441f1167c995', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 2, '{\"card_id\":32,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_delivered\",\"icon\":\"check_circle\"}', NULL, '2026-06-22 10:54:26', '2026-06-22 10:54:26'),
('1f3a5ed9-9828-4b63-833c-103349bd6720', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":33,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', '2026-07-18 09:23:40', '2026-06-22 11:03:07', '2026-07-18 09:23:40'),
('20edded3-fc79-4d9a-9fdc-aa3a5e915c63', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 4, '{\"card_id\":32,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-06-22 10:35:10', '2026-06-22 10:35:10'),
('2c2fbbf3-0ba0-4336-b7b4-ec973ec48d57', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 2, '{\"card_id\":30,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', NULL, '2026-06-22 10:33:33', '2026-06-22 10:33:33'),
('3710b1ab-4808-4fcd-a03f-f4ecf8e74f95', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 3, '{\"card_id\":30,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-06-22 10:16:23', '2026-06-22 10:16:23'),
('3da12c65-3758-4414-96cf-41902eb9cb27', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 5, '{\"card_id\":34,\"card_number\":\"BRQ-2026-1032\",\"message_key\":\"notif_ready_for_qa\",\"icon\":\"verified_user\"}', NULL, '2026-07-18 09:59:30', '2026-07-18 09:59:30'),
('4586f786-c073-468d-b0b8-2b9da8f414b6', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":30,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', '2026-07-18 09:23:40', '2026-06-22 10:16:23', '2026-07-18 09:23:40'),
('4632a41c-052d-4d3c-a366-fd69f7dd2618', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 2, '{\"card_id\":17,\"card_number\":\"BRQ-2026-1017\",\"message_key\":\"notif_delivered\",\"icon\":\"check_circle\"}', NULL, '2026-06-22 10:11:50', '2026-06-22 10:11:50'),
('5d0e8536-a529-46f6-a653-029e61b320ab', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 5, '{\"card_id\":33,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', NULL, '2026-06-22 11:03:45', '2026-06-22 11:03:45'),
('5da91fdf-0b0c-4eeb-9f6f-26cb542b6522', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 4, '{\"card_id\":33,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-06-22 11:03:07', '2026-06-22 11:03:07'),
('653c93f6-5337-4cfb-b2bb-697890d1b6ab', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 5, '{\"card_id\":33,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_ready_for_qa\",\"icon\":\"verified_user\"}', NULL, '2026-06-22 11:03:40', '2026-06-22 11:03:40'),
('6ef19901-ec15-47a1-a82c-251fddd83b96', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":34,\"card_number\":\"BRQ-2026-1032\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', NULL, '2026-07-18 10:00:02', '2026-07-18 10:00:02'),
('752ca20c-07f1-4fba-aa0e-8b52b3cd761f', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 4, '{\"card_id\":30,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-06-22 10:16:23', '2026-06-22 10:16:23'),
('781dafb4-0d85-4d30-9eab-9c8534b06ce2', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":32,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', '2026-07-18 09:23:40', '2026-06-22 10:54:14', '2026-07-18 09:23:40'),
('7e7737b9-f6ee-425c-97cb-4fb9ef503b73', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":33,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', '2026-07-18 09:23:40', '2026-06-22 11:03:45', '2026-07-18 09:23:40'),
('814b003d-acf9-4804-ac20-c4ecec0104ac', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 4, '{\"card_id\":34,\"card_number\":\"BRQ-2026-1032\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-07-18 09:55:51', '2026-07-18 09:55:51'),
('8af3b1d9-aae1-4578-8c22-e82b326912cd', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":32,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_delivered\",\"icon\":\"check_circle\"}', '2026-07-18 09:23:40', '2026-06-22 10:54:26', '2026-07-18 09:23:40'),
('90da9f8e-aebc-45e2-a6d3-ce0fa09cda62', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":30,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_ready_for_qa\",\"icon\":\"verified_user\"}', '2026-07-18 09:23:40', '2026-06-22 10:33:23', '2026-07-18 09:23:40'),
('9836381c-1652-4f12-8525-6fbe48b0aa5e', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":9,\"card_number\":\"BRQ-2026-1009\",\"message_key\":\"notif_ready_for_qa\",\"icon\":\"verified_user\"}', '2026-07-18 09:23:40', '2026-06-24 18:38:16', '2026-07-18 09:23:40'),
('9972617c-e699-455d-964d-172cf2d34301', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 2, '{\"card_id\":33,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', NULL, '2026-06-22 11:03:45', '2026-06-22 11:03:45'),
('99ad4f6d-ba70-4d47-9f87-0e992ebfb779', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 2, '{\"card_id\":32,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', NULL, '2026-06-22 10:54:14', '2026-06-22 10:54:14'),
('9d49f071-6aef-43de-9dc4-e0ee12111588', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":34,\"card_number\":\"BRQ-2026-1032\",\"message_key\":\"notif_delivered\",\"icon\":\"check_circle\"}', NULL, '2026-07-18 10:00:32', '2026-07-18 10:00:32'),
('a258f500-e5ed-47f0-8979-0daba82f1533', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 4, '{\"card_id\":31,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-06-22 10:32:58', '2026-06-22 10:32:58'),
('a723fdfc-ccff-4486-912b-7da87be0d887', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 5, '{\"card_id\":34,\"card_number\":\"BRQ-2026-1032\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', NULL, '2026-07-18 10:00:02', '2026-07-18 10:00:02'),
('b186dc5d-4403-4d23-aead-6bb0af7acd9f', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 3, '{\"card_id\":32,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-06-22 10:35:10', '2026-06-22 10:35:10'),
('bd37ece4-d8fc-48db-b2a5-f26676e2b3e4', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 5, '{\"card_id\":30,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_ready_for_qa\",\"icon\":\"verified_user\"}', NULL, '2026-06-22 10:33:23', '2026-06-22 10:33:23'),
('d22417fe-7058-4602-81c7-006831a4b5e0', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":34,\"card_number\":\"BRQ-2026-1032\",\"message_key\":\"notif_ready_for_qa\",\"icon\":\"verified_user\"}', NULL, '2026-07-18 09:59:30', '2026-07-18 09:59:30'),
('d4d719e7-a32f-4a1d-afaf-4c0df0c4095d', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":30,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', '2026-07-18 09:23:40', '2026-06-22 10:33:33', '2026-07-18 09:23:40'),
('d6cd1662-7031-4963-ade5-fd9d35e9f93d', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":33,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_ready_for_qa\",\"icon\":\"verified_user\"}', '2026-07-18 09:23:40', '2026-06-22 11:03:40', '2026-07-18 09:23:40'),
('dc7f9a75-a83f-4c0e-97e6-b5c473588cf7', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 3, '{\"card_id\":33,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-06-22 11:03:07', '2026-06-22 11:03:07'),
('e19ddbbe-8f94-46b2-bf8e-619bf2fa9a62', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":32,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_ready_for_qa\",\"icon\":\"verified_user\"}', '2026-07-18 09:23:40', '2026-06-22 10:54:09', '2026-07-18 09:23:40'),
('e2f9fd97-62d8-40d0-a3ee-116f0a5bb0ca', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 3, '{\"card_id\":31,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-06-22 10:32:58', '2026-06-22 10:32:58'),
('e4feb5c8-158e-4c0f-ba82-472575b8c67b', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":32,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', '2026-07-18 09:23:40', '2026-06-22 10:35:10', '2026-07-18 09:23:40'),
('e6323889-11b9-40d7-a66c-71e41a361d99', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 5, '{\"card_id\":32,\"card_number\":\"BRQ-2026-1030\",\"message_key\":\"notif_ready_for_qa\",\"icon\":\"verified_user\"}', NULL, '2026-06-22 10:54:09', '2026-06-22 10:54:09'),
('e65f098e-bd49-461c-9090-766474da7966', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":34,\"card_number\":\"BRQ-2026-1032\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-07-18 09:55:51', '2026-07-18 09:55:51'),
('eca73a4d-780f-45e7-b434-2e7c737a992a', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":31,\"card_number\":\"BRQ-2026-1031\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', '2026-07-18 09:23:40', '2026-06-22 10:32:58', '2026-07-18 09:23:40'),
('f2528a17-121c-4790-9e60-eaf6b8b62e5f', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 2, '{\"card_id\":34,\"card_number\":\"BRQ-2026-1032\",\"message_key\":\"notif_ready_delivery\",\"icon\":\"local_shipping\"}', NULL, '2026-07-18 10:00:02', '2026-07-18 10:00:02'),
('f86b890f-1a7e-47f4-91bf-4f4c58d9c922', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 2, '{\"card_id\":34,\"card_number\":\"BRQ-2026-1032\",\"message_key\":\"notif_delivered\",\"icon\":\"check_circle\"}', NULL, '2026-07-18 10:00:32', '2026-07-18 10:00:32'),
('fa17ef6b-c399-4166-97b0-cbf4cef61d68', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 3, '{\"card_id\":34,\"card_number\":\"BRQ-2026-1032\",\"message_key\":\"notif_new_card\",\"icon\":\"assignment\"}', NULL, '2026-07-18 09:55:51', '2026-07-18 09:55:51'),
('fb722499-b400-4d7b-a46f-e70c40bacdb8', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 5, '{\"card_id\":9,\"card_number\":\"BRQ-2026-1009\",\"message_key\":\"notif_ready_for_qa\",\"icon\":\"verified_user\"}', NULL, '2026-06-24 18:38:16', '2026-06-24 18:38:16'),
('fc7d4a99-3c72-4930-b4cf-991cf09a78bf', 'App\\Notifications\\CardStageNotification', 'App\\Models\\User', 1, '{\"card_id\":17,\"card_number\":\"BRQ-2026-1017\",\"message_key\":\"notif_delivered\",\"icon\":\"check_circle\"}', '2026-07-18 09:23:40', '2026-06-22 10:11:50', '2026-07-18 09:23:40');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `key`, `group`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'customers.view', 'customers', 'رؤية العملاء', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(2, 'customers.create', 'customers', 'إضافة عميل', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(3, 'customers.edit', 'customers', 'تعديل عميل', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(4, 'customers.delete', 'customers', 'حذف عميل', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(5, 'items.view', 'items', 'رؤية السجل', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(6, 'items.create', 'items', 'إضافة قطعة', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(7, 'items.edit', 'items', 'تعديل قطعة', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(8, 'items.delete', 'items', 'حذف قطعة', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(9, 'maintenance.view', 'maintenance', 'رؤية الكروت', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(10, 'maintenance.create', 'maintenance', 'فتح كرت جديد', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(11, 'maintenance.edit', 'maintenance', 'تعديل الكرت', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(12, 'maintenance.tech_panel', 'maintenance', 'دخول لوحة الفنيين', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(13, 'maintenance.qa_delivery', 'maintenance', 'الجودة والتسليم', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(14, 'staff.manage', 'staff', 'إدارة الموظفين والصلاحيات', '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(15, 'financials.view', 'financials', 'عرض التقارير المالية والديون', '2026-06-21 15:12:49', '2026-06-21 15:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`role_id`, `permission_id`) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(3, 9),
(4, 9),
(2, 10),
(3, 12),
(4, 13);

-- --------------------------------------------------------

--
-- Table structure for table `qa_inspections`
--

CREATE TABLE `qa_inspections` (
  `id` bigint UNSIGNED NOT NULL,
  `maintenance_card_id` bigint UNSIGNED NOT NULL,
  `qa_supervisor_id` bigint UNSIGNED NOT NULL,
  `status` enum('passed','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'passed',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `qa_inspections`
--

INSERT INTO `qa_inspections` (`id`, `maintenance_card_id`, `qa_supervisor_id`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 5, 5, 'rejected', 'يحتاج إعادة تنظيف داخلي', '2026-06-15 18:02:00', '2026-06-15 18:02:00'),
(2, 17, 5, 'passed', 'تم الفحص بنجاح', '2026-05-28 09:01:00', '2026-05-29 09:01:00'),
(3, 18, 5, 'passed', 'مطابق للمواصفات', '2026-06-19 17:52:00', '2026-06-18 17:52:00'),
(4, 19, 5, 'passed', '', '2026-06-17 11:59:00', '2026-06-16 11:59:00'),
(5, 20, 5, 'passed', 'مطابق للمواصفات', '2026-05-23 13:49:00', '2026-05-24 13:49:00'),
(6, 21, 5, 'passed', 'تم الفحص بنجاح', '2026-06-06 09:08:00', '2026-06-05 09:08:00'),
(7, 22, 5, 'passed', 'تم الفحص بنجاح', '2026-05-21 15:40:00', '2026-05-21 15:40:00'),
(8, 23, 5, 'passed', 'تم الفحص بنجاح', '2026-05-22 12:25:00', '2026-05-21 12:25:00'),
(9, 24, 5, 'passed', 'تم الفحص بنجاح', '2026-05-22 14:02:00', '2026-05-22 14:02:00'),
(10, 25, 5, 'passed', '', '2026-05-02 17:07:00', '2026-05-01 17:07:00'),
(11, 26, 5, 'passed', '', '2026-05-26 11:15:00', '2026-05-25 11:15:00'),
(12, 27, 5, 'passed', 'مطابق للمواصفات', '2026-05-18 17:25:00', '2026-05-16 17:25:00'),
(13, 28, 5, 'passed', '', '2026-06-01 18:32:00', '2026-06-02 18:32:00'),
(14, 29, 5, 'passed', 'مطابق للمواصفات', '2026-06-10 12:48:00', '2026-06-11 12:48:00'),
(16, 32, 1, 'passed', '', '2026-06-22 10:54:14', '2026-06-22 10:54:14'),
(17, 33, 1, 'passed', '', '2026-06-22 11:03:45', '2026-06-22 11:03:45'),
(18, 34, 1, 'passed', '', '2026-07-18 10:00:02', '2026-07-18 10:00:02');

-- --------------------------------------------------------

--
-- Table structure for table `repair_tasks`
--

CREATE TABLE `repair_tasks` (
  `id` bigint UNSIGNED NOT NULL,
  `maintenance_card_id` bigint UNSIGNED NOT NULL,
  `technician_id` bigint UNSIGNED NOT NULL,
  `task_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `used_parts_text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `repair_tasks`
--

INSERT INTO `repair_tasks` (`id`, `maintenance_card_id`, `technician_id`, `task_description`, `start_time`, `end_time`, `duration`, `used_parts_text`, `created_at`, `updated_at`) VALUES
(1, 5, 4, 'حفر اسم العميل مع الشعار', '2026-06-16 00:02:00', '2026-06-16 02:29:00', 147, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(2, 5, 3, 'تركيب اكسسوارات', '2026-06-16 05:29:00', '2026-06-16 07:29:00', 120, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(3, 6, 4, 'تركيب اكسسوارات', '2026-05-04 17:54:00', '2026-05-04 19:05:00', 71, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(4, 7, 4, 'حفر اسم العميل مع الشعار', '2026-06-03 07:37:00', '2026-06-03 10:24:00', 167, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(5, 8, 3, 'تغيير مقابض', '2026-05-16 13:43:00', '2026-05-16 15:17:00', 94, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(6, 8, 4, 'حفر اسم العميل مع الشعار', '2026-05-16 18:17:00', '2026-05-16 20:46:00', 149, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(7, 8, 4, 'تركيب اكسسوارات', '2026-05-18 01:46:00', '2026-05-18 04:24:00', 158, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(8, 9, 3, 'صيانة وتنظيف شامل', '2026-06-15 00:14:00', '2026-06-15 02:13:00', 119, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(9, 9, 3, 'تغيير مقابض', '2026-06-15 23:13:00', '2026-06-16 01:31:00', 138, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(10, 10, 4, 'تركيب اكسسوارات', '2026-06-13 17:17:00', '2026-06-13 18:58:00', 101, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(11, 10, 4, 'تركيب اكسسوارات', '2026-06-13 20:58:00', '2026-06-13 21:54:00', 56, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(12, 11, 3, 'حفر اسم العميل مع الشعار', '2026-05-18 05:19:00', '2026-05-18 08:13:00', 174, 'نابض إرجاع', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(13, 12, 4, 'حفر اسم العميل مع الشعار', '2026-05-08 06:24:00', '2026-05-08 06:58:00', 34, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(14, 12, 3, 'صيانة وتنظيف شامل', '2026-05-08 20:58:00', '2026-05-08 22:38:00', 100, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(15, 12, 3, 'صيانة وتنظيف شامل', '2026-05-10 04:38:00', '2026-05-10 05:34:00', 56, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(16, 13, 4, 'صيانة وتنظيف شامل', '2026-04-24 21:40:00', '2026-04-25 00:18:00', 158, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(17, 13, 4, 'تغيير مقابض', '2026-04-26 06:18:00', '2026-04-26 09:02:00', 164, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(18, 13, 4, 'حفر اسم العميل مع الشعار', '2026-04-26 17:02:00', '2026-04-26 18:42:00', 100, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(19, 14, 4, 'تركيب اكسسوارات', '2026-04-24 20:44:00', '2026-04-24 21:19:00', 35, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(20, 14, 4, 'تغيير مقابض', '2026-04-25 10:19:00', '2026-04-25 11:16:00', 57, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(21, 15, 3, 'تركيب اكسسوارات', '2026-05-21 12:14:00', '2026-05-21 12:37:00', 23, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(22, 15, 4, 'صيانة وتنظيف شامل', '2026-05-22 06:37:00', '2026-05-22 07:34:00', 57, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(23, 16, 3, 'تركيب اكسسوارات', '2026-05-18 04:05:00', '2026-05-18 06:20:00', 135, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(24, 16, 4, 'صيانة وتنظيف شامل', '2026-05-19 08:20:00', '2026-05-19 10:59:00', 159, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(25, 16, 3, 'تركيب اكسسوارات', '2026-05-20 01:59:00', '2026-05-20 02:43:00', 44, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(26, 17, 3, 'حفر اسم العميل مع الشعار', '2026-05-29 00:01:00', '2026-05-29 00:33:00', 32, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(27, 17, 4, 'تركيب اكسسوارات', '2026-05-30 00:33:00', '2026-05-30 01:06:00', 33, 'نابض إرجاع', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(28, 17, 3, 'صيانة وتنظيف شامل', '2026-05-30 04:06:00', '2026-05-30 05:16:00', 70, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(29, 18, 4, 'صيانة وتنظيف شامل', '2026-06-19 04:52:00', '2026-06-19 06:48:00', 116, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(30, 18, 3, 'صيانة وتنظيف شامل', '2026-06-20 06:48:00', '2026-06-20 08:11:00', 83, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(31, 19, 4, 'صيانة وتنظيف شامل', '2026-06-16 16:59:00', '2026-06-16 19:48:00', 169, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(32, 20, 4, 'تركيب اكسسوارات', '2026-05-23 06:49:00', '2026-05-23 07:35:00', 46, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(33, 20, 4, 'صيانة وتنظيف شامل', '2026-05-24 12:35:00', '2026-05-24 15:04:00', 149, 'نابض إرجاع', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(34, 21, 4, 'تركيب اكسسوارات', '2026-06-03 16:08:00', '2026-06-03 17:07:00', 59, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(35, 22, 3, 'صيانة وتنظيف شامل', '2026-05-20 08:40:00', '2026-05-20 10:58:00', 138, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(36, 23, 4, 'صيانة وتنظيف شامل', '2026-05-21 19:25:00', '2026-05-21 19:57:00', 32, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(37, 24, 4, 'حفر اسم العميل مع الشعار', '2026-05-22 02:02:00', '2026-05-22 02:32:00', 30, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(38, 24, 4, 'تغيير مقابض', '2026-05-22 05:32:00', '2026-05-22 08:17:00', 165, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(39, 24, 3, 'تركيب اكسسوارات', '2026-05-22 09:17:00', '2026-05-22 11:17:00', 120, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(40, 25, 3, 'صيانة وتنظيف شامل', '2026-05-01 05:07:00', '2026-05-01 06:59:00', 112, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(41, 25, 4, 'حفر اسم العميل مع الشعار', '2026-05-01 23:59:00', '2026-05-02 00:54:00', 55, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(42, 26, 3, 'صيانة وتنظيف شامل', '2026-05-24 23:15:00', '2026-05-25 00:27:00', 72, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(43, 26, 3, 'صيانة وتنظيف شامل', '2026-05-25 16:27:00', '2026-05-25 19:10:00', 163, 'نابض إرجاع', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(44, 27, 4, 'تغيير مقابض', '2026-05-16 13:25:00', '2026-05-16 14:00:00', 35, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(45, 27, 3, 'تركيب اكسسوارات', '2026-05-16 17:00:00', '2026-05-16 17:28:00', 28, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(46, 27, 3, 'صيانة وتنظيف شامل', '2026-05-17 02:28:00', '2026-05-17 04:33:00', 125, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(47, 28, 4, 'تركيب اكسسوارات', '2026-06-02 05:32:00', '2026-06-02 07:44:00', 132, 'مقبض مطاطي', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(48, 28, 3, 'تغيير مقابض', '2026-06-02 16:44:00', '2026-06-02 18:46:00', 122, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(49, 28, 4, 'حفر اسم العميل مع الشعار', '2026-06-03 13:46:00', '2026-06-03 14:42:00', 56, '', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(50, 29, 3, 'تغيير مقابض', '2026-06-10 16:48:00', '2026-06-10 17:17:00', 29, 'مجموعة دبابيس', '2026-06-21 15:47:55', '2026-06-21 15:47:55'),
(51, 34, 1, 'test', '2026-07-18 09:59:00', '2026-07-18 10:59:00', NULL, 'test', '2026-07-18 09:59:24', '2026-07-18 09:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'manager', 'المدير العام', NULL, '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(2, 'reception', 'موظف الاستقبال', NULL, '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(3, 'technician', 'فني الصيانة', NULL, '2026-06-21 15:12:49', '2026-06-21 15:12:49'),
(4, 'qa', 'مشرف الجودة', NULL, '2026-06-21 15:12:49', '2026-06-21 15:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7A9aDzTuA1oFQA330SbJGsONQHxXbREBcw84LYU6', NULL, '172.68.234.97', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWk1PQnZXWVVVZjBkd3BUZ2R4REdQQVpPOERXMFhrczg1NGsxbDRPZyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cHM6Ly9hdXJhdGFjLnMtcGx1cy5tZS9pdGVtcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwczovL2F1cmF0YWMucy1wbHVzLm1lL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1784366553),
('IwE7rvho5GlUBggtKphQ1wWbiBgp8sYO0PgZHFIA', NULL, '172.68.234.96', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVlNvcFh1VXR2QUsyb2JuQTBmTmROS3F1RXpnQ0U3MkhqRXl4OGtwViI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNToiaHR0cHM6Ly9hdXJhdGFjLnMtcGx1cy5tZSI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwczovL2F1cmF0YWMucy1wbHVzLm1lL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9fQ==', 1784369651);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'system_name', 'AURA TAC', 'branding', '2026-06-21 15:12:53', '2026-06-21 15:12:53'),
(2, 'system_name_en', 'Maintenance System', 'branding', '2026-06-21 15:12:53', '2026-06-21 15:12:53'),
(3, 'logo_path', 'storage/branding/19IEqFAZoHn3WNUJ7rJnpS36FR7Nno3BqQ8PkX4B.png', 'branding', '2026-06-21 15:12:53', '2026-06-22 10:09:33'),
(4, 'footer_text', 'تصميم وتطوير S-Plus', 'branding', '2026-06-21 15:12:53', '2026-06-21 16:03:24'),
(5, 'sms_mode', 'test', 'sms', '2026-06-21 15:12:53', '2026-06-21 15:12:53'),
(6, 'twilio_sid', '', 'sms', '2026-06-21 15:12:53', '2026-06-21 15:12:53'),
(7, 'twilio_token', '', 'sms', '2026-06-21 15:12:53', '2026-06-21 15:12:53'),
(8, 'twilio_from', '', 'sms', '2026-06-21 15:12:53', '2026-06-21 15:12:53'),
(9, 'terms_conditions', '1. المركز غير مسؤول عن أي ذخيرة متبقية في السلاح.\\n2. يتم استلام السلاح بموجب هذا الكرت.\\n3. أقصى مدة بقاء للسلاح بعد الإصلاح هي 30 يوماً.', 'general', '2026-06-21 15:12:53', '2026-06-22 10:05:39'),
(10, 'whatsapp_enabled', '1', 'whatsapp', '2026-06-22 10:05:39', '2026-06-22 10:11:35'),
(11, 'whatsapp_api_url', 'https://wa.stop4web.online/', 'whatsapp', '2026-06-22 10:05:39', '2026-06-22 10:10:17'),
(12, 'whatsapp_api_key', 'Ahmed_Strong_Key_2026', 'whatsapp', '2026-06-22 10:05:39', '2026-06-22 10:10:17'),
(13, 'whatsapp_instance', 'tenant_stop-group', 'whatsapp', '2026-06-22 10:05:39', '2026-06-22 10:10:17'),
(14, 'whatsapp_country_code', '966', 'whatsapp', '2026-06-22 10:05:39', '2026-06-22 10:11:35'),
(15, 'whatsapp_token', '896BCA0C-CEA5-4CFA-8E9C-705CEC505E9D', 'whatsapp', '2026-06-22 10:09:06', '2026-06-22 10:10:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('reception','technician','qa','manager') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reception',
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `phone`, `email`, `password`, `role`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'المدير', 'admin', '0500000000', NULL, '$2y$12$1XE6cZV3QNf/NR0kw/Xq8O3OvADs9IS09CC8ez0sy.H2F.GTv..9S', 'manager', 1, '87zfi9CUxOe6ysmK054oXYAoDwqXbBmdL0pWptyiCMWyd2aAQrRkmkUwZaQe', '2026-06-21 15:12:56', '2026-06-21 15:39:04'),
(2, 'سعد القحطاني', 'reception', '0551000001', NULL, '$2y$12$PWdP4ep.YzhVjV25BPTALuOqkBn1bcrp3v1hxMXZB/BzG8gdK1/ge', 'reception', 2, NULL, '2026-06-21 15:47:53', '2026-06-21 15:47:53'),
(3, 'خالد العتيبي', 'tech1', '0551000002', NULL, '$2y$12$WBOObM.E5.QpJhwry250dOpIKG.N7z/ZN83ZfMJIij6KER09TExsi', 'technician', 3, NULL, '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(4, 'ماجد الدوسري', 'tech2', '0551000003', NULL, '$2y$12$sX4IO3xL1jSKS74IaJB5ze0s3iqRUtOX.FvK0U8ujvcLSVr9AFlYK', 'technician', 3, NULL, '2026-06-21 15:47:54', '2026-06-21 15:47:54'),
(5, 'عبدالعزيز الشمري', 'qa1', '0551000004', NULL, '$2y$12$K6ZZJTsfpxYLlMJQgEWLdeRSCfDf5rMZL4iagalbpHY6/KoHFEkqe', 'qa', 4, NULL, '2026-06-21 15:47:54', '2026-07-18 10:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_logs`
--

CREATE TABLE `whatsapp_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `recipient` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'failed',
  `summary` text COLLATE utf8mb4_unicode_ci,
  `response` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `whatsapp_logs`
--

INSERT INTO `whatsapp_logs` (`id`, `recipient`, `type`, `status`, `summary`, `response`, `created_at`, `updated_at`) VALUES
(1, '201030889618', 'text', 'sent', 'رسالة تجريبية من نظام Aura Tac عبر واتساب ✅', NULL, '2026-06-22 10:53:37', '2026-06-22 10:53:37'),
(2, '01030889618', 'text', 'failed', 'عميلنا احمد معوض، قطعتك جاهزة ✅ تقدر تستلمها من Aura Tac — كرت رقم BRQ-2026-1030.', 'HTTP 400: {\"status\":400,\"error\":\"Bad Request\",\"response\":{\"message\":[{\"jid\":\"9661030889618@s.whatsapp.net\",\"exists\":false,\"number\":\"9661030889618\"}]}}', '2026-06-22 10:54:15', '2026-06-22 10:54:15'),
(3, '01030889618', 'text', 'failed', 'تم تسليم قطعتك بنجاح احمد معوض ✅ شكراً لثقتك في Aura Tac (كرت BRQ-2026-1030). في خدمتك دائماً.', 'HTTP 400: {\"status\":400,\"error\":\"Bad Request\",\"response\":{\"message\":[{\"jid\":\"9661030889618@s.whatsapp.net\",\"exists\":false,\"number\":\"9661030889618\"}]}}', '2026-06-22 10:54:26', '2026-06-22 10:54:26'),
(4, '01030889618', 'document', 'sent', 'أهلاً احمد معوض 👋\nاستلمنا قطعتك في Aura Tac بكرت رقم BRQ-2026-1031. مرفق كرت العمل، وهنطمّنك أول ما تجهز.', NULL, '2026-06-22 11:03:09', '2026-06-22 11:03:09'),
(5, '01030889618', 'text', 'sent', 'احمد معوض، خلّصنا صيانة قطعتك (كرت BRQ-2026-1031) وهي جاهزة للاستلام. نسعد بزيارتك في Aura Tac.', NULL, '2026-06-22 11:03:46', '2026-06-22 11:03:46'),
(6, '01030889618', 'document', 'sent', 'احمد معوض، تم استلام قطعتك بنجاح ✅\nرقم الكرت: BRQ-2026-1032 — Aura Tac. مرفق الكرت، نشكر ثقتك.', NULL, '2026-07-18 09:55:53', '2026-07-18 09:55:53'),
(7, '01030889618', 'text', 'sent', 'عميلنا احمد معوض، قطعتك جاهزة ✅ تقدر تستلمها من Aura Tac — كرت رقم BRQ-2026-1032.', NULL, '2026-07-18 10:00:03', '2026-07-18 10:00:03'),
(8, '01030889618', 'text', 'sent', 'تم تسليم قطعتك بنجاح احمد معوض ✅ شكراً لثقتك في Aura Tac (كرت BRQ-2026-1032). في خدمتك دائماً.', NULL, '2026-07-18 10:00:34', '2026-07-18 10:00:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_phone_unique` (`phone`),
  ADD UNIQUE KEY `customers_national_id_unique` (`national_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_item_number_unique` (`item_number`),
  ADD KEY `items_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_cards`
--
ALTER TABLE `maintenance_cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `maintenance_cards_card_number_unique` (`card_number`),
  ADD KEY `maintenance_cards_customer_id_foreign` (`customer_id`),
  ADD KEY `maintenance_cards_item_id_foreign` (`item_id`),
  ADD KEY `maintenance_cards_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_key_unique` (`key`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_role_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `qa_inspections`
--
ALTER TABLE `qa_inspections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qa_inspections_maintenance_card_id_foreign` (`maintenance_card_id`),
  ADD KEY `qa_inspections_qa_supervisor_id_foreign` (`qa_supervisor_id`);

--
-- Indexes for table `repair_tasks`
--
ALTER TABLE `repair_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repair_tasks_maintenance_card_id_foreign` (`maintenance_card_id`),
  ADD KEY `repair_tasks_technician_id_foreign` (`technician_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `whatsapp_logs`
--
ALTER TABLE `whatsapp_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_cards`
--
ALTER TABLE `maintenance_cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `qa_inspections`
--
ALTER TABLE `qa_inspections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `repair_tasks`
--
ALTER TABLE `repair_tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `whatsapp_logs`
--
ALTER TABLE `whatsapp_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_cards`
--
ALTER TABLE `maintenance_cards`
  ADD CONSTRAINT `maintenance_cards_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_cards_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_cards_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `qa_inspections`
--
ALTER TABLE `qa_inspections`
  ADD CONSTRAINT `qa_inspections_maintenance_card_id_foreign` FOREIGN KEY (`maintenance_card_id`) REFERENCES `maintenance_cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `qa_inspections_qa_supervisor_id_foreign` FOREIGN KEY (`qa_supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `repair_tasks`
--
ALTER TABLE `repair_tasks`
  ADD CONSTRAINT `repair_tasks_maintenance_card_id_foreign` FOREIGN KEY (`maintenance_card_id`) REFERENCES `maintenance_cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `repair_tasks_technician_id_foreign` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
