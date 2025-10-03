-- SQL Script to Update Student Table with Complete Student Information Fields
-- This script adds all required fields for comprehensive student management
-- Run this script on your stajustina_db database

USE stajustina_db;

-- Add new columns to the student table
ALTER TABLE `student` 
-- Date of Birth
ADD COLUMN `date_of_birth` DATE NULL COMMENT 'Student date of birth' AFTER `name`,

-- Citizenship and Religion
ADD COLUMN `citizenship` VARCHAR(50) NULL DEFAULT 'Filipino' COMMENT 'Student citizenship/nationality' AFTER `gender`,
ADD COLUMN `religion` VARCHAR(50) NULL COMMENT 'Student religion (optional)' AFTER `citizenship`,

-- Enrollment Information
ADD COLUMN `enrollment_status` ENUM('new', 'transferee', 'continuing') NOT NULL DEFAULT 'new' COMMENT 'Student enrollment status' AFTER `section`,
ADD COLUMN `school_assigned` VARCHAR(255) NULL COMMENT 'Name of assigned school' AFTER `enrollment_status`,
ADD COLUMN `school_id` VARCHAR(50) NULL COMMENT 'Official school identification number' AFTER `school_assigned`,
ADD COLUMN `date_of_enrollment` DATE NULL COMMENT 'Date when student was enrolled' AFTER `school_id`,

-- Address Information
ADD COLUMN `residential_address` TEXT NULL COMMENT 'Complete residential address' AFTER `address`,

-- Parent/Guardian Information
ADD COLUMN `parent_guardian_name` VARCHAR(200) NULL COMMENT 'Full name of parent/guardian' AFTER `residential_address`,
ADD COLUMN `parent_guardian_contact` VARCHAR(50) NULL COMMENT 'Contact number of parent/guardian' AFTER `parent_guardian_name`,
ADD COLUMN `parent_guardian_email` VARCHAR(100) NULL COMMENT 'Email address of parent/guardian' AFTER `parent_guardian_contact`,

-- Emergency Contact
ADD COLUMN `emergency_contact_name` VARCHAR(200) NULL COMMENT 'Emergency contact person name' AFTER `parent_guardian_email`,
ADD COLUMN `emergency_contact_number` VARCHAR(50) NULL COMMENT 'Emergency contact phone number' AFTER `emergency_contact_name`,

-- Special Needs and Health
ADD COLUMN `special_education_needs` TEXT NULL COMMENT 'Special education needs or requirements' AFTER `emergency_contact_number`,
ADD COLUMN `health_conditions` TEXT NULL COMMENT 'Health conditions or medical information' AFTER `special_education_needs`,

-- Previous School Information
ADD COLUMN `previous_school_attended` VARCHAR(255) NULL COMMENT 'Previous school for transferee students' AFTER `health_conditions`,
ADD COLUMN `previous_school_address` TEXT NULL COMMENT 'Address of previous school' AFTER `previous_school_attended`,

-- Document Information
ADD COLUMN `birth_certificate_number` VARCHAR(100) NULL COMMENT 'Birth certificate or PSA document number' AFTER `previous_school_address`,
ADD COLUMN `identification_documents` JSON NULL COMMENT 'JSON array of identification document references' AFTER `birth_certificate_number`,

-- Academic and Attendance Records
ADD COLUMN `academic_records` JSON NULL COMMENT 'JSON object for academic records and grades' AFTER `identification_documents`,
ADD COLUMN `attendance_record` JSON NULL COMMENT 'JSON object for attendance tracking' AFTER `academic_records`,

-- Student Status and Remarks
ADD COLUMN `student_status` ENUM('active', 'inactive', 'graduated', 'transferred', 'dropped') NOT NULL DEFAULT 'active' COMMENT 'Current status of the student' AFTER `attendance_record`,
ADD COLUMN `remarks` TEXT NULL COMMENT 'Additional remarks or notes about the student' AFTER `student_status`;

-- Add indexes for better performance
ALTER TABLE `student` 
ADD INDEX `idx_enrollment_status` (`enrollment_status`),
ADD INDEX `idx_student_status` (`student_status`),
ADD INDEX `idx_date_enrollment` (`date_of_enrollment`),
ADD INDEX `idx_school_id` (`school_id`);

-- Add unique constraint for birth certificate number (if provided)
ALTER TABLE `student` 
ADD UNIQUE KEY `uk_birth_cert` (`birth_certificate_number`);

-- Update existing records with default values where appropriate
UPDATE `student` SET 
    `citizenship` = 'Filipino' WHERE `citizenship` IS NULL,
    `enrollment_status` = 'continuing' WHERE `enrollment_status` IS NULL,
    `student_status` = 'active' WHERE `student_status` IS NULL,
    `date_of_enrollment` = `created_at` WHERE `date_of_enrollment` IS NULL;

-- Create a view for complete student information
CREATE OR REPLACE VIEW `student_complete_view` AS
SELECT 
    s.*,
    t.name AS teacher_name,
    t.email AS teacher_email,
    p.name AS parent_name,
    p.email AS parent_email,
    p.contact AS parent_contact
FROM `student` s
LEFT JOIN `teacher` t ON s.teacher_id = t.id
LEFT JOIN `parent` p ON s.parent_id = p.id;

-- Show the updated table structure
DESCRIBE `student`;

-- Display success message
SELECT 'Student table has been successfully updated with all required fields!' AS Status;

-- Show sample of updated structure
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'stajustina_db' 
AND TABLE_NAME = 'student'
ORDER BY ORDINAL_POSITION;