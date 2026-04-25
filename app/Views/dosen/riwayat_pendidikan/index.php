<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-mortarboard me-2"></i><?= esc($title ?? 'Riwayat Pendidikan Saya') ?>
                    </h3>
                    <a href="<?= site_url('dosen/riwayat-pendidikan/create') ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Riwayat
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
                        <i class="bi bi-mortarboard fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada data riwayat pendidikan</p>
                        <small>Klik tombol "Tambah Riwayat" untuk memulai</small>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="dt-riwayat">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px" class="text-center">#</th>
                                    <th>Jenjang</th>
                                    <th>Program Studi</th>
                                    <th>Institusi</th>
                                    <th style="width: 90px" class="text-center">Tahun Masuk</th>
                                    <th style="width: 90px" class="text-center">Tahun Lulus</th>
                                    <th style="width: 70px" class="text-center">IPK</th>
                                    <th style="width: 140px" class="text-center">Dokumen</th>
                                    <th style="width: 110px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tableRows as $index => $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $index + 1 ?></td>
                                        <td><?= esc($row['jenjang']) ?></td>
                                        <td><?= esc($row['program_studi']) ?></td>
                                        <td><?= esc($row['institusi']) ?></td>
                                        <td class="text-center"><?= esc($row['tahun_masuk']) ?></td>
                                        <td class="text-center"><?= esc($row['tahun_lulus']) ?></td>
                                        <td class="text-center"><?= esc($row['ipk']) ?></td>
                                        <td class="text-center">
                                            <?php if ($row['dokumen_url']): ?>
                                                <a href="<?= esc($row['dokumen_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary" title="Lihat Dokumen">
                                                    <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div style="display:inline-flex;gap:.4rem;align-items:center;">
                                                <a href="<?= esc($row['edit_url']) ?>" class="btn btn-warning btn-sm" title="Edit" style="width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button class="btn btn-danger btn-sm btn-delete" title="Hapus" data-href="<?= esc($row['delete_url']) ?>" style="width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;">
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

        const table = document.getElementById('dt-riwayat');
        if (table && window.jQuery && jQuery.fn.DataTable) {
            jQuery(table).DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [0, 8]
                }]
            });
        }

        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete');
            if (!deleteBtn) return;
            e.preventDefault();
            const href = deleteBtn.getAttribute('data-href');
            SwalDelete(href, 'Riwayat pendidikan ini', 'Data yang dihapus tidak dapat dikembalikan.');
        });
    })();
</script>
<?= $this->endSection() ?>