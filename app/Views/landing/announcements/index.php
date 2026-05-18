<?= view('landing/partials/_header') ?>

<style>
    .announcement-header {
        background: var(--primary-emerald);
        padding: 120px 0 60px;
        color: white;
        text-align: center;
        clip-path: ellipse(150% 100% at 50% 0%);
    }
    .announcement-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s;
        height: 100%;
        background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .announcement-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .announcement-img {
        height: 200px;
        object-fit: cover;
    }
    .announcement-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 700;
        color: var(--primary-emerald);
        font-size: 0.8rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>

<?= view('landing/partials/_navbar') ?>

<header class="announcement-header">
    <div class="container animate-up">
        <h1 class="display-4 fw-bold mb-3">Pengumuman Terbaru</h1>
        <p class="opacity-75 mx-auto" style="max-width: 600px;">Dapatkan informasi terkini seputar kegiatan penelitian dan pengabdian masyarakat di lingkungan UINSI Samarinda.</p>
    </div>
</header>

<main class="py-5 bg-light min-vh-100">
    <div class="container py-4">
        <?php if (!empty($announcements)): ?>
            <div class="row g-4">
                <?php foreach ($announcements as $index => $row): ?>
                    <div class="col-lg-4 col-md-6 animate-up" style="animation-delay: <?= 0.1 * ($index + 1) ?>s;">
                        <div class="announcement-card position-relative">
                            <?php if ($row['image']): ?>
                                <img src="<?= route_to('media.serve', 'announcements', $row['image']) ?>" class="announcement-img" alt="<?= esc($row['title']) ?>">
                            <?php else: ?>
                                <div class="announcement-img w-100 d-flex align-items-center justify-content-center bg-emerald-light" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);">
                                    <i class="bi bi-megaphone text-white opacity-25" style="font-size: 5rem;"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="announcement-badge">
                                <i class="bi bi-calendar3 me-1"></i> <?= format_indo($row['created_at']) ?>
                            </div>

                            <div class="p-4">
                                <h5 class="fw-bold mb-3 line-clamp-2"><?= esc($row['title']) ?></h5>
                                <div class="text-muted small mb-4 line-clamp-3">
                                    <?= strip_tags($row['content']) ?>
                                </div>
                                <a href="<?= site_url('pengumuman/' . $row['slug']) ?>" class="btn btn-outline-primary rounded-pill px-4 w-100 fw-bold">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                <?= $pager->links('announcements', 'bootstrap_full') ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5 animate-up">
                <div class="mb-4">
                    <i class="bi bi-journal-x text-muted" style="font-size: 5rem;"></i>
                </div>
                <h3>Belum Ada Pengumuman</h3>
                <p class="text-muted">Pantau terus halaman ini untuk mendapatkan informasi terbaru.</p>
                <a href="<?= site_url() ?>" class="btn btn-primary rounded-pill px-4 mt-3">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?= view('landing/partials/_footer') ?>

<?= view('landing/partials/_scripts') ?>
