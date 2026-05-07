<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Master\ProfesiModel;
use App\Models\Master\BidangIlmuModel;
use App\Models\Master\JabatanFungsionalModel;
use App\Models\Master\FakultasModel;
use App\Models\Master\UnitKerjaModel;
use App\Models\Master\ProgramStudiModel;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan validasi agar insert lancar
        $profesiModel = new ProfesiModel();
        $profesiModel->skipValidation(true);
        $bidangIlmuModel = new BidangIlmuModel();
        $bidangIlmuModel->skipValidation(true);
        $jabatanModel = new JabatanFungsionalModel();
        $jabatanModel->skipValidation(true);
        $fakultasModel = new FakultasModel();
        $fakultasModel->skipValidation(true);
        $unitKerjaModel = new UnitKerjaModel();
        $unitKerjaModel->skipValidation(true);
        $prodiModel = new ProgramStudiModel();
        $prodiModel->skipValidation(true);

        // --- Profesi ---
        $profesi = ['Dosen', 'Pranata Komputer', 'Pengembang TP', 'Pranata Keuangan', 'Perancang UU', 'Guru'];
        foreach ($profesi as $p) {
            $profesiModel->insert(['nama' => $p]);
        }

        // --- Bidang Ilmu ---
        $bidangIlmu = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Manajemen',
            'Akuntansi',
            'Ilmu Hukum',
            'Pendidikan Agama Islam',
            'Ekonomi Syariah',
            'Studi Al-Quran dan Tafsir',
            'Studi Islam/Dirasat Islamiyah/Islamic Studies',
            'Ekonomi dan Bisnis Islam',
        ];
        foreach ($bidangIlmu as $b) {
            $bidangIlmuModel->insert(['nama' => $b]);
        }

        // --- Jabatan Fungsional ---
        $jabatan = [
            'Asisten Ahli',
            'Lektor',
            'Lektor Kepala',
            'Guru Besar',
            'Pranata Komputer Ahli Pertama',
            'Pranata Komputer Ahli Muda',
        ];
        foreach ($jabatan as $j) {
            $jabatanModel->insert(['nama' => $j]);
        }

        // --- Fakultas ---
        $fakultas = [
            'Fakultas Teknik',
            'Fakultas Ekonomi dan Bisnis',
            'Fakultas Ilmu Komputer',
            'Fakultas Hukum',
            'Fakultas Ushuluddin dan Studi Agama',
        ];
        foreach ($fakultas as $f) {
            $fakultasModel->insert(['nama' => $f]);
        }

        // --- Unit Kerja (dengan hierarki) ---
        // LPPM sebagai induk
        $lpmmId = $unitKerjaModel->insert(['nama' => 'LPPM'], true);
        $unitKerjaModel->insert(['nama' => 'Lembaga Penelitian dan Pengabdian', 'parent_id' => $lpmmId]);
        $unitKerjaModel->insert(['nama' => 'Lembaga Penelitian', 'parent_id' => $lpmmId]);
        $unitKerjaModel->insert(['nama' => 'Lembaga Pengabdian', 'parent_id' => $lpmmId]);

        // Unit kerja lainnya
        $unitKerjaModel->insert(['nama' => 'Perpustakaan']);
        $unitKerjaModel->insert(['nama' => 'Biro Akademik dan Kemahasiswaan']);
        $unitKerjaModel->insert(['nama' => 'Biro Keuangan']);

        // --- Program Studi (relasi ke fakultas) ---
        // Ambil ID fakultas yang baru diinsert (asumsikan urutan sesuai array di atas)
        $fakultasIds = [];
        foreach ($fakultasModel->findAll() as $f) {
            $fakultasIds[$f['nama']] = $f['id'];
        }

        $prodiList = [
            ['nama' => 'Teknik Informatika', 'fakultas_id' => $fakultasIds['Fakultas Teknik']],
            ['nama' => 'Sistem Informasi', 'fakultas_id' => $fakultasIds['Fakultas Ilmu Komputer']],
            ['nama' => 'Manajemen', 'fakultas_id' => $fakultasIds['Fakultas Ekonomi dan Bisnis']],
            ['nama' => 'Akuntansi', 'fakultas_id' => $fakultasIds['Fakultas Ekonomi dan Bisnis']],
            ['nama' => 'Ilmu Hukum', 'fakultas_id' => $fakultasIds['Fakultas Hukum']],
            ['nama' => 'Studi Agama-Agama', 'fakultas_id' => $fakultasIds['Fakultas Ushuluddin dan Studi Agama']],
        ];

        foreach ($prodiList as $prodi) {
            $prodiModel->insert($prodi);
        }

        // --- Klaster Bantuan ---
        $klasterBantuan = [
            ['nama' => 'BOPTN', 'is_active' => 1],
            ['nama' => 'Dana Hibah', 'is_active' => 1],
            ['nama' => 'Dana Klastering', 'is_active' => 1],
        ];
        foreach ($klasterBantuan as $k) {
            // Check if already exists
            if (!$this->db->table('klaster_bantuan')->where('nama', $k['nama'])->countAllResults() > 0) {
                $k['uuid'] = bin2hex(random_bytes(16));
                $this->db->table('klaster_bantuan')->insert($k);
            }
        }

        // --- Tema Penelitian ---
        $temaPenelitian = [
            ['nama' => 'Agama dan Keagamaan', 'is_active' => 1],
        ];
        foreach ($temaPenelitian as $t) {
            // Check if already exists
            if (!$this->db->table('tema_penelitian')->where('nama', $t['nama'])->countAllResults() > 0) {
                $t['uuid'] = bin2hex(random_bytes(16));
                $this->db->table('tema_penelitian')->insert($t);
            }
        }
    }
}
