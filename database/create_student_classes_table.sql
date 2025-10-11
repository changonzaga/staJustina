-- Create only the missing student_classes table
-- This table links students to their enrolled classes

USE stajustina_db;

CREATE TABLE IF NOT EXISTS `student_classes` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` int(11) UNSIGNED NOT NULL,
    `class_id` int(11) UNSIGNED NOT NULL,
    `enrollment_date` date NOT NULL,
    `status` enum('enrolled', 'dropped', 'transferred', 'graduated') NOT NULL DEFAULT 'enrolled',
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_student_class` (`student_id`, `class_id`),
    INDEX `idx_student` (`student_id`),
    INDEX `idx_class` (`class_id`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student enrollment in classes';
