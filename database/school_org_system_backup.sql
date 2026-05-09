-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2025 at 08:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

CREATE DATABASE IF NOT EXISTS `school_org_system_backup`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;
USE `school_org_system_backup`;

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `announcement_targets`;
DROP TABLE IF EXISTS `attendance`;
DROP TABLE IF EXISTS `event_status`;
DROP TABLE IF EXISTS `membership`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `subscriptions`;
DROP TABLE IF EXISTS `user_achievements`;
DROP TABLE IF EXISTS `user_social_links`;
DROP TABLE IF EXISTS `org_resources`;
DROP TABLE IF EXISTS `admin_resources`;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `organizations`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `org_categories`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_org_system_backup`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(84, NULL, 'User Deleted', 'User \'<b>mark plier</b>\' (plier@gmail.com) with role <b>Student</b> was deleted.', '::1', '2025-03-13 10:23:37'),
(85, NULL, 'User Updated', '<b>Feli Falimo</b> updated details: Name: <b>Fe Falimo</b> ➝ <b>Feli Falimo</b>', '::1', '2025-03-13 10:23:41'),
(86, NULL, 'Admin Login', 'Admin logged into the system', '::1', '2025-03-13 10:30:12'),
(87, NULL, 'User Deleted', 'User \'<b>ahu kiminalua</b>\' (kiminalua@example.com) with role <b>Student</b> was deleted.', '::1', '2025-03-13 12:48:03'),
(88, NULL, 'User Updated', '<b>Feli Falimomina</b> updated details: Name: <b>Feli Falimo</b> ➝ <b>Feli Falimomina</b>, Email: <b>falimo@example.com</b> ➝ <b>falimomina@example.com</b>, Role: <b>Student</b> ➝ <b>Admin</b>, Organization: <b>Theater & Performing Arts</b> ➝ <b>Data Science Society</b>', '::1', '2025-03-13 12:48:23'),
(89, NULL, 'User Updated', '<b>wency manalo</b> updated details: Organization: <b></b> ➝ <b>Animal Welfare Group</b>', '::1', '2025-03-13 13:59:32'),
(90, NULL, 'Failed Admin Login', 'Admin email not found: qeqeqeqe@example.com', '::1', '2025-03-13 16:22:55'),
(91, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:36:55'),
(92, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:36:56'),
(93, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:37:06'),
(94, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:37:18'),
(95, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:37:19'),
(96, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:38:27'),
(97, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:38:29'),
(98, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:38:29'),
(99, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:38:29'),
(100, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:38:30'),
(101, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:38:30'),
(102, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:38:30'),
(103, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:38:32'),
(104, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:38:33'),
(105, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:40:16'),
(106, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:42:22'),
(107, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:43:52'),
(108, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:43:53'),
(109, NULL, 'Failed Login', 'User not found for email: admin@example.com', '::1', '2025-03-13 16:47:38'),
(110, NULL, 'Failed Login', 'User not found for email: admin@example.com', '::1', '2025-03-13 16:47:39'),
(111, NULL, 'Failed Login', 'User not found for email: admin@example.com', '::1', '2025-03-13 16:47:39'),
(112, NULL, 'Failed Login', 'User not found for email: admin@example.com', '::1', '2025-03-13 16:48:05'),
(113, NULL, 'Failed Login', 'User not found for email: admin@example.com', '::1', '2025-03-13 16:48:06'),
(114, NULL, 'Failed Login', 'User not found for email: admin@example.com', '::1', '2025-03-13 16:48:06'),
(115, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:49:06'),
(116, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:49:07'),
(117, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:49:11'),
(118, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:49:18'),
(119, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:49:58'),
(120, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:51:45'),
(121, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:52:08'),
(122, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:52:09'),
(123, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:55:40'),
(124, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:55:44'),
(125, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:57:33'),
(126, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:57:34'),
(127, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 16:59:20'),
(128, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 17:00:38'),
(129, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 17:00:39'),
(130, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 17:00:39'),
(131, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 17:00:42'),
(132, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 17:07:03'),
(133, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 17:08:16'),
(134, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-13 17:09:06'),
(135, NULL, 'Admin Login', 'admin logged into the system', '::1', '2025-03-13 17:10:10'),
(136, NULL, 'User Updated', '<b>wency manalow</b> updated details: Name: <b>wency manalo</b> ➝ <b>wency manalow</b>, Email: <b>manalo@gmail.com.com</b> ➝ <b>manalow@gmail.com.com</b>, Role: <b>Student</b> ➝ <b>Officer</b>, Organization: <b>Animal Welfare Group</b> ➝ <b>Japanese Language Club</b>', '::1', '2025-03-13 17:13:40'),
(137, NULL, 'Admin Login', 'Admin Master logged into the system', '::1', '2025-03-14 03:03:58'),
(138, NULL, 'Admin Login', 'Admin Master logged into the system', '::1', '2025-03-14 03:18:22'),
(139, NULL, 'User Updated', '<b>David Wilson</b> updated details: Organization: <b>Data Science Society</b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 03:18:32'),
(140, NULL, 'Admin Login', 'Admin Master logged into the system', '::1', '2025-03-14 03:19:55'),
(141, NULL, 'User Registration', 'User \'aaaaaaaaaaaaaaaa\' (aaaaaaaaaaaa@example.com) has been registered as Officer for Organization ID: 1.', '::1', '2025-03-14 03:20:12'),
(142, NULL, 'User Registration', 'User \'aaaaabababa\' (aaaaabababa@example.com) has been registered as Officer for Organization ID: 3.', '::1', '2025-03-14 03:27:40'),
(143, NULL, 'User Updated', '<b>aaaaabababa</b> updated details: Organization: <b>Helping Hands Community</b> ➝ <b>Investment & Finance Clube</b>', '::1', '2025-03-14 03:28:30'),
(144, NULL, 'User Registration', 'User \'ax\' (ax@example.com) has been registered as Student for Organization ID: 14.', '::1', '2025-03-14 03:28:46'),
(145, NULL, 'User Registration', 'User \'asasa\' (asasa@example.com) has been registered as Student for Organization ID: 4.', '::1', '2025-03-14 03:32:01'),
(146, NULL, 'User Registration', 'User \'axe\' (axe@example.com) has been registered as Officer for Organization ID: 2.', '::1', '2025-03-14 03:33:46'),
(147, NULL, 'User Updated', '<b>axe</b> updated details: Organization: <b>Entrepreneurial Minds</b> ➝ <b>Japanese Language Club</b>', '::1', '2025-03-14 03:33:55'),
(148, NULL, 'User Registration', 'User \'ate\' (ate@example.com) has been registered as Student for Organization ID: 13.', '::1', '2025-03-14 03:34:40'),
(149, NULL, 'User Registration', 'User \'add\' (add@example.com) has been registered as Student for Organization ID: 16.', '::1', '2025-03-14 03:37:58'),
(150, NULL, 'User Registration', 'User \'atat\' (atat@gmail.com) has been registered as Student for Organization ID: 18.', '::1', '2025-03-14 03:38:22'),
(151, NULL, 'User Registration', 'User \'age\' (age@example.com) has been registered as Student for Organization ID: 13.', '::1', '2025-03-14 03:38:45'),
(152, NULL, 'Admin Login', 'Admin Master logged into the system', '::1', '2025-03-14 03:41:20'),
(153, NULL, 'User Registration', 'User \'aft\' (aft@example.com) has been registered as Student for Organization ID: 17.', '::1', '2025-03-14 03:41:33'),
(154, NULL, 'User Registration', 'User \'few\' (few@example.com) has been registered as Student for Organization ID: 16.', '::1', '2025-03-14 03:42:34'),
(155, NULL, 'User Registration', 'User \'get\' (get@example.com) has been registered as Officer for Organization ID: 16.', '::1', '2025-03-14 03:42:55'),
(156, NULL, 'User Updated', '<b>get</b> updated details: Organization: <b>Public Speaking Society</b> ➝ <b>Investment & Finance Clube</b>', '::1', '2025-03-14 03:43:56'),
(157, NULL, 'User Registration', 'User \'sat\' (sat@example.com) has been registered as Student for Organization ID: 16.', '::1', '2025-03-14 03:47:11'),
(158, NULL, 'User Registration', 'User \'saw\' (saw@example.com) has been registered as Student for Organization ID: 17.', '::1', '2025-03-14 03:49:00'),
(159, NULL, 'User Registration', 'User \'sasa\' (sasa@example.com) has been registered as Student for Organization ID: 13.', '::1', '2025-03-14 03:52:20'),
(160, NULL, 'Admin Login', 'Admin Master logged into the system', '::1', '2025-03-14 03:58:30'),
(161, NULL, 'User Registration', 'User \'daw\' (daw@example.com) has been registered as Student for Organization ID: 17.', '::1', '2025-03-14 03:58:48'),
(162, NULL, 'User Registration', 'User \'vat\' (vat@example.com) has been registered as Officer for Organization ID: 18.', '::1', '2025-03-14 04:04:24'),
(163, NULL, 'User Registration', 'User \'bet\' (bet@example.com) has been registered as Officer for Organization ID: 18.', '::1', '2025-03-14 04:05:20'),
(164, NULL, 'User Registration', 'User \'brew\' (brew@example.com) has been registered as Student for Organization ID: 16.', '::1', '2025-03-14 04:05:41'),
(165, NULL, 'User Registration', 'User \'fert\' (fert@example.com) has been registered as Student for Organization ID: 15.', '::1', '2025-03-14 04:06:05'),
(166, NULL, 'User Deleted', 'User \'<b>fert</b>\' (fert@example.com) with role <b>Student</b> was deleted.', '::1', '2025-03-14 04:07:18'),
(167, NULL, 'User Registration', 'User \'bed\' (bed@example.com) has been registered as Officer for Organization ID: 5.', '::1', '2025-03-14 04:07:41'),
(168, NULL, 'User Registration', 'User \'ber\' (ber@example.com) has been registered as Student for Organization ID: 16.', '::1', '2025-03-14 04:08:03'),
(169, NULL, 'User Updated', '<b>bertt</b> updated details: Name: <b>ber</b> ➝ <b>bertt</b>, Email: <b>ber@example.com</b> ➝ <b>bertt@example.com</b>, Organization: <b>Public Speaking Society</b> ➝ <b>Japanese Language Club</b>', '::1', '2025-03-14 04:08:16'),
(170, NULL, 'User Registration', 'User \'nrt\' (nrt@gmail.com) has been registered as Officer for Organization ID: 16.', '::1', '2025-03-14 04:08:35'),
(171, NULL, 'User Registration', 'User \'nesty\' (nesty@example.com) has been registered as Student for Organization ID: 36.', '::1', '2025-03-14 04:09:20'),
(172, NULL, 'User Registration', 'User \'mett\' (mett@example.com) has been registered as Student for Organization ID: 17.', '::1', '2025-03-14 04:11:10'),
(173, NULL, 'User Registration', 'User \'noth\' (noth@example.com) has been registered as Student for Organization ID: 35.', '::1', '2025-03-14 04:14:20'),
(174, NULL, 'User Registration', 'User \'nothe\' (nothe@example.com) has been registered as Student for Organization ID: 26.', '::1', '2025-03-14 04:14:38'),
(175, NULL, 'User Registration', 'User \'dddddew\' (dddddew@example.com) has been registered as Student for Organization ID: 13.', '::1', '2025-03-14 04:15:01'),
(176, NULL, 'User Registration', 'User \'dddddewwe\' (dddddewwe@example.com) has been registered as Student for Organization ID: 13.', '::1', '2025-03-14 04:15:23'),
(177, NULL, 'User Registration', 'User \'wdwd\' (wdwd@example.com) has been registered as Student for Organization ID: 15.', '::1', '2025-03-14 04:15:37'),
(178, NULL, 'User Registration', 'User \'ce\' (ce@example.com) has been registered as Student for Organization ID: 17.', '::1', '2025-03-14 04:15:53'),
(179, NULL, 'User Registration', 'User \'dw\' (dw@example.com) has been registered as Student for Organization ID: 15.', '::1', '2025-03-14 04:16:25'),
(180, NULL, 'User Registration', 'User \'ytyty\' (utytu@example.com) has been registered as Student for Organization ID: 15.', '::1', '2025-03-14 04:17:09'),
(181, NULL, 'User Registration', 'User \'thyth\' (hrtht@example.com) has been registered as Student for Organization ID: 16.', '::1', '2025-03-14 04:19:23'),
(182, NULL, 'User Registration', 'User \'wewewe\' (wewewewe@example.com) has been registered as Student for Organization ID: 18.', '::1', '2025-03-14 04:20:52'),
(183, NULL, 'User Registration', 'User \'Fe grgrgr\' (grgrgr@example.com) has been registered as Student for Organization ID: 17.', '::1', '2025-03-14 04:21:36'),
(184, NULL, 'User Added', '<b>fefef</b> (<b>efefeff@example.com</b>) added as <b>Student</b> in <b>Islamic Student Organization</b>', '::1', '2025-03-14 04:27:44'),
(185, NULL, 'User Deleted', 'User \'<b>fefef</b>\' (efefeff@example.com) with role <b>Student</b> was deleted.', '::1', '2025-03-14 04:29:31'),
(186, NULL, 'User Updated', '<b>Fe grgrgr</b> updated details: Organization: <b>Islamic Student Organization</b> ➝ <b>Animal Welfare Group</b>', '::1', '2025-03-14 04:29:35'),
(187, NULL, 'User Added', '<b>dwdwd</b> (<b>wdwasxas@example.com</b>) added as <b>Officer</b> in <b>Islamic Student Organization</b>', '::1', '2025-03-14 04:29:43'),
(188, NULL, 'User Added', '<b>asxsx</b> (<b>xasxx@gmail.com</b>) added as <b>Student</b> in <b>Japanese Language Club</b>', '::1', '2025-03-14 04:30:02'),
(189, NULL, 'User Added', '<b>fwfwfw</b> (<b>bbdfx@example.com</b>) added as <b>Student</b> in <b>Investment & Finance Clube</b>', '::1', '2025-03-14 04:32:10'),
(190, NULL, 'User Added', '<b>vfvdv</b> (<b>fv@example.com</b>) added as <b>Student</b> in <b>Public Speaking Society</b>', '::1', '2025-03-14 04:32:54'),
(191, NULL, 'User Added', '<b>dwdwax</b> (<b>xxz@example.com</b>) added as <b>Student</b> in <b>Islamic Student Organization</b>', '::1', '2025-03-14 04:35:31'),
(192, NULL, 'User Added', '<b>mi7kui</b> (<b>uikmiuk@example.com</b>) added as <b>Student</b> in <b>Japanese Language Club</b>', '::1', '2025-03-14 04:36:23'),
(193, NULL, 'Admin Login', 'Admin Master logged into the system', '::1', '2025-03-14 04:59:22'),
(194, NULL, 'User Updated', '<b>mi7kui</b> updated details: Organization: <b>Japanese Language Club</b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 04:59:33'),
(195, NULL, 'User Updated', '<b>dwdwax</b> updated details: Organization: <b>Islamic Student Organization</b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 04:59:37'),
(196, NULL, 'User Updated', '<b>vfvdv</b> updated details: Organization: <b>Public Speaking Society</b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 04:59:40'),
(197, NULL, 'User Updated', '<b>fwfwfw</b> updated details: Organization: <b>Investment & Finance Clube</b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 04:59:44'),
(198, NULL, 'Admin Login', 'Admin Master logged into the system', '::1', '2025-03-14 05:00:41'),
(199, NULL, 'User Updated', '<b>ytyty</b> updated details: Organization: <b>Japanese Language Club</b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:00:54'),
(200, NULL, 'User Added', '<b>hyh</b> (<b>hyhy@example.com</b>) added as <b>Student</b> in <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:01:01'),
(201, NULL, 'User Added', '<b>yhyhyh</b> (<b>hyhthtb@example.com</b>) added as <b>Student</b> in <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:01:15'),
(202, NULL, 'User Added', '<b>tbtbb</b> (<b>btbb@gmail.com</b>) added as <b>Student</b> in <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:01:31'),
(203, NULL, 'User Updated', '<b>thyth</b> updated details: Organization: <b>Public Speaking Society</b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:01:38'),
(204, NULL, 'User Updated', '<b>John Doe</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:01:44'),
(205, NULL, 'User Updated', '<b>Emily Brown</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:01:48'),
(206, NULL, 'User Updated', '<b>Mike Johnson</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:01:51'),
(207, NULL, 'User Updated', '<b>Sophia Taylor</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:02:09'),
(208, NULL, 'User Updated', '<b>Mia Scott</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:02:27'),
(209, NULL, 'User Updated', '<b>Amelia Nelson</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:02:34'),
(210, NULL, 'User Updated', '<b>Sebastian Perez</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:02:37'),
(211, NULL, 'User Updated', '<b>Zoe White</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:02:40'),
(212, NULL, 'User Updated', '<b>Jack Harris</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:02:46'),
(213, NULL, 'User Updated', '<b>Samuel Wright</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:03:04'),
(214, NULL, 'User Updated', '<b>Liam Martinez</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:03:08'),
(215, NULL, 'User Updated', '<b>Jackson Scott</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:03:15'),
(216, NULL, 'User Updated', '<b>Leah Lopez</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:03:18'),
(217, NULL, 'User Updated', '<b>Benjamin Young</b> updated details: Organization: <b></b> ➝ <b>N/A</b>', '::1', '2025-03-14 05:03:20'),
(218, NULL, 'User Updated', '<b>Benjamin Young</b> updated details: Organization: <b></b> ➝ <b>Photography Enthusiasts</b>', '::1', '2025-03-14 05:03:24'),
(219, NULL, 'Admin Login', 'Admin Master logged into the system', '::1', '2025-03-14 06:04:39'),
(220, NULL, 'Admin Login', 'Admin Master logged into the system', '::1', '2025-03-14 09:11:48'),
(221, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-14 09:21:48'),
(222, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-14 09:21:55'),
(223, NULL, 'Admin Login', 'admin logged into the system', '::1', '2025-03-14 09:22:53'),
(224, NULL, 'Admin Login', 'admin logged into the system', '::1', '2025-03-14 09:34:23'),
(227, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-14 09:36:50'),
(228, 115, 'User Deleted', 'User \'<b>tbtbb</b>\' (btbb@gmail.com) with role <b>Student</b> was deleted.', '::1', '2025-03-14 09:36:57'),
(229, 115, 'User Deleted', 'User \'<b>Mia Clark</b>\' (mia.clark@example.com) with role <b>Student</b> was deleted.', '::1', '2025-03-14 09:37:09'),
(230, 115, 'User Updated', '<b>William Lewiso</b> updated details: Name: <b>William Lewis</b> ➝ <b>William Lewiso</b>, Role: <b>Student</b> ➝ <b>Admin</b>, Organization: <b>Data Science Society</b> ➝ <b>Japanese Language Club</b>', '::1', '2025-03-14 09:37:24'),
(231, 115, 'User Updated', '<b>Ava Allen</b> updated details: Role: <b>Student</b> ➝ <b>Officer</b>', '::1', '2025-03-14 09:37:43'),
(232, 115, 'User Updated', '<b>Lucas Harris</b> updated details: Role: <b>Student</b> ➝ <b>Officer</b>', '::1', '2025-03-14 09:38:05'),
(233, NULL, 'Failed Admin Login', 'Admin email not found: admin@example.com', '::1', '2025-03-14 09:40:15'),
(234, NULL, 'Failed Admin Login', 'Admin email not found: falimo@example.com', '::1', '2025-03-14 09:40:23'),
(235, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-14 09:40:47'),
(236, 115, 'User Added', '<b>grgrbb</b> (<b>grgrbb@example.com</b>) added as <b>Student</b> in <b>Theater & Performing Arts</b>', '::1', '2025-03-14 09:53:51'),
(237, 115, 'User Deleted', 'User \'<b>grgrbb</b>\' (grgrbb@example.com) with role <b>Student</b> was deleted.', '::1', '2025-03-14 10:14:10'),
(238, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-14 10:21:27'),
(239, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-14 10:29:32'),
(240, 115, 'User Added', '<b>Dragon Monkey</b> (<b>dmonkey@gmail.com</b>) added as <b>Student</b> in <b>Data Science Society</b>', '::1', '2025-03-14 10:30:39'),
(241, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-14 10:48:14'),
(242, 115, 'User Added', '<b>Timothy Mule Malasarte Capote</b> (<b>temu@example.com</b>) added as <b>Admin</b> in <b>Investment & Finance Clube</b>', '::1', '2025-03-14 11:44:11'),
(243, 176, 'Admin Login', 'Timothy Mule Malasarte Capote logged into the system', '::1', '2025-03-14 18:50:38'),
(244, 176, 'Admin Login', 'Timothy Mule Malasarte Capote logged into the system', '::1', '2025-03-14 18:56:09'),
(245, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-14 19:35:43'),
(246, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 05:57:09'),
(247, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 07:17:18'),
(248, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 08:08:39'),
(249, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 08:17:15'),
(250, 115, 'User Added', '<b>leader</b> (<b>leader@example.com</b>) added as <b>Officer</b> in <b>Digital Arts Society</b>', '::1', '2025-03-15 08:26:11'),
(251, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 09:08:17'),
(252, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 09:22:37'),
(253, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 09:30:46'),
(254, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 09:50:12'),
(255, 115, 'User Added', '<b>fe malasarte</b> (<b>fe@gmiail.com</b>) added as <b>Student</b> in <b>Cultural Exchange Club</b>', '::1', '2025-03-15 09:51:52'),
(256, 115, 'User Updated', '<b>fe malasarte</b> updated details: Role: <b>Student</b> ➝ <b>Officer</b>', '::1', '2025-03-15 09:52:04'),
(257, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 09:56:10'),
(258, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 09:56:55'),
(259, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 10:00:10'),
(260, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 10:03:52'),
(261, 115, 'User Added', '<b>uiberilvrv</b> (<b>foihwerfire@example.com</b>) added as <b>Student</b> in <b>Public Speaking Society</b>', '::1', '2025-03-15 10:04:06'),
(262, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 10:11:52'),
(263, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 10:38:22'),
(264, 115, 'User Added', '<b>wjdbwdwd</b> (<b>ddfalimomina@example.com</b>) added as <b>Student</b> in <b>Japanese Language Club</b>', '::1', '2025-03-15 10:38:47'),
(265, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 10:46:01'),
(266, 115, 'User Added', '<b>jm</b> (<b>jm@example.com</b>) added as <b>Officer</b> in <b>Public Speaking Society</b>', '::1', '2025-03-15 10:47:42'),
(267, 115, 'User Updated', '<b>jm</b> updated details: Role: <b>Officer</b> ➝ <b>Student</b>', '::1', '2025-03-15 10:47:58'),
(268, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-15 10:56:44'),
(269, 115, 'User Added', '<b>Jay</b> (<b>jaym@example.com</b>) added as <b>Student</b> in <b>Cultural Exchange Club</b>', '::1', '2025-03-15 10:58:44'),
(270, 115, 'User Updated', '<b>Jay</b> updated details: Organization: <b>Cultural Exchange Club</b> ➝ <b>Theater & Performing Arts</b>', '::1', '2025-03-15 11:10:00'),
(271, 115, 'User Deleted', 'User \'<b>Jay</b>\' (jaym@example.com) with role <b>Student</b> was deleted.', '::1', '2025-03-15 11:19:28'),
(272, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-19 08:36:52'),
(273, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-19 08:38:26'),
(274, 115, 'Admin Login', 'Feli Falimomina logged into the system', '::1', '2025-03-19 08:39:13');

-- --------------------------------------------------------

--
-- Table structure for table `admin_resources`
--

DROP TABLE IF EXISTS `admin_resources`;
CREATE TABLE `admin_resources` (
  `user_id` int(11) NOT NULL,
  `emergency_contact` varchar(50) DEFAULT NULL,
  `support_email` varchar(100) DEFAULT NULL,
  `communication_channel` varchar(255) DEFAULT NULL,
  `docs_link` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_resources`
--

INSERT INTO `admin_resources` (`user_id`, `emergency_contact`, `support_email`, `communication_channel`, `docs_link`, `updated_at`) VALUES
(34, '', '', '', '', '2025-03-14 05:07:13');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcement_targets`
--

DROP TABLE IF EXISTS `announcement_targets`;
CREATE TABLE `announcement_targets` (
  `target_id` int(11) NOT NULL,
  `announcement_id` int(11) DEFAULT NULL,
  `recipient_type` enum('user','organization','role') NOT NULL,
  `recipient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `attended` tinyint(1) DEFAULT 0,
  `check_in_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `check_out_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` text NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `changed_data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_time` datetime NOT NULL,
  `venue` varchar(255) NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `org_id`, `title`, `description`, `date_time`, `venue`, `capacity`, `created_at`, `status`) VALUES
(8, 16, 'dwdw', 'dwdwdwd', '2025-03-12 06:51:00', 'dwdww', 30, '2025-03-11 22:52:03', 'pending'),
(9, 8, 'Tech Conference', 'Annual tech meetup for developers', '2024-10-05 09:00:00', 'Main Hall', 100, '2025-03-11 23:53:15', 'approved'),
(10, 11, 'AI Workshop', 'Hands-on session on AI and ML', '2024-10-12 14:00:00', 'Computer Lab', 50, '2025-03-11 23:53:15', 'pending'),
(11, 27, 'Cybersecurity Seminar', 'Discuss latest trends in security', '2024-10-20 10:00:00', 'Auditorium', 80, '2025-03-11 23:53:15', 'approved'),
(13, 25, 'Blockchain Summit', 'Exploring the future of blockchain', '2024-11-02 11:00:00', 'Auditorium', 120, '2025-03-11 23:53:15', 'approved'),
(14, 19, 'IoT Workshop', 'Hands-on experience with IoT devices', '2024-11-10 13:00:00', 'Tech Lab', 40, '2025-03-11 23:53:15', 'pending'),
(15, 27, 'Python Bootcamp', 'Learn Python from scratch', '2024-11-18 09:30:00', 'Room 102', 70, '2025-03-11 23:53:15', 'approved'),
(16, 24, 'Cloud Computing Seminar', 'Understanding cloud services', '2024-11-25 14:30:00', 'Auditorium', 90, '2025-03-11 23:53:15', 'approved'),
(18, 7, 'Networking Basics', 'Learn about computer networking', '2024-12-10 10:00:00', 'Lab 5', 50, '2025-03-11 23:53:15', 'approved'),
(19, 24, 'Quantum Computing Talk', 'Exploring the power of quantum', '2024-12-17 16:00:00', 'Conference Room A', 60, '2025-03-11 23:53:15', 'pending'),
(20, 7, 'Cybersecurity Capture the Flag', 'CTF competition for beginners', '2025-01-05 09:00:00', 'Cyber Lab', 40, '2025-03-11 23:53:15', 'approved'),
(21, 27, 'VR/AR Tech Conference', 'Experience the future of virtual reality', '2025-01-15 13:00:00', 'VR Room', 80, '2025-03-11 23:53:15', 'approved'),
(22, 21, 'Data Science Bootcamp', 'Intensive training on data science', '2025-01-25 12:00:00', 'Auditorium', 100, '2025-03-11 23:53:15', 'pending'),
(23, 22, 'Ethical Hacking Seminar', 'Understand hacking from a defensive perspective', '2025-02-05 14:00:00', 'Lab 1', 60, '2025-03-11 23:53:15', 'approved'),
(24, 13, 'Robotics Exhibition', 'Showcase of student-built robots', '2025-02-12 10:30:00', 'Engineering Hall', 120, '2025-03-11 23:53:15', 'approved'),
(25, 27, 'AI Ethics Debate', 'Discuss ethical concerns in AI', '2025-02-20 15:00:00', 'Room 204', 50, '2025-03-11 23:53:15', 'pending'),
(26, 17, '5G & Telecom Innovations', 'Understanding the impact of 5G', '2025-03-01 11:00:00', 'Auditorium', 75, '2025-03-11 23:53:15', 'approved'),
(27, 27, 'Software Engineering Trends', 'Discuss best practices in SE', '2025-03-10 14:00:00', 'Main Hall', 90, '2025-03-11 23:53:15', 'approved'),
(28, 21, 'Blockchain for Beginners', 'Introduction to blockchain tech', '2025-03-18 10:00:00', 'Tech Hub', 40, '2025-03-11 23:53:15', 'pending'),
(29, 13, 'Fintech and Crypto', 'Explore fintech and cryptocurrencies', '2025-03-22 13:30:00', 'Finance Lab', 60, '2025-03-11 23:53:15', 'approved'),
(31, 22, 'AI for Healthcare', 'Applying AI in medicine', '2025-03-28 09:30:00', 'Medical Lab', 70, '2025-03-11 23:53:15', 'pending'),
(32, 10, 'VR Gaming Experience', 'Hands-on with the latest VR games', '2025-03-30 14:00:00', 'VR Room', 50, '2025-03-11 23:53:15', 'approved'),
(34, 2, 'Cloud Security Best Practices', 'Secure your cloud environments', '2025-04-05 11:00:00', 'Cybersecurity Lab', 60, '2025-03-11 23:53:15', 'approved'),
(40, 16, 'efefe', 'fefefe', '2025-03-12 07:56:00', 'fefefe', 12, '2025-03-11 23:56:40', 'pending'),
(41, 12, 'qqq', 'qqq', '2025-03-12 09:29:00', 'Campus Amphitheater', 25, '2025-03-12 01:29:21', 'pending'),
(42, 7, 'qaqaqa', 'aqaqaqa', '2025-03-12 09:30:00', 'UM Gymnasium', 65, '2025-03-12 01:30:40', 'pending'),
(43, 9, 'Golden Hour Workshop', 'Learn to capture the best natural lighting during golden hour.', '2025-04-01 17:30:00', 'University Park', 50, '2025-03-14 19:30:21', 'approved'),
(44, 9, 'Portrait Photography 101', 'A session on mastering the art of portrait photography.', '2025-04-05 14:00:00', 'Photography Studio Room B', 30, '2025-03-14 19:30:21', 'approved'),
(45, 9, 'Street Photography Walk', 'Explore the city and capture stunning street moments.', '2025-04-10 09:00:00', 'Downtown Area', 25, '2025-03-14 19:30:21', 'approved'),
(46, 9, 'Nature Photography Trip', 'A day trip to photograph landscapes and wildlife.', '2025-04-15 07:00:00', 'National Forest Reserve', 40, '2025-03-14 19:30:21', 'approved'),
(47, 9, 'Post-Processing Techniques', 'A tutorial on using Lightroom and Photoshop.', '2025-04-20 16:00:00', 'Computer Lab 1', 20, '2025-03-14 19:30:21', 'approved'),
(48, 9, 'Night Photography Session', 'Capture the beauty of the night sky and city lights.', '2025-04-25 19:30:00', 'Skyview Observatory', 30, '2025-03-14 19:30:21', 'approved'),
(49, 9, 'Photography Equipment Demo', 'Try out various professional cameras and lenses.', '2025-04-30 11:00:00', 'Main Hall', 35, '2025-03-14 19:30:21', 'approved'),
(50, 9, 'Macro Photography Workshop', 'Learn to capture intricate details up close.', '2025-05-05 13:30:00', 'Biology Garden', 25, '2025-03-14 19:30:21', 'approved'),
(51, 9, 'Fashion Photography Shoot', 'Experience a professional fashion photoshoot setup.', '2025-05-10 15:00:00', 'Studio A', 30, '2025-03-14 19:30:21', 'approved'),
(52, 9, 'Event Coverage Training', 'Learn the skills needed for covering live events.', '2025-05-15 10:00:00', 'Auditorium', 40, '2025-03-14 19:30:21', 'approved'),
(53, 9, 'Drone Photography Basics', 'Introduction to drone photography and videography.', '2025-05-20 09:30:00', 'Sports Field', 20, '2025-03-14 19:30:21', 'approved'),
(54, 9, 'Landscape Photography Hike', 'Capture breathtaking landscapes while hiking.', '2025-05-25 06:00:00', 'Mountain Trail', 30, '2025-03-14 19:30:21', 'approved'),
(55, 9, 'Self-Portrait Challenge', 'Master the art of taking creative self-portraits.', '2025-05-30 14:30:00', 'Studio B', 20, '2025-03-14 19:30:21', 'approved'),
(56, 9, 'Photo Editing Masterclass', 'Advanced techniques in Lightroom and Photoshop.', '2025-06-05 16:00:00', 'Multimedia Lab', 25, '2025-03-14 19:30:21', 'approved'),
(57, 9, 'Wildlife Photography Safari', 'A guided tour focused on wildlife photography.', '2025-06-10 07:00:00', 'Wildlife Reserve', 20, '2025-03-14 19:30:21', 'approved'),
(58, 9, 'Abstract Photography Experiment', 'Unleash creativity with abstract photography techniques.', '2025-06-15 12:00:00', 'Art Gallery', 25, '2025-03-14 19:30:21', 'approved'),
(59, 9, 'Social Media Photography Strategies', 'Learn how to take and edit photos for online engagement.', '2025-06-20 14:00:00', 'Library Conference Room', 30, '2025-03-14 19:30:21', 'approved'),
(63, NULL, 'g875tdf,c.s6y5htdfkm,cls.', 'tygidnjkcm34goidc,s', '2025-03-15 14:21:00', 'Campus Amphitheater', 87, '2025-03-15 06:21:50', 'pending'),
(64, NULL, 'g875tdf,c.s6y5htdfkm,cls.', 'tygidnjkcm34goidc,s', '2025-03-15 14:21:00', 'Campus Amphitheater', 87, '2025-03-15 06:21:54', 'pending'),
(65, NULL, 'g875tdf,c.s6y5htdfkm,cls.', 'tygidnjkcm34goidc,s', '2025-03-15 14:21:00', 'Campus Amphitheater', 87, '2025-03-15 06:23:15', 'pending'),
(66, 12, 'today', 'erjkdfcm,x', '2025-03-15 14:24:00', 'Campus Amphitheater', 1234, '2025-03-15 06:24:34', 'pending'),
(67, 9, 'BABANG', 'U4RWIDKJN', '2025-03-15 14:32:00', 'FEWSD', 5432, '2025-03-15 06:32:33', 'pending'),
(68, 13, 'rjgner;jknw', 'wefjbwfipenj', '2025-03-15 14:34:00', 'Campus Amphitheater', 4367, '2025-03-15 06:34:34', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `event_status`
--

DROP TABLE IF EXISTS `event_status`;
CREATE TABLE `event_status` (
  `status_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reason` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_status`
--

INSERT INTO `event_status` (`status_id`, `event_id`, `status`, `reason`, `updated_at`) VALUES
(1, 34, 'pending', NULL, '2025-03-12 04:54:33'),
(5, 18, 'pending', NULL, '2025-03-12 04:54:33'),
(6, 20, 'pending', NULL, '2025-03-12 04:54:33'),
(7, 42, 'pending', NULL, '2025-03-12 04:54:33'),
(8, 9, 'pending', NULL, '2025-03-12 04:54:33'),
(9, 32, 'pending', NULL, '2025-03-12 04:54:33'),
(10, 10, 'pending', NULL, '2025-03-12 04:54:33'),
(11, 41, 'pending', NULL, '2025-03-12 04:54:33'),
(12, 24, 'pending', NULL, '2025-03-12 04:54:33'),
(13, 29, 'pending', NULL, '2025-03-12 04:54:33'),
(14, 8, 'pending', NULL, '2025-03-12 04:54:33'),
(15, 40, 'pending', NULL, '2025-03-12 04:54:33'),
(16, 26, 'pending', NULL, '2025-03-12 04:54:33'),
(18, 14, 'pending', NULL, '2025-03-12 04:54:33'),
(19, 22, 'pending', NULL, '2025-03-12 04:54:33'),
(20, 28, 'pending', NULL, '2025-03-12 04:54:33'),
(21, 23, 'pending', NULL, '2025-03-12 04:54:33'),
(22, 31, 'pending', NULL, '2025-03-12 04:54:33'),
(24, 16, 'pending', NULL, '2025-03-12 04:54:33'),
(25, 19, 'pending', NULL, '2025-03-12 04:54:33'),
(26, 13, 'pending', NULL, '2025-03-12 04:54:33'),
(27, 11, 'pending', NULL, '2025-03-12 04:54:33'),
(28, 15, 'pending', NULL, '2025-03-12 04:54:33'),
(29, 21, 'pending', NULL, '2025-03-12 04:54:33'),
(30, 25, 'pending', NULL, '2025-03-12 04:54:33'),
(31, 27, 'pending', NULL, '2025-03-12 04:54:33'),
(68, 16, 'rejected', 'no spr', '2025-03-12 20:54:26'),
(69, 16, 'approved', NULL, '2025-03-12 20:54:29'),
(70, 32, 'approved', NULL, '2025-03-14 10:35:56'),
(71, 31, 'rejected', 'sds', '2025-03-14 10:36:06');

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

DROP TABLE IF EXISTS `membership`;
CREATE TABLE `membership` (
  `membership_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('member','officer') DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`membership_id`, `user_id`, `org_id`, `status`, `created_at`, `role`) VALUES
(2, 3, 2, 'approved', '2025-03-11 14:39:54', 'member'),
(3, 4, 3, 'approved', '2025-03-11 14:39:54', 'member'),
(4, 5, 4, 'approved', '2025-03-11 14:39:54', 'member'),
(5, 6, 5, 'approved', '2025-03-11 14:39:54', 'member'),
(6, 7, 6, 'approved', '2025-03-11 14:39:54', 'member'),
(7, 8, 7, 'approved', '2025-03-11 14:39:54', 'member'),
(8, 9, 8, 'approved', '2025-03-11 14:39:54', 'member'),
(9, 10, 9, 'approved', '2025-03-11 14:39:54', 'member'),
(11, 12, 15, 'approved', '2025-03-11 14:39:54', 'member'),
(12, 13, 12, 'approved', '2025-03-11 14:39:54', 'member'),
(13, 14, 13, 'approved', '2025-03-11 14:39:54', 'member'),
(14, 15, 14, 'approved', '2025-03-11 14:39:54', 'member'),
(15, 16, 15, 'approved', '2025-03-11 14:39:54', 'member'),
(16, 17, 16, 'approved', '2025-03-11 14:39:54', 'member'),
(17, 18, 17, 'approved', '2025-03-11 14:39:54', 'member'),
(19, 20, 19, 'approved', '2025-03-11 14:39:54', 'member'),
(21, 21, 17, 'approved', '2025-03-11 19:27:33', 'officer'),
(22, 22, 7, 'approved', '2025-03-11 19:29:14', 'officer'),
(24, 24, 14, 'approved', '2025-03-11 19:35:57', 'officer'),
(28, 28, 10, 'approved', '2025-03-11 19:47:49', 'officer'),
(57, 88, 12, 'approved', '2025-03-12 08:50:46', 'member'),
(58, 87, 5, 'approved', '2025-03-12 08:50:48', 'member'),
(61, 53, 14, 'approved', '2025-03-12 08:53:19', 'member'),
(64, 71, 15, 'approved', '2025-03-12 09:07:08', 'officer'),
(65, 70, 14, 'approved', '2025-03-12 09:07:12', 'officer'),
(79, 82, 7, 'approved', '2025-03-12 20:46:57', 'member'),
(81, 74, 12, 'approved', '2025-03-12 20:47:43', 'officer'),
(84, 69, 16, 'approved', '2025-03-12 20:48:03', 'officer'),
(85, 68, 13, 'approved', '2025-03-12 20:48:06', 'member'),
(86, 67, 16, 'approved', '2025-03-12 20:48:09', 'officer'),
(87, 66, 15, 'approved', '2025-03-12 20:48:12', 'member'),
(89, 63, 2, 'approved', '2025-03-12 20:48:26', 'member'),
(90, 62, 4, 'approved', '2025-03-12 20:48:34', 'member'),
(91, 61, 4, 'approved', '2025-03-12 20:48:39', 'member'),
(92, 52, 5, 'approved', '2025-03-12 20:48:45', 'officer'),
(93, 67, 27, 'pending', '2025-03-12 20:51:22', 'officer'),
(96, 22, 26, 'pending', '2025-03-12 20:52:17', 'officer'),
(97, 31, 25, 'pending', '2025-03-12 20:52:22', 'officer'),
(100, 67, 21, 'pending', '2025-03-12 20:52:34', 'officer'),
(101, 69, 10, 'pending', '2025-03-12 20:52:38', 'officer'),
(104, 48, 13, 'pending', '2025-03-12 20:52:56', 'officer'),
(107, 44, 15, 'pending', '2025-03-12 20:53:10', 'officer'),
(108, 41, 17, 'pending', '2025-03-12 20:53:17', 'officer'),
(115, 31, 6, 'pending', '2025-03-12 20:53:52', 'officer'),
(117, 24, 8, 'pending', '2025-03-12 20:54:05', 'officer'),
(119, 114, 14, 'approved', '2025-03-12 20:55:05', 'member'),
(122, 115, 11, 'approved', '2025-03-12 23:23:55', 'member'),
(134, 127, 15, 'approved', '2025-03-13 13:59:32', 'member'),
(135, 128, 1, 'approved', '2025-03-14 03:20:12', 'officer'),
(136, 129, 12, 'approved', '2025-03-14 03:27:40', 'officer'),
(137, 130, 14, 'approved', '2025-03-14 03:28:46', 'member'),
(138, 131, 4, 'approved', '2025-03-14 03:32:01', 'member'),
(139, 132, 15, 'approved', '2025-03-14 03:33:46', 'officer'),
(140, 133, 13, 'approved', '2025-03-14 03:34:40', 'member'),
(141, 134, 16, 'approved', '2025-03-14 03:37:58', 'member'),
(143, 136, 13, 'approved', '2025-03-14 03:38:45', 'member'),
(144, 137, 17, 'approved', '2025-03-14 03:41:33', 'member'),
(145, 138, 16, 'approved', '2025-03-14 03:42:34', 'member'),
(146, 139, 12, 'approved', '2025-03-14 03:42:55', 'officer'),
(147, 140, 16, 'approved', '2025-03-14 03:47:11', 'member'),
(148, 141, 17, 'approved', '2025-03-14 03:49:00', 'member'),
(149, 142, 13, 'approved', '2025-03-14 03:52:20', 'member'),
(150, 143, 17, 'approved', '2025-03-14 03:58:48', 'member'),
(153, 146, 16, 'approved', '2025-03-14 04:05:41', 'member'),
(155, 148, 5, 'approved', '2025-03-14 04:07:41', 'officer'),
(157, 150, 16, 'approved', '2025-03-14 04:08:35', 'officer'),
(158, 151, 36, 'approved', '2025-03-14 04:09:20', 'member'),
(159, 152, 17, 'approved', '2025-03-14 04:11:10', 'member'),
(161, 154, 26, 'approved', '2025-03-14 04:14:38', 'member'),
(162, 155, 13, 'approved', '2025-03-14 04:15:01', 'member'),
(163, 156, 13, 'approved', '2025-03-14 04:15:23', 'member'),
(164, 157, 15, 'approved', '2025-03-14 04:15:37', 'member'),
(165, 158, 17, 'approved', '2025-03-14 04:15:53', 'member'),
(166, 159, 15, 'approved', '2025-03-14 04:16:25', 'member'),
(167, 160, 9, 'approved', '2025-03-14 04:17:09', 'member'),
(168, 161, 9, 'approved', '2025-03-14 04:19:23', 'member'),
(170, 163, 13, 'approved', '2025-03-14 04:21:36', 'member'),
(172, 165, 17, 'approved', '2025-03-14 04:29:43', 'officer'),
(173, 166, 15, 'approved', '2025-03-14 04:30:02', 'member'),
(174, 167, 9, 'approved', '2025-03-14 04:32:10', 'member'),
(175, 168, 9, 'approved', '2025-03-14 04:32:54', 'member'),
(176, 169, 9, 'approved', '2025-03-14 04:35:31', 'member'),
(177, 170, 9, 'approved', '2025-03-14 04:36:23', 'member'),
(178, 171, 9, 'approved', '2025-03-14 05:01:01', 'member'),
(179, 172, 9, 'approved', '2025-03-14 05:01:15', 'member'),
(181, 30, 9, 'approved', '2025-03-14 05:01:44', 'member'),
(182, 33, 9, 'approved', '2025-03-14 05:01:48', 'member'),
(183, 32, 9, 'approved', '2025-03-14 05:01:51', 'member'),
(184, 35, 9, 'approved', '2025-03-14 05:02:09', 'member'),
(185, 45, 9, 'approved', '2025-03-14 05:02:27', 'member'),
(186, 47, 9, 'approved', '2025-03-14 05:02:34', 'member'),
(187, 49, 9, 'approved', '2025-03-14 05:02:37', 'member'),
(188, 50, 9, 'approved', '2025-03-14 05:02:40', 'member'),
(189, 51, 9, 'approved', '2025-03-14 05:02:46', 'member'),
(190, 55, 9, 'approved', '2025-03-14 05:03:04', 'member'),
(191, 56, 9, 'approved', '2025-03-14 05:03:08', 'member'),
(192, 58, 9, 'approved', '2025-03-14 05:03:15', 'member'),
(193, 59, 9, 'approved', '2025-03-14 05:03:18', 'member'),
(194, 43, 9, 'approved', '2025-03-14 05:03:24', 'member'),
(195, 69, 36, 'pending', '2025-03-14 09:42:19', 'officer'),
(197, 175, 11, 'approved', '2025-03-14 10:30:39', 'member'),
(200, 176, 12, 'approved', '2025-03-14 11:44:11', 'member'),
(202, 177, 4, 'approved', '2025-03-15 08:26:11', 'officer'),
(207, 67, 40, 'pending', '2025-03-15 08:39:43', 'officer'),
(208, 70, 39, 'pending', '2025-03-15 08:45:36', 'officer'),
(209, 31, 38, 'pending', '2025-03-15 08:47:02', 'officer'),
(213, 34, 9, 'pending', '2025-03-15 09:10:08', 'officer'),
(214, 178, 5, 'approved', '2025-03-15 09:51:52', 'member'),
(215, 44, 41, 'pending', '2025-03-15 10:00:54', 'officer'),
(216, 44, 42, 'pending', '2025-03-15 10:00:58', 'officer'),
(217, 44, 43, 'pending', '2025-03-15 10:00:59', 'officer'),
(218, 44, 44, 'pending', '2025-03-15 10:00:59', 'officer'),
(219, 67, 45, 'pending', '2025-03-15 10:01:57', 'officer'),
(221, 179, 16, 'approved', '2025-03-15 10:04:06', 'member'),
(222, 67, 46, 'pending', '2025-03-15 10:05:01', 'officer'),
(223, 69, 47, 'pending', '2025-03-15 10:05:11', 'officer'),
(226, 74, 50, 'pending', '2025-03-15 10:12:03', 'officer'),
(227, 67, 51, 'pending', '2025-03-15 10:13:16', 'officer'),
(228, 69, 52, 'pending', '2025-03-15 10:20:57', 'officer'),
(232, 69, 53, 'pending', '2025-03-15 10:27:02', 'officer'),
(233, 180, 15, 'approved', '2025-03-15 10:38:47', 'member'),
(234, 71, 56, 'pending', '2025-03-15 10:38:58', 'officer'),
(235, 181, 16, 'approved', '2025-03-15 10:47:42', 'officer');

--
-- Triggers `membership`
--
DELIMITER $$
CREATE TRIGGER `before_insert_membership` BEFORE INSERT ON `membership` FOR EACH ROW BEGIN
    DECLARE user_role INT;
    
    
    SELECT role_id INTO user_role FROM users WHERE user_id = NEW.user_id;
    
    
    IF user_role = 2 THEN
        SET NEW.role = 'officer';
    ELSEIF user_role = 3 THEN
        SET NEW.role = 'member';
    ELSE
        SET NEW.role = 'member'; 
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

DROP TABLE IF EXISTS `organizations`;
CREATE TABLE `organizations` (
  `org_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT 'assets/images/default-org.png',
  `description` text NOT NULL,
  `status` enum('active','disbanded','pending') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `leader_id` int(11) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`org_id`, `name`, `logo`, `description`, `status`, `created_at`, `category_id`, `leader_id`, `website`, `facebook`, `twitter`, `instagram`) VALUES
(1, 'Tech Innovators Club', '/assets/images/orgs/org_1_1742031033.png', 'A club for students interested in technology and coding.', 'active', '2025-03-11 14:39:22', 1, 128, NULL, NULL, NULL, NULL),
(2, 'Entrepreneurial Minds', '/assets/images/default-org.png', 'A platform for students interested in startups and business.', 'active', '2025-03-11 14:39:22', 2, 63, NULL, NULL, NULL, NULL),
(3, 'Helping Hands Community', '/assets/images/default-org.png', 'Focused on charity and volunteer work.', 'active', '2025-03-11 14:39:22', 3, 4, NULL, NULL, NULL, NULL),
(4, 'Digital Arts Society', '/assets/images/default-org.png', 'A club for graphic designers and video editors.', 'active', '2025-03-11 14:39:22', 4, 62, NULL, NULL, NULL, NULL),
(5, 'Cultural Exchange Club', '/assets/images/default-org.png', 'A club promoting diverse cultural experiences.', 'active', '2025-03-11 14:39:22', 5, 6, NULL, NULL, NULL, NULL),
(6, 'Future Leaders Association', '/assets/images/default-org.png', 'Developing leadership skills for students.', 'active', '2025-03-11 14:39:22', 6, 31, NULL, NULL, NULL, NULL),
(7, 'Christian Youth Fellowship', '/assets/images/default-org.png', 'A religious organization for Christian students.', 'active', '2025-03-11 14:39:22', 7, 22, NULL, NULL, NULL, NULL),
(8, 'Academic Excellence Guild', '/assets/images/default-org.png', 'For high-achieving students in academics.', 'active', '2025-03-11 14:39:22', 8, 9, NULL, NULL, NULL, NULL),
(9, 'Photography Enthusiasts', '/assets/images/orgs/org_9_1742035086.png', 'For students passionate about photography.', 'active', '2025-03-11 14:39:22', 9, 34, 'igfhkjlnfej', 'fi43vnoumf24we', '4fnuefmpuwie', '48v3n0pmewv'),
(10, 'Varsity Basketball Club', '/assets/images/default-org.png', 'The official basketball team of the university.', 'active', '2025-03-11 14:39:22', 10, 28, NULL, NULL, NULL, NULL),
(11, 'Data Science Society', '/assets/images/default-org.png', 'For students interested in AI, ML, and Big Data.', 'active', '2025-03-11 14:39:22', 1, 175, NULL, NULL, NULL, NULL),
(12, 'Investment & Finance Clube', '/assets/images/default-org.png', 'Learning stock markets and investment strategies.', 'active', '2025-03-11 14:39:22', 2, 129, NULL, NULL, NULL, NULL),
(13, 'Animal Welfare Group', '/assets/images/default-org.png', 'Protecting and rescuing animals in need.', 'active', '2025-03-11 14:39:22', 3, 68, NULL, NULL, NULL, NULL),
(14, 'Theater & Performing Arts', '/assets/images/default-org.png', 'For students who love acting and theater.', 'active', '2025-03-11 14:39:22', 4, 15, NULL, NULL, NULL, NULL),
(15, 'Japanese Language Club', '/assets/images/default-org.png', 'Learning Japanese language and culture.', 'active', '2025-03-11 14:39:22', 5, 132, NULL, NULL, NULL, NULL),
(16, 'Public Speaking Society', '/assets/images/default-org.png', 'Developing confidence in speaking and debate.', 'active', '2025-03-11 14:39:22', 6, 138, NULL, NULL, NULL, NULL),
(17, 'Islamic Student Organization', '/assets/images/default-org.png', 'A group for Islamic faith students.', 'active', '2025-03-11 14:39:22', 7, 21, NULL, NULL, NULL, NULL),
(19, 'Esports Gaming Club', '/assets/images/default-org.png', 'Competitive gaming for university students.', 'disbanded', '2025-03-11 14:39:22', 9, 20, NULL, NULL, NULL, NULL),
(21, 'qrqrqerqr', '/assets/images/default-org.png', 'rrerq', 'active', '2025-03-11 20:29:02', 10, 67, NULL, NULL, NULL, NULL),
(22, 'wdwdwd', '/assets/images/default-org.png', 'wdwwwd', 'active', '2025-03-11 20:31:16', 9, 0, NULL, NULL, NULL, NULL),
(23, 'aqaqaq', '/assets/images/default-org.png', 'dwdwdw', 'active', '2025-03-11 20:39:30', 10, 0, NULL, NULL, NULL, NULL),
(24, 'dwdwdw', '/assets/images/default-org.png', 'dwdwwd', 'active', '2025-03-11 20:44:35', 10, 0, NULL, NULL, NULL, NULL),
(25, 'sqqqsqs', '/assets/images/default-org.png', 'qsqqsq', 'active', '2025-03-11 20:54:26', 9, 31, NULL, NULL, NULL, NULL),
(26, 'wddwd', '/assets/images/default-org.png', 'dwdwdw', 'active', '2025-03-11 21:01:09', 10, 154, NULL, NULL, NULL, NULL),
(27, 'cookie baking', '/assets/images/default-org.png', 'we bake', 'active', '2025-03-11 21:03:30', 9, 67, NULL, NULL, NULL, NULL),
(36, 'flower genz', '/assets/images/default-org.png', 'we sell flowers', 'active', '2025-03-12 20:52:00', 9, 151, NULL, NULL, NULL, NULL),
(38, 'Bookkeeping', '/assets/images/orgs/org_38_1742028422.png', 'for bookkeeper course here', 'active', '2025-03-14 10:33:34', 3, 31, NULL, NULL, NULL, NULL),
(39, 'asasa', '/assets/images/orgs/org_39_1742028336.png', 'dsdsd', 'active', '2025-03-14 10:37:17', 7, 70, NULL, NULL, NULL, NULL),
(40, 'rayang', '/assets/images/orgs/org_40_1742027982.png', 'dayang ryan', 'active', '2025-03-15 06:34:56', 9, 67, NULL, NULL, NULL, NULL),
(41, 'Data Science Society', 'assets/images/default-org.png', 'wwewewe', 'active', '2025-03-15 10:00:54', 1, 0, NULL, NULL, NULL, NULL),
(42, 'Data Science Society', 'assets/images/default-org.png', 'wwewewe', 'active', '2025-03-15 10:00:58', 1, 0, NULL, NULL, NULL, NULL),
(43, 'Data Science Society', 'assets/images/default-org.png', 'wwewewe', 'active', '2025-03-15 10:00:59', 1, 0, NULL, NULL, NULL, NULL),
(44, 'Data Science Society', 'assets/images/default-org.png', 'wwewewe', 'active', '2025-03-15 10:00:59', 1, 0, NULL, NULL, NULL, NULL),
(45, 'weiygfweifwuf', 'assets/images/default-org.png', 'ufhweoifu', 'active', '2025-03-15 10:01:57', 8, 0, NULL, NULL, NULL, NULL),
(46, 'weiygfweifwuf', '/assets/images/orgs/org_46_1742033101.png', 'ufhweoifu', 'active', '2025-03-15 10:01:58', 8, 0, NULL, NULL, NULL, NULL),
(47, 'iufiruf', 'assets/images/default-org.png', 'oih3oih43f', 'active', '2025-03-15 10:05:11', 8, 0, NULL, NULL, NULL, NULL),
(50, 'wrguwiefuhwe', 'assets/images/default-org.png', 'wjfbwifbewf', 'active', '2025-03-15 10:12:03', 8, 0, NULL, NULL, NULL, NULL),
(51, 'weuogrwiu', 'assets/images/default-org.png', 'wiryowir', 'active', '2025-03-15 10:13:16', 8, 0, NULL, NULL, NULL, NULL),
(52, 'ouebfoewufh', 'assets/images/default-org.png', 'iwhtoiwfw', 'active', '2025-03-15 10:20:57', 9, 0, NULL, NULL, NULL, NULL),
(53, 'ouebfoewufh', '/assets/images/orgs/org_53_1742034422.png', 'iwhtoiwfw', 'active', '2025-03-15 10:20:58', 9, 0, NULL, NULL, NULL, NULL),
(56, 'dudhiuw', 'assets/images/default-org.png', 'wijdwodi', 'active', '2025-03-15 10:38:58', 9, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `org_categories`
--

DROP TABLE IF EXISTS `org_categories`;
CREATE TABLE `org_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `org_categories`
--

INSERT INTO `org_categories` (`category_id`, `category_name`) VALUES
(1, 'Academic & Professional'),
(2, 'Business & Entrepreneurship'),
(3, 'Community & Service'),
(4, 'Creative & Media'),
(5, 'Cultural'),
(6, 'Leadership & Advocacy'),
(7, 'Religious'),
(8, 'Scholarship & Academic Excellence'),
(9, 'Special Interest'),
(10, 'Sports & Recreation');

-- --------------------------------------------------------

--
-- Table structure for table `org_resources`
--

DROP TABLE IF EXISTS `org_resources`;
CREATE TABLE `org_resources` (
  `id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `support_email` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `docs_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `org_resources`
--

INSERT INTO `org_resources` (`id`, `org_id`, `support_email`, `contact_person`, `docs_link`) VALUES
(1, 9, 'iiehnuirvkejnpgvi@gmail.com', 'rujnimergrp', 'mrvugneoiemo');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `name`) VALUES
(1, 'admin'),
(2, 'leader'),
(3, 'student');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `sub_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT 'default.jpg',
  `bio` text DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `role_id`, `org_id`, `status`, `created_at`, `profile_pic`, `bio`, `gender`, `birthdate`, `phone`, `address`, `student_id`, `course`, `designation`, `last_login`, `updated_at`) VALUES
(3, 'Emma Brown', 'emma.brown@example.com', 'hashedpassword', 3, 2, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(4, 'James Wilson', 'james.wilson@example.com', 'hashedpassword', 3, 3, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(5, 'Olivia Martinez', 'olivia.martinez@example.com', 'hashedpassword', 3, 4, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(6, 'Liam Anderson', 'liam.anderson@example.com', 'hashedpassword', 3, 5, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(7, 'Sophia Thomas', 'sophia.thomas@example.com', 'hashedpassword', 3, 6, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(8, 'Benjamin Jackson', 'benjamin.jackson@example.com', 'hashedpassword', 3, 7, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(9, 'Isabella White', 'isabella.white@example.com', 'hashedpassword', 3, 8, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(10, 'Lucas Harris', 'lucas.harris@example.com', 'hashedpassword', 2, 9, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(12, 'William Lewiso', 'william.lewis@example.com', 'hashedpassword', 1, 15, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(13, 'Evelyn Walker', 'evelyn.walker@example.com', 'hashedpassword', 3, 12, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(14, 'Henry Hall', 'henry.hall@example.com', 'hashedpassword', 3, 13, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(15, 'Ava Allen', 'ava.allen@example.com', 'hashedpassword', 2, 14, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(16, 'Alexander Young', 'alexander.young@example.com', 'hashedpassword', 3, 15, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(17, 'Charlotte Scott', 'charlotte.scott@example.com', 'hashedpassword', 3, 16, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(18, 'Daniel King', 'daniel.king@example.com', 'hashedpassword', 3, 17, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(19, 'Amelia Adams', 'amelia.adams@example.com', 'hashedpassword', 3, NULL, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-13 13:06:17'),
(20, 'Matthew Nelsonsss', 'matthew.nelsonsss@example.com', 'hashedpassword', 1, 19, 'active', '2025-03-11 14:39:19', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(21, 'Fe Malasarte', 'efefeefeff@example.com', '$2y$10$OjPhO7YK8/1WFnxAYQhC3OhvyaFBM0DzRYFBTSkXaSAIH9ZL/xApe', 2, 17, 'inactive', '2025-03-11 19:27:33', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(22, 'Janna Malasarteahhahhah', 'hannnaaa@example.com', '$2y$10$oeBQg7wS292V0zcYCYyCseKulUveL6mDVLcYRLxA9joYu7xduYwyy', 2, 7, 'active', '2025-03-11 19:29:14', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(24, 'joevn', 'joevancapote@gmail.com', '$2y$10$0cyL3vr1VrVsf9ffgqsor.cWlVt/oV1PH2Ub9RI1mN32BlHFlyBI6', 2, 14, 'inactive', '2025-03-11 19:35:57', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(28, 'dadadaddad', 'adddadad@example.com', '$2y$10$dJGYJp6c25051GEjwTavquLTU30lJSq4UwR1/EbchUi/ff4U9SCS6', 2, 10, 'active', '2025-03-11 19:47:49', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(30, 'John Doe', 'john.doe1@example.com', 'password123', 3, 9, 'active', '2024-10-01 04:00:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(31, 'Jane Smith', 'jane.smith2@example.com', 'password123', 2, 25, 'inactive', '2024-10-02 06:30:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(32, 'Mike Johnson', 'mike.johnson3@example.com', 'password123', 1, 9, 'active', '2024-10-05 01:15:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(33, 'Emily Brown', 'emily.brown4@example.com', 'password123', 3, 9, 'active', '2024-10-10 02:30:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(34, 'David Wilson', 'david.wilson5@example.com', 'password123', 2, 9, 'active', '2024-10-12 05:00:00', 'assets/images/profile/user_34_1741944767.png', 'lead of photog', 'male', '2025-03-14', '0909098967', 'Nicanor Reyes St, Sampaloc, Manila, 1008 Metro Manila, 菲律宾', NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(35, 'Sophia Taylor', 'sophia.taylor6@example.com', 'password123', 3, 9, 'active', '2024-10-15 03:45:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(36, 'James Anderson', 'james.anderson7@example.com', 'password123', 1, NULL, 'inactive', '2024-10-20 08:20:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-13 13:06:17'),
(38, 'Oliver Thomas', 'oliver.thomas9@example.com', 'password123', 3, NULL, 'active', '2024-10-25 07:25:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-13 13:06:17'),
(39, 'Ava Harris', 'ava.harris10@example.com', 'password123', 1, NULL, 'inactive', '2024-10-28 09:00:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-13 13:06:17'),
(40, 'William Lewis', 'william.lewis11@example.com', 'password123', 3, NULL, 'active', '2024-11-01 01:30:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-13 13:06:17'),
(41, 'Mason Walker', 'mason.walker12@example.com', 'password123', 2, 17, 'inactive', '2024-11-03 02:15:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(42, 'Isabella Hall', 'isabella.hall13@example.com', 'password123', 1, NULL, 'active', '2024-11-06 04:00:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-13 13:06:17'),
(43, 'Benjamin Young', 'benjamin.young14@example.com', 'password123', 3, 9, 'active', '2024-11-09 05:30:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(44, 'Lucas King', 'lucas.king15@example.com', 'password123', 2, 15, 'inactive', '2024-11-12 06:30:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(45, 'Mia Scott', 'mia.scott16@example.com', 'password123', 1, 9, 'active', '2024-11-16 02:20:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(47, 'Amelia Nelson', 'amelia.nelson18@example.com', 'password123', 3, 9, 'active', '2024-11-20 08:50:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(48, 'Henry Carter', 'henry.carter19@example.com', 'password123', 2, 13, 'inactive', '2024-11-25 01:45:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(49, 'Sebastian Perez', 'sebastian.perez20@example.com', 'password123', 3, 9, 'active', '2024-12-01 05:00:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(50, 'Zoe White', 'zoe.white21@example.com', 'password123', 1, 9, 'inactive', '2024-12-03 06:15:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(51, 'Jack Harris', 'jack.harris22@example.com', 'password123', 3, 9, 'active', '2024-12-06 01:40:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(52, 'Lily Robinson', 'lily.robinson23@example.com', 'password123', 2, 5, 'inactive', '2024-12-08 03:30:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(53, 'Gabriel Lee', 'gabriel.lee24@example.com', 'password123', 1, 14, 'active', '2024-12-10 08:25:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(55, 'Samuel Wright', 'samuel.wright26@example.com', 'password123', 3, 9, 'active', '2024-12-14 00:40:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(56, 'Liam Martinez', 'liam.martinez27@example.com', 'password123', 1, 9, 'inactive', '2024-12-16 04:30:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(58, 'Jackson Scott', 'jackson.scott29@example.com', 'password123', 1, 9, 'inactive', '2024-12-20 05:50:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(59, 'Leah Lopez', 'leah.lopez30@example.com', 'password123', 3, 9, 'active', '2024-12-22 07:20:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(61, 'Victoria Evans', 'victoria.evans32@example.com', 'password123', 3, 4, 'active', '2025-01-01 06:00:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(62, 'David Green', 'david.green33@example.com', 'password123', 1, 4, 'active', '2025-01-02 08:25:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(63, 'Lucas Walker', 'lucas.walker34@example.com', 'password123', 3, 2, 'active', '2025-01-04 01:30:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(66, 'Sophia Harris', 'sophia.harris37@example.com', 'password123', 1, 15, 'inactive', '2025-01-10 05:30:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(67, 'Jackson Thomas', 'jackson.thomas38@example.com', 'password123', 2, 16, 'inactive', '2025-01-12 04:45:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(68, 'Olivia Lopez', 'olivia.lopez39@example.com', 'password123', 1, 13, 'inactive', '2025-01-14 06:15:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(69, 'John White', 'john.white40@example.com', 'password123', 2, 16, 'active', '2025-01-16 03:30:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(70, 'Matthew Robinson', 'matthew.robinson41@example.com', 'password123', 2, 14, 'inactive', '2025-01-18 05:00:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(71, 'Emily Wilson', 'emily.wilson42@example.com', 'password123', 2, 15, 'active', '2025-01-20 02:20:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(74, 'Elija Hally', 'elijah.hall45y@example.com', 'password123', 2, 12, 'active', '2025-01-26 08:25:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(82, 'Jane Doe', 'janedoe@example.com', 'securepassword', 3, 14, 'active', '2025-03-12 06:10:27', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-13 13:06:17'),
(87, 'eeee', 'eeee@example.com', '$2y$10$Xul/euW3PlRZknz9qI1iPOClyXVQHga4muKjodqNrQVOMMn/e2QYO', 3, 5, 'active', '2025-03-12 06:42:54', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(88, 'eeeeeeee', 'eeeeeeeeeee@example.com', '$2y$10$Uz9bwAMuGyOsGcwyF/zIsupsUxehP88coeVKyYCxS5yegsYQmoG4a', 1, 12, 'active', '2025-03-12 06:44:03', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(114, 'allen managa', 'managa@example.com', 'admin123', 3, 14, 'active', '2025-03-12 20:55:05', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(115, 'Feli Falimomina', 'falimomina@example.com', 'admin123', 1, 11, 'active', '2025-03-12 23:23:55', 'assets/images/profile/user_115_1741948269.png', 'One hundred texts', 'male', NULL, '09128128341', 'Davao City', NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(127, 'wency manalow', 'manalow@gmail.com.com', 'admin123', 2, 15, 'active', '2025-03-13 13:47:36', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(128, 'aaaaaaaaaaaaaaaa', 'aaaaaaaaaaaa@example.com', 'admin123', 2, 1, 'active', '2025-03-14 03:20:12', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(129, 'aaaaabababa', 'aaaaabababa@example.com', 'admin123', 2, 12, 'active', '2025-03-14 03:27:40', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(130, 'ax', 'ax@example.com', 'admin123', 3, 14, 'active', '2025-03-14 03:28:46', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(131, 'asasa', 'asasa@example.com', 'admin123', 3, 4, 'active', '2025-03-14 03:32:01', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(132, 'axe', 'axe@example.com', 'admin123', 2, 15, 'active', '2025-03-14 03:33:46', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(133, 'ate', 'ate@example.com', 'admin123', 3, 13, 'active', '2025-03-14 03:34:40', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(134, 'add', 'add@example.com', 'admin123', 3, 16, 'active', '2025-03-14 03:37:58', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(135, 'atat', 'atat@gmail.com', 'admin123', 3, NULL, 'active', '2025-03-14 03:38:22', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-14 03:38:22'),
(136, 'age', 'age@example.com', 'admin123', 3, 13, 'active', '2025-03-14 03:38:45', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(137, 'aft', 'aft@example.com', 'admin123', 3, 17, 'active', '2025-03-14 03:41:33', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(138, 'few', 'few@example.com', 'admin123', 3, 16, 'active', '2025-03-14 03:42:34', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(139, 'get', 'get@example.com', 'admin123', 2, 12, 'active', '2025-03-14 03:42:55', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(140, 'sat', 'sat@example.com', 'admin123', 3, 16, 'active', '2025-03-14 03:47:11', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(141, 'saw', 'saw@example.com', 'admin123', 3, 17, 'active', '2025-03-14 03:49:00', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(142, 'sasa', 'sasa@example.com', 'admin123', 3, 13, 'active', '2025-03-14 03:52:20', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(143, 'daw', 'daw@example.com', 'admin123', 3, 17, 'active', '2025-03-14 03:58:48', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(144, 'vat', 'vat@example.com', 'admin123', 2, NULL, 'active', '2025-03-14 04:04:24', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-14 04:04:24'),
(145, 'bet', 'bet@example.com', 'admin123', 2, NULL, 'active', '2025-03-14 04:05:20', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-14 04:05:20'),
(146, 'brew', 'brew@example.com', 'admin123', 3, 16, 'active', '2025-03-14 04:05:41', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(148, 'bed', 'bed@example.com', 'admin123', 2, 5, 'active', '2025-03-14 04:07:41', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(150, 'nrt', 'nrt@gmail.com', 'admin123', 2, 16, 'active', '2025-03-14 04:08:35', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(151, 'nesty', 'nesty@example.com', 'admin123', 3, 36, 'active', '2025-03-14 04:09:20', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(152, 'mett', 'mett@example.com', 'admin123', 3, 17, 'active', '2025-03-14 04:11:10', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(153, 'noth', 'noth@example.com', 'admin123', 3, NULL, 'active', '2025-03-14 04:14:20', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-14 04:14:20'),
(154, 'nothe', 'nothe@example.com', 'admin123', 3, 26, 'active', '2025-03-14 04:14:38', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(155, 'dddddew', 'dddddew@example.com', 'admin123', 3, 13, 'active', '2025-03-14 04:15:01', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(156, 'dddddewwe', 'dddddewwe@example.com', 'admin123', 3, 13, 'active', '2025-03-14 04:15:23', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(157, 'wdwd', 'wdwd@example.com', 'admin123', 3, 15, 'active', '2025-03-14 04:15:37', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(158, 'ce', 'ce@example.com', 'admin123', 3, 17, 'active', '2025-03-14 04:15:53', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(159, 'dw', 'dw@example.com', 'admin123', 3, 15, 'active', '2025-03-14 04:16:25', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(160, 'ytyty', 'utytu@example.com', 'admin123', 3, 9, 'active', '2025-03-14 04:17:09', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(161, 'thyth', 'hrtht@example.com', 'admin123', 3, 9, 'active', '2025-03-14 04:19:23', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(162, 'wewewe', 'wewewewe@example.com', 'admin123', 3, NULL, 'active', '2025-03-14 04:20:52', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-14 04:20:52'),
(163, 'Fe grgrgr', 'grgrgr@example.com', 'admin123', 3, 13, 'active', '2025-03-14 04:21:36', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(165, 'dwdwd', 'wdwasxas@example.com', 'admin123', 2, 17, 'active', '2025-03-14 04:29:43', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(166, 'asxsx', 'xasxx@gmail.com', 'admin123', 3, 15, 'active', '2025-03-14 04:30:02', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(167, 'fwfwfw', 'bbdfx@example.com', 'admin123', 3, 9, 'active', '2025-03-14 04:32:10', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(168, 'vfvdv', 'fv@example.com', 'admin123', 3, 9, 'active', '2025-03-14 04:32:54', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(169, 'dwdwax', 'xxz@example.com', 'admin123', 3, 9, 'active', '2025-03-14 04:35:31', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(170, 'mi7kui', 'uikmiuk@example.com', 'admin123', 3, 9, 'active', '2025-03-14 04:36:23', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(171, 'hyh', 'hyhy@example.com', 'admin123', 3, 9, 'active', '2025-03-14 05:01:01', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(172, 'yhyhyh', 'hyhthtb@example.com', 'admin123', 3, 9, 'inactive', '2025-03-14 05:01:15', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(175, 'Dragon Monkey', 'dmonkey@gmail.com', 'palaigit', 3, 11, 'inactive', '2025-03-14 10:30:39', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(176, 'Timothy Mule Malasarte Capote', 'temu@example.com', 'admin123', 1, 12, 'active', '2025-03-14 11:44:11', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(177, 'leader', 'leader@example.com', 'admin123', 2, 4, 'active', '2025-03-15 08:26:11', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(178, 'fe malasarte', 'fe@gmiail.com', 'admin123', 2, 5, 'active', '2025-03-15 09:51:52', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:33:01'),
(179, 'uiberilvrv', 'foihwerfire@example.com', 'admin123', 3, 16, 'active', '2025-03-15 10:04:06', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:38:28'),
(180, 'wjdbwdwd', 'ddfalimomina@example.com', 'admin123', 3, NULL, 'active', '2025-03-15 10:38:47', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:38:47'),
(181, 'jm', 'jm@example.com', 'admin123', 3, NULL, 'active', '2025-03-15 10:47:42', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 10:47:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_achievements`
--

DROP TABLE IF EXISTS `user_achievements`;
CREATE TABLE `user_achievements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `organization` varchar(255) NOT NULL,
  `date_received` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_social_links`
--

DROP TABLE IF EXISTS `user_social_links`;
CREATE TABLE `user_social_links` (
  `social_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `platform` enum('website','github','twitter','instagram','facebook') NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin_resources`
--
ALTER TABLE `admin_resources`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `announcement_targets`
--
ALTER TABLE `announcement_targets`
  ADD PRIMARY KEY (`target_id`),
  ADD KEY `announcement_id` (`announcement_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `event_status`
--
ALTER TABLE `event_status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `fk_event_status_event` (`event_id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`membership_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`org_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `org_categories`
--
ALTER TABLE `org_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `org_resources`
--
ALTER TABLE `org_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`sub_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_organization` (`org_id`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- Indexes for table `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_social_links`
--
ALTER TABLE `user_social_links`
  ADD PRIMARY KEY (`social_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcement_targets`
--
ALTER TABLE `announcement_targets`
  MODIFY `target_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `event_status`
--
ALTER TABLE `event_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `membership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `org_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `org_categories`
--
ALTER TABLE `org_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `org_resources`
--
ALTER TABLE `org_resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `user_achievements`
--
ALTER TABLE `user_achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_social_links`
--
ALTER TABLE `user_social_links`
  MODIFY `social_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `admin_resources`
--
ALTER TABLE `admin_resources`
  ADD CONSTRAINT `admin_resources_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `announcement_targets`
--
ALTER TABLE `announcement_targets`
  ADD CONSTRAINT `announcement_targets_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`announcement_id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE CASCADE;

--
-- Constraints for table `event_status`
--
ALTER TABLE `event_status`
  ADD CONSTRAINT `event_status_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_event_status_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `membership`
--
ALTER TABLE `membership`
  ADD CONSTRAINT `membership_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `membership_ibfk_2` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `org_categories` (`category_id`);

--
-- Constraints for table `org_resources`
--
ALTER TABLE `org_resources`
  ADD CONSTRAINT `org_resources_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_organization` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL;

--
-- Constraints for table `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD CONSTRAINT `user_achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_social_links`
--
ALTER TABLE `user_social_links`
  ADD CONSTRAINT `user_social_links_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;
SET FOREIGN_KEY_CHECKS = 1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
