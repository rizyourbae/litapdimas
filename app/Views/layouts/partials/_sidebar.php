<?php
// Ambil variabel dari controller, jika tidak ada, pakai array kosong
$sidebarMenu = $sidebarMenu ?? [];

// Jika sidebarMenu kosong, biarkan kosong — jangan tampilkan menu palsu statis.
// Sidebar yang kosong adalah sinyal bahwa renderView() belum dipanggil di controller.
?>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="<?= site_url() ?>" class="brand-link">
            <img src="<?= base_url('assets/adminlte/assets/img/logo/logo-uinsi.png') ?>"
                alt="Logo" class="brand-image opacity-75 shadow">
            <span class="brand-text fw-light">LITAPDIMAS</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <!-- Sidebar Search -->
        <div class="sidebar-search-container px-3 mt-3 mb-2">
            <div class="input-group search-group">
                <span class="input-group-text border-0 bg-transparent text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="sidebar-menu-search" class="form-control border-0 bg-transparent text-light" placeholder="Cari menu..." aria-label="Search menu">
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">
                <?= render_menu($sidebarMenu) ?>
            </ul>
        </nav>
    </div>
</aside>

<script src="<?= base_url('custom/js/sidebar-search.js?v=' . time()) ?>"></script>