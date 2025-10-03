<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class EmailTemplateTestController extends BaseController
{
    public function testEnrollmentTemplate()
    {
        // Mock data for testing the enrollment approval template
        $data = [
            'studentName' => 'John Doe',
            'accountData' => [
                'student_number' => 'STU-2024-001',
                'password' => 'TempPass123'
            ],
            'enrollmentData' => (object) [
                'email' => 'john.doe@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe'
            ],
            'loginUrl' => 'http://localhost:8080/student/login'
        ];

        // Load and display the template
        return view('email-templates/enrollment-approval', $data);
    }
}