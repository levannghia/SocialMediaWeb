-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 01, 2024 at 07:15 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_social_media`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `color`, `parent_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'sport', 'sport', NULL, NULL, NULL, '2024-02-23 13:06:06', '2024-02-28 13:06:06', NULL),
(2, 'Food', 'food', NULL, NULL, NULL, '2024-02-23 13:06:06', '2024-02-28 13:06:06', NULL),
(3, 'Art', 'art', NULL, NULL, NULL, '2024-02-20 13:06:06', '2024-02-28 13:06:06', NULL),
(4, 'Music', 'music', NULL, NULL, NULL, '2024-02-23 13:06:06', '2024-02-28 13:06:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `post_id` bigint UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `parent_id`, `post_id`, `comment`, `user_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 12, 'Test comment 1', 1, '2024-02-25 14:46:44', '2024-02-25 14:46:44'),
(2, NULL, 12, 'hahaha', 1, '2024-02-26 12:40:59', '2024-02-26 12:40:59'),
(3, NULL, 12, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, debitis similique repudiandae at fuga ipsam dolorem, voluptatibus facere enim assumenda ad voluptate reiciendis. Iste ipsa cum ab, dolore cumque officia.', 1, '2024-02-26 13:10:52', '2024-02-26 13:10:52'),
(4, NULL, 12, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, debitis similique repudiandae at fuga ipsam dolorem, voluptatibus facere enim assumenda ad voluptate reiciendis. Iste ipsa cum ab, dolore cumque officia.\nLorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, debitis similique repudiandae at fuga ipsam dolorem, voluptatibus facere enim assumenda ad voluptate reiciendis. Iste ipsa cum ab, dolore cumque officia.\nLorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, debitis similique repudiandae at fuga ipsam dolorem, voluptatibus facere enim assumenda ad voluptate reiciendis. Iste ipsa cum ab, dolore cumque officia.', 1, '2024-02-26 13:12:48', '2024-02-26 13:12:48'),
(6, NULL, 1, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, debitis similique repudiandae at fuga ipsam dolorem,<br />\n<br />\nLorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, debitis similique repudiandae at fuga ipsam dolorem,<br />\n<br />\nLorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, debitis similique repudiandae at fuga ipsam dolorem,', 1, '2024-02-26 13:52:15', '2024-02-28 13:10:02'),
(10, NULL, 11, 'saaaaaaaaa', 1, '2024-02-26 14:00:26', '2024-02-26 14:00:26'),
(11, NULL, 11, 'sas<br />\nsaaaaaa', 1, '2024-02-26 14:00:33', '2024-03-01 07:17:10'),
(12, NULL, 10, 'Hello cả nhà', 1, '2024-02-29 13:29:09', '2024-02-29 13:29:09'),
(14, NULL, 10, 'Test nhe', 1, '2024-02-29 14:47:12', '2024-02-29 14:47:12'),
(15, NULL, 10, 'Lai', 1, '2024-02-29 14:47:49', '2024-02-29 14:47:49'),
(16, NULL, 10, 'Lai', 1, '2024-02-29 14:49:10', '2024-02-29 14:49:10'),
(17, NULL, 10, 'ok em tra', 1, '2024-02-29 14:49:20', '2024-02-29 14:49:20'),
(20, 12, 10, 'hay quas', 1, '2024-03-01 06:09:37', '2024-03-01 06:09:37'),
(23, 6, 1, 'Hello anh sâs', 1, '2024-03-01 06:47:36', '2024-03-01 12:42:21'),
(24, NULL, 9, 'test 2', 1, '2024-03-01 07:02:20', '2024-03-01 07:03:29'),
(25, 24, 9, 'OK haha 22', 1, '2024-03-01 07:02:30', '2024-03-01 07:03:40'),
(27, 10, 11, 'Hay hay', 1, '2024-03-01 07:30:33', '2024-03-01 07:30:33'),
(28, 6, 1, 'dsdđ', 1, '2024-03-01 08:13:40', '2024-03-01 08:13:40'),
(29, 6, 1, 'saaaa ssaaaa', 1, '2024-03-01 08:44:25', '2024-03-01 08:44:30'),
(31, 27, 11, 'hay', 1, '2024-03-01 13:06:38', '2024-03-01 13:06:38'),
(32, 23, 1, 'test', 1, '2024-03-03 05:50:26', '2024-03-03 05:50:26'),
(33, 27, 11, 'Lại', 1, '2024-03-03 06:25:24', '2024-03-03 06:25:24'),
(34, 29, 1, 'lại', 1, '2024-03-03 06:26:13', '2024-03-03 06:26:13'),
(35, 34, 1, 'hay', 1, '2024-03-03 06:26:43', '2024-03-03 06:26:43'),
(36, 32, 1, 'lại', 1, '2024-03-03 06:32:06', '2024-03-03 06:32:06'),
(37, 36, 1, 'sâs', 1, '2024-03-03 06:32:13', '2024-03-03 06:32:13'),
(38, 36, 1, 'saaaa', 1, '2024-03-03 06:32:19', '2024-03-03 06:32:19'),
(39, 34, 1, 'test', 1, '2024-03-03 06:33:57', '2024-03-03 06:33:57'),
(40, 39, 1, 'Ok', 1, '2024-03-03 06:34:06', '2024-03-03 06:34:06'),
(41, NULL, 11, 'hay', 1, '2024-03-03 06:37:04', '2024-03-03 06:37:04'),
(42, 41, 11, 'phai vạy ko', 1, '2024-03-03 06:37:15', '2024-03-03 06:37:15'),
(43, 6, 1, 'saaaaaa', 1, '2024-03-03 06:38:45', '2024-03-03 06:38:45'),
(44, NULL, 1, 'saaaaaa', 1, '2024-03-03 06:40:29', '2024-03-03 06:40:29'),
(45, 44, 1, 'saaaaaaaaaaa', 1, '2024-03-03 06:40:34', '2024-03-03 06:40:34'),
(46, 44, 1, 'saaaaaaaaa', 1, '2024-03-03 06:40:37', '2024-03-03 06:40:37'),
(47, 46, 1, 'saaaaaaaaa', 1, '2024-03-03 06:40:41', '2024-03-03 06:40:41'),
(48, 11, 11, 'Good comment', 1, '2024-03-03 13:37:28', '2024-03-03 13:37:28'),
(49, 48, 11, 'Oi ban oi', 1, '2024-03-03 13:37:41', '2024-03-03 13:37:41'),
(50, 49, 11, 'v s', 1, '2024-03-03 13:38:04', '2024-03-03 13:38:04'),
(51, NULL, 4, 'ok luôn', 1, '2024-03-19 13:18:47', '2024-03-19 13:18:47'),
(52, NULL, 17, 'Hay<br />\nnè', 1, '2024-03-28 12:09:07', '2024-03-28 12:09:07'),
(53, 52, 17, 'Good luôn', 1, '2024-03-28 12:09:26', '2024-03-28 12:09:26');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint NOT NULL,
  `title` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `location_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` json DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_at` timestamp NOT NULL,
  `end_at` timestamp NOT NULL,
  `date` date NOT NULL,
  `price` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `slug`, `user_id`, `category_id`, `description`, `location_title`, `location_address`, `position`, `file_type`, `path`, `start_at`, `end_at`, `date`, `price`, `created_at`, `updated_at`, `deleted_at`) VALUES
(10, 'hay nha 1', 'hay-nha-1', 1, 1, 'aaaaaa', 'Trung tam 1', '23697 Big Basin Way, Los Gatos, California 95033, Hoa Kỳ', '{\"lat\": \"37.2321679586632\", \"long\": \"-122.14833400177915\"}', 'file', 'events/2024/4/dvYvMcg9oYFahDNcPlxSfwTdCtfJSo0toQaVwQN9.jpg', '2025-01-30 05:57:13', '2024-04-26 01:40:00', '2024-05-25', 1234000, '2024-04-25 13:53:26', '2024-04-25 13:53:26', NULL),
(11, 'demo 2', 'demo-2', 1, 2, 'aaaaaaaa', 'Toa nha B', '24362 Thomas Avenue, Hayward, California 94544, Hoa Kỳ', '{\"lat\": \"37.66192701265679\", \"long\": \"-122.081411424489\"}', 'url', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSVb64W2dD9ReTsrPJ2WFPJodEAIwAWrYwEK7Nd0TV88g&s', '2024-04-28 17:00:00', '2024-04-26 15:41:00', '2024-04-30', 200000, '2024-04-25 13:56:22', '2024-04-25 13:56:22', NULL),
(12, 'Su kien sota', 'su-kien-sota', 1, 3, 'asssssssss', 'HB Building', 'Phường 10, 71400, Gò Vấp, Thành phố Hồ Chí Minh, Việt Nam', '{\"lat\": \"10.83842666192831\", \"long\": \"106.67294155043658\"}', 'url', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRhEAzozcWVmZDS11OZVJIlBd8AjcS5BAAg4CdE54cjjw&s', '2024-04-30 00:30:00', '2024-04-26 03:00:00', '2024-05-25', 20000, '2024-04-26 12:23:21', '2024-04-26 12:23:21', NULL),
(13, 'International Band Music Concert', 'international-band-music-concert', 1, 4, 'Enjoy your favorite dishe and a lovely your friends and family and have a great time. Food from local food trucks will be available for purchase.Enjoy your favorite dishe and a lovely your friends and family and have a great time. Food from local food trucks will be available for purchase.Enjoy your favorite dishe and a lovely your friends and family and have a great time. Food from local food trucks will be available for purchase.Enjoy your favorite dishe and a lovely your friends and family and have a great time. Food from local food trucks will be available for purchase.', 'Gala Convention Center', 'Age UK, 36-44, High Street, Walthamstow, London, Greater London, England, E17 7LD, United Kingdom', '{\"lat\": \"51.58185125\", \"long\": \"-0.03167237\"}', 'url', 'https://i0.wp.com/crmviet.vn/wp-content/uploads/2019/05/event-marketing.jpg?w=641&ssl=1', '2024-04-30 13:04:22', '2024-04-30 13:04:22', '2024-06-28', 399000, '2024-04-28 06:23:40', '2024-04-28 06:23:40', NULL),
(14, 'Pho di bo 30/04 - 01/05', 'pho-di-bo-3004-0105', 1, 2, 'giai phong hoan toan mien nam thong nhat dat nuoc', 'Cong vien 16/4', 'Quảng trường Thành phố Phan Rang-Tháp Chàm, Việt Nam', '{\"lat\": \"11.5652939\", \"long\": \"108.99956343\"}', 'url', 'https://imagev3.dantocmiennui.vn/w1000/Uploaded/2024/znango/2024_04_28/vna-potal-ninh-thuan-khai-truong-tuyen-pho-di-bo-dau-tien-tai-thanh-pho-phan-rang-thap-cham-7348105-663.jpg', '2024-04-29 11:00:00', '2024-04-29 15:30:00', '2024-04-30', 0, '2024-04-29 14:40:37', '2024-04-29 14:40:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_users`
--

CREATE TABLE `event_users` (
  `id` bigint NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_users`
--

INSERT INTO `event_users` (`id`, `status`, `event_id`, `user_id`, `created_by`, `created_at`, `updated_at`) VALUES
(16, 'pending', 10, 3, 1, '2024-04-25 13:53:26', '2024-04-25 13:53:26'),
(17, 'pending', 10, 4, 1, '2024-04-25 13:53:26', '2024-04-25 13:53:26'),
(18, 'approved', 11, 27, 1, '2024-04-25 13:56:22', '2024-04-25 13:56:22'),
(19, 'approved', 11, 21, 1, '2024-04-25 13:56:22', '2024-04-25 13:56:22'),
(20, 'pending', 12, 21, 1, '2024-04-26 12:23:21', '2024-04-26 12:23:21'),
(21, 'approved', 13, 4, 1, '2024-04-26 12:23:21', '2024-04-26 12:23:21'),
(22, 'approved', 13, 2, 1, '2024-04-28 06:23:40', '2024-04-28 06:23:40'),
(23, 'approved', 13, 3, 1, '2024-04-28 06:23:40', '2024-04-28 06:23:40'),
(24, 'approved', 13, 27, 1, '2024-04-28 06:23:40', '2024-04-28 06:23:40');

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
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `follower_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `user_id`, `follower_id`, `created_at`) VALUES
(1, 1, 3, '2024-03-20 13:14:57');

-- --------------------------------------------------------

--
-- Table structure for table `follower_events`
--

CREATE TABLE `follower_events` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `event_id` bigint NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `follower_events`
--

INSERT INTO `follower_events` (`id`, `user_id`, `event_id`, `created_at`) VALUES
(1, 2, 11, '2024-04-28 15:04:33'),
(45, 1, 11, '2024-04-30 13:09:13'),
(49, 1, 12, '2024-05-01 06:10:17'),
(51, 1, 10, '2024-05-01 06:10:55');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_path` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_path` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` text COLLATE utf8mb4_unicode_ci,
  `auto_approval` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` bigint UNSIGNED NOT NULL,
  `pinned_post_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `slug`, `cover_path`, `thumbnail_path`, `about`, `auto_approval`, `user_id`, `pinned_post_id`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'Vue js Developer', 'vue-js-developers', 'group-2/TQTol2kOXxZV7rUD4M2EfU98xWLnbPB8d85KgX2a.png', 'group-2/J5qXBZ2j3StnRhTiHBNWXk4br5ZAH89PJcxleBBa.png', '<p>saaaaaaaaaaaaaaaaaaaa dsssssss</p>', 1, 1, 19, NULL, NULL, '2024-03-05 12:58:35', '2024-03-28 15:06:27'),
(3, 'Laravel intern', 'laravel-intern', NULL, 'group-3/RDnOg8bcoHU1g4sFSt4BkVqAoV7OQ9SCwx6Dod3s.png', NULL, 1, 1, NULL, NULL, NULL, '2024-03-05 13:52:45', '2024-03-12 13:26:19'),
(4, 'React Native Viet Nam', 'react-native-viet-nam', NULL, 'group-4/KWYUrsWjVh7zMcYes7wy2N5VtKmGbiCx727qxhb5.jpg', '<p>Đây là cộng đồng của người Việt Nam</p>', 0, 3, NULL, NULL, NULL, '2024-03-20 12:30:47', '2024-03-20 12:31:44');

-- --------------------------------------------------------

--
-- Table structure for table `group_users`
--

CREATE TABLE `group_users` (
  `id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_expire_date` timestamp NULL DEFAULT NULL,
  `token_used` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_users`
--

INSERT INTO `group_users` (`id`, `status`, `role`, `token`, `token_expire_date`, `token_used`, `user_id`, `group_id`, `created_by`, `created_at`) VALUES
(1, 'approved', 'admin', NULL, NULL, NULL, 1, 2, 1, '2024-03-05 12:58:35'),
(2, 'approved', 'admin', NULL, NULL, NULL, 1, 3, 1, '2024-03-05 13:52:45'),
(7, 'approved', 'user', 'F1keVpe9BW07uINEa9Y2VfFX2fuJXhqJorTtkGZEvVtQCDU3qiQxgB6lrlMYNxYAm2tfYAfYPK7RDVr26RmI8ek21f6O38X6cxunbtUgylVchnvbH6gnzWGpkcR5jBnAerRe79qN6uPRFRErUDqAHNoiysrNlLI9xQCWIggyI9CoPmlh7DEYd635yjWarTwERRue5rf1MqSqLUoroq8aQEvQE6uft4k80jtLcbn8KEGwE4psdzrdjvo8NRkdN0fz', '2024-03-14 13:49:14', '2024-03-13 13:54:39', 2, 2, 1, '2024-03-13 13:49:14'),
(10, 'rejected', 'user', NULL, NULL, NULL, 4, 2, 4, '2024-03-14 13:00:17'),
(11, 'approved', 'admin', NULL, NULL, NULL, 3, 4, 3, '2024-03-20 12:30:47');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_02_13_134200_create_groups_table', 1),
(6, '2024_02_13_134201_create_group_users_table', 1),
(7, '2024_02_13_134227_create_posts_table', 1),
(8, '2024_02_13_134246_create_post_attachments_table', 1),
(9, '2024_02_13_134308_create_post_reactions_table', 1),
(10, '2024_02_13_134351_create_comments_table', 1),
(11, '2024_02_13_134444_create_followers_table', 1);

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(22, 'App\\Models\\User', 21, 'user_token', 'facb8e70e00ccace685810f882aa7298071e2c75eacbbed8e2c62ebf47b33fa4', '[\"*\"]', NULL, NULL, '2024-04-10 13:17:36', '2024-04-10 13:17:36'),
(28, 'App\\Models\\User', 27, 'user_token_google', '6bab78ad102438ba3b6c003ed05f6804491c15a6a1cb0fcecc744c3f0fa24329', '[\"*\"]', NULL, NULL, '2024-04-11 14:03:05', '2024-04-11 14:03:05'),
(29, 'App\\Models\\User', 21, 'user_token', 'a55f75c614b419fb67df49c9e6535de468e7f10c222c8dc0444d126f3f79660b', '[\"*\"]', NULL, NULL, '2024-04-11 14:14:17', '2024-04-11 14:14:17'),
(30, 'App\\Models\\User', 21, 'user_token', '31c20ecd183c29c627c703e6cb49d8529aaa0ce468b31dae38bc21e8ccb182b7', '[\"*\"]', NULL, NULL, '2024-04-11 14:18:24', '2024-04-11 14:18:24'),
(31, 'App\\Models\\User', 21, 'user_token', '0df3aca551ac3ed36a883ca8ceff2759f1f4ea7224ce84def76fae94448626c8', '[\"*\"]', NULL, NULL, '2024-04-11 14:21:20', '2024-04-11 14:21:20'),
(32, 'App\\Models\\User', 21, 'user_token', '51b9e0b9c6f0e0c8d3316b66b3cda2f285817dc93078b33ce20b595e59dcd8b0', '[\"*\"]', NULL, NULL, '2024-04-11 14:22:51', '2024-04-11 14:22:51'),
(33, 'App\\Models\\User', 21, 'user_token', 'bf6fda12a288494a8618e4e3f88242bd67c96bb9608a1f6cc1d4a101373545f8', '[\"*\"]', NULL, NULL, '2024-04-11 14:23:22', '2024-04-11 14:23:22'),
(34, 'App\\Models\\User', 21, 'user_token', '2882e439faaeac0de42f67c16f942c50c3afbef72a7ecf9fbc79d21e1ef54521', '[\"*\"]', NULL, NULL, '2024-04-11 14:23:26', '2024-04-11 14:23:26'),
(35, 'App\\Models\\User', 21, 'user_token', 'c486fc9ea4d276c1c1d6bbbb1d3fdfab704b205a7ea585b78fbaf9a07c6d6c44', '[\"*\"]', NULL, NULL, '2024-04-11 14:24:01', '2024-04-11 14:24:01'),
(36, 'App\\Models\\User', 21, 'user_token', 'eb78a38ed426ff426f8a5a063cc65c37a562940b4599aa0984a0bdc37046f0af', '[\"*\"]', NULL, NULL, '2024-04-11 14:24:45', '2024-04-11 14:24:45'),
(37, 'App\\Models\\User', 21, 'user_token', '3c840fbcb548eda07714f2544330ae0ef5344a0c9c528e8946ad286fdeb62718', '[\"*\"]', NULL, NULL, '2024-04-11 14:34:55', '2024-04-11 14:34:55'),
(38, 'App\\Models\\User', 21, 'user_token', 'c1453a4746eb97c0d66781f36b37431e73795204b15e2d1f1fe19f70c0d88404', '[\"*\"]', NULL, NULL, '2024-04-11 14:36:37', '2024-04-11 14:36:37'),
(39, 'App\\Models\\User', 21, 'user_token', '4863e1902b438ce7c6498ee3ed036a87b43d0205e4e2af5b10869a1f2c4bf55f', '[\"*\"]', NULL, NULL, '2024-04-11 14:37:51', '2024-04-11 14:37:51'),
(40, 'App\\Models\\User', 1, 'user_token_google', 'e10d9d2a15d975e557e89d8705d9032a61b78c36f13f9c373fccbe63a3a08d2d', '[\"*\"]', NULL, NULL, '2024-04-12 14:55:19', '2024-04-12 14:55:19'),
(41, 'App\\Models\\User', 1, 'user_token_google', 'e80cea83b0a7350c71bf5662a6787dddbd9ae7852e2c2bae69a6a1d7733722cb', '[\"*\"]', NULL, NULL, '2024-04-12 14:58:29', '2024-04-12 14:58:29'),
(42, 'App\\Models\\User', 1, 'user_token_google', 'fb07eefb648ea2d3f3f01d543ca475c48779ee6b89bb4eae11f6267a05cbb95b', '[\"*\"]', NULL, NULL, '2024-04-12 15:01:52', '2024-04-12 15:01:52'),
(43, 'App\\Models\\User', 1, 'user_token_google', '410776c79bf459e1adecebc4c1369573a01add4ff5f097a2b58ec550f4f97533', '[\"*\"]', '2024-04-30 14:31:38', NULL, '2024-04-20 14:01:01', '2024-04-30 14:31:38'),
(44, 'App\\Models\\User', 1, 'user_token_google', '1d954da083208c9ad62e9af1df899b49037dc25ffa59a4b6572e59bf0775902a', '[\"*\"]', '2024-04-30 14:32:28', NULL, '2024-04-30 14:32:15', '2024-04-30 14:32:28'),
(45, 'App\\Models\\User', 1, 'user_token_google', 'bd8e7e8c048953f7252633f935a6cf8d8d6c3a7491c4822738a9f68972ede8fb', '[\"*\"]', '2024-05-01 06:13:09', NULL, '2024-04-30 14:33:29', '2024-05-01 06:13:09'),
(46, 'App\\Models\\User', 1, 'user_token_google', 'f34fe9501dfabd2ce7c5f4a23e8be38f4be23fb32078489f7c5a22cb78bd7447', '[\"*\"]', '2024-05-01 06:37:47', NULL, '2024-05-01 06:19:48', '2024-05-01 06:37:47'),
(47, 'App\\Models\\User', 1, 'user_token_google', '42d84e0a57a40aa33654421352cc9ea90a9bca901a3300900b4b98b395d22cc6', '[\"*\"]', '2024-05-01 06:43:59', NULL, '2024-05-01 06:38:07', '2024-05-01 06:43:59'),
(48, 'App\\Models\\User', 1, 'user_token_google', 'a757ee61370a9b61e7f7325bee8eec41379d8edfe408fd0c589b6cf2d8065f7e', '[\"*\"]', '2024-05-01 06:44:28', NULL, '2024-05-01 06:44:14', '2024-05-01 06:44:28'),
(49, 'App\\Models\\User', 1, 'user_token_google', '511cf9c50968ddfa7d26ddf03cff1df1cabd84c9ad4ec3d173fe672e85abefab', '[\"*\"]', '2024-05-01 06:44:37', NULL, '2024-05-01 06:44:36', '2024-05-01 06:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint UNSIGNED NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED DEFAULT NULL,
  `preview` json DEFAULT NULL,
  `preview_url` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `user_id`, `group_id`, `preview`, `preview_url`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '<h2>Ok ipsum dolor sit amet,</h2><blockquote><p>&nbsp;consectetur adipisicing elit. Autem, placeat eveniet esse nemo voluptates voluptate, eligendi vitae nihil id dolor eaque accusantium saaaaaaaaaaaaaaaaa assssssssssssssssss saaaaaaaaaaaaaaaaaaaaaaaaa asssssssssssssssssssssssssssssssssssssssss</p></blockquote>', 1, 2, NULL, NULL, NULL, NULL, '2024-02-23 13:06:06', '2024-02-20 14:34:21'),
(2, '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem, placeat eveniet esse nemo voluptates voluptate, eligendi vitae nihil id dolor eaque</p><ol><li>&nbsp;hay</li><li>qua</li><li>di</li></ol><p>accusantium voluptatibus non! Facere, ab. Iure fuga at est. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem, placeat eveniet esse nemo voluptates voluptate, eligendi vitae nihil id dolor eaque accusantium voluptatibus non! Facere, ab. Iure fuga at est phair vaayj khoong.</p>', 1, 2, NULL, NULL, NULL, NULL, '2024-02-20 13:06:06', '2024-02-20 12:58:12'),
(3, '<p>Đay là bai post đầu tiên của tôi. Tôi muốn test bà pót này <strong>thật nhiều</strong></p><h2>Vậy là xong hết 1 năm</h2>', 1, NULL, NULL, NULL, NULL, NULL, '2024-02-20 13:36:19', '2024-02-20 13:53:03'),
(4, '<h2>Test lại nha</h2>', 1, NULL, NULL, NULL, NULL, NULL, '2024-02-20 13:52:38', '2024-02-20 13:52:38'),
(6, '', 1, 2, NULL, NULL, 1, '2024-03-20 12:53:18', '2024-02-20 14:18:39', '2024-03-20 12:53:18'),
(8, '<p>Hay nha cac ban</p>', 1, NULL, NULL, NULL, NULL, NULL, '2024-02-21 13:29:05', '2024-02-21 13:29:05'),
(9, '', 1, 2, NULL, NULL, NULL, NULL, '2024-02-21 13:47:38', '2024-02-21 13:47:38'),
(10, '', 1, NULL, NULL, NULL, NULL, NULL, '2024-02-21 13:53:13', '2024-02-21 13:53:13'),
(11, '<h2>Laij</h2>', 1, 2, NULL, NULL, NULL, NULL, '2024-02-23 12:49:13', '2024-02-23 12:49:13'),
(12, '', 1, 2, NULL, NULL, NULL, '2024-02-27 13:21:49', '2024-02-24 13:41:17', '2024-02-27 13:21:49'),
(13, '<p>hello anh em</p>', 1, NULL, NULL, NULL, NULL, NULL, '2024-03-03 13:56:51', '2024-03-03 13:56:51'),
(14, '<h2><strong>dsdddddddd</strong></h2>', 1, NULL, NULL, NULL, NULL, NULL, '2024-03-03 13:57:36', '2024-03-20 12:53:46'),
(15, '<ol><li><h2>dfsddddddd</h2></li><li>assssssssssssss</li><li><strong>saaaaaaaaaaaaaa</strong></li><li>saaaaaaaaaaaaaaaaaaaaaaaa</li></ol><p>#ThinhHanh #NghiaLe</p>', 1, NULL, NULL, NULL, NULL, NULL, '2024-03-03 13:59:03', '2024-03-22 12:19:30'),
(16, '<p><strong>Test my group</strong></p>', 1, 2, NULL, NULL, NULL, NULL, '2024-03-15 14:26:39', '2024-03-15 14:26:39'),
(17, '<p>Tại sao lại không hiện ra</p>', 1, 2, NULL, NULL, NULL, NULL, '2024-03-15 14:27:30', '2024-03-15 14:27:30'),
(18, '<p>Lại nha</p>', 1, 2, NULL, NULL, NULL, NULL, '2024-03-15 14:28:06', '2024-03-15 14:28:06'),
(19, '<p>Test <strong>Uyen Mang Tan</strong></p>', 1, 2, NULL, NULL, NULL, NULL, '2024-03-15 14:37:46', '2024-03-15 14:37:46'),
(20, '<p>Lại nha</p>', 1, 2, NULL, NULL, NULL, NULL, '2024-03-15 14:39:47', '2024-03-15 14:39:47'),
(21, '<p>saaaaaa</p>', 1, 2, NULL, NULL, NULL, NULL, '2024-03-15 14:49:30', '2024-03-15 14:49:30'),
(22, '<p>Test nhẹ</p>', 1, 2, NULL, NULL, NULL, NULL, '2024-03-15 14:51:17', '2024-03-15 14:51:17'),
(23, '<p>Đây là bài post đầu tiêng của react native Việt Nam</p>', 3, NULL, NULL, NULL, NULL, NULL, '2024-03-20 12:32:13', '2024-03-20 12:39:18'),
(24, '<p>Test lại</p>', 3, 4, NULL, NULL, NULL, NULL, '2024-03-20 12:36:38', '2024-03-20 12:37:08'),
(25, '<p>OK</p>', 3, 4, NULL, NULL, NULL, NULL, '2024-03-20 12:49:57', '2024-03-20 12:49:57'),
(26, '<p><strong>Su sa Hột Lựu Đây</strong></p>', 3, NULL, NULL, NULL, NULL, NULL, '2024-03-20 13:21:06', '2024-03-20 13:21:06'),
(27, '<figure class=\"media\"><oembed url=\"https://www.youtube.com/watch?v=XTGBLHwvE20\"></oembed></figure>', 1, NULL, NULL, NULL, 1, '2024-03-28 15:02:24', '2024-03-22 12:56:45', '2024-03-28 15:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `post_attachments`
--

CREATE TABLE `post_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_attachments`
--

INSERT INTO `post_attachments` (`id`, `post_id`, `name`, `path`, `mime`, `size`, `created_by`, `created_at`) VALUES
(1, 8, 'Screenshot 2024-02-08 144245.png', 'attachments/8/ZV8BOpcn5tXcnWFEEAR05GeXb7ZgJ4JrNQiQIkys.png', 'image/png', 84816, 1, '2024-02-21 13:29:05'),
(2, 8, 'z5130866833433_8cf84c5d0ee880d63456ededa3682643.jpg', 'attachments/8/Q0hYuBhRezYw6EjtI7RTQolnvZxWeQQWqYIk2jNX.jpg', 'image/jpeg', 7641, 1, '2024-02-21 13:29:05'),
(3, 8, 'z5130867401492_20c1da5c83ace3ffcf892558f9f21017.jpg', 'attachments/8/Qf65gpUXvdki4eSNTJOqYksWaK0mzSiuMBvsyCpx.jpg', 'image/jpeg', 14999, 1, '2024-02-21 13:29:05'),
(4, 9, 'f101c84e1a1f26fed04ce6c8afe02511.png', 'attachments/9/TBx5eHNCX5wXk1krEdhNIQGhSyIyS7dSXlIRXPr4.png', 'image/png', 1375096, 1, '2024-02-21 13:47:38'),
(5, 9, 'f3421eb94b964f0454755ef1b1c380be.jpg', 'attachments/9/LHhPkJi41LwDJRmMhY4mdRKw7k37zYQuxcUNjF32.jpg', 'image/jpeg', 92786, 1, '2024-02-21 13:47:38'),
(6, 9, 'Screenshot 2024-02-08 144245.png', 'attachments/9/qww5r2ClZATwrkZekdFwVsfngDJ75opFtNUemjAh.png', 'image/png', 84816, 1, '2024-02-21 13:47:38'),
(7, 9, 'z5130866833433_8cf84c5d0ee880d63456ededa3682643.jpg', 'attachments/9/pKq68IwWgVpOUgmgMlzUBRBjQhlpmjUm6wy9kLUV.jpg', 'image/jpeg', 7641, 1, '2024-02-21 13:47:38'),
(8, 9, 'z5130867401492_20c1da5c83ace3ffcf892558f9f21017.jpg', 'attachments/9/Xy6LaUXP9VdKk6pzn20PSoa3VeWATTx7mAtFG8PG.jpg', 'image/jpeg', 14999, 1, '2024-02-21 13:47:38'),
(9, 10, 'all-in-one-wp-migration.6.77.zip', 'attachments/10/WTVtLPb4CLj1Rdz56QcYwNXrzKVNmc2SyKv7caEq.zip', 'application/zip', 339702, 1, '2024-02-21 13:53:13'),
(10, 10, 'colorlib-error-404-2.zip', 'attachments/10/GLUXDAMxFz947ZC87YoSKmjl0tXVrY8Cv5Jc9xcp.zip', 'application/zip', 4490, 1, '2024-02-21 13:53:13'),
(11, 10, 'Screenshot 2024-02-08 144245.png', 'attachments/10/gODu9LYEYpDtHyhzbiY0xOsoigdhjYHi3RnKkinN.png', 'image/png', 84816, 1, '2024-02-21 13:53:13'),
(12, 10, 'seo-by-rank-math-pro.zip', 'attachments/10/PnTAFcBsDeyIKexOE0c5jDoWL27PyGOruoBdNTLd.zip', 'application/zip', 1573248, 1, '2024-02-21 13:53:14'),
(13, 10, 'wp-rocket_3.10.8.zip', 'attachments/10/Kc97fo4cHfKED21jhaQfVIr1xlM4H2JJgA88vleL.zip', 'application/zip', 3080837, 1, '2024-02-21 13:53:14'),
(14, 10, 'z5130866833433_8cf84c5d0ee880d63456ededa3682643.jpg', 'attachments/10/dRRzLeztj6s0qA2yeiCKEfAf1huKowsmkSfX8922.jpg', 'image/jpeg', 7641, 1, '2024-02-21 13:53:14'),
(15, 10, 'z5130867401492_20c1da5c83ace3ffcf892558f9f21017.jpg', 'attachments/10/34hSxrj7JAHM8DaajcvTQOXUp4Df0qEs6RYvDcXh.jpg', 'image/jpeg', 14999, 1, '2024-02-21 13:53:14'),
(16, 11, 'z5130867401492_20c1da5c83ace3ffcf892558f9f21017.jpg', 'attachments/11/DrmTeAdfQtfobuy5mIniYOhbyj4nRGdCLni8s91v.jpg', 'image/jpeg', 14999, 1, '2024-02-23 12:49:13'),
(17, 11, 'seo-by-rank-math-pro.zip', 'attachments/11/7dWl6ayEg5Tr88Nz4M7jNgY9tbGeEyVKZmSJYlLE.zip', 'application/zip', 1573248, 1, '2024-02-23 12:49:13'),
(18, 11, 'wp-rocket_3.10.8.zip', 'attachments/11/3BBLGrTSfIeikN01Ydz6k14uduRbN5sjjxURSIEj.zip', 'application/zip', 3080837, 1, '2024-02-23 12:49:13'),
(19, 12, 'Screenshot 2024-02-08 144245.png', 'attachments/12/nvxWPrKSAbTaPYakCdoY72wlWApb89Y3xk0GTHTu.png', 'image/png', 84816, 1, '2024-02-24 13:41:17'),
(20, 13, 'Screenshot 2024-02-08 144245.png', 'attachments/13/gd3fCgZBGP0jCigEfEvedCa6BxtikwNIWyXaY24g.png', 'image/png', 84816, 1, '2024-03-03 13:56:51'),
(21, 13, 'seo-by-rank-math-pro.zip', 'attachments/13/e32D3RPcVn8B3NAgZOFLHQMF4oarz4V8Mmvdci4o.zip', 'application/zip', 1573248, 1, '2024-03-03 13:56:51'),
(22, 19, 'z4915120871664_6197e17d5a4b96fa15738d2b78a8d92f.jpg', 'attachments/19/a2WMMTbGs78OUvtY9oO7ToXSvuI0oy7Uas41RWYi.jpg', 'image/jpeg', 312655, 1, '2024-03-15 14:37:46'),
(23, 22, 'tải xuống.png', 'attachments/22/JABemstNw6L6vxGRpOfqVmmV9CAqa2So3VnInjdt.png', 'image/png', 5266, 1, '2024-03-15 14:51:17'),
(25, 23, 'tải xuống.jpg', 'attachments/23/nkay5ui4fxoTsdwO4O8nNrn026culwqauJSKu7UW.jpg', 'image/jpeg', 9399, 3, '2024-03-20 12:32:13'),
(26, 23, 'tải xuống.jpg', 'attachments/23/KoT4aTSFltFcNDaVtRRrkub1cveRyGZNgFRSdad9.jpg', 'image/jpeg', 11142, 3, '2024-03-20 12:35:57'),
(28, 24, 'tải xuống.png', 'attachments/24/OxhNOuyPQhZ0wuuH3amIEh9y9YoWomUgOXSjeroT.png', 'image/png', 5266, 3, '2024-03-20 12:37:08'),
(29, 22, 'GDPF0hlflsKFc5kGAB8k8DubnSEjbmdjAAAF.mp4', 'attachments/22/Cpt21QF6HC7X7Q4T9AWomvVzrCTyFpPHHNrqZFUo.mp4', 'video/mp4', 1450146, 1, '2024-03-22 12:46:34');

-- --------------------------------------------------------

--
-- Table structure for table `reactions`
--

CREATE TABLE `reactions` (
  `id` bigint UNSIGNED NOT NULL,
  `object_id` bigint UNSIGNED NOT NULL,
  `object_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reactions`
--

INSERT INTO `reactions` (`id`, `object_id`, `object_type`, `type`, `user_id`, `created_at`) VALUES
(13, 1, 'App\\Models\\Post', 'like', 1, '2024-02-28 14:17:19'),
(15, 11, 'App\\Models\\Post', 'like', 1, '2024-02-28 14:17:43'),
(20, 10, 'App\\Models\\Comment', 'like', 1, '2024-02-28 14:32:28'),
(23, 11, 'App\\Models\\Comment', 'like', 1, '2024-02-28 14:49:04'),
(26, 6, 'App\\Models\\Comment', 'like', 1, '2024-02-29 13:28:52'),
(27, 10, 'App\\Models\\Post', 'like', 1, '2024-02-29 13:29:02'),
(28, 19, 'App\\Models\\Comment', 'like', 1, '2024-03-01 07:16:49'),
(29, 35, 'App\\Models\\Comment', 'like', 1, '2024-03-03 06:30:40'),
(30, 48, 'App\\Models\\Comment', 'like', 1, '2024-03-03 13:37:30'),
(31, 49, 'App\\Models\\Comment', 'like', 1, '2024-03-03 13:37:44'),
(32, 14, 'App\\Models\\Post', 'like', 1, '2024-03-03 13:58:21'),
(33, 4, 'App\\Models\\Post', 'like', 1, '2024-03-19 13:18:38'),
(34, 22, 'App\\Models\\Post', 'like', 1, '2024-03-22 12:52:05'),
(35, 52, 'App\\Models\\Comment', 'like', 1, '2024-03-28 12:09:28'),
(36, 53, 'App\\Models\\Comment', 'like', 1, '2024-03-28 12:09:30');

-- --------------------------------------------------------

--
-- Table structure for table `social_providers`
--

CREATE TABLE `social_providers` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `provider_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_providers`
--

INSERT INTO `social_providers` (`id`, `user_id`, `provider_id`, `provider`) VALUES
(1, 21, '105202572159119828913', 'google'),
(7, 1, '105202572159119828913', 'google');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_path` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_path` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pinned_post_id` bigint UNSIGNED DEFAULT NULL,
  `otp` int DEFAULT NULL,
  `otp_expire_date` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_tokens` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `cover_path`, `avatar_path`, `email_verified_at`, `password`, `pinned_post_id`, `otp`, `otp_expire_date`, `remember_token`, `fcm_tokens`, `created_at`, `updated_at`) VALUES
(1, 'Quốc Trần', 'meoem2712@gmail.com', 'nghia-le', 'user-1/JctsC9CsMSy6SkegvwVpj1GN8wkawGXcOKBGF3U3.png', 'https://lh3.googleusercontent.com/a/ACg8ocJainEAWyX1nmAayiS5zJH3aZpe40uLQGZ6KR_W7HYE-f6q5r4=s96-c', '2024-02-14 03:34:28', '$2y$12$g93.a7u8fuGWk25dYNLtw.DA53f1gPg24v2TBs5rSQXpnO5BMUXFy', 10, NULL, NULL, '6sBvGUQq7IPy8U0vB6kk2KoF3h3BJYF2mrHxkyQBktPb6HbXabmqoeVlvec2', '[\"fls-U76XS96PcXRyLFkrdq:APA91bHaEo4t5Y00KcYO6_dleo-RnzpPkKgvulG19_sCLoglWQcx6CVrpRf01-ecfaUZmdszKMpAYTfAHORsExxrqvgaEJpEm6rvsxLBqjD7AfI9nKKFefwd2uDGgg3DptvQPZOrS_4I\"]', '2024-02-14 03:34:07', '2024-05-01 06:44:37'),
(2, 'Test User', 'linhthanhnguyet@gmail.com', 'test-user', NULL, NULL, '2024-02-27 14:43:01', '$2y$12$OPr/.ywjDKSuHBDlzoQX9eb0mg3tZQ7syOVM7aGxma6GGOD5WUH3a', NULL, NULL, NULL, NULL, NULL, '2024-02-27 14:42:31', '2024-02-27 14:43:01'),
(3, 'Sushi nhat ban', 'info@sotagroup.vn', 'sushi-nhat-ban', NULL, NULL, '2024-03-12 13:08:31', '$2y$12$iehqv5uI9hyQ4lSoqOw3C.6ytr.NQO2s.RR2eKnEKIwzGxgSNJDFa', NULL, NULL, NULL, NULL, NULL, '2024-03-12 13:07:44', '2024-03-12 13:08:31'),
(4, 'Chirashizushi', 'kry@gmail.com', 'chirashizushi', NULL, NULL, '2024-03-14 12:59:46', '$2y$12$Mg/3BD4LrxxkTnYpmTIyAuo8/88SsN4LaZItK5qdnIi9T8yfBAnC2', NULL, NULL, NULL, NULL, NULL, '2024-03-14 12:59:25', '2024-03-14 12:59:46'),
(21, 'Quoc Tran', 'abc@gmail.com', 'test-1', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocJainEAWyX1nmAayiS5zJH3aZpe40uLQGZ6KR_W7HYE-f6q5r4=s96-c', '2024-04-11 14:37:36', '$2y$12$FySxaKYvguhHw4Ri69hKl.Mb//ROMwK62KJvEG5NkgBDfCLn8Sot6', NULL, NULL, NULL, NULL, NULL, '2024-04-10 12:44:30', '2024-04-11 13:52:03'),
(27, 'Test 123', 'abc12@gmail.com', 'test-123', NULL, 'https://lh3.googleusercontent.com/a/ACg8ocJainEAWyX1nmAayiS5zJH3aZpe40uLQGZ6KR_W7HYE-f6q5r4=s96-c', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-11 14:03:05', '2024-04-11 14:03:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_post_id_foreign` (`post_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_user_id_foreign` (`user_id`),
  ADD KEY `events_category_id_foreign` (`category_id`);

--
-- Indexes for table `event_users`
--
ALTER TABLE `event_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_users_user_id_foreign` (`user_id`),
  ADD KEY `event_users_created_by_foreign` (`created_by`),
  ADD KEY `event_users_event_id_foreign` (`event_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `followers_user_id_foreign` (`user_id`),
  ADD KEY `followers_follower_id_foreign` (`follower_id`);

--
-- Indexes for table `follower_events`
--
ALTER TABLE `follower_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follower_event_user_id_foreign` (`user_id`),
  ADD KEY `follower_event_event_id_foreign` (`event_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `groups_user_id_foreign` (`user_id`),
  ADD KEY `groups_deleted_by_foreign` (`deleted_by`),
  ADD KEY `groups_pinned_post_id_foreign` (`pinned_post_id`);

--
-- Indexes for table `group_users`
--
ALTER TABLE `group_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_users_user_id_foreign` (`user_id`),
  ADD KEY `group_users_group_id_foreign` (`group_id`),
  ADD KEY `group_users_created_by_foreign` (`created_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_user_id_foreign` (`user_id`),
  ADD KEY `posts_group_id_foreign` (`group_id`),
  ADD KEY `posts_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `post_attachments`
--
ALTER TABLE `post_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_attachments_post_id_foreign` (`post_id`),
  ADD KEY `post_attachments_created_by_foreign` (`created_by`);

--
-- Indexes for table `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_reactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `social_providers`
--
ALTER TABLE `social_providers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `social_providers_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_pinned_post_id_foreign` (`pinned_post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `event_users`
--
ALTER TABLE `event_users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `follower_events`
--
ALTER TABLE `follower_events`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `group_users`
--
ALTER TABLE `group_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `post_attachments`
--
ALTER TABLE `post_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `reactions`
--
ALTER TABLE `reactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `social_providers`
--
ALTER TABLE `social_providers`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `event_users`
--
ALTER TABLE `event_users`
  ADD CONSTRAINT `event_users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `event_users_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `event_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_follower_id_foreign` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `followers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `follower_events`
--
ALTER TABLE `follower_events`
  ADD CONSTRAINT `follower_event_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `follower_event_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `groups_pinned_post_id_foreign` FOREIGN KEY (`pinned_post_id`) REFERENCES `posts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `group_users`
--
ALTER TABLE `group_users`
  ADD CONSTRAINT `group_users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `group_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `group_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `post_attachments`
--
ALTER TABLE `post_attachments`
  ADD CONSTRAINT `post_attachments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `post_attachments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `reactions`
--
ALTER TABLE `reactions`
  ADD CONSTRAINT `post_reactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `social_providers`
--
ALTER TABLE `social_providers`
  ADD CONSTRAINT `social_providers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_pinned_post_id_foreign` FOREIGN KEY (`pinned_post_id`) REFERENCES `posts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
