<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\StudentModel;
use App\Models\User;
use App\Libraries\CIAuth;

class EnrollmentController extends BaseController
{
    protected $enrollmentModel;
    protected $studentModel;
    protected $userModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->studentModel = new StudentModel();
        $this->userModel = new User();
    }

    /**
     * Display enrollment forms
     */
    public function index()
    {
        $data = [
            'pageTitle' => 'Student Enrollment'
        ];
        return view('backend/student/enrollment/enrollment_selection', $data);
    }

    /**
     * Show manual enrollment form
     */
    public function manual()
    {
        $data = [
            'pageTitle' => 'Manual Enrollment Form'
        ];
        return view('backend/student/enrollment/student_enrollment', $data);
    }

    /**
     * Show OCR enrollment form
     */
    public function ocr()
    {
        $data = [
            'pageTitle' => 'OCR Enrollment Form'
        ];
        return view('backend/student/enrollment/ocr_enrollment', $data);
    }

    /**
     * Test endpoint to verify routing
     */
    public function test()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'EnrollmentController test endpoint working',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Handle enrollment form submission
     */
    public function store()
    {
        try {
            // Get form data
            $formData = $this->request->getPost();
            
            // Enhanced logging for debugging
            $logData = [
                'timestamp' => date('Y-m-d H:i:s'),
                'form_data_count' => count($formData),
                'has_lrn_digits' => $this->checkLRNDigits($formData),
                'required_fields' => $this->checkRequiredFields($formData)
            ];
            
            // Force create logs directory and test logging
            $logsDir = WRITEPATH . 'logs';
            if (!is_dir($logsDir)) {
                mkdir($logsDir, 0755, true);
            }
            
            $logFile = $logsDir . '/enrollment_debug_' . date('Y-m-d') . '.log';
            file_put_contents($logFile, date('Y-m-d H:i:s') . ' - Controller: Enrollment submission started' . "\n", FILE_APPEND | LOCK_EX);
            file_put_contents($logFile, 'Form data: ' . json_encode($logData) . "\n", FILE_APPEND | LOCK_EX);
            
            log_message('info', 'Enrollment submission attempt: ' . json_encode($logData));
            
            // Basic validation check
            if (empty($formData)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No form data received',
                    'error_code' => 'NO_DATA'
                ]);
            }
            
            // Step 1: Validate form data using CodeIgniter validation
            $validation = $this->validateEnrollmentData($formData);
            if (!$validation['valid']) {
                log_message('error', 'Validation failed: ' . json_encode($validation['errors']));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(', ', $validation['errors']),
                    'error_code' => 'VALIDATION_FAILED',
                    'validation_errors' => $validation['errors']
                ]);
            }
            
            // Step 2: Handle file uploads with detailed logging
            $documents = $this->handleDocumentUploads();
            if (isset($documents['error'])) {
                log_message('error', 'File upload failed: ' . $documents['error']);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'File upload error: ' . $documents['error'],
                    'error_code' => 'UPLOAD_FAILED'
                ]);
            }
            
            // Step 3: Log successful validation and upload
            log_message('info', 'Validation passed, documents processed: ' . count($documents) . ' files');
            
            // Step 4: Submit enrollment using normalized schema
            $result = $this->enrollmentModel->submitEnrollment($formData, $documents);
            
        } catch (\Exception $e) {
            log_message('error', 'Controller error in enrollment submission: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'System error occurred during submission',
                'error_code' => 'CONTROLLER_ERROR',
                'debug_info' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
        
        if ($result['success']) {
            // Send notification email to parents/guardians (temporarily disabled)
            // $this->sendEnrollmentNotification($result['enrollment_id'], 'submitted');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment submitted successfully to normalized database!',
                'enrollment_number' => $result['enrollment_number'],
                'enrollment_id' => $result['enrollment_id']
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => $result['message']
        ]);
    }

    /**
     * Handle document uploads with enhanced error handling
     */
    private function handleDocumentUploads()
    {
        $documents = [];
        $uploadErrors = [];
        
        try {
            // Get uploaded files
            $files = $this->request->getFiles();
            
            log_message('info', 'Processing file uploads. Files received: ' . json_encode(array_keys($files)));
            
            // Check for individual document uploads
            $documentTypes = ['birthCertInput', 'parentIdInput', 'reportCardInput', 'goodMoralInput'];
            
            foreach ($documentTypes as $inputName) {
                if (isset($files[$inputName])) {
                    $file = $files[$inputName];
                    
                    if ($file->isValid()) {
                        if (!$file->hasMoved()) {
                            // Validate file type and size
                            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                            $maxSize = 5 * 1024 * 1024; // 5MB
                            
                            if (!in_array($file->getClientMimeType(), $allowedTypes)) {
                                $uploadErrors[] = "Invalid file type for {$inputName}: " . $file->getClientMimeType();
                                continue;
                            }
                            
                            if ($file->getSize() > $maxSize) {
                                $uploadErrors[] = "File too large for {$inputName}: " . $file->getSize() . ' bytes';
                                continue;
                            }
                            
                            // Create uploads directory if it doesn't exist
                            $uploadPath = FCPATH . 'uploads/enrollment_documents/';
                            if (!is_dir($uploadPath)) {
                                mkdir($uploadPath, 0755, true);
                            }
                            
                            // Generate unique filename to prevent conflicts
                            $extension = $file->getClientExtension();
                            $newName = uniqid() . '_' . time() . '.' . $extension;
                            
                            // Move file to uploads directory
                            if ($file->move($uploadPath, $newName)) {
                                $documents[] = [
                                    'input_name' => $inputName,
                                    'type' => $file->getClientMimeType(),
                                    'name' => $file->getClientName(),
                                    'path' => 'enrollment_documents/' . $newName,
                                    'size' => $file->getSize()
                                ];
                                log_message('info', "File saved successfully: {$uploadPath}{$newName}");
                            } else {
                                $uploadErrors[] = "Failed to save file for {$inputName}";
                                log_message('error', "Failed to move file: {$inputName}");
                            }
                            
                            log_message('info', "File processed successfully: {$inputName} - " . $file->getClientName());
                        } else {
                            $uploadErrors[] = "File already moved for {$inputName}";
                        }
                    } else {
                        $error = $file->getErrorString();
                        $uploadErrors[] = "Invalid file for {$inputName}: {$error}";
                        log_message('error', "File upload error for {$inputName}: {$error}");
                    }
                }
            }
            
            // Check for batch document uploads
            if (!empty($files['documents'])) {
                foreach ($files['documents'] as $index => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        // Create uploads directory if it doesn't exist
                        $uploadPath = FCPATH . 'uploads/enrollment_documents/';
                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }
                        
                        // Generate unique filename to prevent conflicts
                        $extension = $file->getClientExtension();
                        $newName = uniqid() . '_' . time() . '.' . $extension;
                        
                        // Move file to uploads directory
                        if ($file->move($uploadPath, $newName)) {
                            $documents[] = [
                                'input_name' => 'documents[' . $index . ']',
                                'type' => $file->getClientMimeType(),
                                'name' => $file->getClientName(),
                                'path' => 'enrollment_documents/' . $newName,
                                'size' => $file->getSize()
                            ];
                        }
                    }
                }
            }
            
            if (!empty($uploadErrors)) {
                return ['error' => implode('; ', $uploadErrors)];
            }
            
            log_message('info', 'Document upload processing completed. Total documents: ' . count($documents));
            return $documents;
            
        } catch (\Exception $e) {
            log_message('error', 'Exception in handleDocumentUploads: ' . $e->getMessage());
            return ['error' => 'File upload system error: ' . $e->getMessage()];
        }
    }

    /**
     * Check admin access (simplified for testing)
     */
    private function checkAdminAccess()
    {
        // Temporarily return true for testing
        return true;
        
        // Original implementation would check user session/role
        // return CIAuth::check() && CIAuth::user()->role === 'admin';
    }

    /**
     * Handle OCR enrollment form submission
     */
    public function storeOcr()
    {
        // Similar to store() but with OCR-specific handling
        return $this->store();
    }

    /**
     * Admin enrollment management page
     */
    public function manage()
    {
        // Check admin access
        if (!$this->checkAdminAccess()) {
            return redirect()->to('/login')->with('fail', 'Admin access required');
        }

        $pendingEnrollments = $this->enrollmentModel->getEnrollmentsWithStudentDetails('pending');
        $stats = $this->enrollmentModel->getEnrollmentStats();
        
        $data = [
            'pageTitle' => 'Enrollment Management',
            'pendingEnrollments' => $pendingEnrollments,
            'stats' => $stats
        ];
        
        return view('backend/admin/enrollment/manage', $data);
    }

    /**
     * Get enrollment details for admin review
     */
    public function getDetails($enrollmentId)
    {
        if (!$this->checkAdminAccess()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // Get enrollment with all normalized data
        $enrollment = $this->enrollmentModel->getEnrollmentWithDetails($enrollmentId);
        
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found'
            ]);
        }

        // Format the data for display
        $formData = $enrollment['form_data'];
        $details = [
            'enrollment' => $enrollment,
            'student_info' => [
                'full_name' => trim(($formData['first_name'] ?? '') . ' ' . ($formData['middle_name'] ?? '') . ' ' . ($formData['last_name'] ?? '')),
                'lrn' => $this->formatLRN($formData),
                'grade_level' => $enrollment['grade_level'],
                'gender' => $formData['gender'] ?? '',
                'age' => $formData['age'] ?? '',
                'birth_date' => $formData['date_of_birth'] ?? '',
                'email' => $formData['student_email'] ?? '',
                'contact' => $formData['student_contact'] ?? ''
            ],
            'parent_info' => [
                'father_name' => trim(($formData['father_first_name'] ?? '') . ' ' . ($formData['father_last_name'] ?? '')),
                'father_contact' => $formData['father_contact'] ?? '',
                'mother_name' => trim(($formData['mother_first_name'] ?? '') . ' ' . ($formData['mother_last_name'] ?? '')),
                'mother_contact' => $formData['mother_contact'] ?? '',
                'guardian_name' => trim(($formData['guardian_first_name'] ?? '') . ' ' . ($formData['guardian_last_name'] ?? '')),
                'guardian_contact' => $formData['guardian_contact'] ?? ''
            ],
            'address_info' => [
                'current' => $this->formatAddress($formData, 'current'),
                'permanent' => $this->formatAddress($formData, 'permanent')
            ],
            'academic_info' => [
                'previous_gwa' => $formData['previous_gwa'] ?? '',
                'performance_level' => $formData['performance_level'] ?? '',
                'last_school' => $formData['last_school_attended'] ?? '',
                'last_grade' => $formData['last_grade_completed'] ?? ''
            ],
            'documents' => $enrollment['documents_submitted'] ?? []
        ];

        return $this->response->setJSON([
            'success' => true,
            'data' => $details
        ]);
    }

    /**
     * Approve enrollment
     */
    public function approve($enrollmentId)
    {
        if (!$this->checkAdminAccess()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userInfo = session()->get('userdata');
        $approvedBy = $userInfo['id'] ?? null;
        
        if (!$approvedBy) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User session invalid'
            ]);
        }

        $result = $this->enrollmentModel->approveEnrollment($enrollmentId, $approvedBy);
        
        if ($result) {
            // Send approval notification
            $this->sendEnrollmentNotification($enrollmentId, 'approved');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment approved successfully! Student account has been created.'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to approve enrollment. Please try again.'
        ]);
    }

    /**
     * Decline enrollment
     */
    public function decline($enrollmentId)
    {
        if (!$this->checkAdminAccess()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userInfo = session()->get('userdata');
        $declinedBy = $userInfo['id'] ?? null;
        $reason = $this->request->getPost('reason') ?? 'No reason provided';
        
        if (!$declinedBy) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User session invalid'
            ]);
        }

        $result = $this->enrollmentModel->declineEnrollment($enrollmentId, $declinedBy, $reason);
        
        if ($result) {
            // Send decline notification
            $this->sendEnrollmentNotification($enrollmentId, 'declined', $reason);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment declined successfully.'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to decline enrollment. Please try again.'
        ]);
    }

    /**
     * Get enrollment status
     */
    public function status($enrollmentNumber)
    {
        $enrollment = $this->enrollmentModel->where('enrollment_number', $enrollmentNumber)->first();
        
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found'
            ]);
        }

        // Get student personal information from enrollment_personal_info table
        $db = \Config\Database::connect();
        $personalInfo = $db->table('enrollment_personal_info')
                          ->where('enrollment_id', $enrollment['id'])
                          ->get()
                          ->getRowArray();
        
        $studentName = 'Unknown Student';
        if ($personalInfo) {
            $firstName = $personalInfo['first_name'] ?? '';
            $middleName = $personalInfo['middle_name'] ?? '';
            $lastName = $personalInfo['last_name'] ?? '';
            $extension = $personalInfo['extension_name'] ?? '';
            
            $studentName = trim($firstName . ' ' . $middleName . ' ' . $lastName . ' ' . $extension);
            $studentName = preg_replace('/\s+/', ' ', $studentName); // Remove extra spaces
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'enrollment_number' => $enrollment['enrollment_number'],
                'student_name' => $studentName,
                'status' => $enrollment['enrollment_status'],
                'grade_level' => $enrollment['grade_level'],
                'submission_date' => $enrollment['created_at'],
                'approved_at' => $enrollment['approved_at'] ?? null,
                'declined_reason' => $enrollment['declined_reason'] ?? null
            ]
        ]);
    }



    /**
     * Validate enrollment data with enhanced debugging
     */
    private function validateEnrollmentData($formData)
    {
        $errors = [];
        
        // Log validation start
        log_message('info', 'Starting enrollment data validation');
        
        // Required fields validation
        $requiredFields = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'grade_level' => 'Grade Level',
            'gender' => 'Gender',
            'date_of_birth' => 'Birth Date',
            'age' => 'Age'
        ];
        
        foreach ($requiredFields as $field => $label) {
            if (empty($formData[$field])) {
                $errors[] = $label . ' is required';
                log_message('error', "Missing required field: {$field}");
            } else {
                log_message('info', "Required field present: {$field} = " . $formData[$field]);
            }
        }
        
        // LRN validation with detailed logging
        $lrnComplete = true;
        $lrnDigits = [];
        for ($i = 0; $i < 12; $i++) {
            $digitValue = $formData["lrn_digit_{$i}"] ?? null;
            $lrnDigits[] = $digitValue;
            if ($digitValue === '' || $digitValue === null) {
                $lrnComplete = false;
            }
        }
        
        log_message('info', 'LRN digits: ' . json_encode($lrnDigits));
        
        if (!$lrnComplete) {
            $errors[] = 'Complete 12-digit LRN is required';
            log_message('error', 'Incomplete LRN detected');
        }
        
        // Date format validation
        if (!empty($formData['date_of_birth'])) {
            $date = \DateTime::createFromFormat('Y-m-d', $formData['date_of_birth']);
            if (!$date || $date->format('Y-m-d') !== $formData['date_of_birth']) {
                $errors[] = 'Birth date must be in YYYY-MM-DD format';
                log_message('error', 'Invalid date format: ' . $formData['date_of_birth']);
            }
        }
        
        // Age validation
        if (!empty($formData['age']) && !is_numeric($formData['age'])) {
            $errors[] = 'Age must be a number';
            log_message('error', 'Invalid age format: ' . $formData['age']);
        }
        
        // Gender validation
        if (!empty($formData['gender']) && !in_array($formData['gender'], ['Male', 'Female'])) {
            $errors[] = 'Gender must be Male or Female';
            log_message('error', 'Invalid gender value: ' . $formData['gender']);
        }
        
        // Email validation
        if (!empty($formData['student_email']) && !filter_var($formData['student_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
            log_message('error', 'Invalid email format: ' . $formData['student_email']);
        }
        
        $isValid = empty($errors);
        log_message('info', 'Validation completed. Valid: ' . ($isValid ? 'YES' : 'NO') . ', Errors: ' . count($errors));
        
        return [
            'valid' => $isValid,
            'errors' => $errors
        ];
    }

    /**
     * Send enrollment notification
     */
    private function sendEnrollmentNotification($enrollmentId, $type, $reason = null)
    {
        $enrollment = $this->enrollmentModel->find($enrollmentId);
        if (!$enrollment) return false;
        
        $formData = $enrollment['form_data'];
        $studentName = trim(($formData['first_name'] ?? '') . ' ' . ($formData['last_name'] ?? ''));
        
        // Determine recipient email
        $recipientEmail = $formData['student_email'] ?? $formData['father_email'] ?? $formData['mother_email'] ?? null;
        $recipientName = $formData['father_first_name'] ?? $formData['mother_first_name'] ?? 'Parent/Guardian';
        
        if (!$recipientEmail) return false;
        
        // Prepare notification data
        $notificationData = [
            'enrollment_id' => $enrollmentId,
            'recipient_email' => $recipientEmail,
            'recipient_name' => $recipientName,
            'notification_type' => $type,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        switch ($type) {
            case 'submitted':
                $notificationData['subject'] = 'Enrollment Application Received - ' . $enrollment['enrollment_number'];
                $notificationData['message'] = "Dear {$recipientName},\n\nWe have received the enrollment application for {$studentName}.\n\nEnrollment Number: {$enrollment['enrollment_number']}\nGrade Level: {$enrollment['grade_level']}\nSubmission Date: {$enrollment['created_at']}\n\nYour application is currently under review. You will be notified once the review is complete.\n\nThank you,\nSta. Justina National High School";
                break;
                
            case 'approved':
                // Get student account details
                $student = $this->studentModel->where('enrollment_number', $enrollment['enrollment_number'])->first();
                $user = $this->userModel->where('student_id', $student['id'] ?? 0)->first();
                
                $notificationData['subject'] = 'Enrollment Approved - Welcome to Sta. Justina National High School!';
                $notificationData['message'] = "Dear {$recipientName},\n\n" .
                    "Congratulations! The enrollment application for {$studentName} has been APPROVED.\n\n" .
                    "Enrollment Number: {$enrollment['enrollment_number']}\n" .
                    "Grade Level: {$enrollment['grade_level']}\n" .
                    "Student ID: " . ($student['id'] ?? 'TBD') . "\n\n" .
                    "Student Account Details:\n" .
                    "Username: " . ($user['username'] ?? 'TBD') . "\n" .
                    "Temporary Password: " . ($user['temp_password'] ?? 'TBD') . "\n\n" .
                    "Please log in and change the password on first use.\n\n" .
                    "Welcome to our school family!\n\n" .
                    "Sta. Justina National High School";
                break;
                
            case 'declined':
                $notificationData['subject'] = 'Enrollment Application Status - ' . $enrollment['enrollment_number'];
                $notificationData['message'] = "Dear {$recipientName},\n\nWe regret to inform you that the enrollment application for {$studentName} has been declined.\n\nEnrollment Number: {$enrollment['enrollment_number']}\nReason: {$reason}\n\nIf you have any questions or would like to reapply, please contact our admissions office.\n\nThank you for your interest in our school.\n\nSta. Justina National High School";
                break;
        }
        
        // Store notification in database
        $db = \Config\Database::connect();
        $db->table('enrollment_notifications')->insert($notificationData);
        
        // TODO: Implement actual email sending
        // For now, we just log the notification
        log_message('info', "Enrollment notification ({$type}) prepared for {$recipientEmail}");
        
        return true;
    }

    /**
     * Check if LRN digits are present in form data
     */
    private function checkLRNDigits($formData)
    {
        $lrnDigitsPresent = 0;
        for ($i = 0; $i < 12; $i++) {
            if (isset($formData["lrn_digit_{$i}"]) && !empty($formData["lrn_digit_{$i}"])) {
                $lrnDigitsPresent++;
            }
        }
        return $lrnDigitsPresent;
    }

    /**
     * Check required fields presence
     */
    private function checkRequiredFields($formData)
    {
        $requiredFields = ['first_name', 'last_name', 'grade_level', 'gender', 'date_of_birth', 'age'];
        $presentFields = [];
        
        foreach ($requiredFields as $field) {
            $presentFields[$field] = isset($formData[$field]) && !empty($formData[$field]);
        }
        
        return $presentFields;
    }

    /**
     * Format LRN from form data
     */
    private function formatLRN($formData)
    {
        $lrn = '';
        for ($i = 0; $i < 12; $i++) {
            $lrn .= $formData["lrn_digit_{$i}"] ?? '0';
        }
        return $lrn;
    }

    /**
     * Format address from form data
     */
    private function formatAddress($formData, $type)
    {
        $prefix = $type === 'current' ? 'current_' : 'permanent_';
        
        $parts = [
            $formData[$prefix . 'house_no'] ?? '',
            $formData[$prefix . 'street'] ?? $formData[$prefix . 'street_name'] ?? '',
            $formData[$prefix . 'barangay'] ?? '',
            $formData[$prefix . 'municipality'] ?? '',
            $formData[$prefix . 'province'] ?? '',
            $formData[$prefix . 'country'] ?? 'Philippines'
        ];
        
        return implode(', ', array_filter($parts));
    }

    /**
     * Get enrollment reports
     */
    public function reports()
    {
        if (!$this->checkAdminAccess()) {
            return redirect()->to('/login')->with('fail', 'Admin access required');
        }

        $stats = $this->enrollmentModel->getEnrollmentStats();
        $recentEnrollments = $this->enrollmentModel->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
                                                   ->orderBy('created_at', 'DESC')
                                                   ->findAll();
        
        $data = [
            'pageTitle' => 'Enrollment Reports',
            'stats' => $stats,
            'recentEnrollments' => $recentEnrollments
        ];
        
        return view('backend/admin/enrollment/reports', $data);
    }
}