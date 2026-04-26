<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php $user = service('auth')->user(); ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card dosen-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Panel Dosen</span>
                            <span class="badge text-bg-primary px-3 py-2">Litapdimas</span>
                        </div>
                        <h2 class="h3 dosen-hero__title mb-2">Selamat datang, <?= esc($user['nama_lengkap'] ?? $user['username']) ?>!</h2>
                        <p class="dosen-hero__subtitle mb-0">
                            Gunakan halaman ini untuk memantau aktivitas, melengkapi profil, dan melanjutkan pengisian data akademik Anda.
                        </p>
                    </div>

                    <div class="dosen-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= site_url('dosen/publikasi') ?>" class="btn btn-primary">
                            <i class="bi bi-journal-richtext me-1"></i>Kelola Publikasi
                        </a>
                        <a href="<?= site_url('dosen/riwayat-pendidikan') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-mortarboard me-1"></i>Riwayat Pendidikan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card dosen-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-file-earmark-text text-primary fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small">Proposal Diajukan</div>
                    <div class="fs-4 fw-bold">0</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card dosen-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                    <i class="bi bi-hourglass-split text-warning fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small">Sedang Direview</div>
                    <div class="fs-4 fw-bold">0</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card dosen-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="bi bi-check-circle text-success fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small">Proposal Diterima</div>
                    <div class="fs-4 fw-bold">0</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card dosen-card h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">Langkah Berikutnya</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="<?= site_url('dosen/profil-sinta') ?>" class="text-decoration-none text-body d-block h-100">
                            <div class="dosen-soft-surface p-3 h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge text-bg-info p-2"><i class="bi bi-search"></i></span>
                                    <div>
                                        <div class="fw-semibold">Sinkronkan profil SINTA</div>
                                        <div class="dosen-section-note">Pastikan data SINTA Anda selalu terbaru.</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= site_url('dosen/kelengkapan-dokumen') ?>" class="text-decoration-none text-body d-block h-100">
                            <div class="dosen-soft-surface p-3 h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge text-bg-warning p-2"><i class="bi bi-folder2-open"></i></span>
                                    <div>
                                        <div class="fw-semibold">Periksa kelengkapan dokumen</div>
                                        <div class="dosen-section-note">Upload dokumen yang masih kosong atau perlu diperbarui.</div>
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
        <div class="card dosen-card h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">Akses Cepat</h3>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="<?= site_url('dosen/publikasi/create') ?>" class="btn btn-outline-primary">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Publikasi
                </a>
                <a href="<?= site_url('dosen/kegiatan-mandiri/create') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-clipboard-plus me-1"></i>Tambah Kegiatan
                </a>
                <a href="<?= site_url('dosen/riwayat-pendidikan/create') ?>" class="btn btn-outline-success">
                    <i class="bi bi-mortarboard-fill me-1"></i>Tambah Riwayat
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>