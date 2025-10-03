<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MailTest extends Controller
{
    public function index()
    {
        $email = \Config\Services::email();
        
        $email->setTo('nablessing@my.cspc.edu.ph'); // palitan ng email mo
        $email->setFrom(env('EMAIL_FROM_ADDRESS'), env('EMAIL_FROM_NAME'));
        $email->setSubject('CodeIgniter 4 Email Test');
        $email->setMessage('<h1>Hello!</h1><p>This is a test email from CI4 using Mailtrap.</p>');
        
        if ($email->send()) {
            return '✅ Email sent! Check your Mailtrap inbox.';
        } else {
            return $email->printDebugger(['headers', 'subject', 'body']);
        }
    }
    
    public function testTeacherEmail()
    {
        try {
            $emailService = new \App\Libraries\EmailService();
            
            $testTeacherData = [
                'first_name' => 'Test',
                'last_name' => 'Teacher',
                'email' => 'nablessing@my.cspc.edu.ph' // Change to your test email
            ];
            
            $testCredentials = [
                'account_no' => 'TCH-2025-TEST',
                'password' => 'TestPass123!'
            ];
            
            $result = $emailService->sendTeacherWelcomeEmail($testTeacherData, $testCredentials);
            
            if ($result['success']) {
                return '✅ Teacher welcome email sent successfully! Check your inbox.';
            } else {
                return '❌ Teacher email failed: ' . $result['message'];
            }
        } catch (\Exception $e) {
            return '❌ Error: ' . $e->getMessage();
        }
    }
}
