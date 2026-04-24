<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$activeTab = session()->getFlashdata('active_tab') ?? 'users';
$openModal = session()->getFlashdata('open_modal') ?? null;
$errors    = session()->getFlashdata('errors') ?? [];
$BASE_URL  = site_url('admin/users');
?>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i><?= $title ?>
                    </h3>
                    <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Tambah User
                    </a>
                </div>
            </div>

            <div class="card-body">

                <!-- Filter Section -->
                <div class="row g-2 mb-3">
                    <div class="col-auto">
                        <select id="filterRole" class="form-select form-select-sm" style="min-width: 180px">
                            <option value="">-- Semua Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>" <?= ($filters['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                                    <?= esc($role['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="filterSearch" class="form-control form-control-sm" placeholder="Cari nama, username, email..."
                            value="<?= esc($filters['search'] ?? '') ?>" style="min-width: 250px">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-secondary btn-sm" id="btnFilter">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-lg me-1"></i>Reset
                        </a>
                    </div>
                </div>

                <!-- Skeleton + Real table wrapper -->
                <div class="dt-skeleton-wrap">

                    <!-- Skeleton overlay -->
                    <div class="dt-skeleton-overlay" id="sk-users">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="width:110px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ([70, 55, 65, 50, 80, 45] as $w): ?>
                                    <tr>
                                        <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                        <td><span class="skeleton-line" style="width:<?= $w ?>%"></span></td>
                                        <td><span class="skeleton-line" style="width:60%"></span></td>
                                        <td><span class="skeleton-line" style="width:55%;border-radius:20px;height:20px"></span></td>
                                        <td class="text-center"><span class="skeleton-line mx-auto" style="width:50px;border-radius:20px;height:20px"></span></td>
                                        <td class="text-center"><span class="skeleton-btn me-1"></span><span class="skeleton-btn"></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Real DataTable -->
                    <div class="dt-real-wrap" id="rw-users">
                        <table id="dt-users" class="table table-hover table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px" class="text-center">#</th>
                                    <th>Nama Lengkap</th>
                                    <th>Username / Email</th>
                                    <th>Role</th>
                                    <th style="width:100px" class="text-center">Status</th>
                                    <th style="width:110px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $no => $user): ?>
                                    <tr>
                                        <td class="text-center"><?= $no + 1 ?></td>
                                        <td>
                                            <strong><?= esc($user['nama_lengkap']) ?></strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <div><?= esc($user['username']) ?></div>
                                                <div><?= esc($user['email']) ?></div>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if (!empty($user['role_names'])): ?>
                                                <div>
                                                    <?php foreach (explode(', ', $user['role_names']) as $rname): ?>
                                                        <span class="badge bg-light text-dark border me-1"><?= esc($rname) ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($user['aktif']): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-dash-circle me-1"></i>Nonaktif
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($user['deleted_at'])): ?>
                                                <br>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-archive me-1"></i>Dihapus
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center btn-action-group">
                                            <a href="<?= site_url('admin/users/edit/' . $user['uuid']) ?>"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php if (empty($user['deleted_at'])): ?>
                                                <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                    data-href="<?= site_url('admin/users/delete/' . $user['uuid']) ?>"
                                                    data-nama="<?= esc($user['nama_lengkap']) ?>"
                                                    title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="#" class="btn btn-success btn-sm btn-restore"
                                                    data-href="<?= site_url('admin/users/restore/' . $user['uuid']) ?>"
                                                    data-nama="<?= esc($user['nama_lengkap']) ?>"
                                                    title="Pulihkan">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function() {
        'use strict';

        const BASE_URL = '<?= $BASE_URL ?>';
        const DT_OPTS = {
            columnDefs: [{
                orderable: false,
                targets: [0, 5]
            }]
        };

        // =============================================
        // DataTables — init
        // =============================================
        DtManager.initLazy('dt-users', DT_OPTS, 'sk-users', 'rw-users');

        // =============================================
        // Filter button
        // =============================================
        document.getElementById('btnFilter').addEventListener('click', function() {
            const roleId = document.getElementById('filterRole').value;
            const search = document.getElementById('filterSearch').value;
            const params = new URLSearchParams();
            if (roleId) params.append('role_id', roleId);
            if (search) params.append('search', search);
            window.location.href = BASE_URL + (params.toString() ? '?' + params.toString() : '');
        });

        // Allow Enter key in search input
        document.getElementById('filterSearch').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') document.getElementById('btnFilter').click();
        });

        // =============================================
        // Tombol Hapus → SweetAlert
        // =============================================
        document.querySelectorAll('.btn-delete').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                SwalDelete(this.dataset.href, this.dataset.nama, 'User akan dinonaktifkan dan dapat dipulihkan kembali.');
            });
        });

        // =============================================
        // Tombol Restore → SweetAlert
        // =============================================
        document.querySelectorAll('.btn-restore').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                SwalConfirm(
                    this.dataset.href,
                    'Pulihkan user ini?',
                    `User <strong>${this.dataset.nama}</strong> akan diaktifkan kembali.`,
                    '<i class="bi bi-arrow-counterclockwise me-1"></i>Ya, pulihkan!'
                );
            });
        });

    })();
</script>
<?= $this->endSection() ?>