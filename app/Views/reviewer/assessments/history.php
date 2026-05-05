<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$hero = isset($hero) && is_array($hero) ? $hero : [];
$metrics = isset($metrics) && is_array($metrics) ? $metrics : [];
$table = isset($table) && is_array($table) ? $table : [];
?>

<div class="row g-3 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'reviewer',
            'title' => esc((string) ($hero['title'] ?? 'Riwayat Review')),
            'subtitle' => esc((string) ($hero['subtitle'] ?? 'Lihat kembali seluruh penilaian yang telah Anda berikan.')),
            'badges' => array_merge(
                [['label' => 'Riwayat', 'class' => 'text-bg-light border']],
                $hero['badges'] ?? []
            )
        ]) ?>
    </div>

    <?php foreach ($metrics as $index => $metric): ?>
        <div class="col-md-6 col-xl-4 animate-fade-up delay-<?= $index + 1 ?>">
            <?= view('components/ui-stat-card', [
                'label' => $metric['label'] ?? '',
                'value' => $metric['value'] ?? '0',
                'desc' => $metric['caption'] ?? '',
                'icon' => $metric['icon'] ?? 'bi bi-info-circle',
                'colorClass' => $metric['tone_class'] ?? 'text-dark'
            ]) ?>
        </div>
    <?php endforeach; ?>

    <div class="col-12 mt-4 animate-fade-up delay-4">
        <?php if (empty($table['rows'])): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-clock-history',
                'title' => 'Belum Ada Riwayat',
                'desc' => 'Riwayat reviewer akan tampil di sini setelah Anda memberikan penilaian.'
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead class="table-light">
                <tr>
                    <th style="width:170px">Kategori</th>
                    <th>Judul</th>
                    <th style="width:170px">Klaster</th>
                    <th style="width:140px" class="text-center">Skor</th>
                    <th style="width:80px" class="text-center">Aksi</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($table['rows'] as $row): ?>
                <tr>
                    <td class="small fw-semibold"><?= esc((string) ($row['category_label'] ?? '')) ?></td>
                    <td>
                        <div class="fw-bold text-dark mb-1"><?= esc((string) ($row['title'] ?? '')) ?></div>
                        <span class="badge <?= esc((string) ($row['review_status_badge_class'] ?? 'text-bg-light border')) ?> px-2 py-1 rounded-pill small">
                            <?= esc((string) ($row['review_status_label'] ?? '')) ?>
                        </span>
                    </td>
                    <td class="small text-muted"><?= esc((string) ($row['cluster'] ?? '')) ?></td>
                    <td class="text-center">
                        <div class="fw-bold text-success fs-5"><?= esc((string) ($row['score_display'] ?? '-')) ?></div>
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
                'title' => 'Riwayat Penilaian',
                'badge' => count($table['rows']) . ' Data',
                'tableId' => $table['table_id'] ?? 'dt-history-reviewer',
                'header' => $header,
                'body' => $body,
                'icon' => 'bi bi-clock-history',
                'type' => 'admin'
            ]) ?>
        <?php endif; ?>
    </div>
</div>


<?= $this->endSection() ?>