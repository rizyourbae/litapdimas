<?php
/**
 * @var array $viewState
 * @var array $tabs
 */
?>

<!-- Modals Tambah (Dynamic) -->
<?php foreach ($tabs as $key => $tab): ?>
    <?php $hasError = (($viewState['openModal'] ?? '') === 'tambah-' . $key); ?>
    <div class="modal fade" id="modal-tambah-<?= esc((string) $key) ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="<?= site_url('admin/master/referensi/store/' . $key) ?>" method="post" data-submit-state-form>
                    <?= csrf_field() ?>
                    <div class="modal-header bg-light border-0 py-3">
                        <h5 class="modal-title fw-bold text-dark"><i class="<?= esc((string) ($tab['icon'] ?? '')) ?> me-2"></i>Tambah <?= esc((string) ($tab['label'] ?? '')) ?></h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <?php if ($hasError && !empty($viewState['errors'])): ?>
                            <div class="alert alert-danger py-2 border-0 shadow-sm mb-3">
                                <ul class="mb-0 ps-3">
                                    <?php foreach ((array) $viewState['errors'] as $error): ?>
                                        <li><?= esc((string) $error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div class="mb-0">
                            <label class="form-label fw-bold text-muted small mb-1"><?= esc((string) ($tab['fieldLabel'] ?? 'Nama')) ?> <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control form-control-lg fs-6 shadow-none <?= ($hasError && !empty($viewState['errors']['nama'])) ? 'is-invalid' : '' ?>"
                                value="<?= $hasError ? esc(old('nama', '')) : '' ?>" placeholder="Masukkan <?= esc(strtolower($tab['fieldLabel'])) ?>..." required>
                            <?php if ($hasError && !empty($viewState['errors']['nama'])): ?>
                                <div class="invalid-feedback"><?= esc((string) ($viewState['errors']['nama'] ?? '')) ?></div>
                            <?php endif; ?>
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
<?php endforeach; ?>

<!-- Modal Edit (Generic) -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="" method="post" id="form-edit-referensi" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark" data-admin-modal-title><i class="bi bi-pencil-square me-2"></i>Edit Data</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small mb-1">Nama / Label <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-lg fs-6 shadow-none" data-admin-field="nama" placeholder="Masukkan nama..." required>
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
