-- Simulation script to test disability data migration
-- This simulates the migration process for existing enrolled students

USE stajustina_db;

-- 1. Show current state
SELECT 'Current enrollment disability data:' as step;
SELECT edt.*, e.grade_level, e.enrollment_status 
FROM enrollment_disabilities_temp edt 
JOIN enrollments e ON e.id = edt.enrollment_id 
WHERE e.enrollment_status = 'enrolled'
ORDER BY edt.created_at DESC;

-- 2. Show current student disability data (should be empty)
SELECT 'Current student disability data:' as step;
SELECT COUNT(*) as count FROM student_disabilities_temp;

-- 3. Simulate the disability migration for existing enrolled students
-- This mimics what the transferDisabilityInfo method would do
INSERT INTO student_disabilities_temp (student_id, has_disability, disability_type, created_at, updated_at)
SELECT 
    s.id as student_id,
    edt.has_disability,
    edt.disability_type,
    NOW() as created_at,
    NOW() as updated_at
FROM enrollment_disabilities_temp edt
JOIN enrollments e ON e.id = edt.enrollment_id
JOIN students s ON s.enrollment_id = e.id
WHERE e.enrollment_status = 'enrolled'
AND s.id NOT IN (SELECT student_id FROM student_disabilities_temp WHERE student_id IS NOT NULL);

-- 4. Verify the migration worked
SELECT 'After migration - student disability data:' as step;
SELECT sdt.*, s.account_number, s.enrollment_id, s.grade_level
FROM student_disabilities_temp sdt 
JOIN students s ON s.id = sdt.student_id 
ORDER BY sdt.created_at DESC;

-- 5. Verify data consistency between enrollment and student tables
SELECT 'Data consistency check:' as step;
SELECT 
    e.id as enrollment_id,
    e.grade_level,
    edt.has_disability as enrollment_has_disability,
    edt.disability_type as enrollment_disability_type,
    s.id as student_id,
    s.account_number,
    sdt.has_disability as student_has_disability,
    sdt.disability_type as student_disability_type,
    CASE 
        WHEN edt.has_disability = sdt.has_disability AND edt.disability_type = sdt.disability_type 
        THEN 'MATCH' 
        ELSE 'MISMATCH' 
    END as data_consistency
FROM enrollment_disabilities_temp edt
JOIN enrollments e ON e.id = edt.enrollment_id
JOIN students s ON s.enrollment_id = e.id
LEFT JOIN student_disabilities_temp sdt ON sdt.student_id = s.id
WHERE e.enrollment_status = 'enrolled'
ORDER BY e.id;

SELECT 'Migration simulation complete!' as result;