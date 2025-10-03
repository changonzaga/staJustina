<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $table = 'teachers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'first_name',
        'middle_name',
        'last_name',
        'employee_id',
        'date_of_birth',
        'gender',
        'contact_number',
        'age',
        'civil_status_id',
        'nationality',
        'prc_license_number',
        'educational_attainment',
        'employment_status_id',
        'position',
        'eligibility_status',
        'profile_picture',
        'status'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    // Validation rules
    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]',
        'employee_id' => 'required|max_length[50]|is_unique[teachers.employee_id,id,{id}]',
        'contact_number' => 'permit_empty|min_length[10]|max_length[20]',
        'date_of_birth' => 'permit_empty|valid_date',
        'age' => 'permit_empty|integer|greater_than[17]|less_than[101]',
        'gender' => 'permit_empty|in_list[Male,Female,Other]',
        'civil_status_id' => 'permit_empty|integer',
        'employment_status_id' => 'permit_empty|integer'
    ];
    
    protected $validationMessages = [
        'first_name' => [
            'required' => 'First name is required',
            'min_length' => 'First name must be at least 2 characters long',
            'max_length' => 'First name cannot exceed 100 characters'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'min_length' => 'Last name must be at least 2 characters long',
            'max_length' => 'Last name cannot exceed 100 characters'
        ],

    ];
    
    // Callbacks - account_no generation moved to TeacherAuth model
    protected $beforeInsert = [];
    protected $beforeUpdate = [];
    
    /**
     * Generate auto account number in format TCHYYYY0001 (no hyphens)
     * Now generates for teacher_auth table instead of teachers table
     */
    public function generateAccountNo()
    {
        $year = date('Y');
        // New prefix without hyphens: e.g., TCH2025
        $prefix = 'TCH' . $year;
        
        // Get the last account number for current year from teacher_auth table
        $teacherAuthModel = new \App\Models\TeacherAuth();
        $lastAuth = $teacherAuthModel->like('account_no', $prefix, 'after')
                                   ->orderBy('account_no', 'DESC')
                                   ->first();
        
        if ($lastAuth) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastAuth['account_no'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Final format: TCHYYYYNNNN (e.g., TCH20250001)
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    

    
    /**
     * Generate a secure random password (moved to TeacherAuth model)
     */
    public function generateSecurePassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        $charactersLength = strlen($characters);
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $charactersLength - 1)];
        }
        
        return $password;
    }
    

    
    /**
     * Get teacher with authentication information
     */
    public function getTeacherWithAuth($id)
    {
        return $this->select('teachers.*, teacher_auth.account_no, teacher_auth.email, teacher_auth.auth_type, teacher_auth.last_login_at, teacher_auth.is_active')
                   ->join('teacher_auth', 'teacher_auth.teacher_id = teachers.id', 'left')
                   ->find($id);
    }
    
    /**
     * Get teachers with authentication information
     */
    public function getTeachersWithAuth()
    {
        return $this->select('teachers.*, teacher_auth.account_no, teacher_auth.email, teacher_auth.auth_type, teacher_auth.last_login_at, teacher_auth.is_active')
                   ->join('teacher_auth', 'teacher_auth.teacher_id = teachers.id', 'left')
                   ->findAll();
    }
    
    /**
     * Get teacher by email (through auth table)
     */
    public function getByEmail($email)
    {
        return $this->select('teachers.*, teacher_auth.email, teacher_auth.auth_type, teacher_auth.last_login_at, teacher_auth.is_active')
                   ->join('teacher_auth', 'teacher_auth.teacher_id = teachers.id', 'inner')
                   ->where('teacher_auth.email', $email)
                   ->where('teacher_auth.is_active', 1)
                   ->first();
    }
    
    /**
     * Create teacher from Google OAuth data
     */
    public function createFromGoogleAuth($googleData)
    {
        // Parse name from Google data
        $nameParts = explode(' ', $googleData['name']);
        $firstName = $nameParts[0] ?? '';
        $lastName = end($nameParts) ?? '';
        $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : null;
        
        $teacherData = [
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'profile_picture' => $googleData['picture'] ?? null,
            'nationality' => 'Filipino' // Default value
        ];
        
        $teacherId = $this->insert($teacherData);
        
        if ($teacherId) {
            // Generate account number
            $accountNo = $this->generateAccountNo();
            
            // Create authentication record
            $teacherAuthModel = new \App\Models\TeacherAuth();
            $teacherAuthModel->createForTeacher($teacherId, $accountNo, $googleData['email'], null, 'google');
        }
        
        return $teacherId;
    }
    
    /**
     * Create teacher with email/password authentication
     */
    public function createWithAuth($teacherData, $email, $password = null, $authType = 'email')
    {
        $teacherId = $this->insert($teacherData);
        
        if ($teacherId) {
            // Generate account number
            $accountNo = $this->generateAccountNo();
            
            // Create authentication record
            $teacherAuthModel = new \App\Models\TeacherAuth();
            $teacherAuthModel->createForTeacher($teacherId, $accountNo, $email, $password, $authType);
            
            // Sync with users table
            $this->syncWithUsers($teacherId, $email, $password, $authType);
        }
        
        return $teacherId;
    }
    
    /**
     * Sync teacher with users table using TeacherAuth data
     */
    public function syncWithUsers($teacherId)
    {
        $teacher = $this->find($teacherId);
        if (!$teacher) return false;
        
        // Get teacher auth data
        $teacherAuthModel = new \App\Models\TeacherAuth();
        $teacherAuth = $teacherAuthModel->findByTeacherId($teacherId);
        if (!$teacherAuth) return false;
        
        $userModel = new \App\Models\User();
        
        // Prepare user data
        $userData = [
            'email' => $teacherAuth['email'],
            'role' => 'teacher',
            'auth_type' => $teacherAuth['auth_type'],
            'name' => $this->getFullName($teacher),
            'account_no' => $teacherAuth['account_no'],
            'password' => $teacherAuth['password'],
            'status' => 'active'
        ];
        
        if (!empty($teacher['profile_picture'])) {
            $userData['picture'] = $teacher['profile_picture'];
        }
        
        // Check if user exists
        $existingUser = $userModel->where('email', $teacherAuth['email'])->first();
        
        if ($existingUser) {
            return $userModel->update($existingUser['id'], $userData);
        } else {
            return $userModel->insert($userData);
        }
    }
    
    /**
     * Create teacher with authentication and automatic user sync
     */
    public function createTeacherWithAuth($profileData, $email, $password = null)
    {
        // Insert teacher profile
        $teacherId = $this->insert($profileData);
        
        if ($teacherId) {
            // Generate account number for authentication
            $accountNo = $this->generateAccountNo();
            
            // Create authentication record
            $teacherAuthModel = new \App\Models\TeacherAuth();
            
            // Generate password if not provided
            if ($password === null) {
                $password = $this->generateSecurePassword();
                // Store plain password temporarily for admin to see
                session()->setTempdata('generated_password', $password, 300); // 5 minutes
            }
            
            $authId = $teacherAuthModel->createForTeacher($teacherId, $accountNo, $email, $password, 'email');
            
            if ($authId) {
                // Sync with users table
                $this->syncWithUsers($teacherId);
            }
        }
        
        return $teacherId;
    }
    
    /**
     * Check if teacher profile is complete
     */
    public function isProfileComplete($teacherId)
    {
        $teacher = $this->find($teacherId);
        if (!$teacher) return false;
        
        // Required fields for complete profile
        $requiredFields = [
            'first_name', 'last_name', 'date_of_birth', 'gender',
            'contact_number', 'employee_id', 'position'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($teacher[$field])) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get full name of teacher
     */
    public function getFullName($teacher)
    {
        if (is_numeric($teacher)) {
            $teacher = $this->find($teacher);
        }
        
        if (!$teacher) return '';
        
        return trim($teacher['first_name'] . ' ' . ($teacher['middle_name'] ?? '') . ' ' . $teacher['last_name']);
    }
}