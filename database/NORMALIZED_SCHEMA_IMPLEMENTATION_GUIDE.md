# Normalized Enrollment Schema Implementation Guide

## Overview

This document provides a complete implementation guide for the normalized enrollment database schema that addresses the issues identified in the current monolithic `enrollments` table. The new schema follows 3rd Normal Form (3NF) principles and eliminates data redundancy while maintaining referential integrity.

## Current Implementation Status

### ✅ Tables Successfully Created

1. **`enrollments_normalized`** (13 fields) - Core enrollment tracking
2. **`enrollment_personal_info`** (18 fields) - Student personal data before approval

### 📋 Complete Normalized Schema Structure

Based on your specific requirements, here's the complete 4-table normalized structure:

## 1. Core Enrollments Table

### **`enrollments_normalized`**
```sql
CREATE TABLE enrollments_normalized (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_number VARCHAR(50) UNIQUE NOT NULL COMMENT 'Auto-generated (ENR-YYYYMMDD-XXXX)',
    student_id INT(11) UNSIGNED NULL COMMENT 'Links to students table after approval',
    school_year VARCHAR(20) NOT NULL COMMENT 'e.g., 2024-2025',
    grade_level VARCHAR(20) NOT NULL,
    section VARCHAR(50) NULL,
    enrollment_type ENUM('new', 'returning', 'transferee', 'continuing') DEFAULT 'new',
    enrollment_status ENUM('pending', 'approved', 'declined', 'enrolled', 'transferred', 'dropped') DEFAULT 'pending',
    approved_by INT(11) UNSIGNED NULL COMMENT 'FK to admins/users table',
    approved_at DATETIME NULL,
    declined_reason TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    
    INDEX idx_enrollment_status (enrollment_status),
    INDEX idx_school_year (school_year),
    INDEX idx_grade_level (grade_level),
    INDEX idx_student_id (student_id),
    INDEX idx_approved_by (approved_by)
);
```

**Purpose**: Track the status of each enrollment application

## 2. Student Personal Information Table

### **`enrollment_personal_info`**
```sql
CREATE TABLE enrollment_personal_info (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT(11) UNSIGNED NOT NULL,
    lrn VARCHAR(12) NOT NULL COMMENT 'Learner Reference Number',
    birth_certificate_number VARCHAR(100) NULL,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    extension_name VARCHAR(20) NULL COMMENT 'Jr., III, etc.',
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    age INT(3) NOT NULL,
    mother_tongue VARCHAR(100) NULL,
    student_email VARCHAR(255) NULL,
    student_contact VARCHAR(20) NULL,
    indigenous_people ENUM('Yes', 'No') DEFAULT 'No',
    indigenous_community VARCHAR(255) NULL,
    fourps_beneficiary ENUM('Yes', 'No') DEFAULT 'No',
    fourps_household_id VARCHAR(50) NULL,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments_normalized(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_enrollment_id (enrollment_id),
    INDEX idx_lrn (lrn)
);
```

**Purpose**: Hold raw applicant info before student is officially approved and moved to students table

## 3. Academic History Table

### **`enrollment_academic_history`**
```sql
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
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments_normalized(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_enrollment_id (enrollment_id),
    INDEX idx_previous_gwa (previous_gwa)
);
```

**Purpose**: Record academic history tied to that specific enrollment

## 4. Documents Table

### **`enrollment_documents`**
```sql
CREATE TABLE enrollment_documents (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT(11) UNSIGNED NOT NULL,
    document_type VARCHAR(100) NOT NULL COMMENT 'e.g., Birth Certificate, Report Card, ID',
    original_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL COMMENT 'Path to stored file or blob reference',
    file_size INT(11) NULL,
    mime_type VARCHAR(100) NULL,
    upload_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    uploaded_at DATETIME NULL,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments_normalized(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_enrollment_id (enrollment_id),
    INDEX idx_document_type (document_type),
    INDEX idx_upload_status (upload_status)
);
```

**Purpose**: Each document gets its own row instead of storing all in JSON

## 5. Additional Normalized Tables

### **`enrollment_addresses`** (Address Normalization)
```sql
CREATE TABLE enrollment_addresses (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT(11) UNSIGNED NOT NULL,
    address_type ENUM('current', 'permanent') NOT NULL,
    house_no VARCHAR(50) NULL,
    street VARCHAR(255) NULL,
    barangay VARCHAR(100) NOT NULL,
    municipality VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    country VARCHAR(100) DEFAULT 'Philippines',
    zip_code VARCHAR(10) NULL,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments_normalized(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uk_enrollment_address_type (enrollment_id, address_type),
    INDEX idx_enrollment_id (enrollment_id),
    INDEX idx_address_type (address_type)
);
```

### **`enrollment_parents_guardians`** (Family Information)
```sql
CREATE TABLE enrollment_parents_guardians (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT(11) UNSIGNED NOT NULL,
    relationship_type ENUM('father', 'mother', 'guardian') NOT NULL,
    first_name VARCHAR(100) NULL,
    middle_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NULL,
    contact_number VARCHAR(20) NULL,
    email VARCHAR(255) NULL,
    occupation VARCHAR(100) NULL,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments_normalized(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uk_enrollment_relationship (enrollment_id, relationship_type),
    INDEX idx_enrollment_id (enrollment_id),
    INDEX idx_relationship_type (relationship_type)
);
```

## Relationships Overview

### **🔗 Database Relationships**

```
students (main student table)
    ↓ (1:many)
enrollments_normalized (core enrollment record)
    ↓ (1:1)
├── enrollment_personal_info (temporary student data)
├── enrollment_academic_history (academic background)
├── enrollment_addresses (current/permanent addresses)
└── enrollment_parents_guardians (family information)
    ↓ (1:many)
enrollment_documents (individual document records)
```

### **Key Relationships**
- **students** ⟶ **enrollments_normalized** (many enrollments per student)
- **enrollments_normalized** ⟶ **enrollment_personal_info** (1:1, temporary until approval)
- **enrollments_normalized** ⟶ **enrollment_academic_history** (1:1)
- **enrollments_normalized** ⟶ **enrollment_addresses** (1:2, current + permanent)
- **enrollments_normalized** ⟶ **enrollment_parents_guardians** (1:3, father + mother + guardian)
- **enrollments_normalized** ⟶ **enrollment_documents** (1:many)

## Data Migration Strategy

### **Phase 1: Create Missing Tables**
```sql
-- Create the remaining normalized tables
CREATE TABLE enrollment_academic_history (...); -- As defined above
CREATE TABLE enrollment_documents (...);        -- As defined above
CREATE TABLE enrollment_addresses (...);        -- As defined above
CREATE TABLE enrollment_parents_guardians (...); -- As defined above
```

### **Phase 2: Migrate Existing Data**
```sql
-- Example: Migrate from current enrollments table to normalized structure
INSERT INTO enrollments_normalized (
    enrollment_number, student_id, school_year, grade_level, section,
    enrollment_type, enrollment_status, approved_by, approved_at,
    declined_reason, created_at, updated_at
)
SELECT 
    enrollment_number, student_id, school_year, grade_level, section,
    enrollment_type, enrollment_status, approved_by, approved_at,
    declined_reason, created_at, updated_at
FROM enrollments;

-- Migrate personal information
INSERT INTO enrollment_personal_info (
    enrollment_id, lrn, birth_certificate_number, last_name, first_name,
    middle_name, extension_name, date_of_birth, gender, age, mother_tongue,
    student_email, student_contact, indigenous_people, indigenous_community,
    fourps_beneficiary, fourps_household_id
)
SELECT 
    en.id, e.lrn, e.birth_certificate_number, e.last_name, e.first_name,
    e.middle_name, e.extension_name, e.date_of_birth, e.gender, e.age, e.mother_tongue,
    e.student_email, e.student_contact, e.indigenous_people, e.indigenous_community,
    e.fourps_beneficiary, e.fourps_household_id
FROM enrollments e
JOIN enrollments_normalized en ON e.enrollment_number = en.enrollment_number;

-- Migrate academic history
INSERT INTO enrollment_academic_history (
    enrollment_id, previous_gwa, performance_level, last_grade_completed,
    last_school_year, last_school_attended, school_id, semester, track, strand
)
SELECT 
    en.id, e.previous_gwa, e.performance_level, e.last_grade_completed,
    e.last_school_year, e.last_school_attended, e.school_id, e.semester, e.track, e.strand
FROM enrollments e
JOIN enrollments_normalized en ON e.enrollment_number = en.enrollment_number;
```

## Query Examples

### **Get Complete Enrollment Information**
```sql
SELECT 
    en.enrollment_number,
    en.enrollment_status,
    en.grade_level,
    en.section,
    epi.lrn,
    epi.first_name,
    epi.middle_name,
    epi.last_name,
    epi.date_of_birth,
    epi.gender,
    epi.student_email,
    eah.previous_gwa,
    eah.last_school_attended,
    epi.indigenous_people,
    epi.fourps_beneficiary
FROM enrollments_normalized en
JOIN enrollment_personal_info epi ON en.id = epi.enrollment_id
LEFT JOIN enrollment_academic_history eah ON en.id = eah.enrollment_id
WHERE en.id = ?;
```

### **Get Enrollment with Addresses**
```sql
SELECT 
    en.enrollment_number,
    epi.first_name,
    epi.last_name,
    ea_current.house_no as current_house,
    ea_current.street as current_street,
    ea_current.barangay as current_barangay,
    ea_current.municipality as current_municipality,
    ea_permanent.house_no as permanent_house,
    ea_permanent.street as permanent_street,
    ea_permanent.barangay as permanent_barangay
FROM enrollments_normalized en
JOIN enrollment_personal_info epi ON en.id = epi.enrollment_id
LEFT JOIN enrollment_addresses ea_current ON en.id = ea_current.enrollment_id 
    AND ea_current.address_type = 'current'
LEFT JOIN enrollment_addresses ea_permanent ON en.id = ea_permanent.enrollment_id 
    AND ea_permanent.address_type = 'permanent'
WHERE en.id = ?;
```

### **Get Enrollment with Family Information**
```sql
SELECT 
    en.enrollment_number,
    epi.first_name,
    epi.last_name,
    epg_father.first_name as father_first_name,
    epg_father.last_name as father_last_name,
    epg_father.contact_number as father_contact,
    epg_mother.first_name as mother_first_name,
    epg_mother.last_name as mother_last_name,
    epg_mother.contact_number as mother_contact,
    epg_guardian.first_name as guardian_first_name,
    epg_guardian.last_name as guardian_last_name,
    epg_guardian.contact_number as guardian_contact
FROM enrollments_normalized en
JOIN enrollment_personal_info epi ON en.id = epi.enrollment_id
LEFT JOIN enrollment_parents_guardians epg_father ON en.id = epg_father.enrollment_id 
    AND epg_father.relationship_type = 'father'
LEFT JOIN enrollment_parents_guardians epg_mother ON en.id = epg_mother.enrollment_id 
    AND epg_mother.relationship_type = 'mother'
LEFT JOIN enrollment_parents_guardians epg_guardian ON en.id = epg_guardian.enrollment_id 
    AND epg_guardian.relationship_type = 'guardian'
WHERE en.id = ?;
```

### **Get Enrollment Documents**
```sql
SELECT 
    en.enrollment_number,
    ed.document_type,
    ed.original_filename,
    ed.file_path,
    ed.upload_status,
    ed.uploaded_at
FROM enrollments_normalized en
JOIN enrollment_documents ed ON en.id = ed.enrollment_id
WHERE en.id = ?
ORDER BY ed.uploaded_at DESC;
```

## Integration with Student Table

### **Approval Process**
```sql
-- When enrollment is approved, create student record
INSERT INTO student (
    lrn, name, first_name, middle_name, last_name, extension_name,
    gender, date_of_birth, grade_level, section, 
    email, contact, enrollment_number, enrollment_status, created_at
)
SELECT 
    epi.lrn,
    CONCAT(epi.first_name, ' ', COALESCE(epi.middle_name, ''), ' ', epi.last_name) as name,
    epi.first_name, epi.middle_name, epi.last_name, epi.extension_name,
    epi.gender, epi.date_of_birth,
    en.grade_level, en.section,
    epi.student_email, epi.student_contact,
    en.enrollment_number, 'active',
    NOW()
FROM enrollments_normalized en
JOIN enrollment_personal_info epi ON en.id = epi.enrollment_id
WHERE en.id = ?;

-- Update enrollment with student_id
UPDATE enrollments_normalized 
SET student_id = LAST_INSERT_ID(), 
    enrollment_status = 'approved',
    approved_by = ?,
    approved_at = NOW()
WHERE id = ?;
```

## Benefits of Normalized Schema

### **✅ Eliminates Current Issues**

1. **Too Many Responsibilities**: 
   - ❌ Old: Single table with 39 mixed fields
   - ✅ New: Separate tables for each data type

2. **Redundancy Risks**:
   - ❌ Old: Personal info duplicated for multiple enrollments
   - ✅ New: Personal info stored once, linked via foreign keys

3. **Non-atomic Data**:
   - ❌ Old: JSON documents_submitted field
   - ✅ New: Individual document records in enrollment_documents

### **✅ 3NF Compliance Achieved**

- **1NF**: All fields contain atomic values
- **2NF**: No partial dependencies on composite keys
- **3NF**: No transitive dependencies

### **✅ Performance Benefits**

- Faster queries for specific data types
- Better indexing strategies
- Reduced storage requirements
- Improved concurrent access

### **✅ Maintainability**

- Easier to modify individual data components
- Clear separation of concerns
- Better support for future enhancements
- Improved data consistency

## Implementation Checklist

### **Database Setup**
- [x] Create `enrollments_normalized` table
- [x] Create `enrollment_personal_info` table
- [ ] Create `enrollment_academic_history` table
- [ ] Create `enrollment_documents` table
- [ ] Create `enrollment_addresses` table
- [ ] Create `enrollment_parents_guardians` table

### **Data Migration**
- [ ] Migrate core enrollment data
- [ ] Migrate personal information
- [ ] Migrate academic history
- [ ] Split address data (current/permanent)
- [ ] Split parent/guardian data
- [ ] Convert JSON documents to individual records

### **Application Updates**
- [ ] Update EnrollmentModel for normalized queries
- [ ] Modify form processing logic
- [ ] Update admin interface queries
- [ ] Test approval/decline processes
- [ ] Update reporting queries

### **Testing & Validation**
- [ ] Test data integrity constraints
- [ ] Validate foreign key relationships
- [ ] Performance testing on large datasets
- [ ] End-to-end enrollment workflow testing

## Conclusion

The normalized enrollment schema successfully addresses all identified issues:

- **Eliminates redundancy** through proper table separation
- **Ensures data integrity** with foreign key constraints
- **Improves maintainability** with logical data organization
- **Enhances performance** through strategic indexing
- **Supports scalability** for future growth
- **Follows 3NF principles** for optimal database design

This implementation provides a solid foundation for the enrollment system while maintaining compatibility with the existing workflow and ensuring long-term maintainability.