<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterJabatanFungsional extends Migration
{
    public function up()
    {
        // app/Database/Migrations/2026-04-23-xxxxxx-CreateMasterProfesi.php
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'uuid'        => ['type' => 'CHAR', 'constraint' => 36, 'null' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
            'created_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'updated_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('uuid');
        $this->forge->createTable('master_jabatan_fungsional', true);
    }

    public function down()
    {
        $this->forge->dropTable('master_jabatan_fungsional', true);
    }
}
