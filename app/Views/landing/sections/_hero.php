<!-- Hero Section -->
<section class="hero-section">
    <?php if (!empty($banners)): ?>
        <!-- Dynamic Carousel -->
        <div id="heroCarousel" class="carousel slide carousel-fade h-100" data-bs-ride="carousel">
            <div class="carousel-indicators" style="z-index: 20; bottom: 40px;">
                <?php foreach ($banners as $index => $banner): ?>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" 
                            class="<?= $index === 0 ? 'active' : '' ?> rounded-circle" style="width: 10px; height: 10px;" aria-current="true"></button>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner h-100">
                <?php foreach ($banners as $index => $banner): ?>
                    <div class="carousel-item h-100 <?= $index === 0 ? 'active' : '' ?>" data-bs-interval="6000">
                        <!-- Banner Image -->
                        <div class="hero-bg position-absolute top-0 start-0 w-100 h-100" 
                             style="background: url('<?= base_url($banner['image']) ?>') no-repeat center center / cover;">
                        </div>
                        
                        <!-- Subtle Overlay (Only show if there is text) -->
                        <?php if ($banner['title'] || $banner['description']): ?>
                            <div class="hero-overlay"></div>
                        <?php endif; ?>

                        <div class="container h-100 hero-content-wrapper">
                            <div class="col-lg-7 hero-content animate-up">
                                <?php if ($banner['title']): ?>
                                    <h1 class="display-3 fw-bold mb-3 text-shadow"><?= esc($banner['title']) ?></h1>
                                <?php endif; ?>
                                
                                <?php if ($banner['description']): ?>
                                    <p class="lead mb-4 opacity-90 text-shadow"><?= esc($banner['description']) ?></p>
                                <?php endif; ?>

                                <?php if ($banner['link_url']): ?>
                                    <div class="d-flex gap-3">
                                        <a href="<?= esc($banner['link_url']) ?>" class="btn btn-cta-primary shadow-lg">
                                            Lihat Selengkapnya <i class="bi bi-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Controls with better visibility -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="z-index: 20; width: 8%;">
                <div class="bg-dark bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 20px;"></span>
                </div>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="z-index: 20; width: 8%;">
                <div class="bg-dark bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="width: 20px;"></span>
                </div>
            </button>
        </div>
    <?php else: ?>
        <!-- Fallback Static Hero -->
        <div class="hero-bg position-absolute top-0 start-0 w-100 h-100" 
             style="background: linear-gradient(135deg, rgba(6, 78, 59, 0.85) 0%, rgba(4, 120, 87, 0.6) 100%), url('<?= base_url('assets/adminlte/assets/img/logo/UINSI.jpeg') ?>') no-repeat center center / cover;">
        </div>
        <div class="container h-100 position-relative d-flex align-items-center hero-content-wrapper">
            <div class="col-lg-7 hero-content animate-up">
                <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-2 mb-3 shadow-sm">Portal Resmi Litapdimas 2024</span>
                <h1><?= esc($hero['hero_title'] ?? 'Sistem Informasi Penelitian & Pengabdian Masyarakat') ?></h1>
                <p><?= esc($hero['hero_subtitle'] ?? 'Platform terintegrasi untuk pengelolaan, review, dan publikasi hasil penelitian akademik.') ?></p>
                <div class="d-flex flex-wrap">
                    <a href="<?= site_url('register') ?>" class="btn btn-cta-primary">
                        <i class="bi bi-rocket-takeoff me-2"></i><?= esc($hero['hero_cta_primary'] ?? 'Mulai Pengajuan') ?>
                    </a>
                    <a href="#" class="btn btn-cta-outline">
                        <i class="bi bi-journal-text me-2"></i><?= esc($hero['hero_cta_outline'] ?? 'Panduan Juknis') ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
