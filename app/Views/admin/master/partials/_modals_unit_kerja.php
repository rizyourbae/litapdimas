<?php
/**
 * @var array $viewState
 * @var array $parentGroups
 */
?>

<!-- Modal Tambah Unit Kerja -->
<div class="modal fade" id="modal-tambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= site_url('admin/master/unit-kerja/store') ?>" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Unit Kerja</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <?php if (($viewState['openModal'] ?? '') === 'tambah' && !empty($viewState['errors'])): ?>
                        <div class="alert alert-danger py-2 border-0 shadow-sm mb-3">
                            <ul class="mb-0 ps-3">
                                <?php foreach ((array) $viewState['errors'] as $error): ?>
                                    <li><?= esc((string) $error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small mb-1">Nama Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-lg fs-6 shadow-none <?= (($viewState['openModal'] ?? '') === 'tambah' && !empty($viewState['errors']['nama'])) ? 'is-invalid' : '' ?>"
                            value="<?= ($viewState['openModal'] ?? '') === 'tambah' ? esc(old('nama', '')) : '' ?>" placeholder="Contoh: Rektorat / Lembaga Penelitian" required>
                        <?php if (($viewState['openModal'] ?? '') === 'tambah' && !empty($viewState['errors']['nama'])): ?>
                            <div class="invalid-feedback"><?= esc((string) ($viewState['errors']['nama'] ?? '')) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small mb-1">Unit Induk <span class="text-muted fw-normal ms-1">(Opsional)</span></label>
                        <select name="parent_id" class="form-select form-select-lg fs-6 shadow-none">
                            <option value="">-- Tidak ada (Unit Utama / Root) --</option>
                            <?php foreach ($parentGroups as $groupLabel => $groupItems): ?>
                                <optgroup label="<?= esc($groupLabel) ?>">
                                    <?php foreach ($groupItems as $unit): ?>
                                        <option value="<?= esc((string) ($unit['id'] ?? '')) ?>" <?= (($viewState['openModal'] ?? '') === 'tambah' && old('parent_id') == ($unit['id'] ?? '')) ? 'selected' : '' ?>><?= esc((string) ($unit['nama'] ?? '')) ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted mt-2 d-block">Pilih unit induk jika unit ini berada di bawah lembaga/biro lain.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span>Simpan Data</span></span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Unit Kerja -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark" data-admin-modal-title><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Unit Kerja</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small mb-1">Nama Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-lg fs-6 shadow-none" data-admin-field="nama" placeholder="Nama Unit Kerja" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small mb-1">Unit Induk <span class="text-muted fw-normal ms-1">(Opsional)</span></label>
                        <select name="parent_id" id="editParentId" class="form-select form-select-lg fs-6 shadow-none" data-admin-field="parent_id">
                            <option value="">-- Tidak ada (Unit Utama / Root) --</option>
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
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span>Simpan Perubahan</span></span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
