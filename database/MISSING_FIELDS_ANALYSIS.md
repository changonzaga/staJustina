# Missing Fields Analysis - Enrollment Form Steps 2 & 3

## Current Schema Gap Analysis

After analyzing the enrollment form structure and comparing it with the current normalized schema, several critical data fields from Steps 2 and 3 are missing from the database.

## Missing Fields from Step 2 (Address & Family Information)

### ❌ Address Information (Not in Current Schema)
**Current Address Fields:**
- `current_house_no`
- `current_street`
- `current_barangay`
- `current_municipality`
- `current_province`
- `current_country`
- `current_zip_code`

**Permanent Address Fields:**
- `permanent_house_street`
- `permanent_street_name`
- `permanent_barangay`
- `permanent_municipality`
- `permanent_province`
- `permanent_country`
- `permanent_zip_code`
- `same_as_current` (checkbox indicator)

### ❌ Family Information (Not in Current Schema)
**Father Information:**
- `father_last_name`
- `father_first_name`
- `father_middle_name`
- `father_contact`

**Mother Information:**
- `mother_last_name`
- `mother_first_name`
- `mother_middle_name`
- `mother_contact`

**Guardian Information:**
- `guardian_last_name`
- `guardian_first_name`
- `guardian_middle_name`
- `guardian_contact`

## Missing Fields from Step 3 (Academic & Special Needs)

### ❌ Disability Information (Not in Current Schema)
- `has_disability` (Yes/No)
- `disability_types[]` (Multiple checkboxes):
  - Visual Impairment
  - Hearing Impairment
  - Learning Disability
  - Intellectual Disability
  - Blind
  - Autism Spectrum Disorder
  - Emotional-Behavioral Disorder
  - Orthopedic/Physical Handicap
  - Multiple Disorder
  - Speech/Language Disorder
  - Cerebral Palsy
  - Special Health Problem/Chronic Disease
  - Cancer

### ✅ Academic History (Partially Covered)
**Fields in Current Schema:**
- `previous_gwa` ✅
- `performance_level` ✅
- `last_grade_completed` ✅
- `last_school_year` ✅
- `last_school_attended` ✅
- `school_id` ✅
- `semester` ✅
- `track` ✅
- `strand` ✅

## Senior High School (SHS) Specific Requirements

### ✅ SHS Fields (Already in Academic History Schema)
- `track` - Academic, TVL, Sports, Arts
- `strand` - STEM, ABM, HUMSS, etc.
- `semester` - 1st, 2nd
- `school_id` - Previous school identifier

### ❌ Additional SHS Requirements (Missing)
- **Track-Specific Specializations**
- **Strand Prerequisites**
- **SHS Grade Level Mapping** (Grade 11, Grade 12)
- **Subject Preferences**
- **Career Pathway Indicators**

## Proposed Additional Normalized Tables

### 1. `enrollment_addresses` Table
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
    is_same_as_current BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    UNIQUE KEY uk_enrollment_address_type (enrollment_id, address_type),
    INDEX idx_enrollment_id (enrollment_id)
);
```

### 2. `enrollment_family_info` Table
```sql
CREATE TABLE enrollment_family_info (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT(11) UNSIGNED NOT NULL,
    relationship_type ENUM('father', 'mother', 'guardian') NOT NULL,
    first_name VARCHAR(100) NULL,
    middle_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NULL,
    contact_number VARCHAR(20) NULL,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    UNIQUE KEY uk_enrollment_relationship (enrollment_id, relationship_type),
    INDEX idx_enrollment_id (enrollment_id)
);
```

### 3. `enrollment_disabilities` Table
```sql
CREATE TABLE enrollment_disabilities (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT(11) UNSIGNED NOT NULL,
    has_disability ENUM('Yes', 'No') DEFAULT 'No',
    disability_type VARCHAR(100) NULL,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    INDEX idx_enrollment_id (enrollment_id),
    INDEX idx_disability_type (disability_type)
);
```

### 4. `shs_enrollment_details` Table (SHS-Specific)
```sql
CREATE TABLE shs_enrollment_details (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT(11) UNSIGNED NOT NULL,
    track VARCHAR(50) NOT NULL COMMENT 'Academic, TVL, Sports, Arts',
    strand VARCHAR(50) NOT NULL COMMENT 'STEM, ABM, HUMSS, etc.',
    specialization VARCHAR(100) NULL,
    career_pathway VARCHAR(100) NULL,
    subject_preferences JSON NULL,
    prerequisites_met BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    INDEX idx_enrollment_id (enrollment_id),
    INDEX idx_track_strand (track, strand)
);
```

## Data Integrity & Relationships

### Foreign Key Relationships
```
enrollments (1) ⟶ enrollment_addresses (2) [current + permanent]
enrollments (1) ⟶ enrollment_family_info (3) [father + mother + guardian]
enrollments (1) ⟶ enrollment_disabilities (many) [multiple disabilities]
enrollments (1) ⟶ shs_enrollment_details (1) [SHS students only]
```

### Normalization Benefits
1. **Eliminates Redundancy**: Address and family data stored once per enrollment
2. **Supports Multiple Values**: Multiple disabilities per student
3. **SHS Specialization**: Dedicated structure for Senior High School requirements
4. **Data Integrity**: Foreign key constraints ensure consistency
5. **Query Efficiency**: Indexed fields for common searches

## Implementation Priority

### High Priority (Critical Missing Data)
1. ✅ `enrollment_addresses` - Address information
2. ✅ `enrollment_family_info` - Parent/guardian contacts
3. ✅ `enrollment_disabilities` - Special needs tracking

### Medium Priority (SHS Enhancement)
4. ✅ `shs_enrollment_details` - Senior High School specifics

### Low Priority (Future Enhancements)
5. Track-specific curriculum mapping
6. Subject prerequisite validation
7. Career pathway recommendations

## Migration Strategy

1. **Create Missing Tables**: Implement the 4 additional normalized tables
2. **Data Migration**: Move existing data from monolithic structure
3. **Form Integration**: Update enrollment forms to populate new tables
4. **Query Updates**: Modify application queries to use JOINs
5. **Validation**: Ensure data integrity across all relationships

This analysis shows that significant enrollment data from Steps 2 and 3 is currently not being captured in the database, requiring immediate implementation of additional normalized tables.