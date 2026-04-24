<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$tabs = [
    'profesi' => [
        'label'      => 'Profesi',
        'icon'       => 'bi-person-badge',
        'items'      => $profesi ?? [],
        'fieldLabel' => 'Nama Profesi',
    ],
    'bidang-ilmu' => [
        'label'      => 'Bidang Ilmu',
        'icon'       => 'bi-book',
        'items'      => $bidangIlmu ?? [],
        'fieldLabel' => 'Nama Bidang Ilmu',
    ],
    'jabatan' => [
        'label'      => 'Jabatan Fungsional',
        'icon'       => 'bi-briefcase',
        'items'      => $jabatan ?? [],
        'fieldLabel' => 'Nama Jabatan',
    ],
];

$activeTab = session()->getFlashdata('active_tab') ?? 'profesi';
$openModal = session()->getFlashdata('open_modal') ?? null;
$errors    = session()->getFlashdata('errors') ?? [];
$BASE_URL  = site_url('admin/master/referensi');
?>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="referensiTabs" role="tablist">
                    <?php foreach ($tabs as $key => $tab): ?>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link <?= $key === $activeTab ? 'active' : '' ?>"
                                id="tab-<?= $key ?>-link"
                                data-bs-toggle="tab"
                                href="#tab-<?= $key ?>"
                                role="tab">
                                <i class="<?= $tab['icon'] ?> me-1"></i>
                                <?= $tab['label'] ?>
                                <span class="badge bg-secondary ms-1"><?= count($tab['items']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="referensiTabsContent">
                    <?php foreach ($tabs as $key => $tab):
                        $dtId  = 'dt-' . $key;
                        $skId  = 'sk-' . $key;
                        $rwId  = 'rw-' . $key;
                    ?>
                        <div class="tab-pane fade <?= $key === $activeTab ? 'show active' : '' ?>"
                            id="tab-<?= $key ?>" role="tabpanel">

                            <!-- Toolbar -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 text-muted">
                                    <i class="<?= $tab['icon'] ?> me-1"></i>
                                    Daftar <?= $tab['label'] ?>
                                    &mdash; <strong><?= count($tab['items']) ?></strong> data
                                </h6>
                                <button type="button" class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-tambah-<?= $key ?>">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah <?= $tab['label'] ?>
                                </button>
                            </div>

                            <?php if (empty($tab['items'])): ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <p class="mb-0">Belum ada data <?= $tab['label'] ?></p>
                                    <small>Klik tombol "Tambah" untuk menambahkan data baru</small>
                                </div>
                            <?php else: ?>
                                <!-- Skeleton + Real table wrapper -->
                                <div class="dt-skeleton-wrap">

                                    <!-- Skeleton overlay -->
                                    <div class="dt-skeleton-overlay" id="<?= $skId ?>">
                                        <table class="table table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:60px"></th>
                                                    <th></th>
                                                    <th style="width:110px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ([70, 55, 80, 45, 65] as $w): ?>
                                                    <tr>
                                                        <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                                        <td><span class="skeleton-line" style="width:<?= $w ?>%"></span></td>
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
                                    <div class="dt-real-wrap" id="<?= $rwId ?>">
                                        <table id="<?= $dtId ?>" class="table table-hover table-bordered align-middle w-100">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:60px" class="text-center">#</th>
                                                    <th><?= $tab['fieldLabel'] ?></th>
                                                    <th style="width:110px" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($tab['items'] as $no => $item): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $no + 1 ?></td>
                                                        <td>
                                                            <?= esc($item['nama']) ?>
                                                            <?php if (!empty($item['deleted_at'])): ?>
                                                                <span class="badge bg-secondary ms-1">Nonaktif</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center btn-action-group">
                                                            <?php if (!empty($item['deleted_at'])): ?>
                                                                <a href="#" class="btn btn-success btn-sm btn-restore"
                                                                    data-href="<?= site_url("admin/master/referensi/restore/$key/{$item['id']}") ?>"
                                                                    data-nama="<?= esc($item['nama']) ?>"
                                                                    title="Pulihkan">
                                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                <button type="button" class="btn btn-warning btn-sm btn-edit"
                                                                    data-type="<?= $key ?>"
                                                                    data-id="<?= $item['id'] ?>"
                                                                    data-nama="<?= esc($item['nama']) ?>"
                                                                    title="Edit">
                                                                    <i class="bi bi-pencil"></i>
                                                                </button>
                                                                <a href="#" class="btn btn-danger btn-sm btn-delete"
                                                                    data-href="<?= site_url("admin/master/referensi/delete/$key/{$item['id']}") ?>"
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===================== MODALS: Tambah ===================== -->
<?php foreach ($tabs as $key => $tab):
    $hasError = $openModal === 'tambah-' . $key;
?>
    <div class="modal fade" id="modal-tambah-<?= $key ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= site_url("admin/master/referensi/store/$key") ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="<?= $tab['icon'] ?> me-2"></i>Tambah <?= $tab['label'] ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php if ($hasError && !empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <ul class="mb-0 ps-3">
                                    <?php foreach ((array)$errors as $e): ?>
                                        <li><?= esc($e) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <?= $tab['fieldLabel'] ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nama"
                                class="form-control <?= ($hasError && !empty($errors['nama'])) ? 'is-invalid' : '' ?>"
                                value="<?= $hasError ? esc(old('nama', '')) : '' ?>"
                                placeholder="Masukkan <?= strtolower($tab['fieldLabel']) ?>"
                                autofocus required>
                            <?php if ($hasError && !empty($errors['nama'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['nama']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- ===================== MODAL: Edit (dinamis) ===================== -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" id="form-edit">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" id="editFieldLabel">Nama</label>
                        <input type="text" name="nama" id="editNamaInput"
                            class="form-control" placeholder="Masukkan nama" required>
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

        const TYPE_LABELS = {
            'profesi': 'Profesi',
            'bidang-ilmu': 'Bidang Ilmu',
            'jabatan': 'Jabatan Fungsional',
        };
        const FIELD_LABELS = {
            'profesi': 'Nama Profesi',
            'bidang-ilmu': 'Nama Bidang Ilmu',
            'jabatan': 'Nama Jabatan Fungsional',
        };

        // =============================================
        // DataTables — inisialisasi tab aktif langsung,
        // tab lain lazy saat pertama dibuka
        // =============================================
        const DT_MAP = {
            'profesi': ['dt-profesi', 'sk-profesi', 'rw-profesi'],
            'bidang-ilmu': ['dt-bidang-ilmu', 'sk-bidang-ilmu', 'rw-bidang-ilmu'],
            'jabatan': ['dt-jabatan', 'sk-jabatan', 'rw-jabatan'],
        };
        const DT_OPTS = {
            columnDefs: [{
                orderable: false,
                targets: [0, 2]
            }]
        };

        // Init tab yang sedang aktif
        const initActiveTab = '<?= $activeTab ?>';
        if (DT_MAP[initActiveTab]) {
            DtManager.initLazy(DT_MAP[initActiveTab][0], DT_OPTS, DT_MAP[initActiveTab][1], DT_MAP[initActiveTab][2]);
        }

        // Lazy init + adjust saat tab dibuka
        document.querySelectorAll('#referensiTabs a[data-bs-toggle="tab"]').forEach(function(el) {
            el.addEventListener('shown.bs.tab', function(e) {
                const key = e.target.getAttribute('href').replace('#tab-', '');
                if (DT_MAP[key]) {
                    DtManager.initLazy(DT_MAP[key][0], DT_OPTS, DT_MAP[key][1], DT_MAP[key][2]);
                    DtManager.adjust(DT_MAP[key][0]);
                }
            });
        });

        // =============================================
        // Tombol Edit — Event Delegation
        // (Biar tetap bekerja setelah DataTable re-render)
        // =============================================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-edit');
            if (!btn) return;

            e.preventDefault();

            const type = btn.dataset.type;
            document.getElementById('editFieldLabel').textContent = FIELD_LABELS[type] ?? 'Nama';
            document.getElementById('editNamaInput').value = btn.dataset.nama;
            document.getElementById('modalEditLabel').innerHTML =
                `<i class="bi bi-pencil-square me-2"></i>Edit ${TYPE_LABELS[type] ?? type}`;
            document.getElementById('form-edit').action =
                `${BASE_URL}/update/${type}/${btn.dataset.id}`;

            bootstrap.Modal.getOrCreateInstance(document.getElementById('modal-edit')).show();
        });

        // =============================================
        // Tombol Hapus → SweetAlert — Event Delegation
        // =============================================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete');
            if (!btn) return;

            e.preventDefault();
            SwalDelete(btn.dataset.href, btn.dataset.nama);
        });

        // =============================================
        // Tombol Restore → SweetAlert — Event Delegation
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