<?php

/** @var string $title */
/** @var array<string,mixed> $viewState */
/** @var array<int,array<string,mixed>> $fakultasRows */
/** @var array<int,array<string,mixed>> $prodi */
/** @var array<int,array<string,mixed>> $fakultasOptions */

$title = $title ?? '';
$viewState = $viewState ?? [];
$fakultasRows = $fakultasRows ?? [];
$prodi = $prodi ?? [];
$fakultasOptions = $fakultasOptions ?? [];
?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Master Data</span>
                            <span class="badge text-bg-success px-3 py-2">Akademik</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc((string) $title) ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="d-none" data-admin-auto-open-tab="tab-<?= esc((string) ($viewState['activeTab'] ?? '')) ?>-link" <?= !empty($viewState['openModal'] ?? '') ? ' data-admin-auto-open-modal="modal-' . esc((string) ($viewState['openModal'] ?? '')) . '"' : '' ?>></div>
        <div class="card card-success card-outline admin-table-card card-outline-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="akademikTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?= (($viewState['activeTab'] ?? '') === 'fakultas') ? 'active' : '' ?>" id="tab-fakultas-link" data-bs-toggle="tab" href="#tab-fakultas" role="tab">
                            <i class="bi bi-building me-1"></i>Fakultas
                            <span class="badge bg-secondary ms-1"><?= esc((string) count($fakultasRows)) ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?= (($viewState['activeTab'] ?? '') === 'prodi') ? 'active' : '' ?>" id="tab-prodi-link" data-bs-toggle="tab" href="#tab-prodi" role="tab">
                            <i class="bi bi-mortarboard me-1"></i>Program Studi
                            <span class="badge bg-secondary ms-1"><?= esc((string) count($prodi)) ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="akademikTabsContent">
                    <div class="tab-pane fade <?= (($viewState['activeTab'] ?? '') === 'fakultas') ? 'show active' : '' ?>" id="tab-fakultas" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-muted"><i class="bi bi-building me-1"></i>Daftar Fakultas &mdash; <strong><?= esc((string) count($fakultasRows)) ?></strong> data</h6>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-tambah-fakultas"><i class="bi bi-plus-lg me-1"></i>Tambah Fakultas</button>
                        </div>

                        <?php if (empty($fakultasRows)): ?>
                            <div class="dosen-empty-state">
                                <i class="bi bi-building"></i>
                                <p class="mb-1">Belum ada data Fakultas</p>
                                <small>Tambahkan fakultas terlebih dahulu sebelum membuat program studi.</small>
                            </div>
                        <?php else: ?>
                            <div class="dt-skeleton-wrap">
                                <div class="dt-skeleton-overlay" id="sk-fakultas">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px"></th>
                                                <th></th>
                                                <th style="width:100px"></th>
                                                <th style="width:110px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ([65, 80, 50, 72, 45] as $width): ?>
                                                <tr>
                                                    <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                                    <td><span class="skeleton-line" style="width:<?= esc((string) $width) ?>%"></span></td>
                                                    <td class="text-center"><span class="skeleton-line mx-auto" style="width:50px;border-radius:20px;height:20px"></span></td>
                                                    <td class="text-center"><span class="skeleton-btn me-1"></span><span class="skeleton-btn"></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dt-real-wrap" id="rw-fakultas">
                                    <table id="dt-fakultas" class="table table-hover table-bordered align-middle w-100" data-admin-datatable data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,2,3]}]}' data-skeleton-id="sk-fakultas" data-real-wrap-id="rw-fakultas">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px" class="text-center">#</th>
                                                <th>Nama Fakultas</th>
                                                <th style="width:100px" class="text-center">Prodi</th>
                                                <th style="width:110px" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($fakultasRows as $row): ?>
                                                <tr>
                                                    <td class="text-center"><?= esc((string) $row['number']) ?></td>
                                                    <td>
                                                        <?= esc((string) ($row['name'] ?? '')) ?>
                                                        <?php if (!empty($row['isArchived'])): ?><span class="badge bg-secondary ms-1">Nonaktif</span><?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($row['prodiCount'] > 0): ?>
                                                            <span class="badge bg-info"><?= esc((string) $row['prodiCount']) ?> Prodi</span>
                                                        <?php else: ?>
                                                            <span class="text-muted small">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center btn-action-group admin-action-group">
                                                        <?php if ($row['isArchived']): ?>
                                                            <a href="#" class="btn btn-success btn-sm btn-admin-restore" data-href="<?= site_url('admin/master/akademik/restore/fakultas/' . $row['id']) ?>" data-confirm-title="Pulihkan data ini?" data-confirm-html="Data <strong><?= esc((string) ($row['name'] ?? '')) ?></strong> akan diaktifkan kembali." data-confirm-button="Ya, pulihkan" title="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></a>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-warning btn-sm" data-admin-modal-target="#modal-edit-fakultas" data-admin-form-action="<?= site_url('admin/master/akademik/update/fakultas/' . $row['id']) ?>" data-admin-modal-title-text="<i class='bi bi-pencil-square me-2'></i>Edit Fakultas" data-admin-value-nama="<?= esc((string) ($row['name'] ?? '')) ?>" title="Edit"><i class="bi bi-pencil"></i></button>
                                                            <a href="#" class="btn btn-danger btn-sm btn-delete" data-href="<?= site_url('admin/master/akademik/delete/fakultas/' . $row['id']) ?>" data-delete-label="<?= esc((string) ($row['name'] ?? '')) ?>" data-delete-desc="Fakultas akan dinonaktifkan dan bisa dipulihkan kembali." title="Hapus"><i class="bi bi-trash"></i></a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade <?= (($viewState['activeTab'] ?? '') === 'prodi') ? 'show active' : '' ?>" id="tab-prodi" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-muted"><i class="bi bi-mortarboard me-1"></i>Daftar Program Studi &mdash; <strong><?= esc((string) count($prodi)) ?></strong> data</h6>
                            <?php if (!empty($fakultasOptions)): ?>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-tambah-prodi"><i class="bi bi-plus-lg me-1"></i>Tambah Program Studi</button>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled><i class="bi bi-plus-lg me-1"></i>Tambah Program Studi</button>
                            <?php endif; ?>
                        </div>

                        <?php if (empty($prodi)): ?>
                            <div class="dosen-empty-state">
                                <i class="bi bi-mortarboard"></i>
                                <p class="mb-1">Belum ada data Program Studi</p>
                                <small>Pastikan fakultas sudah tersedia terlebih dahulu.</small>
                            </div>
                        <?php else: ?>
                            <div class="dt-skeleton-wrap">
                                <div class="dt-skeleton-overlay" id="sk-prodi">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px"></th>
                                                <th></th>
                                                <th></th>
                                                <th style="width:110px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ([70, 50, 65, 45, 80] as $width): ?>
                                                <tr>
                                                    <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                                    <td><span class="skeleton-line" style="width:<?= esc((string) $width) ?>%"></span></td>
                                                    <td><span class="skeleton-line" style="width:55%;border-radius:20px;height:20px"></span></td>
                                                    <td class="text-center"><span class="skeleton-btn me-1"></span><span class="skeleton-btn"></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="dt-real-wrap" id="rw-prodi">
                                    <table id="dt-prodi" class="table table-hover table-bordered align-middle w-100" data-admin-datatable data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,3]}]}' data-skeleton-id="sk-prodi" data-real-wrap-id="rw-prodi">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px" class="text-center">#</th>
                                                <th>Nama Program Studi</th>
                                                <th>Fakultas</th>
                                                <th style="width:110px" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($prodi as $index => $item): ?>
                                                <tr>
                                                    <td class="text-center"><?= esc((string) ($index + 1)) ?></td>
                                                    <td><?= esc((string) ($item['nama'] ?? '')) ?><?php if (!empty($item['deleted_at'])): ?><span class="badge bg-secondary ms-1">Nonaktif</span><?php endif; ?></td>
                                                    <td>
                                                        <?php if (!empty($item['nama_fakultas'])): ?>
                                                            <span class="badge bg-light text-dark border"><i class="bi bi-building me-1"></i><?= esc((string) ($item['nama_fakultas'] ?? '')) ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted small"><i>Tidak terdata</i></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center btn-action-group admin-action-group">
                                                        <?php if (!empty($item['deleted_at'])): ?>
                                                            <a href="#" class="btn btn-success btn-sm btn-admin-restore" data-href="<?= site_url('admin/master/akademik/restore/prodi/' . $item['id']) ?>" data-confirm-title="Pulihkan data ini?" data-confirm-html="Data <strong><?= esc((string) ($item['nama'] ?? '')) ?></strong> akan diaktifkan kembali." data-confirm-button="Ya, pulihkan" title="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></a>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-warning btn-sm" data-admin-modal-target="#modal-edit-prodi" data-admin-form-action="<?= site_url('admin/master/akademik/update/prodi/' . $item['id']) ?>" data-admin-modal-title-text="<i class='bi bi-pencil-square me-2'></i>Edit Program Studi" data-admin-value-nama="<?= esc((string) ($item['nama'] ?? '')) ?>" data-admin-value-fakultas-id="<?= esc((string) ($item['fakultas_id'] ?? '')) ?>" title="Edit"><i class="bi bi-pencil"></i></button>
                                                            <a href="#" class="btn btn-danger btn-sm btn-delete" data-href="<?= site_url('admin/master/akademik/delete/prodi/' . $item['id']) ?>" data-delete-label="<?= esc((string) ($item['nama'] ?? '')) ?>" data-delete-desc="Program studi akan dinonaktifkan dan bisa dipulihkan kembali." title="Hapus"><i class="bi bi-trash"></i></a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
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