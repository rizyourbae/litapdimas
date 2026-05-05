<?php
/**
 * Filter Drawer for Publikasi
 * 
 * @var array $viewState
 * @var array $filterOptions
 */
?>

<div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="filterDrawer" aria-labelledby="filterDrawerLabel" style="width: 400px;">
    <div class="offcanvas-header bg-light py-3 px-4">
        <h5 class="offcanvas-title fw-bold d-flex align-items-center" id="filterDrawerLabel">
            <i class="bi bi-funnel-fill text-primary me-2"></i>Filter Publikasi
        </h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body p-4">
        <form action="<?= site_url('admin/publikasi') ?>" method="get" id="filterForm">
            <!-- Hidden Search to preserve it -->
            <input type="hidden" name="search" value="<?= esc($viewState['search'] ?? '') ?>">

            <!-- JENIS PUBLIKASI -->
            <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase mb-2" style="letter-spacing: 1px;">Jenis Publikasi</label>
                <div class="d-flex flex-wrap gap-2">
                    <input type="radio" class="btn-check" name="jenis_publikasi" id="jenis_all" value="" <?= empty($viewState['jenis_publikasi']) ? 'checked' : '' ?>>
                    <label class="btn btn-outline-primary btn-sm rounded-pill px-3" for="jenis_all">Semua</label>
                    
                    <?php foreach ($filterOptions['jenis'] as $jenis): ?>
                        <input type="radio" class="btn-check" name="jenis_publikasi" id="jenis_<?= strtolower($jenis) ?>" 
                            value="<?= esc($jenis) ?>" <?= ($viewState['jenis_publikasi'] === $jenis) ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary btn-sm rounded-pill px-3" for="jenis_<?= strtolower($jenis) ?>"><?= esc($jenis) ?></label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- TAHUN -->
            <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase mb-2" style="letter-spacing: 1px;">Tahun Publikasi</label>
                <select name="tahun" class="form-select form-select-lg fs-6 shadow-none border-2">
                    <option value="">-- Pilih Tahun --</option>
                    <?php foreach ($filterOptions['tahun'] as $tahun): ?>
                        <option value="<?= esc($tahun) ?>" <?= ((string)$viewState['tahun'] === (string)$tahun) ? 'selected' : '' ?>>
                            <?= esc($tahun) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mt-5 d-grid gap-2">
                <button type="submit" class="btn btn-primary py-3 fw-bold rounded-4 shadow-sm">
                    <i class="bi bi-check2-circle me-2"></i>Terapkan Filter
                </button>
                <a href="<?= site_url('admin/publikasi') ?>" class="btn btn-light py-3 fw-bold rounded-4 text-muted">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filter
                </a>
            </div>
        </form>
    </div>

    <div class="offcanvas-footer p-4 border-top bg-light">
        <p class="small text-muted mb-0">
            <i class="bi bi-info-circle me-1"></i> Filter akan menyaring data publikasi berdasarkan kriteria yang dipilih.
        </p>
    </div>
</div>
