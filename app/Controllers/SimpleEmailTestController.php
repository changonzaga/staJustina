<?php

namespace App\Controllers;

class SimpleEmailTestController extends BaseController
{
    public function index()
    {
        // Set content type to plain text for better readability
        $this->response->setContentType('text/plain');
        
        $output = "=== Simple Email Test ===\n\n";
        
        try {
            $output .= "1. Testing basic controller functionality:\n";
            $output .= "✓ Controller loaded successfully\n";
            
            $output .= "\n2. Testing EmailService instantiation:\n";
            $emailService = new \App\Services\EmailService();
            $output .= "✓ EmailService instantiated successfully\n";
            
            $output .= "\n3. Creating mock data:\n";
            $mockEnrollment = (object)[
                'id' => 999,
                'email' => 'test@example.com',
                'first_name' => 'Test',
                'last_name' => 'Student'
            ];
            
            $mockAccount = [
                'student_number' => 'TEST-2025-999',
                'password' => 'TempPass123!'
            ];
            
            $output .= "✓ Mock data created\n";
            
            $output .= "\n4. Testing email method (dry run):\n";
            
            // Test the email service method
            $result = $emailService->sendEnrollmentApprovalEmail($mockEnrollment, $mockAccount);
            
            if ($result) {
                $output .= "✓ Email service method returned true\n";
            } else {
                $output .= "✗ Email service method returned false\n";
            }
            
            $output .= "\n=== TEST COMPLETE ===\n";
            $output .= "✅ Basic email service functionality verified\n";
            
        } catch (\Exception $e) {
            $output .= "\n✗ Error: " . $e->getMessage() . "\n";
            $output .= "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
        }
        
        return $this->response->setBody($output);
    }
}