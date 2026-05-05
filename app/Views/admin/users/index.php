<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/** @var string $title */
/** @var array{baseUrl:string,totalUsers:int,activeUsers:int,inactiveUsers:int,archivedUsers:int,hasFilters:bool,filterCount:int,searchValue:string,selectedRoleId:int|string,selectedStatus:int|string} $viewState */
/** @var array<int,array{number:int,uuid:string,name:string,username:string,email:string,roles:array<int,string>,isActive:bool,isArchived:bool}> $tableRows */
/** @var array<int,array{id:int,name:string}> $roles */
?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Kelola akun, peran, dan status akses pengguna sistem.',
            'badges' => [
                ['label' => 'Operasional Admin', 'class' => 'text-bg-light border'],
                ['label' => 'User Directory', 'class' => 'text-bg-primary shadow-sm']
            ]
        ]) ?>
    </div>

    <div class="col-md-6 col-xl-3 animate-fade-up delay-1">
        <?= view('components/ui-stat-card', [
            'label' => 'Total User',
            'value' => $viewState['totalUsers'],
            'desc' => 'Akun terdaftar',
            'icon' => 'bi bi-people',
            'colorClass' => 'text-primary'
        ]) ?>
    </div>
    <div class="col-md-6 col-xl-3 animate-fade-up delay-2">
        <?= view('components/ui-stat-card', [
            'label' => 'User Aktif',
            'value' => $viewState['activeUsers'],
            'desc' => 'Memiliki akses login',
            'icon' => 'bi bi-check-circle',
            'colorClass' => 'text-success'
        ]) ?>
    </div>
    <div class="col-md-6 col-xl-3 animate-fade-up delay-3">
        <?= view('components/ui-stat-card', [
            'label' => 'Nonaktif',
            'value' => $viewState['inactiveUsers'],
            'desc' => 'Akses ditangguhkan',
            'icon' => 'bi bi-dash-circle',
            'colorClass' => 'text-secondary'
        ]) ?>
    </div>
    <div class="col-md-6 col-xl-3 animate-fade-up delay-3">
        <?= view('components/ui-stat-card', [
            'label' => 'Arsip',
            'value' => $viewState['archivedUsers'],
            'desc' => 'Akun terhapus',
            'icon' => 'bi bi-archive',
            'colorClass' => 'text-danger'
        ]) ?>
    </div>

    <!-- Compact Controls & Filter Trigger -->
    <div class="col-12 mt-4 animate-fade-up" style="animation-delay: 0.4s;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-2">
            <div class="d-flex align-items-center gap-3 flex-grow-1" style="max-width: 500px;">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border bg-white">
                    <span class="input-group-text bg-white border-0 text-muted ps-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="mainSearchInput" class="form-control border-0 shadow-none py-2" 
                           placeholder="Cari nama, username, email..." 
                           value="<?= esc($viewState['searchValue']) ?>"
                           onkeypress="if(event.key === 'Enter') document.querySelector('[data-filter-submit-main]').click()">
                    <button class="btn btn-primary px-4 fw-bold" type="button" data-filter-submit-main>Cari</button>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button class="btn btn-white border rounded-pill px-4 shadow-sm position-relative fw-semibold" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer">
                    <i class="bi bi-funnel me-2 text-primary"></i>Filter
                    <?php if ($viewState['hasFilters']): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem;">
                            <?= esc((string) $viewState['filterCount']) ?>
                        </span>
                    <?php endif; ?>
                </button>
                
                <?php if ($viewState['hasFilters']): ?>
                    <a href="<?= site_url('admin/users') ?>" class="btn btn-light rounded-pill px-3 shadow-sm text-muted" title="Hapus Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- The Drawer (Partial) -->
    <?= view('admin/users/partials/_filter_drawer', ['roles' => $roles, 'viewState' => $viewState]) ?>

    <div class="col-12">
        <?php ob_start(); ?>
        <thead class="table-light">
            <tr>
                <th style="width:50px" class="text-center">#</th>
                <th>Informasi Pengguna</th>
                <th style="width:250px">Peran & Akses</th>
                <th style="width:120px" class="text-center">Status</th>
                <th style="width:140px" class="text-center">Aksi</th>
            </tr>
        </thead>
        <?php $header = ob_get_clean(); ?>

        <?php ob_start(); ?>
        <?php foreach ($tableRows as $row): ?>
            <tr>
                <td class="text-center text-muted small"><?= esc((string) $row['number']) ?></td>
                <td>
                    <div class="fw-bold text-dark mb-1"><?= esc($row['name']) ?></div>
                    <div class="small text-muted d-flex align-items-center gap-2">
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-person small"></i><?= esc($row['username']) ?></span>
                        <span class="text-muted opacity-50">|</span>
                        <span class="d-flex align-items-center gap-1"><i class="bi bi-envelope small"></i><?= esc($row['email']) ?></span>
                    </div>
                </td>
                <td>
                    <?php if (!empty($row['roles'])): ?>
                        <div class="d-flex flex-wrap gap-1">
                            <?php foreach ($row['roles'] as $roleName): ?>
                                <span class="badge text-bg-light border px-2 py-1 rounded-pill small fw-normal"><?= esc($roleName) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <span class="text-muted small italic">— No roles assigned —</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if ($row['isArchived']): ?>
                        <span class="badge text-bg-danger px-2 py-1 rounded-pill small">
                            <i class="bi bi-archive me-1"></i>Arsip
                        </span>
                    <?php elseif ($row['isActive']): ?>
                        <span class="badge text-bg-success px-2 py-1 rounded-pill small">
                            <i class="bi bi-check-circle me-1"></i>Aktif
                        </span>
                    <?php else: ?>
                        <span class="badge text-bg-secondary px-2 py-1 rounded-pill small">
                            <i class="bi bi-dash-circle me-1"></i>Nonaktif
                        </span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="<?= site_url('admin/users/edit/' . $row['uuid']) ?>" class="btn-action-sm bg-warning-soft text-warning shadow-sm" title="Edit Profil">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="<?= site_url('admin/users/resetPassword/' . $row['uuid']) ?>" class="btn-action-sm bg-info-soft text-info shadow-sm" title="Reset Password">
                            <i class="bi bi-key-fill"></i>
                        </a>
                        <?php if (!$row['isArchived']): ?>
                            <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete"
                                data-href="<?= site_url('admin/users/delete/' . $row['uuid']) ?>"
                                data-delete-label="<?= esc($row['name']) ?>"
                                data-delete-desc="Akses pengguna akan ditangguhkan."
                                title="Hapus">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn-action-sm bg-success-soft text-success shadow-sm btn-admin-restore"
                                data-href="<?= site_url('admin/users/restore/' . $row['uuid']) ?>"
                                data-confirm-title="Pulihkan user ini?"
                                data-confirm-html="User <strong><?= esc($row['name']) ?></strong> akan diaktifkan kembali."
                                data-confirm-button="Ya, pulihkan"
                                title="Pulihkan">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php $body = ob_get_clean(); ?>

        <?= view('components/ui-table-card', [
            'tableId' => 'dt-users',
            'header' => $header,
            'body' => $body,
            'type' => 'admin',
            'title' => 'Direktori Pengguna',
            'icon' => 'bi bi-people-fill',
            'actions' => [
                [
                    'label' => 'Tambah User',
                    'url' => site_url('admin/users/create'),
                    'icon' => 'bi bi-person-plus-fill',
                    'class' => 'btn btn-primary btn-sm rounded-pill px-3 shadow-sm'
                ]
            ]
        ]) ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('custom/js/admin-filter-drawer.js') ?>"></script>
<?= $this->endSection() ?>
