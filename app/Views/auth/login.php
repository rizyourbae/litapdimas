<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<form action="<?= site_url('login') ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label for="username" class="form-label">Username atau Email</label>
        <input type="text" class="form-control" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
</form>
<?= $this->endSection() ?>