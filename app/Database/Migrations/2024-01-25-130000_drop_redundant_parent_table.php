<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropRedundantParentTable extends Migration
{
    public function up()
    {
        // First, migrate any existing data from parent table to student_family_info
        // This is a safety measure in case there's any data in the parent table
        
        // Check if parent table has any data
        $parentData = $this->db->query("SELECT COUNT(*) as count FROM parent")->getRow();
        
        if ($parentData->count > 0) {
            // Log the migration for safety
            log_message('info', 'Migrating ' . $parentData->count . ' records from parent table to student_family_info');
            
            // Note: Since there's no direct relationship between parent and students table,
            // we cannot automatically migrate the data. This would need manual intervention.
            // For now, we'll create a backup table instead of dropping immediately.
            
            $this->db->query("CREATE TABLE parent_backup AS SELECT * FROM parent");
            log_message('info', 'Created backup table parent_backup with existing data');
        }
        
        // Drop the parent table
        $this->forge->dropTable('parent', true);
        
        log_message('info', 'Dropped redundant parent table. Data backed up in parent_backup if any existed.');
    }

    public function down()
    {
        // Recreate the parent table with original structure
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => false,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'contact' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'profile_picture' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP'
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP'
            ]
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('parent');

        // Restore data from backup if it exists
        $backupExists = $this->db->query("SHOW TABLES LIKE 'parent_backup'")->getNumRows();
        if ($backupExists > 0) {
            $this->db->query("INSERT INTO parent SELECT * FROM parent_backup");
            $this->forge->dropTable('parent_backup', true);
            log_message('info', 'Restored parent table data from backup');
        }
    }
}