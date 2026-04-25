<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKelengkapanDokumenTable extends Migration
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
            'jenis_dokumen' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'comment'    => 'Sertifikat Dosen, SK Jabatan Fungsional, Kartu NIDN',
            ],
            'dokumen_file' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Path ke file dokumen',
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kelengkapan_dokumen');
    }

    public function down()
    {
        $this->forge->dropTable('kelengkapan_dokumen');
    }
}
