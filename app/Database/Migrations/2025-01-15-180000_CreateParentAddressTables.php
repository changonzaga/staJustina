<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateParentAddressTables extends Migration
{
    public function up()
    {
        // Create enrollment_parent_address table for storing parent addresses during enrollment
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
            'parent_type' => [
                'type' => 'ENUM',
                'constraint' => ['father', 'mother', 'guardian'],
                'null' => false,
            ],
            'is_same_as_student' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '1 if same as student address, 0 if different'
            ],
            'house_number' => [
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
                'null' => true,
            ],
            'municipality' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'province' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('enrollment_id');
        $this->forge->addKey(['enrollment_id', 'parent_type']);
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollment_parent_address', true);

        // Create student_parent_address table for storing parent addresses after enrollment approval
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'parent_type' => [
                'type' => 'ENUM',
                'constraint' => ['father', 'mother', 'guardian'],
                'null' => false,
            ],
            'is_same_as_student' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '1 if same as student address, 0 if different'
            ],
            'house_number' => [
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
                'null' => true,
            ],
            'municipality' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'province' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('student_id');
        $this->forge->addKey(['student_id', 'parent_type']);
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('student_parent_address', true);
    }

    public function down()
    {
        // Drop tables in reverse order due to foreign key constraints
        $this->forge->dropTable('student_parent_address', true);
        $this->forge->dropTable('enrollment_parent_address', true);
    }
}