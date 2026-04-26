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
                            <span class="badge text-bg-info px-3 py-2">Struktur Organisasi</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($title) ?></h2>
                    </div>
                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal-tambah">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Unit Kerja
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="d-none" <?= !empty($viewState['openModal']) ? ' data-admin-auto-open-modal="modal-' . esc($viewState['openModal']) . '"' : '' ?>></div>
        <div class="card card-info card-outline admin-table-card">
            <div class="card-body">
                <div class="alert alert-light border-start border-4 border-info mb-3 admin-soft-banner">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Struktur Hierarki:</strong> Unit dengan indentasi adalah sub-unit dari unit di atasnya.
                </div>

                <?php if (empty($items)): ?>
                    <div class="dosen-empty-state">
                        <i class="bi bi-diagram-3"></i>
                        <p class="mb-1">Belum ada data Unit Kerja</p>
                        <small>Klik tombol tambah untuk memulai struktur unit kerja.</small>
                    </div>
                <?php else: ?>
                    <div class="dt-skeleton-wrap">
                        <div class="dt-skeleton-overlay" id="sk-unit-kerja">
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
                                    <?php foreach ([70, 50, 65, 80, 45] as $width): ?>
                                        <tr>
                                            <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                            <td><span class="skeleton-line" style="width:<?= esc((string) $width) ?>%"></span></td>
                                            <td><span class="skeleton-line" style="width:60%"></span></td>
                                            <td class="text-center"><span class="skeleton-btn me-1"></span><span class="skeleton-btn"></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="dt-real-wrap" id="rw-unit-kerja">
                            <table id="dt-unit-kerja" class="table table-hover table-bordered align-middle w-100" data-admin-datatable data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,3]}]}' data-skeleton-id="sk-unit-kerja" data-real-wrap-id="rw-unit-kerja">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px" class="text-center">#</th>
                                        <th>Nama Unit Kerja</th>
                                        <th>Unit Induk</th>
                                        <th style="width:110px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $index => $item): ?>
                                        <tr>
                                            <td class="text-center"><?= esc((string) ($index + 1)) ?></td>
                                            <td>
                                                <?php if (empty($item['parent_id'])): ?>
                                                    <strong><?= esc($item['nama']) ?></strong>
                                                <?php else: ?>
                                                    <span class="ms-3 me-1">└</span><?= esc($item['nama']) ?>
                                                <?php endif; ?>
                                                <?php if (!empty($item['deleted_at'])): ?>
                                                    <span class="badge bg-secondary ms-1">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($item['nama_induk'])): ?>
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-diagram-2 me-1"></i><?= esc($item['nama_induk']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted small"><i>— Tidak ada —</i></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center btn-action-group admin-action-group">
                                                <?php if (!empty($item['deleted_at'])): ?>
                                                    <a href="#" class="btn btn-success btn-sm btn-admin-restore"
                                                        data-href="<?= site_url('admin/master/unit_kerja/restore/' . $item['id']) ?>"
                                                        data-confirm-title="Pulihkan data ini?"
                                                        data-confirm-html="Data <strong><?= esc($item['nama']) ?></strong> akan diaktifkan kembali."
                                                        data-confirm-button="Ya, pulihkan"
                                                        title="Pulihkan">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-admin-modal-target="#modal-edit"
                                                        data-admin-form-action="<?= site_url('admin/master/unit_kerja/update/' . $item['id']) ?>"
                                                        data-admin-modal-title-text="<i class='bi bi-pencil-square me-2'></i>Edit Unit Kerja"
                                                        data-admin-value-nama="<?= esc($item['nama']) ?>"
                                                        data-admin-value-parent-id="<?= esc((string) ($item['parent_id'] ?? '')) ?>"
                                                        title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                        data-href="<?= site_url('admin/master/unit_kerja/delete/' . $item['id']) ?>"
                                                        data-delete-label="<?= esc($item['nama']) ?>"
                                                        data-delete-desc="Unit kerja akan dinonaktifkan dan bisa dipulihkan kembali."
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
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-tambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/master/unit_kerja/store') ?>" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Unit Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if ($viewState['openModal'] === 'tambah' && !empty($viewState['errors'])): ?>
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3">
                                <?php foreach ((array) $viewState['errors'] as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control <?= ($viewState['openModal'] === 'tambah' && !empty($viewState['errors']['nama'])) ? 'is-invalid' : '' ?>"
                            value="<?= $viewState['openModal'] === 'tambah' ? esc(old('nama', '')) : '' ?>" placeholder="Contoh: Rektorat" required>
                        <?php if ($viewState['openModal'] === 'tambah' && !empty($viewState['errors']['nama'])): ?>
                            <div class="invalid-feedback"><?= esc($viewState['errors']['nama']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Unit Induk <span class="text-muted">(Opsional)</span></label>
                        <select name="parent_id" class="form-select" data-select2>
                            <option value="">-- Tidak ada (Root Unit) --</option>
                            <?php foreach ($options as $unit): ?>
                                <option value="<?= $unit['id'] ?>" <?= ($viewState['openModal'] === 'tambah' && old('parent_id') == $unit['id']) ? 'selected' : '' ?>><?= esc($unit['nama']) ?></option>
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
                        <select name="parent_id" id="editParentId" class="form-select" data-admin-field="parent_id" data-select2>
                            <option value="">-- Tidak ada (Root Unit) --</option>
                            <?php foreach ($options as $unit): ?>
                                <option value="<?= $unit['id'] ?>"><?= esc($unit['nama']) ?></option>
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