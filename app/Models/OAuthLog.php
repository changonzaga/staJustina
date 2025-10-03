<?php

namespace App\Models;

use CodeIgniter\Model;

class OAuthLog extends Model
{
    protected $table = 'oauth_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'email',
        'name',
        'picture',
        'status',
        'error_message',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    protected $useTimestamps = false;

    // Validation rules
    protected $validationRules = [
        'email' => 'required|valid_email',
        'name' => 'required',
        'status' => 'required|in_list[success,failed]',
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email address'
        ],
        'name' => [
            'required' => 'Name is required'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be either success or failed'
        ]
    ];

    /**
     * Log OAuth authentication attempt
     *
     * @param array $data Authentication data
     * @return bool
     */
    public function logAuthAttempt(array $data): bool
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['ip_address'] = service('request')->getIPAddress();
        $data['user_agent'] = service('request')->getUserAgent()->getAgentString();
        
        return $this->insert($data) !== false;
    }

    /**
     * Get recent OAuth login attempts
     *
     * @param int $limit Number of records to return
     * @return array
     */
    public function getRecentAttempts(int $limit = 10): array
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }

    /**
     * Get failed OAuth login attempts
     *
     * @param int $limit Number of records to return
     * @return array
     */
    public function getFailedAttempts(int $limit = 10): array
    {
        return $this->where('status', 'failed')
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }

    /**
     * Get OAuth login attempts by email
     *
     * @param string $email User's email
     * @param int $limit Number of records to return
     * @return array
     */
    public function getAttemptsByEmail(string $email, int $limit = 10): array
    {
        return $this->where('email', $email)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->find();
    }
}