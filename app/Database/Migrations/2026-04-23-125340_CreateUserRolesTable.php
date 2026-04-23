<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserRolesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id'    => ['type' => 'INT', 'unsigned' => true],
            'role_id'    => ['type' => 'INT', 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey(['user_id', 'role_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_roles', true);
    }

    public function down()
    {
        //
    }
}
