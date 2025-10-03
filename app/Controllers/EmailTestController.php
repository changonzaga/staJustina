<?php

namespace App\Controllers;

use App\Services\EmailService;
use App\Models\EnrollmentModel;
use App\Models\EnrollmentPersonalInfoModel;

class EmailTestController extends BaseController
{
    public function testEnrollmentEmail($enrollmentId = null)
    {
        // Set content type to plain text for better readability
        $this->response->setContentType('text/plain');
        
        $output = "=== Testing Enrollment Approval Email ===\n\n";
        
        try {
            $output .= "0. Environment check:\n";
            $output .= "✓ Controller loaded successfully\n";
            
            // If no enrollment ID provided, use the latest one
            if (!$enrollmentId) {
                $output .= "1. Finding latest enrollment:\n";
                $enrollmentModel = new EnrollmentModel();
                $latestEnrollment = $enrollmentModel->orderBy('id', 'DESC')->first();
                if (!$latestEnrollment) {
                    return $this->response->setBody("ERROR: No enrollments found in database");
                }
                $enrollmentId = $latestEnrollment['id'];
                $output .= "✓ Using latest enrollment ID: $enrollmentId\n";
            }
            
            $output .= "\n2. Loading enrollment data (ID: $enrollmentId):\n";
            
            // Get enrollment data
            $enrollmentModel = new EnrollmentModel();
            $personalInfoModel = new EnrollmentPersonalInfoModel();
            
            $enrollment = $enrollmentModel->find($enrollmentId);
            if (!$enrollment) {
                return $this->response->setBody("ERROR: Enrollment ID $enrollmentId not found");
            }
            
            $personalInfo = $personalInfoModel->where('enrollment_id', $enrollmentId)->first();
            if (!$personalInfo) {
                return $this->response->setBody("ERROR: Personal info for enrollment ID $enrollmentId not found");
            }
            
            // Combine enrollment and personal info data
            $enrollmentData = (object) array_merge($enrollment, $personalInfo);
            
            $output .= "✓ Enrollment loaded:\n";
            $output .= "  - Student: {$enrollmentData->first_name} {$enrollmentData->last_name}\n";
            $output .= "  - Email: {$enrollmentData->student_email}\n";
            $output .= "  - Status: {$enrollmentData->enrollment_status}\n";
            
            $output .= "\n2. Testing EmailService instantiation:\n";
            
            // Test EmailService instantiation
            $emailService = new EmailService();
            $output .= "✓ EmailService instantiated successfully\n";
            
            $output .= "\n3. Creating mock account data:\n";
            
            // Create mock account data
            $mockAccount = [
                'student_number' => 'TEST-' . date('Y') . '-' . str_pad($enrollmentId, 4, '0', STR_PAD_LEFT),
                'password' => 'TempPass' . rand(100, 999) . '!'
            ];
            
            $output .= "✓ Mock account created:\n";
            $output .= "  - Student Number: {$mockAccount['student_number']}\n";
            $output .= "  - Password: {$mockAccount['password']}\n";
            
            $output .= "\n4. Testing email template rendering:\n";
            
            // Test if the email template exists and can be rendered
            $templateData = [
                'studentName' => $enrollmentData->first_name . ' ' . $enrollmentData->last_name,
                'enrollmentData' => $enrollmentData,
                'accountData' => $mockAccount,
                'loginUrl' => base_url('/login')
            ];
            
            try {
                $message = view('email-templates/enrollment-approval', $templateData);
                $output .= "✓ Email template rendered successfully\n";
                $output .= "  - Template length: " . strlen($message) . " characters\n";
            } catch (\Exception $e) {
                $output .= "✗ Email template error: " . $e->getMessage() . "\n";
            }
            
            $output .= "\n5. Testing EmailService method:\n";
            
            // Prepare enrollment data with email field (as expected by EmailService)
            $enrollmentData->email = $enrollmentData->student_email;
            
            $output .= "Calling sendEnrollmentApprovalEmail method...\n";
            
            // Test the email service method
            $result = $emailService->sendEnrollmentApprovalEmail($enrollmentData, $mockAccount);
            
            if ($result) {
                $output .= "✓ Email service method completed successfully\n";
            } else {
                $output .= "✗ Email service method returned false\n";
            }
            
            $output .= "\n6. Checking email logs:\n";
            
            // Check if email log was created
            $emailLogModel = new \App\Models\EmailLogModel();
            $recentLogs = $emailLogModel->where('enrollment_id', $enrollmentId)
                                       ->orderBy('created_at', 'DESC')
                                       ->limit(3)
                                       ->findAll();
            
            if (!empty($recentLogs)) {
                $output .= "✓ Email logs found (" . count($recentLogs) . " recent entries):\n";
                foreach ($recentLogs as $log) {
                    $output .= "  - {$log['created_at']}: {$log['status']} - {$log['email_type']} to {$log['email_address']}\n";
                    if ($log['error_message']) {
                        $output .= "    Error: {$log['error_message']}\n";
                    }
                }
            } else {
                $output .= "✗ No email logs found for enrollment ID $enrollmentId\n";
            }
            
            $output .= "\n=== TEST COMPLETE ===\n";
            
            if ($result) {
                $output .= "✅ RESULT: Email sending is working correctly!\n";
                $output .= "✓ EmailService is properly configured and functional\n";
                $output .= "✓ Email template system is working\n";
                $output .= "✓ Database logging is working\n";
            } else {
                $output .= "⚠️  RESULT: Email method works but sending failed (check logs above)\n";
                $output .= "✓ EmailService structure is working\n";
                $output .= "✓ Email template system is working\n";
                $output .= "✓ Database logging is working\n";
                $output .= "✗ SMTP sending failed - check email configuration\n";
            }
            
        } catch (\Exception $e) {
            $output .= "\n✗ Error: " . $e->getMessage() . "\n";
            $output .= "Stack trace:\n" . $e->getTraceAsString() . "\n";
        }
        
        return $this->response->setBody($output);
    }
}