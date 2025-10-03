<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RestoreOriginalStudentFamilyInfoComplete extends Migration
{
    public function up()
    {
        // Drop the current table completely
        $this->forge->dropTable('student_family_info', true);
        
        // Recreate the original student_family_info table structure exactly as it was
        $sql = "CREATE TABLE student_family_info (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id INT(11) UNSIGNED NOT NULL,
            relationship_type ENUM('Father', 'Mother', 'Guardian', 'Stepfather', 'Stepmother', 'Grandparent', 'Sibling', 'Other') NOT NULL,
            is_primary_contact BOOLEAN DEFAULT FALSE COMMENT 'Primary contact for school communications',
            title VARCHAR(20) NULL COMMENT 'Mr., Mrs., Ms., Dr., etc.',
            first_name VARCHAR(100) NOT NULL,
            middle_name VARCHAR(100) NULL,
            last_name VARCHAR(100) NOT NULL,
            suffix VARCHAR(20) NULL,
            date_of_birth DATE NULL,
            occupation VARCHAR(200) NULL,
            employer VARCHAR(200) NULL,
            work_address TEXT NULL,
            monthly_income DECIMAL(12,2) NULL,
            educational_attainment VARCHAR(100) NULL,
            phone_primary VARCHAR(20) NOT NULL,
            phone_secondary VARCHAR(20) NULL,
            email VARCHAR(100) NULL,
            facebook_account VARCHAR(200) NULL,
            other_social_media TEXT NULL,
            living_with_student BOOLEAN DEFAULT TRUE,
            custody_rights BOOLEAN DEFAULT TRUE COMMENT 'Has legal custody/guardianship rights',
            authorized_pickup BOOLEAN DEFAULT TRUE COMMENT 'Authorized to pick up student',
            emergency_contact BOOLEAN DEFAULT TRUE COMMENT 'Can be contacted in emergencies',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            PRIMARY KEY (id),
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE ON UPDATE CASCADE,
            INDEX idx_student_family (student_id),
            INDEX idx_relationship (relationship_type),
            INDEX idx_primary_contact (is_primary_contact),
            INDEX idx_phone (phone_primary)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Student family members and guardian information'";
        
        $this->db->query($sql);
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('student_family_info', true);
    }
}