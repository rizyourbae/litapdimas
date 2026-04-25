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
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">
                <?= render_menu($sidebarMenu) ?>
            </ul>
        </nav>
    </div>
</aside>