<?php
namespace App\Models;

use CodeIgniter\Model;

class StudentParentAddressModel extends Model
{
    protected $table            = 'student_parent_address';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'parent_id',
        'student_id',
        'parent_type',
        'is_same_as_student',
        'house_number',
        'street',
        'barangay',
        'municipality',
        'province',
        'zip_code',
        'created_at',
        'updated_at',
        'migration_status',
    ];

    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}