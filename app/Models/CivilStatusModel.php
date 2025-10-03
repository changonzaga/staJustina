<?php

namespace App\Models;

use CodeIgniter\Model;

class CivilStatusModel extends Model
{
    protected $table = 'civil_status';
    protected $primaryKey = 'id';
    protected $allowedFields = ['status'];
    
    protected $useTimestamps = false;
    
    // Validation rules
    protected $validationRules = [
        'status' => 'required|max_length[20]|is_unique[civil_status.status,id,{id}]'
    ];
    
    protected $validationMessages = [
        'status' => [
            'required' => 'Civil status is required',
            'max_length' => 'Civil status cannot exceed 20 characters',
            'is_unique' => 'This civil status already exists'
        ]
    ];
    
    /**
     * Get all civil status options for dropdowns
     */
    public function getOptions()
    {
        return $this->select('id, status')
                   ->orderBy('status', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get civil status by name
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)->first();
    }
}