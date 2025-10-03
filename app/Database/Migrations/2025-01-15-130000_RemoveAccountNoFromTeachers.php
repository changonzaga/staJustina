<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveAccountNoFromTeachers extends Migration
{
    private function fieldExists($field, $table)
    {
        return $this->db->query("SHOW COLUMNS FROM $table LIKE '$field'")->getNumRows() > 0;
    }
    
    private function checkForeignKeyConstraints()
    {
        // Check if teacher_id in teacher_auth has foreign key constraint to teachers table
        $query = $this->db->query("
            SELECT 
                kcu.CONSTRAINT_NAME,
                kcu.COLUMN_NAME,
                kcu.REFERENCED_TABLE_NAME,
                kcu.REFERENCED_COLUMN_NAME,
                rc.DELETE_RULE,
                rc.UPDATE_RULE
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
            LEFT JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc 
                ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME 
                AND kcu.TABLE_SCHEMA = rc.CONSTRAINT_SCHEMA
            WHERE kcu.TABLE_SCHEMA = DATABASE()
            AND kcu.TABLE_NAME = 'teacher_auth' 
            AND kcu.COLUMN_NAME = 'teacher_id'
            AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        $constraints = $query->getResultArray();
        
        if (!empty($constraints)) {
            echo "Foreign Key Constraints found on teacher_auth.teacher_id:\n";
            foreach ($constraints as $constraint) {
                echo "  - Constraint: {$constraint['CONSTRAINT_NAME']}\n";
                echo "    References: {$constraint['REFERENCED_TABLE_NAME']}.{$constraint['REFERENCED_COLUMN_NAME']}\n";
                echo "    Delete Rule: {$constraint['DELETE_RULE']}\n";
                echo "    Update Rule: {$constraint['UPDATE_RULE']}\n";
            }
            return true;
        } else {
            echo "No foreign key constraints found on teacher_auth.teacher_id\n";
            return false;
        }
    }
    
    public function up()
    {
        echo "\n=== Checking Foreign Key Constraints ===\n";
        $this->checkForeignKeyConstraints();
        
        echo "\n=== Removing account_no column from teachers table ===\n";
        
        // Check if account_no field exists in teachers table
        if ($this->fieldExists('account_no', 'teachers')) {
            echo "Found account_no column in teachers table. Removing...\n";
            
            // First, let's verify data consistency
            $query = $this->db->query("
                SELECT COUNT(*) as count
                FROM teachers t
                LEFT JOIN teacher_auth ta ON t.id = ta.teacher_id
                WHERE t.account_no != ta.account_no OR ta.account_no IS NULL
            ");
            
            $result = $query->getRow();
            if ($result->count > 0) {
                echo "Warning: Found {$result->count} records with inconsistent account_no data.\n";
                echo "Please verify data integrity before proceeding.\n";
            } else {
                echo "Data consistency check passed.\n";
            }
            
            // Remove the account_no column from teachers table
            $this->forge->dropColumn('teachers', 'account_no');
            echo "Successfully removed account_no column from teachers table.\n";
        } else {
            echo "account_no column not found in teachers table. Nothing to remove.\n";
        }
    }

    public function down()
    {
        echo "\n=== Rolling back: Adding account_no column to teachers table ===\n";
        
        // Add account_no column back to teachers table
        if (!$this->fieldExists('account_no', 'teachers')) {
            $this->forge->addColumn('teachers', [
                'account_no' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'unique' => true,
                    'null' => true, // Allow null temporarily
                    'after' => 'id'
                ]
            ]);
            
            // Populate account_no from teacher_auth table
            $this->db->query("
                UPDATE teachers t 
                JOIN teacher_auth ta ON t.id = ta.teacher_id 
                SET t.account_no = ta.account_no
            ");
            
            // Make account_no NOT NULL after populating data
            $this->forge->modifyColumn('teachers', [
                'account_no' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'unique' => true,
                    'null' => false
                ]
            ]);
            
            echo "Successfully restored account_no column to teachers table.\n";
        } else {
            echo "account_no column already exists in teachers table.\n";
        }
    }
}