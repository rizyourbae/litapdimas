<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Detail Publikasi</span>
                            <span class="badge <?= esc($hero['jenis_badge_class']) ?> px-3 py-2"><?= esc($hero['jenis_label']) ?></span>
                            <span class="badge <?= esc($hero['klaster_badge_class']) ?> px-3 py-2"><?= esc($hero['klaster_label']) ?></span>
                            <span class="badge text-bg-light border px-3 py-2">Tahun <?= esc($hero['tahun']) ?></span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($hero['title']) ?></h2>
                        <p class="admin-hero__subtitle mb-0">
                            <i class="bi bi-person-badge me-1"></i><?= esc($hero['subtitle']) ?>
                        </p>
                    </div>

                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= esc($actions['back_url']) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <a href="<?= esc($actions['edit_url']) ?>" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card admin-form-sidecard h-100">
            <div class="card-body">
                <div class="admin-form-sidecard__icon mb-3"><i class="bi bi-journal-check"></i></div>
                <h3 class="h5 mb-2">Ringkasan Publikasi</h3>
                <p class="text-muted mb-3">Gunakan panel ini untuk membaca konteks inti publikasi sebelum melakukan perubahan atau penghapusan data.</p>
                <div class="list-group list-group-flush admin-summary-list">
                    <?php foreach ($summaryItems as $item): ?>
                        <div class="list-group-item px-0 py-3">
                            <div class="small text-muted mb-1"><?= esc($item['label']) ?></div>
                            <div class="fw-semibold"><?= esc($item['value']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card card-primary card-outline admin-table-card h-100">
            <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><?= esc($metadataTitle) ?></h3>
            </div>
            <div class="card-body">
                <?php if (empty($metadataItems)): ?>
                    <div class="admin-empty-state py-4">
                        <i class="bi bi-info-circle"></i>
                        <p class="mb-0 fw-semibold text-body-emphasis">Belum ada detail tambahan untuk publikasi ini.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($metadataItems as $item): ?>
                            <?php if (!empty($item['url'])): ?>
                                <div class="col-12">
                                    <div class="admin-detail-item admin-detail-item--link">
                                        <div class="admin-detail-item__label"><?= esc($item['label']) ?></div>
                                        <?php
                                        $href = trim((string) $item['url']);
                                        if ($href !== '' && !preg_match('~^[a-z][a-z0-9+.-]*:~i', $href)) {
                                            $href = 'https://' . ltrim($href, '/');
                                        }
                                        ?>
                                        <a href="<?= esc($href, 'attr') ?>" target="_blank" rel="noopener noreferrer" class="admin-detail-item__value text-break text-decoration-none"><?= esc($item['value']) ?></a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6">
                                    <div class="admin-detail-item">
                                        <div class="admin-detail-item__label"><?= esc($item['label']) ?></div>
                                        <div class="admin-detail-item__value"><?= esc($item['value']) ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card admin-panel-card">
            <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <div class="small text-uppercase text-muted mb-1">Aksi Admin</div>
                    <h3 class="h5 mb-2">Kelola data publikasi dari halaman detail</h3>
                    <p class="text-muted mb-0">Gunakan edit untuk memperbarui metadata atau hapus jika entri ini memang tidak valid dan tidak perlu dipertahankan.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= esc($actions['edit_url']) ?>" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-1"></i>Edit Publikasi
                    </a>
                    <button class="btn btn-outline-danger btn-delete" data-href="<?= esc($actions['delete_url']) ?>" data-delete-label="Publikasi ini" data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
                        <i class="bi bi-trash me-1"></i>Hapus Publikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>