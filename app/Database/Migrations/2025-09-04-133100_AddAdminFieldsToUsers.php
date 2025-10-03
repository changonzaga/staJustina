<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminFieldsToUsers extends Migration
{
    private function fieldExists($field, $table)
    {
        return $this->db->query("SHOW COLUMNS FROM $table LIKE '$field'")->getNumRows() > 0;
    }
    
    public function up()
    {
        // Add role field
        if (!$this->fieldExists('role', 'users')) {
            $this->forge->addColumn('users', [
                'role' => [
                    'type' => 'ENUM',
                    'constraint' => ['admin', 'teacher', 'student', 'parent'],
                    'default' => 'admin',
                    'after' => 'auth_type'
                ]
            ]);
        }
        
        // Add is_super_admin field
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
        
        // Add permissions field
        if (!$this->fieldExists('permissions', 'users')) {
            $this->forge->addColumn('users', [
                'permissions' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'is_super_admin'
                ]
            ]);
        }
        
        // Add last_login_at field
        if (!$this->fieldExists('last_login_at', 'users')) {
            $this->forge->addColumn('users', [
                'last_login_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'permissions'
                ]
            ]);
        }
        
        // Add status field
        if (!$this->fieldExists('status', 'users')) {
            $this->forge->addColumn('users', [
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['active', 'inactive', 'suspended'],
                    'default' => 'active',
                    'after' => 'last_login_at'
                ]
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['role', 'is_super_admin', 'permissions', 'last_login_at', 'status']);
    }
}