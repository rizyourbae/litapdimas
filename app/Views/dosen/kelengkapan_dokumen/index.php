<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card dosen-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Kelengkapan Dokumen</span>
                            <span class="badge text-bg-primary px-3 py-2">Data Wajib</span>
                        </div>
                        <h2 class="h3 dosen-hero__title mb-2"><?= esc($title) ?></h2>
                        <p class="dosen-hero__subtitle mb-0">Pantau status dokumen wajib Anda dalam tampilan yang lebih jelas dan konsisten.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm dosen-table-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0"><?= esc($title) ?></h3>
                <span class="badge text-bg-light border"><i class="bi bi-info-circle me-1"></i>Silahkan lengkapi dokumen wajib Anda</span>
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

                <div class="mb-3">
                    <span class="badge text-bg-light border">
                        <i class="bi bi-info-circle me-1"></i>Silahkan lengkapi dokumen wajib anda
                    </span>
                </div>

                <div class="table-responsive dosen-table-wrap">
                    <table class="table table-striped table-hover table-bordered align-middle mb-0" id="kelengkapan-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30%;">Jenis Dokumen</th>
                                <th style="width: 15%;">Status</th>
                                <th style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tableRows as $row): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($row['jenis_dokumen']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge text-bg-<?= esc($row['status_badge']) ?>">
                                            <?= esc($row['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['is_uploaded']): ?>
                                            <a href="<?= esc($row['dokumen_url']) ?>" target="_blank" class="btn btn-sm btn-success" title="Lihat Dokumen">
                                                <i class="bi bi-eye"></i>
                                                <span class="d-none d-sm-inline">Lihat</span>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= esc($row['edit_url']) ?>" class="btn btn-sm btn-warning" title="Upload/Edit Dokumen">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-sm-inline">Ubah</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>