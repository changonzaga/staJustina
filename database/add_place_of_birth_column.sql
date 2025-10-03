-- Add place_of_birth column to enrollment_personal_info table
-- This script adds a new column to store the place of birth information

USE stajustina_db;

-- Add place_of_birth column after date_of_birth
ALTER TABLE enrollment_personal_info 
ADD COLUMN place_of_birth VARCHAR(200) NULL 
AFTER date_of_birth;

-- Add comment to the column for documentation
ALTER TABLE enrollment_personal_info 
MODIFY COLUMN place_of_birth VARCHAR(200) NULL 
COMMENT 'Place where the student was born (city, province, country)';

-- Verify the column was added successfully
DESCRIBE enrollment_personal_info;

SELECT 'place_of_birth column added successfully to enrollment_personal_info table' AS status;