<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToProposalTables extends Migration
{
    public function up()
    {
        // Add deleted_at to existing proposal master tables if missing
        $fields = [
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        // Wrap in try/catch to avoid migration crash if table/column already exists
        try {
            $this->forge->addColumn('bidang_ilmu', $fields);
        } catch (\Exception $e) {
            // ignore
        }

        try {
            $this->forge->addColumn('klaster_bantuan', $fields);
        } catch (\Exception $e) {
            // ignore
        }

        try {
            $this->forge->addColumn('tema_penelitian', $fields);
        } catch (\Exception $e) {
            // ignore
        }
    }

    public function down()
    {
        try {
            $this->forge->dropColumn('bidang_ilmu', 'deleted_at');
        } catch (\Exception $e) {
            // ignore
        }

        try {
            $this->forge->dropColumn('klaster_bantuan', 'deleted_at');
        } catch (\Exception $e) {
            // ignore
        }

        try {
            $this->forge->dropColumn('tema_penelitian', 'deleted_at');
        } catch (\Exception $e) {
            // ignore
        }
    }
}
