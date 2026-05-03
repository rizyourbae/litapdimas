<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOutcomeNotesToProposals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('proposal_pengajuan', [
            'outcome_admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'approved_amount',
                'comment' => 'Catatan dari validator terkait luaran/outcome penelitian'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('proposal_pengajuan', 'outcome_admin_notes');
    }
}
