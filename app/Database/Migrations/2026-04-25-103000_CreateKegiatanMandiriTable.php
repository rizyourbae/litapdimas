<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKegiatanMandiriTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'                    => ['type' => 'VARCHAR', 'constraint' => 36, 'unique' => true, 'null' => true],
            'user_id'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tahun'                   => ['type' => 'YEAR', 'constraint' => 4],
            'jenis_kegiatan'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'klaster_skala_kegiatan'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'judul_kegiatan'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'anggota_terlibat'        => ['type' => 'TEXT', 'null' => true],
            'resume_kegiatan'         => ['type' => 'TEXT', 'null' => true],
            'unit_pelaksana_kegiatan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'mitra_kolaborasi'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sumber_dana'             => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'besaran_dana'            => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'null' => true],
            'tautan_bukti_dukung'     => ['type' => 'TEXT', 'null' => true],
            'created_at'              => ['type' => 'DATETIME', 'null' => true],
            'updated_at'              => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'              => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kegiatan_mandiri', true);
    }

    public function down()
    {
        $this->forge->dropTable('kegiatan_mandiri', true);
    }
}
