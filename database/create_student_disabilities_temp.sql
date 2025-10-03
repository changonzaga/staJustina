-- Create student_disabilities_temp table
-- This table mirrors the structure of enrollment_disabilities_temp but for student records
-- Used during enrollment approval process to migrate disability data from enrollment to student

USE stajustina_db;

CREATE TABLE IF NOT EXISTS student_disabilities_temp (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT(11) UNSIGNED NOT NULL,
    has_disability ENUM('Yes','No') DEFAULT 'No',
    disability_type VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_student_disabilities_temp_student_id (student_id),
    CONSTRAINT fk_student_disabilities_temp_student_id 
        FOREIGN KEY (student_id) REFERENCES students(id) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verify table creation
DESCRIBE student_disabilities_temp;