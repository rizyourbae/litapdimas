<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSumberPembiayaanToPublikasi extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('sumber_pembiayaan', 'publikasi')) {
            $this->forge->addColumn('publikasi', [
                'sumber_pembiayaan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'klaster',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('sumber_pembiayaan', 'publikasi')) {
            $this->forge->dropColumn('publikasi', 'sumber_pembiayaan');
        }
    }
}
