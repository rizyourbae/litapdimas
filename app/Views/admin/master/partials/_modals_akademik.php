<?php
/**
 * @var array $viewState
 * @var array $fakultasOptions
 */
?>

<!-- Modal Tambah Fakultas -->
<?php $hasFakultasError = (($viewState['openModal'] ?? '') === 'tambah-fakultas'); ?>
<div class="modal fade" id="modal-tambah-fakultas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= site_url('admin/master/akademik/store/fakultas') ?>" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-building me-2"></i>Tambah Fakultas</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <?php if ($hasFakultasError && !empty($viewState['errors'] ?? [])): ?>
                        <div class="alert alert-danger py-2 border-0 shadow-sm">
                            <ul class="mb-0 ps-3">
                                <?php foreach ((array) ($viewState['errors'] ?? []) as $error): ?>
                                    <li><?= esc((string) $error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small mb-1">Nama Fakultas <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-lg fs-6 shadow-none <?= ($hasFakultasError && !empty($viewState['errors']['nama'])) ? 'is-invalid' : '' ?>" 
                            value="<?= $hasFakultasError ? esc(old('nama', '')) : '' ?>" placeholder="Contoh: Fakultas Teknik" required>
                        <?php if ($hasFakultasError && !empty($viewState['errors']['nama'])): ?>
                            <div class="invalid-feedback"><?= esc((string) ($viewState['errors']['nama'] ?? '')) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                            <i class="bi bi-save"></i><span>Simpan Data</span>
                        </span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Fakultas -->
<div class="modal fade" id="modal-edit-fakultas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark" data-admin-modal-title><i class="bi bi-pencil-square me-2"></i>Edit Fakultas</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small mb-1">Nama Fakultas <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-lg fs-6 shadow-none" data-admin-field="nama" placeholder="Nama Fakultas" required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                            <i class="bi bi-save"></i><span>Simpan Perubahan</span>
                        </span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Prodi -->
<?php $hasProdiError = (($viewState['openModal'] ?? '') === 'tambah-prodi'); ?>
<div class="modal fade" id="modal-tambah-prodi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= site_url('admin/master/akademik/store/prodi') ?>" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-mortarboard me-2"></i>Tambah Program Studi</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <?php if ($hasProdiError && !empty($viewState['errors'] ?? [])): ?>
                        <div class="alert alert-danger py-2 border-0 shadow-sm">
                            <ul class="mb-0 ps-3">
                                <?php foreach ((array) ($viewState['errors'] ?? []) as $error): ?>
                                    <li><?= esc((string) $error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small mb-1">Nama Program Studi <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-lg fs-6 shadow-none <?= ($hasProdiError && !empty($viewState['errors']['nama'])) ? 'is-invalid' : '' ?>" 
                            value="<?= $hasProdiError ? esc(old('nama', '')) : '' ?>" placeholder="Contoh: Teknik Informatika" required>
                        <?php if ($hasProdiError && !empty($viewState['errors']['nama'])): ?>
                            <div class="invalid-feedback"><?= esc((string) ($viewState['errors']['nama'] ?? '')) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small mb-1">Fakultas <span class="text-danger">*</span></label>
                        <select name="fakultas_id" class="form-select form-select-lg fs-6 shadow-none" required>
                            <option value="">-- Pilih Fakultas --</option>
                            <?php foreach ($fakultasOptions as $fak): ?>
                                <option value="<?= esc((string) ($fak['id'] ?? '')) ?>" <?= ($hasProdiError && old('fakultas_id') == ($fak['id'] ?? '')) ? 'selected' : '' ?>><?= esc((string) ($fak['nama'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                            <i class="bi bi-save"></i><span>Simpan Data</span>
                        </span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Prodi -->
<div class="modal fade" id="modal-edit-prodi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark" data-admin-modal-title><i class="bi bi-pencil-square me-2"></i>Edit Program Studi</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small mb-1">Nama Program Studi <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-lg fs-6 shadow-none" data-admin-field="nama" placeholder="Nama Program Studi" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small mb-1">Fakultas <span class="text-danger">*</span></label>
                        <select name="fakultas_id" id="editProdiFakultasId" class="form-select form-select-lg fs-6 shadow-none" data-admin-field="fakultas_id" required>
                            <option value="">-- Pilih Fakultas --</option>
                            <?php foreach ($fakultasOptions as $fak): ?>
                                <option value="<?= esc((string) ($fak['id'] ?? '')) ?>"><?= esc((string) ($fak['nama'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                            <i class="bi bi-save"></i><span>Simpan Perubahan</span>
                        </span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
