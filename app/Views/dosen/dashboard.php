<?php
/** @var array<string,mixed> $user */

$this->extend('layouts/main');

$this->section('content');
?>

<?= view('components/ui-hero', [
    'type' => 'dosen',
    'title' => 'Selamat datang, ' . esc((string) ($user['nama_lengkap'] ?? $user['username'] ?? 'Dosen')) . '!',
    'subtitle' => 'Gunakan halaman ini untuk memantau aktivitas, melengkapi profil, dan melanjutkan pengisian data akademik Anda.',
    'badges' => [
        ['label' => 'Panel Dosen', 'class' => 'text-bg-light border'],
        ['label' => 'Litapdimas', 'class' => 'text-bg-primary']
    ],
    'actions' => [
        [
            'label' => 'Kelola Publikasi',
            'url' => site_url('dosen/publikasi'),
            'icon' => 'bi bi-journal-richtext-fill',
            'class' => 'btn btn-primary rounded-pill px-4 shadow-sm'
        ],
        [
            'label' => 'Riwayat Pendidikan',
            'url' => site_url('dosen/riwayat-pendidikan'),
            'icon' => 'bi bi-mortarboard-fill',
            'class' => 'btn btn-outline-secondary rounded-pill px-4 shadow-sm'
        ]
    ]
]) ?>

<div class="row g-4 mb-4">
    <div class="col-md-4 animate-fade-up delay-1">
        <?= view('components/ui-stat-card', [
            'label' => 'Proposal Diajukan',
            'value' => '0',
            'desc' => 'Total pengajuan tahun ini',
            'icon' => 'bi bi-file-earmark-text',
            'colorClass' => 'text-primary'
        ]) ?>
    </div>
    <div class="col-md-4 animate-fade-up delay-2">
        <?= view('components/ui-stat-card', [
            'label' => 'Sedang Direview',
            'value' => '0',
            'desc' => 'Menunggu penilaian reviewer',
            'icon' => 'bi bi-hourglass-split',
            'colorClass' => 'text-warning'
        ]) ?>
    </div>
    <div class="col-md-4 animate-fade-up delay-3">
        <?= view('components/ui-stat-card', [
            'label' => 'Proposal Diterima',
            'value' => '0',
            'desc' => 'Siap untuk tahap selanjutnya',
            'icon' => 'bi bi-check-circle',
            'colorClass' => 'text-success'
        ]) ?>
    </div>
</div>

<div class="row g-4 animate-fade-up" style="animation-delay: 0.4s;">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100 rounded-4 overflow-hidden">
            <div class="card-header bg-light border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-lightning-fill text-warning"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Langkah Berikutnya</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="<?= site_url('dosen/profil-sinta') ?>" class="text-decoration-none d-block h-100">
                            <div class="bg-light p-4 h-100 rounded-4 transition-base border border-transparent hover-lift">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle"><i class="bi bi-search fs-4"></i></div>
                                    <div>
                                        <div class="fw-bold text-dark">Sinkronkan profil SINTA</div>
                                        <div class="small text-muted">Pastikan data SINTA Anda selalu terbaru.</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= site_url('dosen/kelengkapan-dokumen') ?>" class="text-decoration-none d-block h-100">
                            <div class="bg-light p-4 h-100 rounded-4 transition-base border border-transparent hover-lift">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle"><i class="bi bi-folder2-open fs-4"></i></div>
                                    <div>
                                        <div class="fw-bold text-dark">Lengkapi Dokumen</div>
                                        <div class="small text-muted">Upload dokumen yang masih kosong.</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100 rounded-4 overflow-hidden">
            <div class="card-header bg-light border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-grid-fill text-primary"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Akses Cepat</h6>
                </div>
            </div>
            <div class="card-body p-4 d-grid gap-3">
                <a href="<?= site_url('dosen/publikasi/create') ?>" class="text-decoration-none action-card-premium">
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-primary-soft text-primary hover-lift border border-primary border-opacity-10">
                        <div class="bg-primary text-white rounded-3 p-2 shadow-sm"><i class="bi bi-journal-plus fs-5"></i></div>
                        <div>
                            <div class="fw-bold small">Publikasi Baru</div>
                            <div class="text-muted" style="font-size: 0.7rem;">Tambah karya ilmiah</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto opacity-50"></i>
                    </div>
                </a>
                <a href="<?= site_url('dosen/kegiatan-mandiri/create') ?>" class="text-decoration-none action-card-premium">
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-success-soft text-success hover-lift border border-success border-opacity-10">
                        <div class="bg-success text-white rounded-3 p-2 shadow-sm"><i class="bi bi-clipboard-plus fs-5"></i></div>
                        <div>
                            <div class="fw-bold small">Kegiatan Mandiri</div>
                            <div class="text-muted" style="font-size: 0.7rem;">Tambah catatan kegiatan</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto opacity-50"></i>
                    </div>
                </a>
                <a href="<?= site_url('dosen/riwayat-pendidikan/create') ?>" class="text-decoration-none action-card-premium">
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 bg-info-soft text-info hover-lift border border-info border-opacity-10">
                        <div class="bg-info text-white rounded-3 p-2 shadow-sm"><i class="bi bi-mortarboard-fill fs-5"></i></div>
                        <div>
                            <div class="fw-bold small">Riwayat Pendidikan</div>
                            <div class="text-muted" style="font-size: 0.7rem;">Update data pendidikan</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto opacity-50"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>


<?php $this->endSection(); ?>