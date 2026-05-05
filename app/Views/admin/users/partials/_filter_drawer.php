<!-- User Filter Drawer -->
<div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="filterDrawer" aria-labelledby="filterDrawerLabel" style="width: 350px;">
    <div class="offcanvas-header bg-primary text-white py-4">
        <h5 class="offcanvas-title fw-bold" id="filterDrawerLabel">
            <i class="bi bi-funnel-fill me-2"></i>Filter Pengguna
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body p-4">
        <form id="drawerFilterForm" data-admin-filter-form data-filter-base-url="<?= esc($viewState['baseUrl']) ?>">
            
            <!-- Peran Section -->
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted mb-2 ls-1 text-uppercase">Peran Pengguna</label>
                <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                    <label class="list-group-item list-group-item-action py-2 px-3 border-0">
                        <input class="form-check-input me-2" type="radio" name="role_id" value="" <?= empty($viewState['selectedRoleId']) ? 'checked' : '' ?> data-filter-param="role_id">
                        <span class="small">Semua Role</span>
                    </label>
                    <?php foreach ($roles as $role): ?>
                        <label class="list-group-item list-group-item-action py-2 px-3 border-0">
                            <input class="form-check-input me-2" type="radio" name="role_id" value="<?= esc((string) $role['id']) ?>" <?= ($viewState['selectedRoleId'] ?? '') == $role['id'] ? 'checked' : '' ?> data-filter-param="role_id">
                            <span class="small"><?= esc($role['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Status Section -->
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted mb-2 ls-1 text-uppercase">Status Akun</label>
                <select class="form-select shadow-none bg-light border-0 py-2 rounded-3" data-filter-param="aktif">
                    <option value="">Semua Status</option>
                    <option value="1" <?= ($viewState['selectedStatus'] ?? '') === '1' ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= ($viewState['selectedStatus'] ?? '') === '0' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <!-- Hidden search to keep context -->
            <input type="hidden" data-filter-param="search" value="<?= esc($viewState['searchValue']) ?>">

        </form>
    </div>

    <div class="offcanvas-footer p-4 border-top bg-light">
        <div class="d-grid gap-2">
            <button type="button" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm" data-filter-submit>
                Terapkan Filter
            </button>
            <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary rounded-pill py-2 border-0">
                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
            </a>
        </div>
    </div>
</div>
