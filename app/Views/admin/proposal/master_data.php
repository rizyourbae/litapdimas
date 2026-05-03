<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Kelola data pendukung proposal meliputi Bidang Ilmu, Klaster Bantuan, dan Tema Penelitian.',
            'badges' => [
                ['label' => 'Master Data', 'class' => 'text-bg-light border shadow-sm'],
                ['label' => 'Proposal', 'class' => 'text-bg-primary shadow-sm']
            ]
        ]) ?>
    </div>

    <div class="col-12 animate-fade-up delay-1">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-white p-0 border-bottom">
                <ul class="nav admin-nav-tabs px-3" id="masterDataTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active py-3" id="tab-bidang-link" data-bs-toggle="tab" href="#tab-bidang" role="tab">
                            <i class="bi bi-book-fill me-2"></i>Bidang Ilmu
                            <span class="badge bg-primary-soft text-primary rounded-pill ms-2"><?= esc((string) count($bidang_ilmu)) ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" id="tab-klaster-link" data-bs-toggle="tab" href="#tab-klaster" role="tab">
                            <i class="bi bi-layers-fill me-2"></i>Klaster Bantuan
                            <span class="badge bg-primary-soft text-primary rounded-pill ms-2"><?= esc((string) count($klaster_bantuan)) ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" id="tab-tema-link" data-bs-toggle="tab" href="#tab-tema" role="tab">
                            <i class="bi bi-lightbulb-fill me-2"></i>Tema Penelitian
                            <span class="badge bg-primary-soft text-primary rounded-pill ms-2"><?= esc((string) count($tema_penelitian)) ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="masterDataTabsContent">

                    <!-- TAB BIDANG ILMU -->
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

                    <!-- TAB KLASTER BANTUAN -->
                    <div class="tab-pane fade" id="tab-klaster" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Daftar Klaster Bantuan</h5>
                                <p class="text-muted small mb-0">Pengelompokan jenis bantuan atau skema penelitian.</p>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm"
                                data-admin-modal-add-trigger
                                data-admin-modal-target="#modal-tambah-klaster"
                                data-admin-form-action="<?= site_url('admin/master-data-proposal/store-klaster-bantuan') ?>"
                                data-admin-form-method="POST"
                                data-admin-modal-title-text="Tambah Klaster Bantuan">
                                <i class="bi bi-plus-lg me-2"></i>Tambah Klaster
                            </button>
                        </div>

                        <?php if (empty($klaster_bantuan)): ?>
                            <?= view('components/ui-empty-state', [
                                'icon' => 'bi bi-layers',
                                'title' => 'Belum Ada Klaster',
                                'desc' => 'Data klaster bantuan akan muncul di sini setelah Anda menambahkannya.',
                            ]) ?>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="tabelKlasterBantuan">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;" class="text-center py-3">No</th>
                                            <th class="py-3">Nama Klaster Bantuan</th>
                                            <th style="width: 140px;" class="text-center py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($klaster_bantuan as $index => $item): ?>
                                            <tr>
                                                <td class="text-center text-muted small"><?= esc((string) ($index + 1)) ?></td>
                                                <td><div class="fw-bold text-dark"><?= esc($item->nama) ?></div></td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm"
                                                            data-admin-fetch-url="<?= site_url('admin/master-data-proposal/json-klaster-bantuan/' . $item->uuid) ?>"
                                                            data-admin-modal-target="#modal-edit-klaster"
                                                            data-admin-form-action="<?= site_url('admin/master-data-proposal/update-klaster-bantuan/' . $item->uuid) ?>"
                                                            data-admin-form-method="PUT"
                                                            data-admin-modal-title-text="Edit Klaster Bantuan"
                                                            title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete"
                                                            data-href="<?= site_url('admin/master-data-proposal/delete-klaster-bantuan/' . $item->uuid) ?>"
                                                            data-delete-label="<?= esc($item->nama) ?>"
                                                            data-delete-desc="Data klaster bantuan yang dihapus tidak dapat dikembalikan."
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

                    <!-- TAB TEMA PENELITIAN -->
                    <div class="tab-pane fade" id="tab-tema" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Daftar Tema Penelitian</h5>
                                <p class="text-muted small mb-0">Daftar tema atau fokus riset yang tersedia bagi peneliti.</p>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm"
                                data-admin-modal-add-trigger
                                data-admin-modal-target="#modal-tambah-tema"
                                data-admin-form-action="<?= site_url('admin/master-data-proposal/store-tema-penelitian') ?>"
                                data-admin-form-method="POST"
                                data-admin-modal-title-text="Tambah Tema Penelitian">
                                <i class="bi bi-plus-lg me-2"></i>Tambah Tema
                            </button>
                        </div>

                        <?php if (empty($tema_penelitian)): ?>
                            <?= view('components/ui-empty-state', [
                                'icon' => 'bi bi-lightbulb',
                                'title' => 'Belum Ada Tema',
                                'desc' => 'Data tema penelitian akan muncul di sini setelah Anda menambahkannya.',
                            ]) ?>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="tabelTemaPenelitian">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;" class="text-center py-3">No</th>
                                            <th class="py-3">Nama Tema Penelitian</th>
                                            <th style="width: 140px;" class="text-center py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tema_penelitian as $index => $item): ?>
                                            <tr>
                                                <td class="text-center text-muted small"><?= esc((string) ($index + 1)) ?></td>
                                                <td><div class="fw-bold text-dark"><?= esc($item->nama) ?></div></td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button type="button" class="btn-action-sm bg-warning-soft text-warning shadow-sm"
                                                            data-admin-fetch-url="<?= site_url('admin/master-data-proposal/json-tema-penelitian/' . $item->uuid) ?>"
                                                            data-admin-modal-target="#modal-edit-tema"
                                                            data-admin-form-action="<?= site_url('admin/master-data-proposal/update-tema-penelitian/' . $item->uuid) ?>"
                                                            data-admin-form-method="PUT"
                                                            data-admin-modal-title-text="Edit Tema Penelitian"
                                                            title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <button type="button" class="btn-action-sm bg-danger-soft text-danger shadow-sm btn-delete"
                                                            data-href="<?= site_url('admin/master-data-proposal/delete-tema-penelitian/' . $item->uuid) ?>"
                                                            data-delete-label="<?= esc($item->nama) ?>"
                                                            data-delete-desc="Data tema penelitian yang dihapus tidak dapat dikembalikan."
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

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================== -->
<!-- MODAL TAMBAH/EDIT BIDANG ILMU -->
<!-- ======================================================================== -->
<!-- MODAL TAMBAH BIDANG ILMU -->
<div class="modal fade" id="modal-tambah-bidang" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="<?= site_url('admin/master-data-proposal/store-bidang-ilmu') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="POST">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold text-dark mb-0" data-admin-modal-title>Tambah Bidang Ilmu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nama_bidang" class="form-label fw-bold small text-muted text-uppercase ls-1">Nama Bidang Ilmu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border" id="nama_bidang" name="nama" required minlength="3" maxlength="100" placeholder="Masukkan nama bidang ilmu">
                        <div class="form-text mt-2 small">Contoh: Ilmu Komputer, Teknik Elektro, Ekonomi Pembangunan.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT BIDANG ILMU -->
<div class="modal fade" id="modal-edit-bidang" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form id="form-edit-bidang" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_bidang_id" name="id">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold text-dark mb-0" data-admin-modal-title>Edit Bidang Ilmu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_nama_bidang" class="form-label fw-bold small text-muted text-uppercase ls-1">Nama Bidang Ilmu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border" id="edit_nama_bidang" name="nama" required minlength="3" maxlength="100">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white rounded-pill px-4 shadow-sm">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH KLASTER BANTUAN -->
<div class="modal fade" id="modal-tambah-klaster" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="<?= site_url('admin/master-data-proposal/store-klaster-bantuan') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="POST">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold text-dark mb-0" data-admin-modal-title>Tambah Klaster Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nama_klaster" class="form-label fw-bold small text-muted text-uppercase ls-1">Nama Klaster Bantuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border" id="nama_klaster" name="nama" required minlength="3" maxlength="100" placeholder="Masukkan nama klaster bantuan">
                        <div class="form-text mt-2 small">Contoh: Klaster Kesehatan, Klaster Humaniora, Klaster Sains.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT KLASTER BANTUAN -->
<div class="modal fade" id="modal-edit-klaster" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form id="form-edit-klaster" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_klaster_id" name="id">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold text-dark mb-0" data-admin-modal-title>Edit Klaster Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_nama_klaster" class="form-label fw-bold small text-muted text-uppercase ls-1">Nama Klaster Bantuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border" id="edit_nama_klaster" name="nama" required minlength="3" maxlength="100">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white rounded-pill px-4 shadow-sm">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH TEMA PENELITIAN -->
<div class="modal fade" id="modal-tambah-tema" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="<?= site_url('admin/master-data-proposal/store-tema-penelitian') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="POST">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold text-dark mb-0" data-admin-modal-title>Tambah Tema Penelitian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nama_tema" class="form-label fw-bold small text-muted text-uppercase ls-1">Nama Tema Penelitian <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border" id="nama_tema" name="nama" required minlength="3" maxlength="100" placeholder="Masukkan nama tema penelitian">
                        <div class="form-text mt-2 small">Contoh: Kecerdasan Buatan, Energi Terbarukan, Ketahanan Pangan.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT TEMA PENELITIAN -->
<div class="modal fade" id="modal-edit-tema" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form id="form-edit-tema" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_tema_id" name="id">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold text-dark mb-0" data-admin-modal-title>Edit Tema Penelitian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_nama_tema" class="form-label fw-bold small text-muted text-uppercase ls-1">Nama Tema Penelitian <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border" id="edit_nama_tema" name="nama" required minlength="3" maxlength="100">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white rounded-pill px-4 shadow-sm">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>