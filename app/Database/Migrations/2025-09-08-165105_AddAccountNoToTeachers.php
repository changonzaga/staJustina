<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAccountNoToTeachers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('teachers', [
            'account_no' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'unique' => true,
                'after' => 'id'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('teachers', 'account_no');
    }
}
