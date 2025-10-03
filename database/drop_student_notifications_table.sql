-- Drop the student_notifications table completely
-- This will remove the table and all its data permanently

USE stajustina_db;

-- Drop the table
DROP TABLE IF EXISTS student_notifications;

-- Verify the table has been dropped
SHOW TABLES LIKE 'student_notifications';