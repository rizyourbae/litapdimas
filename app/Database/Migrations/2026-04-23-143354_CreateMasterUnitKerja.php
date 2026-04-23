<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterUnitKerja extends Migration
{
    public function up()
    {
        // app/Database/Migrations/2026-04-23-xxxxxx-CreateMasterProfesi.php
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'uuid'        => ['type' => 'CHAR', 'constraint' => 36, 'null' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'parent_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
            'created_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'updated_by'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('uuid');
        $this->forge->addForeignKey('parent_id', 'master_unit_kerja', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('master_unit_kerja', true);
    }

    public function down()
    {
        $this->forge->dropTable('master_unit_kerja', true);
    }
}
