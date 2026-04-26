<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-primary card-outline shadow-sm mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0"><?= esc($title) ?></h3>
        <span class="badge text-bg-<?= esc($syncInfo['status_badge']) ?>"><?= esc($syncInfo['status_label']) ?></span>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">
            Sinkronisasi profil SINTA untuk dosen dilakukan berdasarkan ID SINTA. Data yang ditarik: nama, skor SINTA semua tahun, skor 3 tahun, dan tautan profil.
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

        <form method="post" action="<?= site_url('dosen/profil-sinta/sync') ?>" class="row g-3 align-items-end">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label class="form-label">ID SINTA</label>
                <input
                    type="text"
                    name="id_sinta"
                    class="form-control"
                    value="<?= esc($formValues['id_sinta']) ?>"
                    placeholder="Contoh: 6824588"
                    required>
            </div>
            <div class="col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-repeat me-1"></i>
                    Sinkronkan Sekarang
                </button>
                <span class="text-muted align-self-center small">Sinkron terakhir: <?= esc($syncInfo['last_synced_at']) ?></span>
            </div>
        </form>
    </div>
</div>

<div class="card card-outline card-secondary shadow-sm">
    <div class="card-header">
        <h3 class="card-title mb-0">Data Profil SINTA</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
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

<?= $this->endSection() ?>