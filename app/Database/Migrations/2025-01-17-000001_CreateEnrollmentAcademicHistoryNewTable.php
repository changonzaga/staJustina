<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentAcademicHistoryNewTable extends Migration
{
    public function up()
    {
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
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollment_academic_history_new', true); // true = IF NOT EXISTS
    }

    public function down()
    {
        $this->forge->dropTable('enrollment_academic_history_new');
    }
}