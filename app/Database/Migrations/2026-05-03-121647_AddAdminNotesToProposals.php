<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminNotesToProposals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('proposal_pengajuan', [
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status'
            ],
            'decided_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'admin_notes'
            ],
            'decided_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'decided_at'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('proposal_pengajuan', ['admin_notes', 'decided_at', 'decided_by']);
    }
}
