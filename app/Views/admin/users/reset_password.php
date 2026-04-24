<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-lock me-2"></i>Reset Password
                </h3>
            </div>
            <form action="<?= esc($action) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>User:</strong> <?= esc($user['nama_lengkap'] ?? $user['username']) ?>
                    </div>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Ada kesalahan:</strong> <?= implode('<br>', (array)session()->getFlashdata('errors')) ?>
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
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-lg me-1"></i>Reset Password
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