# Enrollment Database Schema Documentation

## Overview
This document describes the database schema created for the multi-step enrollment system at STA Justina High School. The schema is designed to efficiently store all data collected through the 4-step enrollment form while maintaining data integrity and supporting the approval workflow.

## Main Tables

### 1. `enrollments` Table (39 fields)
The primary table that stores all enrollment application data directly from the multi-step form.

#### Core Enrollment Information
- `id` - Primary key (auto-increment)
- `enrollment_number` - Auto-generated unique identifier (ENR-YYYYMMDD-XXXX format)
- `student_id` - Links to student table after approval (nullable)
- `school_year` - Academic year (e.g., "2024-2025")
- `grade_level` - Target grade level
- `section` - Assigned section (nullable, set during approval)
- `enrollment_type` - ENUM: new, returning, transferee, continuing
- `enrollment_status` - ENUM: pending, approved, declined, enrolled, transferred, dropped

#### Student Personal Information (Step 1)
- `lrn` - 12-digit Learner Reference Number
- `birth_certificate_number` - Birth certificate number (optional)
- `last_name`, `first_name`, `middle_name` - Student name components
- `extension_name` - Name extension (Jr., III, etc.)
- `date_of_birth` - Birth date
- `gender` - ENUM: Male, Female
- `age` - Student age
- `mother_tongue` - Primary language spoken at home
- `student_email` - Student email address
- `student_contact` - Student contact number

#### Special Categories
- `indigenous_people` - ENUM: Yes, No
- `indigenous_community` - IP community name (if applicable)
- `fourps_beneficiary` - ENUM: Yes, No (4Ps beneficiary status)
- `fourps_household_id` - 4Ps household ID number

#### Academic Information (Step 3)
- `previous_gwa` - Previous General Weighted Average (DECIMAL 5,2)
- `performance_level` - Academic performance level
- `last_grade_completed` - Last completed grade level
- `last_school_year` - Last school year attended
- `last_school_attended` - Previous school name
- `school_id` - Previous school ID
- `semester` - ENUM: 1st, 2nd
- `track` - Academic track (e.g., Academic, TVL, Sports, Arts)
- `strand` - Specific strand (e.g., STEM, ABM, HUMSS)

#### Document Management (Step 4)
- `documents_submitted` - JSON array of uploaded document information

#### Workflow Management
- `approved_by` - User ID who approved the enrollment
- `approved_at` - Timestamp of approval
- `declined_reason` - Reason for decline (if applicable)
- `created_at` - Application submission timestamp
- `updated_at` - Last modification timestamp

### 2. `student` Table (38 fields)
Updated student table that receives approved enrollment data.

## Form-to-Database Mapping

### Step 1: Student Information
- Maps directly to personal information fields in `enrollments` table
- Includes LRN input (12 individual digit fields combined into single `lrn` field)
- Special categories (Indigenous People, 4Ps beneficiary) stored as ENUM values

### Step 2: Address & Family Information
- Address information stored as text fields within main `enrollments` table
- Parent/guardian information stored in dedicated fields
- "Same as current address" checkbox logic handled in application layer

### Step 3: Academic & Special Needs
- Disability information can be stored as JSON or in separate normalized table
- Academic history and performance data stored in dedicated fields
- GWA automatically determines performance level

### Step 4: Document Upload
- Document metadata stored as JSON in `documents_submitted` field
- Actual files stored in filesystem with references in JSON

## Database Design Principles

### 1. Denormalized Approach
The current schema uses a denormalized approach where most enrollment data is stored directly in the main `enrollments` table. This design choice offers:

**Advantages:**
- Simplified queries for enrollment data
- Faster read operations
- Easier backup and data export
- Direct mapping from form fields to database columns
- Reduced complexity in application logic

**Trade-offs:**
- Some data redundancy (acceptable for enrollment use case)
- Larger table size
- Parent/guardian information repeated for siblings

### 2. Future Normalization Options
If needed, the schema can be normalized by creating separate tables:
- `enrollment_addresses` (current/permanent addresses)
- `enrollment_parents_guardians` (family information)
- `enrollment_disabilities` (special needs)
- `enrollment_audit_logs` (action tracking)

## Key Features

### 1. Auto-Generated Enrollment Numbers
- Format: ENR-YYYYMMDD-XXXX
- Unique constraint ensures no duplicates
- Sequential numbering per day

### 2. Comprehensive Data Storage
- All 39 form fields mapped to database columns
- JSON support for complex data (documents, disabilities)
- Proper data types and constraints

### 3. Workflow Support
- Status tracking (pending → approved/declined)
- Approval metadata (who, when)
- Integration with student account creation

### 4. Data Integrity
- Primary keys and unique constraints
- Proper indexing for performance
- ENUM constraints for controlled values
- Foreign key relationships where applicable

## Usage in Application

### 1. Form Submission
```php
// All form data stored in single insert
$enrollmentModel->insert([
    'lrn' => $this->formatLRN($formData),
    'first_name' => $formData['first_name'],
    'last_name' => $formData['last_name'],
    // ... all other fields
    'documents_submitted' => json_encode($documents)
]);
```

### 2. Admin Review
```php
// Simple query to get all enrollment data
$enrollment = $enrollmentModel->find($enrollmentId);
// All information available in single record
```

### 3. Approval Process
```php
// Update status and create student record
$enrollmentModel->update($enrollmentId, [
    'enrollment_status' => 'approved',
    'approved_by' => $adminId,
    'approved_at' => date('Y-m-d H:i:s')
]);
```

## Performance Considerations

### Indexes
- Primary key on `id`
- Unique index on `enrollment_number`
- Unique index on `lrn`
- Indexes on frequently queried fields:
  - `enrollment_status`
  - `grade_level`
  - `school_year`
  - `student_id`

### Query Optimization
- Single table queries for most operations
- Efficient filtering by status, grade, year
- JSON field indexing for document searches (if needed)

## Migration History

### 2025-01-15-140000_CreateEnrollmentTables
- Created main `enrollments` table with 39 fields
- Established proper relationships and constraints
- Set up indexing for performance

## Conclusion

The enrollment database schema successfully supports the complete 4-step enrollment process with:
- ✅ All form fields properly mapped and stored
- ✅ Efficient single-table design for most operations
- ✅ Proper data types and constraints
- ✅ Workflow support for admin approval process
- ✅ Integration with existing student management system
- ✅ Scalable design supporting high enrollment volumes

The schema provides a solid foundation for the enrollment system while maintaining flexibility for future enhancements and normalization if needed.