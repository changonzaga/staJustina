<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DropClassSubjectSchoolYear extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

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

        $columns = $db->getFieldNames('classes');

        if (in_array('subject_id', $columns, true)) {
            $dropConstraintForColumn('classes', 'subject_id');
            $db->query('ALTER TABLE `classes` DROP COLUMN `subject_id`');
        }

        if (in_array('school_year_id', $columns, true)) {
            $dropConstraintForColumn('classes', 'school_year_id');
            $db->query('ALTER TABLE `classes` DROP COLUMN `school_year_id`');
        }
    }
}


