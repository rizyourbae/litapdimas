<?= $this->extend('layouts/main') ?>

<?php
/**
 * Audit Logs Index View
 * 
 * @var string $title
 * @var array<int, array{time_ago:string, date_full:string, user:string, action_class:string, action_label:string, resource:string, description:string, ip:string}> $logs
 */
?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <!-- HERO SECTION -->
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Pantau riwayat aktivitas dan jejak audit keamanan seluruh pengguna sistem.',
            'badges' => [
                ['label' => 'Keamanan Sistem', 'class' => 'text-bg-light border shadow-sm'],
                ['label' => 'Audit Trail', 'class' => 'text-bg-danger shadow-sm']
            ]
        ]) ?>
    </div>

    <!-- TABLE SECTION -->
    <div class="col-12 animate-fade-up delay-1">
        <?php if (empty($logs)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-shield-slash',
                'title' => 'Belum Ada Log Aktivitas',
                'desc' => 'Sistem belum mencatat aktivitas apa pun saat ini.',
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead class="table-light">
                <tr>
                    <th style="width: 180px;" class="py-3 ps-4">Waktu Kejadian</th>
                    <th class="py-3">Pengguna</th>
                    <th style="width: 140px;" class="text-center py-3">Aksi</th>
                    <th style="width: 150px;" class="py-3 text-center">Resource</th>
                    <th class="py-3">Keterangan Aktivitas</th>
                    <th style="width: 150px;" class="text-center py-3 pe-4">IP Address</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark small mb-0"><?= esc($log['time_ago']) ?></div>
                        <div class="text-muted" style="font-size: 0.7rem;"><?= esc($log['date_full']) ?></div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                <?= strtoupper(substr($log['user'], 0, 1)) ?>
                            </div>
                            <span class="fw-bold text-dark small"><?= esc($log['user']) ?></span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge <?= esc($log['action_class']) ?>-soft <?= str_replace('text-bg-', 'text-', (string) $log['action_class']) ?> px-3 py-2 rounded-pill small fw-bold text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.05em;">
                            <?= esc($log['action_label']) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge text-bg-light border px-2 py-1 rounded-pill small fw-normal"><?= esc($log['resource']) ?></span>
                    </td>
                    <td>
                        <div class="text-dark small lh-sm text-truncate" style="max-width: 300px;" title="<?= esc($log['description']) ?>">
                            <?= esc($log['description']) ?>
                        </div>
                    </td>
                    <td class="text-center pe-4">
                        <code class="small bg-light px-2 py-1 rounded border text-muted" style="font-size: 0.7rem;"><?= esc($log['ip']) ?></code>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $body = ob_get_clean(); ?>

            <?= view('components/ui-table-card', [
                'tableId' => 'dt-audit-logs',
                'header' => $header,
                'body' => $body,
                'type' => 'admin',
                'title' => 'Log Aktivitas Terbaru',
                'icon' => 'bi bi-shield-lock-fill',
                'badge' => 'Menampilkan 150 entri'
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
