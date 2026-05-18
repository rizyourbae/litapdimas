<?php
/** @var array $kontribusi_prodi */
?>
<div class="tab-pane fade" id="tab-kontribusi" role="tabpanel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold text-dark mb-1">Daftar Kontribusi Prodi</h5>
            <p class="text-muted small mb-0">Klasifikasi bentuk dukungan atau output kontribusi program studi terhadap penelitian.</p>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm"
            data-admin-modal-add-trigger
            data-admin-modal-target="#modal-tambah-kontribusi"
            data-admin-form-action="<?= site_url('admin/master-data-proposal/store-kontribusi-prodi') ?>"
            data-admin-form-method="POST"
            data-admin-modal-title-text="Tambah Kontribusi Prodi">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kontribusi
        </button>
    </div>

    <?php if (empty($kontribusi_prodi)): ?>
        <?= view('components/ui-empty-state', [
            'icon' => 'bi bi-building-up',
            'title' => 'Belum Ada Kontribusi Prodi',
            'desc' => 'Data kontribusi prodi akan muncul di sini setelah Anda menambahkannya.',
        ]) ?>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabelKontribusiProdi">
                <thead>
                    <tr>
                        <th style="width: 60px;" class="text-center py-3">No</th>
                        <th class="py-3">Nama Kontribusi Prodi</th>
                        <th style="width: 140px;" class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kontribusi_prodi as $index => $item): ?>
                        <tr>
                            <td class="text-center text-muted small"><?= esc((string) ($index + 1)) ?></td>
                            <td><div class="fw-bold text-dark"><?= esc($item->nama) ?></div></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm"
                                        data-admin-fetch-url="<?= site_url('admin/master-data-proposal/json-kontribusi-prodi/' . $item->uuid) ?>"
                                        data-admin-modal-target="#modal-edit-kontribusi"
                                        data-admin-form-action="<?= site_url('admin/master-data-proposal/update-kontribusi-prodi/' . $item->uuid) ?>"
                                        data-admin-form-method="PUT"
                                        data-admin-modal-title-text="Edit Kontribusi Prodi"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete"
                                        data-href="<?= site_url('admin/master-data-proposal/delete-kontribusi-prodi/' . $item->uuid) ?>"
                                        data-delete-label="<?= esc($item->nama) ?>"
                                        data-delete-desc="Data kontribusi prodi yang dihapus tidak dapat dikembalikan."
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
