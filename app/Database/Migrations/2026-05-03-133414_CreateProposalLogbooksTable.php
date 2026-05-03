<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProposalLogbooksTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => '36',
            ],
            'proposal_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'tempat' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'nama_kegiatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'teknik' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'deskripsi_kegiatan' => [
                'type' => 'TEXT',
            ],
            'berkas_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
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
        $this->forge->addUniqueKey('uuid');
        $this->forge->addForeignKey('proposal_id', 'proposal_pengajuan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('proposal_logbooks');
    }

    public function down()
    {
        $this->forge->dropTable('proposal_logbooks');
    }
}
