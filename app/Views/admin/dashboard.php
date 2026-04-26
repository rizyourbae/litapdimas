<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-4">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Panel Operasional</span>
                            <span class="badge text-bg-primary px-3 py-2">Admin Workspace</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2">Dashboard Admin</h2>
                        <p class="admin-hero__subtitle mb-0">Kelola data inti Litapdimas dengan pola tampilan yang seragam, cepat dipindai, dan lebih cocok untuk pekerjaan administratif harian.</p>
                    </div>
                    <div class="admin-hero__stats">
                        <div class="admin-stat-card p-3">
                            <div class="small text-muted text-uppercase">Mode Kerja</div>
                            <div class="fw-semibold">Monitoring dan pengelolaan data</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card admin-panel-card h-100">
            <div class="card-body">
                <div class="admin-panel-icon mb-3"><i class="bi bi-speedometer2"></i></div>
                <h3 class="h5 mb-2">Ruang Kontrol Admin</h3>
                <p class="text-muted mb-0">Masuk ke master data, user, publikasi, dan kegiatan lewat antarmuka yang sekarang disejajarkan dengan area dosen tetapi tetap terasa lebih operasional.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card admin-panel-card h-100">
            <div class="card-body">
                <div class="admin-panel-icon mb-3"><i class="bi bi-layout-text-window-reverse"></i></div>
                <h3 class="h5 mb-2">Tampilan Lebih Konsisten</h3>
                <p class="text-muted mb-0">Hero, kartu tabel, dan form admin kini mengikuti garis desain yang sama dengan dosen agar perpindahan konteks terasa mulus.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card admin-panel-card h-100">
            <div class="card-body">
                <div class="admin-panel-icon mb-3"><i class="bi bi-code-slash"></i></div>
                <h3 class="h5 mb-2">View Lebih Bersih</h3>
                <p class="text-muted mb-0">Logic presentasi secara bertahap dipindah ke controller dan asset bersama agar view lebih mudah dirawat.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>