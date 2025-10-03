<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CivilStatusSeeder extends Seeder
{
    public function run()
    {
        // Check if civil_status table is empty
        if ($this->db->table('civil_status')->countAll() == 0) {
            $data = [
                ['status' => 'Single'],
                ['status' => 'Married'],
                ['status' => 'Divorced'],
                ['status' => 'Widowed'],
                ['status' => 'Separated']
            ];

            $this->db->table('civil_status')->insertBatch($data);
            
            echo "Civil status data inserted successfully.\n";
        } else {
            echo "Civil status table already contains data.\n";
        }
    }
}