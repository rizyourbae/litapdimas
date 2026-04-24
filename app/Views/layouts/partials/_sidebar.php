<?php
// $menu adalah array yang dikirim dari controller, untuk sementara kita definisikan default
$menu = $menu ?? [];
// Fallback menu statis untuk testing
if (empty($menu)) {
    $menu = [
        ['label' => 'Dashboard', 'icon' => 'bi-speedometer', 'url' => site_url('admin'), 'active' => true],
        ['label' => 'Manajemen User', 'icon' => 'bi-people', 'url' => site_url('admin/users')],
        ['label' => 'Proposal', 'icon' => 'bi-file-earmark-text', 'url' => site_url('admin/proposals')],
    ];
}
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