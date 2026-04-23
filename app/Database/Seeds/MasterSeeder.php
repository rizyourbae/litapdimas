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
        // Profesi
        $profesiModel = new ProfesiModel();
        $profesiModel->skipValidation(true);
        $profesi = ['Dosen', 'Pranata Komputer', 'Pengembang TP', 'Pranata Keuangan'];
        foreach ($profesi as $p) {
            $profesiModel->insert(['nama' => $p]);
        }

        // Bidang Ilmu
        $bidangModel = new BidangIlmuModel();
        $bidangModel->skipValidation(true);
        $bidang = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
        foreach ($bidang as $b) {
            $bidangModel->insert(['nama' => $b]);
        }

        // Jabatan Fungsional
        $jabatanModel = new JabatanFungsionalModel();
        $jabatanModel->skipValidation(true);
        $jabatan = ['Lektor', 'Lektor Kepala', 'Guru Besar', 'Asisten Ahli'];
        foreach ($jabatan as $j) {
            $jabatanModel->insert(['nama' => $j]);
        }

        // Fakultas
        $fakultasModel = new FakultasModel();
        $fakultasModel->skipValidation(true);
        $fakultas = ['Fakultas Teknik', 'Fakultas Ekonomi', 'Fakultas Ilmu Komputer'];
        foreach ($fakultas as $f) {
            $fakultasModel->insert(['nama' => $f]);
        }

        // Unit Kerja
        $unitKerjaModel = new UnitKerjaModel();
        $unitKerjaModel->skipValidation(true);
        $lpmmId = $unitKerjaModel->insert(['nama' => 'LPPM'], true);
        $unitKerjaModel->insert(['nama' => 'Lembaga Penelitian', 'parent_id' => $lpmmId]);
        $unitKerjaModel->insert(['nama' => 'Lembaga Pengabdian', 'parent_id' => $lpmmId]);
        $unitKerjaModel->insert(['nama' => 'Perpustakaan']);

        // Program Studi (ambil fakultas_id = 1 untuk contoh)
        $prodiModel = new ProgramStudiModel();
        $prodiModel->skipValidation(true);
        $prodiModel->insert(['nama' => 'Teknik Informatika', 'fakultas_id' => 1]);
        $prodiModel->insert(['nama' => 'Sistem Informasi', 'fakultas_id' => 1]);
        // ... tambahkan lainnya
    }
}
