<?php

namespace App\Services\CMS;

use App\Models\Master\LandingSettingModel;
use App\Models\Master\TemaRisetModel;
use App\Models\Proposal\ProposalPengajuan;
use App\Models\Auth\UserModel;
use App\Models\Publikasi\PublikasiModel;

class LandingPageService
{
    private LandingSettingModel $settingModel;
    private TemaRisetModel $temaModel;

    public function __construct()
    {
        $this->settingModel = new LandingSettingModel();
        $this->temaModel = new TemaRisetModel();
    }

    /**
     * Payload lengkap untuk halaman Landing Page (Public)
     */
    public function getPublicLandingPayload(): array
    {
        return [
            'hero'       => $this->settingModel->getGroup('hero'),
            'contact'    => $this->settingModel->getGroup('contact'),
            'stats'      => $this->getLiveStatistics(),
            'temaRiset'  => $this->temaModel->getActiveThemes(),
        ];
    }

    /**
     * Hitung statistik riil dari database.
     */
    private function getLiveStatistics(): array
    {
        $proposalModel = new ProposalPengajuan();
        $userModel = new UserModel();
        $publikasiModel = new PublikasiModel();

        // Ambil data statistik riil
        $counts = [
            'proposal'   => $proposalModel->countAllResults(),
            'peneliti'   => $userModel->where('aktif', 1)->countAllResults(),
            'publikasi'  => $publikasiModel->countAllResults(),
        ];

        // Ambil "base" atau tambahan angka dari settings jika ada (untuk marketing/historical data)
        $baseProposal = (int) $this->settingModel->getVal('stats_base_proposal', 0);
        $basePeneliti = (int) $this->settingModel->getVal('stats_base_peneliti', 0);
        $basePublikasi = (int) $this->settingModel->getVal('stats_base_publikasi', 0);

        return [
            'total_proposal'  => $counts['proposal'] + $baseProposal,
            'total_peneliti'  => $counts['peneliti'] + $basePeneliti,
            'total_publikasi' => $counts['publikasi'] + $basePublikasi,
        ];
    }

    /**
     * Payload untuk halaman Admin Management
     */
    public function getAdminSettingsPayload(): array
    {
        return [
            'heroSettings'    => $this->settingModel->getGroup('hero'),
            'contactSettings' => $this->settingModel->getGroup('contact'),
            'statsSettings'   => $this->settingModel->getGroup('stats'),
            'themes'          => $this->temaModel->orderBy('sort_order', 'ASC')->findAll(),
        ];
    }

    /**
     * Update Pengaturan Landing
     */
    public function updateSettings(array $input): void
    {
        foreach ($input as $key => $value) {
            $group = 'general';
            if (str_starts_with($key, 'hero_')) $group = 'hero';
            if (str_starts_with($key, 'contact_')) $group = 'contact';
            if (str_starts_with($key, 'stats_')) $group = 'stats';

            $this->settingModel->setVal($key, $value, $group);
        }
    }
    /**
     * Ambil detail tema riset
     */
    public function getThemeByUuid(string $uuid): ?array
    {
        return $this->temaModel->where('uuid', $uuid)->first();
    }

    /**
     * Simpan Tema Riset Baru
     */
    public function storeTheme(array $input): void
    {
        // Handle checkbox is_active
        $input['is_active'] = isset($input['is_active']) ? 1 : 0;

        if (!$this->temaModel->insert($input)) {
            throw new \Exception('Gagal menyimpan tema riset: ' . implode(', ', $this->temaModel->errors()));
        }
    }

    /**
     * Update Tema Riset
     */
    public function updateTheme(string $uuid, array $input): void
    {
        $existing = $this->getThemeByUuid($uuid);
        if (!$existing) throw new \Exception('Tema riset tidak ditemukan.');

        // Handle checkbox is_active (jika tidak dicentang, nilainya tidak ada di POST)
        $input['is_active'] = isset($input['is_active']) ? 1 : 0;

        if (!$this->temaModel->update($existing['id'], $input)) {
            throw new \Exception('Gagal memperbarui tema riset: ' . implode(', ', $this->temaModel->errors()));
        }
    }

    /**
     * Hapus Tema Riset
     */
    public function deleteTheme(string $uuid): void
    {
        $existing = $this->getThemeByUuid($uuid);
        if (!$existing) throw new \Exception('Tema riset tidak ditemukan.');

        $this->temaModel->delete($existing['id']);
    }
}
