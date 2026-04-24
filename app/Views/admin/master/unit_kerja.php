<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$activeTab = session()->getFlashdata('active_tab') ?? 'unit-kerja';
$openModal = session()->getFlashdata('open_modal') ?? null;
$errors    = session()->getFlashdata('errors') ?? [];
$BASE_URL  = site_url('admin/master/unit_kerja');
?>

<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-diagram-3 me-2"></i>Manajemen Unit Kerja
                    </h3>
                    <button type="button" class="btn btn-info btn-sm"
                        data-bs-toggle="modal" data-bs-target="#modal-tambah">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Unit Kerja
                    </button>
                </div>
            </div>
            <div class="card-body">

                <!-- Info hirarki -->
                <div class="alert alert-light border-start border-4 border-info mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Struktur Hierarki:</strong> Unit dengan indentasi adalah sub-unit dari unit di atasnya
                </div>

                <?php if (empty($items)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-diagram-3 fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada data Unit Kerja</p>
                        <small>Klik tombol "Tambah Unit Kerja" untuk memulai</small>
                    </div>
                <?php else: ?>
                    <div class="dt-skeleton-wrap">
                        <!-- Skeleton overlay -->
                        <div class="dt-skeleton-overlay" id="sk-unit-kerja">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px"></th>
                                        <th></th>
                                        <th></th>
                                        <th style="width:110px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ([70, 50, 65, 80, 45] as $w): ?>
                                        <tr>
                                            <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                            <td><span class="skeleton-line" style="width:<?= $w ?>%"></span></td>
                                            <td><span class="skeleton-line" style="width:60%"></span></td>
                                            <td class="text-center">
                                                <span class="skeleton-btn me-1"></span>
                                                <span class="skeleton-btn"></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Real DataTable -->
                        <div class="dt-real-wrap" id="rw-unit-kerja">
                            <table id="dt-unit-kerja" class="table table-hover table-bordered align-middle w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px" class="text-center">#</th>
                                        <th>Nama Unit Kerja</th>
                                        <th>Unit Induk</th>
                                        <th style="width:110px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $no => $item): ?>
                                        <tr>
                                            <td class="text-center"><?= $no + 1 ?></td>
                                            <td>
                                                <?php if (empty($item['parent_id'])): ?>
                                                    <strong><?= esc($item['nama']) ?></strong>
                                                <?php else: ?>
                                                    <span class="ms-3 me-1">└</span><?= esc($item['nama']) ?>
                                                <?php endif; ?>
                                                <?php if (!empty($item['deleted_at'])): ?>
                                                    <span class="badge bg-secondary ms-1">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($item['nama_induk'])): ?>
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="bi bi-diagram-2 me-1"></i><?= esc($item['nama_induk']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted small"><i>— Tidak ada —</i></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center btn-action-group">
                                                <?php if (!empty($item['deleted_at'])): ?>
                                                    <a href="#" class="btn btn-success btn-sm btn-restore"
                                                        data-href="<?= site_url("admin/master/unit_kerja/restore/{$item['id']}") ?>"
                                                        data-nama="<?= esc($item['nama']) ?>"
                                                        title="Pulihkan">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-warning btn-sm btn-edit"
                                                        data-id="<?= $item['id'] ?>"
                                                        data-nama="<?= esc($item['nama']) ?>"
                                                        data-parent-id="<?= $item['parent_id'] ?>"
                                                        title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                        data-href="<?= site_url("admin/master/unit_kerja/delete/{$item['id']}") ?>"
                                                        data-nama="<?= esc($item['nama']) ?>"
                                                        title="Hapus">
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
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- ================ MODAL: Tambah Unit Kerja ================ -->
<div class="modal fade" id="modal-tambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/master/unit_kerja/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Unit Kerja
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if ($openModal === 'tambah' && !empty($errors)): ?>
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3">
                                <?php foreach ((array)$errors as $e): ?>
                                    <li><?= esc($e) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Unit Kerja <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama"
                            class="form-control <?= ($openModal === 'tambah' && !empty($errors['nama'])) ? 'is-invalid' : '' ?>"
                            value="<?= $openModal === 'tambah' ? esc(old('nama', '')) : '' ?>"
                            placeholder="Contoh: Rektorat"
                            required>
                        <?php if ($openModal === 'tambah' && !empty($errors['nama'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['nama']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Unit Induk <span class="text-muted">(Opsional)</span>
                        </label>
                        <select name="parent_id" class="form-select" data-select2>
                            <option value="">-- Tidak ada (Root Unit) --</option>
                            <?php foreach ($options as $unit): ?>
                                <option value="<?= $unit['id'] ?>"
                                    <?= ($openModal === 'tambah' && old('parent_id') == $unit['id']) ? 'selected' : '' ?>>
                                    <?= esc($unit['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================ MODAL: Edit Unit Kerja ================ -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" id="form-edit">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Unit Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editNama" class="form-control"
                            placeholder="Nama Unit Kerja" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Unit Induk <span class="text-muted">(Opsional)</span></label>
                        <select name="parent_id" id="editParentId" class="form-select" data-select2>
                            <option value="">-- Tidak ada (Root Unit) --</option>
                            <?php foreach ($options as $unit): ?>
                                <option value="<?= $unit['id'] ?>"><?= esc($unit['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function() {
        'use strict';

        const BASE_URL = '<?= $BASE_URL ?>';
        const DT_OPTS = {
            columnDefs: [{
                orderable: false,
                targets: [0, 3]
            }]
        };

        // =============================================
        // DataTables — init pada dokumen siap
        // =============================================
        DtManager.initLazy('dt-unit-kerja', DT_OPTS, 'sk-unit-kerja', 'rw-unit-kerja');

        // =============================================
        // Select2 — init saat modal terbuka
        // =============================================
        ['modal-tambah', 'modal-edit'].forEach(function(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('show.bs.modal', function() {
                Select2Init.initModal(this);
            });
        });

        // =============================================
        // Edit Unit Kerja — Event Delegation
        // =============================================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-edit');
            if (!btn) return;

            e.preventDefault();
            const parentId = btn.dataset.parentId || '';
            document.getElementById('editNama').value = btn.dataset.nama;
            document.getElementById('form-edit').action = `${BASE_URL}/update/${btn.dataset.id}`;

            const modal = document.getElementById('modal-edit');
            // Set Select2 value setelah modal shown
            modal.addEventListener('shown.bs.modal', function() {
                Select2Init.setValue('#editParentId', parentId);
            }, {
                once: true
            });

            bootstrap.Modal.getOrCreateInstance(modal).show();
        });

        // =============================================
        // Hapus → SweetAlert — Event Delegation
        // =============================================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete');
            if (!btn) return;

            e.preventDefault();
            SwalDelete(btn.dataset.href, btn.dataset.nama);
        });

        // =============================================
        // Restore → SweetAlert — Event Delegation
        // =============================================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-restore');
            if (!btn) return;

            e.preventDefault();
            SwalConfirm(
                btn.dataset.href,
                'Pulihkan data ini?',
                `Data <strong>${btn.dataset.nama}</strong> akan diaktifkan kembali.`,
                '<i class="bi bi-arrow-counterclockwise me-1"></i>Ya, pulihkan!'
            );
        });

        // =============================================
        // Auto-buka modal jika ada validation error
        // =============================================
        const openModal = <?= json_encode($openModal) ?>;
        if (openModal) {
            const modalEl = document.getElementById(`modal-${openModal}`);
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }

    })();
</script>
<?= $this->endSection() ?>