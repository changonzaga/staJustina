-- =====================================================
-- UPDATE STUDENT VIEWS TO USE NORMALIZED PARENT STRUCTURE
-- This script updates database views to use the new normalized
-- parents + student_parent_relationships structure instead of
-- the deprecated student_family_info table
-- =====================================================

-- Drop existing views that use student_family_info
DROP VIEW IF EXISTS view_student_complete;
DROP VIEW IF EXISTS view_active_students_contacts;
DROP VIEW IF EXISTS student_complete_view;

-- =====================================================
-- UPDATED COMPLETE STUDENT INFORMATION VIEW
-- Uses normalized parent structure
-- =====================================================
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
    
    -- Primary Family Contact (from normalized structure)
    CONCAT(p.first_name, ' ', COALESCE(p.middle_name, ''), ' ', p.last_name) AS primary_contact_name,
    p.contact_number AS primary_contact_phone,
    p.email AS primary_contact_email,
    spr.relationship_type AS primary_contact_relationship,
    
    -- Primary Address
    CONCAT(
        COALESCE(CONCAT(sad.house_no, ' '), ''),
        COALESCE(CONCAT(sad.street, ', '), ''),
        COALESCE(CONCAT(sad.subdivision_village, ', '), ''),
        sad.barangay, ', ',
        sad.municipality_city, ', ',
        sad.province
    ) AS full_address,
    
    s.created_at,
    s.updated_at
    
FROM students s
LEFT JOIN student_personal_info spi ON s.id = spi.student_id
LEFT JOIN student_auth sa ON s.id = sa.student_id
LEFT JOIN student_parent_relationships spr ON s.id = spr.student_id AND spr.is_primary_contact = TRUE
LEFT JOIN parents p ON spr.parent_id = p.id
LEFT JOIN student_address sad ON s.id = sad.student_id AND sad.is_primary = TRUE;

-- =====================================================
-- UPDATED ACTIVE STUDENTS WITH CONTACT INFO VIEW
-- Uses normalized parent structure
-- =====================================================
CREATE VIEW view_active_students_contacts AS
SELECT 
    s.id,
    s.student_account_number,
    s.lrn,
    CONCAT(spi.first_name, ' ', spi.last_name) AS student_name,
    s.grade_level,
    s.section,
    sa.email AS student_email,
    p.contact_number AS family_phone,
    p.email AS family_email,
    CONCAT(p.first_name, ' ', p.last_name) AS contact_person,
    spr.relationship_type AS contact_relationship
FROM students s
JOIN student_personal_info spi ON s.id = spi.student_id
JOIN student_auth sa ON s.id = sa.student_id
JOIN student_parent_relationships spr ON s.id = spr.student_id AND spr.is_primary_contact = TRUE
JOIN parents p ON spr.parent_id = p.id
WHERE s.student_status = 'active';

-- =====================================================
-- NEW COMPREHENSIVE STUDENT PARENTS VIEW
-- Shows all parent relationships for each student
-- =====================================================
CREATE VIEW view_student_all_parents AS
SELECT 
    s.id as student_id,
    s.student_account_number,
    s.lrn,
    CONCAT(spi.first_name, ' ', spi.last_name) as student_name,
    s.grade_level,
    s.section,
    p.id as parent_id,
    CONCAT(p.first_name, ' ', COALESCE(p.middle_name, ''), ' ', p.last_name) as parent_name,
    p.contact_number as parent_contact,
    p.email as parent_email,
    p.occupation as parent_occupation,
    spr.relationship_type,
    spr.is_primary_contact,
    spr.is_emergency_contact,
    pa.house_number,
    pa.street,
    pa.barangay,
    pa.municipality,
    pa.province,
    pa.zip_code
FROM students s
JOIN student_personal_info spi ON s.id = spi.student_id
JOIN student_parent_relationships spr ON s.id = spr.student_id
JOIN parents p ON spr.parent_id = p.id
LEFT JOIN parent_addresses pa ON p.id = pa.parent_id AND pa.address_type = 'home'
WHERE s.student_status = 'active'
ORDER BY s.id, spr.relationship_type;

-- =====================================================
-- STUDENT EMERGENCY CONTACTS VIEW
-- Combines parent emergency contacts with other emergency contacts
-- Note: student_emergency_contacts table doesn't exist, so only showing parent contacts
-- =====================================================
CREATE VIEW view_student_emergency_contacts AS
SELECT 
    s.id as student_id,
    CONCAT(spi.first_name, ' ', spi.last_name) as student_name,
    'parent' as contact_source,
    CONCAT(p.first_name, ' ', p.last_name) as contact_name,
    spr.relationship_type as relationship,
    p.contact_number as phone_primary,
    p.email,
    1 as priority_order,
    spr.is_primary_contact
FROM students s
JOIN student_personal_info spi ON s.id = spi.student_id
JOIN student_parent_relationships spr ON s.id = spr.student_id AND spr.is_emergency_contact = TRUE
JOIN parents p ON spr.parent_id = p.id

ORDER BY student_id, priority_order;

-- =====================================================
-- LEGACY COMPATIBILITY VIEW (TEMPORARY)
-- Provides same structure as old student_family_info for backward compatibility
-- This view should be removed once all code is updated
-- =====================================================
CREATE VIEW student_family_info_legacy AS
SELECT 
    ROW_NUMBER() OVER (ORDER BY s.id, spr.relationship_type) as id,
    s.id as student_id,
    spr.relationship_type,
    p.first_name,
    p.middle_name,
    p.last_name,
    p.contact_number,
    p.email,
    p.occupation,
    p.employer,
    spr.is_primary_contact,
    spr.is_emergency_contact,
    spr.created_at,
    spr.updated_at
FROM students s
JOIN student_parent_relationships spr ON s.id = spr.student_id
JOIN parents p ON spr.parent_id = p.id;

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Show updated view structures
SELECT 'Updated views created successfully!' AS status;

-- Count records in new views
SELECT 
    'view_student_complete' as view_name,
    COUNT(*) as record_count
FROM view_student_complete

UNION ALL

SELECT 
    'view_active_students_contacts' as view_name,
    COUNT(*) as record_count
FROM view_active_students_contacts

UNION ALL

SELECT 
    'view_student_all_parents' as view_name,
    COUNT(*) as record_count
FROM view_student_all_parents;

-- Show sample data from updated views
SELECT 'Sample from view_student_complete:' as info;
SELECT * FROM view_student_complete LIMIT 3;

SELECT 'Sample from view_active_students_contacts:' as info;
SELECT * FROM view_active_students_contacts LIMIT 3;

SELECT 'Sample from view_student_all_parents:' as info;
SELECT * FROM view_student_all_parents LIMIT 5;