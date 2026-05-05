<?php
/** @var string $title */
/** @var array<int,array<string,mixed>> $tableRows */

$this->extend('layouts/main');

$this->section('content');
?>

<div class="row g-4 admin-page">
    <?php
    $heroConfig = [
        'type' => 'dosen',
        'title' => $title ?? 'Publikasi Saya',
        'subtitle' => 'Kelola seluruh karya ilmiah dan hasil publikasi Anda dalam satu panel terpadu.',
        'badges' => [
            ['label' => 'Publikasi', 'class' => 'text-bg-light border shadow-sm'],
            ['label' => 'Litapdimas', 'class' => 'text-bg-primary shadow-sm']
        ],
        'actions' => [
            [
                'label' => 'Tambah Publikasi Baru',
                'class' => 'btn btn-primary rounded-pill px-4 shadow-sm',
                'icon' => 'bi bi-plus-lg',
                'url' => site_url('dosen/publikasi/create')
            ]
        ]
    ];
    ?>
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', $heroConfig) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <?php if (empty($tableRows)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-journal-richtext',
                'title' => 'Belum Ada Publikasi',
                'desc' => 'Anda belum mencatatkan data publikasi ilmiah di sistem ini.',
                'action_label' => 'Tambah Publikasi Pertama',
                'action_url' => site_url('dosen/publikasi/create'),
                'action_icon' => 'bi bi-plus-lg'
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead>
                <tr>
                    <th style="width:60px" class="text-center py-3">#</th>
                    <th class="py-3">Judul Publikasi</th>
                    <th style="width:160px" class="text-center py-3">Jenis</th>
                    <th style="width:100px" class="text-center py-3">Tahun</th>
                    <th style="width:140px" class="text-center py-3">Aksi</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($tableRows as $index => $row): ?>
                <tr>
                    <td class="text-center text-muted small"><?= $index + 1 ?></td>
                    <td>
                        <div class="fw-bold text-dark lh-base"><?= esc((string) $row['judul']) ?></div>
                        <div class="text-muted small mt-1 d-flex align-items-center gap-1">
                            <i class="bi bi-calendar3-event-fill opacity-50 me-1"></i>
                            <span>Ditambahkan pada <?= format_indo($row['created_at'] ?? '') ?></span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge rounded-pill px-3 py-2 <?= esc((string) $row['jenis_badge_class']) ?>-soft <?= str_replace('text-bg-', 'text-', (string) $row['jenis_badge_class']) ?> small">
                            <i class="bi bi-bookmark-fill me-1 small"></i><?= esc((string) $row['jenis_label']) ?>
                        </span>
                    </td>
                    <td class="text-center fw-bold text-dark"><?= esc((string) $row['tahun']) ?></td>
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
                                data-delete-label="publikasi ini"
                                data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $body = ob_get_clean(); ?>

            <?= view('components/ui-table-card', [
                'title' => 'Daftar Publikasi',
                'badge' => count($tableRows) . ' Karya Ilmiah',
                'tableId' => 'dt-publikasi',
                'header' => $header,
                'body' => $body,
                'icon' => 'bi bi-journal-richtext'
            ]) ?>
        <?php endif; ?>
    </div>
</div>



<?php $this->endSection(); ?>