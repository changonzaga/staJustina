<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailLogModel extends Model
{
    protected $table            = 'email_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'enrollment_id',
        'email_address',
        'email_type',
        'status',
        'error_message',
        'sent_at',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'email_address' => 'required|valid_email|max_length[255]',
        'email_type'    => 'required|max_length[50]',
        'status'        => 'required|in_list[success,failed,pending]',
        'sent_at'       => 'required|valid_date'
    ];
    protected $validationMessages   = [
        'email_address' => [
            'required'    => 'Email address is required',
            'valid_email' => 'Please provide a valid email address',
            'max_length'  => 'Email address cannot exceed 255 characters'
        ],
        'email_type' => [
            'required'   => 'Email type is required',
            'max_length' => 'Email type cannot exceed 50 characters'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list'  => 'Status must be one of: success, failed, pending'
        ],
        'sent_at' => [
            'required'   => 'Sent date is required',
            'valid_date' => 'Please provide a valid date'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get email logs with enrollment information
     */
    public function getEmailLogsWithEnrollment($filters = [])
    {
        $builder = $this->db->table($this->table . ' el')
            ->select('el.*, e.enrollment_number, epi.first_name, epi.last_name')
            ->join('enrollments e', 'e.id = el.enrollment_id', 'left')
            ->join('enrollment_personal_info epi', 'epi.enrollment_id = e.id', 'left');

        if (!empty($filters['enrollment_id'])) {
            $builder->where('el.enrollment_id', $filters['enrollment_id']);
        }

        if (!empty($filters['email_type'])) {
            $builder->where('el.email_type', $filters['email_type']);
        }

        if (!empty($filters['status'])) {
            $builder->where('el.status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('el.sent_at >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('el.sent_at <=', $filters['date_to']);
        }

        return $builder->orderBy('el.sent_at', 'DESC')->get()->getResultArray();
    }

    /**
     * Get email statistics
     */
    public function getEmailStats($dateRange = 30)
    {
        $dateFrom = date('Y-m-d H:i:s', strtotime("-{$dateRange} days"));
        
        $stats = [
            'total_sent' => $this->where('sent_at >=', $dateFrom)->countAllResults(false),
            'successful' => $this->where('sent_at >=', $dateFrom)->where('status', 'success')->countAllResults(false),
            'failed' => $this->where('sent_at >=', $dateFrom)->where('status', 'failed')->countAllResults(false),
            'pending' => $this->where('sent_at >=', $dateFrom)->where('status', 'pending')->countAllResults(false)
        ];
        
        $stats['success_rate'] = $stats['total_sent'] > 0 ? 
            round(($stats['successful'] / $stats['total_sent']) * 100, 2) : 0;
        
        return $stats;
    }

    /**
     * Get failed emails that can be retried
     */
    public function getRetryableFailedEmails($hoursOld = 1, $limit = 50)
    {
        $dateThreshold = date('Y-m-d H:i:s', strtotime("-{$hoursOld} hours"));
        
        return $this->where('status', 'failed')
            ->where('updated_at <=', $dateThreshold)
            ->limit($limit)
            ->findAll();
    }

    /**
     * Mark email as retried
     */
    public function markAsRetried($id, $newStatus = 'pending')
    {
        return $this->update($id, [
            'status' => $newStatus,
            'error_message' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Clean old email logs (for maintenance)
     */
    public function cleanOldLogs($daysOld = 90)
    {
        $dateThreshold = date('Y-m-d H:i:s', strtotime("-{$daysOld} days"));
        
        return $this->where('created_at <', $dateThreshold)
            ->where('status', 'success')
            ->delete();
    }

    /**
     * Get email logs for a specific enrollment
     */
    public function getLogsByEnrollment($enrollmentId)
    {
        return $this->where('enrollment_id', $enrollmentId)
            ->orderBy('sent_at', 'DESC')
            ->findAll();
    }

    /**
     * Check if email was already sent for enrollment
     */
    public function wasEmailSent($enrollmentId, $emailType = 'enrollment_approval')
    {
        return $this->where('enrollment_id', $enrollmentId)
            ->where('email_type', $emailType)
            ->where('status', 'success')
            ->countAllResults() > 0;
    }
}