<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLandingPageTables extends Migration
{
    public function up()
    {
        // 1. Table Landing Settings (Key-Value)
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'key'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'value'       => ['type' => 'TEXT', 'null' => true],
            'group'       => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'general'], // hero, footer, contact, etc
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('key');
        $this->forge->createTable('landing_settings');

        // 2. Table Tema Riset
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'        => ['type' => 'VARCHAR', 'constraint' => 36],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'icon'        => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'bi bi-journal-text'],
            'keterangan'  => ['type' => 'TEXT', 'null' => true],
            'is_active'   => ['type' => 'BOOLEAN', 'default' => true],
            'sort_order'  => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('uuid');
        $this->forge->createTable('tema_riset');
    }

    public function down()
    {
        $this->forge->dropTable('tema_riset');
        $this->forge->dropTable('landing_settings');
    }
}
