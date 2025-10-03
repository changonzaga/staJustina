-- Remove Foreign Key Relations from student_notifications Table
-- This script safely removes the foreign key constraint without affecting other database tables
-- Date: Generated automatically
-- Purpose: Isolate student_notifications table from database relationships

USE stajustina_db;

-- Step 1: Drop the foreign key constraint that references the students table
-- This removes the relationship between student_notifications.student_id and students.id
ALTER TABLE student_notifications 
DROP FOREIGN KEY student_notifications_ibfk_1;

-- Step 2: Remove the index associated with the foreign key (if it exists and is not needed)
-- Note: This step is optional and should only be done if the index is not used for queries
-- ALTER TABLE student_notifications DROP INDEX student_id;

-- Step 3: Optionally, you can modify the student_id column to remove any constraints
-- if you want to completely isolate it (uncomment if needed)
-- ALTER TABLE student_notifications MODIFY COLUMN student_id INT(11) UNSIGNED NULL;

-- Verification queries (run these to confirm changes):
-- SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
-- FROM information_schema.KEY_COLUMN_USAGE 
-- WHERE TABLE_SCHEMA = 'stajustina_db' 
--   AND TABLE_NAME = 'student_notifications' 
--   AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Show table structure after changes:
-- DESCRIBE student_notifications;

COMMIT;