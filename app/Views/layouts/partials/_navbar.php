<?php
$auth         = service('auth');
$sessionUser  = $auth->user();
$displayName  = $sessionUser['nama_lengkap'] ?? ($sessionUser['username'] ?? 'User');
$displayRoles = implode(' / ', array_map('ucfirst', $auth->getRoleNames()));
?>
<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <!-- Toggle Sidebar -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <span class="nav-link"><?= esc($currentModule ?? 'Dashboard') ?></span>
            </li>
        </ul>

        <!-- Right Navbar -->
        <ul class="navbar-nav ms-auto">
            <!-- Fullscreen -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="<?= base_url('assets/adminlte/assets/img/user2-160x160.jpg') ?>"
                        class="user-image rounded-circle shadow" alt="User">
                    <span class="d-none d-md-inline"><?= esc($displayName) ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-primary">
                        <img src="<?= base_url('assets/adminlte/assets/img/user2-160x160.jpg') ?>"
                            class="rounded-circle shadow" alt="User">
                        <p>
                            <?= esc($displayName) ?>
                            <small><?= esc($displayRoles ?: 'User') ?></small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="<?= site_url('profile') ?>" class="btn btn-default btn-flat">Profil</a>
                        <a href="<?= site_url('logout') ?>" class="btn btn-default btn-flat float-end">Keluar</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>