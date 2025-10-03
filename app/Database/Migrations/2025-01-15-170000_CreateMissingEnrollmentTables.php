<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMissingEnrollmentTables extends Migration
{
    public function up()
    {
        // Create enrollment_academic_history table (user's exact specification)
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
            'previous_gwa' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'performance_level' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'last_grade_completed' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'last_school_year' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'last_school_attended' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'school_id' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'semester' => [
                'type' => 'ENUM',
                'constraint' => ['1st', '2nd'],
                'null' => true,
            ],
            'track' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'strand' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('enrollment_id');
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollment_academic_history', true); // true = IF NOT EXISTS

        // Create enrollment_documents table (user's exact specification)
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
            'document_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'e.g., Birth Certificate, Report Card, ID'
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => false,
                'comment' => 'Path to stored file or blob if stored in DB'
            ],
            'uploaded_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('enrollment_id');
        $this->forge->addKey('document_type');
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollment_documents', true); // true = IF NOT EXISTS
    }

    public function down()
    {
        // Drop tables in reverse order due to foreign key constraints
        $this->forge->dropTable('enrollment_documents', true);
        $this->forge->dropTable('enrollment_academic_history', true);
    }
}