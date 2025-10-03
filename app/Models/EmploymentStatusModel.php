<?php

namespace App\Models;

use CodeIgniter\Model;

class EmploymentStatusModel extends Model
{
    protected $table = 'employment_status';
    protected $primaryKey = 'id';
    protected $allowedFields = ['status'];
    
    protected $useTimestamps = false;
    
    // Validation rules
    protected $validationRules = [
        'status' => 'required|max_length[50]|is_unique[employment_status.status,id,{id}]'
    ];
    
    protected $validationMessages = [
        'status' => [
            'required' => 'Employment status is required',
            'max_length' => 'Employment status cannot exceed 50 characters',
            'is_unique' => 'This employment status already exists'
        ]
    ];
    
    /**
     * Get all employment status options for dropdowns
     */
    public function getOptions()
    {
        return $this->select('id, status')
                   ->orderBy('status', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get employment status by name
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)->first();
    }
}