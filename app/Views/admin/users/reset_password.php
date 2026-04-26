<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center admin-page">
    <div class="col-md-6">
        <div class="card card-warning card-outline admin-form-card shadow-sm">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title">
                    <i class="bi bi-lock me-2"></i>Reset Password
                </h3>
            </div>
            <form action="<?= esc($action) ?>" method="post" data-submit-state-form>
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="alert alert-info admin-soft-banner" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>User:</strong> <?= esc($viewState['displayName']) ?>
                    </div>

                    <?php if (!empty($viewState['errors'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Ada kesalahan:</strong> <?= implode('<br>', array_map('esc', (array) $viewState['errors'])) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Password Baru <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Minimal 6 karakter"
                            minlength="6" required autofocus>
                        <small class="text-muted">Masukkan password yang kuat, kombinasikan huruf, angka, dan simbol.</small>
                    </div>
                </div>
                <div class="card-footer admin-form-footer d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-warning" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                            <i class="bi bi-check-lg"></i>
                            <span>Reset Password</span>
                        </span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <span>Memproses...</span>
                        </span>
                    </button>
                    <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>