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
            'subtitle' => esc((string) ($hero['subtitle'] ?? 'Kelola dan verifikasi direktori publikasi ilmiah para dosen')),
            'badges' => [
                ['label' => 'Direktori', 'class' => 'text-bg-light border'],
                ['label' => 'Publikasi', 'class' => 'text-bg-primary shadow-sm']
            ],
            'actions' => [
                [
                    'label' => 'Tambah Publikasi',
                    'class' => 'btn btn-primary rounded-pill px-4 shadow-sm',
                    'icon' => 'bi bi-plus-lg',
                    'url' => site_url('admin/publikasi/create')
                ]
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <?php ob_start(); ?>
        <?php if (empty($tableRows)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-journal-richtext',
                'title' => 'Belum Ada Data Publikasi',
                'description' => 'Daftar publikasi akademik dosen akan ditampilkan di sini setelah ditambahkan ke sistem.',
                'action_label' => 'Tambah Publikasi Baru',
                'action_url' => site_url('admin/publikasi/create'),
                'action_icon' => 'bi bi-plus-lg'
            ]) ?>
        <?php else: ?>
            <div class="table-responsive">
                <table id="dt-publikasi" class="table table-hover align-middle mb-0" 
                    data-admin-datatable 
                    data-skeleton-id="sk-dt-publikasi" 
                    data-real-wrap-id="rw-dt-publikasi"
                    data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,-1]}]}'>
                    <thead>
                        <tr>
                            <th style="width: 50px" class="text-center py-3">#</th>
                            <th class="py-3">Dosen & Judul</th>
                            <th class="py-3">Jenis Publikasi</th>
                            <th style="width: 100px" class="text-center py-3">Tahun</th>
                            <th style="width: 140px" class="text-center py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ((array) $tableRows as $no => $row): $row = (array) $row; ?>
                            <tr>
                                <td class="text-center text-muted small"><?= $no + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark mb-0 lh-sm"><?= esc((string) ($row['judul'] ?? '')) ?></div>
                                    <div class="text-muted small mt-1"><i class="bi bi-person-circle me-1 opacity-75"></i><?= esc((string) ($row['dosen_name'] ?? '')) ?></div>
                                </td>
                                <td>
                                    <span class="badge <?= esc((string) ($row['jenis_badge_class'] ?? 'text-bg-light border')) ?>-soft <?= str_replace('text-bg-', 'text-', (string) ($row['jenis_badge_class'] ?? 'text-primary')) ?> rounded-pill px-3 py-2">
                                        <i class="bi bi-bookmark-fill me-1 small"></i><?= esc((string) ($row['jenis_label'] ?? '')) ?>
                                    </span>
                                </td>
                                <td class="text-center fw-bold text-dark"><?= esc((string) ($row['tahun'] ?? '')) ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= esc((string) ($row['show_url'] ?? '')) ?>" class="btn-action-sm bg-primary-soft text-primary shadow-sm" title="Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="<?= esc((string) ($row['edit_url'] ?? '')) ?>" class="btn-action-sm bg-warning-soft text-warning shadow-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete" data-href="<?= esc((string) ($row['delete_url'] ?? '')) ?>" data-delete-label="<?= esc((string) ($row['judul'] ?? 'publikasi ini')) ?>" data-delete-desc="Data yang dihapus tidak dapat dikembalikan." title="Hapus">
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
            'tableId' => 'dt-publikasi',
            'title' => 'Daftar Publikasi Dosen',
            'icon' => 'bi bi-collection',
            'content' => $tableContent,
            'footer' => count($tableRows) > 0 ? 'Ditemukan ' . count($tableRows) . ' data publikasi' : null
        ]) ?>

    </div>
</div>


<?= $this->endSection() ?>