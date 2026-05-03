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
            ],
            'actions' => [
                [
                    'label' => 'Tambah User',
                    'class' => 'btn btn-primary rounded-pill px-4 shadow-sm',
                    'icon' => 'bi bi-plus-lg',
                    'url' => site_url('admin/users/create')
                ]
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

    <div class="col-12 mt-4 animate-fade-up" style="animation-delay: 0.4s;">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-light py-3 px-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-funnel-fill me-2 text-primary"></i>Filter Pengguna
                    </h5>
                    <?php if ($viewState['hasFilters']): ?>
                        <span class="badge text-bg-warning-soft text-warning px-3 py-2 rounded-pill border border-warning border-opacity-25">
                            <i class="bi bi-filter-circle-fill me-1"></i><?= esc((string) $viewState['filterCount']) ?> Filter Aktif
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body p-4">
                <form class="row g-3 align-items-end" data-admin-filter-form data-filter-base-url="<?= esc($viewState['baseUrl']) ?>">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1 ls-1">PERAN PENGGUNA</label>
                        <select id="filterRole" class="form-select shadow-none bg-light border-0 py-2 rounded-3" data-filter-param="role_id">
                            <option value="">-- Semua Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= esc((string) $role['id']) ?>" <?= ($viewState['selectedRoleId'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                                    <?= esc($role['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1 ls-1">STATUS AKUN</label>
                        <select class="form-select shadow-none bg-light border-0 py-2 rounded-3" data-filter-param="aktif">
                            <option value="">-- Semua --</option>
                            <option value="1" <?= ($viewState['selectedStatus'] ?? '') === '1' ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= ($viewState['selectedStatus'] ?? '') === '0' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted mb-1 ls-1">KATA KUNCI</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="filterSearch" class="form-control bg-light border-0 shadow-none py-2" data-filter-param="search" placeholder="Cari nama, username, email..." value="<?= esc($viewState['searchValue']) ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary rounded-pill flex-grow-1 fw-bold shadow-sm" data-filter-submit>
                                Terapkan
                            </button>
                            <a href="<?= site_url('admin/users') ?>" class="btn btn-light rounded-pill px-3" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
            'type' => 'admin'
        ]) ?>
</div>

<?= $this->endSection() ?>