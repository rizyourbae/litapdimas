<form action="<?= site_url('admin/cms/landing/update-settings') ?>" method="post">
    <?= csrf_field() ?>

    <!-- HERO SECTION MANAGEMENT -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-megaphone me-2"></i>Konfigurasi Hero Banner</h5>
            <hr>
        </div>
        <div class="col-md-12">
            <label class="form-label fw-bold">Judul Utama (Headline)</label>
            <input type="text" name="hero_title" class="form-control form-control-lg border-2 shadow-none" 
                   value="<?= esc($hero['hero_title'] ?? '') ?>" placeholder="Sistem Informasi Penelitian...">
        </div>
        <div class="col-md-12">
            <label class="form-label fw-bold">Sub-judul (Description)</label>
            <textarea name="hero_subtitle" class="form-control border-2 shadow-none" rows="3" 
                      placeholder="Deskripsi singkat sistem..."><?= esc($hero['hero_subtitle'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">Label Tombol Utama</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-2 border-end-0"><i class="bi bi-cursor"></i></span>
                <input type="text" name="hero_cta_primary" class="form-control border-2 border-start-0 shadow-none" 
                       value="<?= esc($hero['hero_cta_primary'] ?? '') ?>">
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">Label Tombol Panduan</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-2 border-end-0"><i class="bi bi-book"></i></span>
                <input type="text" name="hero_cta_outline" class="form-control border-2 border-start-0 shadow-none" 
                       value="<?= esc($hero['hero_cta_outline'] ?? '') ?>">
            </div>
        </div>
    </div>

    <!-- STATISTICS OVERRIDE -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-graph-up-arrow me-2"></i>Basis Angka Statistik</h5>
            <p class="text-muted small">Angka ini akan ditambahkan ke jumlah riil yang ada di database.</p>
            <hr>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Base Proposal</label>
            <input type="number" name="stats_base_proposal" class="form-control border-2 shadow-none" 
                   value="<?= esc($stats['stats_base_proposal'] ?? '0') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Base Peneliti</label>
            <input type="number" name="stats_base_peneliti" class="form-control border-2 shadow-none" 
                   value="<?= esc($stats['stats_base_peneliti'] ?? '0') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bold">Base Publikasi</label>
            <input type="number" name="stats_base_publikasi" class="form-control border-2 shadow-none" 
                   value="<?= esc($stats['stats_base_publikasi'] ?? '0') ?>">
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow">
            <i class="bi bi-check-circle me-2"></i>Simpan Semua Perubahan
        </button>
    </div>
</form>
