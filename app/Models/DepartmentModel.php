<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'department_name',
        'description',
        'head_id',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    protected $validationRules = [
        'department_name' => 'required|min_length[2]|max_length[255]',
        'description'     => 'permit_empty|max_length[1000]',
        // If provided, head_id must be a valid existing teacher id
        'head_id'         => 'permit_empty|is_natural_no_zero|is_not_unique[teachers.id]',
    ];

    protected $validationMessages = [
        'department_name' => [
            'required'   => 'Department name is required.',
            'min_length' => 'Department name must be at least 2 characters.',
            'max_length' => 'Department name must not exceed 255 characters.',
        ],
        'description' => [
            'max_length' => 'Description must not exceed 1000 characters.',
        ],
        'head_id' => [
            'is_natural_no_zero' => 'Head must be a valid positive ID.',
            'is_not_unique' => 'Selected head does not exist in teachers.',
        ],
    ];
}