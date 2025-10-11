CREATE TABLE `departments` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `head_id` INT(11) UNSIGNED DEFAULT NULL,   -- Optional: department head teacher
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`head_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `grades` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `grade_name` VARCHAR(50) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `education_level` ENUM('Elementary','Junior High','Senior High') DEFAULT 'Junior High',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `sections` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `grade_id` INT(11) UNSIGNED NOT NULL,
  `section_name` VARCHAR(100) NOT NULL,
  `capacity` INT(3) DEFAULT 40,
  `school_year` VARCHAR(10) NOT NULL,
  `adviser_id` INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`grade_id`) REFERENCES `grades`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`adviser_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `subjects` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_name` VARCHAR(100) NOT NULL,
  `subject_code` VARCHAR(20) DEFAULT NULL,
  `grade_id` INT(11) UNSIGNED NOT NULL,
  `department_id` INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`grade_id`) REFERENCES `grades`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `school_years` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_year` VARCHAR(10) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `academic_periods` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `period_name` VARCHAR(50) NOT NULL,
  `period_type` ENUM('Quarter','Semester') NOT NULL,
  `school_year_id` INT(11) UNSIGNED NOT NULL,
  `start_date` DATE,
  `end_date` DATE,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`school_year_id`) REFERENCES `school_years`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `shs_strands` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `strand_code` VARCHAR(20) NOT NULL,
  `strand_name` VARCHAR(100) NOT NULL,
  `track` ENUM('Academic','TVL','Sports','Arts') DEFAULT 'Academic',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `classes` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_code` VARCHAR(50) NOT NULL UNIQUE,
  `class_name` VARCHAR(100) NOT NULL,
  `grade_id` INT(11) UNSIGNED NOT NULL,
  `section_id` INT(11) UNSIGNED NOT NULL,
  `subject_id` INT(11) UNSIGNED NOT NULL,
  `teacher_id` INT(11) UNSIGNED NOT NULL,
  `school_year_id` INT(11) UNSIGNED NOT NULL,
  `academic_period_id` INT(11) UNSIGNED DEFAULT NULL,
  `strand_id` INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`grade_id`) REFERENCES `grades`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`school_year_id`) REFERENCES `school_years`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`strand_id`) REFERENCES `shs_strands`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `section_schedules` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_id` INT(11) UNSIGNED NOT NULL,
  `subject_id` INT(11) UNSIGNED NOT NULL,
  `teacher_id` INT(11) UNSIGNED NOT NULL,
  `day_of_week` ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `room` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `subject_teachers` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` INT(11) UNSIGNED NOT NULL,
  `teacher_id` INT(11) UNSIGNED NOT NULL,
  `class_id` INT(11) UNSIGNED DEFAULT NULL,
  `school_year_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`school_year_id`) REFERENCES `school_years`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `student_records` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) UNSIGNED NOT NULL,
  `section_id` INT(11) UNSIGNED DEFAULT NULL,
  `strand_id` INT(11) UNSIGNED DEFAULT NULL,
  `school_year_id` INT(11) UNSIGNED NOT NULL,
  `academic_period_id` INT(11) UNSIGNED DEFAULT NULL,
  `overall_average` DECIMAL(5,2) DEFAULT NULL,
  `promotion_status` ENUM('Promoted','Retained','Incomplete','Transferred') DEFAULT 'Promoted',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`strand_id`) REFERENCES `shs_strands`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`school_year_id`) REFERENCES `school_years`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `student_grades` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) UNSIGNED NOT NULL,
  `class_id` INT(11) UNSIGNED NOT NULL,
  `subject_id` INT(11) UNSIGNED NOT NULL,
  `teacher_id` INT(11) UNSIGNED NOT NULL,
  `academic_period_id` INT(11) UNSIGNED DEFAULT NULL,
  `grade` DECIMAL(5,2) DEFAULT NULL,
  `remarks` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `student_attendance` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) UNSIGNED NOT NULL,
  `class_id` INT(11) UNSIGNED DEFAULT NULL,
  `section_id` INT(11) UNSIGNED DEFAULT NULL,
  `academic_period_id` INT(11) UNSIGNED DEFAULT NULL,
  `date` DATE NOT NULL,
  `status` ENUM('Present','Absent','Late','Excused') NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `student_performance_summary` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) UNSIGNED NOT NULL,
  `class_id` INT(11) UNSIGNED DEFAULT NULL,
  `academic_period_id` INT(11) UNSIGNED DEFAULT NULL,
  `average_grade` DECIMAL(5,2) DEFAULT NULL,
  `attendance_rate` DECIMAL(5,2) DEFAULT NULL,
  `rating` ENUM('Excellent','Very Good','Satisfactory','Needs Improvement','Poor') DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`academic_period_id`) REFERENCES `academic_periods`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `calendar_events` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `event_date` DATE NOT NULL,
  `start_time` TIME DEFAULT NULL,
  `end_time` TIME DEFAULT NULL,
  `event_type` ENUM('Exam','Holiday','Meeting','Activity','Other') DEFAULT 'Other',
  `audience` ENUM('All','Students','Teachers','Parents','SpecificClass') DEFAULT 'All',
  `class_id` INT(11) UNSIGNED DEFAULT NULL,
  `created_by` INT(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;