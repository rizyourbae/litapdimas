<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login') ?> | SMART-LP2M</title>
    <link rel="icon" href="<?= base_url('assets/adminlte/assets/img/logo/logo-uinsi.png') ?>">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    <!-- Custom Auth CSS -->
    <link rel="stylesheet" href="<?= base_url('custom/css/auth.css') ?>">
</head>

<body class="auth-page">
    <div class="auth-blob auth-blob-1"></div>
    <div class="auth-blob auth-blob-2"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logos">
                    <img src="<?= base_url('assets/adminlte/assets/img/logo/logo_kemenag.png') ?>" alt="Kemenag" class="logo-item">
                    <img src="<?= base_url('assets/adminlte/assets/img/logo/logo-uinsi.png') ?>" alt="UINSI" class="logo-item">
                </div>
                <h1 class="auth-brand">SMART-P2M</h1>
                <p class="auth-subtitle">UIN Sultan Aji Muhammad Idris Samarinda</p>
            </div>

            <div class="auth-body">
                <?php if (session('error')): ?>
                    <div class="alert alert-danger alert-auth mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div><?= session('error') ?></div>
                    </div>
                <?php endif; ?>

                <?php if (session('success')): ?>
                    <div class="alert alert-success alert-auth mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?= session('success') ?></div>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>

            <div class="auth-footer">
                &copy; <?= date('Y') ?> <strong>UINSI Samarinda</strong>. All rights reserved.
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Auth JS -->
    <script src="<?= base_url('custom/js/auth.js') ?>"></script>
</body>

</html>