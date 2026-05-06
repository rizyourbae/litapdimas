<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => 'Manajemen Pengumuman',
            'subtitle' => 'Terbitkan dan kelola informasi terbaru untuk seluruh civitas akademika.',
            'badges' => [
                ['label' => 'CMS', 'class' => 'text-bg-light border'],
                ['label' => 'Announcements', 'class' => 'text-bg-primary shadow-sm']
            ],
            'actions' => [
                [
                    'label' => 'Tambah Pengumuman',
                    'class' => 'btn btn-primary rounded-pill px-4 shadow-sm',
                    'icon' => 'bi bi-plus-lg',
                    'attr' => 'data-admin-modal-add-trigger data-admin-modal-target="#modal-announcement" data-admin-form-action="' . site_url('admin/cms/announcements/store') . '" data-admin-form-method="POST" data-admin-modal-title-text="Tambah Pengumuman"'
                ]
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <?php ob_start(); ?>
        <thead class="table-light">
            <tr>
                <th style="width: 60px;" class="text-center py-3">#</th>
                <th class="py-3">Informasi Pengumuman</th>
                <th class="py-3">Statistik</th>
                <th class="py-3">Status</th>
                <th class="py-3">Tanggal</th>
                <th style="width: 140px;" class="text-center py-3">Aksi</th>
            </tr>
        </thead>
        <?php $header = ob_get_clean(); ?>

        <?php ob_start(); ?>
        <?php foreach ($announcements as $index => $row): ?>
            <tr>
                <td class="text-center text-muted small"><?= $index + 1 ?></td>
                <td>
                    <div class="fw-bold text-dark mb-1"><?= esc($row['title']) ?></div>
                    <div class="small text-muted text-truncate" style="max-width: 300px;">
                        <i class="bi bi-link-45deg"></i> <?= site_url('pengumuman/' . $row['slug']) ?>
                    </div>
                </td>
                <td>
                    <div class="small">
                        <span class="d-block mb-1"><i class="bi bi-eye text-primary me-1"></i> <?= number_format($row['view_count']) ?> views</span>
                        <?php if ($row['file_attachment']): ?>
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill small">
                                <i class="bi bi-paperclip me-1"></i> Ada Lampiran
                            </span>
                        <?php endif; ?>
                    </div>
                </td>
                <td>
                    <?php if ($row['is_active']): ?>
                        <span class="badge bg-success-soft text-success border-0 rounded-pill px-3">Aktif</span>
                    <?php else: ?>
                        <span class="badge bg-danger-soft text-danger border-0 rounded-pill px-3">Non-Aktif</span>
                    <?php endif; ?>
                </td>
                <td class="small text-muted">
                    <?= format_indo($row['created_at']) ?>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm"
                            data-uuid="<?= $row['uuid'] ?>"
                            data-admin-fetch-url="<?= site_url('admin/cms/announcements/json/' . $row['uuid']) ?>"
                            data-admin-modal-target="#modal-announcement"
                            data-admin-form-action="<?= site_url('admin/cms/announcements/update/' . $row['uuid']) ?>"
                            data-admin-form-method="POST"
                            data-admin-modal-title-text="Edit Pengumuman"
                            title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete-announcement"
                            data-uuid="<?= $row['uuid'] ?>"
                            title="Hapus">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php $body = ob_get_clean(); ?>

        <?= view('components/ui-table-card', [
            'tableId' => 'dt-announcements',
            'header' => $header,
            'body' => $body,
            'type' => 'admin',
            'title' => 'Daftar Pengumuman Terbit',
            'icon' => 'bi bi-megaphone-fill',
            'options' => [
                'columnDefs' => [
                    ['orderable' => false, 'targets' => [0, 5]]
                ]
            ],
        ]) ?>
    </div>
</div>

<!-- Modal Partial -->
<?= view('admin/cms/announcements/partials/_modals') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('custom/js/admin-cms-announcements.js') ?>"></script>
<?= $this->endSection() ?>
