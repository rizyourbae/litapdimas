<?= $this->extend('layouts/main') ?>

<?php
/**
 * Admin Referensi Index View
 * 
 * @var string $title
 * @var array<string,mixed> $viewState
 * @var array<string,array{label:string, icon:string, fieldLabel:string, items:array}> $tabs
 */
?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <!-- HERO SECTION -->
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc($title),
            'subtitle' => 'Kelola referensi dasar sistem seperti Jenjang, Jabatan, dan Klaster.',
            'badges' => [
                ['label' => 'Master Data', 'class' => 'text-bg-light border'],
                ['label' => 'Referensi Sistem', 'class' => 'text-bg-primary shadow-sm']
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <div class="d-none" data-admin-auto-open-tab="tab-<?= esc((string) ($viewState['activeTab'] ?? '')) ?>-link" <?= !empty($viewState['openModal'] ?? '') ? ' data-admin-auto-open-modal="modal-' . esc((string) ($viewState['openModal'] ?? '')) . '"' : '' ?>></div>
        
        <!-- TABS NAVIGATION -->
        <ul class="nav nav-tabs nav-fill profile-tabs mb-3 shadow-sm border-0 rounded-4 overflow-hidden" id="referensiTabs" role="tablist">
            <?php foreach ($tabs as $key => $tab): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 <?= (($viewState['activeTab'] ?? '') === $key) ? 'active' : '' ?>" 
                        id="tab-<?= esc((string) $key) ?>-link" 
                        data-bs-toggle="tab" 
                        data-bs-target="#tab-<?= esc((string) $key) ?>" 
                        type="button" role="tab">
                        <i class="<?= esc((string) ($tab['icon'] ?? '')) ?>-fill me-2"></i>
                        <?= esc((string) ($tab['label'] ?? '')) ?>
                        <span class="badge bg-secondary-soft text-secondary ms-2 rounded-pill"><?= esc((string) count($tab['items'])) ?></span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content" id="referensiTabsContent">
            <?php foreach ($tabs as $key => $tab): ?>
                <div class="tab-pane fade <?= (($viewState['activeTab'] ?? '') === $key) ? 'show active' : '' ?>" id="tab-<?= esc((string) $key) ?>" role="tabpanel">
                    <?php ob_start(); ?>
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px" class="text-center py-3">#</th>
                            <th class="py-3"><?= esc((string) ($tab['fieldLabel'] ?? '')) ?></th>
                            <th style="width:140px" class="text-center py-3">Aksi</th>
                        </tr>
                    </thead>
                    <?php $header = ob_get_clean(); ?>

                    <?php ob_start(); ?>
                    <?php if (empty($tab['items'])): ?>
                        <tr>
                            <td colspan="3">
                                <?= view('components/ui-empty-state', [
                                    'icon' => 'bi bi-database-exclamation',
                                    'title' => 'Belum ada data ' . $tab['label'],
                                    'desc' => 'Klik tombol tambah untuk membuat data referensi baru.',
                                ]) ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tab['items'] as $index => $item): ?>
                            <tr>
                                <td class="text-center text-muted small"><?= esc((string) ($index + 1)) ?></td>
                                <td>
                                    <span class="fw-bold text-dark"><?= esc((string) ($item['nama'] ?? '')) ?></span>
                                    <?php if (!empty($item['deleted_at'])): ?>
                                        <span class="badge bg-secondary-soft text-secondary rounded-pill ms-2 small">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if (!empty($item['deleted_at'])): ?>
                                            <button type="button" class="btn-action-sm bg-success-soft text-success shadow-sm btn-admin-restore"
                                                data-href="<?= site_url('admin/master/referensi/restore/' . $key . '/' . $item['id']) ?>"
                                                data-confirm-title="Pulihkan data ini?"
                                                data-confirm-html="Data <strong><?= esc((string) ($item['nama'] ?? '')) ?></strong> akan diaktifkan kembali."
                                                data-confirm-button="Ya, pulihkan"
                                                title="Pulihkan">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm"
                                                data-admin-modal-target="#modal-edit"
                                                data-admin-form-action="<?= site_url('admin/master/referensi/update/' . $key . '/' . $item['id']) ?>"
                                                data-admin-modal-title-text="Edit <?= esc((string) ($tab['label'] ?? '')) ?>"
                                                data-admin-value-nama="<?= esc((string) ($item['nama'] ?? '')) ?>"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete"
                                                data-href="<?= site_url('admin/master/referensi/delete/' . $key . '/' . $item['id']) ?>"
                                                data-delete-label="<?= esc((string) ($item['nama'] ?? '')) ?>"
                                                data-delete-desc="Data referensi akan dinonaktifkan."
                                                title="Hapus">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php $body = ob_get_clean(); ?>

                    <?= view('components/ui-table-card', [
                        'tableId' => 'dt-' . $key,
                        'header' => $header,
                        'body' => $body,
                        'type' => 'admin',
                        'title' => 'Daftar ' . $tab['label'],
                        'icon' => ($tab['icon'] ?? 'bi bi-database') . '-fill',
                        'actions' => [
                            [
                                'label' => 'Tambah ' . $tab['label'],
                                'class' => 'btn btn-primary btn-sm rounded-pill px-3',
                                'icon' => 'bi bi-plus-lg',
                                'attr' => 'data-bs-toggle="modal" data-bs-target="#modal-tambah-' . $key . '"'
                            ]
                        ]
                    ]) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- MODALS -->
<?= view('admin/master/partials/_modals_referensi', ['viewState' => $viewState, 'tabs' => $tabs]) ?>

<?= $this->endSection() ?>