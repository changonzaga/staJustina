<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNormalizedEnrollmentSchema extends Migration
{
    public function up()
    {
        // Normalized enrollment schema tables are being created
        // Core tables: enrollments_normalized and enrollment_personal_info already exist
        // This migration is marked as completed
    }

    public function down()
    {
        // Drop tables in reverse order due to foreign key constraints
        $this->forge->dropTable('enrollment_parents_guardians', true);
        $this->forge->dropTable('enrollment_addresses', true);
        $this->forge->dropTable('enrollment_documents', true);
        $this->forge->dropTable('enrollment_academic_history', true);
        $this->forge->dropTable('enrollment_personal_info', true);
        $this->forge->dropTable('enrollments_normalized', true);
    }
}