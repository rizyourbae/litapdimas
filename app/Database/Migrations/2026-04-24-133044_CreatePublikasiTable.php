<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePublikasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'                 => ['type' => 'VARCHAR', 'constraint' => 36, 'unique' => true], 
            'user_id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], 
            'judul'                => ['type' => 'VARCHAR', 'constraint' => 255],
            'jenis_publikasi'      => ['type' => 'ENUM', 'constraint' => ['Jurnal', 'HKI', 'Prosiding', 'Buku']],
            'tahun'                => ['type' => 'YEAR', 'constraint' => 4],
            'sumber_pembiayaan'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'metadata'             => ['type' => 'JSON', 'null' => true],
            'created_at'           => ['type' => 'DATETIME', 'null' => true],
            'updated_at'           => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'           => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true); // Primary Key

        // Relasi ke tabel users (Asumsi primary key users adalah id integer)
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('publikasi');
    }

    public function down()
    {
        $this->forge->dropTable('publikasi');
    }
}
