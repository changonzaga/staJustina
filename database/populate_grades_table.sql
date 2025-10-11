-- Sample data for grades table
-- This script populates the grades table with sample data for testing

INSERT INTO `grades` (`id`, `grade_name`, `description`, `education_level`, `created_at`, `updated_at`) VALUES
(1, 'Grade 7', 'First year of Junior High School', 'Junior High', NOW(), NOW()),
(2, 'Grade 8', 'Second year of Junior High School', 'Junior High', NOW(), NOW()),
(3, 'Grade 9', 'Third year of Junior High School', 'Junior High', NOW(), NOW()),
(4, 'Grade 10', 'Fourth year of Junior High School', 'Junior High', NOW(), NOW()),
(5, 'Grade 11', 'First year of Senior High School', 'Senior High', NOW(), NOW()),
(6, 'Grade 12', 'Second year of Senior High School', 'Senior High', NOW(), NOW());

-- Optional: Add some elementary grades if needed
-- INSERT INTO `grades` (`id`, `grade_name`, `description`, `education_level`, `created_at`, `updated_at`) VALUES
-- (7, 'Grade 1', 'First year of Elementary School', 'Elementary', NOW(), NOW()),
-- (8, 'Grade 2', 'Second year of Elementary School', 'Elementary', NOW(), NOW()),
-- (9, 'Grade 3', 'Third year of Elementary School', 'Elementary', NOW(), NOW()),
-- (10, 'Grade 4', 'Fourth year of Elementary School', 'Elementary', NOW(), NOW()),
-- (11, 'Grade 5', 'Fifth year of Elementary School', 'Elementary', NOW(), NOW()),
-- (12, 'Grade 6', 'Sixth year of Elementary School', 'Elementary', NOW(), NOW());
