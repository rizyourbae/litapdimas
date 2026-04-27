<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProposalMasterSeeder extends Seeder
{
    public function run()
    {
        // Truncate existing data
        $this->db->table('proposal_pengelola_bantuan')->truncate();
        $this->db->table('proposal_jenis_penelitian')->truncate();
        $this->db->table('proposal_kontribusi_prodi')->truncate();
        $this->db->table('proposal_bidang_ilmu')->truncate();
        $this->db->table('proposal_klaster_bantuan')->truncate();
        $this->db->table('proposal_tema_penelitian')->truncate();
        // Seed proposal_pengelola_bantuan
        $pengelolaBantuan = [
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440001',
                'nama' => 'Ditjen Dikti',
                'keterangan' => 'Direktorat Jenderal Pendidikan Tinggi',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440002',
                'nama' => 'LPPM Universitas',
                'keterangan' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($pengelolaBantuan as $data) {
            $this->db->table('proposal_pengelola_bantuan')->insert($data);
        }

        // Seed proposal_jenis_penelitian
        $jenisPenelitian = [
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440011',
                'nama' => 'Penelitian Dasar',
                'keterangan' => 'Penelitian yang dilakukan tanpa tujuan aplikasi langsung',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440012',
                'nama' => 'Penelitian Terapan',
                'keterangan' => 'Penelitian dengan hasil yang dapat diterapkan',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($jenisPenelitian as $data) {
            $this->db->table('proposal_jenis_penelitian')->insert($data);
        }

        // Seed proposal_kontribusi_prodi
        $kontribusiProdi = [
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440021',
                'nama' => 'Berkontribusi pada Program Studi',
                'keterangan' => 'Hasil penelitian berkontribusi pada pengembangan program studi',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440022',
                'nama' => 'Tidak Berkontribusi pada Program Studi',
                'keterangan' => 'Hasil penelitian tidak berkontribusi pada program studi',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($kontribusiProdi as $data) {
            $this->db->table('proposal_kontribusi_prodi')->insert($data);
        }

        // Seed proposal_bidang_ilmu
        $bidangIlmu = [
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440031',
                'nama' => 'Teknologi Informasi',
                'keterangan' => 'Bidang ilmu yang fokus pada teknologi informasi',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440032',
                'nama' => 'Sains',
                'keterangan' => 'Bidang ilmu sains alam',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($bidangIlmu as $data) {
            $this->db->table('proposal_bidang_ilmu')->insert($data);
        }

        // Seed proposal_klaster_bantuan
        $klasterBantuan = [
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440041',
                'nama' => 'Penelitian Unggulan',
                'keterangan' => 'Klaster penelitian unggulan universitas',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440042',
                'nama' => 'Penelitian Reguler',
                'keterangan' => 'Klaster penelitian reguler',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($klasterBantuan as $data) {
            $this->db->table('proposal_klaster_bantuan')->insert($data);
        }

        // Seed proposal_tema_penelitian
        $temaPenelitian = [
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440051',
                'nama' => 'Transformasi Digital',
                'keterangan' => 'Tema penelitian seputar transformasi digital',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655440052',
                'nama' => 'Sustainability',
                'keterangan' => 'Tema penelitian seputar keberlanjutan',
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($temaPenelitian as $data) {
            $this->db->table('proposal_tema_penelitian')->insert($data);
        }

        echo "Master data seeded successfully!\n";
    }
}
