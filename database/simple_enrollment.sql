USE stajustina_db;

CREATE TABLE enrollment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_number VARCHAR(50) UNIQUE,
    student_id INT NULL,
    school_year VARCHAR(20),
    grade_level VARCHAR(20),
    enrollment_status ENUM('pending','approved','declined') DEFAULT 'pending',
    enrollment_type ENUM('new','transferee','continuing') DEFAULT 'new',
    form_data JSON,
    enrollment_date DATE,
    approved_by INT NULL,
    approved_at TIMESTAMP NULL,
    declined_reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO enrollment (enrollment_number, school_year, grade_level, enrollment_status, enrollment_type, form_data, enrollment_date) VALUES 
('ENR-20241120-0001', '2024-2025', 'Grade 7', 'pending', 'new', 
 JSON_OBJECT('first_name', 'John', 'last_name', 'Doe', 'grade_level', 'Grade 7', 'gender', 'Male', 'age', 13), 
 '2024-11-20'),
('ENR-20241120-0002', '2024-2025', 'Grade 8', 'pending', 'transferee', 
 JSON_OBJECT('first_name', 'Maria', 'last_name', 'Santos', 'grade_level', 'Grade 8', 'gender', 'Female', 'age', 14), 
 '2024-11-20');

SELECT 'Enrollment table created successfully!' AS message;
SELECT * FROM enrollment;