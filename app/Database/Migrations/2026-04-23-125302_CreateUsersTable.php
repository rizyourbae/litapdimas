<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'        => ['type' => 'CHAR', 'constraint' => 36, 'null' => true],
            'username'    => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama_lengkap' => ['type' => 'VARCHAR', 'constraint' => 100],
            'aktif'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
            'created_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'updated_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('uuid');
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
