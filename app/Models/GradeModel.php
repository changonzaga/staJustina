<?php

namespace App\Models;

use CodeIgniter\Model;

class GradeModel extends Model
{
    protected $table = 'grades';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['grade_name', 'description', 'education_level'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    // Validation rules
    protected $validationRules = [
        'grade_name' => 'required|min_length[1]|max_length[50]',
        'description' => 'permit_empty|max_length[255]',
        'education_level' => 'permit_empty|in_list[Elementary,Junior High,Senior High]'
    ];

    protected $validationMessages = [
        'grade_name' => [
            'required' => 'Grade name is required',
            'min_length' => 'Grade name must be at least 1 character',
            'max_length' => 'Grade name must not exceed 50 characters'
        ],
        'description' => [
            'max_length' => 'Description must not exceed 255 characters'
        ],
        'education_level' => [
            'in_list' => 'Education level must be Elementary, Junior High, or Senior High'
        ]
    ];

    /**
     * Get all grades ordered by grade number (7, 8, 9, 10, 11, 12)
     */
    public function getAllGrades()
    {
        $grades = $this->findAll();
        
        // Custom sorting to handle grade numbers properly (7, 8, 9, 10, 11, 12)
        usort($grades, function($a, $b) {
            // Extract numeric part from grade_name (e.g., "Grade 7" -> 7)
            preg_match('/(\d+)/', $a['grade_name'], $matchesA);
            preg_match('/(\d+)/', $b['grade_name'], $matchesB);
            
            $numA = isset($matchesA[1]) ? (int)$matchesA[1] : 0;
            $numB = isset($matchesB[1]) ? (int)$matchesB[1] : 0;
            
            return $numA - $numB;
        });
        
        return $grades;
    }

    /**
     * Get active grades (if you add an is_active field later)
     */
    public function getActiveGrades()
    {
        $grades = $this->findAll();
        
        // Custom sorting to handle grade numbers properly (7, 8, 9, 10, 11, 12)
        usort($grades, function($a, $b) {
            // Extract numeric part from grade_name (e.g., "Grade 7" -> 7)
            preg_match('/(\d+)/', $a['grade_name'], $matchesA);
            preg_match('/(\d+)/', $b['grade_name'], $matchesB);
            
            $numA = isset($matchesA[1]) ? (int)$matchesA[1] : 0;
            $numB = isset($matchesB[1]) ? (int)$matchesB[1] : 0;
            
            return $numA - $numB;
        });
        
        return $grades;
    }

    /**
     * Get grades by education level
     */
    public function getGradesByEducationLevel($educationLevel)
    {
        $grades = $this->where('education_level', $educationLevel)->findAll();
        
        // Custom sorting to handle grade numbers properly (7, 8, 9, 10, 11, 12)
        usort($grades, function($a, $b) {
            // Extract numeric part from grade_name (e.g., "Grade 7" -> 7)
            preg_match('/(\d+)/', $a['grade_name'], $matchesA);
            preg_match('/(\d+)/', $b['grade_name'], $matchesB);
            
            $numA = isset($matchesA[1]) ? (int)$matchesA[1] : 0;
            $numB = isset($matchesB[1]) ? (int)$matchesB[1] : 0;
            
            return $numA - $numB;
        });
        
        return $grades;
    }

    /**
     * Get grade by ID with error handling
     */
    public function getGradeById($id)
    {
        return $this->find($id);
    }
}
