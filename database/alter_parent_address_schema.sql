-- Alter student_parent_address table to support parent_id foreign key
-- This script repurposes the existing table to be parent-centric instead of student-centric

-- Step 1: Add parent_id column as nullable first (for existing data)
ALTER TABLE student_parent_address 
ADD COLUMN parent_id INT(11) UNSIGNED NULL AFTER student_id;

-- Step 2: Add foreign key constraint to parents table
ALTER TABLE student_parent_address 
ADD CONSTRAINT fk_student_parent_address_parent_id 
FOREIGN KEY (parent_id) REFERENCES parents(id) ON DELETE CASCADE;

-- Step 3: Add index for better performance
ALTER TABLE student_parent_address 
ADD INDEX idx_parent_id (parent_id);

-- Step 4: Add composite index for parent_id and parent_type combination
ALTER TABLE student_parent_address 
ADD INDEX idx_parent_id_type (parent_id, parent_type);

-- Step 5: Modify student_id to be nullable (gradual phase out)
-- We'll keep it for now during migration but make it nullable
ALTER TABLE student_parent_address 
MODIFY COLUMN student_id INT(11) UNSIGNED NULL;

-- Step 6: Add migration status column to track data migration progress
ALTER TABLE student_parent_address 
ADD COLUMN migration_status ENUM('legacy', 'migrated', 'new') DEFAULT 'legacy' 
AFTER updated_at;

-- Step 7: Add comments to document the changes
ALTER TABLE student_parent_address 
COMMENT = 'Parent address table - repurposed to be parent-centric. parent_id is the new primary link, student_id kept for backward compatibility during migration';