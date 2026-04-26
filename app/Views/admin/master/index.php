<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Master Data</span>
                            <span class="badge text-bg-primary px-3 py-2">Admin Workspace</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($title) ?></h2>
                    </div>
                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <?php if ($useModal): ?>
                            <button type="button" class="btn btn-primary"
                                data-admin-modal-add-trigger
                                data-admin-modal-target="#modalForm"
                                data-admin-form-action="<?= site_url($routePrefix . 'store') ?>"
                                data-admin-form-method="POST"
                                data-admin-modal-title-text="Tambah <?= esc($title) ?>">
                                <i class="bi bi-plus"></i> Tambah <?= esc($title) ?>
                            </button>
                        <?php else: ?>
                            <a href="<?= $addUrl ?>" class="btn btn-primary">
                                <i class="bi bi-plus"></i> Tambah <?= esc($title) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card admin-table-card shadow-sm">
            <div class="card-header border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-table me-2"></i>Daftar <?= esc($title) ?>
                    </h3>
                    <span class="badge text-bg-light border">Master reference</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" data-admin-datatable>
                        <thead>
                            <tr>
                                <th>#</th>
                                <?php foreach ($fields as $field): ?>
                                    <th><?= esc($field['label']) ?></th>
                                <?php endforeach; ?>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <?php foreach ($fields as $field): ?>
                                        <td><?= esc($item[$field['name']] ?? '') ?></td>
                                    <?php endforeach; ?>
                                    <td>
                                        <?php if ($useModal): ?>
                                            <a href="#" class="btn btn-warning btn-sm btn-edit"
                                                data-id="<?= $item['id'] ?>"
                                                data-admin-fetch-url="<?= $jsonUrl . $item['id'] ?>"
                                                data-admin-modal-target="#modalForm"
                                                data-admin-form-action="<?= site_url($routePrefix . 'update/') . $item['id'] ?>"
                                                data-admin-form-method="PUT"
                                                data-admin-modal-title-text="Edit <?= esc($title) ?>">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= $editUrl . $item['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($item['deleted_at'])): ?>
                                            <a href="<?= $restoreUrl . $item['id'] ?>" class="btn btn-success btn-sm">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="#"
                                                class="btn btn-danger btn-sm btn-delete"
                                                data-href="<?= $deleteUrl . $item['id'] ?>"
                                                data-delete-label="data ini"
                                                data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($useModal): ?>
    <!-- Modal Form -->
    <div class="modal fade" id="modalForm" tabindex="-1">
        <div class="modal-dialog">
            <form id="masterForm" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle" data-admin-modal-title>Tambah <?= esc($title) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php foreach ($fields as $field): ?>
                            <div class="mb-3">
                                <label class="form-label"><?= esc($field['label']) ?></label>
                                <input type="<?= $field['type'] ?? 'text' ?>"
                                    name="<?= $field['name'] ?>"
                                    class="form-control"
                                    <?= !empty($field['required']) ? 'required' : '' ?>
                                    id="field_<?= $field['name'] ?>"
                                    data-admin-field="<?= $field['name'] ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>