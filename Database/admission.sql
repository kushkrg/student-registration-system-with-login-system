-- Student Registration System with Login System
-- Database: `admission` | Core PHP + MySQL
-- Updated clean schema (2026): same database, tables and column names as the
-- original version, so existing code/data concepts stay compatible.
-- Changes vs the old dump:
--   * Primary keys + AUTO_INCREMENT are declared in CREATE TABLE (the old dump
--     relied on later ALTER statements and the tables could be created broken)
--   * Removed the unused `file` longblob column - it was NOT NULL with no
--     default, which made student INSERTs fail on strict-mode MySQL/MariaDB
--   * `u_state` widened 12 -> 50 chars (the old width silently truncated state
--     names, e.g. "Himachal Pra", "Uttar Prades" in the old data)
--   * `u_birthday` is now a real DATE column
--   * NO user/student data rows are included - the old dump shipped real
--     personal data (including Aadhar numbers). Never commit personal data.

CREATE DATABASE IF NOT EXISTS `admission` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `admission`;

-- --------------------------------------------------------
-- Table: users (login accounts)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: student_data (registered students)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `student_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_card` varchar(12) NOT NULL,
  `u_f_name` varchar(100) NOT NULL,
  `u_l_name` varchar(100) DEFAULT NULL,
  `u_father` varchar(100) DEFAULT NULL,
  `u_mother` varchar(100) DEFAULT NULL,
  `u_aadhar` varchar(12) DEFAULT NULL,
  `u_birthday` date DEFAULT NULL,
  `u_gender` varchar(10) DEFAULT NULL,
  `u_email` varchar(150) NOT NULL,
  `u_phone` varchar(15) NOT NULL,
  `u_state` varchar(50) DEFAULT NULL,
  `u_dist` varchar(100) DEFAULT NULL,
  `u_village` varchar(100) DEFAULT NULL,
  `u_police` varchar(100) DEFAULT NULL,
  `u_pincode` varchar(6) DEFAULT NULL,
  `u_family` varchar(50) DEFAULT NULL,
  `staff_id` varchar(12) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL,
  `uploaded` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `u_card_idx` (`u_card`),
  KEY `u_email_idx` (`u_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create your first account from the website instead:
-- open register.php in the browser after importing this file.
-- (Passwords are stored with password_hash() - never insert plain passwords.)
