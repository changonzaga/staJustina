<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RestoreOriginalStudentFamilyInfoStructure extends Migration
{
    public function up()
    {
        // Drop the current enhanced table
        $this->forge->dropTable('student_family_info', true);
        
        // Recreate the original student_family_info table structure
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'relationship_type' => [
                'type' => 'ENUM',
                'constraint' => ['Father', 'Mother', 'Guardian', 'Stepfather', 'Stepmother', 'Grandparent', 'Sibling', 'Other'],
                'null' => false
            ],
            'is_primary_contact' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Primary contact for school communications'
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Mr., Mrs., Ms., Dr., etc.'
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'middle_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'suffix' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true
            ],
            'occupation' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true
            ],
            'employer' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true
            ],
            'work_address' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'monthly_income' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true
            ],
            'educational_attainment' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'phone_primary' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false
            ],
            'phone_secondary' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'facebook_account' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true
            ],
            'other_social_media' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'living_with_student' => [
                'type' => 'BOOLEAN',
                'default' => true
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
            'emergency_contact' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'comment' => 'Can be contacted in emergencies'
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP'
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP'
            ]
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('student_id', false, false, 'idx_student_family');
        $this->forge->addKey('relationship_type', false, false, 'idx_relationship');
        $this->forge->addKey('is_primary_contact', false, false, 'idx_primary_contact');
        $this->forge->addKey('phone_primary', false, false, 'idx_phone');
        
        $attributes = [
            'ENGINE' => 'InnoDB',
            'DEFAULT CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
            'COMMENT' => 'Student family members and guardian information'
        ];
        
        $this->forge->createTable('student_family_info', false, $attributes);
    }

    public function down()
    {
        // This would restore the enhanced version, but for safety we'll just drop the table
        $this->forge->dropTable('student_family_info', true);
    }
}