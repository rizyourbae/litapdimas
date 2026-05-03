<?php

/** @var string $title */
/** @var array<string,mixed> $viewState */
/** @var array<int,array<string,mixed>> $fakultasRows */
/** @var array<int,array<string,mixed>> $prodi */
/** @var array<int,array<string,mixed>> $fakultasOptions */


?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Kelola data Fakultas dan Program Studi dalam ekosistem akademik.',
            'badges' => [
                ['label' => 'Master Data', 'class' => 'text-bg-light border'],
                ['label' => 'Akademik', 'class' => 'text-bg-success shadow-sm']
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <div class="d-none" data-admin-auto-open-tab="tab-<?= esc((string) ($viewState['activeTab'] ?? '')) ?>-link" <?= !empty($viewState['openModal'] ?? '') ? ' data-admin-auto-open-modal="modal-' . esc((string) ($viewState['openModal'] ?? '')) . '"' : '' ?>></div>
        
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-light p-0 border-bottom">
                <ul class="nav nav-tabs nav-fill admin-nav-tabs border-0" id="akademikTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3 <?= (($viewState['activeTab'] ?? '') === 'fakultas') ? 'active' : '' ?>" id="tab-fakultas-link" data-bs-toggle="tab" href="#tab-fakultas" role="tab">
                            <i class="bi bi-building-fill me-2"></i>Fakultas
                            <span class="badge bg-secondary-soft text-secondary ms-2 rounded-pill"><?= esc((string) count($fakultasRows)) ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3 <?= (($viewState['activeTab'] ?? '') === 'prodi') ? 'active' : '' ?>" id="tab-prodi-link" data-bs-toggle="tab" href="#tab-prodi" role="tab">
                            <i class="bi bi-mortarboard-fill me-2"></i>Program Studi
                            <span class="badge bg-secondary-soft text-secondary ms-2 rounded-pill"><?= esc((string) count($prodi)) ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="akademikTabsContent">
                    <div class="tab-pane fade <?= (($viewState['activeTab'] ?? '') === 'fakultas') ? 'show active' : '' ?>" id="tab-fakultas" role="tabpanel">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">
                                    <i class="bi bi-building-fill text-success me-2"></i>Daftar Fakultas
                                </h5>
                                <p class="text-muted small mb-0 mt-1">Ditemukan <strong><?= esc((string) count($fakultasRows)) ?></strong> data fakultas</p>
                            </div>
                            <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-tambah-fakultas">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Fakultas
                            </button>
                        </div>

                        <?php if (empty($fakultasRows)): ?>
                            <?= view('components/ui-empty-state', [
                                'icon' => 'bi bi-building-exclamation',
                                'title' => 'Belum ada data Fakultas',
                                'desc' => 'Tambahkan fakultas terlebih dahulu sebelum membuat program studi.',
                                'action_label' => 'Tambah Fakultas',
                                'action_url' => '#',
                                'action_attr' => 'data-bs-toggle="modal" data-bs-target="#modal-tambah-fakultas"'
                            ]) ?>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table id="dt-fakultas" class="table table-hover align-middle w-100 border-0" data-admin-datatable data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,2,3]}]}'>
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:60px" class="text-center py-3">#</th>
                                            <th class="py-3">Nama Fakultas</th>
                                            <th style="width:120px" class="text-center py-3">Prodi</th>
                                            <th style="width:140px" class="text-center py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($fakultasRows as $row): ?>
                                            <tr>
                                                <td class="text-center text-muted small"><?= esc((string) $row['number']) ?></td>
                                                <td>
                                                    <span class="fw-bold text-dark"><?= esc((string) ($row['name'] ?? '')) ?></span>
                                                    <?php if (!empty($row['isArchived'])): ?>
                                                        <span class="badge bg-secondary-soft text-secondary rounded-pill ms-2 small">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($row['prodiCount'] > 0): ?>
                                                        <span class="badge bg-primary-soft text-primary rounded-pill px-3"><?= esc((string) $row['prodiCount']) ?> Prodi</span>
                                                    <?php else: ?>
                                                        <span class="text-muted small opacity-50">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <?php if ($row['isArchived']): ?>
                                                            <button type="button" class="btn-action-sm bg-success-soft text-success shadow-sm btn-admin-restore" data-href="<?= site_url('admin/master/akademik/restore/fakultas/' . $row['id']) ?>" data-confirm-title="Pulihkan data ini?" data-confirm-html="Data <strong><?= esc((string) ($row['name'] ?? '')) ?></strong> akan diaktifkan kembali." data-confirm-button="Ya, pulihkan" title="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm" data-admin-modal-target="#modal-edit-fakultas" data-admin-form-action="<?= site_url('admin/master/akademik/update/fakultas/' . $row['id']) ?>" data-admin-modal-title-text="Edit Fakultas" data-admin-value-nama="<?= esc((string) ($row['name'] ?? '')) ?>" title="Edit"><i class="bi bi-pencil-square"></i></button>
                                                            <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete" data-href="<?= site_url('admin/master/akademik/delete/fakultas/' . $row['id']) ?>" data-delete-label="<?= esc((string) ($row['name'] ?? '')) ?>" data-delete-desc="Fakultas akan dinonaktifkan." title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade <?= (($viewState['activeTab'] ?? '') === 'prodi') ? 'show active' : '' ?>" id="tab-prodi" role="tabpanel">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">
                                    <i class="bi bi-mortarboard-fill text-success me-2"></i>Daftar Program Studi
                                </h5>
                                <p class="text-muted small mb-0 mt-1">Ditemukan <strong><?= esc((string) count($prodi)) ?></strong> program studi</p>
                            </div>
                            <?php if (!empty($fakultasOptions)): ?>
                                <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-tambah-prodi">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah Program Studi
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary rounded-pill px-4" disabled><i class="bi bi-plus-lg me-1"></i>Tambah Program Studi</button>
                            <?php endif; ?>
                        </div>

                        <?php if (empty($prodi)): ?>
                            <?= view('components/ui-empty-state', [
                                'icon' => 'bi bi-mortarboard-fill',
                                'title' => 'Belum ada data Program Studi',
                                'desc' => 'Pastikan fakultas sudah tersedia terlebih dahulu sebelum menambah prodi.',
                                'action_label' => 'Tambah Prodi',
                                'action_url' => '#',
                                'action_attr' => 'data-bs-toggle="modal" data-bs-target="#modal-tambah-prodi"'
                            ]) ?>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table id="dt-prodi" class="table table-hover align-middle w-100 border-0" data-admin-datatable data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,3]}]}'>
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:60px" class="text-center py-3">#</th>
                                            <th class="py-3">Nama Program Studi</th>
                                            <th class="py-3">Fakultas</th>
                                            <th style="width:140px" class="text-center py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prodi as $index => $item): ?>
                                            <tr>
                                                <td class="text-center text-muted small"><?= esc((string) ($index + 1)) ?></td>
                                                <td>
                                                    <span class="fw-bold text-dark"><?= esc((string) ($item['nama'] ?? '')) ?></span>
                                                    <?php if (!empty($item['deleted_at'])): ?>
                                                        <span class="badge bg-secondary-soft text-secondary rounded-pill ms-2 small">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($item['nama_fakultas'])): ?>
                                                        <span class="badge bg-light text-dark border fw-normal px-3 py-2 rounded-pill">
                                                            <i class="bi bi-building-fill me-1 text-success opacity-75"></i><?= esc((string) ($item['nama_fakultas'] ?? '')) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted small opacity-50"><i>Tidak terdata</i></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <?php if (!empty($item['deleted_at'])): ?>
                                                            <button type="button" class="btn-action-sm bg-success-soft text-success shadow-sm btn-admin-restore" data-href="<?= site_url('admin/master/akademik/restore/prodi/' . $item['id']) ?>" data-confirm-title="Pulihkan data ini?" data-confirm-html="Data <strong><?= esc((string) ($item['nama'] ?? '')) ?></strong> akan diaktifkan kembali." data-confirm-button="Ya, pulihkan" title="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm" data-admin-modal-target="#modal-edit-prodi" data-admin-form-action="<?= site_url('admin/master/akademik/update/prodi/' . $item['id']) ?>" data-admin-modal-title-text="Edit Program Studi" data-admin-value-nama="<?= esc((string) ($item['nama'] ?? '')) ?>" data-admin-value-fakultas-id="<?= esc((string) ($item['fakultas_id'] ?? '')) ?>" title="Edit"><i class="bi bi-pencil-square"></i></button>
                                                            <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete" data-href="<?= site_url('admin/master/akademik/delete/prodi/' . $item['id']) ?>" data-delete-label="<?= esc((string) ($item['nama'] ?? '')) ?>" data-delete-desc="Program studi akan dinonaktifkan." title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $hasFakultasError = (($viewState['openModal'] ?? '') === 'tambah-fakultas'); ?>
<div class="modal fade" id="modal-tambah-fakultas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/master/akademik/store/fakultas') ?>" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-building me-2"></i>Tambah Fakultas</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if ($hasFakultasError && !empty($viewState['errors'] ?? [])): ?>
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3"><?php foreach ((array) ($viewState['errors'] ?? []) as $error): ?><li><?= esc((string) $error) ?></li><?php endforeach; ?></ul>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Fakultas <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control <?= ($hasFakultasError && !empty($viewState['errors']['nama'])) ? 'is-invalid' : '' ?>" value="<?= $hasFakultasError ? esc(old('nama', '')) : '' ?>" placeholder="Contoh: Fakultas Teknik" required>
                        <?php if ($hasFakultasError && !empty($viewState['errors']['nama'])): ?><div class="invalid-feedback"><?= esc((string) ($viewState['errors']['nama'] ?? '')) ?></div><?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button><button type="submit" class="btn btn-success" data-submit-trigger><span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span>Simpan</span></span><span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span></button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-fakultas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" data-admin-modal-title><i class="bi bi-pencil-square me-2"></i>Edit Fakultas</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Nama Fakultas <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control" data-admin-field="nama" placeholder="Nama Fakultas" required></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button><button type="submit" class="btn btn-warning" data-submit-trigger><span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span>Simpan Perubahan</span></span><span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span></button></div>
            </form>
        </div>
    </div>
</div>

<?php $hasProdiError = (($viewState['openModal'] ?? '') === 'tambah-prodi'); ?>
<div class="modal fade" id="modal-tambah-prodi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/master/akademik/store/prodi') ?>" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-mortarboard me-2"></i>Tambah Program Studi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if ($hasProdiError && !empty($viewState['errors'] ?? [])): ?>
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3"><?php foreach ((array) ($viewState['errors'] ?? []) as $error): ?><li><?= esc((string) $error) ?></li><?php endforeach; ?></ul>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3"><label class="form-label fw-semibold">Nama Program Studi <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control <?= ($hasProdiError && !empty($viewState['errors']['nama'])) ? 'is-invalid' : '' ?>" value="<?= $hasProdiError ? esc(old('nama', '')) : '' ?>" placeholder="Contoh: Teknik Informatika" required><?php if ($hasProdiError && !empty($viewState['errors']['nama'])): ?><div class="invalid-feedback"><?= esc((string) ($viewState['errors']['nama'] ?? '')) ?></div><?php endif; ?></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Fakultas <span class="text-danger">*</span></label><select name="fakultas_id" class="form-select" required>
                            <option value="">-- Pilih Fakultas --</option>
                            <?php foreach ($fakultasOptions as $fak): ?>
                                <option value="<?= esc((string) ($fak['id'] ?? '')) ?>" <?= ($hasProdiError && old('fakultas_id') == ($fak['id'] ?? '')) ? 'selected' : '' ?>><?= esc((string) ($fak['nama'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button><button type="submit" class="btn btn-success" data-submit-trigger><span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span>Simpan</span></span><span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span></button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-prodi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" data-admin-modal-title><i class="bi bi-pencil-square me-2"></i>Edit Program Studi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Nama Program Studi <span class="text-danger">*</span></label><input type="text" name="nama" class="form-control" data-admin-field="nama" placeholder="Nama Program Studi" required></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Fakultas <span class="text-danger">*</span></label><select name="fakultas_id" id="editProdiFakultasId" class="form-select" data-admin-field="fakultas_id" data-select2 required>
                            <option value="">-- Pilih Fakultas --</option>
                            <?php foreach ($fakultasOptions as $fak): ?>
                                <option value="<?= esc((string) ($fak['id'] ?? '')) ?>"><?= esc((string) ($fak['nama'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button><button type="submit" class="btn btn-warning" data-submit-trigger><span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span>Simpan Perubahan</span></span><span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span></button></div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>