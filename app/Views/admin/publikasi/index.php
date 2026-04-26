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
                            <span class="badge text-bg-primary px-3 py-2">Publikasi</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($title ?? 'Data Publikasi') ?></h2>
                        <p class="admin-hero__subtitle mb-0">Kelola publikasi dosen dalam satu ruang kerja yang lebih rapi, lebih mudah dipindai, dan setara secara visual dengan halaman admin lainnya.</p>
                    </div>
                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= site_url('admin/publikasi/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Publikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card admin-panel-card h-100">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="small text-uppercase text-muted mb-1">Informasi</div>
                    <h3 class="h5 mb-2">Review, koreksi, lalu finalkan publikasi</h3>
                    <p class="text-muted mb-0">Masuk ke detail untuk verifikasi metadata, lakukan edit cepat saat ada revisi, dan gunakan aksi hapus hanya untuk data yang memang tidak valid.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline admin-table-card">
            <div class="card-header border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-journal-richtext me-2"></i>Direktori Publikasi
                    </h3>
                    <span class="badge text-bg-light border">Data publikasi akademik</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($tableRows)): ?>
                    <div class="admin-empty-state">
                        <i class="bi bi-journal-richtext"></i>
                        <p class="mb-1 fw-semibold text-body-emphasis">Belum ada data publikasi</p>
                        <small class="d-block mb-3">Mulai dari entri pertama agar publikasi dosen bisa dikelola dari workspace admin ini.</small>
                        <a href="<?= site_url('admin/publikasi/create') ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Publikasi
                        </a>
                    </div>
                <?php else: ?>
                    <div class="dt-skeleton-wrap">
                        <div class="dt-skeleton-overlay" id="sk-publikasi">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="width:110px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ([70, 55, 65, 50, 80, 45] as $w): ?>
                                        <tr>
                                            <td><span class="skeleton-line mx-auto" style="width:24px"></span></td>
                                            <td><span class="skeleton-line" style="width:<?= $w ?>%"></span></td>
                                            <td><span class="skeleton-line" style="width:60%"></span></td>
                                            <td><span class="skeleton-line" style="width:55%;border-radius:20px;height:20px"></span></td>
                                            <td class="text-center"><span class="skeleton-line mx-auto" style="width:40px;border-radius:20px;height:20px"></span></td>
                                            <td class="text-center"><span class="skeleton-btn me-1"></span><span class="skeleton-btn"></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="dt-real-wrap" id="rw-publikasi">
                            <table id="dt-publikasi" class="table table-hover table-bordered align-middle w-100 publikasi-table" data-admin-datatable data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[0,5]}]}' data-skeleton-id="sk-publikasi" data-real-wrap-id="rw-publikasi">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px" class="col-nomor">#</th>
                                        <th>Dosen</th>
                                        <th>Judul Publikasi</th>
                                        <th style="width:140px" class="col-jenis">Jenis</th>
                                        <th style="width:90px" class="col-tahun">Tahun</th>
                                        <th style="width:132px" class="col-aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tableRows as $no => $row): ?>
                                        <tr>
                                            <td class="col-nomor"><?= $no + 1 ?></td>
                                            <td>
                                                <strong><?= esc($row['dosen_name']) ?></strong>
                                            </td>
                                            <td>
                                                <a href="<?= esc($row['show_url']) ?>" class="text-decoration-none text-body-emphasis fw-semibold">
                                                    <?= esc($row['judul']) ?>
                                                </a>
                                            </td>
                                            <td class="col-jenis">
                                                <span class="badge admin-inline-badge <?= esc($row['jenis_badge_class']) ?>">
                                                    <i class="bi bi-bookmark me-1"></i><?= esc($row['jenis_label']) ?>
                                                </span>
                                            </td>
                                            <td class="col-tahun"><?= esc($row['tahun']) ?></td>
                                            <td class="col-aksi">
                                                <div class="admin-action-inline">
                                                    <a href="<?= esc($row['show_url']) ?>" class="btn btn-info btn-sm admin-icon-btn" title="Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?= esc($row['edit_url']) ?>" class="btn btn-warning btn-sm admin-icon-btn" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm btn-delete admin-icon-btn" data-href="<?= esc($row['delete_url']) ?>" data-delete-label="Publikasi ini" data-delete-desc="Data yang dihapus tidak dapat dikembalikan." title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
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

<?= $this->endSection() ?>