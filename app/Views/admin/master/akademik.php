<?= $this->extend('layouts/main') ?>

<?php
/**
 * Admin Akademik Index View
 * 
 * @var string $title
 * @var array<string,mixed> $viewState
 * @var array<int,array<string,mixed>> $fakultasRows
 * @var array<int,array<string,mixed>> $prodi
 * @var array<int,array<string,mixed>> $fakultasOptions
 */
?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <!-- HERO SECTION -->
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Kelola data Fakultas dan Program Studi dalam ekosistem akademik.',
            'badges' => [
                ['label' => 'Master Data', 'class' => 'text-bg-light border'],
                ['label' => 'Akademik', 'class' => 'text-bg-success shadow-sm']
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <div class="d-none" data-admin-auto-open-tab="tab-<?= esc((string) ($viewState['activeTab'] ?? '')) ?>-link" <?= !empty($viewState['openModal'] ?? '') ? ' data-admin-auto-open-modal="modal-' . esc((string) ($viewState['openModal'] ?? '')) . '"' : '' ?>></div>
        
        <!-- TABS NAVIGATION -->
        <ul class="nav nav-tabs nav-fill profile-tabs mb-3 shadow-sm border-0 rounded-4 overflow-hidden" id="akademikTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link py-3 <?= (($viewState['activeTab'] ?? '') === 'fakultas') ? 'active' : '' ?>" id="tab-fakultas-link" data-bs-toggle="tab" data-bs-target="#tab-fakultas" type="button" role="tab">
                    <i class="bi bi-building-fill me-2"></i>Fakultas
                    <span class="badge bg-secondary-soft text-secondary ms-2 rounded-pill"><?= esc((string) count($fakultasRows)) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-3 <?= (($viewState['activeTab'] ?? '') === 'prodi') ? 'active' : '' ?>" id="tab-prodi-link" data-bs-toggle="tab" data-bs-target="#tab-prodi" type="button" role="tab">
                    <i class="bi bi-mortarboard-fill me-2"></i>Program Studi
                    <span class="badge bg-secondary-soft text-secondary ms-2 rounded-pill"><?= esc((string) count($prodi)) ?></span>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="akademikTabsContent">
            <!-- TAB: FAKULTAS -->
            <div class="tab-pane fade <?= (($viewState['activeTab'] ?? '') === 'fakultas') ? 'show active' : '' ?>" id="tab-fakultas" role="tabpanel">
                <?php ob_start(); ?>
                <thead class="table-light">
                    <tr>
                        <th style="width:60px" class="text-center py-3">#</th>
                        <th class="py-3">Nama Fakultas</th>
                        <th style="width:150px" class="text-center py-3">Jumlah Prodi</th>
                        <th style="width:140px" class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <?php $headerFakultas = ob_get_clean(); ?>

                <?php ob_start(); ?>
                <?php foreach ($fakultasRows as $row): ?>
                    <tr>
                        <td class="text-center text-muted small"><?= esc((string) $row['number']) ?></td>
                        <td>
                            <span class="fw-bold text-dark"><?= esc((string) ($row['name'] ?? '')) ?></span>
                            <?php if (!empty($row['isArchived'])): ?>
                                <span class="badge bg-secondary-soft text-secondary rounded-pill ms-2 small">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['prodiCount'] > 0): ?>
                                <span class="badge bg-primary-soft text-primary rounded-pill px-3"><?= esc((string) $row['prodiCount']) ?> Prodi</span>
                            <?php else: ?>
                                <span class="text-muted small opacity-50">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <?php if ($row['isArchived']): ?>
                                    <button type="button" class="btn-action-sm bg-success-soft text-success shadow-sm btn-admin-restore" data-href="<?= site_url('admin/master/akademik/restore/fakultas/' . $row['id']) ?>" data-confirm-title="Pulihkan data ini?" data-confirm-html="Data <strong><?= esc((string) ($row['name'] ?? '')) ?></strong> akan diaktifkan kembali." data-confirm-button="Ya, pulihkan" title="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></button>
                                <?php else: ?>
                                    <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm" data-admin-modal-target="#modal-edit-fakultas" data-admin-form-action="<?= site_url('admin/master/akademik/update/fakultas/' . $row['id']) ?>" data-admin-modal-title-text="Edit Fakultas" data-admin-value-nama="<?= esc((string) ($row['name'] ?? '')) ?>" title="Edit"><i class="bi bi-pencil-square"></i></button>
                                    <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete" data-href="<?= site_url('admin/master/akademik/delete/fakultas/' . $row['id']) ?>" data-delete-label="<?= esc((string) ($row['name'] ?? '')) ?>" data-delete-desc="Fakultas akan dinonaktifkan." title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php $bodyFakultas = ob_get_clean(); ?>

                <?= view('components/ui-table-card', [
                    'tableId' => 'dt-fakultas',
                    'header' => $headerFakultas,
                    'body' => $bodyFakultas,
                    'type' => 'admin',
                    'title' => 'Daftar Fakultas',
                    'icon' => 'bi bi-building-fill',
                    'actions' => [
                        [
                            'label' => 'Tambah Fakultas',
                            'class' => 'btn btn-primary btn-sm rounded-pill px-3',
                            'icon' => 'bi bi-plus-lg',
                            'attr' => 'data-bs-toggle="modal" data-bs-target="#modal-tambah-fakultas"'
                        ]
                    ]
                ]) ?>
            </div>

            <!-- TAB: PROGRAM STUDI -->
            <div class="tab-pane fade <?= (($viewState['activeTab'] ?? '') === 'prodi') ? 'show active' : '' ?>" id="tab-prodi" role="tabpanel">
                <?php ob_start(); ?>
                <thead class="table-light">
                    <tr>
                        <th style="width:60px" class="text-center py-3">#</th>
                        <th class="py-3">Nama Program Studi</th>
                        <th class="py-3">Afiliasi Fakultas</th>
                        <th style="width:140px" class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <?php $headerProdi = ob_get_clean(); ?>

                <?php ob_start(); ?>
                <?php foreach ($prodi as $index => $item): ?>
                    <tr>
                        <td class="text-center text-muted small"><?= esc((string) ($index + 1)) ?></td>
                        <td>
                            <span class="fw-bold text-dark"><?= esc((string) ($item['nama'] ?? '')) ?></span>
                            <?php if (!empty($item['deleted_at'])): ?>
                                <span class="badge bg-secondary-soft text-secondary rounded-pill ms-2 small">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($item['nama_fakultas'])): ?>
                                <span class="badge bg-light text-dark border fw-normal px-3 py-2 rounded-pill">
                                    <i class="bi bi-building-fill me-1 text-primary opacity-75"></i><?= esc((string) ($item['nama_fakultas'] ?? '')) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted small opacity-50"><i>Tidak terdata</i></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <?php if (!empty($item['deleted_at'])): ?>
                                    <button type="button" class="btn-action-sm bg-success-soft text-success shadow-sm btn-admin-restore" data-href="<?= site_url('admin/master/akademik/restore/prodi/' . $item['id']) ?>" data-confirm-title="Pulihkan data ini?" data-confirm-html="Data <strong><?= esc((string) ($item['nama'] ?? '')) ?></strong> akan diaktifkan kembali." data-confirm-button="Ya, pulihkan" title="Pulihkan"><i class="bi bi-arrow-counterclockwise"></i></button>
                                <?php else: ?>
                                    <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm" data-admin-modal-target="#modal-edit-prodi" data-admin-form-action="<?= site_url('admin/master/akademik/update/prodi/' . $item['id']) ?>" data-admin-modal-title-text="Edit Program Studi" data-admin-value-nama="<?= esc((string) ($item['nama'] ?? '')) ?>" data-admin-value-fakultas-id="<?= esc((string) ($item['fakultas_id'] ?? '')) ?>" title="Edit"><i class="bi bi-pencil-square"></i></button>
                                    <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete" data-href="<?= site_url('admin/master/akademik/delete/prodi/' . $item['id']) ?>" data-delete-label="<?= esc((string) ($item['nama'] ?? '')) ?>" data-delete-desc="Program studi akan dinonaktifkan." title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php $bodyProdi = ob_get_clean(); ?>

                <?= view('components/ui-table-card', [
                    'tableId' => 'dt-prodi',
                    'header' => $headerProdi,
                    'body' => $bodyProdi,
                    'type' => 'admin',
                    'title' => 'Daftar Program Studi',
                    'icon' => 'bi bi-mortarboard-fill',
                    'actions' => [
                        [
                            'label' => 'Tambah Prodi',
                            'class' => 'btn btn-primary btn-sm rounded-pill px-3',
                            'icon' => 'bi bi-plus-lg',
                            'attr' => !empty($fakultasOptions) ? 'data-bs-toggle="modal" data-bs-target="#modal-tambah-prodi"' : 'disabled'
                        ]
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->
<?= view('admin/master/partials/_modals_akademik', ['viewState' => $viewState, 'fakultasOptions' => $fakultasOptions]) ?>

<?= $this->endSection() ?>