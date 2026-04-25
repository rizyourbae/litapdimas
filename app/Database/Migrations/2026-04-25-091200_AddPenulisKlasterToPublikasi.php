<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPenulisKlasterToPublikasi extends Migration
{
    public function up()
    {
        $fields = [
            'penulis' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'klaster' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
        ];

        $this->forge->addColumn('publikasi', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('publikasi', ['penulis', 'klaster']);
    }
}
