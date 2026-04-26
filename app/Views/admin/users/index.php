<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Operasional Admin</span>
                            <span class="badge text-bg-primary px-3 py-2">User Directory</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($title) ?></h2>
                        <p class="admin-hero__subtitle mb-0">Kelola akun, peran, dan status pengguna</p>
                    </div>
                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Tambah User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xl-3">
        <div class="card admin-metric-card h-100">
            <div class="card-body">
                <div class="small text-uppercase text-muted mb-2">Total User</div>
                <div class="admin-metric-card__value"><?= esc((string) $viewState['totalUsers']) ?></div>
                <div class="text-muted small">Semua akun terdaftar saat ini.</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-3">
        <div class="card admin-metric-card h-100">
            <div class="card-body">
                <div class="small text-uppercase text-muted mb-2">Aktif</div>
                <div class="admin-metric-card__value text-success"><?= esc((string) $viewState['activeUsers']) ?></div>
                <div class="text-muted small">User aktif yang bisa masuk sistem.</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-3">
        <div class="card admin-metric-card h-100">
            <div class="card-body">
                <div class="small text-uppercase text-muted mb-2">Nonaktif</div>
                <div class="admin-metric-card__value text-secondary"><?= esc((string) $viewState['inactiveUsers']) ?></div>
                <div class="text-muted small">Akun dinonaktifkan sementara.</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-3">
        <div class="card admin-metric-card h-100">
            <div class="card-body">
                <div class="small text-uppercase text-muted mb-2">Diarsipkan</div>
                <div class="admin-metric-card__value text-danger"><?= esc((string) $viewState['archivedUsers']) ?></div>
                <div class="text-muted small">Soft deleted dan bisa dipulihkan.</div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline admin-table-card">
            <div class="card-header border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i><?= esc($title) ?>
                    </h3>
                    <?php if ($viewState['hasFilters']): ?>
                        <span class="badge text-bg-light border">Filter aktif: <?= esc((string) $viewState['filterCount']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <form class="admin-filter-bar row g-2 mb-4" data-admin-filter-form data-filter-base-url="<?= esc($viewState['baseUrl']) ?>">
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label small text-muted fw-semibold mb-1">Filter Role</label>
                        <select id="filterRole" class="form-select form-select-sm" data-filter-param="role_id">
                            <option value="">-- Semua Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= esc((string) $role['id']) ?>" <?= ($viewState['selectedRoleId'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                                    <?= esc($role['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label small text-muted fw-semibold mb-1">Status</label>
                        <select class="form-select form-select-sm" data-filter-param="aktif">
                            <option value="">-- Semua Status --</option>
                            <option value="1" <?= ($viewState['selectedStatus'] ?? '') === '1' ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= ($viewState['selectedStatus'] ?? '') === '0' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label small text-muted fw-semibold mb-1">Pencarian</label>
                        <input type="text" id="filterSearch" class="form-control form-control-sm" data-filter-param="search" placeholder="Cari nama, username, email..."
                            value="<?= esc($viewState['searchValue']) ?>">
                    </div>
                    <div class="col-lg-2 d-flex align-items-end gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm flex-grow-1" data-filter-submit>
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-lg me-1"></i>Reset
                        </a>
                    </div>
                </form>

                <div class="dt-skeleton-wrap">
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

                    <div class="dt-real-wrap" id="rw-users">
                        <table id="dt-users" class="table table-hover table-bordered align-middle w-100" data-admin-datatable
                            data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,5]}]}'
                            data-skeleton-id="sk-users" data-real-wrap-id="rw-users">
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
                                <?php foreach ($tableRows as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= esc((string) $row['number']) ?></td>
                                        <td><strong><?= esc($row['name']) ?></strong></td>
                                        <td>
                                            <small class="text-muted">
                                                <div><?= esc($row['username']) ?></div>
                                                <div><?= esc($row['email']) ?></div>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if (!empty($row['roles'])): ?>
                                                <div>
                                                    <?php foreach ($row['roles'] as $roleName): ?>
                                                        <span class="badge bg-light text-dark border me-1"><?= esc($roleName) ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['isActive']): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-dash-circle me-1"></i>Nonaktif
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($row['isArchived']): ?>
                                                <br>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-archive me-1"></i>Dihapus
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center btn-action-group admin-action-group">
                                            <a href="<?= site_url('admin/users/edit/' . $row['uuid']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?= site_url('admin/users/resetPassword/' . $row['uuid']) ?>" class="btn btn-outline-secondary btn-sm" title="Reset Password">
                                                <i class="bi bi-key"></i>
                                            </a>
                                            <?php if (!$row['isArchived']): ?>
                                                <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                    data-href="<?= site_url('admin/users/delete/' . $row['uuid']) ?>"
                                                    data-delete-label="<?= esc($row['name']) ?>"
                                                    data-delete-desc="User akan dinonaktifkan dan dapat dipulihkan kembali."
                                                    title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="#" class="btn btn-success btn-sm btn-admin-restore"
                                                    data-href="<?= site_url('admin/users/restore/' . $row['uuid']) ?>"
                                                    data-confirm-title="Pulihkan user ini?"
                                                    data-confirm-html="User <strong><?= esc($row['name']) ?></strong> akan diaktifkan kembali."
                                                    data-confirm-button="Ya, pulihkan"
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