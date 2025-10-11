<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $table = 'sections';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'grade_id', 
        'section_name', 
        'capacity', 
        'school_year', 
        'adviser_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all sections with grade and adviser information
     */
    public function getAllSectionsWithDetails()
    {
        $builder = $this->db->table($this->table);
        $builder->select('sections.*, grades.grade_name as grade_level, CONCAT(teachers.first_name, " ", teachers.last_name) as adviser_name');
        $builder->join('grades', 'grades.id = sections.grade_id', 'left');
        $builder->join('teachers', 'teachers.id = sections.adviser_id', 'left');
        $builder->orderBy('sections.grade_id', 'ASC');
        $builder->orderBy('sections.section_name', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get a single section with grade and adviser information
     */
    public function getSectionWithDetails($id)
    {
        $builder = $this->db->table($this->table);
        $builder->select('sections.*, grades.grade_name as grade_level, CONCAT(teachers.first_name, " ", teachers.last_name) as adviser_name');
        $builder->join('grades', 'grades.id = sections.grade_id', 'left');
        $builder->join('teachers', 'teachers.id = sections.adviser_id', 'left');
        $builder->where('sections.id', $id);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Check if a section already exists for a grade and school year
     */
    public function sectionExists($gradeId, $sectionName, $schoolYear, $excludeId = null)
    {
        $builder = $this->db->table($this->table);
        $builder->where('grade_id', $gradeId);
        $builder->where('section_name', $sectionName);
        $builder->where('school_year', $schoolYear);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Get sections for a specific grade
     */
    public function getSectionsByGrade($gradeId)
    {
        return $this->where('grade_id', $gradeId)
                    ->orderBy('section_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get sections for a specific school year
     */
    public function getSectionsBySchoolYear($schoolYear)
    {
        return $this->where('school_year', $schoolYear)
                    ->orderBy('grade_id', 'ASC')
                    ->orderBy('section_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get sections with student count
     */
    public function getSectionsWithStudentCount()
    {
        $builder = $this->db->table($this->table);
        $builder->select('sections.*, grades.grade_name, COUNT(students.id) as student_count');
        $builder->join('grades', 'grades.id = sections.grade_id', 'left');
        $builder->join('students', 'students.section_id = sections.id', 'left');
        $builder->groupBy('sections.id');
        $builder->orderBy('sections.grade_id', 'ASC');
        $builder->orderBy('sections.section_name', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}