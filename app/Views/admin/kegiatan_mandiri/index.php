<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
/** @var string $title */
/** @var array<int,array<string,mixed>> $tableRows */
?>

<div class="row g-4 admin-page">
    <div class="col-12 mb-2 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) ($hero['title'] ?? $title)),
            'subtitle' => esc((string) ($hero['subtitle'] ?? 'Pantau kegiatan mandiri yang dilakukan oleh para dosen')),
            'badges' => [
                ['label' => 'Direktori', 'class' => 'text-bg-light border'],
                ['label' => 'Kegiatan Mandiri', 'class' => 'text-bg-primary shadow-sm']
            ],
            'actions' => [
                [
                    'label' => 'Tambah Kegiatan',
                    'class' => 'btn btn-primary rounded-pill px-4 shadow-sm',
                    'icon' => 'bi bi-plus-lg',
                    'url' => site_url('admin/kegiatan-mandiri/create')
                ]
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <?php ob_start(); ?>
        <?php if (empty($tableRows)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-journal-text',
                'title' => 'Belum Ada Kegiatan Mandiri',
                'description' => 'Data kegiatan mandiri dosen akan muncul di sini setelah ditambahkan oleh admin atau diimpor.',
                'action_label' => 'Mulai Tambah Data',
                'action_url' => site_url('admin/kegiatan-mandiri/create'),
                'action_icon' => 'bi bi-plus-lg'
            ]) ?>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="dt-kegiatan-mandiri" 
                    data-admin-datatable 
                    data-skeleton-id="sk-dt-kegiatan-mandiri" 
                    data-real-wrap-id="rw-dt-kegiatan-mandiri"
                    data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,-1]}]}'>
                    <thead>
                        <tr>
                            <th style="width: 50px" class="text-center py-3">#</th>
                            <th class="py-3">Dosen & Judul</th>
                            <th class="py-3">Klasifikasi</th>
                            <th class="py-3">Klaster/Skala</th>
                            <th style="width: 80px" class="text-center py-3">Tahun</th>
                            <th style="width: 140px" class="text-center py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ((array) $tableRows as $index => $row): $row = (array) $row; ?>
                            <tr>
                                <td class="text-center text-muted small"><?= $index + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark mb-0 lh-sm"><?= esc((string) ($row['judul_kegiatan'] ?? '')) ?></div>
                                    <div class="text-muted small mt-1"><i class="bi bi-person-circle me-1 opacity-75"></i><?= esc((string) ($row['display_name'] ?? '')) ?></div>
                                </td>
                                <td>
                                    <span class="badge <?= esc((string) ($row['jenis_badge_class'] ?? 'text-bg-light border')) ?>-soft <?= str_replace('text-bg-', 'text-', (string) ($row['jenis_badge_class'] ?? 'text-primary')) ?> rounded-pill px-3 py-2">
                                        <?= esc((string) ($row['jenis_kegiatan'] ?? '')) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= esc((string) ($row['klaster_badge_class'] ?? 'text-bg-light border')) ?> rounded-pill px-3 py-2">
                                        <?= esc((string) ($row['klaster_label'] ?? '')) ?>
                                    </span>
                                </td>
                                <td class="text-center fw-bold text-dark"><?= esc((string) ($row['tahun'] ?? '')) ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= esc((string) ($row['show_url'] ?? '')) ?>" class="btn-action-sm bg-primary-soft text-primary shadow-sm" title="Lihat Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="<?= esc((string) ($row['edit_url'] ?? '')) ?>" class="btn-action-sm bg-warning-soft text-warning shadow-sm" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete" title="Hapus Data" data-href="<?= esc((string) ($row['delete_url'] ?? '')) ?>" data-delete-label="<?= esc((string) ($row['judul_kegiatan'] ?? 'kegiatan ini')) ?>" data-delete-desc="Data yang dihapus tidak dapat dipulihkan kembali.">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php $tableContent = ob_get_clean(); ?>

        <?= view('components/ui-table-card', [
            'type' => 'admin',
            'tableId' => 'dt-kegiatan-mandiri',
            'title' => 'Daftar Kegiatan Mandiri',
            'icon' => 'bi bi-grid-3x3-gap',
            'content' => $tableContent,
            'footer' => count($tableRows) > 0 ? 'Menampilkan total ' . count($tableRows) . ' entri data' : null
        ]) ?>

    </div>
</div>


<?= $this->endSection() ?>