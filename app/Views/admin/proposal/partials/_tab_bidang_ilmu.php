<?php
/** @var array $bidang_ilmu */
?>
<div class="tab-pane fade show active" id="tab-bidang" role="tabpanel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Daftar Bidang Ilmu</h5>
            <p class="text-muted small mb-0">Klasifikasi rumpun ilmu untuk setiap pengajuan proposal.</p>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm"
            data-admin-modal-add-trigger
            data-admin-modal-target="#modal-tambah-bidang"
            data-admin-form-action="<?= site_url('admin/master-data-proposal/store-bidang-ilmu') ?>"
            data-admin-form-method="POST"
            data-admin-modal-title-text="Tambah Bidang Ilmu">
            <i class="bi bi-plus-lg me-2"></i>Tambah Bidang
        </button>
    </div>

    <?php if (empty($bidang_ilmu)): ?>
        <?= view('components/ui-empty-state', [
            'icon' => 'bi bi-book',
            'title' => 'Belum Ada Bidang Ilmu',
            'desc' => 'Data bidang ilmu akan muncul di sini setelah Anda menambahkannya.',
        ]) ?>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabelBidangIlmu">
                <thead>
                    <tr>
                        <th style="width: 60px;" class="text-center py-3">No</th>
                        <th class="py-3">Nama Bidang Ilmu</th>
                        <th style="width: 140px;" class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bidang_ilmu as $index => $item): ?>
                        <tr>
                            <td class="text-center text-muted small"><?= esc((string) ($index + 1)) ?></td>
                            <td><div class="fw-bold text-dark"><?= esc($item->nama) ?></div></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm"
                                        data-admin-fetch-url="<?= site_url('admin/master-data-proposal/json-bidang-ilmu/' . $item->uuid) ?>"
                                        data-admin-modal-target="#modal-edit-bidang"
                                        data-admin-form-action="<?= site_url('admin/master-data-proposal/update-bidang-ilmu/' . $item->uuid) ?>"
                                        data-admin-form-method="PUT"
                                        data-admin-modal-title-text="Edit Bidang Ilmu"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete"
                                        data-href="<?= site_url('admin/master-data-proposal/delete-bidang-ilmu/' . $item->uuid) ?>"
                                        data-delete-label="<?= esc($item->nama) ?>"
                                        data-delete-desc="Data bidang ilmu yang dihapus tidak dapat dikembalikan."
                                        title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
