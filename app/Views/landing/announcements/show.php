<?= view('landing/partials/_header') ?>

<style>
    .detail-header {
        background: var(--primary-emerald);
        padding: 140px 0 80px;
        color: white;
        position: relative;
    }
    .content-card {
        background: white;
        border-radius: 30px;
        margin-top: -60px;
        padding: 50px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        position: relative;
        z-index: 10;
    }
    .article-content {
        line-height: 1.8;
        font-size: 1.1rem;
        color: #334155;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 15px;
    }
    .attachment-box {
        background: #f1f5f9;
        border-radius: 20px;
        padding: 25px;
        border: 2px dashed #cbd5e1;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-top: 40px;
    }
    .meta-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-right: 25px;
        font-size: 0.9rem;
        opacity: 0.8;
    }
    @media (max-width: 768px) {
        .content-card { padding: 30px 20px; margin-top: -40px; }
    }
</style>

<?= view('landing/partials/_navbar') ?>

<div class="detail-header">
    <div class="container animate-up">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url() ?>" class="text-white opacity-75 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('pengumuman') ?>" class="text-white opacity-75 text-decoration-none">Pengumuman</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Detail</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold mb-0"><?= esc($row['title']) ?></h1>
    </div>
</div>

<main class="bg-light pb-5 min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <article class="content-card animate-up" style="animation-delay: 0.2s;">
                    <div class="mb-4 pb-4 border-bottom">
                        <div class="meta-item"><i class="bi bi-calendar3 text-primary"></i> <?= format_indo($row['created_at']) ?></div>
                        <div class="meta-item"><i class="bi bi-eye text-primary"></i> <?= number_format($row['view_count']) ?> Dilihat</div>
                        <div class="meta-item"><i class="bi bi-person text-primary"></i> Administrator</div>
                    </div>

                    <?php if ($row['image']): ?>
                        <div class="mb-5">
                            <img src="<?= route_to('media.serve', 'announcements', $row['image']) ?>" class="w-100 rounded" alt="<?= esc($row['title']) ?>">
                        </div>
                    <?php endif; ?>

                    <div class="article-content">
                        <?= $row['content'] ?>
                    </div>

                    <?php if ($row['file_attachment']): ?>
                        <div class="attachment-box">
                            <div class="fs-1 text-primary">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Dokumen Lampiran</h6>
                                <p class="text-muted small mb-0">Silakan unduh file untuk informasi lebih detail.</p>
                            </div>
                            <a href="<?= route_to('media.serve', 'attachments', $row['file_attachment']) ?>" class="btn btn-primary rounded-pill px-4" target="_blank">
                                <i class="bi bi-download me-2"></i> Unduh File
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="mt-5 pt-4 border-top">
                        <a href="<?= site_url('pengumuman') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</main>

<?= view('landing/partials/_footer') ?>

<?= view('landing/partials/_scripts') ?>
