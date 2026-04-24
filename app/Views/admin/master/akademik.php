<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$activeTab        = session()->getFlashdata('active_tab') ?? 'fakultas';
$openModal        = session()->getFlashdata('open_modal') ?? null;
$errors           = session()->getFlashdata('errors') ?? [];
$hasFakultasError = $openModal === 'tambah-fakultas';
$hasProdiError    = $openModal === 'tambah-prodi';
$BASE_URL         = site_url('admin/master/akademik');
?>

<div class="row">
    <div class="col-12">
        <div class="card card-success card-outline card-outline-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="akademikTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?= $activeTab === 'fakultas' ? 'active' : '' ?>"
                            id="tab-fakultas-link"
                            data-bs-toggle="tab"
                            href="#tab-fakultas"
                            role="tab">
                            <i class="bi bi-building me-1"></i>
                            Fakultas
                            <span class="badge bg-secondary ms-1"><?= count($fakultas) ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?= $activeTab === 'prodi' ? 'active' : '' ?>"
                            id="tab-prodi-link"
                            data-bs-toggle="tab"
                            href="#tab-prodi"
                            role="tab">
                            <i class="bi bi-mortarboard me-1"></i>
                            Program Studi
                            <span class="badge bg-secondary ms-1"><?= count($prodi) ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="akademikTabsContent">

                    <!-- ==================== TAB: FAKULTAS ==================== -->
                    <div class="tab-pane fade <?= $activeTab === 'fakultas' ? 'show active' : '' ?>"
                        id="tab-fakultas" role="tabpanel">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-muted">
                                <i class="bi bi-building me-1"></i>
                                Daftar Fakultas &mdash; <strong><?= count($fakultas) ?></strong> data
                            </h6>
                            <button type="button" class="btn btn-success btn-sm"
                                data-bs-toggle="modal" data-bs-target="#modal-tambah-fakultas">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Fakultas
                            </button>
                        </div>

                        <?php if (empty($fakultas)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-building fs-1 d-block mb-2"></i>
                                <p class="mb-0">Belum ada data Fakultas</p>
                                <small>Tambahkan fakultas terlebih dahulu sebelum menambah Program Studi</small>
                            </div>
                        <?php else: ?>
                            <div class="dt-skeleton-wrap">
                                <!-- Skeleton -->
                                <div class="dt-skeleton-overlay" id="sk-fakultas">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px"></th>
                                                <th></th>
                                                <th style="width:100px"></th>
                                                <th style="width:110px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ([65, 80, 50, 72, 45] as $w): ?>
                                                <tr>
                                                    <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                                    <td><span class="skeleton-line" style="width:<?= $w ?>%"></span></td>
                                                    <td class="text-center"><span class="skeleton-line mx-auto" style="width:50px;border-radius:20px;height:20px"></span></td>
                                                    <td class="text-center"><span class="skeleton-btn me-1"></span><span class="skeleton-btn"></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Real Table -->
                                <div class="dt-real-wrap" id="rw-fakultas">
                                    <table id="dt-fakultas" class="table table-hover table-bordered align-middle w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px" class="text-center">#</th>
                                                <th>Nama Fakultas</th>
                                                <th style="width:100px" class="text-center">Prodi</th>
                                                <th style="width:110px" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($fakultas as $no => $item):
                                                $jumlahProdi = count(array_filter($prodi, fn($p) => $p['fakultas_id'] == $item['id']));
                                            ?>
                                                <tr>
                                                    <td class="text-center"><?= $no + 1 ?></td>
                                                    <td>
                                                        <?= esc($item['nama']) ?>
                                                        <?php if (!empty($item['deleted_at'])): ?>
                                                            <span class="badge bg-secondary ms-1">Nonaktif</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($jumlahProdi > 0): ?>
                                                            <span class="badge bg-info"><?= $jumlahProdi ?> Prodi</span>
                                                        <?php else: ?>
                                                            <span class="text-muted small">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center btn-action-group">
                                                        <?php if (!empty($item['deleted_at'])): ?>
                                                            <a href="#" class="btn btn-success btn-sm btn-restore"
                                                                data-href="<?= site_url("admin/master/akademik/restore/fakultas/{$item['id']}") ?>"
                                                                data-nama="<?= esc($item['nama']) ?>"
                                                                title="Pulihkan">
                                                                <i class="bi bi-arrow-counterclockwise"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-warning btn-sm btn-edit-fakultas"
                                                                data-id="<?= $item['id'] ?>"
                                                                data-nama="<?= esc($item['nama']) ?>"
                                                                title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                                data-href="<?= site_url("admin/master/akademik/delete/fakultas/{$item['id']}") ?>"
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

                    <!-- ==================== TAB: PROGRAM STUDI ==================== -->
                    <div class="tab-pane fade <?= $activeTab === 'prodi' ? 'show active' : '' ?>"
                        id="tab-prodi" role="tabpanel">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-muted">
                                <i class="bi bi-mortarboard me-1"></i>
                                Daftar Program Studi &mdash; <strong><?= count($prodi) ?></strong> data
                            </h6>
                            <?php if (!empty($fakultas)): ?>
                                <button type="button" class="btn btn-success btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#modal-tambah-prodi">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah Program Studi
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled
                                    title="Tambahkan Fakultas terlebih dahulu">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah Program Studi
                                </button>
                            <?php endif; ?>
                        </div>

                        <?php if (empty($prodi)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-mortarboard fs-1 d-block mb-2"></i>
                                <p class="mb-0">Belum ada data Program Studi</p>
                                <small>Pastikan Fakultas sudah ditambahkan terlebih dahulu</small>
                            </div>
                        <?php else: ?>
                            <div class="dt-skeleton-wrap">
                                <!-- Skeleton -->
                                <div class="dt-skeleton-overlay" id="sk-prodi">
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
                                            <?php foreach ([70, 50, 65, 45, 80] as $w): ?>
                                                <tr>
                                                    <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                                    <td><span class="skeleton-line" style="width:<?= $w ?>%"></span></td>
                                                    <td><span class="skeleton-line" style="width:55%;border-radius:20px;height:20px"></span></td>
                                                    <td class="text-center"><span class="skeleton-btn me-1"></span><span class="skeleton-btn"></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Real Table -->
                                <div class="dt-real-wrap" id="rw-prodi">
                                    <table id="dt-prodi" class="table table-hover table-bordered align-middle w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:60px" class="text-center">#</th>
                                                <th>Nama Program Studi</th>
                                                <th>Fakultas</th>
                                                <th style="width:110px" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($prodi as $no => $item): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no + 1 ?></td>
                                                    <td>
                                                        <?= esc($item['nama']) ?>
                                                        <?php if (!empty($item['deleted_at'])): ?>
                                                            <span class="badge bg-secondary ms-1">Nonaktif</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($item['nama_fakultas'])): ?>
                                                            <span class="badge bg-light text-dark border">
                                                                <i class="bi bi-building me-1"></i><?= esc($item['nama_fakultas']) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted small"><i>Tidak terdata</i></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center btn-action-group">
                                                        <?php if (!empty($item['deleted_at'])): ?>
                                                            <a href="#" class="btn btn-success btn-sm btn-restore"
                                                                data-href="<?= site_url("admin/master/akademik/restore/prodi/{$item['id']}") ?>"
                                                                data-nama="<?= esc($item['nama']) ?>"
                                                                title="Pulihkan">
                                                                <i class="bi bi-arrow-counterclockwise"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-warning btn-sm btn-edit-prodi"
                                                                data-id="<?= $item['id'] ?>"
                                                                data-nama="<?= esc($item['nama']) ?>"
                                                                data-fakultas-id="<?= $item['fakultas_id'] ?>"
                                                                title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                                data-href="<?= site_url("admin/master/akademik/delete/prodi/{$item['id']}") ?>"
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
    </div>
</div>

<!-- ================ MODAL: Tambah Fakultas ================ -->
<div class="modal fade" id="modal-tambah-fakultas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/master/akademik/store/fakultas') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-building me-2"></i>Tambah Fakultas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if ($hasFakultasError && !empty($errors)): ?>
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
                            Nama Fakultas <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama"
                            class="form-control <?= ($hasFakultasError && !empty($errors['nama'])) ? 'is-invalid' : '' ?>"
                            value="<?= $hasFakultasError ? esc(old('nama', '')) : '' ?>"
                            placeholder="Contoh: Fakultas Teknik"
                            required>
                        <?php if ($hasFakultasError && !empty($errors['nama'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['nama']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================ MODAL: Edit Fakultas ================ -->
<div class="modal fade" id="modal-edit-fakultas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" id="form-edit-fakultas">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Fakultas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Fakultas <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editFakultasNama" class="form-control"
                            placeholder="Nama Fakultas" required>
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

<!-- ================ MODAL: Tambah Program Studi ================ -->
<div class="modal fade" id="modal-tambah-prodi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/master/akademik/store/prodi') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-mortarboard me-2"></i>Tambah Program Studi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if ($hasProdiError && !empty($errors)): ?>
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
                            Nama Program Studi <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama"
                            class="form-control"
                            value="<?= $hasProdiError ? esc(old('nama', '')) : '' ?>"
                            placeholder="Contoh: Teknik Informatika"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Fakultas <span class="text-danger">*</span>
                        </label>
                        <select name="fakultas_id" class="form-select" data-select2 required>
                            <option value="">-- Pilih Fakultas --</option>
                            <?php foreach ($fakultasOptions as $fak): ?>
                                <option value="<?= $fak['id'] ?>"
                                    <?= ($hasProdiError && old('fakultas_id') == $fak['id']) ? 'selected' : '' ?>>
                                    <?= esc($fak['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================ MODAL: Edit Program Studi ================ -->
<div class="modal fade" id="modal-edit-prodi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" id="form-edit-prodi">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Program Studi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Program Studi <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editProdiNama" class="form-control"
                            placeholder="Nama Program Studi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fakultas <span class="text-danger">*</span></label>
                        <select name="fakultas_id" id="editProdiFakultasId" class="form-select" data-select2 required>
                            <option value="">-- Pilih Fakultas --</option>
                            <?php foreach ($fakultasOptions as $fak): ?>
                                <option value="<?= $fak['id'] ?>"><?= esc($fak['nama']) ?></option>
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
                targets: [0, 2, 3]
            }]
        };

        // =============================================
        // DataTables — init tab aktif, lazy tab lain
        // =============================================
        const initActiveTab = '<?= $activeTab ?>';
        if (initActiveTab === 'fakultas') {
            DtManager.initLazy('dt-fakultas', DT_OPTS, 'sk-fakultas', 'rw-fakultas');
        } else {
            DtManager.initLazy('dt-prodi', {
                columnDefs: [{
                    orderable: false,
                    targets: [0, 3]
                }]
            }, 'sk-prodi', 'rw-prodi');
        }

        document.querySelectorAll('#akademikTabs a[data-bs-toggle="tab"]').forEach(function(el) {
            el.addEventListener('shown.bs.tab', function(e) {
                const key = e.target.getAttribute('href').replace('#tab-', '');
                if (key === 'fakultas') {
                    DtManager.initLazy('dt-fakultas', DT_OPTS, 'sk-fakultas', 'rw-fakultas');
                    DtManager.adjust('dt-fakultas');
                } else if (key === 'prodi') {
                    DtManager.initLazy('dt-prodi', {
                        columnDefs: [{
                            orderable: false,
                            targets: [0, 3]
                        }]
                    }, 'sk-prodi', 'rw-prodi');
                    DtManager.adjust('dt-prodi');
                }
            });
        });

        // =============================================
        // Select2 — init saat modal terbuka
        // =============================================
        ['modal-tambah-prodi', 'modal-edit-prodi'].forEach(function(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('show.bs.modal', function() {
                Select2Init.initModal(this);
            });
        });

        // =============================================
        // Edit Fakultas — Event Delegation
        // =============================================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-edit-fakultas');
            if (!btn) return;

            e.preventDefault();
            document.getElementById('editFakultasNama').value = btn.dataset.nama;
            document.getElementById('form-edit-fakultas').action =
                `${BASE_URL}/update/fakultas/${btn.dataset.id}`;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-edit-fakultas')).show();
        });

        // =============================================
        // Edit Program Studi — Event Delegation
        // =============================================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-edit-prodi');
            if (!btn) return;

            e.preventDefault();
            const fakultasId = btn.dataset.fakultasId;
            document.getElementById('editProdiNama').value = btn.dataset.nama;
            document.getElementById('form-edit-prodi').action =
                `${BASE_URL}/update/prodi/${btn.dataset.id}`;

            const modal = document.getElementById('modal-edit-prodi');
            // Set Select2 value setelah modal shown (Select2 init pada show.bs.modal)
            modal.addEventListener('shown.bs.modal', function() {
                Select2Init.setValue('#editProdiFakultasId', fakultasId);
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
        // Auto-buka tab dari flash
        // =============================================
        const activeTab = <?= json_encode($activeTab) ?>;
        if (activeTab) {
            const tabEl = document.getElementById(`tab-${activeTab}-link`);
            if (tabEl) bootstrap.Tab.getOrCreateInstance(tabEl).show();
        }

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