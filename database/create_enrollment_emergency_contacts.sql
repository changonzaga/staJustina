-- Create enrollment_emergency_contacts table
-- This table mirrors the structure of student_emergency_contacts but relates to enrollments

CREATE TABLE IF NOT EXISTS `enrollment_emergency_contacts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_id` int(11) unsigned NOT NULL,
  `emergency_contact_name` varchar(100) NOT NULL,
  `emergency_contact_phone` varchar(20) NOT NULL,
  `emergency_contact_relationship` varchar(50) NOT NULL,
  `is_primary_contact` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `enrollment_id` (`enrollment_id`),
  KEY `is_primary_contact` (`is_primary_contact`),
  CONSTRAINT `fk_enrollment_emergency_contacts_enrollment` 
    FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;