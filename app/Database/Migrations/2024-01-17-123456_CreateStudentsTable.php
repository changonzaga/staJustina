<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'lrn' => [
                'type'       => 'VARCHAR',
                'constraint' => 12,
            ],
            'grade_level' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'section' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'age' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'guardian' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'contact' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'profile_picture' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'teacher_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('lrn');
        $this->forge->createTable('students');
    }

    public function down()
    {
        $this->forge->dropTable('students');
    }
}