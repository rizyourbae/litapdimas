<?= $this->extend('layouts/main') ?>

<?php
/**
 * Admin Publikasi Index View
 * 
 * @var string $title
 * @var array<int, array{judul:string, dosen_name:string, jenis_label:string, jenis_badge_class:string, tahun:int|string, show_url:string, edit_url:string, delete_url:string}> $tableRows
 * @var array $viewState
 * @var array $filterOptions
 */
?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <!-- HERO SECTION -->
    <div class="col-12 mb-2 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Kelola dan verifikasi direktori publikasi ilmiah para dosen',
            'badges' => [
                ['label' => 'Direktori', 'class' => 'text-bg-light border'],
                ['label' => 'Publikasi', 'class' => 'text-bg-primary shadow-sm']
            ]
        ]) ?>
    </div>

    <!-- CONTROLS & FILTER -->
    <div class="col-12 mt-4 animate-fade-up" style="animation-delay: 0.3s;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-2">
            <!-- Search Bar -->
            <div class="d-flex align-items-center gap-3 flex-grow-1" style="max-width: 500px;">
                <form action="<?= site_url('admin/publikasi') ?>" method="get" class="w-100">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border bg-white">
                        <span class="input-group-text bg-white border-0 text-muted ps-3">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-0 shadow-none py-2" 
                               placeholder="Cari judul, nama dosen..." 
                               value="<?= esc($viewState['search'] ?? '') ?>">
                        <button class="btn btn-primary px-4 fw-bold" type="submit">Cari</button>
                    </div>
                    <!-- Preserve existing filters when searching -->
                    <input type="hidden" name="jenis_publikasi" value="<?= esc($viewState['jenis_publikasi'] ?? '') ?>">
                    <input type="hidden" name="tahun" value="<?= esc($viewState['tahun'] ?? '') ?>">
                </form>
            </div>
            
            <!-- Filter Trigger -->
            <div class="d-flex gap-2">
                <button class="btn btn-white border rounded-pill px-4 shadow-sm position-relative fw-semibold" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer">
                    <i class="bi bi-funnel me-2 text-primary"></i>Filter
                    <?php if ($viewState['filterCount'] > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem;">
                            <?= esc((string) $viewState['filterCount']) ?>
                        </span>
                    <?php endif; ?>
                </button>
                
                <?php if ($viewState['hasFilters']): ?>
                    <a href="<?= site_url('admin/publikasi') ?>" class="btn btn-light rounded-pill px-3 shadow-sm text-muted" title="Hapus Semua Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- FILTER DRAWER PARTIAL -->
    <?= view('admin/publikasi/partials/_filter_drawer', ['viewState' => $viewState, 'filterOptions' => $filterOptions]) ?>

    <!-- TABLE SECTION -->
    <div class="col-12 animate-fade-up delay-1">
        <?php if (empty($tableRows)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => $viewState['hasFilters'] ? 'bi bi-search' : 'bi bi-journal-richtext',
                'title' => $viewState['hasFilters'] ? 'Hasil Tidak Ditemukan' : 'Belum Ada Data Publikasi',
                'desc' => $viewState['hasFilters'] ? 'Coba ubah kriteria filter atau kata kunci pencarian Anda.' : 'Daftar publikasi akademik dosen akan ditampilkan di sini.',
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead class="table-light">
                <tr>
                    <th style="width: 50px" class="text-center py-3">#</th>
                    <th class="py-3">Dosen & Judul Publikasi</th>
                    <th style="width: 200px;" class="py-3">Jenis Publikasi</th>
                    <th style="width: 100px" class="text-center py-3">Tahun</th>
                    <th style="width: 140px" class="text-center py-3">Aksi</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($tableRows as $index => $row): ?>
                <tr>
                    <td class="text-center text-muted small"><?= $index + 1 ?></td>
                    <td>
                        <div class="fw-bold text-dark mb-0 lh-sm"><?= esc((string) $row['judul']) ?></div>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-person-circle me-1 opacity-75"></i><?= esc((string) $row['dosen_name']) ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?= esc((string) ($row['jenis_badge_class'] ?? 'text-bg-light')) ?>-soft <?= str_replace('text-bg-', 'text-', (string) ($row['jenis_badge_class'] ?? 'text-primary')) ?> rounded-pill px-3 py-2 small fw-bold">
                            <i class="bi bi-bookmark-fill me-1 small"></i><?= esc((string) $row['jenis_label']) ?>
                        </span>
                    </td>
                    <td class="text-center fw-bold text-dark"><?= esc((string) $row['tahun']) ?></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?= esc((string) $row['show_url']) ?>" class="btn-action-sm bg-primary-soft text-primary shadow-sm" title="Lihat Detail">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="<?= esc((string) $row['edit_url']) ?>" class="btn-action-sm bg-warning-soft text-warning shadow-sm" title="Edit Data">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete" 
                                title="Hapus Data" 
                                data-href="<?= esc((string) $row['delete_url']) ?>" 
                                data-delete-label="<?= esc((string) $row['judul']) ?>" 
                                data-delete-desc="Data yang dihapus tidak dapat dipulihkan kembali.">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $body = ob_get_clean(); ?>

            <?= view('components/ui-table-card', [
                'type' => 'admin',
                'tableId' => 'dt-admin-publikasi',
                'title' => 'Daftar Publikasi Dosen',
                'icon' => 'bi bi-collection-fill',
                'header' => $header,
                'body' => $body,
                'badge' => count($tableRows) . ' Data',
                'actions' => [
                    [
                        'label' => 'Tambah Publikasi',
                        'url' => site_url('admin/publikasi/create'),
                        'icon' => 'bi bi-plus-lg',
                        'class' => 'btn btn-primary btn-sm rounded-pill px-3 shadow-sm'
                    ]
                ]
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('custom/js/admin-filter-drawer.js') ?>"></script>
<?= $this->endSection() ?>