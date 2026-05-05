<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
/**
 * View variables defined in AdminProposalService::getIndexPayload
 * 
 * @var string $title 
 * @var array<int, array{label:string, value:string, tone_class:string, caption:string}> $metrics
 * @var array<int, array{number:int, title:string, owner_name:string, bidang_ilmu:string, status_label:string, status_badge_class:string, updated_at:string, show_url:string}> $tableRows
 * @var array{status:string, bidang_ilmu:string|int, search:string, hasFilters:bool, filterCount:int} $viewState
 * @var array<int, array{id:int, nama:string}> $bidangIlmuList
 */
?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Monitor seluruh ajuan proposal, tinjau detail, dan tunjuk reviewer yang relevan.',
            'badges' => [
                ['label' => 'Operasional Admin', 'class' => 'text-bg-light border shadow-sm'],
                ['label' => 'Proposal Monitoring', 'class' => 'text-bg-primary shadow-sm']
            ]
        ]) ?>
    </div>

    <?php foreach ($metrics as $i => $metric): ?>
        <div class="col-md-6 col-xl-3 animate-fade-up" style="animation-delay: <?= 0.1 * ($i + 1) ?>s">
            <?= view('components/ui-stat-card', [
                'label' => $metric['label'],
                'value' => $metric['value'],
                'desc' => $metric['caption'],
                'icon' => 'bi bi-file-earmark-text-fill',
                'colorClass' => $metric['tone_class'] ?? 'text-primary'
            ]) ?>
        </div>
    <?php endforeach; ?>

    <!-- Compact Controls & Filter Trigger -->
    <div class="col-12 mt-4 animate-fade-up" style="animation-delay: 0.4s;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-2">
            <div class="d-flex align-items-center gap-3 flex-grow-1" style="max-width: 500px;">
                <div class="input-group shadow-sm rounded-pill overflow-hidden border bg-white">
                    <span class="input-group-text bg-white border-0 text-muted ps-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="mainSearchInput" class="form-control border-0 shadow-none py-2"
                        placeholder="Cari judul, pengusul..."
                        value="<?= esc($viewState['search'] ?? '') ?>"
                        onkeypress="if(event.key === 'Enter') document.querySelector('[data-filter-submit-main]').click()">
                    <button class="btn btn-primary px-4 fw-bold" type="button" data-filter-submit-main>Cari</button>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-white border rounded-pill px-4 shadow-sm position-relative fw-semibold" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer">
                    <i class="bi bi-funnel me-2 text-primary"></i>Filter
                    <?php if ($viewState['hasFilters']): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem;">
                            <?= esc((string) $viewState['filterCount']) ?>
                        </span>
                    <?php endif; ?>
                </button>

                <?php if ($viewState['hasFilters']): ?>
                    <a href="<?= site_url('admin/proposals') ?>" class="btn btn-light rounded-pill px-3 shadow-sm text-muted" title="Hapus Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- The Drawer (Partial) -->
    <?= view('admin/proposal/partials/_filter_drawer', ['bidangIlmuList' => $bidangIlmuList, 'viewState' => $viewState]) ?>

    <div class="col-12 animate-fade-up" style="animation-delay: 0.5s;">
        <?php if (empty($tableRows) && !$viewState['hasFilters']): ?>
            <?= view('components/ui-empty-state', [
                'title' => 'Belum ada ajuan proposal',
                'desc' => 'Proposal akan muncul di sini setelah dosen melakukan submit pengajuan.',
                'icon' => 'bi bi-inbox-fill'
            ]) ?>
        <?php elseif (empty($tableRows) && $viewState['hasFilters']): ?>
            <?= view('components/ui-empty-state', [
                'title' => 'Tidak ada hasil',
                'desc' => 'Coba ubah filter atau kata kunci pencarian Anda.',
                'icon' => 'bi bi-search'
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead class="table-light">
                <tr>
                    <th style="width:60px" class="text-center py-3">#</th>
                    <th class="py-3">Judul Proposal & Pengusul</th>
                    <th style="width:200px" class="py-3">Bidang Ilmu</th>
                    <th style="width:180px" class="text-center py-3">Status Monitoring</th>
                    <th style="width:120px" class="text-center py-3">Aksi</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($tableRows as $row): ?>
                <tr>
                    <td class="text-center text-muted small"><?= esc((string) $row['number']) ?></td>
                    <td>
                        <a href="<?= esc($row['show_url']) ?>" class="text-decoration-none d-block mb-1">
                            <span class="fw-bold text-dark h6 mb-0 lh-sm"><?= esc($row['title']) ?></span>
                        </a>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <div class="small text-primary fw-bold"><i class="bi bi-person-circle me-1 opacity-75"></i><?= esc($row['owner_name']) ?></div>
                            <span class="text-muted opacity-25 small">|</span>
                            <div class="small text-muted"><i class="bi bi-calendar3 me-1 opacity-75"></i><?= format_indo($row['updated_at']) ?></div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-2 py-1 fw-normal rounded-pill small"><?= esc($row['bidang_ilmu']) ?></span>
                    </td>
                    <td class="text-center">
                        <div class="mb-1">
                            <span class="badge <?= esc($row['status_badge_class']) ?>-soft <?= str_replace('text-bg-', 'text-', (string) $row['status_badge_class']) ?> px-3 py-2 rounded-pill small">
                                <i class="bi bi-info-circle-fill me-1 small"></i><?= esc($row['status_label']) ?>
                            </span>
                        </div>
                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                            <i class="bi bi-people-fill me-1 opacity-75"></i><?= esc((string) $row['reviewer_count']) ?> Reviewer
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?= esc($row['show_url']) ?>" class="btn-action-sm bg-primary-soft text-primary shadow-sm" title="Kelola Proposal">
                                <i class="bi bi-gear-wide-connected"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $body = ob_get_clean(); ?>

            <?= view('components/ui-table-card', [
                'tableId' => 'dt-admin-proposals',
                'header' => $header,
                'body' => $body,
                'type' => 'admin',
                'title' => 'Daftar Pengajuan Proposal',
                'icon' => 'bi bi-files'
            ]) ?>
        <?php endif; ?>
    </div>
</div>


<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="<?= base_url('custom/js/admin-filter-drawer.js') ?>"></script>
<?= $this->endSection() ?>