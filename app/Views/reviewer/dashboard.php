<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
/** @var string $title */
/** @var string $userDisplayName */
/** @var array<int,array<string,mixed>> $metrics */
/** @var array<int,array<string,mixed>> $categories */
/** @var array<int,array<string,mixed>> $queuePreview */
/** @var string $queueUrl */
/** @var string $historyUrl */

$metrics = isset($metrics) && is_array($metrics) ? $metrics : [];
$categories = isset($categories) && is_array($categories) ? $categories : [];
$queuePreview = isset($queuePreview) && is_array($queuePreview) ? $queuePreview : [];
$userDisplayName = (string) ($userDisplayName ?? 'Reviewer');
?>

<div class="row g-3 admin-page">
    <div class="col-12">
        <?= view('components/ui-hero', [
            'type' => 'reviewer',
            'title' => 'Selamat datang, ' . esc($userDisplayName) . '!',
            'subtitle' => esc((string) ($hero['subtitle'] ?? 'Pantau antrian review dan berikan penilaian Anda.')),
            'badges' => array_merge(
                [['label' => 'Panel Reviewer', 'class' => 'text-bg-light border']],
                $hero['badges'] ?? []
            ),
            'actions' => '
                <a href="' . esc((string) $queueUrl) . '" class="btn btn-success"><i class="bi bi-list-check me-1"></i>Buka Antrian Review</a>
                <a href="' . esc((string) $historyUrl) . '" class="btn btn-outline-secondary"><i class="bi bi-clock-history me-1"></i>Riwayat Review</a>
            '
        ]) ?>
    </div>

    <?php foreach ($metrics as $metric): ?>
        <div class="col-md-6 col-xl-3">
            <?= view('components/ui-stat-card', [
                'label' => $metric['label'] ?? '',
                'value' => $metric['value'] ?? '0',
                'desc' => $metric['caption'] ?? '',
                'icon' => $metric['icon'] ?? 'bi bi-info-circle',
                'colorClass' => $metric['tone_class'] ?? 'text-dark'
            ]) ?>
        </div>
    <?php endforeach; ?>

    <div class="col-12 mt-4">
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h3 class="h5 fw-bold mb-0">
                        <i class="bi bi-grid-3x3-gap me-2 opacity-50"></i>Shortcut Kategori
                    </h3>
                    <small class="text-muted">Masuk cepat ke kategori penilaian yang sedang aktif.</small>
                </div>
                <a href="<?= esc((string) $queueUrl) ?>" class="btn btn-outline-success btn-sm px-3">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php foreach ($categories as $category): ?>
                        <div class="col-md-6 col-xl-3">
                            <a href="<?= esc((string) ($category['url'] ?? $queueUrl)) ?>" class="text-decoration-none d-block h-100">
                                <div class="admin-choice-card h-100 p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-success bg-opacity-10 text-success p-3 rounded-3">
                                            <i class="<?= esc((string) ($category['icon'] ?? 'bi bi-circle')) ?> fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="small text-uppercase text-muted fw-bold"><?= esc((string) ($category['label'] ?? '')) ?></div>
                                            <div class="fs-3 fw-bold text-dark"><?= esc((string) ($category['count'] ?? '0')) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4">
        <?php if (empty($queuePreview)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-inbox',
                'title' => 'Belum Ada Antrian Review',
                'desc' => 'Antrian akan muncul saat data penilaian sudah tersedia di tiap kategori.',
                'action' => '<a href="' . esc((string) $queueUrl) . '" class="btn btn-primary btn-sm px-4">Buka Semua Antrian</a>'
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead class="table-light">
                <tr>
                    <th style="width:170px">Kategori</th>
                    <th>Judul</th>
                    <th style="width:160px">Klaster</th>
                    <th style="width:120px">Nilai</th>
                    <th style="width:140px" class="text-center">Status</th>
                    <th style="width:80px" class="text-center">Aksi</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($queuePreview as $row): ?>
                <tr>
                    <td class="small fw-semibold"><?= esc((string) ($row['category_label'] ?? '')) ?></td>
                    <td>
                        <div class="fw-bold text-dark"><?= esc((string) ($row['title'] ?? '')) ?></div>
                    </td>
                    <td class="small text-muted"><?= esc((string) ($row['cluster'] ?? '')) ?></td>
                    <td class="fw-bold text-primary"><?= esc((string) ($row['score_display'] ?? '-')) ?></td>
                    <td class="text-center">
                        <span class="badge <?= esc((string) ($row['review_status_badge_class'] ?? 'text-bg-warning')) ?> px-3 py-2 rounded-pill">
                            <?= esc((string) ($row['review_status_label'] ?? 'Belum Dinilai')) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="<?= esc((string) ($row['action_url'] ?? '#')) ?>" class="btn btn-info btn-sm text-white admin-icon-btn" title="Lihat">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $body = ob_get_clean(); ?>

            <?= view('components/ui-table-card', [
                'title' => 'Antrian Terkini',
                'badge' => count($queuePreview) . ' Preview',
                'tableId' => 'dt-reviewer-dashboard',
                'header' => $header,
                'body' => $body,
                'icon' => 'bi bi-inbox',
                'type' => 'admin'
            ]) ?>
        <?php endif; ?>
    </div>
</div>


<?= $this->endSection() ?>