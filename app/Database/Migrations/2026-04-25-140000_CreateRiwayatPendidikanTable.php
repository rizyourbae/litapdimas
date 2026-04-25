<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRiwayatPendidikanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'                 => ['type' => 'VARCHAR', 'constraint' => 36, 'unique' => true, 'null' => true],
            'user_id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jenjang_pendidikan'   => ['type' => 'VARCHAR', 'constraint' => 50], // S1, S2, S3, etc
            'program_studi'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'institusi'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'tahun_masuk'          => ['type' => 'YEAR', 'constraint' => 4],
            'tahun_lulus'          => ['type' => 'YEAR', 'constraint' => 4],
            'ipk'                  => ['type' => 'DECIMAL', 'constraint' => '3,2', 'null' => true], // 3.45
            'dokumen_ijazah'       => ['type' => 'TEXT', 'null' => true], // URL or file path
            'dokumen_tipe'         => ['type' => 'ENUM', 'constraint' => ['url', 'file'], 'default' => 'url'], // url or file
            'created_at'           => ['type' => 'DATETIME', 'null' => true],
            'updated_at'           => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'           => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('riwayat_pendidikan', true);
    }

    public function down()
    {
        $this->forge->dropTable('riwayat_pendidikan', true);
    }
}
