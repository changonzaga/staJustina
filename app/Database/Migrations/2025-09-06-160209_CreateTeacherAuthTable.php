<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeacherAuthTable extends Migration
{
    private function tableExists($table)
    {
        return $this->db->query("SHOW TABLES LIKE '$table'")->getNumRows() > 0;
    }
    
    public function up()
    {
        if (!$this->tableExists('teacher_auth')) {
            $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'teacher_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'unique' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Nullable for Google OAuth users',
            ],
            'auth_type' => [
                'type' => 'ENUM',
                'constraint' => ['email', 'google'],
                'default' => 'email',
            ],
            'last_login_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('teacher_id');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('auth_type');
        
        // Create the table
        $this->forge->createTable('teacher_auth');
        
        // Add foreign key constraint
        try {
            $this->forge->addForeignKey('teacher_id', 'teachers', 'id', 'CASCADE', 'CASCADE');
        } catch (\Exception $e) {
            // Foreign key may already exist
        }
        }
    }

    public function down()
    {
        $this->forge->dropTable('teacher_auth');
    }
}
