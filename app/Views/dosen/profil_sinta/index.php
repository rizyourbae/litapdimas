<?php
/** @var string $title */
/** @var array<string,mixed> $syncInfo */
/** @var array<string,mixed> $formValues */
/** @var object|null $profile */

$this->extend('layouts/main');

$this->section('content');
?>

<div class="row g-4 admin-page">
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'dosen',
            'title' => $title ?? 'Sinkronisasi Profil SINTA',
            'subtitle' => 'Kelola ID SINTA Anda untuk memastikan sinkronisasi data publikasi dan skor akademik berjalan lancar.',
            'badges' => [
                ['label' => 'Integrasi Sistem', 'class' => 'text-bg-light border shadow-sm'],
                ['label' => 'SINTA Kemenristek', 'class' => 'text-bg-info text-white shadow-sm']
            ]
        ]) ?>
    </div>

    <div class="col-lg-5 animate-fade-up delay-1">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
            <div class="card-header bg-primary py-3">
                <h5 class="card-title mb-0 text-white"><i class="bi bi-arrow-repeat me-2"></i>Sinkronisasi Profil</h5>
            </div>
            <div class="card-body p-4">
                <div class="bg-light p-3 rounded-3 mb-4">
                    <p class="text-muted small mb-0">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        Data yang ditarik meliputi nama lengkap, skor SINTA (semua tahun & 3 tahun), serta tautan profil publik.
                    </p>
                </div>

                <form method="post" action="<?= site_url('dosen/profil-sinta/sync') ?>" class="row g-3"
                    data-submit-state-form data-submit-loading-text="Sedang sinkronisasi ke SINTA...">
                    <?= csrf_field() ?>
                    <div class="col-12">
                        <label class="form-label fw-bold text-dark">ID SINTA Peneliti</label>
                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden border">
                            <span class="input-group-text bg-white border-0"><i class="bi bi-person-badge text-muted"></i></span>
                            <input type="text" name="id_sinta" class="form-control border-0" value="<?= esc($formValues['id_sinta']) ?>" placeholder="Contoh: 6824588" required>
                        </div>
                        <div class="form-text mt-2 small">Masukkan 7 digit ID SINTA Anda.</div>
                    </div>
                    <div class="col-12 mt-4 d-flex flex-column gap-3">
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow" data-submit-trigger>
                            <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                                <i class="bi bi-arrow-repeat"></i>
                                <span>Sinkronkan Sekarang</span>
                            </span>
                            <span class="d-none align-items-center gap-2" data-submit-loading-content>
                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                <span>Sedang Memproses...</span>
                            </span>
                        </button>
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light border">
                            <span class="text-muted small">Update terakhir:</span>
                            <span class="fw-bold text-dark small"><?= format_indo($syncInfo['last_synced_at'] ?? '') ?></span>
                        </div>
                        <div class="d-none mt-2 alert alert-info py-2" data-submit-feedback aria-live="polite">
                            <i class="bi bi-info-circle-fill me-2"></i>Koneksi ke SINTA sedang dibangun...
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7 animate-fade-up delay-2">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 text-dark fw-bold">Data Hasil Sinkronisasi</h5>
                    <span class="badge bg-success-soft text-success rounded-pill px-3 py-2"><?= esc($syncInfo['status_label']) ?></span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            <tr class="border-bottom">
                                <th style="width: 240px;" class="bg-light px-4 py-3 text-muted fw-semibold">Nama di SINTA</th>
                                <td class="px-4 py-3 fw-bold text-dark"><?= esc((string) ($profile->nama_sinta ?? '-')) ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="bg-light px-4 py-3 text-muted fw-semibold">Identitas (ID SINTA)</th>
                                <td class="px-4 py-3 font-monospace text-primary fw-bold"><?= esc((string) ($profile->id_sinta ?? '-')) ?></td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="bg-light px-4 py-3 text-muted fw-semibold">SINTA Score (All Years)</th>
                                <td class="px-4 py-3">
                                    <span class="badge bg-primary-soft text-primary rounded-pill px-3 py-2 fw-bold fs-6">
                                        <?= esc((string) ($profile->sinta_score_all_years ?? '-')) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <th class="bg-light px-4 py-3 text-muted fw-semibold">SINTA Score (3 Years)</th>
                                <td class="px-4 py-3">
                                    <span class="badge bg-info-soft text-info rounded-pill px-3 py-2 fw-bold fs-6">
                                        <?= esc((string) ($profile->sinta_score_3_years ?? '-')) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light px-4 py-3 text-muted fw-semibold">Tautan Profil SINTA</th>
                                <td class="px-4 py-3">
                                    <?php if (!empty($profile->sinta_profile_url)): ?>
                                        <a href="<?= esc((string) $profile->sinta_profile_url) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2">
                                            <i class="bi bi-box-arrow-up-right me-2"></i>Buka Profil Eksternal
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small italic">Tautan belum tersedia. Silakan sinkronkan.</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light border-0 py-3 text-center">
                <small class="text-muted italic">Data di atas disinkronkan langsung dari server SINTA Kemenristek/BRIN.</small>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>