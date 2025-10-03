-- =====================================================
-- STUDENT MANAGEMENT DATABASE SCHEMA (SIMPLIFIED)
-- Normalized structure for managing students with
-- enrollment integration and notification support
-- =====================================================

-- Drop existing tables if they exist (in reverse dependency order)
DROP TABLE IF EXISTS student_notifications;
DROP TABLE IF EXISTS student_emergency_contacts;
DROP TABLE IF EXISTS student_address;
DROP TABLE IF EXISTS student_family_info;
DROP TABLE IF EXISTS student_personal_info;
DROP TABLE IF EXISTS student_auth;
DROP TABLE IF EXISTS students;

-- =====================================================
-- 1. CORE STUDENTS TABLE
-- Central table with unique identifiers and status
-- =====================================================
CREATE TABLE students (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    student_account_number VARCHAR(20) NOT NULL UNIQUE COMMENT 'Auto-generated unique account number',
    lrn VARCHAR(12) NOT NULL UNIQUE COMMENT 'Learner Reference Number',
    enrollment_id INT(11) UNSIGNED NULL COMMENT 'Reference to original enrollment record',
    student_status ENUM('active', 'inactive', 'suspended', 'graduated', 'transferred', 'dropped') NOT NULL DEFAULT 'active',
    enrollment_date DATE NOT NULL COMMENT 'Date when student was enrolled/approved',
    grade_level VARCHAR(50) NOT NULL,
    section VARCHAR(50) NULL,
    academic_year VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    INDEX idx_student_account (student_account_number),
    INDEX idx_lrn (lrn),
    INDEX idx_status (student_status),
    INDEX idx_enrollment_id (enrollment_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Core student records with unique identifiers';

-- =====================================================
-- 2. STUDENT AUTHENTICATION TABLE
-- Login credentials and authentication data
-- =====================================================
CREATE TABLE student_auth (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT(11) UNSIGNED NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE COMMENT 'Login username (usually email or account number)',
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL COMMENT 'Hashed password',
    password_salt VARCHAR(100) NULL COMMENT 'Password salt for additional security',
    temp_password VARCHAR(100) NULL COMMENT 'Temporary password for first login',
    password_reset_token VARCHAR(255) NULL,
    password_reset_expires DATETIME NULL,
    last_login DATETIME NULL,
    login_attempts INT(3) DEFAULT 0,
    account_locked_until DATETIME NULL,
    email_verified BOOLEAN DEFAULT FALSE,
    email_verification_token VARCHAR(255) NULL,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    two_factor_secret VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_student_auth (student_id),
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_password_reset (password_reset_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student authentication and login credentials';

-- =====================================================
-- 3. STUDENT PERSONAL INFORMATION TABLE
-- Personal details and demographic information
-- =====================================================
CREATE TABLE student_personal_info (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT(11) UNSIGNED NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NOT NULL,
    suffix VARCHAR(20) NULL COMMENT 'Jr., Sr., III, etc.',
    preferred_name VARCHAR(100) NULL,
    date_of_birth DATE NOT NULL,
    place_of_birth VARCHAR(200) NULL,
    gender ENUM('Male', 'Female', 'Other', 'Prefer not to say') NOT NULL,
    civil_status ENUM('Single', 'Married', 'Widowed', 'Separated', 'Divorced') DEFAULT 'Single',
    nationality VARCHAR(100) DEFAULT 'Filipino',
    citizenship VARCHAR(100) DEFAULT 'Filipino',
    religion VARCHAR(100) NULL,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NULL,
    height_cm DECIMAL(5,2) NULL COMMENT 'Height in centimeters',
    weight_kg DECIMAL(5,2) NULL COMMENT 'Weight in kilograms',
    profile_picture VARCHAR(255) NULL,
    special_needs TEXT NULL COMMENT 'Special educational needs or disabilities',
    medical_conditions TEXT NULL COMMENT 'Known medical conditions or allergies',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_student_personal (student_id),
    INDEX idx_full_name (last_name, first_name),
    INDEX idx_birth_date (date_of_birth),
    INDEX idx_gender (gender)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student personal and demographic information';

-- =====================================================
-- 4. STUDENT FAMILY INFORMATION TABLE
-- Parent/Guardian details and relationships
-- =====================================================
CREATE TABLE student_family_info (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT(11) UNSIGNED NOT NULL,
    relationship_type ENUM('Father', 'Mother', 'Guardian', 'Stepfather', 'Stepmother', 'Grandparent', 'Sibling', 'Other') NOT NULL,
    is_primary_contact BOOLEAN DEFAULT FALSE COMMENT 'Primary contact for school communications',
    title VARCHAR(20) NULL COMMENT 'Mr., Mrs., Ms., Dr., etc.',
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NOT NULL,
    suffix VARCHAR(20) NULL,
    date_of_birth DATE NULL,
    occupation VARCHAR(200) NULL,
    employer VARCHAR(200) NULL,
    work_address TEXT NULL,
    monthly_income DECIMAL(12,2) NULL,
    educational_attainment VARCHAR(100) NULL,
    phone_primary VARCHAR(20) NOT NULL,
    phone_secondary VARCHAR(20) NULL,
    email VARCHAR(100) NULL,
    facebook_account VARCHAR(200) NULL,
    other_social_media TEXT NULL,
    living_with_student BOOLEAN DEFAULT TRUE,
    custody_rights BOOLEAN DEFAULT TRUE COMMENT 'Has legal custody/guardianship rights',
    authorized_pickup BOOLEAN DEFAULT TRUE COMMENT 'Authorized to pick up student',
    emergency_contact BOOLEAN DEFAULT TRUE COMMENT 'Can be contacted in emergencies',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_student_family (student_id),
    INDEX idx_relationship (relationship_type),
    INDEX idx_primary_contact (is_primary_contact),
    INDEX idx_phone (phone_primary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student family members and guardian information';

-- =====================================================
-- 5. STUDENT ADDRESS TABLE
-- Current and permanent address information
-- =====================================================
CREATE TABLE student_address (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT(11) UNSIGNED NOT NULL,
    address_type ENUM('current', 'permanent', 'mailing', 'emergency') NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE COMMENT 'Primary address for official correspondence',
    house_number VARCHAR(50) NULL,
    street VARCHAR(200) NULL,
    subdivision_village VARCHAR(200) NULL,
    barangay VARCHAR(200) NOT NULL,
    municipality_city VARCHAR(200) NOT NULL,
    province VARCHAR(200) NOT NULL,
    region VARCHAR(200) NOT NULL,
    postal_code VARCHAR(10) NULL,
    country VARCHAR(100) DEFAULT 'Philippines',
    landmark VARCHAR(500) NULL COMMENT 'Nearby landmarks for easier location',
    coordinates_lat DECIMAL(10, 8) NULL COMMENT 'GPS latitude',
    coordinates_lng DECIMAL(11, 8) NULL COMMENT 'GPS longitude',
    same_as_permanent BOOLEAN DEFAULT FALSE COMMENT 'Same as permanent address',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_student_address (student_id),
    INDEX idx_address_type (address_type),
    INDEX idx_location (municipality_city, province),
    INDEX idx_primary (is_primary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student address information (current, permanent, etc.)';

-- =====================================================
-- 6. STUDENT EMERGENCY CONTACTS TABLE
-- Emergency contact persons and details
-- =====================================================
CREATE TABLE student_emergency_contacts (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT(11) UNSIGNED NOT NULL,
    priority_order INT(2) NOT NULL DEFAULT 1 COMMENT 'Contact priority (1=first, 2=second, etc.)',
    relationship VARCHAR(100) NOT NULL COMMENT 'Relationship to student',
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NOT NULL,
    phone_primary VARCHAR(20) NOT NULL,
    phone_secondary VARCHAR(20) NULL,
    email VARCHAR(100) NULL,
    address TEXT NULL,
    workplace VARCHAR(200) NULL,
    work_phone VARCHAR(20) NULL,
    notes TEXT NULL COMMENT 'Additional notes about this contact',
    is_family_member BOOLEAN DEFAULT FALSE COMMENT 'Is this person also in family_info table',
    authorized_pickup BOOLEAN DEFAULT FALSE COMMENT 'Authorized to pick up student',
    medical_authorization BOOLEAN DEFAULT FALSE COMMENT 'Can authorize medical treatment',
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_student_emergency (student_id),
    INDEX idx_priority (priority_order),
    INDEX idx_phone (phone_primary),
    INDEX idx_active (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Emergency contact persons for students';

-- =====================================================
-- 7. STUDENT NOTIFICATIONS TABLE
-- Track notification history and preferences
-- =====================================================
CREATE TABLE student_notifications (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT(11) UNSIGNED NOT NULL,
    notification_type ENUM('enrollment_approval', 'account_created', 'password_reset', 'grade_update', 'attendance_alert', 'general_announcement', 'payment_reminder', 'other') NOT NULL,
    delivery_method ENUM('email', 'sms', 'both') NOT NULL,
    recipient_email VARCHAR(100) NULL,
    recipient_phone VARCHAR(20) NULL,
    subject VARCHAR(500) NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'sent', 'delivered', 'failed', 'bounced') DEFAULT 'pending',
    sent_at DATETIME NULL,
    delivered_at DATETIME NULL,
    error_message TEXT NULL,
    retry_count INT(2) DEFAULT 0,
    max_retries INT(2) DEFAULT 3,
    priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
    reference_id VARCHAR(100) NULL COMMENT 'External reference (email ID, SMS ID, etc.)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_student_notifications (student_id),
    INDEX idx_type (notification_type),
    INDEX idx_status (status),
    INDEX idx_delivery_method (delivery_method),
    INDEX idx_sent_date (sent_at),
    INDEX idx_priority (priority)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student notification history and delivery tracking';

-- =====================================================
-- ADDITIONAL INDEXES FOR PERFORMANCE OPTIMIZATION
-- =====================================================

-- Additional composite indexes for common queries
CREATE INDEX idx_student_grade_section ON students(grade_level, section);
CREATE INDEX idx_student_status_year ON students(student_status, academic_year);
CREATE INDEX idx_personal_name_search ON student_personal_info(last_name, first_name, middle_name);
CREATE INDEX idx_family_primary_phone ON student_family_info(is_primary_contact, phone_primary);
CREATE INDEX idx_address_primary_type ON student_address(is_primary, address_type);
CREATE INDEX idx_emergency_priority_active ON student_emergency_contacts(priority_order, active);
CREATE INDEX idx_notifications_pending ON student_notifications(status, created_at);

-- =====================================================
-- VIEWS FOR COMMON QUERIES
-- =====================================================

-- Complete student information view
CREATE VIEW view_student_complete AS
SELECT 
    s.id,
    s.student_account_number,
    s.lrn,
    s.student_status,
    s.grade_level,
    s.section,
    s.academic_year,
    s.enrollment_date,
    
    -- Personal Info
    CONCAT(spi.first_name, ' ', COALESCE(spi.middle_name, ''), ' ', spi.last_name, ' ', COALESCE(spi.suffix, '')) AS full_name,
    spi.first_name,
    spi.middle_name,
    spi.last_name,
    spi.date_of_birth,
    spi.gender,
    spi.nationality,
    spi.religion,
    
    -- Auth Info
    sa.email,
    sa.username,
    sa.email_verified,
    sa.last_login,
    
    -- Primary Family Contact
    CONCAT(sfi.first_name, ' ', sfi.last_name) AS primary_contact_name,
    sfi.phone_primary AS primary_contact_phone,
    sfi.email AS primary_contact_email,
    sfi.relationship_type AS primary_contact_relationship,
    
    -- Primary Address
    CONCAT(
        COALESCE(CONCAT(sad.house_number, ' '), ''),
        COALESCE(CONCAT(sad.street, ', '), ''),
        COALESCE(CONCAT(sad.subdivision_village, ', '), ''),
        sad.barangay, ', ',
        sad.municipality_city, ', ',
        sad.province
    ) AS primary_address,
    
    s.created_at,
    s.updated_at
    
FROM students s
LEFT JOIN student_personal_info spi ON s.id = spi.student_id
LEFT JOIN student_auth sa ON s.id = sa.student_id
LEFT JOIN student_family_info sfi ON s.id = sfi.student_id AND sfi.is_primary_contact = TRUE
LEFT JOIN student_address sad ON s.id = sad.student_id AND sad.is_primary = TRUE;

-- Active students with contact info view
CREATE VIEW view_active_students_contacts AS
SELECT 
    s.id,
    s.student_account_number,
    s.lrn,
    CONCAT(spi.first_name, ' ', spi.last_name) AS student_name,
    s.grade_level,
    s.section,
    sa.email AS student_email,
    sfi.phone_primary AS family_phone,
    sfi.email AS family_email,
    CONCAT(sfi.first_name, ' ', sfi.last_name) AS contact_person
FROM students s
JOIN student_personal_info spi ON s.id = spi.student_id
JOIN student_auth sa ON s.id = sa.student_id
JOIN student_family_info sfi ON s.id = sfi.student_id AND sfi.is_primary_contact = TRUE
WHERE s.student_status = 'active';

-- =====================================================
-- SCHEMA CREATION COMPLETE
-- =====================================================

SELECT 'Student Management Schema Created Successfully!' AS status;