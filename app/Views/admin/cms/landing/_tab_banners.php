<div class="row">
    <div class="col-12">
        <?php ob_start(); ?>
        <thead class="table-light">
            <tr>
                <th style="width: 50px" class="text-center py-3">Urutan</th>
                <th style="width: 150px" class="py-3">Pratinjau</th>
                <th class="py-3">Informasi Banner</th>
                <th style="width: 100px" class="text-center py-3">Status</th>
                <th style="width: 120px" class="text-center py-3">Aksi</th>
            </tr>
        </thead>
        <?php $header = ob_get_clean(); ?>

        <?php ob_start(); ?>
        <?php foreach ($banners as $row): ?>
            <tr>
                <td class="text-center fw-bold text-primary"><?= esc($row['sort_order']) ?></td>
                <td>
                    <img src="<?= base_url($row['image']) ?>" class="rounded shadow-sm" style="width: 120px; height: 60px; object-fit: cover;">
                </td>
                <td>
                    <div class="fw-bold text-dark mb-1"><?= esc($row['title'] ?: 'Tanpa Judul') ?></div>
                    <div class="small text-muted text-truncate" style="max-width: 300px;"><?= esc($row['description']) ?></div>
                </td>
                <td class="text-center">
                    <?php if ($row['is_active']): ?>
                        <span class="badge bg-success-soft text-success rounded-pill px-3">Aktif</span>
                    <?php else: ?>
                        <span class="badge bg-danger-soft text-danger rounded-pill px-3">Non-aktif</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn-action-sm bg-warning-soft text-warning shadow-sm btn-edit-banner" 
                                data-uuid="<?= esc($row['uuid']) ?>" 
                                data-url="<?= base_url('admin/cms/landing/banners/json/' . $row['uuid']) ?>"
                                title="Edit Banner">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete-banner" 
                                data-uuid="<?= esc($row['uuid']) ?>" 
                                data-url="<?= base_url('admin/cms/landing/banners/delete/' . $row['uuid']) ?>"
                                title="Hapus Banner">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php $body = ob_get_clean(); ?>

        <?= view('components/ui-table-card', [
            'type' => 'admin',
            'tableId' => 'dt-cms-banners',
            'title' => 'Daftar Hero Carousel / Info Slider',
            'icon' => 'bi bi-images',
            'header' => $header,
            'body' => $body,
            'badge' => count($banners) . ' Banner',
            'actions' => [
                [
                    'label' => 'Tambah Banner',
                    'url' => '#',
                    'icon' => 'bi bi-plus-lg',
                    'class' => 'btn btn-primary btn-sm rounded-pill px-3 shadow-sm btn-add-banner'
                ]
            ]
        ]) ?>
    </div>
</div>
