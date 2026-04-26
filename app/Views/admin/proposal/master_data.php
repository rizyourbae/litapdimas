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
                            <span class="badge text-bg-primary px-3 py-2">Proposal</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($title) ?></h2>
                        <p class="text-muted mb-0">Kelola data master untuk proposal: Bidang Ilmu, Klaster Bantuan, dan Tema Penelitian</p>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="text-end">
                            <small class="text-muted d-block">Total Data</small>
                            <strong class="text-primary"><?= esc($counts['bidang_ilmu_count'] + $counts['klaster_bantuan_count'] + $counts['tema_penelitian_count']) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline admin-table-card card-outline-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="masterDataTabs" role="tablist">
                    <!-- Tab Bidang Ilmu -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="tab-bidang-link" data-bs-toggle="tab" href="#tab-bidang" role="tab">
                            <i class="bi bi-book me-1"></i>
                            Bidang Ilmu
                            <span class="badge bg-secondary ms-1"><?= esc((string) count($bidang_ilmu)) ?></span>
                        </a>
                    </li>
                    <!-- Tab Klaster Bantuan -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-klaster-link" data-bs-toggle="tab" href="#tab-klaster" role="tab">
                            <i class="bi bi-layers me-1"></i>
                            Klaster Bantuan
                            <span class="badge bg-secondary ms-1"><?= esc((string) count($klaster_bantuan)) ?></span>
                        </a>
                    </li>
                    <!-- Tab Tema Penelitian -->
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-tema-link" data-bs-toggle="tab" href="#tab-tema" role="tab">
                            <i class="bi bi-lightbulb me-1"></i>
                            Tema Penelitian
                            <span class="badge bg-secondary ms-1"><?= esc((string) count($tema_penelitian)) ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="masterDataTabsContent">

                    <!-- ======================================================================== -->
                    <!-- TAB BIDANG ILMU -->
                    <!-- ======================================================================== -->
                    <div class="tab-pane fade show active" id="tab-bidang" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-muted">
                                <i class="bi bi-book me-1"></i>
                                Daftar Bidang Ilmu
                                &mdash; <strong><?= esc((string) count($bidang_ilmu)) ?></strong> data
                            </h6>
                            <button type="button" class="btn btn-primary btn-sm"
                                data-admin-modal-add-trigger
                                data-admin-modal-target="#modal-tambah-bidang"
                                data-admin-form-action="<?= site_url('admin/master-data-proposal/store-bidang-ilmu') ?>"
                                data-admin-form-method="POST"
                                data-admin-modal-title-text="Tambah Bidang Ilmu">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Bidang Ilmu
                            </button>
                        </div>

                        <?php if (empty($bidang_ilmu)): ?>
                            <div class="dosen-empty-state">
                                <i class="bi bi-inbox"></i>
                                <p class="mb-1">Belum ada data Bidang Ilmu</p>
                                <small>Klik tombol tambah untuk membuat data baru.</small>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tabelBidangIlmu">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;" class="text-center">No</th>
                                            <th>Nama Bidang Ilmu</th>
                                            <th style="width: 120px;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bidang_ilmu as $index => $item): ?>
                                            <tr>
                                                <td class="text-center"><?= esc((string) ($index + 1)) ?></td>
                                                <td><strong><?= esc($item->nama) ?></strong></td>
                                                <td class="text-center">
                                                    <div class="admin-action-inline admin-action-group">
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            data-admin-fetch-url="<?= site_url('admin/master-data-proposal/json-bidang-ilmu/' . $item->uuid) ?>"
                                                            data-admin-modal-target="#modal-edit-bidang"
                                                            data-admin-form-action="<?= site_url('admin/master-data-proposal/update-bidang-ilmu/' . $item->uuid) ?>"
                                                            data-admin-form-method="PUT"
                                                            data-admin-modal-title-text="Edit Bidang Ilmu">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form action="<?= site_url('admin/master-data-proposal/delete-bidang-ilmu/' . $item->uuid) ?>" method="post" class="m-0">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Bidang Ilmu">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- ======================================================================== -->
                    <!-- TAB KLASTER BANTUAN -->
                    <!-- ======================================================================== -->
                    <div class="tab-pane fade" id="tab-klaster" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-muted">
                                <i class="bi bi-layers me-1"></i>
                                Daftar Klaster Bantuan
                                &mdash; <strong><?= esc((string) count($klaster_bantuan)) ?></strong> data
                            </h6>
                            <button type="button" class="btn btn-primary btn-sm"
                                data-admin-modal-add-trigger
                                data-admin-modal-target="#modal-tambah-klaster"
                                data-admin-form-action="<?= site_url('admin/master-data-proposal/store-klaster-bantuan') ?>"
                                data-admin-form-method="POST"
                                data-admin-modal-title-text="Tambah Klaster Bantuan">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Klaster Bantuan
                            </button>
                        </div>

                        <?php if (empty($klaster_bantuan)): ?>
                            <div class="dosen-empty-state">
                                <i class="bi bi-inbox"></i>
                                <p class="mb-1">Belum ada data Klaster Bantuan</p>
                                <small>Klik tombol tambah untuk membuat data baru.</small>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tabelKlasterBantuan">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;" class="text-center">No</th>
                                            <th>Nama Klaster Bantuan</th>
                                            <th style="width: 120px;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($klaster_bantuan as $index => $item): ?>
                                            <tr>
                                                <td class="text-center"><?= esc((string) ($index + 1)) ?></td>
                                                <td><strong><?= esc($item->nama) ?></strong></td>
                                                <td class="text-center">
                                                    <div class="admin-action-inline admin-action-group">
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            data-admin-fetch-url="<?= site_url('admin/master-data-proposal/json-klaster-bantuan/' . $item->uuid) ?>"
                                                            data-admin-modal-target="#modal-edit-klaster"
                                                            data-admin-form-action="<?= site_url('admin/master-data-proposal/update-klaster-bantuan/' . $item->uuid) ?>"
                                                            data-admin-form-method="PUT"
                                                            data-admin-modal-title-text="Edit Klaster Bantuan">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form action="<?= site_url('admin/master-data-proposal/delete-klaster-bantuan/' . $item->uuid) ?>" method="post" class="m-0">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Klaster Bantuan">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- ======================================================================== -->
                    <!-- TAB TEMA PENELITIAN -->
                    <!-- ======================================================================== -->
                    <div class="tab-pane fade" id="tab-tema" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-muted">
                                <i class="bi bi-lightbulb me-1"></i>
                                Daftar Tema Penelitian
                                &mdash; <strong><?= esc((string) count($tema_penelitian)) ?></strong> data
                            </h6>
                            <button type="button" class="btn btn-primary btn-sm"
                                data-admin-modal-add-trigger
                                data-admin-modal-target="#modal-tambah-tema"
                                data-admin-form-action="<?= site_url('admin/master-data-proposal/store-tema-penelitian') ?>"
                                data-admin-form-method="POST"
                                data-admin-modal-title-text="Tambah Tema Penelitian">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Tema Penelitian
                            </button>
                        </div>

                        <?php if (empty($tema_penelitian)): ?>
                            <div class="dosen-empty-state">
                                <i class="bi bi-inbox"></i>
                                <p class="mb-1">Belum ada data Tema Penelitian</p>
                                <small>Klik tombol tambah untuk membuat data baru.</small>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tabelTemaPenelitian">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;" class="text-center">No</th>
                                            <th>Nama Tema Penelitian</th>
                                            <th style="width: 120px;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tema_penelitian as $index => $item): ?>
                                            <tr>
                                                <td class="text-center"><?= esc((string) ($index + 1)) ?></td>
                                                <td><strong><?= esc($item->nama) ?></strong></td>
                                                <td class="text-center">
                                                    <div class="admin-action-inline admin-action-group">
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            data-admin-fetch-url="<?= site_url('admin/master-data-proposal/json-tema-penelitian/' . $item->uuid) ?>"
                                                            data-admin-modal-target="#modal-edit-tema"
                                                            data-admin-form-action="<?= site_url('admin/master-data-proposal/update-tema-penelitian/' . $item->uuid) ?>"
                                                            data-admin-form-method="PUT"
                                                            data-admin-modal-title-text="Edit Tema Penelitian">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form action="<?= site_url('admin/master-data-proposal/delete-tema-penelitian/' . $item->uuid) ?>" method="post" class="m-0">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Tema Penelitian">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
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
<div class="modal fade" id="modal-tambah-bidang" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/master-data-proposal/store-bidang-ilmu') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title" data-admin-modal-title>Tambah Bidang Ilmu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_bidang" class="form-label">Nama Bidang Ilmu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_bidang" name="nama" required minlength="3" maxlength="100" placeholder="Contoh: Ilmu Komputer">
                        <small class="form-text text-muted">Minimal 3 karakter, maksimal 100 karakter.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-bidang" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-edit-bidang" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_bidang_id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" data-admin-modal-title>Edit Bidang Ilmu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_bidang" class="form-label">Nama Bidang Ilmu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_bidang" name="nama" required minlength="3" maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================================== -->
<!-- MODAL TAMBAH/EDIT KLASTER BANTUAN -->
<!-- ======================================================================== -->
<div class="modal fade" id="modal-tambah-klaster" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/master-data-proposal/store-klaster-bantuan') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title" data-admin-modal-title>Tambah Klaster Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_klaster" class="form-label">Nama Klaster Bantuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_klaster" name="nama" required minlength="3" maxlength="100" placeholder="Contoh: Klaster Kesehatan">
                        <small class="form-text text-muted">Minimal 3 karakter, maksimal 100 karakter.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-klaster" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-edit-klaster" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_klaster_id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" data-admin-modal-title>Edit Klaster Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_klaster" class="form-label">Nama Klaster Bantuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_klaster" name="nama" required minlength="3" maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================================== -->
<!-- MODAL TAMBAH/EDIT TEMA PENELITIAN -->
<!-- ======================================================================== -->
<div class="modal fade" id="modal-tambah-tema" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('admin/master-data-proposal/store-tema-penelitian') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="POST">
                <div class="modal-header">
                    <h5 class="modal-title" data-admin-modal-title>Tambah Tema Penelitian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_tema" class="form-label">Nama Tema Penelitian <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_tema" name="nama" required minlength="3" maxlength="100" placeholder="Contoh: Kecerdasan Buatan">
                        <small class="form-text text-muted">Minimal 3 karakter, maksimal 100 karakter.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-tema" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-edit-tema" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_tema_id" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" data-admin-modal-title>Edit Tema Penelitian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama_tema" class="form-label">Nama Tema Penelitian <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_tema" name="nama" required minlength="3" maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>