<!-- Themes Section -->
<section class="py-5 bg-light" id="tema">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-3">
            <div class="section-title text-start mb-0">
                <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 2px;">Research Priorities</span>
                <h2>Tema Prioritas Riset</h2>
            </div>
            <a href="#" class="btn btn-outline-primary rounded-pill px-4">Lihat Selengkapnya <i class="bi bi-arrow-right ms-2"></i></a>
        </div>

        <div class="row g-3">
            <?php foreach ($temaRiset as $index => $t): ?>
                <div class="col-lg-3 col-md-6 animate-up" style="animation-delay: <?= 0.1 * ($index + 1) ?>s;">
                    <div class="theme-card d-flex align-items-center gap-3">
                        <div class="fs-4"><i class="<?= esc($t['icon']) ?>"></i></div>
                        <h6><?= esc($t['nama']) ?></h6>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="bg-primary p-5 rounded-5 text-center text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 100%) !important;">
            <div class="position-absolute top-0 start-0 opacity-10" style="transform: translate(-20%, -20%) scale(2.5);">
                <i class="bi bi-mortarboard" style="font-size: 15rem;"></i>
            </div>
            <div class="position-relative z-1">
                <h2 class="fw-bold mb-3">Siap Untuk Berkontribusi?</h2>
                <p class="opacity-75 mb-4 mx-auto" style="max-width: 600px;">Bergabunglah dengan ribuan peneliti lainnya dan wujudkan kontribusi nyata bagi masyarakat.</p>
                <a href="<?= site_url('register') ?>" class="btn btn-warning btn-lg px-5 rounded-pill fw-bold">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</section>
