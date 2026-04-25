<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4" style="background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 55%, #f7fbf4 100%);">
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
                        <a href="<?= esc($evidence['url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Lihat Bukti
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-primary card-outline shadow-sm h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">Ringkasan Kegiatan</h3>
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
            <div class="card-header">
                <h3 class="card-title mb-0">Pelaksanaan dan Pendanaan</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($detailItems as $item): ?>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100 bg-light-subtle">
                                <div class="small text-muted mb-1"><?= esc($item['label']) ?></div>
                                <div class="fw-semibold"><?= esc($item['value']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card card-primary card-outline shadow-sm h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">Resume Kegiatan</h3>
            </div>
            <div class="card-body">
                <div class="lh-lg text-body-emphasis"><?= $resumeHtml ?></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-primary card-outline shadow-sm h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">Bukti Dukung</h3>
            </div>
            <div class="card-body d-flex flex-column gap-3">
                <div class="rounded-3 border bg-light-subtle p-3">
                    <div class="small text-muted mb-2">Tautan Dokumen</div>
                    <div class="fw-semibold text-break"><?= esc($evidence['label']) ?></div>
                </div>

                <a href="<?= esc($evidence['url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary w-100">
                    <i class="bi bi-link-45deg me-1"></i>Buka Link Bukti Dukung
                </a>

                <button class="btn btn-outline-danger w-100 btn-delete" data-href="<?= esc($actions['delete_url']) ?>">
                    <i class="bi bi-trash me-1"></i>Hapus Kegiatan
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
                'Kegiatan mandiri ini',
                'Data yang dihapus tidak dapat dikembalikan.'
            );
        });
    })();
</script>
<?= $this->endSection() ?>