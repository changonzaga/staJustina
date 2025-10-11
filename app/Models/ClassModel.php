<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'class_code',
        'class_name',
        'grade_id',
        'section_id',
        'teacher_id',
        'academic_period_id',
        'strand_id'
    ];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    // Validation rules
    protected $validationRules = [
        'class_name' => 'required|max_length[100]',
        'grade_id' => 'required|integer',
        'section_id' => 'required|integer',
        
    ];
    
    protected $validationMessages = [
        'class_name' => [
            'required' => 'Class name is required',
            'max_length' => 'Class name cannot exceed 100 characters'
        ],
        'grade_id' => [
            'required' => 'Grade is required',
            'integer' => 'Grade must be a valid selection'
        ],
        'section_id' => [
            'required' => 'Section is required',
            'integer' => 'Section must be a valid selection'
        ],
        'subject_id' => [
            'required' => 'Subject is required',
            'integer' => 'Subject must be a valid selection'
        ],
        'school_year_id' => [
            'required' => 'School year is required',
            'integer' => 'School year must be a valid selection'
        ]
    ];
    
    /**
     * Get active classes
     */
    public function getActiveClasses()
    {
        return $this->orderBy('grade_id', 'ASC')
                   ->orderBy('section_id', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get classes by school year
     */
    public function getBySchoolYear($schoolYearId)
    {
        return $this->where('school_year_id', $schoolYearId)
                   ->orderBy('grade_id', 'ASC')
                   ->orderBy('section_id', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get classes by grade level
     */
    public function getByGradeLevel($gradeId)
    {
        return $this->where('grade_id', $gradeId)
                   ->orderBy('section_id', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get classes with adviser information
     */
    public function getClassesWithAdviser($schoolYearId = null)
    {
        $builder = $this->db->table('classes c')
            ->select('c.*, g.grade_name, s.section_name, t.first_name, t.last_name')
            ->join('grades g', 'g.id = c.grade_id', 'left')
            ->join('sections s', 's.id = c.section_id', 'left')
            ->join('teachers t', 't.id = c.teacher_id', 'left');
            
        if ($schoolYearId) {
            $builder->where('c.school_year_id', $schoolYearId);
        }
        
        return $builder->orderBy('g.grade_name', 'ASC')
                      ->orderBy('s.section_name', 'ASC')
                      ->get()
                      ->getResultArray();
    }
    
    /**
     * Get class options for dropdowns
     */
    public function getOptions($schoolYearId = null)
    {
        $builder = $this->select('id, class_name, grade_id, section_id');
        
        if ($schoolYearId) {
            $builder->where('school_year_id', $schoolYearId);
        }
        
        return $builder->orderBy('grade_id', 'ASC')
                      ->orderBy('section_id', 'ASC')
                      ->findAll();
    }
    
    /**
     * Check if class exists for grade level, section, and school year
     */
    public function classExists($gradeId, $sectionId, $schoolYearId, $excludeId = null)
    {
        $builder = $this->where('grade_id', $gradeId)
                       ->where('section_id', $sectionId)
                       ->where('school_year_id', $schoolYearId);
        
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
    
    /**
     * Get all classes with detailed information
     */
    public function getAllClassesWithDetails()
    {
        $builder = $this->db->table('classes c')
            ->select('
                c.id,
                c.class_code,
                c.class_name,
                c.grade_id,
                c.section_id,
                c.teacher_id,
                g.grade_name as grade_level,
                CONCAT(t.first_name, " ", t.last_name) as adviser
            ')
            ->join('grades g', 'g.id = c.grade_id', 'left')
            
            ->join('teachers t', 't.id = c.teacher_id', 'left')
            ->orderBy('g.grade_name', 'ASC')
            ->orderBy('c.section_id', 'ASC');
            
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get single class with detailed information
     */
    public function getClassWithDetails($id)
    {
        $builder = $this->db->table('classes c')
            ->select('
                c.id,
                c.class_code,
                c.class_name,
                c.grade_id,
                c.section_id,
                c.teacher_id,
                g.grade_name as grade_level,
                CONCAT(t.first_name, " ", t.last_name) as adviser
            ')
            ->join('grades g', 'g.id = c.grade_id', 'left')
            
            ->join('teachers t', 't.id = c.teacher_id', 'left')
            ->where('c.id', $id);
            
        $result = $builder->get()->getRowArray();
        return $result ?: null;
    }
    
    /**
     * Get students enrolled in a specific class (placeholder - no student_classes table)
     */
    public function getClassStudents($classId)
    {
        // Return empty array since student_classes table doesn't exist
        return [];
    }
    
    /**
     * Check if class has enrolled students (placeholder - no student_classes table)
     */
    public function hasEnrolledStudents($classId)
    {
        // Return false since student_classes table doesn't exist
        return false;
    }
    
    /**
     * Generate unique class code
     */
    public function generateClassCode()
    {
        $prefix = 'CLS';
        $year = date('Y');
        
        // Get the last class code for this year
        $lastCode = $this->db->table('classes')
            ->select('class_code')
            ->like('class_code', $prefix . $year)
            ->orderBy('class_code', 'DESC')
            ->get()
            ->getRow();
            
        if ($lastCode) {
            // Extract number from last code and increment
            $lastNumber = (int) substr($lastCode->class_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get dropdown data for forms
     */
    public function getDropdowns()
    {
        $data = [];
        
        // Get grades
        $data['grades'] = $this->db->table('grades')
            ->select('id, grade_name')
            ->orderBy('grade_name', 'ASC')
            ->get()
            ->getResultArray();
            
        // Get subjects
        $data['subjects'] = $this->db->table('subjects')
            ->select('id, subject_name')
            ->orderBy('subject_name', 'ASC')
            ->get()
            ->getResultArray();
            
        // Get teachers
        $data['teachers'] = $this->db->table('teachers')
            ->select('id, CONCAT(first_name, " ", last_name) as full_name')
            ->orderBy('last_name', 'ASC')
            ->get()
            ->getResultArray();
            
        // For sections and school_years, we'll create simple arrays since these tables don't exist
        $data['sections'] = [
            ['id' => 1, 'section_name' => 'Section A'],
            ['id' => 2, 'section_name' => 'Section B'],
            ['id' => 3, 'section_name' => 'Section C']
        ];
        
        $data['school_years'] = [
            ['id' => 1, 'year_name' => '2024-2025'],
            ['id' => 2, 'year_name' => '2023-2024']
        ];
            
        return $data;
    }
}