<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentAddressesTable extends Migration
{
    public function up()
    {
        // Create enrollment_addresses table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'address_type' => [
                'type' => 'ENUM',
                'constraint' => ['current', 'permanent'],
            ],
            'house_no' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'street' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'barangay' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'municipality' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'province' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'Philippines',
            ],
            'zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'is_same_as_current' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'For permanent address same as current',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        
        // Set primary key
        $this->forge->addPrimaryKey('id');
        
        // Add indexes
        $this->forge->addKey('enrollment_id');
        $this->forge->addKey('address_type');
        
        // Add unique constraint for enrollment_id + address_type
        $this->forge->addUniqueKey(['enrollment_id', 'address_type'], 'uk_enrollment_address_type');
        
        // Add foreign key constraint
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id', 'CASCADE', 'CASCADE');
        
        // Create the table
        $this->forge->createTable('enrollment_addresses', true);
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('enrollment_addresses', true);
    }
}