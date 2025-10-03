<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMissingEnrollmentDataTables extends Migration
{
    public function up()
    {
        // 1. Create enrollment_addresses table (Step 2 - Address Information)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'address_type' => [
                'type' => 'ENUM',
                'constraint' => ['current', 'permanent'],
                'null' => false,
            ],
            'house_no' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'street' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'barangay' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'municipality' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'province' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'Philippines',
            ],
            'zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'is_same_as_current' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'For permanent address same as current'
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('enrollment_id');
        $this->forge->addKey('address_type');
        $this->forge->addUniqueKey(['enrollment_id', 'address_type'], 'uk_enrollment_address_type');
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollment_addresses', true);

        // 2. Create enrollment_family_info table (Step 2 - Family Information)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'relationship_type' => [
                'type' => 'ENUM',
                'constraint' => ['father', 'mother', 'guardian'],
                'null' => false,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'middle_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'contact_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('enrollment_id');
        $this->forge->addKey('relationship_type');
        $this->forge->addUniqueKey(['enrollment_id', 'relationship_type'], 'uk_enrollment_relationship');
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollment_family_info', true);

        // 3. Create enrollment_disabilities table (Step 3 - Disability Information)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'has_disability' => [
                'type' => 'ENUM',
                'constraint' => ['Yes', 'No'],
                'default' => 'No',
            ],
            'disability_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Visual Impairment, Hearing Impairment, Learning Disability, etc.'
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('enrollment_id');
        $this->forge->addKey('disability_type');
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollment_disabilities', true);

        // 4. Create shs_enrollment_details table (SHS-Specific Requirements)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'track' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => 'Academic, TVL, Sports, Arts'
            ],
            'strand' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => 'STEM, ABM, HUMSS, etc.'
            ],
            'specialization' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Track-specific specialization'
            ],
            'career_pathway' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Intended career direction'
            ],
            'subject_preferences' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Preferred elective subjects'
            ],
            'prerequisites_met' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Whether student meets track/strand prerequisites'
            ],
            'semester' => [
                'type' => 'ENUM',
                'constraint' => ['1st', '2nd'],
                'null' => true,
                'comment' => 'Current semester for SHS'
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('enrollment_id');
        $this->forge->addKey(['track', 'strand'], 'idx_track_strand');
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('shs_enrollment_details', true);

        // 5. Create enrollment_emergency_contacts table (Additional Safety Feature)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'contact_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'relationship' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => 'Relationship to student'
            ],
            'contact_number' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'is_primary' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Primary emergency contact'
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('enrollment_id');
        $this->forge->addKey('is_primary');
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollment_emergency_contacts', true);
    }

    public function down()
    {
        // Drop tables in reverse order due to foreign key constraints
        $this->forge->dropTable('enrollment_emergency_contacts', true);
        $this->forge->dropTable('shs_enrollment_details', true);
        $this->forge->dropTable('enrollment_disabilities', true);
        $this->forge->dropTable('enrollment_family_info', true);
        $this->forge->dropTable('enrollment_addresses', true);
    }
}