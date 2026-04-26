<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSintaProfilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'null'       => true,
                'unique'     => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_sinta' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'nama_sinta' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],
            'sinta_score_all_years' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'sinta_score_3_years' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'sinta_profile_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status_validasi_sinta' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Belum Sinkron',
            ],
            'sync_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'never',
            ],
            'sync_error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'raw_payload_json' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'last_synced_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sinta_profiles');
    }

    public function down()
    {
        $this->forge->dropTable('sinta_profiles');
    }
}
