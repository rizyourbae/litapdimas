<?php

namespace App\Services\Admin;

use App\Models\Auth\UserModel;
use App\Models\Proposal\ProposalPengajuan;
use App\Models\Publikasi\PublikasiModel;
use App\Models\Master\AnnouncementModel;

class DashboardService
{
    protected $userModel;
    protected $proposalModel;
    protected $publikasiModel;
    protected $announcementModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->proposalModel = new ProposalPengajuan();
        $this->publikasiModel = new PublikasiModel();
        $this->announcementModel = new AnnouncementModel();
    }

    /**
     * Mendapatkan semua statistik dashboard
     */
    public function getStats(): array
    {
        return [
            'total_users'       => $this->userModel->countAllResults(),
            'active_proposals'  => $this->proposalModel->whereIn('status', ['submitted', 'reviewing', 'revision'])->countAllResults(),
            'new_publications'  => $this->publikasiModel->countAllResults(), // Sementara count all, bisa disesuaikan status verifikasi nanti
            'active_announcements' => $this->announcementModel->where('is_active', true)->countAllResults(),
        ];
    }
}
