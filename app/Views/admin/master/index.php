<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
/** @var string $title */
/** @var bool $useModal */
/** @var string $routePrefix */
/** @var string $addUrl */
/** @var array<int, array<string,mixed>> $fields */
/** @var array<int, array<string,mixed>> $items */
/** @var string $jsonUrl */
/** @var string $editUrl */
/** @var string $deleteUrl */
/** @var string $restoreUrl */
?>
<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Manajemen data referensi sistem Litapdimas.',
            'badges' => [
                ['label' => 'Master Data', 'class' => 'text-bg-light border'],
                ['label' => 'Konfigurasi', 'class' => 'text-bg-primary shadow-sm']
            ],
            'actions' => [
                [
                    'label' => 'Tambah ' . esc((string) $title),
                    'class' => 'btn btn-primary rounded-pill px-4 shadow-sm',
                    'icon' => 'bi bi-plus-lg',
                    'attr' => $useModal ? 'data-admin-modal-add-trigger data-admin-modal-target="#modalForm" data-admin-form-action="' . site_url($routePrefix . 'store') . '" data-admin-form-method="POST" data-admin-modal-title-text="Tambah ' . esc((string) $title) . '"' : 'href="' . esc((string) $addUrl) . '"'
                ]
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <?php ob_start(); ?>
        <thead>
            <tr>
                <th style="width: 60px;" class="text-center py-3">#</th>
                <?php foreach ((array) $fields as $field): ?>
                    <th class="py-3"><?= esc((string) ($field['label'] ?? '')) ?></th>
                <?php endforeach; ?>
                <th style="width: 140px;" class="text-center py-3">Aksi</th>
            </tr>
        </thead>
        <?php $header = ob_get_clean(); ?>

        <?php ob_start(); ?>
        <?php foreach ((array) $items as $index => $item): ?>
            <tr>
                <td class="text-center text-muted small"><?= $index + 1 ?></td>
                <?php foreach ((array) $fields as $field): ?>
                    <td class="fw-bold text-dark"><?= esc((string) ($item[$field['name']] ?? '')) ?></td>
                <?php endforeach; ?>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <?php if ($useModal): ?>
                            <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm"
                                data-id="<?= $item['id'] ?>"
                                data-admin-fetch-url="<?= esc($jsonUrl . (string) ($item['id'] ?? '')) ?>"
                                data-admin-modal-target="#modalForm"
                                data-admin-form-action="<?= site_url($routePrefix . 'update/') . (string) ($item['id'] ?? '') ?>"
                                data-admin-form-method="PUT"
                                data-admin-modal-title-text="Edit <?= esc((string) $title) ?>"
                                title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        <?php else: ?>
                            <a href="<?= esc((string) ($editUrl . (string) ($item['id'] ?? ''))) ?>" class="btn-action-sm bg-warning-soft text-warning shadow-sm" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($item['deleted_at'])): ?>
                            <a href="<?= esc((string) ($restoreUrl . (string) ($item['id'] ?? ''))) ?>" class="btn-action-sm bg-success-soft text-success shadow-sm" title="Restore">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        <?php else: ?>
                            <button type="button"
                                class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete"
                                data-href="<?= esc((string) ($deleteUrl . (string) ($item['id'] ?? ''))) ?>"
                                data-delete-label="<?= esc((string) $title) ?>"
                                data-delete-desc="Data ini akan dihapus dari sistem."
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
            'tableId' => 'dt-master-generic',
            'header' => $header,
            'body' => $body,
            'type' => 'admin',
            'title' => 'Tabel Data ' . $title,
            'icon' => 'bi bi-database-fill'
        ]) ?>
    </div>
</div>

<?php if ($useModal): ?>
    <div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form id="masterForm" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="POST">
                    <div class="modal-header bg-light border-0 py-3 px-4">
                        <h5 class="modal-title fw-bold text-dark" id="modalTitle" data-admin-modal-title>Tambah <?= esc((string) $title) ?></h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <?php foreach ((array) $fields as $field): ?>
                            <div class="mb-4 last-child-mb-0">
                                <label class="form-label fw-bold text-muted small mb-1"><?= esc((string) ($field['label'] ?? '')) ?><?= !empty($field['required']) ? '<span class="text-danger ms-1">*</span>' : '' ?></label>
                                <input type="<?= esc((string) ($field['type'] ?? 'text')) ?>"
                                    name="<?= esc((string) ($field['name'] ?? '')) ?>"
                                    class="form-control form-control-lg fs-6 shadow-none"
                                    placeholder="Masukkan <?= strtolower(esc((string) ($field['label'] ?? ''))) ?>..."
                                    <?= !empty($field['required']) ? 'required' : '' ?>
                                    id="field_<?= esc((string) ($field['name'] ?? '')) ?>"
                                    data-admin-field="<?= esc((string) ($field['name'] ?? '')) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3 px-4">
                        <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>