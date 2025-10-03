<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmploymentStatusSeeder extends Seeder
{
    public function run()
    {
        // Check if employment_status table is empty
        if ($this->db->table('employment_status')->countAll() == 0) {
            $data = [
                ['status' => 'Regular'],
                ['status' => 'Contractual'],
                ['status' => 'Substitute'],
                ['status' => 'Part-time'],
                ['status' => 'Probationary'],
                ['status' => 'Temporary']
            ];

            $this->db->table('employment_status')->insertBatch($data);
            
            echo "Employment status data inserted successfully.\n";
        } else {
            echo "Employment status table already contains data.\n";
        }
    }
}