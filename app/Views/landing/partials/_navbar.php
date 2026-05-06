<nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= site_url() ?>">
            <img src="<?= base_url('assets/adminlte/assets/img/logo/logo-uinsi.png') ?>" alt="Logo UINSI" class="me-3" style="height: 50px; width: auto; filter: drop-shadow(0 0 10px rgba(0,0,0,0.1));">
            <div class="d-flex flex-column lh-1">
                <span class="fs-4 fw-bold">LITAPDIMAS</span>
                <span class="small opacity-75 fw-normal" style="font-size: 0.7rem; letter-spacing: 1px;">UINSI SAMARINDA</span>
            </div>
        </a>
        <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list text-white fs-1"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link <?= (isset($currentPage) && $currentPage == 'pengumuman') ? 'active' : '' ?>" href="<?= site_url('pengumuman') ?>">Pengumuman</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url() ?>#alur">Alur Pengajuan</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= site_url() ?>#tema">Tema Prioritas</a></li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-login" href="<?= site_url('login') ?>">
                        <i class="bi bi-person-circle me-2"></i>LOGIN
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
