<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentPersonalInfoModel extends Model
{
    protected $table = 'student_personal_info';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'student_id',
        'last_name',
        'first_name',
        'middle_name',
        'extension_name',
        'birth_certificate_number',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'age',
        'mother_tongue',
        'student_email',
        'student_contact',
        'profile_picture',
        'indigenous_people',
        'indigenous_community',
        'fourps_beneficiary',
        'fourps_household_id',
        'lrn',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
}