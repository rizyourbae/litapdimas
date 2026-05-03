<?php
/** @var string $title */
/** @var array<int,array<string,mixed>> $tableRows */

$this->extend('layouts/main');

$this->section('content');
?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'dosen',
            'title' => $title ?? 'Kelengkapan Dokumen',
            'subtitle' => 'Kelola dokumen persyaratan hibah penelitian dan pengabdian Anda.',
            'badges' => [
                ['label' => 'Dokumen Wajib', 'class' => 'text-bg-light border shadow-sm'],
                ['label' => 'Litapdimas', 'class' => 'text-bg-primary shadow-sm']
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <?php ob_start(); ?>
        <thead>
            <tr>
                <th class="py-3">Jenis Dokumen Persyaratan</th>
                <th style="width: 250px;" class="text-center py-3">Status Verifikasi</th>
                <th style="width: 150px;" class="text-center py-3">Aksi</th>
            </tr>
        </thead>
        <?php $header = ob_get_clean(); ?>

        <?php ob_start(); ?>
        <?php foreach ($tableRows as $row): ?>
            <tr>
                <td>
                    <div class="fw-bold text-dark lh-base"><?= esc((string) $row['jenis_dokumen']) ?></div>
                    <div class="text-muted small mt-1">Dokumen pendukung administrasi hibah.</div>
                </td>
                <td class="text-center">
                    <span class="badge text-bg-<?= esc((string) $row['status_badge']) ?>-soft text-<?= esc((string) $row['status_badge']) ?> px-3 py-2 rounded-pill small">
                        <i class="bi bi-patch-check-fill me-1 small"></i><?= esc((string) $row['status']) ?>
                    </span>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <?php if ($row['is_uploaded']): ?>
                            <a href="<?= esc((string) $row['dokumen_url']) ?>" target="_blank" class="btn-action-sm bg-primary-soft text-primary shadow-sm" title="Lihat Dokumen">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        <?php endif; ?>
                        <a href="<?= esc((string) $row['edit_url']) ?>" class="btn-action-sm bg-warning-soft text-warning shadow-sm" title="Upload/Edit Dokumen">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php $body = ob_get_clean(); ?>

        <?= view('components/ui-table-card', [
            'title' => 'Status Dokumen Wajib',
            'badge' => count($tableRows) . ' Dokumen',
            'tableId' => 'dt-kelengkapan-dokumen',
            'header' => $header,
            'body' => $body,
            'icon' => 'bi bi-file-earmark-check-fill'
        ]) ?>
    </div>
</div>


<?php $this->endSection(); ?>