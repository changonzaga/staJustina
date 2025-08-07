<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'student'; // Make sure you're not using 'students'
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'lrn',
        'grade_level',
        'section',
        'gender',
        'age',
        'guardian',
        'contact',
        'address',
        'profile_picture',
        'teacher_id',
        'parent_id',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime'; // for consistency (optional)

    // Validation is handled in the controller
    protected $validationRules = [];

    // Validation messages are handled in the controller
    protected $validationMessages = [];

    /**
     * Get students with joined teacher and parent info.
     */
    public function getStudentsWithRelations()
    {
       return $this->select('student.*, teacher.name AS teacher_name, parent.name AS parent_name')
                ->join('teacher', 'teacher.id = student.teacher_id', 'left')
                ->join('parent', 'parent.id = student.parent_id', 'left')
                ->findAll();
    }

    /**
     * Get student by LRN.
     */
    public function getByLRN($lrn)
    {
        return $this->where('lrn', $lrn)->first();
    }

    /**
     * Get students by section.
     */
    public function getBySection($section)
    {
        return $this->where('section', $section)->findAll();
    }

    /**
     * Get students by grade level.
     */
    public function getByGradeLevel($gradeLevel)
    {
        return $this->where('grade_level', $gradeLevel)->findAll();
    }
}
