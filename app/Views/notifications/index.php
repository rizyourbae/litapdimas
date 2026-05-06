<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <!-- HERO SECTION -->
    <div class="col-12 mb-2 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => 'Pusat Notifikasi',
            'subtitle' => 'Pantau semua aktivitas dan pembaruan sistem untuk akun Anda',
            'badges' => [
                ['label' => 'User', 'class' => 'text-bg-light border'],
                ['label' => 'Notifikasi', 'class' => 'text-bg-primary shadow-sm']
            ]
        ]) ?>
    </div>

    <!-- NOTIFICATION LIST -->
    <div class="col-12 animate-fade-up delay-1">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon-sm bg-primary-soft text-primary rounded-circle">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold">Daftar Notifikasi</h5>
                    </div>
                    <a href="<?= site_url('notifications/mark-all-read') ?>" class="btn btn-light btn-sm rounded-pill px-3 border shadow-sm">
                        <i class="bi bi-check2-all me-1"></i> Tandai Semua Terbaca
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($notifications)): ?>
                    <div class="p-5 text-center">
                        <div class="mb-4">
                            <i class="bi bi-bell-slash display-1 text-muted opacity-25"></i>
                        </div>
                        <h4 class="text-dark fw-bold">Belum Ada Notifikasi</h4>
                        <p class="text-muted mx-auto" style="max-width: 400px;">
                            Semua pemberitahuan mengenai status proposal, tugas review, dan informasi lainnya akan muncul di sini.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($notifications as $notif): ?>
                            <div class="list-group-item list-group-item-action p-4 border-bottom <?= $notif->is_read ? 'opacity-75 bg-white' : 'bg-light-soft border-start border-primary border-4' ?>">
                                <div class="d-flex gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="stat-icon bg-<?= $notif->type ?>-soft text-<?= $notif->type ?> rounded-circle">
                                            <i class="bi <?= $notif->type === 'success' ? 'bi-check-circle' : ($notif->type === 'warning' ? 'bi-exclamation-triangle' : ($notif->type === 'danger' ? 'bi-x-circle' : 'bi-info-circle')) ?> fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="fw-bold text-dark mb-0"><?= esc($notif->title) ?></h6>
                                            <small class="text-muted"><i class="bi bi-clock me-1"></i> <?= date('d M Y, H:i', strtotime($notif->created_at)) ?></small>
                                        </div>
                                        <p class="text-muted mb-3" style="max-width: 800px;"><?= esc($notif->message) ?></p>
                                        <?php if ($notif->link): ?>
                                            <a href="<?= site_url('notifications/read/' . $notif->id) ?>" class="btn btn-primary-soft btn-sm rounded-pill px-4 fw-bold">
                                                Buka Tautan <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        <?php elseif (!$notif->is_read): ?>
                                            <a href="<?= site_url('notifications/read/' . $notif->id) ?>" class="btn btn-light btn-sm rounded-pill px-4 border">
                                                Tandai Terbaca
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="p-4 border-top">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-soft { background-color: rgba(var(--bs-light-rgb), 0.5); }
    .btn-primary-soft {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
        border: none;
    }
    .btn-primary-soft:hover {
        background-color: var(--bs-primary);
        color: white;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<?= $this->endSection() ?>
