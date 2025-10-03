<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropAdminsTable extends Migration
{
    private function tableExists($table)
    {
        return $this->db->query("SHOW TABLES LIKE '$table'")->getNumRows() > 0;
    }
    
    private function foreignKeyExists($table, $constraint)
    {
        $query = $this->db->query(
            "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
             WHERE TABLE_SCHEMA = DATABASE() 
             AND TABLE_NAME = '$table' 
             AND CONSTRAINT_NAME = '$constraint'"
        );
        return $query->getNumRows() > 0;
    }
    
    public function up()
    {
        // First, drop any foreign key constraints that might reference the admins table
        if ($this->tableExists('users')) {
            // Check for common foreign key constraint names and drop them if they exist
            $possibleConstraints = [
                'fk_users_admin_id',
                'users_admin_id_foreign',
                'users_ibfk_1',
                'users_ibfk_2'
            ];
            
            foreach ($possibleConstraints as $constraint) {
                if ($this->foreignKeyExists('users', $constraint)) {
                    $this->db->query("ALTER TABLE users DROP FOREIGN KEY $constraint");
                }
            }
            
            // Also drop any admin_id column if it exists in users table
            $adminIdExists = $this->db->query("SHOW COLUMNS FROM users LIKE 'admin_id'")->getNumRows() > 0;
            if ($adminIdExists) {
                $this->forge->dropColumn('users', 'admin_id');
            }
        }
        
        // Drop the admins table if it exists
        if ($this->tableExists('admins')) {
            $this->forge->dropTable('admins', true);
        }
    }

    public function down()
    {
        // Note: This is a destructive operation, so we won't recreate the table
        // If you need to rollback, you'll need to restore from backup
        log_message('warning', 'Cannot rollback DropAdminsTable migration - this is a destructive operation');
    }
}