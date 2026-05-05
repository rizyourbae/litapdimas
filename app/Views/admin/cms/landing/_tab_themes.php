<div class="row">
    <div class="col-12">
        <?php ob_start(); ?>
        <thead class="table-light">
            <tr>
                <th style="width: 50px" class="text-center py-3">Urutan</th>
                <th style="width: 80px" class="text-center py-3">Ikon</th>
                <th class="py-3">Nama Tema Riset</th>
                <th style="width: 100px" class="text-center py-3">Status</th>
                <th style="width: 120px" class="text-center py-3">Aksi</th>
            </tr>
        </thead>
        <?php $header = ob_get_clean(); ?>

        <?php ob_start(); ?>
        <?php if (empty($themes)): ?>
            <tr>
                <td colspan="5" class="text-center py-5 text-muted">Belum ada tema riset yang terdaftar.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($themes as $row): ?>
                <tr>
                    <td class="text-center fw-bold text-primary"><?= esc($row['sort_order']) ?></td>
                    <td class="text-center fs-4"><i class="<?= esc($row['icon']) ?>"></i></td>
                    <td class="fw-bold"><?= esc($row['nama']) ?></td>
                    <td class="text-center">
                        <?php if ($row['is_active']): ?>
                            <span class="badge bg-success-soft text-success rounded-pill px-3">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-danger-soft text-danger rounded-pill px-3">Non-aktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn-action-sm bg-warning-soft text-warning shadow-sm btn-edit-theme" 
                                    data-uuid="<?= esc($row['uuid']) ?>" 
                                    data-url="<?= base_url('admin/cms/landing/themes/json/' . $row['uuid']) ?>"
                                    title="Edit Tema">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete-theme" 
                                    data-uuid="<?= esc($row['uuid']) ?>" 
                                    data-url="<?= base_url('admin/cms/landing/themes/delete/' . $row['uuid']) ?>"
                                    title="Hapus Tema">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php $body = ob_get_clean(); ?>

        <?= view('components/ui-table-card', [
            'type' => 'admin',
            'tableId' => 'dt-cms-themes',
            'title' => 'Daftar Tema Riset Prioritas',
            'icon' => 'bi bi-grid-3x3-gap',
            'header' => $header,
            'body' => $body,
            'badge' => count($themes) . ' Tema',
            'actions' => [
                [
                    'label' => 'Tambah Tema',
                    'url' => '#',
                    'icon' => 'bi bi-plus-lg',
                    'class' => 'btn btn-primary btn-sm rounded-pill px-3 shadow-sm btn-add-theme'
                ]
            ]
        ]) ?>
    </div>
</div>
