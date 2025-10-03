<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'name', 
        'account_no', 
        'email', 
        'password', 
        'picture', 
        'auth_type',
        'role',
        'permissions',
        'last_login_at',
        'status'
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $dateFormat       = 'datetime';

    // Get user role
    public function getRole()
    {
        return $this->attributes['role'] ?? null;
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->attributes['role'] === 'admin';
    }
    
    // Check if user is teacher
    public function isTeacher()
    {
        return $this->attributes['role'] === 'teacher';
    }
    
    // Check if user is student
    public function isStudent()
    {
        return $this->attributes['role'] === 'student';
    }
    
    // Check if user is parent
    public function isParent()
    {
        return $this->attributes['role'] === 'parent';
    }

    // Get user permissions
    public function getPermissions()
    {
        return json_decode($this->attributes['permissions'] ?? '[]', true);
    }

    // Check if user has specific permission
    public function hasPermission($permission)
    {
        $permissions = $this->getPermissions();
        return in_array($permission, $permissions);
    }

    // Update last login time
    public function updateLastLogin()
    {
        $this->attributes['last_login_at'] = date('Y-m-d H:i:s');
        return $this->save($this->attributes);
    }

    // Get user status
    public function getStatus()
    {
        return $this->attributes['status'] ?? 'active';
    }

    // Check if user is active
    public function isActive()
    {
        return $this->getStatus() === 'active';
    }
    
    // Get user's authentication type
    public function getAuthType()
    {
        return $this->attributes['auth_type'] ?? 'email';
    }
    
    // Check if user uses Google authentication
    public function isGoogleAuth()
    {
        return $this->getAuthType() === 'google';
    }
    
    // Check if user uses email authentication
    public function isEmailAuth()
    {
        return $this->getAuthType() === 'email';
    }
    
    // Get teacher record if user is a teacher
    public function getTeacherRecord()
    {
        if (!$this->isTeacher()) {
            return null;
        }
        
        $teacherModel = new \App\Models\TeacherModel();
        return $teacherModel->getByEmail($this->attributes['email']);
    }
    
    // Check if teacher profile is complete
    public function isTeacherProfileComplete()
    {
        if (!$this->isTeacher()) {
            return true; // Not applicable for non-teachers
        }
        
        $teacherModel = new \App\Models\TeacherModel();
        $teacher = $teacherModel->getByEmail($this->attributes['email']);
        
        if (!$teacher) {
            return false;
        }
        
        return $teacherModel->isProfileComplete($teacher['id']);
    }
    
    // Get user's full name from teacher record if available
    public function getFullName()
    {
        if ($this->isTeacher()) {
            $teacherModel = new \App\Models\TeacherModel();
            $teacher = $teacherModel->getByEmail($this->attributes['email']);
            if ($teacher) {
                return $teacherModel->getFullName($teacher);
            }
        }
        
        return $this->attributes['name'] ?? '';
    }
    
    // Get user's profile picture with fallback
    public function getProfilePicture()
    {
        // First check user table
        if (!empty($this->attributes['picture'])) {
            return $this->attributes['picture'];
        }
        
        // For teachers, check teacher table
        if ($this->isTeacher()) {
            $teacherModel = new \App\Models\TeacherModel();
            $teacher = $teacherModel->getByEmail($this->attributes['email']);
            if ($teacher && !empty($teacher['profile_picture'])) {
                return $teacher['profile_picture'];
            }
        }
        
        return null;
    }
    
    // Create user for teacher (with authentication data)
    public static function createForTeacher($teacherData, $authData)
    {
        $userModel = new self();
        
        $userData = [
            'name' => trim(($teacherData['first_name'] ?? '') . ' ' . ($teacherData['middle_name'] ?? '') . ' ' . ($teacherData['last_name'] ?? '')),
            'account_no' => explode('@', $authData['email'])[0],
            'email' => $authData['email'],
            'role' => 'teacher',
            'auth_type' => $authData['auth_type'] ?? 'email',
            'status' => 'active'
        ];
        
        if (isset($authData['password']) && !empty($authData['password'])) {
            $userData['password'] = password_hash($authData['password'], PASSWORD_DEFAULT);
        }
        
        if (isset($teacherData['profile_picture'])) {
            $userData['picture'] = $teacherData['profile_picture'];
        }
        
        return $userModel->insert($userData);
    }
    
    // Get teacher authentication record
    public function getTeacherAuth()
    {
        if (!$this->isTeacher()) {
            return null;
        }
        
        $teacherAuthModel = new \App\Models\TeacherAuth();
        return $teacherAuthModel->findByEmail($this->attributes['email']);
    }
}
