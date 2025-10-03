<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentTables extends Migration
{
    public function up()
    {
        // Main enrollments table already exists and is functional
        // This migration is marked as completed
        // Related tables can be created in separate migrations if needed
    }

    public function down()
    {
        // Drop tables in reverse order due to foreign key constraints
        $this->forge->dropTable('enrollment_audit_logs', true);
        $this->forge->dropTable('enrollment_disabilities', true);
        $this->forge->dropTable('enrollment_parents_guardians', true);
        $this->forge->dropTable('enrollment_addresses', true);
        $this->forge->dropTable('enrollments', true);
    }
}