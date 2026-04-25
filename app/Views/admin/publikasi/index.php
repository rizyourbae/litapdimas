<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .publikasi-table th,
    .publikasi-table td {
        vertical-align: middle;
    }

    .publikasi-table .col-jenis,
    .publikasi-table .col-aksi,
    .publikasi-table .col-tahun,
    .publikasi-table .col-nomor {
        text-align: center;
    }

    .publikasi-table .jenis-badge {
        min-width: 92px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.45rem 0.7rem;
        border-radius: 999px;
    }

    .publikasi-table .aksi-wrap {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .publikasi-table .aksi-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 0.45rem;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-journal-richtext me-2"></i><?= esc($title ?? 'Data Publikasi') ?>
                    </h3>
                    <a href="<?= site_url('admin/publikasi/create') ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Publikasi
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($tableRows)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-richtext fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada data Publikasi</p>
                        <small>Klik tombol "Tambah Publikasi" untuk memulai</small>
                    </div>
                <?php else: ?>
                    <div class="dt-skeleton-wrap">
                        <!-- Skeleton overlay -->
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

                        <!-- Real DataTable -->
                        <div class="dt-real-wrap" id="rw-publikasi">
                            <table id="dt-publikasi" class="table table-hover table-bordered align-middle w-100 publikasi-table">
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
                                                <span class="badge jenis-badge <?= esc($row['jenis_badge_class']) ?>">
                                                    <i class="bi bi-bookmark me-1"></i><?= esc($row['jenis_label']) ?>
                                                </span>
                                            </td>
                                            <td class="col-tahun"><?= esc($row['tahun']) ?></td>
                                            <td class="col-aksi">
                                                <div class="aksi-wrap">
                                                    <a href="<?= esc($row['show_url']) ?>" class="btn btn-info btn-sm aksi-btn" title="Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?= esc($row['edit_url']) ?>" class="btn btn-warning btn-sm aksi-btn" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm btn-delete aksi-btn" data-href="<?= esc($row['delete_url']) ?>" title="Hapus">
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

<?= $this->section('scripts') ?>
<script>
    (function() {
        'use strict';

        const DT_OPTS = {
            columnDefs: [{
                orderable: false,
                targets: [0, 5]
            }]
        };

        // Init DataTable
        DtManager.initLazy('dt-publikasi', DT_OPTS, 'sk-publikasi', 'rw-publikasi');

        // Delete button handler
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete');
            if (!btn) return;

            e.preventDefault();
            const href = btn.getAttribute('data-href');

            SwalDelete(
                href,
                'Publikasi ini',
                'Data yang dihapus tidak dapat dikembalikan.'
            );
        });
    })();
</script>
<?= $this->endSection() ?>