<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Master\LandingSettingModel;
use App\Models\Master\TemaRisetModel;

class LandingPageSeeder extends Seeder
{
    public function run()
    {
        $settingModel = new LandingSettingModel();
        $temaModel = new TemaRisetModel();

        // 1. Seed Settings
        $settings = [
            ['key' => 'hero_title', 'value' => 'Sistem Informasi Penelitian & Pengabdian Masyarakat', 'group' => 'hero'],
            ['key' => 'hero_subtitle', 'value' => 'Platform terintegrasi untuk pengelolaan, review, dan publikasi hasil penelitian akademik di lingkungan UINSI Samarinda.', 'group' => 'hero'],
            ['key' => 'hero_cta_primary', 'value' => 'Mulai Pengajuan', 'group' => 'hero'],
            ['key' => 'hero_cta_outline', 'value' => 'Panduan Juknis', 'group' => 'hero'],
            
            ['key' => 'stats_base_proposal', 'value' => '1200', 'group' => 'stats'],
            ['key' => 'stats_base_peneliti', 'value' => '400', 'group' => 'stats'],
            ['key' => 'stats_base_publikasi', 'value' => '80', 'group' => 'stats'],
        ];

        foreach ($settings as $s) {
            $settingModel->setVal($s['key'], $s['value'], $s['group']);
        }

        // 2. Seed Tema Riset
        $temas = [
            ['nama' => 'Ekoteologi Pangan', 'icon' => 'bi bi-droplets', 'sort_order' => 1],
            ['nama' => 'Moderasi Beragama', 'icon' => 'bi bi-heart-pulse', 'sort_order' => 2],
            ['nama' => 'Digital Islam', 'icon' => 'bi bi-cpu', 'sort_order' => 3],
            ['nama' => 'Islam & Sains', 'icon' => 'bi bi-infinity', 'sort_order' => 4],
            ['nama' => 'Manuskrip Turats', 'icon' => 'bi bi-book', 'sort_order' => 5],
            ['nama' => 'Sekolah Rakyat', 'icon' => 'bi bi-people', 'sort_order' => 6],
            ['nama' => 'Kajian Global', 'icon' => 'bi bi-globe', 'sort_order' => 7],
            ['nama' => 'Ketahanan Sosial', 'icon' => 'bi bi-shield-check', 'sort_order' => 8],
        ];

        foreach ($temas as $t) {
            $temaModel->insert($t);
        }
    }
}
