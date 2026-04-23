<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login') ?> | Litapdimas</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .login-card {
            max-width: 400px;
            margin: 100px auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-card">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Litapdimas</h3>
                    <?php if (session('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>