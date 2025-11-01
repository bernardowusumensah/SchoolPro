-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2025 at 04:57 PM
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
-- Database: `schoolpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliverables`
--

CREATE TABLE `deliverables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('milestone','document','presentation','code','final_project') NOT NULL,
  `status` enum('pending','submitted','reviewed','approved','rejected') NOT NULL DEFAULT 'pending',
  `resubmission_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `due_date` date NOT NULL,
  `weight_percentage` int(11) NOT NULL DEFAULT 0,
  `requirements` text DEFAULT NULL,
  `file_types_allowed` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`file_types_allowed`)),
  `max_file_size_mb` int(11) NOT NULL DEFAULT 10,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deliverables`
--

INSERT INTO `deliverables` (`id`, `project_id`, `title`, `description`, `type`, `status`, `resubmission_allowed`, `due_date`, `weight_percentage`, `requirements`, `file_types_allowed`, `max_file_size_mb`, `created_at`, `updated_at`) VALUES
(2, 4, 'Final Project Deliverable - AI DATA CHALLENGE', 'Submit your completed project including all required components and documentation. Work on your project and log progress regularly. Submit final deliverable when due.', 'final_project', 'pending', 0, '2025-12-27', 100, 'Submit your completed project with all documentation, code, and supporting materials.', '[\"pdf\",\"doc\",\"docx\",\"zip\",\"rar\"]', 50, '2025-11-01 15:08:39', '2025-11-01 15:08:39');

-- --------------------------------------------------------

--
-- Table structure for table `deliverable_submissions`
--

CREATE TABLE `deliverable_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deliverable_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `file_paths` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`file_paths`)),
  `submission_note` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('submitted','late','reviewed','approved','rejected') NOT NULL DEFAULT 'submitted',
  `grade` decimal(5,2) DEFAULT NULL,
  `teacher_feedback` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `content` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `supervisor_feedback` text DEFAULT NULL,
  `feedback_date` timestamp NULL DEFAULT NULL,
  `supervisor_rating` enum('Excellent','Good','Satisfactory','Needs Improvement') DEFAULT NULL,
  `private_notes` text DEFAULT NULL,
  `requires_followup` tinyint(1) NOT NULL DEFAULT 0,
  `feedback_updated_at` timestamp NULL DEFAULT NULL,
  `student_acknowledged` tinyint(1) NOT NULL DEFAULT 0,
  `student_question` text DEFAULT NULL,
  `acknowledged_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `project_id`, `student_id`, `content`, `file_path`, `supervisor_feedback`, `feedback_date`, `supervisor_rating`, `private_notes`, `requires_followup`, `feedback_updated_at`, `student_acknowledged`, `student_question`, `acknowledged_at`, `created_at`, `updated_at`, `action`, `description`, `old_data`, `new_data`, `ip_address`, `user_agent`) VALUES
(21, 4, 2, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-01 15:01:17', '2025-11-01 15:01:17', 'created', 'Project proposal created', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36'),
(22, 4, 2, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-01 15:01:17', '2025-11-01 15:01:17', 'created', 'Project created', NULL, '{\"title\":\"AI DATA CHALLENGE\",\"description\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\",\"category\":\"Data Science\",\"objectives\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\",\"methodology\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\",\"expected_outcomes\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\",\"expected_start_date\":\"2025-11-02 00:00:00\",\"expected_completion_date\":\"2025-11-30 00:00:00\",\"estimated_hours\":\"40\",\"required_resources\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\",\"technology_stack\":\"[\\\"CSS3\\\",\\\"Node.js\\\",\\\"MongoDB\\\",\\\"Kotlin\\\",\\\"Azure\\\"]\",\"tools_and_software\":\"Vs code\",\"supporting_documents\":\"[{\\\"original_name\\\":\\\"BERNARD OWUSU - RESUME (1).pdf\\\",\\\"filename\\\":\\\"1761994877_BERNARD OWUSU - RESUME (1).pdf\\\",\\\"path\\\":\\\"projects\\\\\\/supporting_documents\\\\\\/1761994877_BERNARD OWUSU - RESUME (1).pdf\\\",\\\"size\\\":193123,\\\"type\\\":\\\"application\\\\\\/pdf\\\",\\\"uploaded_at\\\":\\\"2025-11-01T11:01:17.369532Z\\\"}]\",\"student_id\":2,\"supervisor_id\":\"3\",\"status\":\"Pending\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36'),
(23, 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-01 15:08:39', '2025-11-01 15:08:39', 'updated', 'Project updated: status, updated_at, approval_comments, approved_at, approved_by, reviewed_at, reviewed_by', '{\"status\":\"Pending\",\"updated_at\":\"2025-11-01T11:01:17.000000Z\",\"approval_comments\":null,\"approved_at\":null,\"approved_by\":null,\"reviewed_at\":null,\"reviewed_by\":null}', '{\"status\":\"Approved\",\"updated_at\":\"2025-11-01 11:08:39\",\"approval_comments\":\"approved\",\"approved_at\":\"2025-11-01 11:08:39\",\"approved_by\":3,\"reviewed_at\":\"2025-11-01 11:08:39\",\"reviewed_by\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36'),
(24, 4, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-01 15:08:39', '2025-11-01 15:08:39', 'updated', 'Project details updated: status, approval_comments, approved_at, approved_by, reviewed_at, reviewed_by', '{\"status\":\"Pending\",\"approval_comments\":null,\"approved_at\":null,\"approved_by\":null,\"reviewed_at\":null,\"reviewed_by\":null}', '{\"status\":\"Approved\",\"approval_comments\":\"approved\",\"approved_at\":\"2025-11-01 11:08:39\",\"approved_by\":3,\"reviewed_at\":\"2025-11-01 11:08:39\",\"reviewed_by\":3}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36'),
(25, 4, 2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2025-11-01 17:14:09', NULL, NULL, 0, NULL, 0, NULL, NULL, '2025-11-01 17:11:12', '2025-11-01 17:14:09', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `log_analytics_cache`
--

CREATE TABLE `log_analytics_cache` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `word_count` int(11) NOT NULL DEFAULT 0,
  `character_count` int(11) NOT NULL DEFAULT 0,
  `keyword_frequency` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`keyword_frequency`)),
  `sentiment_score` decimal(3,2) DEFAULT NULL,
  `topics_mentioned` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`topics_mentioned`)),
  `has_questions` tinyint(1) NOT NULL DEFAULT 0,
  `mentions_challenges` tinyint(1) NOT NULL DEFAULT 0,
  `shows_progress` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '2025_09_16_000001_create_users_table', 1),
(2, '2025_09_16_000002_create_projects_table', 1),
(3, '2025_09_16_000004_create_grades_table', 1),
(4, '2025_09_16_000006_create_logs_table', 1),
(5, '2025_09_16_212305_create_sessions_table', 1),
(6, '2025_09_19_002806_create_cache_table', 1),
(7, '2025_09_22_235454_add_profile_picture_to_users_table', 1),
(8, '2025_09_24_233406_add_submission_fields_to_projects_table', 1),
(9, '2025_09_30_223355_add_enhanced_fields_to_projects_table', 1),
(10, '2025_09_30_223543_update_project_status_enum', 1),
(11, '2025_10_01_013038_add_approval_workflow_fields_to_projects_table', 1),
(12, '2025_10_11_101754_add_supervisor_feedback_to_logs_table', 1),
(13, '2025_10_11_104610_add_student_acknowledgment_to_logs_table', 1),
(14, '2025_10_11_181908_add_audit_columns_to_logs_table', 1),
(15, '2025_10_11_182027_make_content_nullable_in_logs_table', 1),
(16, '2025_10_13_235223_create_analytics_tables', 1),
(17, '2025_10_16_213755_create_deliverables_table', 1),
(18, '2025_10_16_213859_create_deliverable_submissions_table', 1),
(19, '2025_10_17_014207_add_final_grade_to_projects_table', 1),
(20, '2025_10_18_120650_add_resubmission_allowed_to_deliverables_table', 1),
(21, '2025_10_18_220000_add_super_admin_to_users_table', 1),
(22, '2025_11_01_103409_add_resubmitted_at_to_projects_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `progress_milestones`
--

CREATE TABLE `progress_milestones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `milestone_type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `target_date` date NOT NULL,
  `completed_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','overdue') NOT NULL,
  `completion_evidence` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`completion_evidence`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `objectives` text DEFAULT NULL,
  `methodology` text DEFAULT NULL,
  `expected_outcomes` text DEFAULT NULL,
  `expected_start_date` date DEFAULT NULL,
  `expected_completion_date` date DEFAULT NULL,
  `estimated_hours` int(11) DEFAULT NULL,
  `required_resources` text DEFAULT NULL,
  `technology_stack` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`technology_stack`)),
  `tools_and_software` text DEFAULT NULL,
  `supporting_documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`supporting_documents`)),
  `is_draft` tinyint(1) NOT NULL DEFAULT 0,
  `draft_saved_at` timestamp NULL DEFAULT NULL,
  `supervisor_feedback` text DEFAULT NULL,
  `final_grade` decimal(5,2) DEFAULT NULL,
  `revision_notes` text DEFAULT NULL,
  `revision_count` int(11) NOT NULL DEFAULT 0,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `supervisor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `final_document_path` varchar(255) DEFAULT NULL,
  `presentation_path` varchar(255) DEFAULT NULL,
  `source_code_path` varchar(255) DEFAULT NULL,
  `submission_note` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approval_comments` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `resubmitted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `category`, `objectives`, `methodology`, `expected_outcomes`, `expected_start_date`, `expected_completion_date`, `estimated_hours`, `required_resources`, `technology_stack`, `tools_and_software`, `supporting_documents`, `is_draft`, `draft_saved_at`, `supervisor_feedback`, `final_grade`, `revision_notes`, `revision_count`, `student_id`, `supervisor_id`, `status`, `final_document_path`, `presentation_path`, `source_code_path`, `submission_note`, `submitted_at`, `created_at`, `updated_at`, `approval_comments`, `rejection_reason`, `approved_at`, `approved_by`, `reviewed_at`, `resubmitted_at`, `completed_at`, `reviewed_by`) VALUES
(4, 'AI DATA CHALLENGE', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Data Science', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2025-11-02', '2025-11-30', 40, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '[\"CSS3\",\"Node.js\",\"MongoDB\",\"Kotlin\",\"Azure\"]', 'Vs code', '[{\"original_name\":\"BERNARD OWUSU - RESUME (1).pdf\",\"filename\":\"1761994877_BERNARD OWUSU - RESUME (1).pdf\",\"path\":\"projects\\/supporting_documents\\/1761994877_BERNARD OWUSU - RESUME (1).pdf\",\"size\":193123,\"type\":\"application\\/pdf\",\"uploaded_at\":\"2025-11-01T11:01:17.369532Z\"}]', 0, NULL, NULL, NULL, NULL, 0, 2, 3, 'Approved', NULL, NULL, NULL, NULL, NULL, '2025-11-01 15:01:17', '2025-11-01 15:08:39', 'approved', NULL, '2025-11-01 15:08:39', 3, '2025-11-01 15:08:39', NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('jjsbDxwMAbnccSOBDl9AzdDe32oj7Qwz2aRdwQpO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMDBDN3N0TVdrNWFjZjVaSkUyaUhGZldPNlE2dzFIQWFRdjZCbmtJWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fX0=', 1762004289);

-- --------------------------------------------------------

--
-- Table structure for table `student_analytics`
--

CREATE TABLE `student_analytics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `week_starting` date NOT NULL,
  `logs_submitted` int(11) NOT NULL DEFAULT 0,
  `words_written` int(11) NOT NULL DEFAULT 0,
  `attachments_uploaded` int(11) NOT NULL DEFAULT 0,
  `feedback_received` int(11) NOT NULL DEFAULT 0,
  `feedback_acknowledged` int(11) NOT NULL DEFAULT 0,
  `avg_submission_time_hours` decimal(5,2) DEFAULT NULL,
  `activity_pattern` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`activity_pattern`)),
  `consistency_score` decimal(3,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_analytics`
--

CREATE TABLE `teacher_analytics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `week_starting` date NOT NULL,
  `students_supervised` int(11) NOT NULL DEFAULT 0,
  `logs_reviewed` int(11) NOT NULL DEFAULT 0,
  `feedback_provided` int(11) NOT NULL DEFAULT 0,
  `avg_response_time_hours` decimal(5,2) NOT NULL DEFAULT 0.00,
  `at_risk_students` int(11) NOT NULL DEFAULT 0,
  `subject_areas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`subject_areas`)),
  `workload_score` decimal(3,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','teacher','admin') NOT NULL,
  `super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `super_admin`, `status`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$12$jtKv2AbF1VQZqavTMJG6Ce.xnAq3o6AKSsy8/4rgSnOVXM6H6c20u', 'admin', 1, 'active', NULL, '2025-11-01 11:30:14', '2025-11-01 11:30:14'),
(2, 'Student Test', 'student@gmail.com', '$2y$12$3LK7/OKs/KtI0VpWspx.WOedzpxQnBFY8e24RD3c0vteZzpfEgMEW', 'student', 0, 'active', 'profile_pictures/XjH4p66AeHuOawYHyxu2PZINDbwOMlgAPpazD345.jpg', '2025-11-01 11:39:34', '2025-11-01 11:39:34'),
(3, 'Teacher Test', 'teacher@gmail.com', '$2y$12$kRnhIP3DKKmiDPvkfcP9ee1GUVs1qto2RPMnOPkhYZjAk6fPrBs5W', 'teacher', 0, 'active', 'profile_pictures/jDX5sI0sKkH3qhEw4DRBWfplcKw7OnDeVPRNCvJY.jpg', '2025-11-01 12:14:40', '2025-11-01 12:14:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `deliverables`
--
ALTER TABLE `deliverables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deliverables_project_id_foreign` (`project_id`);

--
-- Indexes for table `deliverable_submissions`
--
ALTER TABLE `deliverable_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deliverable_submissions_deliverable_id_foreign` (`deliverable_id`),
  ADD KEY `deliverable_submissions_student_id_foreign` (`student_id`),
  ADD KEY `deliverable_submissions_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grades_project_id_foreign` (`project_id`),
  ADD KEY `grades_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_project_id_foreign` (`project_id`),
  ADD KEY `logs_student_id_foreign` (`student_id`),
  ADD KEY `logs_supervisor_rating_index` (`supervisor_rating`),
  ADD KEY `logs_requires_followup_index` (`requires_followup`),
  ADD KEY `logs_feedback_updated_at_index` (`feedback_updated_at`),
  ADD KEY `logs_student_acknowledged_index` (`student_acknowledged`);

--
-- Indexes for table `log_analytics_cache`
--
ALTER TABLE `log_analytics_cache`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `log_analytics_cache_log_id_unique` (`log_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress_milestones`
--
ALTER TABLE `progress_milestones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progress_milestones_project_id_foreign` (`project_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_student_id_foreign` (`student_id`),
  ADD KEY `projects_supervisor_id_foreign` (`supervisor_id`),
  ADD KEY `projects_approved_by_foreign` (`approved_by`),
  ADD KEY `projects_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `student_analytics`
--
ALTER TABLE `student_analytics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_analytics_student_id_project_id_week_starting_unique` (`student_id`,`project_id`,`week_starting`),
  ADD KEY `student_analytics_project_id_foreign` (`project_id`),
  ADD KEY `student_analytics_week_starting_index` (`week_starting`);

--
-- Indexes for table `teacher_analytics`
--
ALTER TABLE `teacher_analytics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_analytics_teacher_id_week_starting_unique` (`teacher_id`,`week_starting`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deliverables`
--
ALTER TABLE `deliverables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `deliverable_submissions`
--
ALTER TABLE `deliverable_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `log_analytics_cache`
--
ALTER TABLE `log_analytics_cache`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `progress_milestones`
--
ALTER TABLE `progress_milestones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_analytics`
--
ALTER TABLE `student_analytics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_analytics`
--
ALTER TABLE `teacher_analytics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deliverables`
--
ALTER TABLE `deliverables`
  ADD CONSTRAINT `deliverables_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deliverable_submissions`
--
ALTER TABLE `deliverable_submissions`
  ADD CONSTRAINT `deliverable_submissions_deliverable_id_foreign` FOREIGN KEY (`deliverable_id`) REFERENCES `deliverables` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deliverable_submissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `deliverable_submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_analytics_cache`
--
ALTER TABLE `log_analytics_cache`
  ADD CONSTRAINT `log_analytics_cache_log_id_foreign` FOREIGN KEY (`log_id`) REFERENCES `logs` (`id`);

--
-- Constraints for table `progress_milestones`
--
ALTER TABLE `progress_milestones`
  ADD CONSTRAINT `progress_milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `student_analytics`
--
ALTER TABLE `student_analytics`
  ADD CONSTRAINT `student_analytics_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `student_analytics_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `teacher_analytics`
--
ALTER TABLE `teacher_analytics`
  ADD CONSTRAINT `teacher_analytics_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
