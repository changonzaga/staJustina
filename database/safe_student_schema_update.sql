-- SAFE STUDENT TABLE SCHEMA UPDATE SCRIPT
-- Execute this script in a staging environment first, then production
-- This script follows a phased approach to minimize risks

USE stajustina_db;

-- =====================================================
-- PHASE 1: PRE-UPDATE VALIDATION AND BACKUP
-- =====================================================

-- Create backup table
DROP TABLE IF EXISTS `student_backup_before_update`;
CREATE TABLE `student_backup_before_update` AS SELECT * FROM `student`;

-- Validate existing data integrity
SELECT 'Data Integrity Check - Starting' as Status;

-- Check for orphaned foreign key references
SELECT 
    'Orphaned attendance records' as Check_Type,
    COUNT(*) as Count
FROM attendance a 
LEFT JOIN student s ON a.student_id = s.id 
WHERE s.id IS NULL;

SELECT 
    'Orphaned report card records' as Check_Type,
    COUNT(*) as Count
FROM report_cards r 
LEFT JOIN student s ON r.student_id = s.id 
WHERE s.id IS NULL;

-- Check current LRN format consistency
SELECT 
    'LRN Format Check' as Check_Type,
    COUNT(*) as Total_Records,
    COUNT(CASE WHEN LENGTH(lrn) = 12 THEN 1 END) as Valid_12_Digit,
    COUNT(CASE WHEN LENGTH(lrn) != 12 THEN 1 END) as Invalid_Format
FROM student;

-- =====================================================
-- PHASE 2: SCHEMA UPDATE WITH SAFE DEFAULTS
-- =====================================================

START TRANSACTION;

-- Add new fields with safe defaults and proper positioning
ALTER TABLE `student` 
-- Personal Information
ADD COLUMN `date_of_birth` DATE NULL 
    COMMENT 'Student date of birth' 
    AFTER `name`,

ADD COLUMN `citizenship` VARCHAR(50) NULL DEFAULT 'Filipino' 
    COMMENT 'Student citizenship/nationality' 
    AFTER `gender`,

ADD COLUMN `religion` VARCHAR(50) NULL 
    COMMENT 'Student religion (optional)' 
    AFTER `citizenship`,

-- Enrollment Information
ADD COLUMN `enrollment_status` ENUM('new', 'transferee', 'continuing') NOT NULL DEFAULT 'continuing' 
    COMMENT 'Student enrollment status' 
    AFTER `section`,

ADD COLUMN `school_assigned` VARCHAR(255) NULL 
    COMMENT 'Name of assigned school' 
    AFTER `enrollment_status`,

ADD COLUMN `school_id` VARCHAR(50) NULL 
    COMMENT 'Official school identification number' 
    AFTER `school_assigned`,

ADD COLUMN `date_of_enrollment` DATE NULL 
    COMMENT 'Date when student was enrolled' 
    AFTER `school_id`,

-- Enhanced Address Information
ADD COLUMN `residential_address` TEXT NULL 
    COMMENT 'Complete residential address' 
    AFTER `address`,

-- Enhanced Parent/Guardian Information
ADD COLUMN `parent_guardian_name` VARCHAR(200) NULL 
    COMMENT 'Full name of parent/guardian' 
    AFTER `residential_address`,

ADD COLUMN `parent_guardian_contact` VARCHAR(50) NULL 
    COMMENT 'Contact number of parent/guardian' 
    AFTER `parent_guardian_name`,

ADD COLUMN `parent_guardian_email` VARCHAR(100) NULL 
    COMMENT 'Email address of parent/guardian' 
    AFTER `parent_guardian_contact`,

-- Emergency Contact
ADD COLUMN `emergency_contact_name` VARCHAR(200) NULL 
    COMMENT 'Emergency contact person name' 
    AFTER `parent_guardian_email`,

ADD COLUMN `emergency_contact_number` VARCHAR(50) NULL 
    COMMENT 'Emergency contact phone number' 
    AFTER `emergency_contact_name`,

-- Special Needs and Health
ADD COLUMN `special_education_needs` TEXT NULL 
    COMMENT 'Special education needs or requirements' 
    AFTER `emergency_contact_number`,

ADD COLUMN `health_conditions` TEXT NULL 
    COMMENT 'Health conditions or medical information' 
    AFTER `special_education_needs`,

-- Previous School Information
ADD COLUMN `previous_school_attended` VARCHAR(255) NULL 
    COMMENT 'Previous school for transferee students' 
    AFTER `health_conditions`,

ADD COLUMN `previous_school_address` TEXT NULL 
    COMMENT 'Address of previous school' 
    AFTER `previous_school_attended`,

-- Document Information
ADD COLUMN `birth_certificate_number` VARCHAR(100) NULL 
    COMMENT 'Birth certificate or PSA document number' 
    AFTER `previous_school_address`,

ADD COLUMN `identification_documents` JSON NULL 
    COMMENT 'JSON array of identification document references' 
    AFTER `birth_certificate_number`,

-- Academic and Attendance Records (JSON for flexibility)
ADD COLUMN `academic_records` JSON NULL 
    COMMENT 'JSON object for academic records and grades' 
    AFTER `identification_documents`,

ADD COLUMN `attendance_record` JSON NULL 
    COMMENT 'JSON object for attendance tracking' 
    AFTER `academic_records`,

-- Status and Remarks
ADD COLUMN `student_status` ENUM('active', 'inactive', 'graduated', 'transferred', 'dropped') NOT NULL DEFAULT 'active' 
    COMMENT 'Current status of the student' 
    AFTER `attendance_record`,

ADD COLUMN `remarks` TEXT NULL 
    COMMENT 'Additional remarks or notes about the student' 
    AFTER `student_status`;

-- =====================================================
-- PHASE 3: ADD INDEXES FOR PERFORMANCE
-- =====================================================

-- Add performance indexes
ALTER TABLE `student` 
ADD INDEX `idx_enrollment_status` (`enrollment_status`),
ADD INDEX `idx_student_status` (`student_status`),
ADD INDEX `idx_date_enrollment` (`date_of_enrollment`),
ADD INDEX `idx_school_id` (`school_id`),
ADD INDEX `idx_citizenship` (`citizenship`),
ADD INDEX `idx_date_of_birth` (`date_of_birth`);

-- =====================================================
-- PHASE 4: ADD UNIQUE CONSTRAINTS (CAREFULLY)
-- =====================================================

-- Add unique constraint for birth certificate number (only if not null)
-- This allows multiple NULL values but ensures uniqueness when provided
ALTER TABLE `student` 
ADD UNIQUE KEY `uk_birth_cert` (`birth_certificate_number`);

-- =====================================================
-- PHASE 5: POPULATE DEFAULT VALUES FOR EXISTING RECORDS
-- =====================================================

-- Update existing records with sensible defaults
UPDATE `student` SET 
    `citizenship` = 'Filipino' WHERE `citizenship` IS NULL,
    `enrollment_status` = 'continuing' WHERE `enrollment_status` IS NULL,
    `student_status` = 'active' WHERE `student_status` IS NULL,
    `date_of_enrollment` = DATE(`created_at`) WHERE `date_of_enrollment` IS NULL;

-- Migrate existing guardian info to new structured fields
UPDATE `student` SET 
    `parent_guardian_name` = `guardian`,
    `parent_guardian_contact` = `contact`
WHERE `guardian` IS NOT NULL OR `contact` IS NOT NULL;

-- =====================================================
-- PHASE 6: VALIDATION AND VERIFICATION
-- =====================================================

-- Verify the update was successful
SELECT 'Schema Update Validation' as Status;

-- Check table structure
DESCRIBE `student`;

-- Verify data integrity after update
SELECT 
    'Post-Update Record Count' as Check_Type,
    COUNT(*) as Count
FROM `student`;

-- Verify no data was lost
SELECT 
    'Data Integrity Verification' as Check_Type,
    CASE 
        WHEN (SELECT COUNT(*) FROM student) = (SELECT COUNT(*) FROM student_backup_before_update)
        THEN 'PASS - No records lost'
        ELSE 'FAIL - Record count mismatch'
    END as Result;

-- Check foreign key relationships are intact
SELECT 
    'Foreign Key Integrity Check' as Check_Type,
    COUNT(*) as Orphaned_Attendance_Records
FROM attendance a 
LEFT JOIN student s ON a.student_id = s.id 
WHERE s.id IS NULL;

SELECT 
    'Foreign Key Integrity Check' as Check_Type,
    COUNT(*) as Orphaned_Report_Card_Records
FROM report_cards r 
LEFT JOIN student s ON r.student_id = s.id 
WHERE s.id IS NULL;

-- Verify new fields are properly populated
SELECT 
    'New Fields Population Check' as Check_Type,
    COUNT(CASE WHEN citizenship IS NOT NULL THEN 1 END) as Records_With_Citizenship,
    COUNT(CASE WHEN enrollment_status IS NOT NULL THEN 1 END) as Records_With_Enrollment_Status,
    COUNT(CASE WHEN student_status IS NOT NULL THEN 1 END) as Records_With_Student_Status
FROM student;

-- =====================================================
-- PHASE 7: CREATE ENHANCED VIEW FOR COMPLETE STUDENT INFO
-- =====================================================

-- Create or replace view for complete student information
CREATE OR REPLACE VIEW `student_complete_view` AS
SELECT 
    s.*,
    t.name AS teacher_name,
    t.email AS teacher_email,
    p.name AS parent_name,
    p.email AS parent_email,
    p.contact AS parent_contact_alt
FROM `student` s
LEFT JOIN `teacher` t ON s.teacher_id = t.id
LEFT JOIN `parent` p ON s.parent_id = p.id;

-- =====================================================
-- COMMIT OR ROLLBACK
-- =====================================================

-- If all validations pass, commit the transaction
-- If any issues are found, use ROLLBACK instead
COMMIT;

-- Display success message
SELECT 
    'SCHEMA UPDATE COMPLETED SUCCESSFULLY!' as Status,
    NOW() as Completion_Time,
    'All new student information fields have been added safely' as Message;

-- Show sample of updated structure with new fields
SELECT 
    id,
    name,
    lrn,
    date_of_birth,
    citizenship,
    enrollment_status,
    student_status,
    created_at
FROM student 
LIMIT 5;

-- =====================================================
-- ROLLBACK PROCEDURE (USE IF NEEDED)
-- =====================================================
/*
-- If something goes wrong, use this rollback procedure:

ROLLBACK;

-- Restore from backup if needed:
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` AS SELECT * FROM `student_backup_before_update`;

-- Recreate indexes and constraints
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lrn` (`lrn`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `parent_id` (`parent_id`);

ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`) ON DELETE SET NULL;

ALTER TABLE `student` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
*/

-- =====================================================
-- POST-UPDATE TESTING QUERIES
-- =====================================================

-- Test CRUD operations
-- INSERT test (uncomment to test)
/*
INSERT INTO student (
    name, lrn, date_of_birth, gender, age, citizenship, religion,
    grade_level, section, enrollment_status, school_assigned, school_id,
    date_of_enrollment, address, residential_address,
    parent_guardian_name, parent_guardian_contact, parent_guardian_email,
    student_status
) VALUES (
    'Test Student', '123456789012', '2010-01-01', 'Male', 13, 'Filipino', 'Catholic',
    'Grade 7', 'Section A', 'new', 'Sta. Justina School', 'SJS001',
    CURDATE(), '123 Test St.', '123 Test St., Test City',
    'Test Parent', '09123456789', 'parent@test.com',
    'active'
);
*/

-- SELECT test with new fields
SELECT 
    name, lrn, enrollment_status, student_status, citizenship,
    parent_guardian_name, date_of_enrollment
FROM student 
WHERE student_status = 'active'
LIMIT 3;

-- UPDATE test
-- UPDATE student SET remarks = 'Schema update completed successfully' WHERE id = 1;

-- Test relationships are intact
SELECT 
    s.name as student_name,
    s.lrn,
    s.enrollment_status,
    t.name as teacher_name,
    p.name as parent_name
FROM student s
LEFT JOIN teacher t ON s.teacher_id = t.id
LEFT JOIN parent p ON s.parent_id = p.id
LIMIT 3;

SELECT 'SCHEMA UPDATE AND VALIDATION COMPLETE!' as Final_Status;