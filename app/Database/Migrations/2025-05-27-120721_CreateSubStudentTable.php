<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubStudentTable extends Migration
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
            'LRN' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'unique'     => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'grade_level' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'section' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'address' => [
                'type'       => 'TEXT',
            ],
            'guardian' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'contact' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at timestamp default current_timestamp',
            'updated_at timestamp default current_timestamp on update current_timestamp'

        ]);

        $this->forge->addKey('id', true); // Primary key
        $this->forge->createTable('students');
    }

    public function down()
    {
        $this->forge->dropTable('students');
    }
}
