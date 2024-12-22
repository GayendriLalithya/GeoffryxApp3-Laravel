-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2024 at 08:39 PM
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
-- Database: `geoffryx_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AcceptVerification` (IN `verify_id_param` INT)   BEGIN
    DECLARE user_id_param INT;
    DECLARE professional_type_param VARCHAR(255);

    -- Fetch user_id and professional_type from the verification request
    SELECT user_id, professional_type
    INTO user_id_param, professional_type_param
    FROM verify_requests
    WHERE verify_id = verify_id_param;

    -- Update the status of the verification request
    UPDATE verify_requests
    SET status = 'verified'
    WHERE verify_id = verify_id_param;

    -- Insert a notification for the user
    INSERT INTO notifications (
        user_id, 
        title, 
        message, 
        status, 
        created_at, 
        updated_at
    )
    VALUES (
        user_id_param, 
        'Account Verified', 
        'Your professional account request has been approved by Geoffryx. Welcome to Geoffryx professionals!', 
        'unread', 
        NOW(), 
        NOW()
    );

    -- Update the user's type in the users table
    UPDATE users
    SET user_type = 'professional'
    WHERE user_id = user_id_param;

    -- Insert a new record into the professionals table
    INSERT INTO professionals (
        user_id, 
        type, 
        availability, 
        work_location, 
        payment_min, 
        payment_max, 
        preferred_project_size, 
        created_at, 
        updated_at
    )
    VALUES (
        user_id_param, 
        professional_type_param, 
        'Available', 
        'anywhere', 
        20000000, 
        100000000, 
        'all', 
        NOW(), 
        NOW()
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AcceptWorkAndAddToTeam` (IN `workId` INT, IN `userId` INT)   BEGIN
    DECLARE teamId INT;

    -- Update status in pending_professional table
    UPDATE pending_professional
    SET professional_status = 'accepted'
    WHERE work_id = workId
    AND professional_id = (SELECT professional_id FROM professionals WHERE user_id = userId);

    -- Get the team ID for the work ID
    SELECT team_id INTO teamId
    FROM team
    WHERE work_id = workId;

    -- Add record to team_members table
    INSERT INTO team_members (user_id, team_id, status, created_at, updated_at)
    VALUES (userId, teamId, 'not stated', NOW(), NOW());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_project_with_professionals` (IN `p_user_id` BIGINT, IN `p_name` VARCHAR(255), IN `p_location` VARCHAR(255), IN `p_start_date` DATE, IN `p_end_date` DATE, IN `p_budget` DECIMAL(10,2), IN `p_description` TEXT, IN `p_professionals` JSON)   BEGIN
    DECLARE project_id BIGINT;
    DECLARE professional_user_id BIGINT;
    DECLARE invalid_professional BOOLEAN DEFAULT FALSE;
    DECLARE idx INT DEFAULT 0;
    DECLARE total_profs INT;

    -- Get the total number of professionals in the JSON array
    SET total_profs = JSON_LENGTH(p_professionals);

    -- Check each professional_id in the JSON array
    WHILE_LOOP: WHILE idx < total_profs DO
        SET professional_user_id = (
            SELECT user_id
            FROM professionals
            WHERE professional_id = JSON_UNQUOTE(JSON_EXTRACT(p_professionals, CONCAT('$[', idx, ']')))
            LIMIT 1
        );

        -- If the professional's user_id matches the project creator's user_id
        IF professional_user_id = p_user_id THEN
            SET invalid_professional = TRUE;
            LEAVE WHILE_LOOP; -- Exit the labeled WHILE loop
        END IF;

        SET idx = idx + 1;
    END WHILE WHILE_LOOP;

    -- If any professional is invalid, throw an error and do not insert
    IF invalid_professional THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot select yourself as a professional for the project.';
    END IF;

    -- Insert project details into the 'work' table
    INSERT INTO work (user_id, name, location, start_date, end_date, budget, description, created_at, updated_at)
    VALUES (p_user_id, p_name, p_location, p_start_date, p_end_date, p_budget, p_description, NOW(), NOW());

    SET project_id = LAST_INSERT_ID();

    -- Insert professionals into 'pending_professional' table
    INSERT INTO pending_professional (user_id, professional_id, work_id, professional_status, created_at, updated_at)
    SELECT p_user_id, JSON_UNQUOTE(JSON_EXTRACT(p_professionals, CONCAT('$[', idx2.i, ']'))), project_id, 'pending', NOW(), NOW()
    FROM (
        SELECT 0 AS i UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
    ) idx2
    WHERE JSON_UNQUOTE(JSON_EXTRACT(p_professionals, CONCAT('$[', idx2.i, ']'))) IS NOT NULL;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetTeamMembersByTeamId` (IN `teamId` INT)   BEGIN
    SELECT 
        tm.team_member_id,
        tm.user_id,
        u.name AS member_name,
        tm.status AS member_status,
        p.type AS professional_type
    FROM 
        team_members tm
    JOIN 
        users u ON tm.user_id = u.user_id
    LEFT JOIN 
        professionals p ON tm.user_id = p.user_id
    WHERE 
        tm.team_id = teamId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetTeamMembersByWork` (IN `workId` INT, IN `userId` INT)   BEGIN
    SELECT 
        tm.user_id,
        tm.status AS team_member_status,
        u.name AS team_member_name,
        p.type AS professional_type,
        CASE WHEN tm.user_id = userId THEN TRUE ELSE FALSE END AS is_editable
    FROM team_members tm
    JOIN users u ON tm.user_id = u.user_id
    LEFT JOIN professionals p ON u.user_id = p.user_id
    WHERE tm.team_id = (SELECT team_id FROM team WHERE work_id = workId);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `LoadAllProfessionals` ()   BEGIN
    SELECT p.professional_id, p.user_id, u.name, p.type, p.work_location, 
           pp.profile_pic AS profile_picture_url, r.average_rating
    FROM professionals p
    JOIN users u ON p.user_id = u.user_id
    LEFT JOIN profile_picture pp ON u.user_id = pp.user_id
    LEFT JOIN (
        SELECT professional_id, AVG(rate) AS average_rating
        FROM rating
        GROUP BY professional_id
    ) r ON p.professional_id = r.professional_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `LoadProfessionalDetails` (IN `prof_id` INT)   BEGIN
    -- Fetch professional details including basic info and profile picture
    SELECT * FROM professional_details WHERE professional_id = prof_id;

    -- Fetch average ratings for the professional
    SELECT * FROM professional_ratings WHERE professional_id = prof_id;

    -- Fetch work history for the professional
    SELECT * FROM professional_work_history WHERE professional_id = prof_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProcessPayment` (IN `p_work_id` INT, IN `p_user_id` INT, IN `p_amount` DECIMAL(10,2))   BEGIN
    -- Create a payment record
    INSERT INTO payment (work_id, user_id, amount, date, time, created_at, updated_at)
    VALUES (p_work_id, p_user_id, p_amount, CURDATE(), CURTIME(), NOW(), NOW());

    -- Send notification to the customer
    INSERT INTO notifications (user_id, title, message, status, created_at, updated_at)
    VALUES (p_user_id, 'Payment Successful', 'Your payment has been successfully processed.', 'unread', NOW(), NOW());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RejectWork` (IN `workId` INT, IN `professionalId` INT, IN `rejectionReason` TEXT)   BEGIN
    DECLARE userId INT;
    DECLARE professionalName VARCHAR(255);

    -- Update status in pending_professional table
    UPDATE pending_professional
    SET professional_status = 'rejected'
    WHERE work_id = workId
    AND professional_id = professionalId;

    -- Get the user_id from the work table
    SELECT user_id INTO userId
    FROM work
    WHERE work_id = workId;

    -- Get the professional name
    SELECT name INTO professionalName
    FROM users
    WHERE user_id = (SELECT user_id FROM professionals WHERE professional_id = professionalId);

    -- Insert notification into the notifications table
    INSERT INTO notifications (user_id, title, message, status, created_at, updated_at)
    VALUES (
        userId,
        'Project Rejection',
        CONCAT('Your project has been rejected by ', professionalName, '. The reason is: ', rejectionReason),
        'unread',
        NOW(),
        NOW()
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `submit_ratings` (IN `p_work_id` BIGINT, IN `p_user_id` BIGINT, IN `p_ratings` JSON)   BEGIN
    DECLARE idx INT DEFAULT 0;
    DECLARE total_ratings INT;
    DECLARE professional_id BIGINT;
    DECLARE rate ENUM('1', '2', '3', '4', '5');
    DECLARE comment TEXT;

    -- Get the total number of ratings from the JSON array
    SET total_ratings = JSON_LENGTH(p_ratings);

    -- Iterate through each rating in the JSON array
    WHILE idx < total_ratings DO
        -- Extract the professional_id, rate, and comment from the JSON array
        SET professional_id = JSON_UNQUOTE(JSON_EXTRACT(p_ratings, CONCAT('$[', idx, '].professional_id')));
        SET rate = JSON_UNQUOTE(JSON_EXTRACT(p_ratings, CONCAT('$[', idx, '].rate')));
        SET comment = JSON_UNQUOTE(JSON_EXTRACT(p_ratings, CONCAT('$[', idx, '].comment')));

        -- Insert the rating into the 'rating' table
        INSERT INTO rating (professional_id, work_id, user_id, rate, comment, created_at, updated_at)
        VALUES (professional_id, p_work_id, p_user_id, rate, comment, NOW(), NOW());

        -- Increment the index to move to the next rating
        SET idx = idx + 1;
    END WHILE;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `all_professional_details`
-- (See below for the actual view)
--
CREATE TABLE `all_professional_details` (
`professional_id` bigint(20) unsigned
,`user_id` bigint(20) unsigned
,`user_name` varchar(255)
,`type` varchar(255)
,`availability` enum('Available','Not Available')
,`work_location` varchar(255)
,`payment_min` decimal(13,2)
,`payment_max` decimal(13,2)
,`preferred_project_size` enum('small','medium','large','all')
,`profile_picture_url` varchar(255)
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `certificate`
--

CREATE TABLE `certificate` (
  `certificate_id` bigint(20) UNSIGNED NOT NULL,
  `certificate_name` varchar(255) NOT NULL,
  `certificate` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verify_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificate`
--

INSERT INTO `certificate` (`certificate_id`, `certificate_name`, `certificate`, `created_at`, `updated_at`, `verify_id`) VALUES
(1, 'Charted Architect Certificate 1', 'images/certificate/1734585516_Frame 8.png', '2024-12-18 23:48:36', '2024-12-18 23:48:36', 1),
(2, 'Charted Architect Certificate 2', 'images/certificate/1734585516_Frame 9.png', '2024-12-18 23:48:36', '2024-12-18 23:48:36', 1),
(3, 'Structural Engineer Certificate 1', 'images/certificate/1734585728_example_certificate.jpg', '2024-12-18 23:52:08', '2024-12-18 23:52:08', 2),
(4, 'Structural Engineer Certificate 1', 'images/certificate/1734585905_example_certificate.jpg', '2024-12-18 23:55:05', '2024-12-18 23:55:05', 3),
(5, 'Charted Architect Certificate 1', 'images/certificate/1734806473_example_certificate.jpg', '2024-12-21 13:11:13', '2024-12-21 13:11:13', 4);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `document` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `installment_plan`
--

CREATE TABLE `installment_plan` (
  `installment_plan_id` bigint(20) UNSIGNED NOT NULL,
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `material_cost` decimal(10,2) NOT NULL,
  `labor_charge` decimal(10,2) NOT NULL,
  `service_charge` decimal(10,2) NOT NULL,
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meetups`
--

CREATE TABLE `meetups` (
  `meetup_id` bigint(20) UNSIGNED NOT NULL,
  `schedule_date` datetime NOT NULL,
  `schedule_time` time NOT NULL,
  `url` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_tasks`
--

CREATE TABLE `member_tasks` (
  `member_task_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('not started','in progress','done') NOT NULL,
  `team_member_id` bigint(20) UNSIGNED NOT NULL,
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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_11_07_082326_create_professional_table', 1),
(6, '2024_11_07_082428_create_profile_picture_table', 1),
(7, '2024_11_07_082520_create_admin_table', 1),
(8, '2024_11_07_082600_create_customer_table', 1),
(9, '2024_11_07_082643_create_verify_table', 1),
(10, '2024_11_16_153928_create_reference_table', 1),
(11, '2024_11_16_154045_create_work_table', 1),
(12, '2024_11_16_154128_create_installment_plan_table', 1),
(13, '2024_11_16_154206_create_team_table', 1),
(14, '2024_11_16_154250_create_invoice_table', 1),
(15, '2024_11_16_154335_create_document_table', 1),
(16, '2024_11_16_154422_create_payment_table', 1),
(17, '2024_11_16_154505_create_meetups_table', 1),
(18, '2024_11_16_154558_create_rating_table', 1),
(19, '2024_11_16_154637_create_work_history_table', 1),
(20, '2024_11_16_154724_create_referrals_table', 1),
(21, '2024_11_16_154814_create_team_members_table', 1),
(22, '2024_11_16_154852_create_member_tasks_table', 1),
(23, '2024_11_29_152711_add_deleted_column_to_users_table', 1),
(24, '2024_11_30_090724_create_certificate_table', 1),
(25, '2024_11_30_095432_add_professional_type_to_verify_table', 1),
(26, '2024_11_30_162812_add_status_to_verify_table', 1),
(27, '2024_12_01_054332_modify_certificate_table_add_verify_id', 1),
(28, '2024_12_01_152540_create_notifications_table', 1),
(29, '2024_12_08_145421_update_professionals_table', 1),
(30, '2024_12_15_143949_update_payment_columns_in_professionals_table', 1),
(31, '2024_12_15_144957_change_type_column_in_professionals_table', 1),
(32, '2024_12_16_050515_modify_work_table_add_start_end_dates', 1),
(33, '2024_12_16_050921_add_work_user_to_work_history', 1),
(34, '2024_12_16_083720_create_pending_professional_table', 1),
(35, '2024_12_17_053835_create_work_user_view', 1),
(36, '2024_12_17_070254_update_professional_status_in_pending_professional_table', 1),
(37, '2024_12_18_141816_add_status_to_work_table', 1),
(40, '2024_12_20_103033_update_status_enum_in_work_table', 2),
(41, '2024_12_20_160726_update_work_history_table_remove_columns', 3),
(42, '2024_12_20_160754_update_payment_table_add_columns', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('read','unread') NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `title`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Account Verified', 'Your professional account request has been approved by Geoffryx. Welcome to Geoffryx professionals!', 'unread', '2024-12-19 05:22:51', '2024-12-19 05:22:51'),
(2, 3, 'Verification Rejected', 'Sorry :( Your professional account request has been rejected by Geoffry. Reason: ', 'unread', '2024-12-18 23:53:22', '2024-12-18 23:53:22'),
(3, 3, 'Account Verified', 'Your professional account request has been approved by Geoffryx. Welcome to Geoffryx professionals!', 'unread', '2024-12-19 10:24:58', '2024-12-19 10:24:58'),
(14, 4, 'Payment Successful', 'Your payment has been successfully processed.', 'unread', '2024-12-20 19:50:38', '2024-12-20 19:50:38');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `work_id`, `user_id`, `amount`, `date`, `time`, `created_at`, `updated_at`) VALUES
(11, 1, 4, 3500000.00, '2024-12-21', '01:20:38', '2024-12-20 19:50:38', '2024-12-20 19:50:38');

-- --------------------------------------------------------

--
-- Table structure for table `pending_professional`
--

CREATE TABLE `pending_professional` (
  `pending_prof_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `professional_id` bigint(20) UNSIGNED NOT NULL,
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `professional_status` enum('pending','accepted','rejected','removed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pending_professional`
--

INSERT INTO `pending_professional` (`pending_prof_id`, `user_id`, `professional_id`, `work_id`, `professional_status`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 1, 'accepted', '2024-12-19 05:32:02', '2024-12-19 05:32:02'),
(2, 2, 2, 2, 'accepted', '2024-12-19 10:26:16', '2024-12-19 10:26:16'),
(3, 3, 1, 3, 'accepted', '2024-12-19 16:14:09', '2024-12-19 16:14:09'),
(4, 4, 2, 4, 'accepted', '2024-12-19 17:48:43', '2024-12-19 17:48:43'),
(5, 4, 1, 4, 'accepted', '2024-12-19 17:48:43', '2024-12-19 17:48:43'),
(6, 2, 2, 5, 'pending', '2024-12-20 04:03:50', '2024-12-20 04:03:50'),
(7, 4, 2, 6, 'accepted', '2024-12-20 04:59:56', '2024-12-20 04:59:56'),
(8, 4, 1, 6, 'pending', '2024-12-20 04:59:56', '2024-12-20 04:59:56');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `professionals`
--

CREATE TABLE `professionals` (
  `professional_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `availability` enum('Available','Not Available') NOT NULL,
  `work_location` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_min` decimal(13,2) DEFAULT NULL,
  `payment_max` decimal(13,2) DEFAULT NULL,
  `preferred_project_size` enum('small','medium','large','all') NOT NULL DEFAULT 'all'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `professionals`
--

INSERT INTO `professionals` (`professional_id`, `user_id`, `type`, `availability`, `work_location`, `created_at`, `updated_at`, `payment_min`, `payment_max`, `preferred_project_size`) VALUES
(1, 2, 'Charted Architect', 'Available', 'Colombo', '2024-12-19 05:22:51', '2024-12-19 05:22:51', 20000000.00, 100000000.00, 'all'),
(2, 3, 'Structural Engineer', 'Available', 'anywhere', '2024-12-19 10:24:58', '2024-12-19 10:24:58', 20000000.00, 100000000.00, 'all');

-- --------------------------------------------------------

--
-- Stand-in structure for view `professional_details`
-- (See below for the actual view)
--
CREATE TABLE `professional_details` (
`professional_id` bigint(20) unsigned
,`user_id` bigint(20) unsigned
,`name` varchar(255)
,`work_location` varchar(255)
,`payment_min` decimal(13,2)
,`payment_max` decimal(13,2)
,`profile_picture_url` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `professional_ratings`
-- (See below for the actual view)
--
CREATE TABLE `professional_ratings` (
`professional_id` bigint(20) unsigned
,`average_rating` decimal(12,1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `professional_work_history`
-- (See below for the actual view)
--
CREATE TABLE `professional_work_history` (
);

-- --------------------------------------------------------

--
-- Table structure for table `profile_picture`
--

CREATE TABLE `profile_picture` (
  `profile_picture_id` bigint(20) UNSIGNED NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profile_picture`
--

INSERT INTO `profile_picture` (`profile_picture_id`, `profile_pic`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '1734585365_sam winchester.jpg', 1, '2024-12-18 23:45:45', '2024-12-18 23:46:06'),
(2, '1734585425_image 10.png', 2, '2024-12-18 23:46:50', '2024-12-18 23:47:05'),
(3, '1734585600_Emma Watson.png', 3, '2024-12-18 23:49:35', '2024-12-18 23:50:00'),
(4, '1734585952_Tiffany Andrews.jpg', 4, '2024-12-18 23:55:39', '2024-12-18 23:55:52'),
(5, '1734807803_mary.jpg', 5, '2024-12-21 13:10:21', '2024-12-21 13:33:23');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` bigint(20) UNSIGNED NOT NULL,
  `professional_id` bigint(20) UNSIGNED NOT NULL,
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rate` enum('1','2','3','4','5') NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rating_id`, `professional_id`, `work_id`, `user_id`, `rate`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, '5', 'Excellent work!', '2024-12-21 13:30:27', '2024-12-21 13:30:27'),
(2, 2, 1, 4, '4', 'Good job.', '2024-12-21 13:30:27', '2024-12-21 13:30:27'),
(3, 1, 1, 4, '5', 'Excellent work!', '2024-12-21 13:55:57', '2024-12-21 13:55:57'),
(4, 2, 1, 4, '4', 'Good job.', '2024-12-21 13:55:57', '2024-12-21 13:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `reference`
--

CREATE TABLE `reference` (
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `professional_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `referral_id` bigint(20) UNSIGNED NOT NULL,
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `professional_id` bigint(20) UNSIGNED NOT NULL,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('accepted','rejected','pending') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`team_id`, `work_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-12-19 05:32:02', '2024-12-19 05:32:02'),
(2, 2, '2024-12-19 10:26:16', '2024-12-19 10:26:16'),
(3, 3, '2024-12-19 16:14:09', '2024-12-19 16:14:09'),
(4, 4, '2024-12-19 17:48:42', '2024-12-19 17:48:42'),
(5, 5, '2024-12-20 04:03:50', '2024-12-20 04:03:50'),
(6, 6, '2024-12-20 04:59:56', '2024-12-20 04:59:56');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `team_member_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('not started','in progress','halfway through','almost done','completed') NOT NULL DEFAULT 'not started',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`team_member_id`, `user_id`, `team_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'completed', '2024-12-19 05:35:37', '2024-12-20 10:03:42'),
(2, 3, 2, 'not started', '2024-12-19 15:55:12', '2024-12-19 15:55:12'),
(3, 2, 4, 'in progress', '2024-12-19 17:49:44', '2024-12-20 06:25:58'),
(4, 3, 4, 'completed', '2024-12-19 18:10:11', '2024-12-20 05:12:24'),
(5, 2, 3, 'almost done', '2024-12-20 04:02:55', '2024-12-20 06:20:38'),
(6, 3, 6, 'not started', '2024-12-20 05:00:28', '2024-12-20 05:00:28');

--
-- Triggers `team_members`
--
DELIMITER $$
CREATE TRIGGER `after_team_members_update` AFTER INSERT ON `team_members` FOR EACH ROW BEGIN
    DECLARE total_members INT;
    DECLARE completed_members INT;
    DECLARE not_started_members INT;
    DECLARE work_id INT;

    -- Get the work_id for the team
    SELECT work_id INTO work_id
    FROM team
    WHERE team_id = NEW.team_id;

    -- Calculate the total number of team members
    SELECT COUNT(*) INTO total_members
    FROM team_members
    WHERE team_id = NEW.team_id;

    -- Calculate the number of team members with status 'completed'
    SELECT COUNT(*) INTO completed_members
    FROM team_members
    WHERE team_id = NEW.team_id AND status = 'completed';

    -- Calculate the number of team members with status 'not stated'
    SELECT COUNT(*) INTO not_started_members
    FROM team_members
    WHERE team_id = NEW.team_id AND status = 'not stated';

    -- Update the work status based on the counts
    IF total_members = completed_members THEN
        UPDATE work
        SET status = 'completed'
        WHERE work_id = work_id;
    ELSEIF total_members = not_started_members THEN
        UPDATE work
        SET status = 'not started'
        WHERE work_id = work_id;
    ELSE
        UPDATE work
        SET status = 'in progress'
        WHERE work_id = work_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('professional','customer','admin') NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `contact_no`, `address`, `email`, `password`, `user_type`, `remember_token`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 'Sam Winchester', '1230985674', 'Pole Street, Chicago', 'sam@gmail.com', '$2y$12$h2qNaurq5HCrfCi6S7O2iOILMHfUtC4prYDKqrBo5p0VbeMle/7gG', 'admin', NULL, '2024-12-18 23:45:45', '2024-12-18 23:45:45', 0),
(2, 'Ann Fox', '02341567112', '111 Builder’s Avenue, Colombo 11', 'annfox@example.com', '$2y$12$UzYVcBLNlqb9kXV8h0Bz6unxLoVb8eARh4sTf7rr1EMGbs3995.LW', 'professional', NULL, '2024-12-18 23:46:50', '2024-12-18 23:46:50', 0),
(3, 'Example', '0987654321', '123 Dance Street', 'exampl@gmail.com', '$2y$12$egtxnuAt2OB07MAGauezGuNiA/FCUgZhiwc7TWMwakn6RNmbbbmWa', 'professional', NULL, '2024-12-18 23:49:35', '2024-12-18 23:49:35', 0),
(4, 'Tiffany James', '1234567890', '123 Hale Street, Colorado', 'tiffany@example.com', '$2y$12$9asAJ9BWIg2nY7QnvYdqFulDDXGyJVeWSxaWBvr3Ub6yZGEbzx7VC', 'customer', NULL, '2024-12-18 23:55:39', '2024-12-18 23:55:39', 0),
(5, 'Mary', '0987654321', 'Campbell', 'hemice4055@exoular.com', '$2y$12$wAem56.Crr3XaEITJX/9fOJVC5ffDNLiuiSS2wyY/o9JzdM2dpgyO', 'customer', NULL, '2024-12-21 13:10:21', '2024-12-21 13:10:21', 0);

-- --------------------------------------------------------

--
-- Table structure for table `verify`
--

CREATE TABLE `verify` (
  `verify_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nic_no` varchar(255) NOT NULL,
  `nic_front` varchar(255) NOT NULL,
  `nic_back` varchar(255) NOT NULL,
  `professional_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `verify`
--

INSERT INTO `verify` (`verify_id`, `user_id`, `nic_no`, `nic_front`, `nic_back`, `professional_type`, `created_at`, `updated_at`, `status`) VALUES
(1, 2, '092387167285', 'images/nic/1734585516_image 4.png', 'images/nic/1734585516_image 5.png', 'Charted Architect', '2024-12-18 23:48:36', '2024-12-18 23:48:36', 'verified'),
(2, 3, '092387167284', 'images/nic/1734585728_image 4.png', 'images/nic/1734585728_image 5.png', 'Structural Engineer', '2024-12-18 23:52:08', '2024-12-18 23:52:08', 'rejected'),
(3, 3, '092387167284', 'images/nic/1734585905_image 4.png', 'images/nic/1734585905_image 5.png', 'Structural Engineer', '2024-12-18 23:55:05', '2024-12-18 23:55:05', 'verified'),
(4, 5, '092387167283', 'images/nic/1734806472_image 4.png', 'images/nic/1734806473_image 5.png', 'Charted Architect', '2024-12-21 13:11:13', '2024-12-21 13:11:13', 'pending');

-- --------------------------------------------------------

--
-- Stand-in structure for view `verify_requests`
-- (See below for the actual view)
--
CREATE TABLE `verify_requests` (
`verify_id` bigint(20) unsigned
,`user_id` bigint(20) unsigned
,`nic_no` varchar(255)
,`nic_front` varchar(255)
,`nic_back` varchar(255)
,`professional_type` varchar(255)
,`status` varchar(255)
,`user_name` varchar(255)
,`contact_no` varchar(15)
,`address` text
,`email` varchar(255)
,`profile_pic` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_teams_with_work`
-- (See below for the actual view)
--
CREATE TABLE `view_teams_with_work` (
`team_id` bigint(20) unsigned
,`work_id` bigint(20) unsigned
,`work_name` varchar(255)
,`work_description` text
,`work_status` enum('not started','in progress','completed')
,`work_owner_id` bigint(20) unsigned
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_user_projects`
-- (See below for the actual view)
--
CREATE TABLE `view_user_projects` (
`work_id` bigint(20) unsigned
,`description` text
,`name` varchar(255)
,`user_id` bigint(20) unsigned
,`location` varchar(255)
,`budget` decimal(10,2)
,`start_date` date
,`end_date` date
,`status` enum('not started','in progress','completed')
,`created_at` timestamp
,`updated_at` timestamp
,`client_name` varchar(255)
,`client_contact` varchar(15)
);

-- --------------------------------------------------------

--
-- Table structure for table `work`
--

CREATE TABLE `work` (
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `location` varchar(255) NOT NULL,
  `budget` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('not started','in progress','completed') NOT NULL DEFAULT 'not started',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work`
--

INSERT INTO `work` (`work_id`, `description`, `name`, `user_id`, `location`, `budget`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Need a house plan with following\r\n2 bedrooms\r\n2 bathrooms\r\n1 kitchen', 'Sunset Villa', 4, 'Colombo', 3500000.00, '2025-01-22', '2025-06-30', 'completed', '2024-12-19 05:32:02', '2024-12-20 10:03:42'),
(2, 'The Office Complex needs a structured plan \r\nstrong house plan', 'Greenwood Office Complex', 2, 'Kandy', 5000000.00, '2024-12-01', '2025-01-10', 'not started', '2024-12-19 10:26:16', '2024-12-19 10:26:16'),
(3, 'Regression testing', 'Example Project', 3, 'Gampaha', 5000000.00, '2024-12-01', '2024-12-31', 'in progress', '2024-12-19 16:14:09', '2024-12-20 06:20:38'),
(4, 'Testing multiple profesisonals', 'Seaside Resort Development', 4, 'Kandy', 5000000.00, '2024-12-01', '2025-04-02', 'in progress', '2024-12-19 17:48:42', '2024-12-20 06:08:57'),
(5, 'Test Project', 'Test Project', 2, 'Gampaha', 5000000.00, '2024-12-01', '2025-01-10', 'not started', '2024-12-20 04:03:50', '2024-12-20 04:03:50'),
(6, 'Hii', 'Test Project 2', 4, 'Gampaha', 3500000.00, '2024-12-05', '2024-12-17', 'not started', '2024-12-20 04:59:56', '2024-12-20 04:59:56');

--
-- Triggers `work`
--
DELIMITER $$
CREATE TRIGGER `after_work_insert` AFTER INSERT ON `work` FOR EACH ROW BEGIN
    -- Insert a new team record whenever a work record is created
    INSERT INTO team (work_id, created_at, updated_at) 
    VALUES (NEW.work_id, NOW(), NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `work_history`
--

CREATE TABLE `work_history` (
  `work_history_id` bigint(20) UNSIGNED NOT NULL,
  `work_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_history`
--

INSERT INTO `work_history` (`work_history_id`, `work_id`, `user_id`, `created_at`, `updated_at`) VALUES
(6, 1, 4, '2024-12-20 11:53:54', '2024-12-20 11:53:54');

-- --------------------------------------------------------

--
-- Structure for view `all_professional_details`
--
DROP TABLE IF EXISTS `all_professional_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `all_professional_details`  AS SELECT `p`.`professional_id` AS `professional_id`, `p`.`user_id` AS `user_id`, `u`.`name` AS `user_name`, `p`.`type` AS `type`, `p`.`availability` AS `availability`, `p`.`work_location` AS `work_location`, `p`.`payment_min` AS `payment_min`, `p`.`payment_max` AS `payment_max`, `p`.`preferred_project_size` AS `preferred_project_size`, `pp`.`profile_pic` AS `profile_picture_url`, `p`.`created_at` AS `created_at`, `p`.`updated_at` AS `updated_at` FROM ((`professionals` `p` join `users` `u` on(`p`.`user_id` = `u`.`user_id`)) left join `profile_picture` `pp` on(`p`.`user_id` = `pp`.`user_id`)) WHERE `u`.`deleted` = 0 ;

-- --------------------------------------------------------

--
-- Structure for view `professional_details`
--
DROP TABLE IF EXISTS `professional_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `professional_details`  AS SELECT `p`.`professional_id` AS `professional_id`, `p`.`user_id` AS `user_id`, `u`.`name` AS `name`, `p`.`work_location` AS `work_location`, `p`.`payment_min` AS `payment_min`, `p`.`payment_max` AS `payment_max`, `pp`.`profile_pic` AS `profile_picture_url` FROM ((`professionals` `p` join `users` `u` on(`p`.`user_id` = `u`.`user_id`)) left join `profile_picture` `pp` on(`u`.`user_id` = `pp`.`user_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `professional_ratings`
--
DROP TABLE IF EXISTS `professional_ratings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `professional_ratings`  AS SELECT `rating`.`professional_id` AS `professional_id`, round(avg(cast(`rating`.`rate` as decimal(10,0))),1) AS `average_rating` FROM `rating` GROUP BY `rating`.`professional_id` ;

-- --------------------------------------------------------

--
-- Structure for view `professional_work_history`
--
DROP TABLE IF EXISTS `professional_work_history`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `professional_work_history`  AS SELECT `wh`.`professional_id` AS `professional_id`, `w`.`name` AS `project_name`, `r`.`rate` AS `rating`, `w`.`description` AS `description`, `w`.`location` AS `project_location` FROM ((`work_history` `wh` join `work` `w` on(`wh`.`work_id` = `w`.`work_id`)) left join `rating` `r` on(`wh`.`rating_id` = `r`.`rating_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `verify_requests`
--
DROP TABLE IF EXISTS `verify_requests`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `verify_requests`  AS SELECT `v`.`verify_id` AS `verify_id`, `v`.`user_id` AS `user_id`, `v`.`nic_no` AS `nic_no`, `v`.`nic_front` AS `nic_front`, `v`.`nic_back` AS `nic_back`, `v`.`professional_type` AS `professional_type`, `v`.`status` AS `status`, `u`.`name` AS `user_name`, `u`.`contact_no` AS `contact_no`, `u`.`address` AS `address`, `u`.`email` AS `email`, `pp`.`profile_pic` AS `profile_pic` FROM ((`verify` `v` join `users` `u` on(`v`.`user_id` = `u`.`user_id`)) left join `profile_picture` `pp` on(`u`.`user_id` = `pp`.`user_id`)) WHERE `v`.`status` = 'pending' AND `u`.`deleted` = 0 ;

-- --------------------------------------------------------

--
-- Structure for view `view_teams_with_work`
--
DROP TABLE IF EXISTS `view_teams_with_work`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_teams_with_work`  AS SELECT `t`.`team_id` AS `team_id`, `t`.`work_id` AS `work_id`, `w`.`name` AS `work_name`, `w`.`description` AS `work_description`, `w`.`status` AS `work_status`, `w`.`user_id` AS `work_owner_id` FROM (`team` `t` join `work` `w` on(`t`.`work_id` = `w`.`work_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_user_projects`
--
DROP TABLE IF EXISTS `view_user_projects`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_user_projects`  AS SELECT `work`.`work_id` AS `work_id`, `work`.`description` AS `description`, `work`.`name` AS `name`, `work`.`user_id` AS `user_id`, `work`.`location` AS `location`, `work`.`budget` AS `budget`, `work`.`start_date` AS `start_date`, `work`.`end_date` AS `end_date`, `work`.`status` AS `status`, `work`.`created_at` AS `created_at`, `work`.`updated_at` AS `updated_at`, `users`.`name` AS `client_name`, `users`.`contact_no` AS `client_contact` FROM (`work` join `users` on(`work`.`user_id` = `users`.`user_id`)) ORDER BY `work`.`created_at` DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `admin_user_id_foreign` (`user_id`);

--
-- Indexes for table `certificate`
--
ALTER TABLE `certificate`
  ADD PRIMARY KEY (`certificate_id`),
  ADD KEY `certificate_verify_id_foreign` (`verify_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `customer_user_id_foreign` (`user_id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `document_work_id_foreign` (`work_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `installment_plan`
--
ALTER TABLE `installment_plan`
  ADD PRIMARY KEY (`installment_plan_id`),
  ADD KEY `installment_plan_work_id_foreign` (`work_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `invoice_work_id_foreign` (`work_id`);

--
-- Indexes for table `meetups`
--
ALTER TABLE `meetups`
  ADD PRIMARY KEY (`meetup_id`),
  ADD KEY `meetups_user_id_foreign` (`user_id`),
  ADD KEY `meetups_work_id_foreign` (`work_id`);

--
-- Indexes for table `member_tasks`
--
ALTER TABLE `member_tasks`
  ADD PRIMARY KEY (`member_task_id`),
  ADD KEY `member_tasks_team_member_id_foreign` (`team_member_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `payment_work_id_foreign` (`work_id`),
  ADD KEY `payment_user_id_foreign` (`user_id`);

--
-- Indexes for table `pending_professional`
--
ALTER TABLE `pending_professional`
  ADD PRIMARY KEY (`pending_prof_id`),
  ADD KEY `pending_professional_user_id_foreign` (`user_id`),
  ADD KEY `pending_professional_professional_id_foreign` (`professional_id`),
  ADD KEY `pending_professional_work_id_foreign` (`work_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `professionals`
--
ALTER TABLE `professionals`
  ADD PRIMARY KEY (`professional_id`),
  ADD KEY `professionals_user_id_foreign` (`user_id`);

--
-- Indexes for table `profile_picture`
--
ALTER TABLE `profile_picture`
  ADD PRIMARY KEY (`profile_picture_id`),
  ADD KEY `profile_picture_user_id_foreign` (`user_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `rating_professional_id_foreign` (`professional_id`),
  ADD KEY `rating_work_id_foreign` (`work_id`),
  ADD KEY `rating_user_id_foreign` (`user_id`);

--
-- Indexes for table `reference`
--
ALTER TABLE `reference`
  ADD PRIMARY KEY (`reference_id`),
  ADD KEY `reference_professional_id_foreign` (`professional_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`referral_id`),
  ADD KEY `referrals_work_id_foreign` (`work_id`),
  ADD KEY `referrals_professional_id_foreign` (`professional_id`),
  ADD KEY `referrals_reference_id_foreign` (`reference_id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `team_work_id_foreign` (`work_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`team_member_id`),
  ADD KEY `team_members_user_id_foreign` (`user_id`),
  ADD KEY `team_members_team_id_foreign` (`team_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `verify`
--
ALTER TABLE `verify`
  ADD PRIMARY KEY (`verify_id`),
  ADD KEY `verify_user_id_foreign` (`user_id`);

--
-- Indexes for table `work`
--
ALTER TABLE `work`
  ADD PRIMARY KEY (`work_id`),
  ADD KEY `work_user_id_foreign` (`user_id`);

--
-- Indexes for table `work_history`
--
ALTER TABLE `work_history`
  ADD PRIMARY KEY (`work_history_id`),
  ADD KEY `work_history_work_id_foreign` (`work_id`),
  ADD KEY `work_history_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificate`
--
ALTER TABLE `certificate`
  MODIFY `certificate_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `document_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `installment_plan`
--
ALTER TABLE `installment_plan`
  MODIFY `installment_plan_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meetups`
--
ALTER TABLE `meetups`
  MODIFY `meetup_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_tasks`
--
ALTER TABLE `member_tasks`
  MODIFY `member_task_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pending_professional`
--
ALTER TABLE `pending_professional`
  MODIFY `pending_prof_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `professionals`
--
ALTER TABLE `professionals`
  MODIFY `professional_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `profile_picture`
--
ALTER TABLE `profile_picture`
  MODIFY `profile_picture_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reference`
--
ALTER TABLE `reference`
  MODIFY `reference_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `referral_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `team_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `team_member_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `verify`
--
ALTER TABLE `verify`
  MODIFY `verify_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `work`
--
ALTER TABLE `work`
  MODIFY `work_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `work_history`
--
ALTER TABLE `work_history`
  MODIFY `work_history_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `certificate`
--
ALTER TABLE `certificate`
  ADD CONSTRAINT `certificate_verify_id_foreign` FOREIGN KEY (`verify_id`) REFERENCES `verify` (`verify_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `document_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `work` (`work_id`);

--
-- Constraints for table `installment_plan`
--
ALTER TABLE `installment_plan`
  ADD CONSTRAINT `installment_plan_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `work` (`work_id`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `work` (`work_id`);

--
-- Constraints for table `meetups`
--
ALTER TABLE `meetups`
  ADD CONSTRAINT `meetups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `meetups_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `work` (`work_id`);

--
-- Constraints for table `member_tasks`
--
ALTER TABLE `member_tasks`
  ADD CONSTRAINT `member_tasks_team_member_id_foreign` FOREIGN KEY (`team_member_id`) REFERENCES `team_members` (`team_member_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `payment_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `work` (`work_id`);

--
-- Constraints for table `pending_professional`
--
ALTER TABLE `pending_professional`
  ADD CONSTRAINT `pending_professional_professional_id_foreign` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`professional_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pending_professional_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pending_professional_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `work` (`work_id`) ON DELETE CASCADE;

--
-- Constraints for table `professionals`
--
ALTER TABLE `professionals`
  ADD CONSTRAINT `professionals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `profile_picture`
--
ALTER TABLE `profile_picture`
  ADD CONSTRAINT `profile_picture_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_professional_id_foreign` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`professional_id`),
  ADD CONSTRAINT `rating_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `rating_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `work` (`work_id`);

--
-- Constraints for table `reference`
--
ALTER TABLE `reference`
  ADD CONSTRAINT `reference_professional_id_foreign` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`professional_id`);

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_professional_id_foreign` FOREIGN KEY (`professional_id`) REFERENCES `professionals` (`professional_id`),
  ADD CONSTRAINT `referrals_reference_id_foreign` FOREIGN KEY (`reference_id`) REFERENCES `reference` (`reference_id`),
  ADD CONSTRAINT `referrals_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `work` (`work_id`);

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `work` (`work_id`);

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`),
  ADD CONSTRAINT `team_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `verify`
--
ALTER TABLE `verify`
  ADD CONSTRAINT `verify_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `work`
--
ALTER TABLE `work`
  ADD CONSTRAINT `work_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `work_history`
--
ALTER TABLE `work_history`
  ADD CONSTRAINT `work_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `work_history_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `work` (`work_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
