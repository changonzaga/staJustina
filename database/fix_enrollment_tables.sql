-- Fix Enrollment Tables According to User Specifications
-- This script renames tables and creates missing ones with exact field names

-- Step 1: Backup the old monolithic enrollments table
RENAME TABLE enrollments TO enrollments_old_backup;

-- Step 2: Rename enrollments_normalized to enrollments (user's specification)
RENAME TABLE enrollments_normalized TO enrollments;

-- Step 3: Create enrollment_academic_history table (user's exact specification)
DROP TABLE IF EXISTS enrollment_academic_history;
CREATE TABLE enrollment_academic_history (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT(11) UNSIGNED NOT NULL,
    previous_gwa DECIMAL(5,2) NULL,
    performance_level VARCHAR(50) NULL,
    last_grade_completed VARCHAR(20) NULL,
    last_school_year VARCHAR(20) NULL,
    last_school_attended VARCHAR(255) NULL,
    school_id VARCHAR(20) NULL,
    semester ENUM('1st', '2nd') NULL,
    track VARCHAR(100) NULL,
    strand VARCHAR(100) NULL,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_enrollment_id (enrollment_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Step 4: Create enrollment_documents table (user's exact specification)
DROP TABLE IF EXISTS enrollment_documents;
CREATE TABLE enrollment_documents (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT(11) UNSIGNED NOT NULL,
    document_type VARCHAR(100) NOT NULL COMMENT 'e.g., Birth Certificate, Report Card, ID',
    file_path VARCHAR(500) NOT NULL COMMENT 'Path to stored file or blob if stored in DB',
    uploaded_at DATETIME NULL,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_enrollment_id (enrollment_id),
    INDEX idx_document_type (document_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Step 5: Update enrollment_personal_info foreign key to point to enrollments
-- Drop existing foreign key constraint
ALTER TABLE enrollment_personal_info DROP FOREIGN KEY enrollment_personal_info_enrollment_id_foreign;

-- Add new foreign key constraint pointing to enrollments table
ALTER TABLE enrollment_personal_info 
ADD CONSTRAINT enrollment_personal_info_enrollment_id_foreign 
FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE ON UPDATE CASCADE;

-- Verify the final table structure
SELECT 'Tables created successfully!' as status;

-- Show final table list
SHOW TABLES LIKE '%enrollment%';