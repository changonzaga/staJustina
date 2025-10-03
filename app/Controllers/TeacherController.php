<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TeacherModel;

class TeacherController extends BaseController
{
    protected $teacherModel;
    
    public function __construct()
    {
        $this->teacherModel = new TeacherModel();
    }
    
    /**
     * Show teachers list (Admin only)
     */
    public function index()
    {
        // Check if user is admin
        $userInfo = session()->get('userdata');
        if (!$userInfo || $userInfo['role'] !== 'admin') {
            return redirect()->to('/login')->with('fail', 'Admin access required');
        }
        
        $data = [
            'pageTitle' => 'Teachers Management',
            'teachers' => $this->teacherModel->getTeachersWithAuth()
        ];
        
        return view('backend/admin/teachers/teacher', $data);
    }
    
    public function dashboard()
    {
        // Check if user is logged in and is a teacher
        $userInfo = session()->get('userdata');
        if (!$userInfo || $userInfo['role'] !== 'teacher') {
            return redirect()->to('/login')->with('fail', 'Please login as a teacher');
        }
        
        // Get teacher record and check profile completion
        $teacher = $this->teacherModel->getByEmail($userInfo['email']);
        $isProfileComplete = false;
        
        if ($teacher) {
            $isProfileComplete = $this->teacherModel->isProfileComplete($teacher['id']);
        }
        
        // Load the teacher dashboard view
        $data = [
            'pageTitle' => 'Teacher Dashboard',
            'teacher' => $teacher,
            'isProfileComplete' => $isProfileComplete,
            'userInfo' => $userInfo
        ];
        return view('backend/teacher/dashboard/home', $data);
    }
    
    /**
     * Show profile completion form
     */
    public function profileComplete()
    {
        // Check if user is logged in and is a teacher
        $userInfo = session()->get('userdata');
        if (!$userInfo || $userInfo['role'] !== 'teacher') {
            return redirect()->to('/login')->with('fail', 'Please login as a teacher');
        }
        
        // Get teacher record
        $teacher = $this->teacherModel->getByEmail($userInfo['email']);
        if (!$teacher) {
            return redirect()->to('/login')->with('fail', 'Teacher record not found');
        }
        
        $data = [
            'pageTitle' => 'Complete Profile',
            'teacher' => $teacher
        ];
        
        return view('teacher/profile/complete', $data);
    }
    
    /**
     * Handle profile completion form submission
     */
    public function profileCompleteHandler()
    {
        // Check if user is logged in and is a teacher
        $userInfo = session()->get('userdata');
        if (!$userInfo || $userInfo['role'] !== 'teacher') {
            return redirect()->to('/login')->with('fail', 'Please login as a teacher');
        }
        
        // Validation rules for profile completion
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'date_of_birth' => 'required|valid_date',
            'gender' => 'required|in_list[Male,Female,Other]',
            'contact_number' => 'required|min_length[10]|max_length[20]',
            'position' => 'required|max_length[100]',
            'specialization' => 'required|max_length[255]',
            'civil_status' => 'permit_empty|in_list[Single,Married,Divorced,Widowed]',
            'employment_status' => 'permit_empty|in_list[Regular,Contractual,Substitute,Part-time]',
            'profile_picture' => 'permit_empty|uploaded[profile_picture]|max_size[profile_picture,2048]|is_image[profile_picture]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Get teacher record
        $teacher = $this->teacherModel->getByEmail($userInfo['email']);
        if (!$teacher) {
            return redirect()->to('/login')->with('fail', 'Teacher record not found');
        }
        
        // Prepare update data
        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'contact_number' => $this->request->getPost('contact_number'),
            'employee_id' => $this->request->getPost('employee_id'),
            'position' => $this->request->getPost('position'),
            'specialization' => $this->request->getPost('specialization'),
            'civil_status' => $this->request->getPost('civil_status'),
            'employment_status' => $this->request->getPost('employment_status'),
            'educational_attainment' => $this->request->getPost('educational_attainment'),
            'teaching_assignment' => $this->request->getPost('teaching_assignment'),
            'school_assigned' => $this->request->getPost('school_assigned'),
            'prc_license_number' => $this->request->getPost('prc_license_number'),
            'residential_address' => $this->request->getPost('residential_address'),
            'emergency_contact' => $this->request->getPost('emergency_contact')
        ];
        
        // Handle profile picture upload
        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = FCPATH . 'uploads/teachers/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Generate unique filename using teacher_id since account_no is now in teacher_auth table
            $fileName = 'teacher_' . $teacher['id'] . '_' . time() . '.' . $profilePicture->getExtension();
            
            // Move uploaded file
            if ($profilePicture->move($uploadPath, $fileName)) {
                // Delete old profile picture if exists
                if (!empty($teacher['profile_picture']) && file_exists($uploadPath . $teacher['profile_picture'])) {
                    unlink($uploadPath . $teacher['profile_picture']);
                }
                
                $updateData['profile_picture'] = $fileName;
            }
        }
        
        // Update teacher record
        if ($this->teacherModel->update($teacher['id'], $updateData)) {
            return redirect()->to('/teacher/dashboard')
                ->with('success', 'Profile completed successfully! You now have access to all features.');
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }
    
    /**
     * Show create teacher form (Admin only)
     */
    public function create()
    {
        // Check if user is admin
        $userInfo = session()->get('userdata');
        if (!$userInfo || $userInfo['role'] !== 'admin') {
            return redirect()->to('/login')->with('fail', 'Admin access required');
        }
        
        // Load reference data for dropdowns
        $civilStatusModel = new \App\Models\CivilStatusModel();
        $employmentStatusModel = new \App\Models\EmploymentStatusModel();
        $subjectModel = new \App\Models\SubjectModel();
        
        $data = [
            'pageTitle' => 'Add New Teacher',
            'validation' => null,
            'civil_statuses' => $civilStatusModel->getOptions(),
            'employment_statuses' => $employmentStatusModel->getOptions(),
            'subjects' => $subjectModel->getOptions()
        ];
        
        return view('backend/admin/teachers/create', $data);
    }
    
    /**
     * Store new teacher (Admin only)
     */
    public function store()
    {
        // Check if user is admin
        $userInfo = session()->get('userdata');
        if (!$userInfo || $userInfo['role'] !== 'admin') {
            return redirect()->to('/login')->with('fail', 'Admin access required');
        }
        
        // Validation rules
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[teacher_auth.email]',
            'employee_id' => 'required|max_length[50]|is_unique[teachers.employee_id]',
            'date_of_birth' => 'permit_empty|valid_date',
            'age' => 'permit_empty|integer|greater_than[9]|less_than[101]',
            'gender' => 'required|in_list[Male,Female,Other]',
            'contact_number' => 'permit_empty|min_length[10]|max_length[20]',
            'civil_status_id' => 'permit_empty|integer',
            'employment_status_id' => 'permit_empty|integer',
            'profile_picture' => 'permit_empty|uploaded[profile_picture]|max_size[profile_picture,2048]|is_image[profile_picture]',
            // Address validation rules
            'residential_street_address' => 'required|max_length[500]',
            'residential_barangay' => 'permit_empty|max_length[100]',
            'residential_city' => 'permit_empty|max_length[100]',
            'residential_province' => 'permit_empty|max_length[100]',
            'residential_postal_code' => 'permit_empty|max_length[10]',
            'permanent_street_address' => 'permit_empty|max_length[500]',
            'permanent_barangay' => 'permit_empty|max_length[100]',
            'permanent_city' => 'permit_empty|max_length[100]',
            'permanent_province' => 'permit_empty|max_length[100]',
            'permanent_postal_code' => 'permit_empty|max_length[10]',
            // Specialization validation rules
            'specializations.0.subject_id' => 'required|integer',
            'specializations.0.proficiency_level' => 'required|in_list[Basic,Intermediate,Advanced,Expert]',
            'specializations.*.subject_id' => 'permit_empty|integer',
            'specializations.*.proficiency_level' => 'permit_empty|in_list[Basic,Intermediate,Advanced,Expert]',
            'specializations.*.years_experience' => 'permit_empty|integer|greater_than_equal_to[0]|less_than[100]'
        ];
        
        if (!$this->validate($rules)) {
            // Load reference data for dropdowns when validation fails
            $civilStatusModel = new \App\Models\CivilStatusModel();
            $employmentStatusModel = new \App\Models\EmploymentStatusModel();
            $subjectModel = new \App\Models\SubjectModel();
            
            return view('backend/admin/teachers/create', [
                'pageTitle' => 'Add New Teacher',
                'validation' => $this->validator,
                'civil_statuses' => $civilStatusModel->getOptions(),
                'employment_statuses' => $employmentStatusModel->getOptions(),
                'subjects' => $subjectModel->getOptions()
            ]);
        }
        
        // Prepare teacher data for normalized schema
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'employee_id' => $this->request->getPost('employee_id'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'age' => $this->request->getPost('age') ?: null,
            'gender' => $this->request->getPost('gender'),
            'contact_number' => $this->request->getPost('contact_number'),
            'educational_attainment' => $this->request->getPost('educational_attainment'),
            'position' => $this->request->getPost('position'),
            'eligibility_status' => $this->request->getPost('eligibility_status'),
            'prc_license_number' => $this->request->getPost('prc_license_number'),
            'nationality' => $this->request->getPost('nationality') ?: 'Filipino',
            'civil_status_id' => $this->request->getPost('civil_status_id') ?: null,
            'employment_status_id' => $this->request->getPost('employment_status_id') ?: null,
            'status' => 'Active'  // Automatically set to Active for new teachers
        ];
        
        // Handle profile picture upload
        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = FCPATH . 'uploads/teachers/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Generate unique filename
            $fileName = 'teacher_' . time() . '.' . $profilePicture->getExtension();
            
            // Move uploaded file
            if ($profilePicture->move($uploadPath, $fileName)) {
                $data['profile_picture'] = $fileName;
            }
        }
        
        // Get email from form data
        $email = $this->request->getPost('email');
        
        // Create teacher with authentication
        $teacherId = $this->teacherModel->createTeacherWithAuth($data, $email);
        
        if ($teacherId) {
            // Save address information
            $this->saveTeacherAddresses($teacherId);
            
            // Save specializations
            $this->saveTeacherSpecializations($teacherId);
            
            // Get the generated password to show to admin
            $generatedPassword = session()->getTempdata('generated_password');
            
            // Get teacher auth data for account number
            $teacherAuthModel = new \App\Models\TeacherAuth();
            $teacherAuth = $teacherAuthModel->where('teacher_id', $teacherId)->first();
            
            $message = 'Teacher created successfully!';
            $emailStatus = '';
            
            if ($generatedPassword && $teacherAuth) {
                // Prepare teacher data with email for email service
                $teacherDataForEmail = $data;
                $teacherDataForEmail['email'] = $teacherAuth['email'];
                
                // Send welcome email with credentials
                $emailService = new \App\Libraries\EmailService();
                $emailResult = $emailService->sendTeacherWelcomeEmail(
                    $teacherDataForEmail, // Teacher data with email
                    [
                        'account_no' => $teacherAuth['account_no'],
                        'password' => $generatedPassword
                    ]
                );
                
                // Prepare success message
                $message = '🎉 Teacher Created Successfully! 🎉\n\n';
                $message .= '📋 LOGIN CREDENTIALS:\n';
                $message .= '👤 Account Number: ' . $teacherAuth['account_no'] . '\n';
                $message .= '🔑 Password: ' . $generatedPassword . '\n';
                $message .= '📧 Email: ' . $teacherAuth['email'] . '\n\n';
                
                if ($emailResult['success']) {
                    $emailStatus = '✅ Welcome email sent successfully to ' . $teacherAuth['email'];
                    $message .= '📧 ' . $emailStatus . '\n\n';
                    $message .= '⚠️ IMPORTANT: Login credentials have been sent to the teacher\'s email address. Please also save these credentials for your records!';
                } else {
                    $emailStatus = '❌ Failed to send welcome email: ' . $emailResult['message'];
                    $message .= '📧 ' . $emailStatus . '\n\n';
                    $message .= '⚠️ IMPORTANT: Please manually share these credentials with the teacher securely!';
                    log_message('warning', 'Teacher created but email failed: ' . $emailResult['message']);
                }
            } elseif ($generatedPassword) {
                $message .= ' Generated password: ' . $generatedPassword . ' (Please save this password and share it with the teacher)';
            }
            
            return redirect()->to('/admin/teacher')
                ->with('success', $message);
        } else {
            // Get detailed error information
            $errors = $this->teacherModel->errors();
            $errorMessage = 'Failed to create teacher. ';
            
            if (!empty($errors)) {
                $errorMessage .= 'Errors: ' . implode(', ', $errors);
            } else {
                $errorMessage .= 'Please check all required fields and try again.';
            }
            
            log_message('error', 'Teacher creation failed: ' . json_encode([
                'data' => $data,
                'email' => $email,
                'errors' => $errors
            ]));
            
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }
    
    /**
     * Show edit teacher form (Admin only)
     */
    public function edit($id)
    {
        // Check if user is admin
        $userInfo = session()->get('userdata');
        if (!$userInfo || $userInfo['role'] !== 'admin') {
            return redirect()->to('/login')->with('fail', 'Admin access required');
        }
        
        $teacher = $this->teacherModel->getTeacherWithAuth($id);
        
        if (empty($teacher)) {
            return redirect()->to('/admin/teacher')->with('error', 'Teacher not found');
        }
        
        // Load reference data for dropdowns
        $civilStatusModel = new \App\Models\CivilStatusModel();
        $employmentStatusModel = new \App\Models\EmploymentStatusModel();
        $subjectModel = new \App\Models\SubjectModel();
        
        // Load teacher's addresses
        $addressModel = new \App\Models\TeacherAddressModel();
        $addresses = $addressModel->where('teacher_id', $id)->findAll();
        
        // Organize addresses by type
        $teacherAddresses = [];
        foreach ($addresses as $address) {
            $teacherAddresses[$address['address_type']] = $address;
        }
        
        // Load teacher's specializations
        $specializationModel = new \App\Models\TeacherSpecializationModel();
        $specializations = $specializationModel->select('teacher_specializations.*, subjects.subject_name')
                                              ->join('subjects', 'subjects.id = teacher_specializations.subject_id', 'left')
                                              ->where('teacher_id', $id)
                                              ->orderBy('is_primary', 'DESC')
                                              ->findAll();
        
        $data = [
            'pageTitle' => 'Edit Teacher',
            'teacher' => $teacher,
            'teacher_addresses' => $teacherAddresses,
            'teacher_specializations' => $specializations,
            'validation' => null,
            'civil_statuses' => $civilStatusModel->getOptions(),
            'employment_statuses' => $employmentStatusModel->getOptions(),
            'subjects' => $subjectModel->getOptions()
        ];
        
        return view('backend/admin/teachers/edit', $data);
    }
    
    /**
     * Update teacher (Admin only)
     */
    public function update($id)
    {
        // Debug: Log method entry
        error_log('TEACHER UPDATE METHOD CALLED - ID: ' . $id);
        file_put_contents('debug_update.txt', 'Update method called at ' . date('Y-m-d H:i:s') . ' with ID: ' . $id . "\n", FILE_APPEND);
        log_message('debug', 'Teacher Update Method Called - ID: ' . $id);
        log_message('debug', 'Teacher Update - POST Data: ' . json_encode($this->request->getPost()));
        
        // Check if user is admin
        $userInfo = session()->get('userdata');
        if (!$userInfo || $userInfo['role'] !== 'admin') {
            return redirect()->to('/login')->with('fail', 'Admin access required');
        }
        
        $teacher = $this->teacherModel->getTeacherWithAuth($id);
        
        if (empty($teacher)) {
            return redirect()->to('/admin/teacher')->with('error', 'Teacher not found');
        }
        
        // Validation rules
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'date_of_birth' => 'permit_empty|valid_date',
            'age' => 'permit_empty|integer|greater_than[9]|less_than[101]',
            'gender' => 'permit_empty|in_list[Male,Female,Other]',
            'contact_number' => 'permit_empty|min_length[10]|max_length[20]',
            'employment_status_id' => 'permit_empty|numeric',
            'civil_status_id' => 'permit_empty|numeric',
            'profile_picture' => 'permit_empty|uploaded[profile_picture]|max_size[profile_picture,2048]|is_image[profile_picture]',
            // Address validation rules
            'residential_street_address' => 'permit_empty|max_length[500]',
            'residential_barangay' => 'permit_empty|max_length[100]',
            'residential_city' => 'permit_empty|max_length[100]',
            'residential_province' => 'permit_empty|max_length[100]',
            'residential_postal_code' => 'permit_empty|max_length[10]',
            'permanent_street_address' => 'permit_empty|max_length[500]',
            'permanent_barangay' => 'permit_empty|max_length[100]',
            'permanent_city' => 'permit_empty|max_length[100]',
            'permanent_province' => 'permit_empty|max_length[100]',
            'permanent_postal_code' => 'permit_empty|max_length[10]',
            // Specialization validation rules
            'specializations.*.subject_id' => 'permit_empty|integer',
            'specializations.*.proficiency_level' => 'permit_empty|in_list[Basic,Intermediate,Advanced,Expert]',
            'specializations.*.years_experience' => 'permit_empty|integer|greater_than_equal_to[0]|less_than[100]'
        ];
        
        // Get teacher auth data for email validation
        $teacherAuthModel = new \App\Models\TeacherAuth();
        $teacherAuth = $teacherAuthModel->findByTeacherId($id);
        
        // Only validate email uniqueness if it has changed
        if ($teacherAuth && $teacherAuth['email'] != $this->request->getPost('email')) {
            $rules['email'] = "required|valid_email|is_unique[teacher_auth.email,teacher_id,{$id}]";
        } else {
            $rules['email'] = 'required|valid_email';
        }
        
        // Employee ID is auto-generated, no validation needed
        
        // Temporarily bypass validation for debugging
        file_put_contents('debug_update.txt', 'Validation rules: ' . json_encode($rules) . "\n", FILE_APPEND);
        $validationResult = $this->validate($rules);
        file_put_contents('debug_update.txt', 'Validation result: ' . ($validationResult ? 'PASSED' : 'FAILED') . "\n", FILE_APPEND);
        
        if (!$validationResult) {
            file_put_contents('debug_update.txt', 'Validation errors: ' . json_encode($this->validator->getErrors()) . "\n", FILE_APPEND);
            
            // Load reference data for dropdowns
            $civilStatusModel = new \App\Models\CivilStatusModel();
            $employmentStatusModel = new \App\Models\EmploymentStatusModel();
            $subjectModel = new \App\Models\SubjectModel();
            
            // Load teacher's addresses for validation failure
            $addressModel = new \App\Models\TeacherAddressModel();
            $addresses = $addressModel->where('teacher_id', $id)->findAll();
            $teacherAddresses = [];
            foreach ($addresses as $address) {
                $teacherAddresses[$address['address_type']] = $address;
            }
            
            // Load teacher's specializations for validation failure
            $specializationModel = new \App\Models\TeacherSpecializationModel();
            $specializations = $specializationModel->select('teacher_specializations.*, subjects.subject_name')
                                                  ->join('subjects', 'subjects.id = teacher_specializations.subject_id', 'left')
                                                  ->where('teacher_id', $id)
                                                  ->orderBy('is_primary', 'DESC')
                                                  ->findAll();
            
            return view('backend/admin/teachers/edit', [
                'pageTitle' => 'Edit Teacher',
                'teacher' => $teacher,
                'teacher_addresses' => $teacherAddresses,
                'teacher_specializations' => $specializations,
                'validation' => $this->validator,
                'civil_statuses' => $civilStatusModel->getOptions(),
                'employment_statuses' => $employmentStatusModel->getOptions(),
                'subjects' => $subjectModel->getOptions()
            ]);
        }
        
        // Prepare profile update data (only fields that exist in teachers table)
        $profileData = [
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'contact_number' => $this->request->getPost('contact_number'),
            'educational_attainment' => $this->request->getPost('educational_attainment'),
            'employment_status_id' => $this->request->getPost('employment_status_id'),
            'position' => $this->request->getPost('position'),
            'eligibility_status' => $this->request->getPost('eligibility_status'),
            'civil_status_id' => $this->request->getPost('civil_status_id'),
            'prc_license_number' => $this->request->getPost('prc_license_number'),
            'nationality' => $this->request->getPost('nationality')
        ];
        
        // Handle address data separately (teacher_addresses table)
        file_put_contents('debug_update.txt', 'About to update addresses...' . "\n", FILE_APPEND);
        $addressData = [
            'residential' => [
                'street_address' => $this->request->getPost('residential_street_address'),
                'barangay' => $this->request->getPost('residential_barangay'),
                'city' => $this->request->getPost('residential_city'),
                'province' => $this->request->getPost('residential_province'),
                'postal_code' => $this->request->getPost('residential_postal_code')
            ],
            'permanent' => [
                'street_address' => $this->request->getPost('permanent_street_address'),
                'barangay' => $this->request->getPost('permanent_barangay'),
                'city' => $this->request->getPost('permanent_city'),
                'province' => $this->request->getPost('permanent_province'),
                'postal_code' => $this->request->getPost('permanent_postal_code')
            ]
        ];
        file_put_contents('debug_update.txt', 'Address data: ' . json_encode($addressData) . "\n", FILE_APPEND);
        $this->updateTeacherAddresses($id, $addressData);
        
        // Handle specializations
        file_put_contents('debug_update.txt', 'About to update specializations...' . "\n", FILE_APPEND);
        $specializationsData = $this->request->getPost('specializations') ?? [];
        file_put_contents('debug_update.txt', 'Specializations data: ' . json_encode($specializationsData) . "\n", FILE_APPEND);
        $this->updateTeacherSpecializations($id, $specializationsData);
        
        // Handle email update in teacher_auth table
        $newEmail = $this->request->getPost('email');
        if ($teacherAuth && $teacherAuth['email'] != $newEmail) {
            $teacherAuthModel->update($teacherAuth['id'], ['email' => $newEmail]);
        }
        
        // Handle password update if provided
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword) && $teacherAuth) {
            $teacherAuthModel->changePassword($teacherAuth['id'], $newPassword);
        }
        
        // Handle profile picture upload
        $profilePicture = $this->request->getFile('profile_picture');
        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = FCPATH . 'uploads/teachers/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Delete old profile picture if exists
            if (!empty($teacher['profile_picture']) && file_exists($uploadPath . $teacher['profile_picture'])) {
                unlink($uploadPath . $teacher['profile_picture']);
            }
            
            // Generate unique filename
            $fileName = $teacher['account_no'] . '_' . time() . '.' . $profilePicture->getExtension();
            
            // Move uploaded file
            if ($profilePicture->move($uploadPath, $fileName)) {
                $profileData['profile_picture'] = $fileName;
            }
        }
        
        // Debug: Log the data being updated
        file_put_contents('debug_update.txt', 'Profile data to update: ' . json_encode($profileData) . "\n", FILE_APPEND);
        log_message('debug', 'Teacher Update - Profile Data: ' . json_encode($profileData));
        log_message('debug', 'Teacher Update - Teacher ID: ' . $id);
        
        // Update teacher record
        file_put_contents('debug_update.txt', 'About to update teacher record...' . "\n", FILE_APPEND);
        $updateResult = $this->teacherModel->update($id, $profileData);
        file_put_contents('debug_update.txt', 'Teacher update result: ' . ($updateResult ? 'SUCCESS' : 'FAILED') . "\n", FILE_APPEND);
        
        // Debug: Log the update result
        log_message('debug', 'Teacher Update - Update Result: ' . ($updateResult ? 'SUCCESS' : 'FAILED'));
        
        if ($updateResult) {
            // Sync with users table if email changed
            $this->teacherModel->syncWithUsers($id);
            
            return redirect()->to('/admin/teacher')
                ->with('success', 'Teacher updated successfully!');
        } else {
            // Get the last database error for debugging
            $db = \Config\Database::connect();
            $error = $db->error();
            log_message('error', 'Teacher Update Failed - DB Error: ' . json_encode($error));
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update teacher. Database error: ' . ($error['message'] ?? 'Unknown error'));
        }
    }
    
    /**
     * Show teacher details (Admin only)
     */
    public function show($id)
    {
        // Check if user is admin
        $userInfo = session()->get('userdata');
        if (!$userInfo || $userInfo['role'] !== 'admin') {
            return redirect()->to('/login')->with('fail', 'Admin access required');
        }
        
        $teacher = $this->teacherModel->getTeacherWithAuth($id);
        
        if (empty($teacher)) {
            return redirect()->to('/admin/teacher')->with('error', 'Teacher not found');
        }
        
        $data = [
            'pageTitle' => 'Teacher Profile',
            'teacher' => $teacher
        ];
        
        return view('backend/admin/teachers/teacher_profile', $data);
    }
    
    /**
     * Delete teacher (Admin only)
     */
    public function delete($id)
    {
        // Check if user is admin
        $userInfo = session()->get('userdata');
        if (!$userInfo || $userInfo['role'] !== 'admin') {
            return redirect()->to('/login')->with('fail', 'Admin access required');
        }
        
        $teacher = $this->teacherModel->find($id);
        
        if (empty($teacher)) {
            return redirect()->to('/admin/teacher')->with('error', 'Teacher not found');
        }
        
        // Delete profile picture if exists
        $uploadPath = FCPATH . 'uploads/teachers/';
        if (!empty($teacher['profile_picture']) && file_exists($uploadPath . $teacher['profile_picture'])) {
            unlink($uploadPath . $teacher['profile_picture']);
        }
        
        // Get teacher auth data
        $teacherAuthModel = new \App\Models\TeacherAuth();
        $teacherAuth = $teacherAuthModel->findByTeacherId($id);
        
        // Delete from users table first
        if ($teacherAuth) {
            $userModel = new \App\Models\User();
            $userModel->where('email', $teacherAuth['email'])->delete();
            
            // Delete teacher auth record
            $teacherAuthModel->delete($teacherAuth['id']);
        }
        
        // Delete teacher record
        $this->teacherModel->delete($id);
        
        return redirect()->to('/admin/teacher')->with('success', 'Teacher deleted successfully');
    }
    
    /**
     * Save teacher addresses
     */
    private function saveTeacherAddresses($teacherId)
    {
        $addressModel = new \App\Models\TeacherAddressModel();
        
        // Save residential address
        $residentialAddress = [
            'teacher_id' => $teacherId,
            'address_type' => 'residential',
            'street_address' => $this->request->getPost('residential_street_address'),
            'barangay' => $this->request->getPost('residential_barangay'),
            'city' => $this->request->getPost('residential_city'),
            'province' => $this->request->getPost('residential_province'),
            'postal_code' => $this->request->getPost('residential_postal_code'),
            'country' => 'Philippines',
            'is_current' => 1
        ];
        
        if (!empty($residentialAddress['street_address'])) {
            $addressModel->insert($residentialAddress);
        }
        
        // Save permanent address (if different from residential)
        if (!$this->request->getPost('same_as_residential')) {
            $permanentAddress = [
                'teacher_id' => $teacherId,
                'address_type' => 'permanent',
                'street_address' => $this->request->getPost('permanent_street_address'),
                'barangay' => $this->request->getPost('permanent_barangay'),
                'city' => $this->request->getPost('permanent_city'),
                'province' => $this->request->getPost('permanent_province'),
                'postal_code' => $this->request->getPost('permanent_postal_code'),
                'country' => 'Philippines',
                'is_current' => 1
            ];
            
            if (!empty($permanentAddress['street_address'])) {
                $addressModel->insert($permanentAddress);
            }
        } else {
            // If same as residential, copy residential data to permanent
            $permanentAddress = $residentialAddress;
            $permanentAddress['address_type'] = 'permanent';
            
            if (!empty($permanentAddress['street_address'])) {
                $addressModel->insert($permanentAddress);
            }
        }
    }
    
    /**
     * Save teacher specializations
     */
    private function saveTeacherSpecializations($teacherId)
    {
        $specializationModel = new \App\Models\TeacherSpecializationModel();
        $specializations = $this->request->getPost('specializations');
        $primarySpecialization = $this->request->getPost('primary_specialization');
        
        if (!empty($specializations) && is_array($specializations)) {
            foreach ($specializations as $index => $specialization) {
                if (!empty($specialization['subject_id']) && !empty($specialization['proficiency_level'])) {
                    $specializationData = [
                        'teacher_id' => $teacherId,
                        'subject_id' => $specialization['subject_id'],
                        'proficiency_level' => $specialization['proficiency_level'],
                        'years_experience' => !empty($specialization['years_experience']) ? (int)$specialization['years_experience'] : 0,
                        'is_primary' => ($primarySpecialization == $index) ? 1 : 0
                    ];
                    
                    $specializationModel->insert($specializationData);
                }
            }
        }
    }
    
    /**
     * Update teacher addresses in separate table
     */
    private function updateTeacherAddresses($teacherId, $addressData)
    {
        $addressModel = new \App\Models\TeacherAddressModel();
        
        // Update residential address
        if (!empty($addressData['residential']['street_address'])) {
            $existingResidential = $addressModel->where('teacher_id', $teacherId)
                                                ->where('address_type', 'residential')
                                                ->first();
            
            $residentialData = [
                'teacher_id' => $teacherId,
                'address_type' => 'residential',
                'street_address' => $addressData['residential']['street_address'],
                'barangay' => $addressData['residential']['barangay'] ?? null,
                'city' => $addressData['residential']['city'] ?? null,
                'province' => $addressData['residential']['province'] ?? null,
                'postal_code' => $addressData['residential']['postal_code'] ?? null,
                'country' => 'Philippines',
                'is_current' => 1
            ];
            
            if ($existingResidential) {
                $addressModel->update($existingResidential['id'], $residentialData);
            } else {
                $addressModel->insert($residentialData);
            }
        }
        
        // Update permanent address
        if (!empty($addressData['permanent']['street_address'])) {
            $existingPermanent = $addressModel->where('teacher_id', $teacherId)
                                              ->where('address_type', 'permanent')
                                              ->first();
            
            $permanentData = [
                'teacher_id' => $teacherId,
                'address_type' => 'permanent',
                'street_address' => $addressData['permanent']['street_address'],
                'barangay' => $addressData['permanent']['barangay'] ?? null,
                'city' => $addressData['permanent']['city'] ?? null,
                'province' => $addressData['permanent']['province'] ?? null,
                'postal_code' => $addressData['permanent']['postal_code'] ?? null,
                'country' => 'Philippines',
                'is_current' => 1
            ];
            
            if ($existingPermanent) {
                $addressModel->update($existingPermanent['id'], $permanentData);
            } else {
                $addressModel->insert($permanentData);
            }
        }
    }
    
    /**
     * Update teacher specializations
     */
    private function updateTeacherSpecializations($teacherId, $specializations)
    {
        $specializationModel = new \App\Models\TeacherSpecializationModel();
        
        // Delete existing specializations
        $specializationModel->where('teacher_id', $teacherId)->delete();
        
        // Add new specializations
        if (!empty($specializations)) {
            foreach ($specializations as $index => $specialization) {
                if (!empty($specialization['subject_id']) && !empty($specialization['proficiency_level'])) {
                    $specializationData = [
                        'teacher_id' => $teacherId,
                        'subject_id' => $specialization['subject_id'],
                        'proficiency_level' => $specialization['proficiency_level'],
                        'years_experience' => !empty($specialization['years_experience']) ? (int)$specialization['years_experience'] : 0,
                        'is_primary' => ($index == 0) ? 1 : 0
                    ];
                    
                    $specializationModel->insert($specializationData);
                }
            }
        }
    }
}