<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApprovedAmountToProposals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('proposal_pengajuan', [
            'approved_amount' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'status',
                'comment'    => 'Biaya yang disetujui oleh admin'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('proposal_pengajuan', 'approved_amount');
    }
}
