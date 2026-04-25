<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-list-check me-2"></i><?= esc($title ?? 'Data Kegiatan Mandiri') ?>
                    </h3>
                    <a href="<?= site_url('admin/kegiatan-mandiri/create') ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Kegiatan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($tableRows)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-check fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada data kegiatan mandiri.</p>
                        <small>Klik tombol "Tambah Kegiatan" untuk memulai.</small>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="dt-kegiatan-mandiri">
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
                                            <a href="<?= esc($row['show_url']) ?>" class="btn btn-info btn-sm" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= esc($row['edit_url']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button class="btn btn-danger btn-sm btn-delete" title="Hapus" data-href="<?= esc($row['delete_url']) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
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

        const table = document.getElementById('dt-kegiatan-mandiri');
        if (table && window.jQuery && jQuery.fn.DataTable) {
            jQuery(table).DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [0, 6]
                }]
            });
        }

        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete');
            if (!deleteBtn) {
                return;
            }

            e.preventDefault();
            const href = deleteBtn.getAttribute('data-href');
            SwalDelete(
                href,
                'Kegiatan mandiri ini',
                'Data yang dihapus tidak dapat dikembalikan.'
            );
        });
    })();
</script>
<?= $this->endSection() ?>