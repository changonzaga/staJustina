<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
            'type'=>'INT',
            'unsigned'=>TRUE,
            'auto_increment'=>TRUE,
        ],
            'name'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
            ],
            'username'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
            ],
            'email'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
            ],
            'password'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>TRUE,
            ],
            'picture'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>TRUE,
            ],
            'bio'=>[
                'type'=>'TEXT',
                'null'
            ],
            'created_at timestamp default current_timestamp',
            'updated_at timestamp default current_timestamp on update current_timestamp',
            
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->createTable('users');
        
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
