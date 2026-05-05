<!-- Proposal Filter Drawer -->
<div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="filterDrawer" aria-labelledby="filterDrawerLabel" style="width: 350px;">
    <div class="offcanvas-header bg-primary text-white py-4">
        <h5 class="offcanvas-title fw-bold" id="filterDrawerLabel">
            <i class="bi bi-funnel-fill me-2"></i>Filter Proposal
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body p-4">
        <form id="drawerFilterForm" data-admin-filter-form data-filter-base-url="<?= site_url('admin/proposals') ?>">
            
            <!-- Status Section -->
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted mb-2 ls-1 text-uppercase">Status Proposal</label>
                <select class="form-select shadow-none bg-light border-0 py-2 rounded-3" data-filter-param="status">
                    <option value="">Semua Status</option>
                    <option value="submitted" <?= ($viewState['status'] ?? '') === 'submitted' ? 'selected' : '' ?>>Submitted</option>
                    <option value="reviewed" <?= ($viewState['status'] ?? '') === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                    <option value="approved" <?= ($viewState['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= ($viewState['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>

            <!-- Bidang Ilmu Section -->
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted mb-2 ls-1 text-uppercase">Bidang Ilmu</label>
                <select class="form-select shadow-none bg-light border-0 py-2 rounded-3" data-filter-param="bidang_ilmu">
                    <option value="">Semua Bidang Ilmu</option>
                    <?php if (isset($bidangIlmuList)): ?>
                        <?php foreach ($bidangIlmuList as $bi): ?>
                            <option value="<?= esc($bi['id']) ?>" <?= ($viewState['bidang_ilmu'] ?? '') == $bi['id'] ? 'selected' : '' ?>>
                                <?= esc($bi['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Hidden search to keep context -->
            <input type="hidden" data-filter-param="search" value="<?= esc($viewState['search'] ?? '') ?>">

        </form>
    </div>

    <div class="offcanvas-footer p-4 border-top bg-light">
        <div class="d-grid gap-2">
            <button type="button" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm" data-filter-submit>
                Terapkan Filter
            </button>
            <a href="<?= site_url('admin/proposals') ?>" class="btn btn-outline-secondary rounded-pill py-2 border-0">
                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
            </a>
        </div>
    </div>
</div>
