-- Test script for disability data migration workflow
-- This script tests the complete flow from enrollment_disabilities_temp to student_disabilities_temp

-- Select the database
USE stajustina_db;

-- 1. First, let's check if we have any existing enrollment data to work with
SELECT 'Checking existing enrollments...' as step;
SELECT id, grade_level, enrollment_status, created_at 
FROM enrollments 
WHERE enrollment_status = 'pending' 
LIMIT 5;

-- 2. Check if we have any enrollment disability data
SELECT 'Checking enrollment disability data...' as step;
SELECT COUNT(*) as enrollment_disability_count 
FROM enrollment_disabilities_temp;

-- 3. Insert test enrollment disability data if none exists
INSERT IGNORE INTO enrollment_disabilities_temp (enrollment_id, has_disability, disability_type, created_at, updated_at)
SELECT 
    e.id as enrollment_id,
    'Yes' as has_disability,
    CASE 
        WHEN e.id % 3 = 0 THEN 'Visual Impairment'
        WHEN e.id % 3 = 1 THEN 'Hearing Impairment'
        ELSE 'Learning Disability'
    END as disability_type,
    NOW() as created_at,
    NOW() as updated_at
FROM enrollments e 
WHERE e.enrollment_status = 'pending' 
AND e.id NOT IN (SELECT enrollment_id FROM enrollment_disabilities_temp WHERE enrollment_id IS NOT NULL)
LIMIT 3;

-- 4. Verify the test data was inserted
SELECT 'Test disability data inserted:' as step;
SELECT edt.*, e.grade_level, e.enrollment_status
FROM enrollment_disabilities_temp edt
JOIN enrollments e ON e.id = edt.enrollment_id
ORDER BY edt.created_at DESC
LIMIT 5;

-- 5. Check current student_disabilities_temp table
SELECT 'Current student disability records:' as step;
SELECT COUNT(*) as student_disability_count 
FROM student_disabilities_temp;

-- 6. Show the structure of both tables for comparison
SELECT 'enrollment_disabilities_temp structure:' as step;
DESCRIBE enrollment_disabilities_temp;

SELECT 'student_disabilities_temp structure:' as step;
DESCRIBE student_disabilities_temp;

-- 7. Show any existing students that might be related to our test enrollments
SELECT 'Existing students from enrollments:' as step;
SELECT s.id, s.account_number, s.enrollment_id, s.grade_level, s.student_status
FROM students s
WHERE s.enrollment_id IN (
    SELECT enrollment_id FROM enrollment_disabilities_temp
)
LIMIT 5;

SELECT 'Test setup complete. Ready for enrollment approval testing.' as result;