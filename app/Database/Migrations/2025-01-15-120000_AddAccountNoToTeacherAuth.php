<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAccountNoToTeacherAuth extends Migration
{
    private function fieldExists($field, $table)
    {
        return $this->db->query("SHOW COLUMNS FROM $table LIKE '$field'")->getNumRows() > 0;
    }
    
    public function up()
    {
        // Add account_no field to teacher_auth table if it doesn't exist
        if (!$this->fieldExists('account_no', 'teacher_auth')) {
            $this->forge->addColumn('teacher_auth', [
                'account_no' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'unique' => true,
                    'null' => true, // Temporarily allow null for existing records
                    'after' => 'teacher_id'
                ]
            ]);
            
            // Populate account_no from teachers table for existing records
            $this->db->query("
                UPDATE teacher_auth ta 
                JOIN teachers t ON ta.teacher_id = t.id 
                SET ta.account_no = t.account_no
            ");
            
            // Make account_no NOT NULL after populating data
            $this->forge->modifyColumn('teacher_auth', [
                'account_no' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'unique' => true,
                    'null' => false
                ]
            ]);
            
            // Add index for faster lookups
            $this->forge->addKey('account_no', false, true); // unique key
        }
    }

    public function down()
    {
        // Remove account_no field from teacher_auth table
        if ($this->fieldExists('account_no', 'teacher_auth')) {
            $this->forge->dropColumn('teacher_auth', 'account_no');
        }
    }
}