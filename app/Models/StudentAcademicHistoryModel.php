<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentAcademicHistoryModel extends Model
{
    protected $table = 'student_academic_history';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id',
        'previous_gwa',
        'performance_level',
        'last_grade_completed',
        'last_school_year',
        'last_school_attended',
        'school_id',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
}