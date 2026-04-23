<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterProgramStudi extends Migration
{
    public function up()
    {
        // app/Database/Migrations/2026-04-23-xxxxxx-CreateMasterProfesi.php
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'uuid'        => ['type' => 'CHAR', 'constraint' => 36, 'null' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'fakultas_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
            'created_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'updated_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('uuid');
        $this->forge->addForeignKey('fakultas_id', 'master_fakultas', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('master_program_studi', true);
    }

    public function down()
    {
        $this->forge->dropTable('master_program_studi', true);
    }
}
