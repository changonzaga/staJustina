<?php
// app/Models/SubjectModel.php
namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['subject_code', 'subject_name', 'grade_id', 'department_id'];
    protected $useTimestamps = false;

    // Validation rules matching database constraints
    protected $validationRules = [
        'subject_name' => 'required|min_length[1]|max_length[100]',
        'subject_code' => 'permit_empty|max_length[20]',
        'grade_id' => 'required|integer|in_list[1,2,3,4]',
        'department_id' => 'permit_empty|integer|is_natural_no_zero'
    ];

    protected $validationMessages = [
        'subject_name' => [
            'required' => 'Subject name is required',
            'min_length' => 'Subject name must be at least 1 character',
            'max_length' => 'Subject name must not exceed 100 characters'
        ],
        'subject_code' => [
            'max_length' => 'Subject code must not exceed 20 characters'
        ],
        'grade_id' => [
            'required' => 'Grade is required',
            'integer' => 'Grade must be a valid number',
            'in_list' => 'Grade must be 1, 2, 3, or 4 (corresponding to Grade 7, 8, 9, 10)'
        ],
        'department_id' => [
            'integer' => 'Department ID must be a valid number',
            'is_natural_no_zero' => 'Department ID must be a positive number'
        ]
    ];

    // Custom method to get subjects with grade level mapped
    public function getSubjectsWithGradeLevel()
    {
        $subjects = $this->findAll();
        $gradeMap = [
            1 => 'Grade 7',
            2 => 'Grade 8',
            3 => 'Grade 9',
            4 => 'Grade 10'
        ];
        foreach ($subjects as &$subject) {
            $subject['grade_level'] = $gradeMap[$subject['grade_id']] ?? 'Unknown';
        }
        return $subjects;
    }

    // Custom method to get subjects with grade level and department information
    public function getSubjectsWithGradeLevelAndDepartment()
    {
        $builder = $this->db->table('subjects s');
        $builder->select('s.*, d.department_name');
        $builder->join('departments d', 's.department_id = d.id', 'left');
        $subjects = $builder->get()->getResultArray();
        
        $gradeMap = [
            1 => 'Grade 7',
            2 => 'Grade 8',
            3 => 'Grade 9',
            4 => 'Grade 10'
        ];
        
        foreach ($subjects as &$subject) {
            $subject['grade_level'] = $gradeMap[$subject['grade_id']] ?? 'Unknown';
            $subject['department_name'] = $subject['department_name'] ?? 'No Department';
        }
        
        return $subjects;
    }
}
?>