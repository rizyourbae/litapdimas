<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/** @var string $title */
/** @var array<string,mixed> $viewState */
/** @var array<string,mixed> $tabs */
?>
<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc($title),
            'subtitle' => 'Kelola referensi dasar sistem seperti Jenjang, Jabatan, dan Klaster.',
            'badges' => [
                ['label' => 'Master Data', 'class' => 'text-bg-light border'],
                ['label' => 'Referensi Sistem', 'class' => 'text-bg-primary shadow-sm']
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <div class="d-none" data-admin-auto-open-tab="tab-<?= esc((string) ($viewState['activeTab'] ?? '')) ?>-link" <?= !empty($viewState['openModal'] ?? '') ? ' data-admin-auto-open-modal="modal-' . esc((string) ($viewState['openModal'] ?? '')) . '"' : '' ?>></div>
        
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-light p-0 border-bottom">
                <ul class="nav nav-tabs nav-fill admin-nav-tabs border-0" id="referensiTabs" role="tablist">
                    <?php foreach ($tabs as $key => $tab): ?>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link py-3 <?= (($viewState['activeTab'] ?? '') === $key) ? 'active' : '' ?>" id="tab-<?= esc((string) $key) ?>-link" data-bs-toggle="tab" href="#tab-<?= esc((string) $key) ?>" role="tab">
                                <i class="<?= esc((string) ($tab['icon'] ?? '')) ?>-fill me-2"></i>
                                <?= esc((string) ($tab['label'] ?? '')) ?>
                                <span class="badge bg-secondary-soft text-secondary ms-2 rounded-pill"><?= esc((string) count($tab['items'])) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="referensiTabsContent">
                    <?php foreach ($tabs as $key => $tab): ?>
                        <?php
                        $dtId = 'dt-' . $key;
                        $skId = 'sk-' . $key;
                        $rwId = 'rw-' . $key;
                        $hasError = (($viewState['openModal'] ?? '') === 'tambah-' . $key);
                        ?>
                        <div class="tab-pane fade <?= (($viewState['activeTab'] ?? '') === $key) ? 'show active' : '' ?>" id="tab-<?= esc((string) $key) ?>" role="tabpanel">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark">
                                        <i class="<?= esc((string) ($tab['icon'] ?? '')) ?>-fill text-primary me-2"></i>
                                        Daftar <?= esc((string) ($tab['label'] ?? '')) ?>
                                    </h5>
                                    <p class="text-muted small mb-0 mt-1">Ditemukan <strong><?= esc((string) count($tab['items'])) ?></strong> entri data</p>
                                </div>
                                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-tambah-<?= esc((string) $key) ?>">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah <?= esc((string) ($tab['label'] ?? '')) ?>
                                </button>
                            </div>

                            <?php if (empty($tab['items'])): ?>
                                <?= view('components/ui-empty-state', [
                                    'icon' => 'bi bi-database-exclamation',
                                    'title' => 'Belum ada data ' . $tab['label'],
                                    'desc' => 'Klik tombol tambah untuk membuat data referensi baru.',
                                    'action_label' => 'Tambah Data',
                                    'action_url' => '#',
                                    'action_attr' => 'data-bs-toggle="modal" data-bs-target="#modal-tambah-' . $key . '"'
                                ]) ?>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table id="<?= esc($dtId) ?>" class="table table-hover align-middle w-100 border-0" data-admin-datatable data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,2]}]}'>
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px" class="text-center py-3">#</th>
                                                <th class="py-3"><?= esc((string) ($tab['fieldLabel'] ?? '')) ?></th>
                                                <th style="width:140px" class="text-center py-3">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tab['items'] as $index => $item): ?>
                                                <tr>
                                                    <td class="text-center text-muted small"><?= esc((string) ($index + 1)) ?></td>
                                                    <td>
                                                        <span class="fw-bold text-dark"><?= esc((string) ($item['nama'] ?? '')) ?></span>
                                                        <?php if (!empty($item['deleted_at'])): ?>
                                                            <span class="badge bg-secondary-soft text-secondary rounded-pill ms-2 small">Nonaktif</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <?php if (!empty($item['deleted_at'])): ?>
                                                                <button type="button" class="btn-action-sm bg-success-soft text-success shadow-sm btn-admin-restore"
                                                                    data-href="<?= site_url('admin/master/referensi/restore/' . $key . '/' . $item['id']) ?>"
                                                                    data-confirm-title="Pulihkan data ini?"
                                                                    data-confirm-html="Data <strong><?= esc((string) ($item['nama'] ?? '')) ?></strong> akan diaktifkan kembali."
                                                                    data-confirm-button="Ya, pulihkan"
                                                                    title="Pulihkan">
                                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                                </button>
                                                            <?php else: ?>
                                                                <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm"
                                                                    data-admin-modal-target="#modal-edit"
                                                                    data-admin-form-action="<?= site_url('admin/master/referensi/update/' . $key . '/' . $item['id']) ?>"
                                                                    data-admin-modal-title-text="Edit <?= esc((string) ($tab['label'] ?? '')) ?>"
                                                                    data-admin-value-nama="<?= esc((string) ($item['nama'] ?? '')) ?>"
                                                                    title="Edit">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                </button>
                                                                <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete"
                                                                    data-href="<?= site_url('admin/master/referensi/delete/' . $key . '/' . $item['id']) ?>"
                                                                    data-delete-label="<?= esc((string) ($item['nama'] ?? '')) ?>"
                                                                    data-delete-desc="Data referensi akan dinonaktifkan."
                                                                    title="Hapus">
                                                                    <i class="bi bi-trash3-fill"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
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