-- Create enrollment_addresses table for storing student address information
-- This table stores both current and permanent addresses from the enrollment form

CREATE TABLE `enrollment_addresses` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `enrollment_id` int(11) unsigned NOT NULL,
    `address_type` enum('current','permanent') NOT NULL,
    `house_no` varchar(50) DEFAULT NULL,
    `street` varchar(255) DEFAULT NULL,
    `barangay` varchar(100) NOT NULL,
    `municipality` varchar(100) NOT NULL,
    `province` varchar(100) NOT NULL,
    `country` varchar(100) DEFAULT 'Philippines',
    `zip_code` varchar(10) DEFAULT NULL,
    `is_same_as_current` tinyint(1) DEFAULT 0 COMMENT 'For permanent address same as current',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_enrollment_id` (`enrollment_id`),
    KEY `idx_address_type` (`address_type`),
    UNIQUE KEY `uk_enrollment_address_type` (`enrollment_id`,`address_type`),
    CONSTRAINT `fk_enrollment_addresses_enrollment_id` 
        FOREIGN KEY (`enrollment_id`) 
        REFERENCES `enrollments` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data to test the table (optional)
-- You can remove this section if you don't want test data
/*
INSERT INTO `enrollment_addresses` 
(`enrollment_id`, `address_type`, `house_no`, `street`, `barangay`, `municipality`, `province`, `country`, `zip_code`, `is_same_as_current`) 
VALUES 
(1, 'current', '123', 'Sample Street', 'Sample Barangay', 'Sample City', 'Sample Province', 'Philippines', '1234', 0),
(1, 'permanent', '456', 'Permanent Street', 'Permanent Barangay', 'Permanent City', 'Permanent Province', 'Philippines', '5678', 0);
*/