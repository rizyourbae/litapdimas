<?php
/** @var string $title */
/** @var array<int,array<string,mixed>> $proposals */

$this->extend('layouts/main');

$this->section('content');
?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'dosen',
            'title' => $title ?? 'Proposal Saya',
            'subtitle' => 'Kelola proposal penelitian dan pengabdian Anda dalam satu panel terpusat.',
            'badges' => [
                ['label' => 'Proposal Saya', 'class' => 'text-bg-light border shadow-sm'],
                ['label' => 'Litapdimas', 'class' => 'text-bg-primary shadow-sm']
            ],
            'actions' => [
                [
                    'label' => 'Buat Proposal Baru',
                    'class' => 'btn btn-primary rounded-pill px-4 shadow-sm',
                    'icon' => 'bi bi-plus-lg',
                    'url' => site_url('dosen/proposals/create')
                ]
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <?php if (empty($proposals)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-journal-text',
                'title' => 'Belum Ada Proposal',
                'desc' => 'Anda belum memiliki proposal penelitian atau pengabdian yang diajukan.',
                'action_label' => 'Buat Proposal Pertama',
                'action_url' => site_url('dosen/proposals/create'),
                'action_icon' => 'bi bi-plus-lg'
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead>
                <tr>
                    <th style="width:60px" class="text-center py-3">#</th>
                    <th class="py-3">Judul Proposal</th>
                    <th style="width:150px" class="text-center py-3">Status</th>
                    <th style="width:140px" class="text-center py-3">Dibuat Pada</th>
                    <th style="width:120px" class="text-center py-3">Aksi</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($proposals as $index => $row): ?>
                <tr>
                    <td class="text-center text-muted small"><?= $index + 1 ?></td>
                    <td>
                        <div class="fw-bold text-dark lh-base"><?= esc((string) $row['judul']) ?></div>
                    </td>
                    <td class="text-center">
                        <span class="badge <?= esc((string) $row['status_badge_class']) ?>-soft <?= str_replace('text-bg-', 'text-', (string) $row['status_badge_class']) ?> px-3 py-2 rounded-pill small">
                            <i class="bi bi-info-circle-fill me-1 small"></i><?= esc((string) $row['status_label']) ?>
                        </span>
                    </td>
                    <td class="text-center small text-muted fw-medium">
                        <i class="bi bi-calendar-check-fill opacity-50 me-1"></i><?= esc((string) $row['created_at_formatted']) ?>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?= esc((string) $row['show_url']) ?>" class="btn-action-sm bg-primary-soft text-primary shadow-sm" title="Detail">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="<?= esc((string) $row['edit_url']) ?>" class="btn-action-sm bg-warning-soft text-warning shadow-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete" title="Hapus"
                                data-href="<?= esc((string) $row['delete_url']) ?>"
                                data-delete-label="proposal ini"
                                data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $body = ob_get_clean(); ?>

            <?= view('components/ui-table-card', [
                'title' => 'Daftar Proposal',
                'badge' => count($proposals) . ' Data',
                'tableId' => 'dt-proposal-dosen',
                'header' => $header,
                'body' => $body,
                'icon' => 'bi bi-journal-text'
            ]) ?>
        <?php endif; ?>
    </div>
</div>


<?php $this->endSection(); ?>