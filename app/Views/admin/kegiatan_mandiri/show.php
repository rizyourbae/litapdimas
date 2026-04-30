<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
/** @var array<string,mixed> $hero */
/** @var array<string,string> $actions */
/** @var array<string,mixed> $evidence */
/** @var array<int,array<string,mixed>> $summaryItems */
/** @var array<int,array<string,mixed>> $detailItems */
/** @var string $resumeHtml */
?>

<div class="row g-3 admin-page">
    <?php
    $evidenceHref = trim((string) ($evidence['url'] ?? ''));
    if ($evidenceHref !== '' && !preg_match('~^[a-z][a-z0-9+.-]*:~i', $evidenceHref)) {
        $evidenceHref = 'https://' . ltrim($evidenceHref, '/');
    }
    ?>
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Detail Kegiatan</span>
                            <span class="badge <?= esc((string) ($hero['jenis_badge_class'] ?? '')) ?> px-3 py-2"><?= esc((string) ($hero['jenis_label'] ?? '')) ?></span>
                            <span class="badge <?= esc((string) ($hero['klaster_badge_class'] ?? '')) ?> px-3 py-2"><?= esc((string) ($hero['klaster_label'] ?? '')) ?></span>
                            <span class="badge text-bg-light border px-3 py-2">Tahun <?= esc((string) ($hero['tahun'] ?? '')) ?></span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc((string) ($hero['title'] ?? '')) ?></h2>
                        <p class="admin-hero__subtitle mb-0">
                            <i class="bi bi-person-badge me-1"></i><?= esc((string) ($hero['subtitle'] ?? '')) ?>
                        </p>
                    </div>

                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= esc((string) ($actions['back_url'] ?? '')) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <a href="<?= esc((string) ($actions['edit_url'] ?? '')) ?>" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i>Edit
                        </a>
                        <a href="<?= esc((string) $evidenceHref, 'attr') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Lihat Bukti
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card admin-form-sidecard h-100">
            <div class="card-body">
                <div class="admin-form-sidecard__icon mb-3"><i class="bi bi-clipboard2-check"></i></div>
                <h3 class="h5 mb-2">Ringkasan Kegiatan</h3>
                <p class="text-muted mb-3">Baca konteks inti kegiatan di sisi kiri sebelum masuk ke resume, pendanaan, atau proses koreksi data.</p>
                <div class="list-group list-group-flush admin-summary-list">
                    <?php foreach ((array) $summaryItems as $item): $item = (array) $item; ?>
                        <div class="list-group-item px-0 py-3">
                            <div class="small text-muted mb-1"><?= esc((string) ($item['label'] ?? '')) ?></div>
                            <div class="fw-semibold"><?= esc((string) ($item['value'] ?? '')) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card card-primary card-outline admin-table-card h-100">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title mb-0">Pelaksanaan dan Pendanaan</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ((array) $detailItems as $item): $item = (array) $item; ?>
                        <div class="col-md-6">
                            <div class="admin-detail-item">
                                <div class="admin-detail-item__label"><?= esc((string) ($item['label'] ?? '')) ?></div>
                                <div class="admin-detail-item__value"><?= esc((string) ($item['value'] ?? '')) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card card-primary card-outline admin-table-card h-100">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title mb-0">Resume Kegiatan</h3>
            </div>
            <div class="card-body">
                <?php if (trim((string) $resumeHtml) === ''): ?>
                    <div class="admin-empty-state py-4">
                        <i class="bi bi-file-earmark-text"></i>
                        <p class="mb-0 fw-semibold text-body-emphasis">Belum ada resume kegiatan</p>
                        <small class="d-block text-muted">Tambahkan ringkasan kegiatan pada form agar admin dapat melihat inti aktivitas disini.</small>
                    </div>
                <?php else: ?>
                    <div class="admin-detail-item admin-detail-item--resume">
                        <div class="resume-content text-body-emphasis">
                            <?= $resumeHtml ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card admin-panel-card h-100">
            <div class="card-body d-flex flex-column gap-3">
                <div>
                    <div class="small text-uppercase text-muted mb-1">Bukti Dukung</div>
                    <h3 class="h5 mb-2">Dokumen pendukung kegiatan</h3>
                    <p class="text-muted mb-0">Tautan ini menjadi rujukan admin saat memverifikasi kelengkapan dokumen kegiatan.</p>
                </div>

                <div class="admin-detail-item admin-detail-item--link">
                    <div class="admin-detail-item__label">Tautan Dokumen</div>
                    <div class="admin-detail-item__value text-break"><?= esc((string) ($evidence['label'] ?? '')) ?></div>
                </div>

                <a href="<?= esc((string) $evidenceHref, 'attr') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary w-100">
                    <i class="bi bi-link-45deg me-1"></i>Buka Link Bukti Dukung
                </a>

                <button class="btn btn-outline-danger w-100 btn-delete" data-href="<?= esc((string) ($actions['delete_url'] ?? '')) ?>" data-delete-label="Kegiatan mandiri ini" data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
                    <i class="bi bi-trash me-1"></i>Hapus Kegiatan
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>