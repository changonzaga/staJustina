-- SQL Script to Update Enrollment Table for Complete Enrollment Workflow
-- This script adds required fields for the enrollment management system
-- Run this script on your stajustina_db database

USE stajustina_db;

-- Add new columns to the enrollment table
ALTER TABLE `enrollment` 
-- Enrollment Number (unique identifier)
ADD COLUMN `enrollment_number` VARCHAR(50) NULL UNIQUE COMMENT 'Unique enrollment number (ENR-YYYYMMDD-XXXX)' AFTER `id`,

-- Form Data Storage
ADD COLUMN `form_data` JSON NULL COMMENT 'Complete form data from enrollment submission' AFTER `documents_submitted`,

-- Approval/Decline Fields
ADD COLUMN `approved_by` INT(11) NULL COMMENT 'User ID who approved the enrollment' AFTER `updated_by`,
ADD COLUMN `approved_at` TIMESTAMP NULL COMMENT 'Timestamp when enrollment was approved' AFTER `approved_by`,
ADD COLUMN `declined_reason` TEXT NULL COMMENT 'Reason for declining enrollment' AFTER `approved_at`;

-- Update enrollment_status enum to include pending, approved, declined
ALTER TABLE `enrollment` 
MODIFY COLUMN `enrollment_status` ENUM('pending','approved','declined','enrolled','transferred','dropped','graduated') NOT NULL DEFAULT 'pending';

-- Add indexes for better performance
ALTER TABLE `enrollment`
ADD INDEX `idx_enrollment_number` (`enrollment_number`),
ADD INDEX `idx_enrollment_status` (`enrollment_status`),
ADD INDEX `idx_approved_by` (`approved_by`),
ADD INDEX `idx_approved_at` (`approved_at`);

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
ADD COLUMN `role` ENUM('admin','teacher','student','parent') NULL DEFAULT 'student' COMMENT 'User role in the system' AFTER `email`,
ADD COLUMN `student_id` INT(11) NULL COMMENT 'Link to student table for student users' AFTER `role`,
ADD COLUMN `enrollment_number` VARCHAR(50) NULL COMMENT 'Enrollment number for student users' AFTER `student_id`,
ADD COLUMN `status` ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active' COMMENT 'Account status' AFTER `enrollment_number`,
ADD COLUMN `temp_password` VARCHAR(255) NULL COMMENT 'Temporary password for new accounts' AFTER `password`,
ADD COLUMN `password_changed` BOOLEAN DEFAULT FALSE COMMENT 'Whether user has changed default password' AFTER `temp_password`;

-- Add indexes to users table
ALTER TABLE `users`
ADD INDEX `idx_role` (`role`),
ADD INDEX `idx_student_id` (`student_id`),
ADD INDEX `idx_enrollment_number` (`enrollment_number`),
ADD INDEX `idx_status` (`status`);

-- Add foreign key constraint for student_id (if student table exists)
SET @student_table_exists = (SELECT COUNT(*) FROM information_schema.tables 
                            WHERE table_schema = 'stajustina_db' AND table_name = 'student');

SET @sql = IF(@student_table_exists > 0,
    'ALTER TABLE `users` ADD CONSTRAINT `fk_users_student` 
     FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) 
     ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT "Student table not found, skipping foreign key constraint" as message');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update student table to include enrollment_number if it doesn't exist
SET @enrollment_number_exists = (SELECT COUNT(*) FROM information_schema.columns 
                                WHERE table_schema = 'stajustina_db' 
                                AND table_name = 'student' 
                                AND column_name = 'enrollment_number');

SET @sql = IF(@enrollment_number_exists = 0,
    'ALTER TABLE `student` 
     ADD COLUMN `enrollment_number` VARCHAR(50) NULL UNIQUE 
     COMMENT "Enrollment number from enrollment process" AFTER `id`,
     ADD INDEX `idx_student_enrollment_number` (`enrollment_number`)',
    'SELECT "enrollment_number column already exists in student table" as message');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create notifications table for enrollment notifications (if it doesn't exist)
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

-- Insert sample data for testing (optional)
-- You can uncomment these lines to add sample pending enrollments
/*
INSERT INTO `enrollment` (
    `enrollment_number`, `school_year`, `grade_level`, `enrollment_date`, 
    `enrollment_status`, `enrollment_type`, `form_data`, `created_at`
) VALUES 
(
    'ENR-20241120-0001', '2024-2025', 'Grade 7', '2024-11-20',
    'pending', 'new', 
    JSON_OBJECT(
        'first_name', 'John',
        'last_name', 'Doe',
        'lrn_digit_0', '1', 'lrn_digit_1', '2', 'lrn_digit_2', '3',
        'lrn_digit_3', '4', 'lrn_digit_4', '5', 'lrn_digit_5', '6',
        'lrn_digit_6', '7', 'lrn_digit_7', '8', 'lrn_digit_8', '9',
        'lrn_digit_9', '0', 'lrn_digit_10', '1', 'lrn_digit_11', '2',
        'grade_level', 'Grade 7',
        'gender', 'Male',
        'age', 13,
        'student_email', 'john.doe@email.com',
        'father_first_name', 'Robert',
        'father_last_name', 'Doe',
        'father_contact', '09123456789'
    ),
    NOW()
);
*/

-- Show updated table structure
DESCRIBE `enrollment`;

-- Show enrollment_logs table structure
DESCRIBE `enrollment_logs`;

-- Show updated users table structure
DESCRIBE `users`;

-- Display success message
SELECT 'Enrollment table has been successfully updated for complete enrollment workflow!' AS Status;

-- Show enrollment statistics
SELECT 
    'Enrollment Statistics' as Report,
    COUNT(*) as Total_Enrollments,
    SUM(CASE WHEN enrollment_status = 'pending' THEN 1 ELSE 0 END) as Pending,
    SUM(CASE WHEN enrollment_status = 'approved' THEN 1 ELSE 0 END) as Approved,
    SUM(CASE WHEN enrollment_status = 'declined' THEN 1 ELSE 0 END) as Declined,
    SUM(CASE WHEN enrollment_status = 'enrolled' THEN 1 ELSE 0 END) as Enrolled
FROM `enrollment`;