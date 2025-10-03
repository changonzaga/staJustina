<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveAccountNoFromTeachers extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('teachers', 'account_no');
    }

    public function down()
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
}
