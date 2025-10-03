<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherSpecializationModel extends Model
{
    protected $table = 'teacher_specializations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'teacher_id',
        'subject_id',
        'proficiency_level',
        'is_primary',
        'years_experience'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    // Validation rules
    protected $validationRules = [
        'teacher_id' => 'required|integer',
        'subject_id' => 'required|integer',
        'proficiency_level' => 'required|in_list[Basic,Intermediate,Advanced,Expert]',
        'is_primary' => 'permit_empty|in_list[0,1]',
        'years_experience' => 'permit_empty|integer|greater_than_equal_to[0]|less_than[100]'
    ];
    
    protected $validationMessages = [
        'teacher_id' => [
            'required' => 'Teacher ID is required',
            'integer' => 'Teacher ID must be a valid number'
        ],
        'subject_id' => [
            'required' => 'Subject is required',
            'integer' => 'Subject ID must be a valid number'
        ],
        'proficiency_level' => [
            'required' => 'Proficiency level is required',
            'in_list' => 'Proficiency level must be Basic, Intermediate, Advanced, or Expert'
        ],
        'years_experience' => [
            'integer' => 'Years of experience must be a valid number',
            'greater_than_equal_to' => 'Years of experience cannot be negative',
            'less_than' => 'Years of experience must be less than 100'
        ]
    ];
    
    /**
     * Get specializations by teacher ID
     */
    public function getByTeacherId($teacherId)
    {
        return $this->select('teacher_specializations.*, subjects.subject_name, subjects.subject_code')
                   ->join('subjects', 'subjects.id = teacher_specializations.subject_id')
                   ->where('teacher_id', $teacherId)
                   ->findAll();
    }
    
    /**
     * Get primary specialization for a teacher
     */
    public function getPrimarySpecialization($teacherId)
    {
        return $this->select('teacher_specializations.*, subjects.subject_name, subjects.subject_code')
                   ->join('subjects', 'subjects.id = teacher_specializations.subject_id')
                   ->where('teacher_id', $teacherId)
                   ->where('is_primary', 1)
                   ->first();
    }
    
    /**
     * Set primary specialization (ensures only one primary per teacher)
     */
    public function setPrimarySpecialization($teacherId, $subjectId)
    {
        // First, remove primary flag from all specializations for this teacher
        $this->where('teacher_id', $teacherId)
             ->set(['is_primary' => 0])
             ->update();
        
        // Then set the new primary specialization
        return $this->where('teacher_id', $teacherId)
                   ->where('subject_id', $subjectId)
                   ->set(['is_primary' => 1])
                   ->update();
    }
    
    /**
     * Delete specializations by teacher ID
     */
    public function deleteByTeacherId($teacherId)
    {
        return $this->where('teacher_id', $teacherId)->delete();
    }
}