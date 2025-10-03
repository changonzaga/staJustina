# Complete Enrollment Schema Documentation

## ✅ Implementation Complete

All missing enrollment data from Steps 2 and 3 of the multi-step enrollment form have been successfully captured in properly normalized database tables. The schema now fully supports the complete enrollment workflow including Senior High School (SHS) specific requirements.

## 📊 Complete Normalized Schema Overview

### Core Enrollment Tables (9 Tables Total)

| # | Table Name | Fields | Purpose | Form Step |
|---|------------|--------|---------|----------|
| 1 | `enrollments` | 13 | Core Application Record | All Steps |
| 2 | `enrollment_personal_info` | 18 | Student Data Before Approval | Step 1 |
| 3 | `enrollment_academic_history_new` | 11 | Academic Background | Step 3 |
| 4 | `enrollment_documents` | 5 | Document Storage | Step 4 |
| 5 | `student_addresses` | 11 | Address Information | **Step 2** |
| 6 | `student_family_info` | 7 | Family Information | **Step 2** |
| 7 | `student_disabilities` | 4 | Disability Information | **Step 3** |
| 8 | `shs_student_details` | 9 | SHS-Specific Details | **Step 3** |
| 9 | `student_emergency_contacts` | 6 | Emergency Contacts | Additional |

**Total Fields**: 84 normalized fields across 9 specialized tables

## 🆕 Newly Created Tables (Missing Data Captured)

### 1. `student_addresses` - Address Information (Step 2)
**Purpose**: Capture current and permanent address information from enrollment form Step 2

**Fields**:
- `id` (PK)
- `enrollment_id` (FK → enrollments)
- `address_type` (ENUM: 'current', 'permanent')
- `house_no` (VARCHAR 50)
- `street` (VARCHAR 255)
- `barangay` (VARCHAR 100) - Required
- `municipality` (VARCHAR 100) - Required
- `province` (VARCHAR 100) - Required
- `country` (VARCHAR 100) - Default: 'Philippines'
- `zip_code` (VARCHAR 10)
- `is_same_as_current` (BOOLEAN) - For permanent address checkbox

**Relationships**: 1 enrollment → 2 addresses (current + permanent)

### 2. `student_family_info` - Family Information (Step 2)
**Purpose**: Capture father, mother, and guardian information from enrollment form Step 2

**Fields**:
- `id` (PK)
- `enrollment_id` (FK → enrollments)
- `relationship_type` (ENUM: 'father', 'mother', 'guardian')
- `first_name` (VARCHAR 100)
- `middle_name` (VARCHAR 100)
- `last_name` (VARCHAR 100)
- `contact_number` (VARCHAR 20)

**Relationships**: 1 enrollment → 3 family members (father + mother + guardian)

### 3. `student_disabilities` - Disability Information (Step 3)
**Purpose**: Capture special needs and disability information from enrollment form Step 3

**Fields**:
- `id` (PK)
- `enrollment_id` (FK → enrollments)
- `has_disability` (ENUM: 'Yes', 'No') - Default: 'No'
- `disability_type` (VARCHAR 100) - Multiple types supported:
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

**Relationships**: 1 enrollment → many disabilities (supports multiple disabilities per student)

### 4. `shs_student_details` - Senior High School Specific (Step 3)
**Purpose**: Capture SHS-specific requirements including track, strand, and career pathway

**Fields**:
- `id` (PK)
- `enrollment_id` (FK → enrollments)
- `track` (VARCHAR 50) - Required: Academic, TVL, Sports, Arts
- `strand` (VARCHAR 50) - Required: STEM, ABM, HUMSS, etc.
- `specialization` (VARCHAR 100) - Track-specific specialization
- `career_pathway` (VARCHAR 100) - Intended career direction
- `subject_preferences` (JSON) - Preferred elective subjects
- `prerequisites_met` (BOOLEAN) - Track/strand requirements validation
- `semester` (ENUM: '1st', '2nd') - Current semester

**Relationships**: 1 enrollment → 1 SHS detail (for SHS students only)

### 5. `student_emergency_contacts` - Emergency Contacts (Additional)
**Purpose**: Additional safety feature for emergency contact information

**Fields**:
- `id` (PK)
- `enrollment_id` (FK → enrollments)
- `contact_name` (VARCHAR 150) - Required
- `relationship` (VARCHAR 50) - Required
- `contact_number` (VARCHAR 20) - Required
- `is_primary` (BOOLEAN) - Primary emergency contact flag

**Relationships**: 1 enrollment → many emergency contacts

## 📝 Form Step to Database Mapping

### Step 1: Student Information
**Database Table**: `enrollment_personal_info`
- ✅ **Status**: Already implemented
- **Fields**: 18 fields covering basic student information
- **Data**: LRN, names, birth info, contact details, IP status, 4Ps status

### Step 2: Address & Family Information
**Database Tables**: `student_addresses` + `student_family_info`
- ✅ **Status**: **Newly implemented**
- **Address Fields**: 11 fields covering current and permanent addresses
- **Family Fields**: 7 fields covering father, mother, guardian information
- **Data**: Complete address details, family contact information

### Step 3: Academic & Special Needs
**Database Tables**: `enrollment_academic_history_new` + `student_disabilities` + `shs_student_details`
- ✅ **Status**: **Newly completed**
- **Academic Fields**: 11 fields covering previous school and performance
- **Disability Fields**: 4 fields covering special needs
- **SHS Fields**: 9 fields covering track, strand, career pathway
- **Data**: Academic history, disability information, SHS requirements

### Step 4: Document Upload
**Database Table**: `enrollment_documents`
- ✅ **Status**: Already implemented
- **Fields**: 5 fields covering document management
- **Data**: Document types, file paths, upload status

### Step 5: Review & Submit
**Database Integration**: All tables combined
- ✅ **Status**: Complete schema ready
- **Function**: Displays data from all normalized tables
- **Process**: Final validation and submission

## 🔗 Database Relationships

### Primary Relationships
```
students (main student table)
    ↓ (1:many)
enrollments (core enrollment record)
    ↓ (1:1) enrollment_personal_info [Step 1]
    ↓ (1:2) student_addresses [Step 2: current + permanent]
    ↓ (1:3) student_family_info [Step 2: father + mother + guardian]
    ↓ (1:1) enrollment_academic_history_new [Step 3: academic]
    ↓ (1:many) student_disabilities [Step 3: multiple disabilities]
    ↓ (1:1) shs_student_details [Step 3: SHS students only]
    ↓ (1:many) enrollment_documents [Step 4: documents]
    ↓ (1:many) student_emergency_contacts [Additional: emergency]
```

### Foreign Key Constraints
- All child tables reference `enrollments.id`
- CASCADE DELETE: When enrollment is deleted, all related data is removed
- CASCADE UPDATE: When enrollment ID changes, all references are updated
- UNIQUE constraints prevent duplicate relationships (e.g., one current address per enrollment)

## 🏫 Senior High School (SHS) Support

### Track Support
- **Academic Track**: College preparatory courses
- **Technical-Vocational-Livelihood (TVL)**: Skills-based programs
- **Sports Track**: Athletic and sports-focused curriculum
- **Arts Track**: Creative and artistic programs

### Strand Support
- **STEM**: Science, Technology, Engineering, Mathematics
- **ABM**: Accountancy, Business, Management
- **HUMSS**: Humanities and Social Sciences
- **GAS**: General Academic Strand
- **TVL Strands**: Various technical specializations

### Additional SHS Features
- **Specialization**: Track-specific focus areas
- **Career Pathway**: Intended career direction
- **Subject Preferences**: JSON storage for elective choices
- **Prerequisites**: Validation for track/strand requirements
- **Semester Tracking**: 1st and 2nd semester management

## 🎯 Normalization Benefits Achieved

### 3rd Normal Form (3NF) Compliance
- **1NF**: All fields contain atomic values
- **2NF**: No partial dependencies on composite keys
- **3NF**: No transitive dependencies

### Data Integrity
- **Referential Integrity**: Foreign key constraints ensure consistency
- **Unique Constraints**: Prevent duplicate relationships
- **Cascade Operations**: Maintain data consistency on updates/deletes
- **Data Validation**: ENUM constraints for controlled values

### Performance Optimization
- **Strategic Indexing**: Optimized for common enrollment queries
- **Efficient JOINs**: Proper relationships for fast data retrieval
- **Reduced Redundancy**: No duplicate data storage
- **Scalable Design**: Supports future growth and modifications

## 📋 Query Examples

### Get Complete Enrollment Information
```sql
SELECT 
    e.enrollment_number,
    e.enrollment_status,
    epi.first_name,
    epi.last_name,
    epi.lrn,
    sa_current.barangay as current_barangay,
    sa_current.municipality as current_municipality,
    sfi_father.first_name as father_name,
    sfi_father.contact_number as father_contact,
    sd.disability_type,
    shs.track,
    shs.strand
FROM enrollments e
JOIN enrollment_personal_info epi ON e.id = epi.enrollment_id
LEFT JOIN student_addresses sa_current ON e.id = sa_current.enrollment_id 
    AND sa_current.address_type = 'current'
LEFT JOIN student_family_info sfi_father ON e.id = sfi_father.enrollment_id 
    AND sfi_father.relationship_type = 'father'
LEFT JOIN student_disabilities sd ON e.id = sd.enrollment_id
LEFT JOIN shs_student_details shs ON e.id = shs.enrollment_id
WHERE e.id = ?;
```

### Get SHS Students by Track and Strand
```sql
SELECT 
    e.enrollment_number,
    epi.first_name,
    epi.last_name,
    shs.track,
    shs.strand,
    shs.specialization,
    shs.career_pathway
FROM enrollments e
JOIN enrollment_personal_info epi ON e.id = epi.enrollment_id
JOIN shs_student_details shs ON e.id = shs.enrollment_id
WHERE shs.track = 'Academic' AND shs.strand = 'STEM'
ORDER BY epi.last_name, epi.first_name;
```

### Get Students with Disabilities
```sql
SELECT 
    e.enrollment_number,
    epi.first_name,
    epi.last_name,
    sd.disability_type,
    sd.has_disability
FROM enrollments e
JOIN enrollment_personal_info epi ON e.id = epi.enrollment_id
JOIN student_disabilities sd ON e.id = sd.enrollment_id
WHERE sd.has_disability = 'Yes'
ORDER BY sd.disability_type, epi.last_name;
```

## 🚀 Implementation Status

### ✅ Completed Features
- [x] Core enrollment workflow (enrollments table)
- [x] Student personal information capture (Step 1)
- [x] Address information capture (Step 2) - **NEW**
- [x] Family information capture (Step 2) - **NEW**
- [x] Academic history capture (Step 3)
- [x] Disability information capture (Step 3) - **NEW**
- [x] SHS-specific requirements (Step 3) - **NEW**
- [x] Document management (Step 4)
- [x] Emergency contacts - **NEW**
- [x] Complete normalization (3NF)
- [x] Foreign key relationships
- [x] Data integrity constraints
- [x] Performance optimization

### 📈 Schema Statistics
- **Total Tables**: 9 normalized tables
- **Total Fields**: 84 fields across all tables
- **Form Coverage**: 100% of multi-step enrollment form
- **SHS Support**: Complete track/strand/specialization coverage
- **Normalization**: Full 3NF compliance
- **Relationships**: 8 foreign key relationships established

## 🎉 Summary

The enrollment database schema has been successfully extended to capture all missing data from Steps 2 and 3 of the multi-step enrollment form:

### Key Achievements
1. **Complete Form Coverage**: All form steps now have corresponding database tables
2. **Proper Normalization**: 3NF compliance with no data redundancy
3. **SHS Support**: Dedicated structure for Senior High School requirements
4. **Data Integrity**: Foreign key constraints ensure consistency
5. **Performance Optimized**: Strategic indexing for efficient queries
6. **Scalable Design**: Supports future enhancements and modifications

### Missing Data Now Captured
- ✅ **Address Information**: Current and permanent addresses
- ✅ **Family Information**: Father, mother, guardian details
- ✅ **Disability Information**: Special needs and accommodations
- ✅ **SHS Requirements**: Track, strand, career pathway, specializations
- ✅ **Emergency Contacts**: Additional safety information

The enrollment system now has a complete, normalized, and efficient database schema that fully supports the multi-step enrollment workflow while maintaining data integrity and optimal performance.