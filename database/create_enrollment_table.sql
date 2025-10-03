-- Create basic enrollment table structure
USE stajustina_db;

-- Drop table if exists to recreate
DROP TABLE IF EXISTS `enrollment`;

-- Create enrollment table
CREATE TABLE `enrollment` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `enrollment_number` VARCHAR(50) NULL UNIQUE COMMENT 'Unique enrollment number (ENR-YYYYMMDD-XXXX)',
    `student_id` int(11) NULL COMMENT 'Link to student table after approval',
    `school_year` varchar(20) NOT NULL COMMENT 'e.g., 2024-2025',
    `grade_level` varchar(20) NOT NULL,
    `section` varchar(50) NULL,
    `enrollment_date` date NOT NULL,
    `enrollment_status` enum('pending','approved','declined','enrolled','transferred','dropped','graduated') NOT NULL DEFAULT 'pending',
    `enrollment_type` enum('new','transferee','continuing','returnee') NOT NULL DEFAULT 'new',
    `previous_school` varchar(255) NULL COMMENT 'For transferees',
    `previous_grade` varchar(20) NULL COMMENT 'Previous grade level',
    `enrollment_fee` decimal(10,2) NULL DEFAULT 0.00,
    `payment_status` enum('paid','partial','unpaid') NOT NULL DEFAULT 'unpaid',
    `documents_submitted` json NULL COMMENT 'List of submitted documents',
    `form_data` JSON NULL COMMENT 'Complete form data from enrollment submission',
    `special_program` varchar(100) NULL COMMENT 'Special programs enrolled in',
    `parent_guardian_signature` tinyint(1) DEFAULT 0 COMMENT 'Parent/guardian signature received',
    `medical_clearance` tinyint(1) DEFAULT 0 COMMENT 'Medical clearance submitted',
    `academic_year_start` date NULL,
    `academic_year_end` date NULL,
    `teacher_id` int(11) NULL COMMENT 'Assigned class teacher',
    `remarks` text NULL,
    `created_by` int(11) NULL COMMENT 'User who created the enrollment',
    `updated_by` int(11) NULL COMMENT 'User who last updated',
    `approved_by` INT(11) NULL COMMENT 'User ID who approved the enrollment',
    `approved_at` TIMESTAMP NULL COMMENT 'Timestamp when enrollment was approved',
    `declined_reason` TEXT NULL COMMENT 'Reason for declining enrollment',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_enrollment_number` (`enrollment_number`),
    KEY `idx_student_id` (`student_id`),
    KEY `idx_school_year` (`school_year`),
    KEY `idx_enrollment_status` (`enrollment_status`),
    KEY `idx_enrollment_type` (`enrollment_type`),
    KEY `idx_grade_level` (`grade_level`),
    KEY `idx_enrollment_date` (`enrollment_date`),
    KEY `idx_teacher_id` (`teacher_id`),
    KEY `idx_approved_by` (`approved_by`),
    KEY `idx_approved_at` (`approved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci 
COMMENT='Student enrollment records by school year';

-- Create enrollment_logs table for audit trail
CREATE TABLE IF NOT EXISTS `enrollment_logs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `enrollment_id` INT(11) NOT NULL,
    `action` ENUM('submitted','approved','declined','updated') NOT NULL,
    `user_id` INT(11) NULL COMMENT 'User who performed the action',
    `remarks` TEXT NULL COMMENT 'Additional remarks or notes',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_enrollment_id` (`enrollment_id`),
    KEY `idx_action` (`action`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_created_at` (`created_at`),
    CONSTRAINT `fk_enrollment_logs_enrollment` 
        FOREIGN KEY (`enrollment_id`) REFERENCES `enrollment` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci 
COMMENT='Audit trail for enrollment actions';

-- Update users table to support student accounts
ALTER TABLE `users`
ADD COLUMN IF NOT EXISTS `role` ENUM('admin','teacher','student','parent') NULL DEFAULT 'student' COMMENT 'User role in the system' AFTER `email`,
ADD COLUMN IF NOT EXISTS `student_id` INT(11) NULL COMMENT 'Link to student table for student users' AFTER `role`,
ADD COLUMN IF NOT EXISTS `enrollment_number` VARCHAR(50) NULL COMMENT 'Enrollment number for student users' AFTER `student_id`,
ADD COLUMN IF NOT EXISTS `status` ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active' COMMENT 'Account status' AFTER `enrollment_number`,
ADD COLUMN IF NOT EXISTS `temp_password` VARCHAR(255) NULL COMMENT 'Temporary password for new accounts' AFTER `password`,
ADD COLUMN IF NOT EXISTS `password_changed` BOOLEAN DEFAULT FALSE COMMENT 'Whether user has changed default password' AFTER `temp_password`;

-- Add indexes to users table
ALTER TABLE `users`
ADD INDEX IF NOT EXISTS `idx_role` (`role`),
ADD INDEX IF NOT EXISTS `idx_student_id` (`student_id`),
ADD INDEX IF NOT EXISTS `idx_enrollment_number` (`enrollment_number`),
ADD INDEX IF NOT EXISTS `idx_status` (`status`);

-- Update student table to include enrollment_number if it doesn't exist
ALTER TABLE `student` 
ADD COLUMN IF NOT EXISTS `enrollment_number` VARCHAR(50) NULL UNIQUE 
COMMENT 'Enrollment number from enrollment process' AFTER `id`,
ADD INDEX IF NOT EXISTS `idx_student_enrollment_number` (`enrollment_number`);

-- Create notifications table for enrollment notifications
CREATE TABLE IF NOT EXISTS `enrollment_notifications` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `enrollment_id` INT(11) NOT NULL,
    `recipient_email` VARCHAR(255) NOT NULL,
    `recipient_name` VARCHAR(255) NOT NULL,
    `notification_type` ENUM('submitted','approved','declined','account_created') NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `sent_at` TIMESTAMP NULL,
    `status` ENUM('pending','sent','failed') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_enrollment_id` (`enrollment_id`),
    KEY `idx_notification_type` (`notification_type`),
    KEY `idx_status` (`status`),
    KEY `idx_sent_at` (`sent_at`),
    CONSTRAINT `fk_enrollment_notifications_enrollment` 
        FOREIGN KEY (`enrollment_id`) REFERENCES `enrollment` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci 
COMMENT='Notifications for enrollment process';

-- Insert sample enrollment data for testing
INSERT INTO `enrollment` (
    `enrollment_number`, `school_year`, `grade_level`, `enrollment_date`, 
    `enrollment_status`, `enrollment_type`, `form_data`, `created_at`
) VALUES 
(
    'ENR-20241120-0001', '2024-2025', 'Grade 7', '2024-11-20',
    'pending', 'new', 
    JSON_OBJECT(
        'first_name', 'John',
        'middle_name', 'Michael',
        'last_name', 'Doe',
        'lrn_digit_0', '1', 'lrn_digit_1', '2', 'lrn_digit_2', '3',
        'lrn_digit_3', '4', 'lrn_digit_4', '5', 'lrn_digit_5', '6',
        'lrn_digit_6', '7', 'lrn_digit_7', '8', 'lrn_digit_8', '9',
        'lrn_digit_9', '0', 'lrn_digit_10', '1', 'lrn_digit_11', '2',
        'grade_level', 'Grade 7',
        'gender', 'Male',
        'age', 13,
        'date_of_birth', '2011-05-15',
        'student_email', 'john.doe@email.com',
        'student_contact', '09123456789',
        'father_first_name', 'Robert',
        'father_last_name', 'Doe',
        'father_contact', '09123456789',
        'mother_first_name', 'Jane',
        'mother_last_name', 'Doe',
        'mother_contact', '09123456790',
        'current_house_no', '123',
        'current_street', 'Main Street',
        'current_barangay', 'Poblacion',
        'current_municipality', 'Sample City',
        'current_province', 'Sample Province'
    ),
    NOW()
),
(
    'ENR-20241120-0002', '2024-2025', 'Grade 8', '2024-11-20',
    'pending', 'transferee', 
    JSON_OBJECT(
        'first_name', 'Maria',
        'middle_name', 'Cruz',
        'last_name', 'Santos',
        'lrn_digit_0', '2', 'lrn_digit_1', '3', 'lrn_digit_2', '4',
        'lrn_digit_3', '5', 'lrn_digit_4', '6', 'lrn_digit_5', '7',
        'lrn_digit_6', '8', 'lrn_digit_7', '9', 'lrn_digit_8', '0',
        'lrn_digit_9', '1', 'lrn_digit_10', '2', 'lrn_digit_11', '3',
        'grade_level', 'Grade 8',
        'gender', 'Female',
        'age', 14,
        'date_of_birth', '2010-08-22',
        'student_email', 'maria.santos@email.com',
        'student_contact', '09234567890',
        'father_first_name', 'Roberto',
        'father_last_name', 'Santos',
        'father_contact', '09234567890',
        'mother_first_name', 'Carmen',
        'mother_last_name', 'Santos',
        'mother_contact', '09234567891',
        'last_school_attended', 'ABC Elementary School'
    ),
    NOW()
);

-- Show success message
SELECT 'Enrollment system tables created successfully!' AS Status;

-- Show table structures
DESCRIBE `enrollment`;
DESCRIBE `enrollment_logs`;
DESCRIBE `enrollment_notifications`;

-- Show sample data
SELECT 
    enrollment_number,
    JSON_UNQUOTE(JSON_EXTRACT(form_data, '$.first_name')) as first_name,
    JSON_UNQUOTE(JSON_EXTRACT(form_data, '$.last_name')) as last_name,
    grade_level,
    enrollment_status,
    created_at
FROM enrollment;