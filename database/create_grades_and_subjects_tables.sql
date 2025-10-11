-- =====================================================
-- CREATE GRADES AND SUBJECTS TABLES
-- =====================================================

-- Create grades table first (referenced by subjects)
CREATE TABLE IF NOT EXISTS `grades` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `grade_level` varchar(20) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `grade_level` (`grade_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default grade levels
INSERT INTO `grades` (`grade_level`, `description`) VALUES
(7, 'Grade 7 - Junior High School'),
(8, 'Grade 8 - Junior High School'),
(9, 'Grade 9 - Junior High School'),
(10, 'Grade 10 - Junior High School');

-- Create subjects table with proper foreign key constraints
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(100) NOT NULL,
  `subject_code` varchar(20) DEFAULT NULL,
  `grade_id` int(11) UNSIGNED NOT NULL,
  `department_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `grade_id` (`grade_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert some sample subjects
INSERT INTO `subjects` (`subject_name`, `subject_code`, `grade_id`, `department_id`) VALUES
('Mathematics', 'MATH7', 1, NULL),
('English', 'ENG7', 1, NULL),
('Science', 'SCI7', 1, NULL),
('Filipino', 'FIL7', 1, NULL),
('Mathematics', 'MATH8', 2, NULL),
('English', 'ENG8', 2, NULL),
('Science', 'SCI8', 2, NULL),
('Filipino', 'FIL8', 2, NULL),
('Mathematics', 'MATH9', 3, NULL),
('English', 'ENG9', 3, NULL),
('Science', 'SCI9', 3, NULL),
('Filipino', 'FIL9', 3, NULL),
('Mathematics', 'MATH10', 4, NULL),
('English', 'ENG10', 4, NULL),
('Science', 'SCI10', 4, NULL),
('Filipino', 'FIL10', 4, NULL);
