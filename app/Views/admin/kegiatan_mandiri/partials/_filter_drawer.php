<?php
/**
 * Filter Drawer for Kegiatan Mandiri
 * 
 * @var array $viewState
 * @var array $filterOptions
 */
?>

<div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="filterDrawer" aria-labelledby="filterDrawerLabel" style="width: 400px;">
    <div class="offcanvas-header bg-light py-3 px-4">
        <h5 class="offcanvas-title fw-bold d-flex align-items-center" id="filterDrawerLabel">
            <i class="bi bi-funnel-fill text-primary me-2"></i>Filter Kegiatan
        </h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body p-4">
        <form action="<?= site_url('admin/kegiatan-mandiri') ?>" method="get" id="filterForm">
            <!-- Hidden Search to preserve it -->
            <input type="hidden" name="search" value="<?= esc($viewState['search'] ?? '') ?>">

            <!-- JENIS KEGIATAN -->
            <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase mb-2" style="letter-spacing: 1px;">Jenis Kegiatan</label>
                <div class="d-flex flex-wrap gap-2">
                    <input type="radio" class="btn-check" name="jenis_kegiatan" id="jenis_all" value="" <?= empty($viewState['jenis_kegiatan']) ? 'checked' : '' ?>>
                    <label class="btn btn-outline-primary btn-sm rounded-pill px-3" for="jenis_all">Semua</label>
                    
                    <?php foreach ($filterOptions['jenis'] as $jenis): ?>
                        <input type="radio" class="btn-check" name="jenis_kegiatan" id="jenis_<?= str_replace(['/', ' '], '_', strtolower($jenis)) ?>" 
                            value="<?= esc($jenis) ?>" <?= ($viewState['jenis_kegiatan'] === $jenis) ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary btn-sm rounded-pill px-3" for="jenis_<?= str_replace(['/', ' '], '_', strtolower($jenis)) ?>"><?= esc($jenis) ?></label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- KLASTER / SKALA -->
            <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase mb-2" style="letter-spacing: 1px;">Klaster / Skala</label>
                <div class="d-flex flex-wrap gap-2">
                    <input type="radio" class="btn-check" name="klaster_skala_kegiatan" id="klaster_all" value="" <?= empty($viewState['klaster_skala_kegiatan']) ? 'checked' : '' ?>>
                    <label class="btn btn-outline-info btn-sm rounded-pill px-3" for="klaster_all">Semua</label>
                    
                    <?php foreach ($filterOptions['klaster'] as $klaster): ?>
                        <input type="radio" class="btn-check" name="klaster_skala_kegiatan" id="klaster_<?= strtolower($klaster) ?>" 
                            value="<?= esc($klaster) ?>" <?= ($viewState['klaster_skala_kegiatan'] === $klaster) ? 'checked' : '' ?>>
                        <label class="btn btn-outline-info btn-sm rounded-pill px-3" for="klaster_<?= strtolower($klaster) ?>"><?= esc($klaster) ?></label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- TAHUN -->
            <div class="mb-4">
                <label class="form-label fw-bold text-muted small text-uppercase mb-2" style="letter-spacing: 1px;">Tahun Pelaksanaan</label>
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
                <a href="<?= site_url('admin/kegiatan-mandiri') ?>" class="btn btn-light py-3 fw-bold rounded-4 text-muted">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filter
                </a>
            </div>
        </form>
    </div>

    <div class="offcanvas-footer p-4 border-top bg-light text-center">
        <p class="small text-muted mb-0">
            Ditemukan <strong><?= esc((string)count($tableRows)) ?></strong> data yang sesuai.
        </p>
    </div>
</div>
