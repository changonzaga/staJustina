<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentAuth extends Model
{
    protected $table = 'student_auth';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id',
        'account_number', 
        'password_hash',
        'email',
        'email_verified_at',
        'is_active',
        'last_login_at',
        'password_changed_at',
        'failed_login_attempts',
        'locked_until'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    /**
     * Find student by account number or email
     */
    public function findByLoginId($loginId)
    {
        return $this->where('account_number', $loginId)
                   ->orWhere('email', $loginId)
                   ->where('is_active', 1)
                   ->first();
    }

    /**
     * Find student by account number
     */
    public function findByAccountNumber($accountNumber)
    {
        return $this->where('account_number', $accountNumber)
                   ->where('is_active', 1)
                   ->first();
    }

    /**
     * Find student by email
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)
                   ->where('is_active', 1)
                   ->first();
    }

    /**
     * Verify password
     */
    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Update last login time
     */
    public function updateLastLogin($id)
    {
        return $this->update($id, [
            'last_login_at' => date('Y-m-d H:i:s'),
            'failed_login_attempts' => 0
        ]);
    }

    /**
     * Increment failed login attempts
     */
    public function incrementFailedAttempts($id)
    {
        $student = $this->find($id);
        if ($student) {
            $attempts = $student['failed_login_attempts'] + 1;
            $updateData = ['failed_login_attempts' => $attempts];
            
            // Lock account after 5 failed attempts for 30 minutes
            if ($attempts >= 5) {
                $updateData['locked_until'] = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            }
            
            return $this->update($id, $updateData);
        }
        return false;
    }

    /**
     * Check if account is locked
     */
    public function isAccountLocked($studentAuth)
    {
        if (!$studentAuth['locked_until']) {
            return false;
        }
        
        return strtotime($studentAuth['locked_until']) > time();
    }

    /**
     * Get student with personal info for authentication
     */
    public function getStudentWithInfo($studentAuthId)
    {
        return $this->select('student_auth.*, students.id as student_id, student_personal_info.first_name, student_personal_info.last_name')
                   ->join('students', 'students.id = student_auth.student_id', 'left')
                   ->join('student_personal_info', 'student_personal_info.student_id = students.id', 'left')
                   ->where('student_auth.id', $studentAuthId)
                   ->first();
    }
}