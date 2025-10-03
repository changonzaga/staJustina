<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNormalizedTeacherSchema extends Migration
{
    private function tableExists($table)
    {
        return $this->db->query("SHOW TABLES LIKE '$table'")->getNumRows() > 0;
    }
    
    private function fieldExists($field, $table)
    {
        return $this->db->query("SHOW COLUMNS FROM $table LIKE '$field'")->getNumRows() > 0;
    }
    
    public function up()
    {
        // 1. Create Civil Status Reference Table
        if (!$this->tableExists('civil_status')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'unique' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('civil_status');
        }
        
        // Insert civil status data if table is empty
        if ($this->db->table('civil_status')->countAll() == 0) {
            $this->db->table('civil_status')->insertBatch([
                ['status' => 'Single'],
                ['status' => 'Married'],
                ['status' => 'Divorced'],
                ['status' => 'Widowed']
            ]);
        }

        // 2. Create Employment Status Reference Table
        if (!$this->tableExists('employment_status')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'unique' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('employment_status');
        }
        
        // Insert employment status data
        if ($this->db->table('employment_status')->countAll() == 0) {
            $this->db->table('employment_status')->insertBatch([
                ['status' => 'Regular'],
                ['status' => 'Contractual'],
                ['status' => 'Substitute'],
                ['status' => 'Part-time']
            ]);
        }

        // 3. Create Subjects Reference Table
        if (!$this->tableExists('subjects')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'subject_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'unique' => true,
                ],
                'subject_code' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => true,
                ],
                'grade_level' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => true,
                ],
                'department' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('subjects');
        }
        
        // Insert subjects data
        if ($this->db->table('subjects')->countAll() == 0) {
            $this->db->table('subjects')->insertBatch([
                ['subject_name' => 'Mathematics', 'subject_code' => 'MATH', 'department' => 'Mathematics'],
                ['subject_name' => 'Statistics', 'subject_code' => 'STAT', 'department' => 'Mathematics'],
                ['subject_name' => 'Science', 'subject_code' => 'SCI', 'department' => 'Science'],
                ['subject_name' => 'Biology', 'subject_code' => 'BIO', 'department' => 'Science'],
                ['subject_name' => 'English', 'subject_code' => 'ENG', 'department' => 'English'],
                ['subject_name' => 'Literature', 'subject_code' => 'LIT', 'department' => 'English'],
                ['subject_name' => 'Filipino', 'subject_code' => 'FIL', 'department' => 'Languages'],
                ['subject_name' => 'Social Studies', 'subject_code' => 'SS', 'department' => 'Social Studies'],
                ['subject_name' => 'Physical Education', 'subject_code' => 'PE', 'department' => 'Physical Education'],
                ['subject_name' => 'Technology and Livelihood Education', 'subject_code' => 'TLE', 'department' => 'TLE'],
                ['subject_name' => 'Music', 'subject_code' => 'MUSIC', 'department' => 'Arts'],
                ['subject_name' => 'Arts', 'subject_code' => 'ARTS', 'department' => 'Arts']
            ]);
        }

        // 4. Create Classes Table
        if (!$this->tableExists('classes')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'class_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'grade_level' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                ],
                'section' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'school_year' => [
                    'type' => 'VARCHAR',
                    'constraint' => 10,
                ],
                'capacity' => [
                    'type' => 'INT',
                    'constraint' => 3,
                    'default' => 40,
                ],
                'is_active' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey(['grade_level', 'section', 'school_year'], 'unique_class');
            $this->forge->addKey('grade_level', false, false, 'idx_grade_level');
            $this->forge->addKey('school_year', false, false, 'idx_school_year');
            $this->forge->createTable('classes');
        }
        
        // Insert sample classes
        if ($this->db->table('classes')->countAll() == 0) {
            $this->db->table('classes')->insertBatch([
                ['class_name' => 'Grade 7 - Einstein', 'grade_level' => 'Grade 7', 'section' => 'Einstein', 'school_year' => '2024-2025'],
                ['class_name' => 'Grade 7 - Newton', 'grade_level' => 'Grade 7', 'section' => 'Newton', 'school_year' => '2024-2025'],
                ['class_name' => 'Grade 8 - Darwin', 'grade_level' => 'Grade 8', 'section' => 'Darwin', 'school_year' => '2024-2025'],
                ['class_name' => 'Grade 8 - Tesla', 'grade_level' => 'Grade 8', 'section' => 'Tesla', 'school_year' => '2024-2025'],
                ['class_name' => 'Grade 9 - Curie', 'grade_level' => 'Grade 9', 'section' => 'Curie', 'school_year' => '2024-2025'],
                ['class_name' => 'Grade 10 - Hawking', 'grade_level' => 'Grade 10', 'section' => 'Hawking', 'school_year' => '2024-2025'],
                ['class_name' => 'Grade 11 - STEM A', 'grade_level' => 'Grade 11', 'section' => 'STEM A', 'school_year' => '2024-2025'],
                ['class_name' => 'Grade 11 - HUMSS A', 'grade_level' => 'Grade 11', 'section' => 'HUMSS A', 'school_year' => '2024-2025'],
                ['class_name' => 'Grade 12 - STEM A', 'grade_level' => 'Grade 12', 'section' => 'STEM A', 'school_year' => '2024-2025'],
                ['class_name' => 'Grade 12 - HUMSS A', 'grade_level' => 'Grade 12', 'section' => 'HUMSS A', 'school_year' => '2024-2025']
            ]);
        }

        // 5. Add foreign key columns to existing teachers table
        if (!$this->fieldExists('civil_status_id', 'teachers')) {
            $this->forge->addColumn('teachers', [
                'civil_status_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'age'
                ]
            ]);
        }
        
        if (!$this->fieldExists('employment_status_id', 'teachers')) {
            $this->forge->addColumn('teachers', [
                'employment_status_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'civil_status_id'
                ]
            ]);
        }

        // Migrate existing data
        $this->migrateExistingData();

        // Add foreign key constraints
        $this->addForeignKeyConstraints();
    }

    private function migrateExistingData()
    {
        // Migrate civil_status data
        $this->db->query("
            UPDATE teachers t 
            JOIN civil_status cs ON (
                (t.civil_status = 'Single' AND cs.status = 'Single') OR
                (t.civil_status = 'Married' AND cs.status = 'Married') OR
                (t.civil_status = 'Divorced' AND cs.status = 'Divorced') OR
                (t.civil_status = 'Widowed' AND cs.status = 'Widowed')
            )
            SET t.civil_status_id = cs.id
        ");

        // Migrate employment_status data
        $this->db->query("
            UPDATE teachers t 
            JOIN employment_status es ON (
                (t.employment_status = 'Regular' AND es.status = 'Regular') OR
                (t.employment_status = 'Contractual' AND es.status = 'Contractual') OR
                (t.employment_status = 'Substitute' AND es.status = 'Substitute') OR
                (t.employment_status = 'Part-time' AND es.status = 'Part-time')
            )
            SET t.employment_status_id = es.id
        ");
    }

    private function addForeignKeyConstraints()
    {
        // Add foreign keys to teachers table
        try {
            $this->db->query('ALTER TABLE teachers ADD CONSTRAINT fk_teachers_civil_status FOREIGN KEY (civil_status_id) REFERENCES civil_status(id) ON UPDATE CASCADE ON DELETE SET NULL');
        } catch (\Exception $e) {
            // Constraint may already exist
        }
        
        try {
            $this->db->query('ALTER TABLE teachers ADD CONSTRAINT fk_teachers_employment_status FOREIGN KEY (employment_status_id) REFERENCES employment_status(id) ON UPDATE CASCADE ON DELETE SET NULL');
        } catch (\Exception $e) {
            // Constraint may already exist
        }
    }

    public function down()
    {
        // Drop foreign key constraints first
        try {
            $this->db->query('ALTER TABLE teachers DROP FOREIGN KEY IF EXISTS fk_teachers_civil_status');
            $this->db->query('ALTER TABLE teachers DROP FOREIGN KEY IF EXISTS fk_teachers_employment_status');
        } catch (\Exception $e) {
            // Constraints may not exist
        }

        // Drop tables in reverse order
        $this->forge->dropTable('classes', true);
        $this->forge->dropTable('subjects', true);
        $this->forge->dropTable('employment_status', true);
        $this->forge->dropTable('civil_status', true);

        // Remove foreign key columns from teachers table
        if ($this->fieldExists('civil_status_id', 'teachers')) {
            $this->forge->dropColumn('teachers', 'civil_status_id');
        }
        if ($this->fieldExists('employment_status_id', 'teachers')) {
            $this->forge->dropColumn('teachers', 'employment_status_id');
        }
    }
}