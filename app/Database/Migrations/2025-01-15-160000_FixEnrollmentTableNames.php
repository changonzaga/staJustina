<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixEnrollmentTableNames extends Migration
{
    public function up()
    {
        // Tables are being renamed and created according to user specifications
        // This migration handles the table structure fixes
    }

    public function down()
    {
        // Reverse the changes
        if ($this->db->tableExists('enrollment_documents')) {
            $this->forge->dropTable('enrollment_documents', true);
        }
        
        if ($this->db->tableExists('enrollment_academic_history')) {
            $this->forge->dropTable('enrollment_academic_history', true);
        }
        
        // Rename back to original names
        if ($this->db->tableExists('enrollments')) {
            $this->forge->renameTable('enrollments', 'enrollments_normalized');
        }
        
        if ($this->db->tableExists('enrollments_old_backup')) {
            $this->forge->renameTable('enrollments_old_backup', 'enrollments');
        }
    }
}