<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveIsSuperAdminFromUsers extends Migration
{
    private function fieldExists($field, $table)
    {
        return $this->db->query("SHOW COLUMNS FROM $table LIKE '$field'")->getNumRows() > 0;
    }
    
    public function up()
    {
        // Remove is_super_admin field if it exists
        if ($this->fieldExists('is_super_admin', 'users')) {
            $this->forge->dropColumn('users', 'is_super_admin');
        }
    }

    public function down()
    {
        // Add back is_super_admin field if it doesn't exist
        if (!$this->fieldExists('is_super_admin', 'users')) {
            $this->forge->addColumn('users', [
                'is_super_admin' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'after' => 'role'
                ]
            ]);
        }
    }
}