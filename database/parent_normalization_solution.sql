-- =====================================================
-- PARENT NORMALIZATION SOLUTION
-- Prevents duplicate parent records while maintaining proper relationships
-- =====================================================

-- Step 1: Create normalized parents table
CREATE TABLE IF NOT EXISTS `parents` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `first_name` varchar(100) NOT NULL,
    `middle_name` varchar(100) DEFAULT NULL,
    `last_name` varchar(100) NOT NULL,
    `contact_number` varchar(20) DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `occupation` varchar(100) DEFAULT NULL,
    `employer` varchar(255) DEFAULT NULL,
    `monthly_income` decimal(10,2) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_parent` (`first_name`, `last_name`, `contact_number`),
    KEY `idx_parent_name` (`first_name`, `last_name`),
    KEY `idx_contact` (`contact_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 2: Create parent-student relationship table
CREATE TABLE IF NOT EXISTS `student_parent_relationships` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `student_id` int(11) unsigned NOT NULL,
    `parent_id` int(11) unsigned NOT NULL,
    `relationship_type` enum('father','mother','guardian') NOT NULL,
    `is_primary_contact` tinyint(1) DEFAULT 0,
    `is_emergency_contact` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_student_parent_relationship` (`student_id`, `parent_id`, `relationship_type`),
    KEY `idx_student_id` (`student_id`),
    KEY `idx_parent_id` (`parent_id`),
    KEY `idx_relationship_type` (`relationship_type`),
    CONSTRAINT `fk_spr_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_spr_parent` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 3: Create parent address table (normalized)
CREATE TABLE IF NOT EXISTS `parent_addresses` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `parent_id` int(11) unsigned NOT NULL,
    `address_type` enum('home','work','other') DEFAULT 'home',
    `is_same_as_student` tinyint(1) DEFAULT 0,
    `house_number` varchar(50) DEFAULT NULL,
    `street` varchar(255) DEFAULT NULL,
    `barangay` varchar(100) DEFAULT NULL,
    `municipality` varchar(100) DEFAULT NULL,
    `province` varchar(100) DEFAULT NULL,
    `zip_code` varchar(10) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_parent_id` (`parent_id`),
    KEY `idx_address_type` (`address_type`),
    CONSTRAINT `fk_pa_parent` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Step 4: Create enrollment parent relationships (temporary during enrollment)
CREATE TABLE IF NOT EXISTS `enrollment_parent_relationships` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `enrollment_id` int(11) unsigned NOT NULL,
    `parent_id` int(11) unsigned DEFAULT NULL, -- NULL if new parent
    `relationship_type` enum('father','mother','guardian') NOT NULL,
    `first_name` varchar(100) DEFAULT NULL,
    `middle_name` varchar(100) DEFAULT NULL,
    `last_name` varchar(100) DEFAULT NULL,
    `contact_number` varchar(20) DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `is_existing_parent` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_enrollment_id` (`enrollment_id`),
    KEY `idx_parent_id` (`parent_id`),
    KEY `idx_relationship_type` (`relationship_type`),
    CONSTRAINT `fk_epr_enrollment` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_epr_parent` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- MIGRATION SCRIPT: Consolidate existing duplicate parents
-- =====================================================

-- Step 5: Create procedure to find and merge duplicate parents
DELIMITER //

CREATE PROCEDURE ConsolidateDuplicateParents()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_first_name VARCHAR(100);
    DECLARE v_last_name VARCHAR(100);
    DECLARE v_contact VARCHAR(20);
    DECLARE v_count INT;
    DECLARE v_master_parent_id INT;
    
    -- Cursor to find duplicate parent groups
    DECLARE duplicate_cursor CURSOR FOR
        SELECT first_name, last_name, contact_number, COUNT(*) as cnt
        FROM student_family_info
        WHERE first_name IS NOT NULL AND last_name IS NOT NULL
        GROUP BY first_name, last_name, contact_number
        HAVING cnt > 1;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    START TRANSACTION;
    
    -- Create parents from existing student_family_info
    INSERT IGNORE INTO parents (first_name, middle_name, last_name, contact_number, created_at)
    SELECT DISTINCT 
        first_name, 
        middle_name, 
        last_name, 
        contact_number,
        MIN(created_at)
    FROM student_family_info
    WHERE first_name IS NOT NULL AND last_name IS NOT NULL
    GROUP BY first_name, last_name, contact_number;
    
    -- Create student-parent relationships
    INSERT IGNORE INTO student_parent_relationships (student_id, parent_id, relationship_type, created_at)
    SELECT DISTINCT
        sfi.student_id,
        p.id as parent_id,
        sfi.relationship_type,
        sfi.created_at
    FROM student_family_info sfi
    JOIN parents p ON (
        sfi.first_name = p.first_name 
        AND sfi.last_name = p.last_name 
        AND COALESCE(sfi.contact_number, '') = COALESCE(p.contact_number, '')
    )
    WHERE sfi.first_name IS NOT NULL AND sfi.last_name IS NOT NULL;
    
    -- Migrate parent addresses
    INSERT IGNORE INTO parent_addresses (parent_id, address_type, is_same_as_student, house_number, street, barangay, municipality, province, zip_code, created_at)
    SELECT DISTINCT
        p.id as parent_id,
        'home' as address_type,
        spa.is_same_as_student,
        spa.house_number,
        spa.street,
        spa.barangay,
        spa.municipality,
        spa.province,
        spa.zip_code,
        spa.created_at
    FROM student_parent_address spa
    JOIN student_family_info sfi ON (spa.student_id = sfi.student_id AND spa.parent_type = sfi.relationship_type)
    JOIN parents p ON (
        sfi.first_name = p.first_name 
        AND sfi.last_name = p.last_name 
        AND COALESCE(sfi.contact_number, '') = COALESCE(p.contact_number, '')
    );
    
    COMMIT;
    
    SELECT 
        (SELECT COUNT(*) FROM parents) as total_parents_created,
        (SELECT COUNT(*) FROM student_parent_relationships) as total_relationships_created,
        (SELECT COUNT(*) FROM parent_addresses) as total_addresses_migrated;
        
END //

DELIMITER ;

-- =====================================================
-- HELPER FUNCTIONS AND VIEWS
-- =====================================================

-- View to get student with all parent information
CREATE OR REPLACE VIEW student_parents_view AS
SELECT 
    s.id as student_id,
    CONCAT(spi.first_name, ' ', spi.last_name) as student_name,
    p.id as parent_id,
    CONCAT(p.first_name, ' ', COALESCE(p.middle_name, ''), ' ', p.last_name) as parent_name,
    p.contact_number as parent_contact,
    p.email as parent_email,
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
LEFT JOIN parent_addresses pa ON p.id = pa.parent_id AND pa.address_type = 'home';

-- Function to find existing parent by details
DELIMITER //

CREATE FUNCTION FindExistingParent(
    p_first_name VARCHAR(100),
    p_last_name VARCHAR(100),
    p_contact VARCHAR(20)
) RETURNS INT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE parent_id INT DEFAULT NULL;
    
    SELECT id INTO parent_id
    FROM parents
    WHERE first_name = p_first_name
    AND last_name = p_last_name
    AND COALESCE(contact_number, '') = COALESCE(p_contact, '')
    LIMIT 1;
    
    RETURN parent_id;
END //

DELIMITER ;

-- =====================================================
-- USAGE INSTRUCTIONS
-- =====================================================

/*
1. Run this script to create the normalized structure
2. Execute: CALL ConsolidateDuplicateParents();
3. Update application code to use new structure
4. Test with: SELECT * FROM student_parents_view;
5. After verification, drop old tables:
   - DROP TABLE student_family_info;
   - DROP TABLE student_parent_address;
*/