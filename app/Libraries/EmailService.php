<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;
use Exception;

class EmailService
{
    protected $email;
    protected $config;
    
    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->config = config('Email');
    }

    /**
     * Send welcome email to new student with login credentials
     */
    public function sendStudentWelcomeEmail($studentData, $credentials)
    {
        try {
            // Email configuration
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($studentData['email']);
            $this->email->setSubject('Welcome to Sta. Justina - Your Student Login Credentials');

            // Prepare template data
            $templateData = [
                'studentName' => trim(($studentData['first_name'] ?? '') . ' ' . ($studentData['last_name'] ?? '')),
                'accountNo' => $credentials['account_no'] ?? '',
                'password' => $credentials['password'] ?? '',
                'email' => $studentData['email'] ?? '',
                'loginUrl' => base_url('/login'),
            ];

            // Load and render email template
            $emailBody = view('email-templates/student-welcome', $templateData);
            $this->email->setMessage($emailBody);

            // Send email
            if ($this->email->send()) {
                log_message('info', 'Student welcome email sent successfully to: ' . $studentData['email']);
                return [
                    'success' => true,
                    'message' => 'Student welcome email sent successfully'
                ];
            } else {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', 'Failed to send student welcome email: ' . $error);
                return [
                    'success' => false,
                    'message' => 'Failed to send student welcome email',
                    'error' => $error
                ];
            }

        } catch (\Exception $e) {
            log_message('error', 'Email service error (student welcome): ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Email service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send welcome email to new teacher with login credentials
     */
    public function sendTeacherWelcomeEmail($teacherData, $credentials)
    {
        try {
            // Email configuration
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($teacherData['email']);
            $this->email->setSubject('Welcome to STA Justina School System - Your Login Credentials');
            
            // Prepare template data
            $templateData = [
                'teacherName' => $teacherData['first_name'] . ' ' . $teacherData['last_name'],
                'accountNo' => $credentials['account_no'],
                'password' => $credentials['password'],
                'email' => $teacherData['email'],
                'loginUrl' => base_url('/login')
            ];
            
            // Load and render email template
            $emailBody = view('email-templates/teacher-welcome', $templateData);
            $this->email->setMessage($emailBody);
            
            // Send email
            if ($this->email->send()) {
                log_message('info', 'Welcome email sent successfully to: ' . $teacherData['email']);
                return [
                    'success' => true,
                    'message' => 'Welcome email sent successfully'
                ];
            } else {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', 'Failed to send welcome email: ' . $error);
                return [
                    'success' => false,
                    'message' => 'Failed to send welcome email',
                    'error' => $error
                ];
            }
            
        } catch (Exception $e) {
            log_message('error', 'Email service error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Email service error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail($teacherData, $resetToken)
    {
        try {
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($teacherData['email']);
            $this->email->setSubject('Password Reset Request - STA Justina School System');
            
            $templateData = [
                'teacherName' => $teacherData['first_name'] . ' ' . $teacherData['last_name'],
                'resetLink' => base_url('/reset-password?token=' . $resetToken),
                'expiryTime' => '24 hours'
            ];
            
            // You can create a password-reset template later
            $emailBody = view('email-templates/password-reset', $templateData);
            $this->email->setMessage($emailBody);
            
            return $this->email->send();
            
        } catch (Exception $e) {
            log_message('error', 'Password reset email error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send enrollment decline notification email
     */
    public function sendEnrollmentDeclineEmail($enrollmentData, $reason)
    {
        try {
            // Handle both form_data format and separate table format
            if (isset($enrollmentData['form_data']) && !empty($enrollmentData['form_data'])) {
                // Old format with form_data JSON
                $formData = is_string($enrollmentData['form_data']) ? 
                    json_decode($enrollmentData['form_data'], true) : 
                    $enrollmentData['form_data'];
                $studentName = trim(($formData['first_name'] ?? '') . ' ' . ($formData['last_name'] ?? ''));
                $recipientEmail = $formData['student_email'] ?? $formData['father_email'] ?? $formData['mother_email'] ?? null;
                $recipientName = $formData['father_first_name'] ?? $formData['mother_first_name'] ?? 'Parent/Guardian';
            } else {
                // New format with separate tables - get personal info
                $db = \Config\Database::connect();
                $personalInfo = $db->table('enrollment_personal_info')
                    ->where('enrollment_id', $enrollmentData['id'])
                    ->get()
                    ->getRowArray();
                
                if (!$personalInfo) {
                    log_message('error', "No personal info found for enrollment: {$enrollmentData['id']}");
                    return [
                        'success' => false,
                        'message' => 'No personal information found'
                    ];
                }
                
                $studentName = trim(($personalInfo['first_name'] ?? '') . ' ' . ($personalInfo['last_name'] ?? ''));
                $recipientEmail = $personalInfo['student_email'] ?? null;
                $recipientName = 'Parent/Guardian';
            }
            
            if (!$recipientEmail) {
                log_message('error', "No recipient email found for enrollment decline notification: {$enrollmentData['id']}");
                return [
                    'success' => false,
                    'message' => 'No recipient email found'
                ];
            }
            
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($recipientEmail);
            $this->email->setSubject('Enrollment Application Status - ' . $enrollmentData['enrollment_number']);
            
            $templateData = [
                'recipientName' => $recipientName,
                'studentName' => $studentName,
                'enrollmentNumber' => $enrollmentData['enrollment_number'],
                'reason' => $reason,
                'schoolName' => 'Sta. Justina National High School'
            ];
            
            // Load and render email template
            $emailBody = view('email-templates/enrollment-decline', $templateData);
            $this->email->setMessage($emailBody);
            
            // Send email
            if ($this->email->send()) {
                log_message('info', 'Enrollment decline email sent successfully to: ' . $recipientEmail);
                return [
                    'success' => true,
                    'message' => 'Decline email sent successfully',
                    'recipient' => $recipientEmail
                ];
            } else {
                $error = $this->email->printDebugger(['headers']);
                log_message('error', 'Failed to send enrollment decline email: ' . $error);
                return [
                    'success' => false,
                    'message' => 'Failed to send decline email',
                    'error' => $error
                ];
            }
            
        } catch (Exception $e) {
            log_message('error', 'Enrollment decline email error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Email service error: ' . $e->getMessage()
            ];
        }
    }
}