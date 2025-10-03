<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuthTypeToUsers extends Migration
{
    public function up()
    {
        $field_exists = $this->db->query("SHOW COLUMNS FROM users LIKE 'auth_type'")->getNumRows() > 0;
        
        if (!$field_exists) {
            $fields = [
                'auth_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => true,
                    'default' => 'email',
                    'after' => 'picture'
                ]
            ];

            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'auth_type');
    }
}