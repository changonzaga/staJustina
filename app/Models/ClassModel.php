<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'class_name',
        'grade_level',
        'section',
        'school_year',
        'capacity',
        'is_active'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    // Validation rules
    protected $validationRules = [
        'class_name' => 'required|max_length[100]',
        'grade_level' => 'required|max_length[20]',
        'section' => 'required|max_length[50]',
        'school_year' => 'required|max_length[10]',
        'capacity' => 'permit_empty|integer|greater_than[0]',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'class_name' => [
            'required' => 'Class name is required',
            'max_length' => 'Class name cannot exceed 100 characters'
        ],
        'grade_level' => [
            'required' => 'Grade level is required',
            'max_length' => 'Grade level cannot exceed 20 characters'
        ],
        'section' => [
            'required' => 'Section is required',
            'max_length' => 'Section cannot exceed 50 characters'
        ],
        'school_year' => [
            'required' => 'School year is required',
            'max_length' => 'School year cannot exceed 10 characters'
        ],
        'capacity' => [
            'integer' => 'Capacity must be a number',
            'greater_than' => 'Capacity must be greater than 0'
        ]
    ];
    
    /**
     * Get active classes
     */
    public function getActiveClasses()
    {
        return $this->where('is_active', 1)
                   ->orderBy('grade_level', 'ASC')
                   ->orderBy('section', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get classes by school year
     */
    public function getBySchoolYear($schoolYear)
    {
        return $this->where('school_year', $schoolYear)
                   ->where('is_active', 1)
                   ->orderBy('grade_level', 'ASC')
                   ->orderBy('section', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get classes by grade level
     */
    public function getByGradeLevel($gradeLevel)
    {
        return $this->where('grade_level', $gradeLevel)
                   ->where('is_active', 1)
                   ->orderBy('section', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get classes with adviser information
     */
    public function getClassesWithAdviser($schoolYear = null)
    {
        $builder = $this->select('classes.*, CONCAT(t.first_name, " ", t.last_name) as adviser_name, t.id as adviser_id')
                       ->join('class_advisers ca', 'ca.class_id = classes.id AND ca.is_active = 1', 'left')
                       ->join('teachers t', 't.id = ca.teacher_id', 'left')
                       ->where('classes.is_active', 1);
        
        if ($schoolYear) {
            $builder->where('classes.school_year', $schoolYear);
        }
        
        return $builder->orderBy('classes.grade_level', 'ASC')
                      ->orderBy('classes.section', 'ASC')
                      ->findAll();
    }
    
    /**
     * Get class options for dropdowns
     */
    public function getOptions($schoolYear = null)
    {
        $builder = $this->select('id, class_name, grade_level, section')
                       ->where('is_active', 1);
        
        if ($schoolYear) {
            $builder->where('school_year', $schoolYear);
        }
        
        return $builder->orderBy('grade_level', 'ASC')
                      ->orderBy('section', 'ASC')
                      ->findAll();
    }
    
    /**
     * Check if class exists for grade level, section, and school year
     */
    public function classExists($gradeLevel, $section, $schoolYear, $excludeId = null)
    {
        $builder = $this->where('grade_level', $gradeLevel)
                       ->where('section', $section)
                       ->where('school_year', $schoolYear);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Get current school year
     */
    public function getCurrentSchoolYear()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        return $currentYear . '-' . $nextYear;
    }
    
    /**
     * Get all school years
     */
    public function getSchoolYears()
    {
        return $this->select('school_year')
                   ->distinct()
                   ->orderBy('school_year', 'DESC')
                   ->findColumn('school_year');
    }
}