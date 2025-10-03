<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnhanceStudentFamilyInfoTable extends Migration
{
    public function up()
    {
        // Add new fields to student_family_info table to make it comprehensive
        $fields = [
            'suffix' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Jr., Sr., III, etc.'
            ],
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Computed full name for display purposes'
            ],
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Parent/Guardian date of birth'
            ],
            'occupation' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
                'comment' => 'Parent/Guardian occupation'
            ],
            'employer' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
                'comment' => 'Employer name'
            ],
            'work_address' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Work address'
            ],
            'monthly_income' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
                'comment' => 'Monthly income'
            ],
            'educational_attainment' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Highest educational attainment'
            ],
            'phone_secondary' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Secondary phone number'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Email address for communication'
            ],
            'emergency_contact_name' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
                'comment' => 'Emergency contact person name'
            ],
            'emergency_contact_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Emergency contact phone number'
            ],
            'emergency_contact_relationship' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Relationship to emergency contact'
            ],
            'is_primary_contact' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Primary contact for school communications'
            ],
            'living_with_student' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'comment' => 'Whether parent/guardian lives with student'
            ],
            'custody_rights' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'comment' => 'Has legal custody/guardianship rights'
            ],
            'authorized_pickup' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'comment' => 'Authorized to pick up student'
            ],
            'profile_picture' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Profile picture file path'
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Home address'
            ],
            'facebook_account' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
                'comment' => 'Facebook account for communication'
            ],
            'other_social_media' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Other social media accounts'
            ]
        ];

        $this->forge->addColumn('student_family_info', $fields);

        // Add indexes for better performance
        $this->forge->addKey(['student_id', 'is_primary_contact'], false, false, 'idx_student_primary_contact');
        $this->forge->addKey('email', false, false, 'idx_family_email');
        $this->forge->addKey('contact_number', false, false, 'idx_family_phone');
        
        // Update the relationship_type enum to include more options
        $this->db->query("ALTER TABLE student_family_info MODIFY COLUMN relationship_type ENUM('father', 'mother', 'guardian', 'stepfather', 'stepmother', 'grandparent', 'aunt', 'uncle', 'sibling', 'other') NOT NULL");
    }

    public function down()
    {
        // Remove the added columns
        $columnsToRemove = [
            'suffix', 'full_name', 'date_of_birth', 'occupation', 'employer', 
            'work_address', 'monthly_income', 'educational_attainment', 
            'phone_secondary', 'email', 'emergency_contact_name', 
            'emergency_contact_phone', 'emergency_contact_relationship',
            'is_primary_contact', 'living_with_student', 'custody_rights',
            'authorized_pickup', 'profile_picture', 'address',
            'facebook_account', 'other_social_media'
        ];

        foreach ($columnsToRemove as $column) {
            $this->forge->dropColumn('student_family_info', $column);
        }

        // Revert relationship_type enum to original
        $this->db->query("ALTER TABLE student_family_info MODIFY COLUMN relationship_type ENUM('father', 'mother', 'guardian') NOT NULL");
    }
}