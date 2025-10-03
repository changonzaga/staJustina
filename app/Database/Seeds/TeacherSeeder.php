<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        // Sample teacher data for testing
        $teachers = [
            [
                'account_no' => 'TCH-2025-001',
                'first_name' => 'Maria',
                'middle_name' => 'Santos',
                'last_name' => 'Rodriguez',
                'date_of_birth' => '1985-03-15',
                'gender' => 'Female',
                'contact_number' => '09123456789',
                'employee_id' => 'EMP-001',
                'educational_attainment' => 'Master of Arts in Education',
                'employment_status' => 'Regular',
                'position' => 'Senior High School Teacher',
                'teaching_assignment' => 'Mathematics, Statistics',
                'school_assigned' => 'Sta. Justina National High School',
                'eligibility_status' => 'Licensed Professional Teacher',
                'civil_status' => 'Married',
                'residential_address' => '123 Main St, Sta. Justina, Camarines Sur',
                'permanent_address' => '123 Main St, Sta. Justina, Camarines Sur',
                'prc_license_number' => 'PRC-123456',
                'nationality' => 'Filipino',
                'specialization' => 'Mathematics',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'account_no' => 'TCH-2025-002',
                'first_name' => 'John',
                'middle_name' => 'Cruz',
                'last_name' => 'Dela Cruz',
                'date_of_birth' => '1982-07-22',
                'gender' => 'Male',
                'contact_number' => '09987654321',
                'employee_id' => 'EMP-002',
                'educational_attainment' => 'Bachelor of Science in Education',
                'employment_status' => 'Regular',
                'position' => 'Junior High School Teacher',
                'teaching_assignment' => 'Science, Biology',
                'school_assigned' => 'Sta. Justina National High School',
                'eligibility_status' => 'Licensed Professional Teacher',
                'civil_status' => 'Single',
                'residential_address' => '456 School Ave, Sta. Justina, Camarines Sur',
                'permanent_address' => '789 Home St, Naga City, Camarines Sur',
                'prc_license_number' => 'PRC-789012',
                'nationality' => 'Filipino',
                'specialization' => 'Science',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'account_no' => 'TCH-2025-003',
                'first_name' => 'Ana',
                'middle_name' => 'Reyes',
                'last_name' => 'Garcia',
                'date_of_birth' => '1990-11-08',
                'gender' => 'Female',
                'contact_number' => '09456789123',
                'employee_id' => 'EMP-003',
                'educational_attainment' => 'Bachelor of Arts in English',
                'employment_status' => 'Contractual',
                'position' => 'English Teacher',
                'teaching_assignment' => 'English, Literature',
                'school_assigned' => 'Sta. Justina National High School',
                'eligibility_status' => 'Licensed Professional Teacher',
                'civil_status' => 'Single',
                'residential_address' => '321 Teacher St, Sta. Justina, Camarines Sur',
                'permanent_address' => '321 Teacher St, Sta. Justina, Camarines Sur',
                'prc_license_number' => 'PRC-345678',
                'nationality' => 'Filipino',
                'specialization' => 'English',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Authentication credentials for teachers
        $teacherAuth = [
            [
                'email' => 'maria.rodriguez@stajustina.edu.ph',
                'password' => 'teacher123',
                'auth_type' => 'email'
            ],
            [
                'email' => 'john.delacruz@stajustina.edu.ph',
                'password' => 'teacher123',
                'auth_type' => 'email'
            ],
            [
                'email' => 'ana.garcia@stajustina.edu.ph',
                'password' => 'teacher123',
                'auth_type' => 'email'
            ]
        ];

        // Insert teachers and their authentication data
        foreach ($teachers as $index => $teacherData) {
            // Insert teacher record
            $this->db->table('teachers')->insert($teacherData);
            $teacherId = $this->db->insertID();

            // Insert teacher authentication record
            $authData = [
                'teacher_id' => $teacherId,
                'email' => $teacherAuth[$index]['email'],
                'password' => password_hash($teacherAuth[$index]['password'], PASSWORD_DEFAULT),
                'auth_type' => $teacherAuth[$index]['auth_type'],
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->db->table('teacher_auth')->insert($authData);

            // Insert user record for centralized authentication
            $userData = [
                'name' => trim($teacherData['first_name'] . ' ' . ($teacherData['middle_name'] ? $teacherData['middle_name'] . ' ' : '') . $teacherData['last_name']),
                'account_no' => strtolower($teacherData['first_name'] . '.' . $teacherData['last_name']),
                'email' => $teacherAuth[$index]['email'],
                'password' => password_hash($teacherAuth[$index]['password'], PASSWORD_DEFAULT),
                'role' => 'teacher',
                'auth_type' => 'email',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->db->table('users')->insert($userData);

            echo "Created teacher: {$userData['name']} ({$teacherAuth[$index]['email']})\n";
        }

        echo "\n=== Teacher Login Credentials ===\n";
        echo "Email: maria.rodriguez@stajustina.edu.ph | Password: teacher123\n";
        echo "Email: john.delacruz@stajustina.edu.ph | Password: teacher123\n";
        echo "Email: ana.garcia@stajustina.edu.ph | Password: teacher123\n";
        echo "\nYou can now login at: http://localhost:8080/login\n";
    }
}
