<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentDisabilitiesModel extends Model
{
    protected $table = 'student_disabilities_temp';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'student_id',
        'has_disability',
        'disability_type'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'student_id' => 'required|integer',
        'has_disability' => 'required|in_list[Yes,No]',
        'disability_type' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'student_id' => [
            'required' => 'Student ID is required',
            'integer' => 'Student ID must be a valid integer'
        ],
        'has_disability' => [
            'required' => 'Disability status is required',
            'in_list' => 'Disability status must be either Yes or No'
        ],
        'disability_type' => [
            'max_length' => 'Disability type cannot exceed 255 characters'
        ]
    ];

    /**
     * Get disability information for a specific student
     */
    public function getByStudentId($studentId)
    {
        return $this->where('student_id', $studentId)->first();
    }

    /**
     * Get all students with disabilities
     */
    public function getStudentsWithDisabilities()
    {
        return $this->where('has_disability', 'Yes')->findAll();
    }

    /**
     * Get students by disability type
     */
    public function getByDisabilityType($disabilityType)
    {
        return $this->where('has_disability', 'Yes')
                   ->where('disability_type', $disabilityType)
                   ->findAll();
    }

    /**
     * Update or create disability information for a student
     */
    public function updateOrCreate($studentId, $data)
    {
        $existing = $this->getByStudentId($studentId);
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            $data['student_id'] = $studentId;
            return $this->insert($data);
        }
    }

    /**
     * Get disability statistics
     */
    public function getDisabilityStats()
    {
        $stats = [];
        
        // Count students with and without disabilities
        $stats['total_with_disabilities'] = $this->where('has_disability', 'Yes')->countAllResults();
        $stats['total_without_disabilities'] = $this->where('has_disability', 'No')->countAllResults();
        
        // Count by disability type
        $stats['by_disability_type'] = $this->select('disability_type, COUNT(*) as count')
                                           ->where('has_disability', 'Yes')
                                           ->where('disability_type IS NOT NULL')
                                           ->where('disability_type !=', '')
                                           ->groupBy('disability_type')
                                           ->findAll();
        
        return $stats;
    }

    /**
     * Get students with disabilities including their personal information
     */
    public function getStudentsWithDisabilitiesAndInfo()
    {
        $db = \Config\Database::connect();
        
        return $db->table($this->table . ' sd')
            ->select('sd.*, s.account_number, s.lrn, s.grade_level, s.section,
                     spi.first_name, spi.middle_name, spi.last_name, spi.gender, spi.date_of_birth,
                     CONCAT(spi.first_name, " ", COALESCE(spi.middle_name, ""), " ", spi.last_name) as full_name')
            ->join('students s', 's.id = sd.student_id', 'left')
            ->join('student_personal_info spi', 's.id = spi.student_id', 'left')
            ->where('sd.has_disability', 'Yes')
            ->orderBy('spi.last_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Check if student has disability record
     */
    public function hasDisabilityRecord($studentId)
    {
        return $this->where('student_id', $studentId)->countAllResults() > 0;
    }

    /**
     * Delete disability record for a student
     */
    public function deleteByStudentId($studentId)
    {
        return $this->where('student_id', $studentId)->delete();
    }
}