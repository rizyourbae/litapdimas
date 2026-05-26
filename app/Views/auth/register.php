<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="mb-4 text-center">
    <h5 class="fw-bold mb-1">Daftar Akun Baru</h5>
    <p class="text-muted small">Silakan lengkapi data diri Anda untuk mendaftar sebagai Dosen.</p>
</div>

<form action="<?= site_url('register') ?>" method="post" autocomplete="off">
    <?= csrf_field() ?>
    
    <div class="form-floating mb-3">
        <input type="text" class="form-control <?= session('errors.nama_lengkap') ? 'is-invalid' : '' ?>" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" value="<?= old('nama_lengkap') ?>" required autofocus>
        <label for="nama_lengkap"><i class="bi bi-person-badge me-2"></i>Nama Lengkap (Sesuai Gelar)</label>
        <?php if (session('errors.nama_lengkap')): ?>
            <div class="invalid-feedback"><?= session('errors.nama_lengkap') ?></div>
        <?php endif; ?>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <div class="form-floating">
                <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Alamat Email" value="<?= old('email') ?>" required>
                <label for="email"><i class="bi bi-envelope me-2"></i>Alamat Email</label>
                <?php if (session('errors.email')): ?>
                    <div class="invalid-feedback"><?= session('errors.email') ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" id="username" name="username" placeholder="Username" value="<?= old('username') ?>" required>
                <label for="username"><i class="bi bi-person me-2"></i>Username</label>
                <?php if (session('errors.username')): ?>
                    <div class="invalid-feedback"><?= session('errors.username') ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <div class="form-floating position-relative">
                <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Password" required>
                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                <?php if (session('errors.password')): ?>
                    <div class="invalid-feedback"><?= session('errors.password') ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="password" class="form-control <?= session('errors.confirm_password') ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" placeholder="Ulangi Password" required>
                <label for="confirm_password"><i class="bi bi-shield-check me-2"></i>Konfirmasi</label>
                <?php if (session('errors.confirm_password')): ?>
                    <div class="invalid-feedback"><?= session('errors.confirm_password') ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="mb-4 d-flex align-items-center gap-3">
        <div class="border rounded p-1 bg-white shadow-sm">
            <img src="<?= $captcha ?? '' ?>" alt="Captcha" class="img-fluid" style="min-width: 150px; height: 50px;">
        </div>
        <div class="form-floating flex-grow-1">
            <input type="text" class="form-control" id="captcha" name="captcha" placeholder="Kode Captcha" required autocomplete="off">
            <label for="captcha"><i class="bi bi-shield-check me-2"></i>Kode Captcha</label>
        </div>
    </div>

    <button type="submit" class="btn btn-auth-submit w-100 mb-3">
        Daftar Sekarang <i class="bi bi-person-plus ms-2"></i>
    </button>

    <div class="text-center">
        <p class="small text-muted mb-0">Sudah punya akun? <a href="<?= site_url('login') ?>" class="text-success fw-bold text-decoration-none">Login di sini</a></p>
    </div>
</form>
<?= $this->endSection() ?>
