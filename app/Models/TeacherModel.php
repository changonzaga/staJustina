<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $table = 'teachers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'account_no',
        'name',
        'subjects',
        'gender',
        'age',
        'student_count',
        'status',
        'profile_picture',
        'created_at'
    ];
    
    protected $useTimestamps = false;
}