<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminCredentialUpdateSeeder extends Seeder
{
    public function run()
    {
        // New admin credentials
        $newEmail = 'sjnhs2025@gmail.com';
        $newPasswordPlain = 'stajustinasystem2025';

        $data = [
            'email' => $newEmail,
            'password' => password_hash($newPasswordPlain, PASSWORD_BCRYPT),
        ];

        // Update admin user with id = 1
        $this->db->table('users')->where('id', 1)->update($data);
    }
}