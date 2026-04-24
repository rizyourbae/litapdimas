<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserProfiles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'uuid'               => ['type' => 'CHAR', 'constraint' => 36, 'null' => true],
            'user_id'            => ['type' => 'INT', 'unsigned' => true, 'unique' => true],
            'foto'               => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'gelar_depan'        => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'gelar_belakang'     => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'jenis_kelamin'      => ['type' => 'ENUM("L","P")', 'null' => true],
            'tempat_lahir'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_lahir'      => ['type' => 'DATE', 'null' => true],
            'alamat'             => ['type' => 'TEXT', 'null' => true],
            'no_hp'              => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nik'                => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'nidn'               => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'nip'                => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'profesi_id'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'bidang_ilmu_id'     => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'fakultas_id'        => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'program_studi_id'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'jabatan_fungsional_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'id_sinta'           => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'         => ['type' => 'DATETIME', 'null' => true],
            'created_by'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'updated_by'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('uuid');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        // Foreign keys ke master opsional, tidak perlu ON DELETE CASCADE
        $this->forge->addForeignKey('profesi_id', 'master_profesi', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('bidang_ilmu_id', 'master_bidang_ilmu', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('fakultas_id', 'master_fakultas', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('program_studi_id', 'master_program_studi', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('jabatan_fungsional_id', 'master_jabatan_fungsional', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('user_profiles', true);
    }

    public function down()
    {
        $this->forge->dropTable('user_profiles', true);
    }
}
