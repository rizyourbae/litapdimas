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
                        <i class="bi bi-journal-richtext me-2"></i><?= esc($title ?? 'Publikasi Saya') ?>
                    </h3>
                    <a href="<?= site_url('dosen/publikasi/create') ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Publikasi
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (empty($tableRows)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-richtext fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada data Publikasi</p>
                        <small>Klik tombol "Tambah Publikasi" untuk memulai</small>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table id="dt-publikasi-dosen" class="table table-hover table-bordered align-middle w-100 publikasi-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px" class="col-nomor">#</th>
                                    <th>Judul Publikasi</th>
                                    <th style="width:130px" class="col-jenis">Jenis</th>
                                    <th style="width:90px" class="col-tahun">Tahun</th>
                                    <th style="width:110px" class="col-aksi">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tableRows as $index => $row): ?>
                                    <tr>
                                        <td class="col-nomor"><?= $index + 1 ?></td>
                                        <td>
                                            <a href="<?= esc($row['show_url']) ?>" class="text-decoration-none fw-semibold text-body-emphasis">
                                                <?= esc($row['judul']) ?>
                                            </a>
                                        </td>
                                        <td class="col-jenis">
                                            <span class="badge jenis-badge <?= esc($row['jenis_badge_class']) ?>">
                                                <?= esc($row['jenis_label']) ?>
                                            </span>
                                        </td>
                                        <td class="col-tahun"><?= esc($row['tahun']) ?></td>
                                        <td class="col-aksi">
                                            <div class="aksi-wrap">
                                                <a href="<?= esc($row['show_url']) ?>" class="btn btn-info aksi-btn" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= esc($row['edit_url']) ?>" class="btn btn-warning aksi-btn" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button class="btn btn-danger aksi-btn btn-delete" title="Hapus" data-href="<?= esc($row['delete_url']) ?>">
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

<?= $this->section('scripts') ?>
<script>
    (function() {
        'use strict';

        const table = document.getElementById('dt-publikasi-dosen');
        if (table && window.jQuery && jQuery.fn.DataTable) {
            jQuery(table).DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [0, 4]
                }]
            });
        }

        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete');
            if (!deleteBtn) return;
            e.preventDefault();
            const href = deleteBtn.getAttribute('data-href');
            SwalDelete(href, 'Publikasi ini', 'Data yang dihapus tidak dapat dikembalikan.');
        });
    })();
</script>
<?= $this->endSection() ?>