<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Libraries\Hash;

class UpdateUserPassword extends Seeder
{
    public function run()
    {
        $data = [
            'password' => Hash::make('cjpogi123')
        ];

        $this->db->table('users')
                  ->where('id', 3)
                  ->update($data);
    }
}