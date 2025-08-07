<?php

namespace App\Models;

use CodeIgniter\Model;

class ParentModel extends Model
{
    protected $table = 'parent';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'email',
        'password',
        'contact',
        'profile_picture',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
