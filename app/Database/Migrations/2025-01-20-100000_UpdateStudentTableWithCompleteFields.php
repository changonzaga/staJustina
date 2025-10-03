<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateStudentTableWithCompleteFields extends Migration
{
    public function up()
    {
        // Add new fields to the student table
        $fields = [
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Student date of birth',
                'after' => 'name'
            ],
            'citizenship' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => 'Filipino',
                'comment' => 'Student citizenship/nationality',
                'after' => 'gender'
            ],
            'religion' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Student religion (optional)',
                'after' => 'citizenship'
            ],
            'enrollment_status' => [
                'type' => 'ENUM',
                'constraint' => ['new', 'transferee', 'continuing'],
                'default' => 'new',
                'null' => false,
                'comment' => 'Student enrollment status',
                'after' => 'section'
            ],
            'school_assigned' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Name of assigned school',
                'after' => 'enrollment_status'
            ],
            'school_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Official school identification number',
                'after' => 'school_assigned'
            ],
            'date_of_enrollment' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Date when student was enrolled',
                'after' => 'school_id'
            ],
            'residential_address' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Complete residential address',
                'after' => 'address'
            ],
            'parent_guardian_name' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
                'comment' => 'Full name of parent/guardian',
                'after' => 'residential_address'
            ],
            'parent_guardian_contact' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Contact number of parent/guardian',
                'after' => 'parent_guardian_name'
            ],
            'parent_guardian_email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Email address of parent/guardian',
                'after' => 'parent_guardian_contact'
            ],
            'emergency_contact_name' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
                'comment' => 'Emergency contact person name',
                'after' => 'parent_guardian_email'
            ],
            'emergency_contact_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'Emergency contact phone number',
                'after' => 'emergency_contact_name'
            ],
            'special_education_needs' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Special education needs or requirements',
                'after' => 'emergency_contact_number'
            ],
            'health_conditions' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Health conditions or medical information',
                'after' => 'special_education_needs'
            ],
            'previous_school_attended' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Previous school for transferee students',
                'after' => 'health_conditions'
            ],
            'previous_school_address' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Address of previous school',
                'after' => 'previous_school_attended'
            ],
            'birth_certificate_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Birth certificate or PSA document number',
                'after' => 'previous_school_address'
            ],
            'identification_documents' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'JSON array of identification document references',
                'after' => 'birth_certificate_number'
            ],
            'academic_records' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'JSON object for academic records and grades',
                'after' => 'identification_documents'
            ],
            'attendance_record' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'JSON object for attendance tracking',
                'after' => 'academic_records'
            ],
            'student_status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'graduated', 'transferred', 'dropped'],
                'default' => 'active',
                'null' => false,
                'comment' => 'Current status of the student',
                'after' => 'attendance_record'
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Additional remarks or notes about the student',
                'after' => 'student_status'
            ]
        ];

        $this->forge->addColumn('student', $fields);

        // Add indexes for better performance
        $this->forge->addKey(['enrollment_status'], false, false, 'idx_enrollment_status');
        $this->forge->addKey(['student_status'], false, false, 'idx_student_status');
        $this->forge->addKey(['date_of_enrollment'], false, false, 'idx_date_enrollment');
        $this->forge->addKey(['school_id'], false, false, 'idx_school_id');
        
        // Add unique constraint for birth certificate number if provided
        $this->db->query('ALTER TABLE student ADD UNIQUE KEY uk_birth_cert (birth_certificate_number)');
    }

    public function down()
    {
        // Remove the added columns
        $columnsToRemove = [
            'date_of_birth',
            'citizenship',
            'religion',
            'enrollment_status',
            'school_assigned',
            'school_id',
            'date_of_enrollment',
            'residential_address',
            'parent_guardian_name',
            'parent_guardian_contact',
            'parent_guardian_email',
            'emergency_contact_name',
            'emergency_contact_number',
            'special_education_needs',
            'health_conditions',
            'previous_school_attended',
            'previous_school_address',
            'birth_certificate_number',
            'identification_documents',
            'academic_records',
            'attendance_record',
            'student_status',
            'remarks'
        ];

        $this->forge->dropColumn('student', $columnsToRemove);
        
        // Drop the indexes
        $this->forge->dropKey('student', 'idx_enrollment_status');
        $this->forge->dropKey('student', 'idx_student_status');
        $this->forge->dropKey('student', 'idx_date_enrollment');
        $this->forge->dropKey('student', 'idx_school_id');
        $this->forge->dropKey('student', 'uk_birth_cert');
    }
}