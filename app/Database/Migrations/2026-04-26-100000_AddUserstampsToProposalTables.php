<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserstampsToProposalTables extends Migration
{
    public function up()
    {
        $fields = [
            'created_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'updated_by' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ];

        try {
            $this->forge->addColumn('bidang_ilmu', $fields);
        } catch (\Exception $e) {
            // ignore if the columns already exist
        }

        try {
            $this->forge->addColumn('klaster_bantuan', $fields);
        } catch (\Exception $e) {
            // ignore if the columns already exist
        }

        try {
            $this->forge->addColumn('tema_penelitian', $fields);
        } catch (\Exception $e) {
            // ignore if the columns already exist
        }
    }

    public function down()
    {
        try {
            $this->forge->dropColumn('bidang_ilmu', ['created_by', 'updated_by']);
        } catch (\Exception $e) {
            // ignore
        }

        try {
            $this->forge->dropColumn('klaster_bantuan', ['created_by', 'updated_by']);
        } catch (\Exception $e) {
            // ignore
        }

        try {
            $this->forge->dropColumn('tema_penelitian', ['created_by', 'updated_by']);
        } catch (\Exception $e) {
            // ignore
        }
    }
}
