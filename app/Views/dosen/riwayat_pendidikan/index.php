<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card dosen-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Data Akademik</span>
                            <span class="badge text-bg-primary px-3 py-2">Riwayat Pendidikan</span>
                        </div>
                        <h2 class="h3 dosen-hero__title mb-2"><?= esc($title ?? 'Riwayat Pendidikan Saya') ?></h2>
                        <p class="dosen-hero__subtitle mb-0">Kelola jenjang pendidikan, program studi, dan dokumen ijazah dengan tampilan yang lebih rapi.</p>
                    </div>

                    <div class="dosen-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= site_url('dosen/riwayat-pendidikan/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm dosen-table-card">
            <div class="card-header">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 align-items-md-center">
                    <div>
                        <h3 class="card-title mb-1">Daftar Riwayat Pendidikan</h3>
                    </div>
                    <span class="badge text-bg-light border">Total data: <?= esc(count($tableRows ?? [])) ?></span>
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
                    <div class="dosen-empty-state">
                        <i class="bi bi-mortarboard"></i>
                        <h4 class="h5 text-body mb-2">Belum ada data riwayat pendidikan</h4>
                        <p class="mb-0">Klik tombol Tambah Riwayat untuk mulai melengkapi data akademik Anda.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive dosen-table-wrap">
                        <table class="table table-striped table-hover table-bordered align-middle mb-0" id="dt-riwayat" data-dosen-datatable>
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
                                                <a href="<?= esc($row['dokumen_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i>Lihat Dokumen
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="dosen-action-group">
                                                <a href="<?= esc($row['edit_url']) ?>" class="btn btn-warning dosen-icon-btn" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button class="btn btn-danger dosen-icon-btn btn-delete" title="Hapus"
                                                    data-href="<?= esc($row['delete_url']) ?>"
                                                    data-delete-label="riwayat pendidikan ini"
                                                    data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
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