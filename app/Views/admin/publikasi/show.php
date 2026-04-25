<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4" style="background: linear-gradient(135deg, #f7fbff 0%, #eef4ff 60%, #f8fcf6 100%);">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge <?= esc($hero['jenis_badge_class']) ?> px-3 py-2"><?= esc($hero['jenis_label']) ?></span>
                            <span class="badge <?= esc($hero['klaster_badge_class']) ?> px-3 py-2"><?= esc($hero['klaster_label']) ?></span>
                            <span class="badge text-bg-light border px-3 py-2">Tahun <?= esc($hero['tahun']) ?></span>
                        </div>
                        <h2 class="h3 mb-2"><?= esc($hero['title']) ?></h2>
                        <p class="text-muted mb-0">
                            <i class="bi bi-person-badge me-1"></i><?= esc($hero['subtitle']) ?>
                        </p>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
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

    <div class="col-lg-5">
        <div class="card card-primary card-outline shadow-sm h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">Ringkasan Publikasi</h3>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($summaryItems as $item): ?>
                        <div class="list-group-item px-4 py-3">
                            <div class="small text-muted mb-1"><?= esc($item['label']) ?></div>
                            <div class="fw-semibold"><?= esc($item['value']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-primary card-outline shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><?= esc($metadataTitle) ?></h3>
            </div>
            <div class="card-body">
                <?php if (empty($metadataItems)): ?>
                    <p class="text-muted mb-0">Belum ada detail tambahan untuk publikasi ini.</p>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($metadataItems as $item): ?>
                            <?php if (!empty($item['url'])): ?>
                                <div class="col-12">
                                    <div class="border rounded-3 p-3 bg-light-subtle">
                                        <div class="small text-muted mb-1"><?= esc($item['label']) ?></div>
                                        <a href="<?= esc($item['url']) ?>" target="_blank" rel="noopener noreferrer" class="fw-semibold text-break text-decoration-none">
                                            <?= esc($item['value']) ?>
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                        <div class="small text-muted mb-1"><?= esc($item['label']) ?></div>
                                        <div class="fw-semibold"><?= esc($item['value']) ?></div>
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
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0">Aksi Cepat</h3>
            </div>
            <div class="card-body d-flex flex-wrap gap-2">
                <a href="<?= esc($actions['edit_url']) ?>" class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i>Edit Publikasi
                </a>
                <button class="btn btn-outline-danger btn-delete" data-href="<?= esc($actions['delete_url']) ?>">
                    <i class="bi bi-trash me-1"></i>Hapus Publikasi
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function() {
        'use strict';

        document.addEventListener('click', function(event) {
            const deleteBtn = event.target.closest('.btn-delete');
            if (!deleteBtn) {
                return;
            }

            event.preventDefault();
            SwalDelete(
                deleteBtn.getAttribute('data-href'),
                'Publikasi ini',
                'Data yang dihapus tidak dapat dikembalikan.'
            );
        });
    })();
</script>
<?= $this->endSection() ?>