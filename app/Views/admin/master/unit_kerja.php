<?= $this->extend('layouts/main') ?>

<?php
/**
 * Admin Unit Kerja Index View
 * 
 * @var string $title
 * @var array<string,mixed> $viewState
 * @var array<int,array<string,mixed>> $items
 * @var array<string,array<int,array<string,mixed>>> $parentGroups
 */
?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <!-- HERO SECTION -->
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc($title),
            'subtitle' => 'Kelola hierarki dan struktur unit kerja di lingkungan universitas.',
            'badges' => [
                ['label' => 'Master Data', 'class' => 'text-bg-light border'],
                ['label' => 'Unit Kerja', 'class' => 'text-bg-info shadow-sm text-white']
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <div class="d-none" <?= !empty($viewState['openModal'] ?? '') ? ' data-admin-auto-open-modal="modal-' . esc((string) ($viewState['openModal'] ?? '')) . '"' : '' ?>></div>
        
        <!-- GUIDANCE ALERT -->
        <div class="alert alert-primary bg-primary-soft border-0 rounded-4 p-4 mb-4 d-flex align-items-start gap-3 shadow-sm">
            <div class="bg-primary text-white rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="bi bi-info-circle fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold text-primary mb-1">Panduan Struktur Hierarki</h6>
                <p class="text-primary opacity-75 small mb-0">Pilih induk dari grup Lembaga atau Unit, lalu isi sub-unit di bawahnya untuk membangun hierarki yang tepat dan terorganisir.</p>
            </div>
        </div>

        <?php if (empty($items)): ?>
            <?= view('components/ui-empty-state', [
                'icon' => 'bi bi-diagram-3-fill',
                'title' => 'Belum ada data Unit Kerja',
                'desc' => 'Klik tombol tambah untuk mulai menyusun struktur unit kerja organisasi Anda.',
                'action_label' => 'Tambah Unit Pertama',
                'action_url' => '#',
                'action_attr' => 'data-bs-toggle="modal" data-bs-target="#modal-tambah"'
            ]) ?>
        <?php else: ?>
            <?php ob_start(); ?>
            <thead class="table-light">
                <tr>
                    <th style="width:60px" class="text-center py-3">#</th>
                    <th class="py-3">Nama Unit Kerja</th>
                    <th class="py-3">Unit Induk (Atasan)</th>
                    <th style="width:140px" class="text-center py-3">Aksi</th>
                </tr>
            </thead>
            <?php $header = ob_get_clean(); ?>

            <?php ob_start(); ?>
            <?php foreach ($items as $index => $item): ?>
                <tr>
                    <td class="text-center text-muted small"><?= esc((string) ($index + 1)) ?></td>
                    <td>
                        <?php if (empty($item['parent_id'])): ?>
                            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-building-fill text-primary opacity-50"></i>
                                <?= esc((string) ($item['nama'] ?? '')) ?>
                            </div>
                        <?php else: ?>
                            <div class="ps-4 d-flex align-items-center gap-2">
                                <span class="text-muted opacity-50">└</span>
                                <span class="text-dark fw-medium"><?= esc((string) ($item['nama'] ?? '')) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['deleted_at'])): ?>
                            <span class="badge bg-secondary-soft text-secondary rounded-pill ms-2 small">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($item['nama_induk'])): ?>
                            <span class="badge bg-light text-dark border fw-normal rounded-pill px-3 py-2">
                                <i class="bi bi-diagram-2-fill me-1 text-primary opacity-75"></i><?= esc((string) ($item['nama_induk'] ?? '')) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted small italic opacity-50">
                                <i class="bi bi-star-fill me-1 text-warning small"></i>Unit Utama (Root)
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <?php if (!empty($item['deleted_at'])): ?>
                                <button type="button" class="btn-action-sm bg-success-soft text-success shadow-sm btn-admin-restore"
                                    data-href="<?= site_url('admin/master/unit-kerja/restore/' . $item['id']) ?>"
                                    data-confirm-title="Pulihkan data ini?"
                                    data-confirm-html="Data <strong><?= esc((string) ($item['nama'] ?? '')) ?></strong> akan diaktifkan kembali."
                                    data-confirm-button="Ya, pulihkan"
                                    title="Pulihkan">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm"
                                    data-admin-modal-target="#modal-edit"
                                    data-admin-form-action="<?= site_url('admin/master/unit-kerja/update/' . $item['id']) ?>"
                                    data-admin-modal-title-text="Edit Unit Kerja"
                                    data-admin-value-nama="<?= esc((string) ($item['nama'] ?? '')) ?>"
                                    data-admin-value-parent-id="<?= esc((string) ($item['parent_id'] ?? '')) ?>"
                                    title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete"
                                    data-href="<?= site_url('admin/master/unit-kerja/delete/' . $item['id']) ?>"
                                    data-delete-label="<?= esc((string) ($item['nama'] ?? '')) ?>"
                                    data-delete-desc="Unit kerja akan dinonaktifkan."
                                    title="Hapus">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php $body = ob_get_clean(); ?>

            <?= view('components/ui-table-card', [
                'tableId' => 'dt-unit-kerja',
                'header' => $header,
                'body' => $body,
                'type' => 'admin',
                'title' => 'Struktur Unit Kerja',
                'icon' => 'bi bi-diagram-3-fill',
                'actions' => [
                    [
                        'label' => 'Tambah Unit',
                        'class' => 'btn btn-primary btn-sm rounded-pill px-3',
                        'icon' => 'bi bi-plus-lg',
                        'attr' => 'data-bs-toggle="modal" data-bs-target="#modal-tambah"'
                    ]
                ]
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<!-- MODALS -->
<?= view('admin/master/partials/_modals_unit_kerja', ['viewState' => $viewState, 'parentGroups' => $parentGroups]) ?>

<?= $this->endSection() ?>