<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/** @var string $title */
/** @var array<string,mixed> $viewState */
/** @var array<string,mixed> $tabs */
?>
<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Master Data</span>
                            <span class="badge text-bg-primary px-3 py-2">Referensi Sistem</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($title) ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="d-none" data-admin-auto-open-tab="tab-<?= esc((string) ($viewState['activeTab'] ?? '')) ?>-link" <?= !empty($viewState['openModal'] ?? '') ? ' data-admin-auto-open-modal="modal-' . esc((string) ($viewState['openModal'] ?? '')) . '"' : '' ?>></div>
        <div class="card card-primary card-outline admin-table-card card-outline-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="referensiTabs" role="tablist">
                    <?php foreach ($tabs as $key => $tab): ?>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link <?= (($viewState['activeTab'] ?? '') === $key) ? 'active' : '' ?>" id="tab-<?= esc((string) $key) ?>-link" data-bs-toggle="tab" href="#tab-<?= esc((string) $key) ?>" role="tab">
                                <i class="<?= esc((string) ($tab['icon'] ?? '')) ?> me-1"></i>
                                <?= esc((string) ($tab['label'] ?? '')) ?>
                                <span class="badge bg-secondary ms-1"><?= esc((string) count($tab['items'])) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="referensiTabsContent">
                    <?php foreach ($tabs as $key => $tab): ?>
                        <?php
                        $dtId = 'dt-' . $key;
                        $skId = 'sk-' . $key;
                        $rwId = 'rw-' . $key;
                        $hasError = (($viewState['openModal'] ?? '') === 'tambah-' . $key);
                        ?>
                        <div class="tab-pane fade <?= (($viewState['activeTab'] ?? '') === $key) ? 'show active' : '' ?>" id="tab-<?= esc((string) $key) ?>" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 text-muted">
                                    <i class="<?= esc((string) ($tab['icon'] ?? '')) ?> me-1"></i>
                                    Daftar <?= esc((string) ($tab['label'] ?? '')) ?>
                                    &mdash; <strong><?= esc((string) count($tab['items'])) ?></strong> data
                                </h6>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-tambah-<?= esc((string) $key) ?>">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah <?= esc((string) ($tab['label'] ?? '')) ?>
                                </button>
                            </div>

                            <?php if (empty($tab['items'])): ?>
                                <div class="dosen-empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p class="mb-1">Belum ada data <?= esc((string) ($tab['label'] ?? '')) ?></p>
                                    <small>Klik tombol tambah untuk membuat data referensi baru.</small>
                                </div>
                            <?php else: ?>
                                <div class="dt-skeleton-wrap">
                                    <div class="dt-skeleton-overlay" id="<?= esc($skId) ?>">
                                        <table class="table table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:60px"></th>
                                                    <th></th>
                                                    <th style="width:110px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ([70, 55, 80, 45, 65] as $width): ?>
                                                    <tr>
                                                        <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                                        <td><span class="skeleton-line" style="width:<?= esc((string) $width) ?>%"></span></td>
                                                        <td class="text-center"><span class="skeleton-btn me-1"></span><span class="skeleton-btn"></span></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="dt-real-wrap" id="<?= esc($rwId) ?>">
                                        <table id="<?= esc($dtId) ?>" class="table table-hover table-bordered align-middle w-100" data-admin-datatable data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,2]}]}' data-skeleton-id="<?= esc($skId) ?>" data-real-wrap-id="<?= esc($rwId) ?>">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:60px" class="text-center">#</th>
                                                    <th><?= esc((string) ($tab['fieldLabel'] ?? '')) ?></th>
                                                    <th style="width:110px" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($tab['items'] as $index => $item): ?>
                                                    <tr>
                                                        <td class="text-center"><?= esc((string) ($index + 1)) ?></td>
                                                        <td>
                                                            <?= esc((string) ($item['nama'] ?? '')) ?>
                                                            <?php if (!empty($item['deleted_at'])): ?>
                                                                <span class="badge bg-secondary ms-1">Nonaktif</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center btn-action-group admin-action-group">
                                                            <?php if (!empty($item['deleted_at'])): ?>
                                                                <a href="#" class="btn btn-success btn-sm btn-admin-restore"
                                                                    data-href="<?= site_url('admin/master/referensi/restore/' . $key . '/' . $item['id']) ?>"
                                                                    data-confirm-title="Pulihkan data ini?"
                                                                    data-confirm-html="Data <strong><?= esc((string) ($item['nama'] ?? '')) ?></strong> akan diaktifkan kembali."
                                                                    data-confirm-button="Ya, pulihkan"
                                                                    title="Pulihkan">
                                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                <button type="button" class="btn btn-warning btn-sm"
                                                                    data-admin-modal-target="#modal-edit"
                                                                    data-admin-form-action="<?= site_url('admin/master/referensi/update/' . $key . '/' . $item['id']) ?>"
                                                                    data-admin-modal-title-text="<i class='bi bi-pencil-square me-2'></i>Edit <?= esc((string) ($tab['label'] ?? '')) ?>"
                                                                    data-admin-value-nama="<?= esc((string) ($item['nama'] ?? '')) ?>"
                                                                    title="Edit">
                                                                    <i class="bi bi-pencil"></i>
                                                                </button>
                                                                <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                                    data-href="<?= site_url('admin/master/referensi/delete/' . $key . '/' . $item['id']) ?>"
                                                                    data-delete-label="<?= esc((string) ($item['nama'] ?? '')) ?>"
                                                                    data-delete-desc="Data referensi akan dinonaktifkan dan bisa dipulihkan kembali."
                                                                    title="Hapus">
                                                                    <i class="bi bi-trash"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="modal fade" id="modal-tambah-<?= esc((string) $key) ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="<?= site_url('admin/master/referensi/store/' . $key) ?>" method="post" data-submit-state-form>
                                            <?= csrf_field() ?>
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="<?= esc((string) ($tab['icon'] ?? '')) ?> me-2"></i>Tambah <?= esc((string) ($tab['label'] ?? '')) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php if ($hasError && !empty($viewState['errors'])): ?>
                                                    <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                                        <ul class="mb-0 ps-3">
                                                            <?php foreach ((array) $viewState['errors'] as $error): ?>
                                                                <li><?= esc((string) $error) ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold"><?= esc((string) ($tab['fieldLabel'] ?? '')) ?> <span class="text-danger">*</span></label>
                                                    <input type="text" name="nama" class="form-control <?= ($hasError && !empty($viewState['errors']['nama'])) ? 'is-invalid' : '' ?>"
                                                        value="<?= $hasError ? esc(old('nama', '')) : '' ?>" placeholder="Masukkan <?= esc(strtolower($tab['fieldLabel'])) ?>" required>
                                                    <?php if ($hasError && !empty($viewState['errors']['nama'])): ?>
                                                        <div class="invalid-feedback"><?= esc((string) ($viewState['errors']['nama'] ?? '')) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button>
                                                <button type="submit" class="btn btn-primary" data-submit-trigger>
                                                    <span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span>Simpan</span></span>
                                                    <span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" id="form-edit-referensi" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" data-admin-modal-title><i class="bi bi-pencil-square me-2"></i>Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text" name="nama" class="form-control" data-admin-field="nama" placeholder="Masukkan nama" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button>
                    <button type="submit" class="btn btn-warning" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span>Simpan Perubahan</span></span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>