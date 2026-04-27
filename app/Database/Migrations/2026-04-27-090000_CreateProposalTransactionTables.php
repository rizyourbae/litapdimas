<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProposalTransactionTables extends Migration
{
    public function up()
    {
        // ============================================================
        // Table: proposal_pengajuan (Header/Main)
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'unique'     => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kata_kunci' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Comma-separated keywords',
            ],
            'pengelola_bantuan_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'klaster_bantuan_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'bidang_ilmu_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'tema_penelitian_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'jenis_penelitian_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'kontribusi_prodi_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'draft',
                'comment'    => 'draft, submitted, reviewed, approved, rejected',
            ],
            'current_step' => [
                'type'       => 'INT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1-5 for wizard steps',
            ],
            'step_1_data' => [
                'type'       => 'LONGTEXT',
                'null'       => true,
                'comment'    => 'JSON: Step 1 draft data',
            ],
            'step_2_data' => [
                'type'       => 'LONGTEXT',
                'null'       => true,
                'comment'    => 'JSON: Step 2 draft data',
            ],
            'step_3_data' => [
                'type'       => 'LONGTEXT',
                'null'       => true,
                'comment'    => 'JSON: Step 3 draft data',
            ],
            'step_4_data' => [
                'type'       => 'LONGTEXT',
                'null'       => true,
                'comment'    => 'JSON: Step 4 draft data',
            ],
            'step_5_data' => [
                'type'       => 'LONGTEXT',
                'null'       => true,
                'comment'    => 'JSON: Step 5 draft data',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'updated_by' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->createTable('proposal_pengajuan');

        // ============================================================
        // Table: proposal_peneliti (Repeatable - 1 to many)
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'unique'     => true,
            ],
            'proposal_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'asal_instansi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'posisi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'is_internal' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Internal, 0=External',
            ],
            'is_ketua' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '1=Lead researcher, 0=Member',
            ],
            'order_position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('proposal_id');
        $this->forge->createTable('proposal_peneliti');

        // ============================================================
        // Table: proposal_mahasiswa (Repeatable - 1 to many)
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'unique'     => true,
            ],
            'proposal_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nim' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'program_studi_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'order_position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('proposal_id');
        $this->forge->createTable('proposal_mahasiswa');

        // ============================================================
        // Table: proposal_anggota_eksternal (Repeatable - 1 to many)
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'unique'     => true,
            ],
            'proposal_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'institusi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'posisi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'tipe' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'PTU, Profesional, Other',
            ],
            'order_position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('proposal_id');
        $this->forge->createTable('proposal_anggota_eksternal');

        // ============================================================
        // Table: proposal_substansi_bagian (Repeatable - 1 to many)
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'unique'     => true,
            ],
            'proposal_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'abstrak' => [
                'type'       => 'LONGTEXT',
                'null'       => true,
                'comment'    => 'Rich text (Quill HTML)',
            ],
            'judul_bagian' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'isi_bagian' => [
                'type'       => 'LONGTEXT',
                'null'       => true,
                'comment'    => 'Rich text (Quill HTML)',
            ],
            'order_position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('proposal_id');
        $this->forge->createTable('proposal_substansi_bagian');

        // ============================================================
        // Table: proposal_dokumen (Repeatable - 1 to many)
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'unique'     => true,
            ],
            'proposal_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'tipe_dokumen' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'comment'    => 'proposal, rab, similarity, pendukung',
            ],
            'nama_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'path_file' => [
                'type'       => 'TEXT',
                'comment'    => 'writable/uploads/proposal/{uuid}/{file}',
            ],
            'file_size' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'File size in bytes',
            ],
            'mime_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'keterangan' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'order_position' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('proposal_id');
        $this->forge->createTable('proposal_dokumen');

        // ============================================================
        // Table: proposal_jurnal (1 to 1)
        // ============================================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => 36,
                'unique'     => true,
            ],
            'proposal_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'unique'     => true,
            ],
            'issn' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_jurnal' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'profil_jurnal' => [
                'type'       => 'LONGTEXT',
                'null'       => true,
                'comment'    => 'Rich text (Quill HTML)',
            ],
            'url_website' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'url_scopus_wos' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'url_surat_rekomendasi' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'total_pengajuan_dana' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Max 100000000 (Rp 100 juta)',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        // proposal_id is already unique via field definition, no need for addKey
        $this->forge->createTable('proposal_jurnal');
    }

    public function down()
    {
        $this->forge->dropTable('proposal_jurnal');
        $this->forge->dropTable('proposal_dokumen');
        $this->forge->dropTable('proposal_substansi_bagian');
        $this->forge->dropTable('proposal_anggota_eksternal');
        $this->forge->dropTable('proposal_mahasiswa');
        $this->forge->dropTable('proposal_peneliti');
        $this->forge->dropTable('proposal_pengajuan');
    }
}
