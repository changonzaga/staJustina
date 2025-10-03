<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameEmailLogsTable extends Migration
{
    public function up()
    {
        // Check if email_logs table exists before renaming
        if ($this->db->tableExists('email_logs')) {
            // Rename the table from email_logs to enrollment_email_logs
            $this->forge->renameTable('email_logs', 'enrollment_email_logs');
        }
    }

    public function down()
    {
        // Check if enrollment_email_logs table exists before renaming back
        if ($this->db->tableExists('enrollment_email_logs')) {
            // Rename the table back from enrollment_email_logs to email_logs
            $this->forge->renameTable('enrollment_email_logs', 'email_logs');
        }
    }
}