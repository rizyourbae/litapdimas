<!-- Stats Section -->
<section class="stats-container container">
    <div class="row g-4 justify-content-center">
        <div class="col-md-4 col-lg-3 animate-up" style="animation-delay: 0.2s;">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                <span class="stat-number"><?= number_format($stats['total_proposal'] ?? 0, 0, ',', '.') ?>+</span>
                <span class="stat-label">Proposal Masuk</span>
            </div>
        </div>
        <div class="col-md-4 col-lg-3 animate-up" style="animation-delay: 0.3s;">
            <div class="stat-card border-warning" style="border-width: 2px !important;">
                <div class="stat-icon text-warning"><i class="bi bi-people-fill"></i></div>
                <span class="stat-number"><?= number_format($stats['total_peneliti'] ?? 0, 0, ',', '.') ?>+</span>
                <span class="stat-label">Peneliti Aktif</span>
            </div>
        </div>
        <div class="col-md-4 col-lg-3 animate-up" style="animation-delay: 0.4s;">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
                <span class="stat-number"><?= number_format($stats['total_publikasi'] ?? 0, 0, ',', '.') ?>+</span>
                <span class="stat-label">Publikasi Terindeks</span>
            </div>
        </div>
    </div>
</section>
