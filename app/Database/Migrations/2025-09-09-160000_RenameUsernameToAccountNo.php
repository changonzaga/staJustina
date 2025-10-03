<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameUsernameToAccountNo extends Migration
{
    private function columnExists($table, $column)
    {
        return $this->db->query("SHOW COLUMNS FROM $table LIKE '$column'")->getNumRows() > 0;
    }
    
    public function up()
    {
        // Rename username column to account_no in users table
        if ($this->columnExists('users', 'username')) {
            $this->forge->modifyColumn('users', [
                'username' => [
                    'name' => 'account_no',
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => false
                ]
            ]);
        }
    }

    public function down()
    {
        // Rename account_no column back to username in users table
        if ($this->columnExists('users', 'account_no')) {
            $this->forge->modifyColumn('users', [
                'account_no' => [
                    'name' => 'username',
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => false
                ]
            ]);
        }
    }
}