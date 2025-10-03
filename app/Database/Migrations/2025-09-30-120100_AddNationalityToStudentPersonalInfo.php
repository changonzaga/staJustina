<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNationalityToStudentPersonalInfo extends Migration
{
    public function up()
    {
        // Add nationality column to student_personal_info if missing
        $columnExists = $this->db->query("SHOW COLUMNS FROM student_personal_info LIKE 'nationality'")->getNumRows() > 0;
        if (!$columnExists) {
            $fields = [
                'nationality' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'after' => 'gender',
                ],
            ];
            $this->forge->addColumn('student_personal_info', $fields);
        }
    }

    public function down()
    {
        // Drop nationality column if it exists
        $this->forge->dropColumn('student_personal_info', 'nationality');
    }
}