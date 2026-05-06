<div class="dropdown notification-dropdown">
    <a class="nav-link position-relative dropdown-toggle no-caret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        <?php if ($unreadCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="padding: 0.35em 0.5em; font-size: 0.65rem;">
                <?= $unreadCount > 99 ? '99+' : $unreadCount ?>
            </span>
        <?php endif; ?>
    </a>
    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 py-0 overflow-hidden mt-3" style="width: 320px;">
        <div class="dropdown-header bg-light py-3 px-4">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark">Notifikasi</h6>
                <span class="badge bg-primary-soft text-primary rounded-pill"><?= $unreadCount ?> Baru</span>
            </div>
        </div>
        <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
            <?php if (empty($latest)): ?>
                <div class="p-5 text-center">
                    <div class="mb-3">
                        <i class="bi bi-bell-slash fs-1 text-muted opacity-25"></i>
                    </div>
                    <p class="text-muted small mb-0">Belum ada notifikasi untuk Anda.</p>
                </div>
            <?php else: ?>
                <?php foreach ($latest as $notif): ?>
                    <a class="dropdown-item py-3 px-4 border-bottom <?= $notif->is_read ? 'bg-white' : 'bg-light-soft' ?>" href="<?= $notif->link ?: '#' ?>">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="stat-icon-sm bg-<?= $notif->type ?>-soft text-<?= $notif->type ?> rounded-circle">
                                    <i class="bi <?= $notif->type === 'success' ? 'bi-check-circle' : ($notif->type === 'warning' ? 'bi-exclamation-triangle' : ($notif->type === 'danger' ? 'bi-x-circle' : 'bi-info-circle')) ?>"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="fw-bold text-dark small text-truncate"><?= esc($notif->title) ?></div>
                                <div class="text-muted extra-small line-clamp-2 mt-1" style="font-size: 0.75rem; line-height: 1.4;">
                                    <?= esc($notif->message) ?>
                                </div>
                                <div class="text-primary extra-small mt-2" style="font-size: 0.7rem;">
                                    <i class="bi bi-clock me-1"></i> <?= date('d M, H:i', strtotime($notif->created_at)) ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="dropdown-footer bg-light text-center py-2 px-4">
            <a href="<?= site_url('notifications') ?>" class="text-decoration-none small fw-bold text-primary">Lihat Semua Notifikasi</a>
        </div>
    </div>
</div>

<style>
    .bg-light-soft { background-color: rgba(var(--bs-light-rgb), 0.5); }
    .extra-small { font-size: 0.8rem; }
    .no-caret::after { display: none !important; }
    .notification-dropdown .dropdown-toggle i {
        transition: transform 0.2s ease;
    }
    .notification-dropdown .dropdown-toggle:hover i {
        transform: rotate(15deg);
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
