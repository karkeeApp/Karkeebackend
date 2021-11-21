-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2021 at 06:13 PM
-- Server version: 8.0.27-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_qa_carkee`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `account_id` int NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `days_unverified_reg` int DEFAULT '7',
  `club_code` int DEFAULT NULL,
  `confirmed_by` int DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `is_one_approval` tinyint DEFAULT '0',
  `num_days_expiry` int DEFAULT '30',
  `renewal_fee` decimal(10,3) DEFAULT '0.000',
  `enable_ads` tinyint DEFAULT '1',
  `enable_banner` tinyint DEFAULT '1',
  `skip_approval` tinyint DEFAULT '1',
  `member_expiry` datetime DEFAULT NULL,
  `renewal_alert` int DEFAULT '30',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `company_full_name` varchar(255) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo` text,
  `hash_id` varchar(45) DEFAULT NULL,
  `prefix` varchar(20) DEFAULT NULL,
  `user_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_membership`
--

DROP TABLE IF EXISTS `account_membership`;
CREATE TABLE `account_membership` (
  `id` int NOT NULL,
  `account_id` int NOT NULL,
  `user_id` int NOT NULL,
  `club_code` varchar(20) DEFAULT NULL,
  `filename` text,
  `description` text,
  `status` tinyint DEFAULT '1',
  `confirmed_by` int DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_security_questions`
--

DROP TABLE IF EXISTS `account_security_questions`;
CREATE TABLE `account_security_questions` (
  `id` int NOT NULL,
  `account_id` int NOT NULL,
  `question` text NOT NULL,
  `is_file_upload` tinyint DEFAULT '0',
  `status` tinyint DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_user`
--

DROP TABLE IF EXISTS `account_user`;
CREATE TABLE `account_user` (
  `user_id` int NOT NULL,
  `account_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `role` int NOT NULL DEFAULT '2',
  `email` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `admin_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` smallint NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `role` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
CREATE TABLE `ads` (
  `id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `is_bottom` tinyint DEFAULT '0',
  `link` text,
  `enable_ads` tinyint DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_remove_attachment`
--

DROP TABLE IF EXISTS `ads_remove_attachment`;
CREATE TABLE `ads_remove_attachment` (
  `id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `ads_id` bigint DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `filename` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner_images`
--

DROP TABLE IF EXISTS `banner_images`;
CREATE TABLE `banner_images` (
  `id` bigint NOT NULL,
  `banner_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  `filename` varchar(100) DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `uploaded_by` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `account_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner_management`
--

DROP TABLE IF EXISTS `banner_management`;
CREATE TABLE `banner_management` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` longtext,
  `status` tinyint DEFAULT '1',
  `managed_by` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `country_code` char(3) NOT NULL,
  `name` varchar(225) DEFAULT NULL,
  `nicename` varchar(80) NOT NULL,
  `iso3` varchar(3) DEFAULT NULL,
  `numcode` smallint DEFAULT NULL,
  `phonecode` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
CREATE TABLE `document` (
  `doc_id` bigint NOT NULL,
  `filename` varchar(225) DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `type` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='     ';

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `event_id` bigint NOT NULL,
  `account_id` bigint DEFAULT NULL,
  `order` int DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `is_closed` tinyint DEFAULT '0',
  `created_by` bigint DEFAULT NULL,
  `summary` mediumtext,
  `image` varchar(100) DEFAULT NULL,
  `is_public` tinyint DEFAULT '0',
  `place` varchar(255) DEFAULT NULL,
  `event_time` datetime DEFAULT NULL,
  `is_paid` tinyint DEFAULT '0',
  `event_fee` double DEFAULT NULL,
  `cut_off_at` datetime DEFAULT NULL,
  `limit` int DEFAULT '1000',
  `num_guest_brought_limit` int DEFAULT '10'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_attendee`
--

DROP TABLE IF EXISTS `event_attendee`;
CREATE TABLE `event_attendee` (
  `id` bigint NOT NULL,
  `event_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `paid` double DEFAULT '0',
  `filename` text,
  `name` varchar(255) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `num_guest_brought` int DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_gallery`
--

DROP TABLE IF EXISTS `event_gallery`;
CREATE TABLE `event_gallery` (
  `gallery_id` bigint NOT NULL,
  `account_id` bigint DEFAULT NULL,
  `event_id` bigint DEFAULT NULL,
  `filename` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_primary` tinyint DEFAULT '0',
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_notification`
--

DROP TABLE IF EXISTS `hr_notification`;
CREATE TABLE `hr_notification` (
  `notification_id` bigint NOT NULL,
  `account_id` bigint DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `message` mediumtext,
  `created_at` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `hr_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_setting`
--

DROP TABLE IF EXISTS `hr_setting`;
CREATE TABLE `hr_setting` (
  `setting_id` bigint NOT NULL,
  `account_id` bigint DEFAULT NULL,
  `cut_off` int DEFAULT NULL,
  `created_at` varchar(45) DEFAULT NULL,
  `working_days` mediumtext,
  `leave_full` tinyint DEFAULT '1',
  `leave_half` tinyint DEFAULT '1',
  `leave_quarter` tinyint DEFAULT '1',
  `salary_date` int DEFAULT NULL,
  `loan_cut_off` int DEFAULT NULL,
  `salary_tax` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_staff_update`
--

DROP TABLE IF EXISTS `hr_staff_update`;
CREATE TABLE `hr_staff_update` (
  `id` bigint NOT NULL,
  `account_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `type` int DEFAULT NULL,
  `before_attributes` mediumtext,
  `after_attributes` mediumtext,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `updated_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `item_id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` bigint DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `limit` int DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `amount` decimal(11,2) DEFAULT '0.00',
  `approved_by` bigint DEFAULT NULL,
  `confirmed_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_gallery`
--

DROP TABLE IF EXISTS `item_gallery`;
CREATE TABLE `item_gallery` (
  `gallery_id` bigint NOT NULL,
  `account_id` bigint DEFAULT NULL,
  `item_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `filename` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_primary` tinyint DEFAULT '0',
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_redeem`
--

DROP TABLE IF EXISTS `item_redeem`;
CREATE TABLE `item_redeem` (
  `redeem_id` bigint NOT NULL,
  `item_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listing`
--

DROP TABLE IF EXISTS `listing`;
CREATE TABLE `listing` (
  `listing_id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` bigint DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` mediumtext,
  `image` varchar(100) DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `approved_by` bigint DEFAULT NULL,
  `confirmed_by` bigint DEFAULT NULL,
  `is_primary` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listing_gallery`
--

DROP TABLE IF EXISTS `listing_gallery`;
CREATE TABLE `listing_gallery` (
  `gallery_id` bigint NOT NULL,
  `account_id` bigint DEFAULT NULL,
  `listing_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `filename` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_primary` tinyint DEFAULT '0',
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `media_id` bigint NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `filename` varchar(225) DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_expiry`
--

DROP TABLE IF EXISTS `member_expiry`;
CREATE TABLE `member_expiry` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `account_id` bigint NOT NULL,
  `renewal_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `member_expiry` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='                       ';

-- --------------------------------------------------------

--
-- Table structure for table `member_security_answers`
--

DROP TABLE IF EXISTS `member_security_answers`;
CREATE TABLE `member_security_answers` (
  `id` int NOT NULL,
  `account_membership_id` int NOT NULL,
  `account_id` int NOT NULL,
  `user_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer` text,
  `status` tinyint DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Truncate table before insert `migration`
--

TRUNCATE TABLE `migration`;
--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m130524_201442_init', 1582610755),
('m200223_112936_user_register', 1582610759),
('m200224_141540_alter_user_onboarding', 1582610759),
('m200225_063358_session_table', 1583054394),
('m200301_090026_user_account_user', 1583054466),
('m200303_103003_item', 1583231648),
('m200305_140245_alter_user_vendor', 1583417053),
('m200305_141719_alter_item_amount', 1583418191),
('m200308_085309_item_upload', 1583663613),
('m200309_032708_vendor_name', 1583724516),
('m200310_030623_account_hash_id', 1583811420),
('m200310_033420_news', 1583811420),
('m200310_090929_news_category', 1583831421),
('m200315_073011_vendor_founded', 1584257531),
('m200316_035408_user_uiid', 1584337131),
('m200320_052914_annual_salary', 1584682333),
('m200322_075931_emergency_code', 1584864612),
('m200322_082913_member_type', 1584866550),
('m200322_090943_telephone_code', 1584881229),
('m200324_062757_account_prefix', 1585031431),
('m200325_022722_vendor_profile', 1585103416),
('m200403_075928_account_user', 1585993248),
('m200406_071027_admin_role', 1586157447),
('m200410_064032_club_onboarding', 1586501081),
('m200412_153648_car_images', 1586706015),
('m200504_141900_news_order', 1588602651),
('m200505_123924_watchdog', 1588682701),
('m200507_114616_alter_country_table', 1588864388),
('m200507_150228_insert_country_table', 1588864388),
('m200508_142215_password_reset', 1588948581),
('m200610_025901_carkee_member_type', 1591776399),
('m200610_143519_carkee_settings', 1591804640),
('m200821_142521_admin_permission', 1598930206),
('m200908_102227_user_role', 1599561229),
('m200910_073309_news_multi_categories', 1599723732),
('m200922_145900_news_gallery', 1600787226),
('m201007_041213_alter_new_table', 1602050028),
('m201014_052650_alter_news', 1602661129),
('m201014_070922_listing', 1602661132),
('m201014_104014_user_alter', 1602672218),
('m201014_152211_event', 1602689070),
('m201015_091859_create_event_attendee_table', 1602757264),
('m201020_040835_company', 1603171718),
('m201021_021604_user_approved_at', 1603246710),
('m201023_032439_create_banner_management_table', 1603474308),
('m201023_032516_create_banner_images_table', 1603474308),
('m201026_014111_banner_alter', 1603678759),
('m201026_082845_alter_banner_image_table', 1611800785),
('m201218_023134_existing_member', 1611800788),
('m210128_021135_alter_user_p9', 1611800788),
('m210219_102935_alter_user_table', 1613730810),
('m210223_025959_alter_user_table', 1614049377),
('m210223_032149_alter_user_table', 1614050565),
('m210224_083206_renewal', 1614156781),
('m210301_050339_create_user_sponsor_table', 1614581039),
('m210301_061230_create_sponsor_table', 1614581039),
('m210305_053032_alter_settings_table', 1615629513),
('m210313_092729_user_social_media_table', 1615629513),
('m210316_173139_create_user_fcm_table', 1615917371),
('m210328_131717_create_ads_table', 1616937827),
('m210404_172728_alter_ads_table', 1617594107),
('m210405_042727_create_ads_remove_attachment_table', 1618155786),
('m210405_051130_alter_ads_table', 1618155786),
('m210411_152335_alter_user_table', 1618155786),
('m210411_153226_user_payment_table', 1618155786),
('m210411_160125_user_payment_attachment', 1618156983),
('m210411_194432_alter_user_table', 1618175664),
('m210411_203554_alter_user_payment_table', 1618175664),
('m210412_085028_alter_ads_table', 1618217730),
('m210412_085813_alter_ads_table', 1618217963),
('m210422_123454_alter_user_fcm_table', 1619114865),
('m210422_185308_support_table', 1619118209),
('m210427_075221_alter_account_table', 1619511231),
('m210427_173950_support_reply_table', 1619545782),
('m210501_174845_create_queue_table', 1619892867),
('m210501_220538_alter_event_table', 1619907056),
('m210610_140717_alter_event_table', 1623335168),
('m210610_141244_alter_event_attendee_table', 1623335168),
('m210622_122207_alter_event_table', 1624392090),
('m210622_122235_alter_event_attendee_table', 1624392090),
('m210622_140142_alter_settings_table', 1624392090),
('m210622_142911_alter_settings_table', 1624392090),
('m210622_160850_alter_settings_table', 1624392090),
('m210622_190931_create_member_expiry_table', 1624392090),
('m210622_191413_alter_renewal_table', 1624392090),
('m210625_050240_alter_renewal_table', 1624597633),
('m210819_173236_alter_settings_table', 1629410216),
('m210819_182537_alter_account_table', 1629410216),
('m210819_190232_create_user_settings_table', 1629410216),
('m210820_112508_alter_account_table', 1629465419),
('m210820_112516_alter_settings_table', 1629465419),
('m210820_112736_alter_user_settings_table', 1629465419),
('m210821_170015_alter_account_table', 1629573419),
('m210826_201335_alter_account_table', 1630036371),
('m210830_205200_alter_settings_table', 1630358192),
('m210906_143740_alter_user_settings_table', 1630943197),
('m210907_153517_alter_account_settings_table', 1631029687),
('m210908_044837_create_account_membership_table', 1631083657),
('m210908_045344_create_account_security_questions_table', 1631083657),
('m210908_045521_create_member_security_answers_table', 1631083657),
('m210908_071316_alter_account_membership_table', 1631085743),
('m210908_084917_alter_account_membership_table', 1631091124),
('m210908_094046_alter_account_security_questions_table', 1631156317),
('m210908_101423_create_security_file_upload_table', 1631156317),
('m210909_134531_alter_ads_table', 1631195954),
('m210915_045539_alter_account_and_settings_table', 1631682509),
('m210924_075734_alter_account_membership_table', 1632471390),
('m210925_095706_alter_user_payment_table', 1632586878),
('m211020_043902_alter_listing_table', 1636543918),
('m211110_111433_alter_user_payment_table', 1636543918),
('m211110_115211_alter_account_user_settings_table', 1636545628),
('m211111_034327_alter_account_user_settings_table', 1636602298),
('m211117_064102_alter_event_attendee_table', 1637131359),
('m211118_074409_alter_event_attendee_table', 1637221553),
('m211118_100235_alter_user_payment_table', 1637229807),
('m211118_150157_alter_settings_table', 1637252188),
('m211118_163619_alter_events_table', 1637253463);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `news_id` bigint NOT NULL,
  `account_id` bigint DEFAULT NULL,
  `order` int DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `created_by` bigint DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `category_id` int DEFAULT '0',
  `is_news` tinyint DEFAULT '0',
  `is_guest` tinyint DEFAULT '0',
  `is_trending` tinyint DEFAULT '0',
  `is_event` tinyint DEFAULT '0',
  `is_happening` tinyint DEFAULT '0',
  `is_public` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_gallery`
--

DROP TABLE IF EXISTS `news_gallery`;
CREATE TABLE `news_gallery` (
  `gallery_id` bigint NOT NULL,
  `account_id` bigint DEFAULT NULL,
  `news_id` bigint DEFAULT NULL,
  `filename` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_primary` tinyint DEFAULT '0',
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `notification_id` bigint NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` mediumtext,
  `admin_id` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `recipient` mediumtext,
  `sent` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `page_id` bigint NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_admin_id` bigint DEFAULT NULL,
  `updated_admin_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
CREATE TABLE `queue` (
  `id` int NOT NULL,
  `channel` varchar(255) NOT NULL,
  `job` blob NOT NULL,
  `pushed_at` int NOT NULL,
  `ttr` int NOT NULL,
  `delay` int NOT NULL DEFAULT '0',
  `priority` int UNSIGNED NOT NULL DEFAULT '1024',
  `reserved_at` int DEFAULT NULL,
  `attempt` int DEFAULT NULL,
  `done_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `renewal`
--

DROP TABLE IF EXISTS `renewal`;
CREATE TABLE `renewal` (
  `id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` bigint DEFAULT NULL,
  `expiry_id` bigint DEFAULT NULL,
  `paid` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `filename` varchar(225) DEFAULT NULL,
  `log_card` varchar(191) DEFAULT NULL COMMENT 'log card img file',
  `updated_by` bigint DEFAULT NULL,
  `status` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='                       ';

-- --------------------------------------------------------

--
-- Table structure for table `security_file_upload`
--

DROP TABLE IF EXISTS `security_file_upload`;
CREATE TABLE `security_file_upload` (
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `id` char(40) NOT NULL,
  `expire` int DEFAULT NULL,
  `data` blob,
  `user_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `setting_id` int NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` bigint DEFAULT NULL,
  `master_account_id` int DEFAULT '0',
  `default_interest` float(11,2) DEFAULT NULL,
  `content` longtext,
  `renewal_fee` double DEFAULT '100',
  `title` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT 'Karkee',
  `logo` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT 'Karkee Admin',
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `days_unverified_reg` int DEFAULT '7',
  `club_code` int DEFAULT NULL,
  `is_one_approval` tinyint DEFAULT '1',
  `num_days_expiry` int DEFAULT '30',
  `enable_ads` tinyint DEFAULT '1',
  `enable_banner` tinyint DEFAULT '1',
  `skip_approval` tinyint DEFAULT '1',
  `member_expiry` datetime DEFAULT NULL,
  `renewal_alert` int DEFAULT '30',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sponsor`
--

DROP TABLE IF EXISTS `sponsor`;
CREATE TABLE `sponsor` (
  `id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext,
  `status` tinyint DEFAULT '1',
  `category` tinyint DEFAULT '1',
  `managed_by` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

DROP TABLE IF EXISTS `support`;
CREATE TABLE `support` (
  `id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` int DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_reply`
--

DROP TABLE IF EXISTS `support_reply`;
CREATE TABLE `support_reply` (
  `id` bigint NOT NULL,
  `support_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` int DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `message` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` bigint NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `auth_key` varchar(32) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `pin_hash` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` smallint NOT NULL DEFAULT '1',
  `premium_status` tinyint DEFAULT '0',
  `is_premium` tinyint DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `account_id` bigint NOT NULL,
  `mobile` varchar(45) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `birthday` blob,
  `firstname` varchar(225) DEFAULT NULL,
  `lastname` varchar(225) DEFAULT NULL,
  `mobile_code` varchar(5) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `unit_no` varchar(10) DEFAULT NULL,
  `add_1` varchar(255) DEFAULT NULL,
  `add_2` varchar(255) DEFAULT NULL,
  `nric` varchar(15) DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `annual_salary` varchar(100) DEFAULT NULL,
  `chasis_number` varchar(50) DEFAULT NULL,
  `plate_no` varchar(255) DEFAULT NULL,
  `car_model` varchar(50) DEFAULT NULL,
  `registration_code` varchar(45) DEFAULT NULL,
  `are_you_owner` tinyint DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `emergency_code` varchar(5) DEFAULT NULL,
  `emergency_no` varchar(20) DEFAULT NULL,
  `relationship` tinyint DEFAULT NULL,
  `img_profile` varchar(255) DEFAULT NULL,
  `img_nric` varchar(255) DEFAULT NULL,
  `img_insurance` varchar(255) DEFAULT NULL,
  `img_authorization` varchar(255) DEFAULT NULL,
  `img_log_card` varchar(255) DEFAULT NULL,
  `img_vendor` varchar(255) DEFAULT NULL,
  `transfer_no` varchar(45) DEFAULT NULL,
  `transfer_banking_nick` varchar(45) DEFAULT NULL,
  `transfer_date` date DEFAULT NULL,
  `transfer_amount` decimal(11,2) DEFAULT NULL,
  `transfer_screenshot` varchar(255) DEFAULT NULL,
  `step` tinyint DEFAULT '1',
  `is_vendor` tinyint DEFAULT '0',
  `vendor_name` varchar(255) DEFAULT NULL,
  `vendor_description` varchar(255) DEFAULT NULL,
  `about` mediumtext,
  `ios_uiid` varchar(100) DEFAULT NULL,
  `android_uiid` varchar(100) DEFAULT NULL,
  `ios_biometric` tinyint DEFAULT '0',
  `android_biometric` tinyint DEFAULT '0',
  `telephone_no` varchar(20) DEFAULT NULL,
  `founded_date` date DEFAULT NULL,
  `member_type` tinyint DEFAULT '1',
  `carkee_member_type` tinyint DEFAULT '5',
  `telephone_code` varchar(5) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `eun` varchar(20) DEFAULT NULL,
  `number_of_employees` int DEFAULT NULL,
  `img_acra` varchar(255) DEFAULT NULL,
  `img_memorandum` varchar(255) DEFAULT NULL,
  `img_car_front` varchar(255) DEFAULT NULL,
  `img_car_back` varchar(255) DEFAULT NULL,
  `img_car_left` varchar(255) DEFAULT NULL,
  `img_car_right` varchar(255) DEFAULT NULL,
  `reset_code` varchar(6) DEFAULT NULL,
  `longitude` decimal(10,8) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `member_expire` date DEFAULT NULL,
  `approved_by` bigint DEFAULT NULL,
  `confirmed_by` bigint DEFAULT NULL,
  `role` tinyint DEFAULT NULL,
  `company_mobile_code` varchar(3) DEFAULT NULL,
  `company_mobile` varchar(15) DEFAULT NULL,
  `company_email` varchar(225) DEFAULT NULL,
  `company_country` varchar(50) DEFAULT NULL,
  `company_postal_code` varchar(10) DEFAULT NULL,
  `company_unit_no` varchar(10) DEFAULT NULL,
  `company_add_1` varchar(225) DEFAULT NULL,
  `company_add_2` varchar(225) DEFAULT NULL,
  `company_logo` longtext,
  `brand_synopsis` longtext,
  `brand_guide` longtext,
  `club_logo` longtext,
  `insurance_date` date DEFAULT NULL,
  `level` tinyint DEFAULT '0',
  `carkee_level` tinyint DEFAULT '0',
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_director`
--

DROP TABLE IF EXISTS `user_director`;
CREATE TABLE `user_director` (
  `director_id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_code` varchar(5) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `is_director` tinyint DEFAULT '0',
  `is_shareholder` tinyint DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `account_id` bigint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_existing`
--

DROP TABLE IF EXISTS `user_existing`;
CREATE TABLE `user_existing` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `plate_no` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `account_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_fcm_token`
--

DROP TABLE IF EXISTS `user_fcm_token`;
CREATE TABLE `user_fcm_token` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `account_id` int DEFAULT '0',
  `fcm_token` mediumtext,
  `fcm_topics` varchar(255) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_file`
--

DROP TABLE IF EXISTS `user_file`;
CREATE TABLE `user_file` (
  `file_id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `filename` varchar(225) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `type` int DEFAULT NULL,
  `account_id` bigint DEFAULT NULL,
  `decription` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

DROP TABLE IF EXISTS `user_logs`;
CREATE TABLE `user_logs` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `renewal_id` bigint DEFAULT NULL,
  `log_card` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '1' COMMENT '1: member, 2: renewal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_notification`
--

DROP TABLE IF EXISTS `user_notification`;
CREATE TABLE `user_notification` (
  `notification_id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `message` mediumtext,
  `created_at` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_payment`
--

DROP TABLE IF EXISTS `user_payment`;
CREATE TABLE `user_payment` (
  `id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `renewal_id` int DEFAULT NULL,
  `event_id` int DEFAULT NULL,
  `amount` decimal(10,3) DEFAULT NULL,
  `payment_for` tinyint DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `filename` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `confirmed_by` int DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_payment_attachment`
--

DROP TABLE IF EXISTS `user_payment_attachment`;
CREATE TABLE `user_payment_attachment` (
  `id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `payment_id` bigint DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `filename` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `amount` decimal(10,3) DEFAULT '0.000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
CREATE TABLE `user_settings` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `account_id` bigint NOT NULL,
  `enable_ads` tinyint DEFAULT '1',
  `skip_approval` tinyint DEFAULT '1',
  `renewal_alert` int DEFAULT '30',
  `status` tinyint DEFAULT '1',
  `verification_code` varchar(10) DEFAULT NULL,
  `is_verified` tinyint DEFAULT '0',
  `club_code` int DEFAULT NULL,
  `is_one_approval` tinyint DEFAULT '0',
  `num_days_expiry` int DEFAULT '30',
  `renewal_fee` decimal(10,3) DEFAULT '0.000',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_social_media`
--

DROP TABLE IF EXISTS `user_social_media`;
CREATE TABLE `user_social_media` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `social_media_id` mediumtext,
  `social_media_type` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_sponsor`
--

DROP TABLE IF EXISTS `user_sponsor`;
CREATE TABLE `user_sponsor` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `sponsor_id` bigint DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `watchdog`
--

DROP TABLE IF EXISTS `watchdog`;
CREATE TABLE `watchdog` (
  `wid` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `message` longtext NOT NULL,
  `variables` longtext NOT NULL,
  `referer` varchar(128) DEFAULT NULL,
  `hostname` varchar(128) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `account_id` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `club_code` (`club_code`);

--
-- Indexes for table `account_membership`
--
ALTER TABLE `account_membership`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_security_questions`
--
ALTER TABLE `account_security_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_user`
--
ALTER TABLE `account_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ads_remove_attachment`
--
ALTER TABLE `ads_remove_attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_images`
--
ALTER TABLE `banner_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_management`
--
ALTER TABLE `banner_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`country_code`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_attendee`
--
ALTER TABLE `event_attendee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_gallery`
--
ALTER TABLE `event_gallery`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `hr_notification`
--
ALTER TABLE `hr_notification`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `hr_setting`
--
ALTER TABLE `hr_setting`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `hr_staff_update`
--
ALTER TABLE `hr_staff_update`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `item_gallery`
--
ALTER TABLE `item_gallery`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `item_redeem`
--
ALTER TABLE `item_redeem`
  ADD PRIMARY KEY (`redeem_id`);

--
-- Indexes for table `listing`
--
ALTER TABLE `listing`
  ADD PRIMARY KEY (`listing_id`);

--
-- Indexes for table `listing_gallery`
--
ALTER TABLE `listing_gallery`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `member_expiry`
--
ALTER TABLE `member_expiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_security_answers`
--
ALTER TABLE `member_security_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `news_gallery`
--
ALTER TABLE `news_gallery`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel` (`channel`),
  ADD KEY `reserved_at` (`reserved_at`),
  ADD KEY `priority` (`priority`);

--
-- Indexes for table `renewal`
--
ALTER TABLE `renewal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `security_file_upload`
--
ALTER TABLE `security_file_upload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_reply`
--
ALTER TABLE `support_reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Indexes for table `user_director`
--
ALTER TABLE `user_director`
  ADD PRIMARY KEY (`director_id`);

--
-- Indexes for table `user_existing`
--
ALTER TABLE `user_existing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_fcm_token`
--
ALTER TABLE `user_fcm_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_file`
--
ALTER TABLE `user_file`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_notification`
--
ALTER TABLE `user_notification`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `user_payment`
--
ALTER TABLE `user_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_payment_attachment`
--
ALTER TABLE `user_payment_attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_social_media`
--
ALTER TABLE `user_social_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_sponsor`
--
ALTER TABLE `user_sponsor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `watchdog`
--
ALTER TABLE `watchdog`
  ADD PRIMARY KEY (`wid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_membership`
--
ALTER TABLE `account_membership`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_security_questions`
--
ALTER TABLE `account_security_questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_user`
--
ALTER TABLE `account_user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_remove_attachment`
--
ALTER TABLE `ads_remove_attachment`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner_images`
--
ALTER TABLE `banner_images`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner_management`
--
ALTER TABLE `banner_management`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `doc_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_attendee`
--
ALTER TABLE `event_attendee`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_gallery`
--
ALTER TABLE `event_gallery`
  MODIFY `gallery_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_notification`
--
ALTER TABLE `hr_notification`
  MODIFY `notification_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_setting`
--
ALTER TABLE `hr_setting`
  MODIFY `setting_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_staff_update`
--
ALTER TABLE `hr_staff_update`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_gallery`
--
ALTER TABLE `item_gallery`
  MODIFY `gallery_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_redeem`
--
ALTER TABLE `item_redeem`
  MODIFY `redeem_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listing`
--
ALTER TABLE `listing`
  MODIFY `listing_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listing_gallery`
--
ALTER TABLE `listing_gallery`
  MODIFY `gallery_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_expiry`
--
ALTER TABLE `member_expiry`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_security_answers`
--
ALTER TABLE `member_security_answers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_gallery`
--
ALTER TABLE `news_gallery`
  MODIFY `gallery_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `page_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `renewal`
--
ALTER TABLE `renewal`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `security_file_upload`
--
ALTER TABLE `security_file_upload`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sponsor`
--
ALTER TABLE `sponsor`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_reply`
--
ALTER TABLE `support_reply`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_director`
--
ALTER TABLE `user_director`
  MODIFY `director_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_existing`
--
ALTER TABLE `user_existing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_fcm_token`
--
ALTER TABLE `user_fcm_token`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_file`
--
ALTER TABLE `user_file`
  MODIFY `file_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_notification`
--
ALTER TABLE `user_notification`
  MODIFY `notification_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_payment`
--
ALTER TABLE `user_payment`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_payment_attachment`
--
ALTER TABLE `user_payment_attachment`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_social_media`
--
ALTER TABLE `user_social_media`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_sponsor`
--
ALTER TABLE `user_sponsor`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `watchdog`
--
ALTER TABLE `watchdog`
  MODIFY `wid` int NOT NULL AUTO_INCREMENT;
COMMIT;


TRUNCATE TABLE `settings`;
--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `user_id`, `account_id`, `master_account_id`, `default_interest`, `content`, `renewal_fee`, `title`, `email`, `company`, `logo`, `contact_name`, `address`, `status`, `days_unverified_reg`, `club_code`, `is_one_approval`, `num_days_expiry`, `enable_ads`, `enable_banner`, `skip_approval`, `member_expiry`, `renewal_alert`, `created_at`, `updated_at`) VALUES
(2, 1, 8, 0, NULL, NULL, 50, NULL, NULL, 'Karkee', NULL, 'Karkee Admin', NULL, 1, 7, NULL, 1, 30, 1, 1, 1, NULL, 30, NULL, '2021-07-06 06:37:18');

--
-- Truncate table before insert `user`
--

TRUNCATE TABLE `user`;
--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `pin_hash`, `email`, `status`, `premium_status`, `is_premium`, `created_at`, `updated_at`, `account_id`, `mobile`, `gender`, `birthday`, `firstname`, `lastname`, `mobile_code`, `country`, `postal_code`, `unit_no`, `add_1`, `add_2`, `nric`, `profession`, `company`, `annual_salary`, `chasis_number`, `plate_no`, `car_model`, `registration_code`, `are_you_owner`, `contact_person`, `emergency_code`, `emergency_no`, `relationship`, `img_profile`, `img_nric`, `img_insurance`, `img_authorization`, `img_log_card`, `img_vendor`, `transfer_no`, `transfer_banking_nick`, `transfer_date`, `transfer_amount`, `transfer_screenshot`, `step`, `is_vendor`, `vendor_name`, `vendor_description`, `about`, `ios_uiid`, `android_uiid`, `ios_biometric`, `android_biometric`, `telephone_no`, `founded_date`, `member_type`, `carkee_member_type`, `telephone_code`, `fullname`, `eun`, `number_of_employees`, `img_acra`, `img_memorandum`, `img_car_front`, `img_car_back`, `img_car_left`, `img_car_right`, `reset_code`, `longitude`, `latitude`, `member_expire`, `approved_by`, `confirmed_by`, `role`, `company_mobile_code`, `company_mobile`, `company_email`, `company_country`, `company_postal_code`, `company_unit_no`, `company_add_1`, `company_add_2`, `company_logo`, `brand_synopsis`, `brand_guide`, `club_logo`, `insurance_date`, `level`, `carkee_level`, `approved_at`) VALUES
(1, 'test', 'wok5AQf3ggPNqMD_lF1UmSqEd2Cwf9uK', '$2y$12$Q5Co/INb8m5rLUrP5oEbke304EAc.YBrlyH/feAT5VXfHoKfE4E2K', NULL, '$2y$13$QGxbrzoP8t3N2g3fu73QTe2hrTdm0qK5awauAO6BZ69dwNDyL/W4W', 'demo@yopmail.com', 3, 1, 0, '2020-01-04 08:43:17', '2021-04-28 14:41:56', 8, '34343434', 'm', 0x323031302d30312d3031, '1', '1', '+65', 'Singapore', '123123', '3434', 'test', '', 'g3151119k', 'asdf', 'asdf', 'Less than 99K', '5273737e', '647474', 'hdhdhd', '2020-09-14', 1, 'hdhdhdhd', '+65', '62929295', 1, '2253d83b1600069505.jpg', 'dd9c519d1600069492.jpg', '74cf498f1600069515.jpg', NULL, '6c37d2e01600069535.jpg', NULL, NULL, NULL, NULL, NULL, '9d5cdd9a1600069547.jpg', 6, 0, 'vendor 9999', 'Im a vendor', 'testt', NULL, '3109738030ae3047', 0, 0, '', '2020-01-04', 2, 5, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'test brand ', '464a8ee21619056418.jpg', '485cdfc81619056385.jpg', NULL, 0, 0, '2021-03-17 10:15:55');

--
-- Truncate table before insert `user_settings`
--

TRUNCATE TABLE `user_settings`;
--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`id`, `user_id`, `account_id`, `enable_ads`, `skip_approval`, `renewal_alert`, `status`, `verification_code`, `is_verified`, `club_code`, `is_one_approval`, `num_days_expiry`, `renewal_fee`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 1, 1, 30, 1, '770876', 0, NULL, 0, 30, '0.000', '2021-08-20 00:56:24', '2021-08-20 00:56:24');

--
-- Truncate table before insert `country`
--

TRUNCATE TABLE `country`;
--
-- Dumping data for table `country`
--

INSERT INTO `country` (`country_code`, `name`, `nicename`, `iso3`, `numcode`, `phonecode`) VALUES
('AD', 'ANDORRA', 'Andorra', 'AND', 20, 376),
('AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784, 971),
('AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4, 93),
('AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28, 1268),
('AI', 'ANGUILLA', 'Anguilla', 'AIA', 660, 1264),
('AL', 'ALBANIA', 'Albania', 'ALB', 8, 355),
('AM', 'ARMENIA', 'Armenia', 'ARM', 51, 374),
('AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530, 599),
('AO', 'ANGOLA', 'Angola', 'AGO', 24, 244),
('AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL, 0),
('AR', 'ARGENTINA', 'Argentina', 'ARG', 32, 54),
('AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16, 1684),
('AT', 'AUSTRIA', 'Austria', 'AUT', 40, 43),
('AU', 'AUSTRALIA', 'Australia', 'AUS', 36, 61),
('AW', 'ARUBA', 'Aruba', 'ABW', 533, 297),
('AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31, 994),
('BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70, 387),
('BB', 'BARBADOS', 'Barbados', 'BRB', 52, 1246),
('BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50, 880),
('BE', 'BELGIUM', 'Belgium', 'BEL', 56, 32),
('BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854, 226),
('BG', 'BULGARIA', 'Bulgaria', 'BGR', 100, 359),
('BH', 'BAHRAIN', 'Bahrain', 'BHR', 48, 973),
('BI', 'BURUNDI', 'Burundi', 'BDI', 108, 257),
('BJ', 'BENIN', 'Benin', 'BEN', 204, 229),
('BM', 'BERMUDA', 'Bermuda', 'BMU', 60, 1441),
('BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96, 673),
('BO', 'BOLIVIA', 'Bolivia', 'BOL', 68, 591),
('BR', 'BRAZIL', 'Brazil', 'BRA', 76, 55),
('BS', 'BAHAMAS', 'Bahamas', 'BHS', 44, 1242),
('BT', 'BHUTAN', 'Bhutan', 'BTN', 64, 975),
('BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL, 0),
('BW', 'BOTSWANA', 'Botswana', 'BWA', 72, 267),
('BY', 'BELARUS', 'Belarus', 'BLR', 112, 375),
('BZ', 'BELIZE', 'Belize', 'BLZ', 84, 501),
('CA', 'CANADA', 'Canada', 'CAN', 124, 1),
('CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL, 672),
('CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180, 242),
('CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140, 236),
('CG', 'CONGO', 'Congo', 'COG', 178, 242),
('CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756, 41),
('CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384, 225),
('CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184, 682),
('CL', 'CHILE', 'Chile', 'CHL', 152, 56),
('CM', 'CAMEROON', 'Cameroon', 'CMR', 120, 237),
('CN', 'CHINA', 'China', 'CHN', 156, 86),
('CO', 'COLOMBIA', 'Colombia', 'COL', 170, 57),
('CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188, 506),
('CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL, 381),
('CU', 'CUBA', 'Cuba', 'CUB', 192, 53),
('CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132, 238),
('CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL, 61),
('CY', 'CYPRUS', 'Cyprus', 'CYP', 196, 357),
('CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203, 420),
('DE', 'GERMANY', 'Germany', 'DEU', 276, 49),
('DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262, 253),
('DK', 'DENMARK', 'Denmark', 'DNK', 208, 45),
('DM', 'DOMINICA', 'Dominica', 'DMA', 212, 1767),
('DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214, 1809),
('DZ', 'ALGERIA', 'Algeria', 'DZA', 12, 213),
('EC', 'ECUADOR', 'Ecuador', 'ECU', 218, 593),
('EE', 'ESTONIA', 'Estonia', 'EST', 233, 372),
('EG', 'EGYPT', 'Egypt', 'EGY', 818, 20),
('EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732, 212),
('ER', 'ERITREA', 'Eritrea', 'ERI', 232, 291),
('ES', 'SPAIN', 'Spain', 'ESP', 724, 34),
('ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231, 251),
('FI', 'FINLAND', 'Finland', 'FIN', 246, 358),
('FJ', 'FIJI', 'Fiji', 'FJI', 242, 679),
('FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238, 500),
('FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583, 691),
('FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234, 298),
('FR', 'FRANCE', 'France', 'FRA', 250, 33),
('GA', 'GABON', 'Gabon', 'GAB', 266, 241),
('GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826, 44),
('GD', 'GRENADA', 'Grenada', 'GRD', 308, 1473),
('GE', 'GEORGIA', 'Georgia', 'GEO', 268, 995),
('GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254, 594),
('GH', 'GHANA', 'Ghana', 'GHA', 288, 233),
('GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292, 350),
('GL', 'GREENLAND', 'Greenland', 'GRL', 304, 299),
('GM', 'GAMBIA', 'Gambia', 'GMB', 270, 220),
('GN', 'GUINEA', 'Guinea', 'GIN', 324, 224),
('GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312, 590),
('GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226, 240),
('GR', 'GREECE', 'Greece', 'GRC', 300, 30),
('GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL, 0),
('GT', 'GUATEMALA', 'Guatemala', 'GTM', 320, 502),
('GU', 'GUAM', 'Guam', 'GUM', 316, 1671),
('GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624, 245),
('GY', 'GUYANA', 'Guyana', 'GUY', 328, 592),
('HK', 'HONG KONG', 'Hong Kong', 'HKG', 344, 852),
('HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL, 0),
('HN', 'HONDURAS', 'Honduras', 'HND', 340, 504),
('HR', 'CROATIA', 'Croatia', 'HRV', 191, 385),
('HT', 'HAITI', 'Haiti', 'HTI', 332, 509),
('HU', 'HUNGARY', 'Hungary', 'HUN', 348, 36),
('ID', 'INDONESIA', 'Indonesia', 'IDN', 360, 62),
('IE', 'IRELAND', 'Ireland', 'IRL', 372, 353),
('IL', 'ISRAEL', 'Israel', 'ISR', 376, 972),
('IN', 'INDIA', 'India', 'IND', 356, 91),
('IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL, 246),
('IQ', 'IRAQ', 'Iraq', 'IRQ', 368, 964),
('IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364, 98),
('IS', 'ICELAND', 'Iceland', 'ISL', 352, 354),
('IT', 'ITALY', 'Italy', 'ITA', 380, 39),
('JM', 'JAMAICA', 'Jamaica', 'JAM', 388, 1876),
('JO', 'JORDAN', 'Jordan', 'JOR', 400, 962),
('JP', 'JAPAN', 'Japan', 'JPN', 392, 81),
('KE', 'KENYA', 'Kenya', 'KEN', 404, 254),
('KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417, 996),
('KH', 'CAMBODIA', 'Cambodia', 'KHM', 116, 855),
('KI', 'KIRIBATI', 'Kiribati', 'KIR', 296, 686),
('KM', 'COMOROS', 'Comoros', 'COM', 174, 269),
('KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659, 1869),
('KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408, 850),
('KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410, 82),
('KW', 'KUWAIT', 'Kuwait', 'KWT', 414, 965),
('KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136, 1345),
('KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398, 7),
('LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418, 856),
('LB', 'LEBANON', 'Lebanon', 'LBN', 422, 961),
('LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662, 1758),
('LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438, 423),
('LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144, 94),
('LR', 'LIBERIA', 'Liberia', 'LBR', 430, 231),
('LS', 'LESOTHO', 'Lesotho', 'LSO', 426, 266),
('LT', 'LITHUANIA', 'Lithuania', 'LTU', 440, 370),
('LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442, 352),
('LV', 'LATVIA', 'Latvia', 'LVA', 428, 371),
('LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434, 218),
('MA', 'MOROCCO', 'Morocco', 'MAR', 504, 212),
('MC', 'MONACO', 'Monaco', 'MCO', 492, 377),
('MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498, 373),
('MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450, 261),
('MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584, 692),
('MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807, 389),
('ML', 'MALI', 'Mali', 'MLI', 466, 223),
('MM', 'MYANMAR', 'Myanmar', 'MMR', 104, 95),
('MN', 'MONGOLIA', 'Mongolia', 'MNG', 496, 976),
('MO', 'MACAO', 'Macao', 'MAC', 446, 853),
('MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580, 1670),
('MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474, 596),
('MR', 'MAURITANIA', 'Mauritania', 'MRT', 478, 222),
('MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500, 1664),
('MT', 'MALTA', 'Malta', 'MLT', 470, 356),
('MU', 'MAURITIUS', 'Mauritius', 'MUS', 480, 230),
('MV', 'MALDIVES', 'Maldives', 'MDV', 462, 960),
('MW', 'MALAWI', 'Malawi', 'MWI', 454, 265),
('MX', 'MEXICO', 'Mexico', 'MEX', 484, 52),
('MY', 'MALAYSIA', 'Malaysia', 'MYS', 458, 60),
('MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508, 258),
('NA', 'NAMIBIA', 'Namibia', 'NAM', 516, 264),
('NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540, 687),
('NE', 'NIGER', 'Niger', 'NER', 562, 227),
('NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574, 672),
('NG', 'NIGERIA', 'Nigeria', 'NGA', 566, 234),
('NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558, 505),
('NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528, 31),
('NO', 'NORWAY', 'Norway', 'NOR', 578, 47),
('NP', 'NEPAL', 'Nepal', 'NPL', 524, 977),
('NR', 'NAURU', 'Nauru', 'NRU', 520, 674),
('NU', 'NIUE', 'Niue', 'NIU', 570, 683),
('NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554, 64),
('OM', 'OMAN', 'Oman', 'OMN', 512, 968),
('PA', 'PANAMA', 'Panama', 'PAN', 591, 507),
('PE', 'PERU', 'Peru', 'PER', 604, 51),
('PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258, 689),
('PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598, 675),
('PH', 'PHILIPPINES', 'Philippines', 'PHL', 608, 63),
('PK', 'PAKISTAN', 'Pakistan', 'PAK', 586, 92),
('PL', 'POLAND', 'Poland', 'POL', 616, 48),
('PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666, 508),
('PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612, 0),
('PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630, 1787),
('PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL, 970),
('PT', 'PORTUGAL', 'Portugal', 'PRT', 620, 351),
('PW', 'PALAU', 'Palau', 'PLW', 585, 680),
('PY', 'PARAGUAY', 'Paraguay', 'PRY', 600, 595),
('QA', 'QATAR', 'Qatar', 'QAT', 634, 974),
('RE', 'REUNION', 'Reunion', 'REU', 638, 262),
('RO', 'ROMANIA', 'Romania', 'ROM', 642, 40),
('RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643, 70),
('RW', 'RWANDA', 'Rwanda', 'RWA', 646, 250),
('SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682, 966),
('SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90, 677),
('SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690, 248),
('SD', 'SUDAN', 'Sudan', 'SDN', 736, 249),
('SE', 'SWEDEN', 'Sweden', 'SWE', 752, 46),
('SG', 'SINGAPORE', 'Singapore', 'SGP', 702, 65),
('SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654, 290),
('SI', 'SLOVENIA', 'Slovenia', 'SVN', 705, 386),
('SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744, 47),
('SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703, 421),
('SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694, 232),
('SM', 'SAN MARINO', 'San Marino', 'SMR', 674, 378),
('SN', 'SENEGAL', 'Senegal', 'SEN', 686, 221),
('SO', 'SOMALIA', 'Somalia', 'SOM', 706, 252),
('SR', 'SURINAME', 'Suriname', 'SUR', 740, 597),
('ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678, 239),
('SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222, 503),
('SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760, 963),
('SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748, 268),
('TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796, 1649),
('TD', 'CHAD', 'Chad', 'TCD', 148, 235),
('TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL, 0),
('TG', 'TOGO', 'Togo', 'TGO', 768, 228),
('TH', 'THAILAND', 'Thailand', 'THA', 764, 66),
('TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762, 992),
('TK', 'TOKELAU', 'Tokelau', 'TKL', 772, 690),
('TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL, 670),
('TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795, 7370),
('TN', 'TUNISIA', 'Tunisia', 'TUN', 788, 216),
('TO', 'TONGA', 'Tonga', 'TON', 776, 676),
('TR', 'TURKEY', 'Turkey', 'TUR', 792, 90),
('TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780, 1868),
('TV', 'TUVALU', 'Tuvalu', 'TUV', 798, 688),
('TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158, 886),
('TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834, 255),
('UA', 'UKRAINE', 'Ukraine', 'UKR', 804, 380),
('UG', 'UGANDA', 'Uganda', 'UGA', 800, 256),
('UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL, 1),
('US', 'UNITED STATES', 'United States', 'USA', 840, 1),
('UY', 'URUGUAY', 'Uruguay', 'URY', 858, 598),
('UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860, 998),
('VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336, 39),
('VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670, 1784),
('VE', 'VENEZUELA', 'Venezuela', 'VEN', 862, 58),
('VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92, 1284),
('VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850, 1340),
('VN', 'VIET NAM', 'Viet Nam', 'VNM', 704, 84),
('VU', 'VANUATU', 'Vanuatu', 'VUT', 548, 678),
('WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876, 681),
('WS', 'SAMOA', 'Samoa', 'WSM', 882, 684),
('YE', 'YEMEN', 'Yemen', 'YEM', 887, 967),
('YT', 'MAYOTTE', 'Mayotte', NULL, NULL, 269),
('ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710, 27),
('ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894, 260),
('ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716, 263);
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
