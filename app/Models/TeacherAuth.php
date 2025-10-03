<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherAuth extends Model
{
    protected $table = 'teacher_auth';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'teacher_id',
        'account_no',
        'email',
        'password',
        'auth_type',
        'last_login_at',
        'is_active'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    // Validation rules
    protected $validationRules = [
        'teacher_id' => 'required|integer|is_unique[teacher_auth.teacher_id,id,{id}]',
        'account_no' => 'required|max_length[20]|is_unique[teacher_auth.account_no,id,{id}]',
        'email' => 'required|valid_email|is_unique[teacher_auth.email,id,{id}]',
        'password' => 'permit_empty|min_length[6]',
        'auth_type' => 'required|in_list[email,google]',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'teacher_id' => [
            'required' => 'Teacher ID is required',
            'integer' => 'Teacher ID must be a valid integer',
            'is_unique' => 'This teacher already has authentication credentials'
        ],
        'account_no' => [
            'required' => 'Account number is required',
            'max_length' => 'Account number cannot exceed 20 characters',
            'is_unique' => 'This account number is already registered'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email address',
            'is_unique' => 'This email is already registered'
        ],
        'password' => [
            'min_length' => 'Password must be at least 6 characters long'
        ],
        'auth_type' => [
            'required' => 'Authentication type is required',
            'in_list' => 'Authentication type must be either email or google'
        ]
    ];
    
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    /**
     * Hash password before saving
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            // Only hash if it's not already hashed
            if (!password_get_info($data['data']['password'])['algo']) {
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            }
        }
        
        return $data;
    }
    
    /**
     * Find teacher authentication by email
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)
                   ->where('is_active', 1)
                   ->first();
    }
    
    /**
     * Find teacher authentication by account number
     */
    public function findByAccountNo($accountNo)
    {
        return $this->where('account_no', $accountNo)
                   ->where('is_active', 1)
                   ->first();
    }
    
    /**
     * Find teacher authentication by teacher ID
     */
    public function findByTeacherId($teacherId)
    {
        return $this->where('teacher_id', $teacherId)
                   ->where('is_active', 1)
                   ->first();
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword);
    }
    
    /**
     * Update last login time
     */
    public function updateLastLogin($id)
    {
        return $this->update($id, [
            'last_login_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Create authentication record for teacher
     */
    public function createForTeacher($teacherId, $accountNo, $email, $password = null, $authType = 'email')
    {
        $data = [
            'teacher_id' => $teacherId,
            'account_no' => $accountNo,
            'email' => $email,
            'auth_type' => $authType,
            'is_active' => 1
        ];
        
        if ($password !== null) {
            $data['password'] = $password;
        }
        
        return $this->insert($data);
    }
    
    /**
     * Get teacher authentication with teacher details
     */
    public function getWithTeacherDetails($id = null)
    {
        $builder = $this->select('teacher_auth.*, teachers.first_name, teachers.middle_name, teachers.last_name, teachers.position')
                       ->join('teachers', 'teachers.id = teacher_auth.teacher_id', 'left')
                       ->where('teacher_auth.is_active', 1);
        
        if ($id !== null) {
            return $builder->find($id);
        }
        
        return $builder->findAll();
    }
    
    /**
     * Activate/Deactivate teacher authentication
     */
    public function setActiveStatus($id, $isActive)
    {
        return $this->update($id, [
            'is_active' => $isActive ? 1 : 0
        ]);
    }
    
    /**
     * Change password
     */
    public function changePassword($id, $newPassword)
    {
        return $this->update($id, [
            'password' => $newPassword, // Will be hashed by beforeUpdate callback
            'auth_type' => 'email' // Ensure auth type is email when password is set
        ]);
    }
    
    /**
     * Update authentication type (for Google OAuth)
     */
    public function updateAuthType($id, $authType)
    {
        $updateData = ['auth_type' => $authType];
        
        // If switching to Google auth, clear password
        if ($authType === 'google') {
            $updateData['password'] = null;
        }
        
        return $this->update($id, $updateData);
    }
    
    /**
     * Get authentication statistics
     */
    public function getAuthStats()
    {
        $total = $this->where('is_active', 1)->countAllResults(false);
        $emailAuth = $this->where('auth_type', 'email')->where('is_active', 1)->countAllResults(false);
        $googleAuth = $this->where('auth_type', 'google')->where('is_active', 1)->countAllResults();
        
        return [
            'total' => $total,
            'email_auth' => $emailAuth,
            'google_auth' => $googleAuth,
            'inactive' => $this->where('is_active', 0)->countAllResults()
        ];
    }
}