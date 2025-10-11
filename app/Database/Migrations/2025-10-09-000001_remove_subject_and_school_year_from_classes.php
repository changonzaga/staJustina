<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveSubjectAndSchoolYearFromClasses extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Helper to drop FK by column name dynamically
        $dropConstraintForColumn = function (string $table, string $column) use ($db) {
            $sql = "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL";
            $rows = $db->query($sql, [$table, $column])->getResultArray();
            foreach ($rows as $row) {
                $constraint = $row['CONSTRAINT_NAME'] ?? null;
                if ($constraint) {
                    $db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraint}`");
                }
            }
        };

        // Check existing columns first to avoid errors
        $columns = $db->getFieldNames('classes');

        // subject_id: drop FK then column
        if (in_array('subject_id', $columns, true)) {
            $dropConstraintForColumn('classes', 'subject_id');
            $db->query('ALTER TABLE `classes` DROP COLUMN `subject_id`');
        }

        // school_year_id: drop FK then column if present
        if (in_array('school_year_id', $columns, true)) {
            $dropConstraintForColumn('classes', 'school_year_id');
            $db->query('ALTER TABLE `classes` DROP COLUMN `school_year_id`');
        }
    }

    public function down()
    {
        // Optional: recreate columns without data (no FKs re-added automatically)
        $db = \Config\Database::connect();
        $columns = $db->getFieldNames('classes');

        if (!in_array('subject_id', $columns, true)) {
            $db->query('ALTER TABLE `classes` ADD COLUMN `subject_id` INT(11) UNSIGNED NULL');
        }

        if (!in_array('school_year_id', $columns, true)) {
            $db->query('ALTER TABLE `classes` ADD COLUMN `school_year_id` INT(11) UNSIGNED NULL');
        }
    }
}


