# Normalized Enrollment Database Schema Analysis

## Current Table Analysis

### Current `enrollments` Table Issues

The current `enrollments` table (39 fields) violates multiple normalization principles:

#### **1st Normal Form (1NF) Violations**
- **Non-atomic fields**: `documents_submitted` (JSON containing multiple document records)
- **Repeating groups**: Address information (current vs permanent), Parent information (father, mother, guardian)

#### **2nd Normal Form (2NF) Violations**
- **Partial dependencies**: Student personal information depends only on student, not enrollment
- **Mixed entities**: Academic history, personal details, and enrollment workflow in same table

#### **3rd Normal Form (3NF) Violations**
- **Transitive dependencies**: `age` depends on `date_of_birth`, not directly on enrollment
- **Derived data**: `performance_level` calculated from `previous_gwa`
- **Redundant data**: Address and parent information repeated for siblings

## Identified Data Groups

### 1. **Core Enrollment Data**
- `id`, `enrollment_number`, `school_year`, `grade_level`, `section`
- `enrollment_type`, `enrollment_status`, `created_at`, `updated_at`
- `approved_by`, `approved_at`, `declined_reason`

### 2. **Student Personal Information**
- `lrn`, `first_name`, `middle_name`, `last_name`, `extension_name`
- `date_of_birth`, `gender`, `mother_tongue`
- `birth_certificate_number`, `student_email`, `student_contact`

### 3. **Address Information (Repeating Group)**
- **Current Address**: `current_house_no`, `current_street`, `current_barangay`, `current_municipality`, `current_province`, `current_country`, `current_zip_code`
- **Permanent Address**: `permanent_house_street`, `permanent_street_name`, `permanent_barangay`, `permanent_municipality`, `permanent_province`, `permanent_country`, `permanent_zip_code`

### 4. **Parent/Guardian Information (Repeating Group)**
- **Father**: `father_first_name`, `father_middle_name`, `father_last_name`, `father_contact`, `father_email`
- **Mother**: `mother_first_name`, `mother_middle_name`, `mother_last_name`, `mother_contact`, `mother_email`
- **Guardian**: `guardian_first_name`, `guardian_middle_name`, `guardian_last_name`, `guardian_contact`, `guardian_email`

### 5. **Academic History**
- `previous_gwa`, `performance_level`, `last_grade_completed`
- `last_school_year`, `last_school_attended`, `school_id`
- `semester`, `track`, `strand`

### 6. **Special Categories**
- `indigenous_people`, `indigenous_community`
- `fourps_beneficiary`, `fourps_household_id`

### 7. **Documents (Non-atomic)**
- `documents_submitted` (JSON array)

### 8. **Derived/Calculated Fields**
- `age` (calculated from `date_of_birth`)
- `performance_level` (derived from `previous_gwa`)

## Proposed Normalized Schema (3NF)

### **1. Core Tables**

#### **`enrollments` (Core Enrollment Data)**
```sql
CREATE TABLE enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_number VARCHAR(50) UNIQUE NOT NULL,
    student_personal_id INT NOT NULL,
    school_year VARCHAR(20) NOT NULL,
    grade_level VARCHAR(20) NOT NULL,
    section VARCHAR(50),
    enrollment_type ENUM('new', 'returning', 'transferee', 'continuing') DEFAULT 'new',
    enrollment_status ENUM('pending', 'approved', 'declined', 'enrolled', 'transferred', 'dropped') DEFAULT 'pending',
    approved_by INT,
    approved_at DATETIME,
    declined_reason TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_personal_id) REFERENCES student_personal_info(id),
    FOREIGN KEY (approved_by) REFERENCES users(id),
    INDEX idx_enrollment_status (enrollment_status),
    INDEX idx_school_year (school_year),
    INDEX idx_grade_level (grade_level)
);
```

#### **`student_personal_info` (Student Personal Data)**
```sql
CREATE TABLE student_personal_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lrn VARCHAR(12) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    extension_name VARCHAR(20),
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    mother_tongue VARCHAR(100),
    birth_certificate_number VARCHAR(100),
    student_email VARCHAR(255),
    student_contact VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY uk_lrn (lrn),
    INDEX idx_last_name (last_name),
    INDEX idx_birth_date (date_of_birth)
);
```

### **2. Address Tables**

#### **`student_addresses` (Normalized Address Data)**
```sql
CREATE TABLE student_addresses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_personal_id INT NOT NULL,
    address_type ENUM('current', 'permanent') NOT NULL,
    house_no VARCHAR(50),
    street VARCHAR(255),
    barangay VARCHAR(100) NOT NULL,
    municipality VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    country VARCHAR(100) DEFAULT 'Philippines',
    zip_code VARCHAR(10),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_personal_id) REFERENCES student_personal_info(id) ON DELETE CASCADE,
    UNIQUE KEY uk_student_address_type (student_personal_id, address_type),
    INDEX idx_municipality (municipality),
    INDEX idx_province (province)
);
```

### **3. Family Information Tables**

#### **`student_parents_guardians` (Normalized Family Data)**
```sql
CREATE TABLE student_parents_guardians (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_personal_id INT NOT NULL,
    relationship_type ENUM('father', 'mother', 'guardian') NOT NULL,
    first_name VARCHAR(100),
    middle_name VARCHAR(100),
    last_name VARCHAR(100),
    contact_number VARCHAR(20),
    email VARCHAR(255),
    occupation VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_personal_id) REFERENCES student_personal_info(id) ON DELETE CASCADE,
    UNIQUE KEY uk_student_relationship (student_personal_id, relationship_type),
    INDEX idx_relationship_type (relationship_type)
);
```

### **4. Academic Information Tables**

#### **`student_academic_history` (Academic Background)**
```sql
CREATE TABLE student_academic_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_personal_id INT NOT NULL,
    previous_gwa DECIMAL(5,2),
    last_grade_completed VARCHAR(20),
    last_school_year VARCHAR(20),
    last_school_attended VARCHAR(255),
    previous_school_id VARCHAR(20),
    semester ENUM('1st', '2nd'),
    track VARCHAR(100),
    strand VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_personal_id) REFERENCES student_personal_info(id) ON DELETE CASCADE,
    INDEX idx_previous_gwa (previous_gwa),
    INDEX idx_last_school (last_school_attended)
);
```

### **5. Special Categories Tables**

#### **`student_special_categories` (Special Program Information)**
```sql
CREATE TABLE student_special_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_personal_id INT NOT NULL,
    indigenous_people ENUM('Yes', 'No') DEFAULT 'No',
    indigenous_community VARCHAR(255),
    fourps_beneficiary ENUM('Yes', 'No') DEFAULT 'No',
    fourps_household_id VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_personal_id) REFERENCES student_personal_info(id) ON DELETE CASCADE,
    INDEX idx_indigenous (indigenous_people),
    INDEX idx_fourps (fourps_beneficiary)
);
```

### **6. Document Management Tables**

#### **`enrollment_documents` (Document Tracking)**
```sql
CREATE TABLE enrollment_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_id INT NOT NULL,
    document_type VARCHAR(100) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    stored_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT,
    mime_type VARCHAR(100),
    upload_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    INDEX idx_enrollment_id (enrollment_id),
    INDEX idx_document_type (document_type),
    INDEX idx_upload_status (upload_status)
);
```

### **7. Disability Information Tables**

#### **`student_disabilities` (Special Needs)**
```sql
CREATE TABLE student_disabilities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_personal_id INT NOT NULL,
    disability_type VARCHAR(100) NOT NULL,
    severity_level ENUM('mild', 'moderate', 'severe'),
    support_needed TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_personal_id) REFERENCES student_personal_info(id) ON DELETE CASCADE,
    INDEX idx_student_id (student_personal_id),
    INDEX idx_disability_type (disability_type)
);
```

### **8. Audit and Logging Tables**

#### **`enrollment_audit_logs` (Action Tracking)**
```sql
CREATE TABLE enrollment_audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_id INT NOT NULL,
    action ENUM('submitted', 'approved', 'declined', 'updated', 'document_uploaded', 'status_changed') NOT NULL,
    performed_by INT,
    old_values JSON,
    new_values JSON,
    remarks TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    FOREIGN KEY (performed_by) REFERENCES users(id),
    INDEX idx_enrollment_id (enrollment_id),
    INDEX idx_action (action),
    INDEX idx_performed_by (performed_by),
    INDEX idx_created_at (created_at)
);
```

## Normalization Benefits

### **1st Normal Form (1NF) Compliance**
- ✅ **Atomic values**: Each field contains single, indivisible values
- ✅ **No repeating groups**: Address and parent data in separate tables
- ✅ **Unique rows**: Each table has proper primary keys

### **2nd Normal Form (2NF) Compliance**
- ✅ **Full functional dependency**: All non-key attributes depend on entire primary key
- ✅ **Separate entities**: Student personal info separate from enrollment workflow
- ✅ **No partial dependencies**: Each table focuses on single entity

### **3rd Normal Form (3NF) Compliance**
- ✅ **No transitive dependencies**: Removed calculated fields like `age`
- ✅ **Direct dependencies**: All attributes depend directly on primary key
- ✅ **Eliminated redundancy**: No duplicate data across tables

## Data Integrity Features

### **Referential Integrity**
- Foreign key constraints ensure data consistency
- Cascade deletes maintain referential integrity
- Unique constraints prevent duplicate records

### **Data Validation**
- ENUM constraints for controlled values
- NOT NULL constraints for required fields
- Unique constraints for business rules (LRN, enrollment numbers)

### **Performance Optimization**
- Strategic indexing on frequently queried fields
- Composite indexes for multi-column searches
- Optimized for enrollment workflow queries

## Integration with Student Table

### **Approved Enrollment Integration**

When an enrollment is approved:

1. **Create Student Record**:
```sql
INSERT INTO student (
    lrn, name, first_name, middle_name, last_name, extension_name,
    gender, date_of_birth, grade_level, section, 
    email, contact, address, guardian, guardian_contact,
    enrollment_number, enrollment_status, created_at
)
SELECT 
    spi.lrn,
    CONCAT(spi.first_name, ' ', COALESCE(spi.middle_name, ''), ' ', spi.last_name) as name,
    spi.first_name, spi.middle_name, spi.last_name, spi.extension_name,
    spi.gender, spi.date_of_birth,
    e.grade_level, e.section,
    spi.student_email, spi.student_contact,
    CONCAT(sa.house_no, ' ', sa.street, ', ', sa.barangay, ', ', sa.municipality, ', ', sa.province) as address,
    CONCAT(spg.first_name, ' ', spg.last_name) as guardian,
    spg.contact_number as guardian_contact,
    e.enrollment_number, 'active',
    NOW()
FROM enrollments e
JOIN student_personal_info spi ON e.student_personal_id = spi.id
LEFT JOIN student_addresses sa ON spi.id = sa.student_personal_id AND sa.address_type = 'current'
LEFT JOIN student_parents_guardians spg ON spi.id = spg.student_personal_id AND spg.relationship_type = 'father'
WHERE e.id = ?;
```

2. **Update Enrollment Status**:
```sql
UPDATE enrollments 
SET student_id = LAST_INSERT_ID(), 
    enrollment_status = 'approved',
    approved_by = ?,
    approved_at = NOW()
WHERE id = ?;
```

### **Query Examples**

#### **Get Complete Enrollment Information**
```sql
SELECT 
    e.enrollment_number, e.enrollment_status, e.grade_level,
    spi.lrn, spi.first_name, spi.middle_name, spi.last_name,
    spi.date_of_birth, spi.gender, spi.student_email,
    sa_current.house_no, sa_current.street, sa_current.barangay,
    sa_current.municipality, sa_current.province,
    spg_father.first_name as father_first_name,
    spg_father.last_name as father_last_name,
    spg_father.contact_number as father_contact,
    spg_mother.first_name as mother_first_name,
    spg_mother.last_name as mother_last_name,
    spg_mother.contact_number as mother_contact,
    sah.previous_gwa, sah.last_school_attended,
    ssc.indigenous_people, ssc.fourps_beneficiary
FROM enrollments e
JOIN student_personal_info spi ON e.student_personal_id = spi.id
LEFT JOIN student_addresses sa_current ON spi.id = sa_current.student_personal_id 
    AND sa_current.address_type = 'current'
LEFT JOIN student_parents_guardians spg_father ON spi.id = spg_father.student_personal_id 
    AND spg_father.relationship_type = 'father'
LEFT JOIN student_parents_guardians spg_mother ON spi.id = spg_mother.student_personal_id 
    AND spg_mother.relationship_type = 'mother'
LEFT JOIN student_academic_history sah ON spi.id = sah.student_personal_id
LEFT JOIN student_special_categories ssc ON spi.id = ssc.student_personal_id
WHERE e.id = ?;
```

#### **Find Students by Address**
```sql
SELECT DISTINCT spi.lrn, spi.first_name, spi.last_name, e.enrollment_number
FROM student_personal_info spi
JOIN enrollments e ON spi.id = e.student_personal_id
JOIN student_addresses sa ON spi.id = sa.student_personal_id
WHERE sa.municipality = ? AND sa.province = ?;
```

#### **Get Family Information**
```sql
SELECT 
    spi.lrn, spi.first_name, spi.last_name,
    spg.relationship_type, spg.first_name as parent_first_name,
    spg.last_name as parent_last_name, spg.contact_number
FROM student_personal_info spi
JOIN student_parents_guardians spg ON spi.id = spg.student_personal_id
WHERE spg.contact_number = ? OR spg.email = ?;
```

## Migration Strategy

### **Phase 1: Create Normalized Tables**
1. Create all new normalized tables
2. Add proper indexes and constraints
3. Test table structure and relationships

### **Phase 2: Data Migration**
1. Migrate existing enrollment data to normalized structure
2. Split current table data into appropriate tables
3. Validate data integrity after migration

### **Phase 3: Application Updates**
1. Update models to work with normalized schema
2. Modify queries to use JOIN operations
3. Update form processing logic

### **Phase 4: Performance Optimization**
1. Analyze query performance
2. Add additional indexes if needed
3. Optimize frequently used queries

### **Phase 5: Legacy Table Cleanup**
1. Backup original enrollments table
2. Drop original table after validation
3. Update documentation

## Advantages of Normalized Schema

### **Data Integrity**
- ✅ Eliminates data redundancy
- ✅ Prevents update anomalies
- ✅ Ensures referential integrity
- ✅ Reduces storage requirements

### **Maintainability**
- ✅ Easier to modify individual data types
- ✅ Cleaner separation of concerns
- ✅ Better support for future enhancements
- ✅ Improved data consistency

### **Performance**
- ✅ Faster updates (less data to modify)
- ✅ Better indexing strategies
- ✅ Reduced storage footprint
- ✅ Optimized for specific query patterns

### **Flexibility**
- ✅ Easy to add new address types
- ✅ Support for multiple guardians
- ✅ Extensible document management
- ✅ Scalable for future requirements

## Conclusion

The proposed normalized schema transforms the current 39-field monolithic table into a well-structured, 3NF-compliant database design that:

- **Eliminates redundancy** through proper normalization
- **Ensures data integrity** with foreign key constraints
- **Improves maintainability** with logical data separation
- **Enhances performance** through strategic indexing
- **Supports scalability** for future growth
- **Maintains compatibility** with existing enrollment workflow

This normalized approach provides a solid foundation for the enrollment system while following database design best practices and ensuring long-term maintainability.