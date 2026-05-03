<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/** @var string $title */
/** @var array<string,mixed> $viewState */
/** @var array<int,array<string,mixed>> $items */
/** @var array<string,array<int,array<string,mixed>>> $parentGroups */
?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc($title),
            'subtitle' => 'Kelola hierarki dan struktur unit kerja di lingkungan universitas.',
            'badges' => [
                ['label' => 'Master Data', 'class' => 'text-bg-light border'],
                ['label' => 'Unit Kerja', 'class' => 'text-bg-info shadow-sm text-white']
            ],
            'actions' => [
                [
                    'label' => 'Tambah Unit Kerja',
                    'class' => 'btn btn-primary rounded-pill px-4 shadow-sm',
                    'icon' => 'bi bi-plus-lg',
                    'attr' => 'data-bs-toggle="modal" data-bs-target="#modal-tambah"'
                ]
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <div class="d-none" <?= !empty($viewState['openModal'] ?? '') ? ' data-admin-auto-open-modal="modal-' . esc((string) ($viewState['openModal'] ?? '')) . '"' : '' ?>></div>
        
        <div class="alert alert-primary bg-primary-soft border-0 rounded-4 p-4 mb-4 d-flex align-items-start gap-3">
            <div class="bg-primary text-white rounded-circle p-2 shadow-sm"><i class="bi bi-info-circle fs-5"></i></div>
            <div>
                <h6 class="fw-bold text-primary mb-1">Panduan Struktur Hierarki</h6>
                <p class="text-primary opacity-75 small mb-0">Pilih induk dari grup Lembaga atau Unit, lalu isi sub-unit di bawahnya untuk membangun hierarki yang tepat.</p>
            </div>
        </div>

        <?php if (empty($items)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-diagram-3-fill',
                'title' => 'Belum ada data Unit Kerja',
                'desc' => 'Klik tombol tambah untuk mulai menyusun struktur unit kerja organisasi Anda.',
                'action_label' => 'Tambah Unit Pertama',
                'action_url' => '#',
                'action_attr' => 'data-bs-toggle="modal" data-bs-target="#modal-tambah"'
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead class="table-light">
                <tr>
                    <th style="width:60px" class="text-center py-3">#</th>
                    <th class="py-3">Nama Unit Kerja</th>
                    <th class="py-3">Unit Induk</th>
                    <th style="width:140px" class="text-center py-3">Aksi</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($items as $index => $item): ?>
                <tr>
                    <td class="text-center text-muted small"><?= esc((string) ($index + 1)) ?></td>
                    <td>
                        <?php if (empty($item['parent_id'])): ?>
                            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-building text-primary opacity-50"></i>
                                <?= esc((string) ($item['nama'] ?? '')) ?>
                            </div>
                        <?php else: ?>
                            <div class="ps-4 d-flex align-items-center gap-2">
                                <span class="text-muted opacity-50">└</span>
                                <span class="text-dark"><?= esc((string) ($item['nama'] ?? '')) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['deleted_at'])): ?>
                            <span class="badge bg-secondary-soft text-secondary rounded-pill ms-2 small">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($item['nama_induk'])): ?>
                            <span class="badge bg-light text-dark border fw-normal rounded-pill px-3 py-2">
                                <i class="bi bi-diagram-2-fill me-1 text-primary"></i><?= esc((string) ($item['nama_induk'] ?? '')) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted small italic opacity-50">— Unit Utama —</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <?php if (!empty($item['deleted_at'])): ?>
                                <button type="button" class="btn-action-sm bg-success-soft text-success shadow-sm btn-admin-restore"
                                    data-href="<?= site_url('admin/master/unit-kerja/restore/' . $item['id']) ?>"
                                    data-confirm-title="Pulihkan data ini?"
                                    data-confirm-html="Data <strong><?= esc((string) ($item['nama'] ?? '')) ?></strong> akan diaktifkan kembali."
                                    data-confirm-button="Ya, pulihkan"
                                    title="Pulihkan">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm"
                                    data-admin-modal-target="#modal-edit"
                                    data-admin-form-action="<?= site_url('admin/master/unit-kerja/update/' . $item['id']) ?>"
                                    data-admin-modal-title-text="Edit Unit Kerja"
                                    data-admin-value-nama="<?= esc((string) ($item['nama'] ?? '')) ?>"
                                    data-admin-value-parent-id="<?= esc((string) ($item['parent_id'] ?? '')) ?>"
                                    title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete"
                                    data-href="<?= site_url('admin/master/unit-kerja/delete/' . $item['id']) ?>"
                                    data-delete-label="<?= esc((string) ($item['nama'] ?? '')) ?>"
                                    data-delete-desc="Unit kerja akan dinonaktifkan."
                                    title="Hapus">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $body = ob_get_clean(); ?>

            <?= view('components/ui-table-card', [
                'tableId' => 'dt-unit-kerja',
                'header' => $header,
                'body' => $body,
                'type' => 'admin',
                'title' => 'Struktur Unit Kerja',
                'icon' => 'bi bi-diagram-3-fill'
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modal-tambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/master/unit-kerja/store') ?>" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Unit Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if (($viewState['openModal'] ?? '') === 'tambah' && !empty($viewState['errors'])): ?>
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3">
                                <?php foreach ((array) $viewState['errors'] as $error): ?>
                                    <li><?= esc((string) $error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control <?= (($viewState['openModal'] ?? '') === 'tambah' && !empty($viewState['errors']['nama'])) ? 'is-invalid' : '' ?>"
                            value="<?= ($viewState['openModal'] ?? '') === 'tambah' ? esc(old('nama', '')) : '' ?>" placeholder="Contoh: Rektorat" required>
                        <?php if (($viewState['openModal'] ?? '') === 'tambah' && !empty($viewState['errors']['nama'])): ?>
                            <div class="invalid-feedback"><?= esc((string) ($viewState['errors']['nama'] ?? '')) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Unit Induk <span class="text-muted">(Opsional)</span></label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- Tidak ada (Root Unit) --</option>
                            <?php foreach ($parentGroups as $groupLabel => $groupItems): ?>
                                <optgroup label="<?= esc($groupLabel) ?>">
                                    <?php foreach ($groupItems as $unit): ?>
                                        <option value="<?= esc((string) ($unit['id'] ?? '')) ?>" <?= (($viewState['openModal'] ?? '') === 'tambah' && old('parent_id') == ($unit['id'] ?? '')) ? 'selected' : '' ?>><?= esc((string) ($unit['nama'] ?? '')) ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button>
                    <button type="submit" class="btn btn-info" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span>Simpan</span></span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" data-admin-modal-title><i class="bi bi-pencil-square me-2"></i>Edit Unit Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" data-admin-field="nama" placeholder="Nama Unit Kerja" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Unit Induk <span class="text-muted">(Opsional)</span></label>
                        <select name="parent_id" id="editParentId" class="form-select" data-admin-field="parent_id">
                            <option value="">-- Tidak ada (Root Unit) --</option>
                            <?php foreach ($parentGroups as $groupLabel => $groupItems): ?>
                                <optgroup label="<?= esc($groupLabel) ?>">
                                    <?php foreach ($groupItems as $unit): ?>
                                        <option value="<?= esc((string) ($unit['id'] ?? '')) ?>"><?= esc((string) ($unit['nama'] ?? '')) ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
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