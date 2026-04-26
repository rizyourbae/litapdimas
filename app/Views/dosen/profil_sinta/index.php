<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card dosen-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Sinkronisasi Data</span>
                            <span class="badge text-bg-<?= esc($syncInfo['status_badge']) ?> px-3 py-2"><?= esc($syncInfo['status_label']) ?></span>
                        </div>
                        <h2 class="h3 dosen-hero__title mb-2"><?= esc($title) ?></h2>
                        <p class="dosen-hero__subtitle mb-0">Sinkronisasi profil SINTA dilakukan berdasarkan ID SINTA untuk menjaga data tetap konsisten.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-primary card-outline shadow-sm dosen-form-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0">Sinkron Profil</h3>
                <span class="badge text-bg-light border">Update data SINTA</span>
            </div>
            <div class="card-body">
                <p class="dosen-section-note mb-3">
                    Data yang ditarik: nama, skor SINTA semua tahun, skor 3 tahun, dan tautan profil.
                </p>

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

                <form method="post" action="<?= site_url('dosen/profil-sinta/sync') ?>" class="row g-3 align-items-end"
                    data-submit-state-form data-submit-loading-text="Sedang sinkronisasi ke SINTA...">
                    <?= csrf_field() ?>
                    <div class="col-12">
                        <label class="form-label fw-semibold">ID SINTA</label>
                        <input type="text" name="id_sinta" class="form-control" value="<?= esc($formValues['id_sinta']) ?>" placeholder="Contoh: 6824588" required>
                    </div>
                    <div class="col-12 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary" data-submit-trigger>
                            <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                                <i class="bi bi-arrow-repeat"></i>
                                <span>Sinkronkan Sekarang</span>
                            </span>
                            <span class="d-none align-items-center gap-2" data-submit-loading-content>
                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                <span>Sedang Memproses...</span>
                            </span>
                        </button>
                        <span class="d-none dosen-processing-note align-self-center small" data-submit-feedback aria-live="polite">
                            Sinkronisasi sedang berjalan. Mohon tunggu.
                        </span>
                        <span class="text-muted align-self-center small">Sinkron terakhir: <?= esc($syncInfo['last_synced_at']) ?></span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-outline card-secondary shadow-sm dosen-show-card h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">Data Profil SINTA</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge text-bg-light border">
                        <i class="bi bi-info-circle me-1"></i>Berikut data profil SINTA hasil sinkronisasi
                    </span>
                </div>

                <div class="table-responsive dosen-table-wrap">
                    <table class="table table-striped table-hover table-bordered align-middle mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 280px;" class="bg-light">Nama</th>
                                <td><?= esc($profile->nama_sinta ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">ID SINTA</th>
                                <td><?= esc($profile->id_sinta ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">SINTA Score (All Years)</th>
                                <td><?= esc($profile->sinta_score_all_years ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">SINTA Score (3 Years)</th>
                                <td><?= esc($profile->sinta_score_3_years ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Profil SINTA</th>
                                <td>
                                    <?php if (!empty($profile->sinta_profile_url)): ?>
                                        <a href="<?= esc($profile->sinta_profile_url) ?>" target="_blank" rel="noopener noreferrer">
                                            Lihat Profil
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Status Sinkronisasi</th>
                                <td>
                                    <span class="badge text-bg-<?= esc($syncInfo['status_badge']) ?>">
                                        <?= esc($syncInfo['status_label']) ?>
                                    </span>
                                    <?php if (!empty($profile->sync_error_message)): ?>
                                        <div class="text-danger small mt-1"><?= esc($profile->sync_error_message) ?></div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>