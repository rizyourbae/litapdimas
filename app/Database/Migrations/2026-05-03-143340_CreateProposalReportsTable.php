<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProposalReportsTable extends Migration
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
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'Laporan Antara, Laporan Keuangan Sementara, Laporan Akademik, Laporan Keuangan'
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'original_filename' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
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
        $this->forge->createTable('proposal_reports');
    }

    public function down()
    {
        $this->forge->dropTable('proposal_reports');
    }
}
