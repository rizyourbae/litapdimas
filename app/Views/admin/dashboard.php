<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => 'Dashboard Administrator',
            'subtitle' => 'Pusat kontrol dan monitoring seluruh operasional sistem Litapdimas.',
            'badges' => [
                ['label' => 'Super Admin', 'class' => 'text-bg-danger shadow-sm'],
                ['label' => 'Sistem Aktif', 'class' => 'text-bg-success shadow-sm']
            ]
        ]) ?>
    </div>

    <div class="col-md-6 col-xl-3 animate-fade-up delay-1">
        <?= view('components/ui-stat-card', [
            'label' => 'Total Pengguna',
            'value' => '1.240',
            'desc' => 'Dosen & Reviewer terdaftar',
            'icon' => 'bi bi-people',
            'colorClass' => 'text-primary'
        ]) ?>
    </div>
    <div class="col-md-6 col-xl-3 animate-fade-up delay-2">
        <?= view('components/ui-stat-card', [
            'label' => 'Proposal Aktif',
            'value' => '42',
            'desc' => 'Dalam proses review',
            'icon' => 'bi bi-file-earmark-text',
            'colorClass' => 'text-warning'
        ]) ?>
    </div>
    <div class="col-md-6 col-xl-3 animate-fade-up delay-3">
        <?= view('components/ui-stat-card', [
            'label' => 'Publikasi Baru',
            'value' => '12',
            'desc' => 'Menunggu verifikasi',
            'icon' => 'bi bi-journal-bookmark',
            'colorClass' => 'text-info'
        ]) ?>
    </div>
    <div class="col-md-6 col-xl-3 animate-fade-up delay-3">
        <?= view('components/ui-stat-card', [
            'label' => 'Server Load',
            'value' => '14%',
            'desc' => 'Kondisi sistem optimal',
            'icon' => 'bi bi-cpu',
            'colorClass' => 'text-success'
        ]) ?>
    </div>

    <div class="col-12 mt-4 animate-fade-up" style="animation-delay: 0.5s;">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-light border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-grid-fill text-primary"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Manajemen Cepat</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <a href="<?= site_url('admin/users') ?>" class="text-decoration-none d-block h-100">
                            <div class="bg-light p-4 h-100 rounded-4 transition-base border border-transparent hover-lift">
                                <div class="admin-panel-icon-sm mb-3 bg-white shadow-sm rounded-3 text-primary fs-4"><i class="bi bi-person-gear"></i></div>
                                <h4 class="h6 fw-bold text-dark">Kelola Pengguna</h4>
                                <p class="small text-muted mb-0">Tambah, edit, dan atur peran pengguna sistem.</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= site_url('admin/master/index') ?>" class="text-decoration-none d-block h-100">
                            <div class="bg-light p-4 h-100 rounded-4 transition-base border border-transparent hover-lift">
                                <div class="admin-panel-icon-sm mb-3 bg-white shadow-sm rounded-3 text-info fs-4"><i class="bi bi-database-gear"></i></div>
                                <h4 class="h6 fw-bold text-dark">Master Referensi</h4>
                                <p class="small text-muted mb-0">Kelola data akademik, klaster, dan unit kerja.</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= site_url('admin/proposal') ?>" class="text-decoration-none d-block h-100">
                            <div class="bg-light p-4 h-100 rounded-4 transition-base border border-transparent hover-lift">
                                <div class="admin-panel-icon-sm mb-3 bg-white shadow-sm rounded-3 text-warning fs-4"><i class="bi bi-files"></i></div>
                                <h4 class="h6 fw-bold text-dark">Monitoring Proposal</h4>
                                <p class="small text-muted mb-0">Pantau status pengajuan penelitian dan pengabdian.</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>