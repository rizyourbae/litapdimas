<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProposalOutcomesTable extends Migration
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
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['jurnal', 'buku'],
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'nama_penerbit_jurnal' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'volume_nomor' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'isbn' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'tahun_terbit' => [
                'type'       => 'VARCHAR',
                'constraint' => '4',
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
        $this->forge->createTable('proposal_outcomes');
    }

    public function down()
    {
        $this->forge->dropTable('proposal_outcomes');
    }
}
