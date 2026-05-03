<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<form action="<?= site_url('login') ?>" method="post" autocomplete="off">
    <?= csrf_field() ?>
    
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="username" name="username" placeholder="Username atau Email" required autofocus>
        <label for="username"><i class="bi bi-person me-2"></i>Username atau Email</label>
    </div>
    
    <div class="form-floating mb-3 position-relative">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
        <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none text-muted pe-3" id="togglePassword">
            <i class="bi bi-eye"></i>
        </button>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
            <label class="form-check-label small text-muted" for="rememberMe">
                Ingat Saya
            </label>
        </div>
        <a href="#" class="small text-decoration-none text-success fw-semibold">Lupa Password?</a>
    </div>

    <button type="submit" class="btn btn-auth-submit w-100">
        Masuk ke Sistem <i class="bi bi-arrow-right ms-2"></i>
    </button>
</form>
<?= $this->endSection() ?>