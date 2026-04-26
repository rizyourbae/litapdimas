<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Operasional Admin</span>
                            <span class="badge text-bg-primary px-3 py-2">Kegiatan Mandiri</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($title ?? 'Data Kegiatan Mandiri') ?></h2>
                        <p class="admin-hero__subtitle mb-0">Pantau kegiatan mandiri dosen</p>
                    </div>
                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= site_url('admin/kegiatan-mandiri/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Kegiatan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline admin-table-card">
            <div class="card-header border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-list-check me-2"></i>Direktori Kegiatan Mandiri
                    </h3>
                    <span class="badge text-bg-light border">Data aktivitas mandiri dosen</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($tableRows)): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-journal-check"></i>
                        <p class="mb-1 fw-semibold text-body-emphasis">Belum ada data kegiatan mandiri</p>
                        <small class="d-block mb-3">Tambahkan kegiatan pertama agar riwayat aktivitas dosen bisa dipantau dari panel admin.</small>
                        <a href="<?= site_url('admin/kegiatan-mandiri/create') ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Kegiatan
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="dt-kegiatan-mandiri" data-admin-datatable data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,6]}]}'>
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px" class="text-center">#</th>
                                    <th>Dosen</th>
                                    <th>Judul Kegiatan</th>
                                    <th>Jenis</th>
                                    <th>Klaster/Skala</th>
                                    <th style="width: 90px" class="text-center">Tahun</th>
                                    <th style="width: 110px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tableRows as $index => $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $index + 1 ?></td>
                                        <td><?= esc($row['display_name']) ?></td>
                                        <td>
                                            <a href="<?= esc($row['show_url']) ?>" class="text-decoration-none fw-semibold text-body-emphasis">
                                                <?= esc($row['judul_kegiatan']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge <?= esc($row['jenis_badge_class']) ?>">
                                                <?= esc($row['jenis_kegiatan']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= esc($row['klaster_badge_class']) ?>">
                                                <?= esc($row['klaster_label']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center"><?= esc($row['tahun']) ?></td>
                                        <td class="text-center">
                                            <div class="admin-action-inline">
                                                <a href="<?= esc($row['show_url']) ?>" class="btn btn-info btn-sm admin-icon-btn" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= esc($row['edit_url']) ?>" class="btn btn-warning btn-sm admin-icon-btn" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button class="btn btn-danger btn-sm btn-delete admin-icon-btn" title="Hapus" data-href="<?= esc($row['delete_url']) ?>" data-delete-label="Kegiatan mandiri ini" data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
                                                    <i class="bi bi-trash"></i>
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

<?= $this->endSection() ?>