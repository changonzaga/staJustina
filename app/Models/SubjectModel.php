<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'subject_name',
        'subject_code',
        'grade_level',
        'department'
    ];
    
    protected $useTimestamps = false;
    
    // Validation rules
    protected $validationRules = [
        'subject_name' => 'required|max_length[100]|is_unique[subjects.subject_name,id,{id}]',
        'subject_code' => 'permit_empty|max_length[20]',
        'grade_level' => 'permit_empty|max_length[20]',
        'department' => 'permit_empty|max_length[100]'
    ];
    
    protected $validationMessages = [
        'subject_name' => [
            'required' => 'Subject name is required',
            'max_length' => 'Subject name cannot exceed 100 characters',
            'is_unique' => 'This subject already exists'
        ],
        'subject_code' => [
            'max_length' => 'Subject code cannot exceed 20 characters'
        ],
        'grade_level' => [
            'max_length' => 'Grade level cannot exceed 20 characters'
        ],
        'department' => [
            'max_length' => 'Department cannot exceed 100 characters'
        ]
    ];
    
    /**
     * Get all subjects for dropdowns
     */
    public function getOptions()
    {
        return $this->select('id, subject_name, subject_code')
                   ->orderBy('subject_name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get subjects by department
     */
    public function getByDepartment($department)
    {
        return $this->where('department', $department)
                   ->orderBy('subject_name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get subjects by grade level
     */
    public function getByGradeLevel($gradeLevel)
    {
        return $this->where('grade_level', $gradeLevel)
                   ->orderBy('subject_name', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get subject by name
     */
    public function getByName($subjectName)
    {
        return $this->where('subject_name', $subjectName)->first();
    }
    
    /**
     * Get all departments
     */
    public function getDepartments()
    {
        return $this->select('department')
                   ->distinct()
                   ->where('department IS NOT NULL')
                   ->orderBy('department', 'ASC')
                   ->findColumn('department');
    }
}