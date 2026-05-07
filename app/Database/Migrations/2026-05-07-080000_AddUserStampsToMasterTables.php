<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserStampsToMasterTables extends Migration
{
    public function up()
    {
        // Add to bidang_ilmu table
        if (!$this->db->fieldExists('created_by', 'bidang_ilmu')) {
            $this->forge->addColumn('bidang_ilmu', [
                'created_by' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'deleted_at',
                ],
            ]);
        }

        if (!$this->db->fieldExists('updated_by', 'bidang_ilmu')) {
            $this->forge->addColumn('bidang_ilmu', [
                'updated_by' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'created_by',
                ],
            ]);
        }

        // Add to klaster_bantuan table
        if (!$this->db->fieldExists('created_by', 'klaster_bantuan')) {
            $this->forge->addColumn('klaster_bantuan', [
                'created_by' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'deleted_at',
                ],
            ]);
        }

        if (!$this->db->fieldExists('updated_by', 'klaster_bantuan')) {
            $this->forge->addColumn('klaster_bantuan', [
                'updated_by' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'created_by',
                ],
            ]);
        }

        // Add to tema_penelitian table
        if (!$this->db->fieldExists('created_by', 'tema_penelitian')) {
            $this->forge->addColumn('tema_penelitian', [
                'created_by' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'deleted_at',
                ],
            ]);
        }

        if (!$this->db->fieldExists('updated_by', 'tema_penelitian')) {
            $this->forge->addColumn('tema_penelitian', [
                'updated_by' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'created_by',
                ],
            ]);
        }
    }

    public function down()
    {
        // Remove from bidang_ilmu
        if ($this->db->fieldExists('created_by', 'bidang_ilmu')) {
            $this->forge->dropColumn('bidang_ilmu', 'created_by');
        }
        if ($this->db->fieldExists('updated_by', 'bidang_ilmu')) {
            $this->forge->dropColumn('bidang_ilmu', 'updated_by');
        }

        // Remove from klaster_bantuan
        if ($this->db->fieldExists('created_by', 'klaster_bantuan')) {
            $this->forge->dropColumn('klaster_bantuan', 'created_by');
        }
        if ($this->db->fieldExists('updated_by', 'klaster_bantuan')) {
            $this->forge->dropColumn('klaster_bantuan', 'updated_by');
        }

        // Remove from tema_penelitian
        if ($this->db->fieldExists('created_by', 'tema_penelitian')) {
            $this->forge->dropColumn('tema_penelitian', 'created_by');
        }
        if ($this->db->fieldExists('updated_by', 'tema_penelitian')) {
            $this->forge->dropColumn('tema_penelitian', 'updated_by');
        }
    }
}
