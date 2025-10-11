<?php

namespace App\Services;

use CodeIgniter\Email\Email;
use App\Models\EmailLogModel;
use Exception;

class EmailService
{
    protected $email;
    protected $emailLogModel;
    protected $config;
    protected $maxRetries = 3;
    protected $retryDelay = 5; // seconds

    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->config = config('Email');
        $this->emailLogModel = new EmailLogModel();
    }

    /**
     * Send enrollment approval email with account details
     */
    public function sendEnrollmentApprovalEmail($enrollmentData, $accountData)
    {
        $emailData = [
            'enrollment_id' => $enrollmentData->id,
            'email_address' => $enrollmentData->email,
            'email_type' => 'enrollment_approval',
            'status' => 'pending',
            'sent_at' => date('Y-m-d H:i:s')
        ];

        // Log the email attempt
        $logId = $this->emailLogModel->insert($emailData);

        try {
            // Configure email using the same configuration as teacher emails
            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($enrollmentData->email);
            $this->email->setSubject('Enrollment Application Approved - Account Created');

            // Prepare email content using template (like teacher emails)
            $templateData = [
                'studentName' => $enrollmentData->first_name . ' ' . $enrollmentData->last_name,
                'enrollmentData' => $enrollmentData,
                'accountData' => $accountData,
                'loginUrl' => base_url('/login')
            ];
            
            // Load and render email template
            $message = view('email-templates/enrollment-approval', $templateData);
            $this->email->setMessage($message);

            // Send email with retry mechanism
            $success = $this->sendWithRetry();

            if ($success) {
                // Update log as successful
                $this->emailLogModel->update($logId, [
                    'status' => 'success',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                
                log_message('info', "Enrollment approval email sent successfully to {$enrollmentData->email} for enrollment ID {$enrollmentData->id}");
                return true;
            } else {
                throw new Exception('Failed to send email after all retry attempts');
            }
        } catch (Exception $e) {
            // Update log with error
            $this->emailLogModel->update($logId, [
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            log_message('error', "Failed to send enrollment approval email to {$enrollmentData->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email with retry mechanism
     */
    protected function sendWithRetry()
    {
        $attempts = 0;
        
        while ($attempts < $this->maxRetries) {
            try {
                if ($this->email->send()) {
                    return true;
                }
                
                $attempts++;
                if ($attempts < $this->maxRetries) {
                    sleep($this->retryDelay);
                    log_message('warning', "Email send attempt {$attempts} failed, retrying...");
                }
            } catch (Exception $e) {
                $attempts++;
                if ($attempts < $this->maxRetries) {
                    sleep($this->retryDelay);
                    log_message('warning', "Email send attempt {$attempts} failed with exception: " . $e->getMessage() . ", retrying...");
                } else {
                    throw $e;
                }
            }
        }
        
        return false;
    }

    /**
     * Build the approval email content
     */
    protected function buildApprovalEmailContent($enrollmentData, $accountData)
    {
        $studentName = $enrollmentData->first_name . ' ' . $enrollmentData->last_name;
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .account-details { background-color: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0; }
                .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
                .important { color: #dc3545; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Sta. Justina National High School</h1>
                <h2>Enrollment Application Approved</h2>
            </div>
            
            <div class='content'>
                <p>Dear {$studentName},</p>
                
                <p>Congratulations! We are pleased to inform you that your enrollment application has been <strong>approved</strong>.</p>
                
                <p>Your student account has been created with the following details:</p>
                
                <div class='account-details'>
                    <h3>Account Information</h3>
                    <p><strong>Student Account Number:</strong> {$accountData['student_number']}</p>
                    <p><strong>Temporary Password:</strong> <span class='important'>{$accountData['password']}</span></p>
                    <p><strong>Email:</strong> {$enrollmentData->email}</p>
                </div>
                
                <div class='important'>
                    <h3>Important Security Notice:</h3>
                    <ul>
                        <li>Please change your password immediately after your first login</li>
                        <li>Do not share your login credentials with anyone</li>
                        <li>Keep this email secure and delete it after changing your password</li>
                    </ul>
                </div>
                
                <h3>Next Steps:</h3>
                <ol>
                    <li>Visit our student portal at: <a href='#'>https://portal.stajustina.edu</a></li>
                    <li>Log in using your student account number and temporary password</li>
                    <li>Complete your profile setup</li>
                    <li>Change your password to something secure</li>
                    <li>Review your enrollment details and course schedule</li>
                </ol>
                
                <p>If you have any questions or need assistance, please contact our admissions office at:</p>
                <ul>
                    <li>Email: admissions@stajustina.edu</li>
                    <li>Phone: (123) 456-7890</li>
                    <li>Office Hours: Monday-Friday, 8:00 AM - 5:00 PM</li>
                </ul>
                
                <p>Welcome to Sta. Justina National High School! We look forward to supporting your academic journey.</p>
                
                <p>Best regards,<br>
                <strong>Admissions Office</strong><br>
                Sta. Justina National High School</p>
            </div>
            
            <div class='footer'>
                <p>This is an automated message. Please do not reply to this email.</p>
                <p>© 2024 Sta. Justina National High School. All rights reserved.</p>
            </div>
        </body>
        </html>
        ";
        
        return $message;
    }

    /**
     * Get email logs for audit purposes
     */
    public function getEmailLogs($filters = [])
    {
        $builder = $this->emailLogModel->builder();
        
        if (!empty($filters['enrollment_id'])) {
            $builder->where('enrollment_id', $filters['enrollment_id']);
        }
        
        if (!empty($filters['email_type'])) {
            $builder->where('email_type', $filters['email_type']);
        }
        
        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('sent_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('sent_at <=', $filters['date_to']);
        }
        
        return $builder->orderBy('sent_at', 'DESC')->findAll();
    }

    /**
     * Retry failed emails
     */
    public function retryFailedEmails($limit = 10)
    {
        $failedEmails = $this->emailLogModel->where('status', 'failed')
            ->where('updated_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
            ->limit($limit)
            ->findAll();
        
        $retryCount = 0;
        
        foreach ($failedEmails as $emailLog) {
            // Here you would need to reconstruct the email data and retry
            // This is a simplified version - in practice, you might want to store
            // the original email data or have a more sophisticated retry mechanism
            log_message('info', "Attempting to retry failed email ID: {$emailLog['id']}");
            $retryCount++;
        }
        
        return $retryCount;
    }

    /**
     * Send announcement notification emails to the specified audience.
     * Minimal implementation to satisfy controller usage; extend as needed.
     */
    public function sendAnnouncementNotification($announcement, $audienceType)
    {
        try {
            // Basic fan-out: for now just send to admins (or configured test email) to verify pipeline
            $to = $announcement['test_email'] ?? ($this->config->fromEmail ?? null);
            if (!$to) {
                log_message('warning', 'Announcement email skipped: no recipient configured');
                return ['success' => 0, 'failed' => 0];
            }

            $this->email->setFrom($this->config->fromEmail, $this->config->fromName);
            $this->email->setTo($to);
            $this->email->setSubject('[Announcement] ' . ($announcement['title'] ?? ''));
            $message = view('email-templates/announcement-basic', [
                'title' => $announcement['title'] ?? '',
                'content' => $announcement['content'] ?? '',
                'audience' => $audienceType
            ]);
            $this->email->setMessage($message);
            $ok = $this->sendWithRetry();
            return ['success' => $ok ? 1 : 0, 'failed' => $ok ? 0 : 1];
        } catch (Exception $e) {
            log_message('error', 'sendAnnouncementNotification error: ' . $e->getMessage());
            return ['success' => 0, 'failed' => 1];
        }
    }
}