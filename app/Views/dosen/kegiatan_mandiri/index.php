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
            'title' => $title ?? 'Kegiatan Mandiri Saya',
            'subtitle' => 'Pantau dan kelola seluruh catatan kegiatan mandiri Anda dengan tampilan yang modern dan terorganisir.',
            'badges' => [
                ['label' => 'Kegiatan Mandiri', 'class' => 'text-bg-light border shadow-sm'],
                ['label' => 'Litapdimas', 'class' => 'text-bg-primary shadow-sm']
            ],
            'actions' => [
                [
                    'label' => 'Tambah Kegiatan Baru',
                    'class' => 'btn btn-primary rounded-pill px-4 shadow-sm',
                    'icon' => 'bi bi-plus-lg',
                    'url' => site_url('dosen/kegiatan-mandiri/create')
                ]
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <?php if (empty($tableRows)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-clipboard-check-fill',
                'title' => 'Belum Ada Kegiatan',
                'desc' => 'Daftar kegiatan mandiri Anda masih kosong. Mulai catat kegiatan Anda sekarang.',
                'action_label' => 'Tambah Kegiatan Pertama',
                'action_url' => site_url('dosen/kegiatan-mandiri/create'),
                'action_icon' => 'bi bi-plus-lg'
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead>
                <tr>
                    <th style="width: 60px" class="text-center py-3">#</th>
                    <th class="py-3">Informasi Kegiatan</th>
                    <th style="width: 150px" class="text-center py-3">Jenis</th>
                    <th style="width: 140px" class="text-center py-3">Klaster/Skala</th>
                    <th style="width: 90px" class="text-center py-3">Tahun</th>
                    <th style="width: 140px" class="text-center py-3">Aksi</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($tableRows as $index => $row): ?>
                <tr>
                    <td class="text-center text-muted small"><?= $index + 1 ?></td>
                    <td>
                        <div class="fw-bold text-dark lh-base"><?= esc((string) $row['judul_kegiatan']) ?></div>
                        <div class="text-muted small mt-1 d-flex align-items-center gap-1">
                            <i class="bi bi-clock-fill opacity-50 me-1"></i>
                            <span>Diperbarui baru-baru ini</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge rounded-pill px-3 py-2 <?= esc((string) $row['jenis_badge_class']) ?>-soft <?= str_replace('text-bg-', 'text-', (string) $row['jenis_badge_class']) ?> small">
                            <i class="bi bi-tag-fill me-1 small"></i><?= esc((string) $row['jenis_kegiatan']) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2 small fw-normal">
                            <i class="bi bi-layers-fill me-1 text-primary opacity-75"></i><?= esc((string) $row['klaster_label']) ?>
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
                                data-delete-label="kegiatan mandiri ini"
                                data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $body = ob_get_clean(); ?>

            <?= view('components/ui-table-card', [
                'title' => 'Daftar Kegiatan Mandiri',
                'badge' => count($tableRows) . ' Catatan',
                'tableId' => 'dt-kegiatan-mandiri',
                'header' => $header,
                'body' => $body,
                'icon' => 'bi bi-clipboard-check-fill'
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection(); ?>