<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentAcademicHistoryNewFromDb2 extends Migration
{
    public function up()
    {
        // Create enrollment_academic_history_new table based on structure from stajustina_db2
        // This table is essential for returning learners and transfer students
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
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('enrollment_id');
        
        // Add foreign key constraint to enrollments table
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        
        // Create table with IF NOT EXISTS to avoid conflicts
        $this->forge->createTable('enrollment_academic_history_new', true);
    }

    public function down()
    {
        $this->forge->dropTable('enrollment_academic_history_new');
    }
}