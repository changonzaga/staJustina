<?php
namespace App\Models;

use CodeIgniter\Model;

class StudentParentRelationshipModel extends Model
{
    protected $table            = 'student_parent_relationships';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'student_id',
        'parent_id',
        'relationship_type',
        'is_primary_contact',
        'is_emergency_contact',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}


