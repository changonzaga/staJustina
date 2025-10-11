<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentShsDetailsModel extends Model
{
    protected $table = 'student_shs_details';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id',
        'semester',
        'track',
        'strand',
        'specialization',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
}