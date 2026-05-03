<?php
/** @var string $title */
/** @var string $formAction */
/** @var array<string,mixed> $formValues */
/** @var array<int,array<string,mixed>> $jenisOptions */
/** @var array<int,array<string,mixed>> $klasterOptions */
/** @var string $submitLabel */

$this->extend('layouts/main');

$this->section('content');
?>

<?= view('components/ui-hero', [
    'type' => 'dosen',
    'title' => $title ?? 'Form Kegiatan Mandiri',
    'subtitle' => 'Susun data kegiatan dengan urutan yang jelas, ringkas, dan seragam di semua halaman.',
    'badges' => [
        ['label' => 'Dosen Workspace', 'class' => 'text-bg-light border'],
        ['label' => 'Kegiatan Mandiri', 'class' => 'text-bg-primary shadow-sm']
    ],
    'actions' => [
        [
            'label' => 'Batal',
            'class' => 'btn btn-outline-secondary rounded-pill px-4',
            'icon' => 'bi bi-x-lg',
            'url' => site_url('dosen/kegiatan-mandiri')
        ]
    ]
]) ?>

<div class="row g-4">
    <div class="col-xl-9 mx-auto">
        <form action="<?= esc((string) $formAction) ?>" method="post" id="form-kegiatan-mandiri" novalidate data-submit-state-form>
            <?= csrf_field() ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= esc((string) session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-light border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="admin-panel-icon-sm bg-primary-soft text-primary">
                            <i class="bi bi-info-circle fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Informasi Kegiatan</h6>
                            <p class="text-muted small mb-0 mt-1">Masukkan detail utama kegiatan mandiri Anda</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Tahun Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" min="1900" max="2100" value="<?= esc((string) $formValues['tahun']) ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <select name="jenis_kegiatan" class="form-select shadow-none bg-light border-0 py-2 px-3 rounded-3" required>
                                <option value="">Pilih jenis kegiatan</option>
                                <?php foreach ($jenisOptions as $option): ?>
                                    <option value="<?= esc((string) $option['value']) ?>" <?= (string) $option['selectedAttr'] ?>><?= esc((string) $option['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Klaster/Skala <span class="text-danger">*</span></label>
                            <select name="klaster_skala_kegiatan" class="form-select shadow-none bg-light border-0 py-2 px-3 rounded-3" required>
                                <option value="">Pilih klaster/skala</option>
                                <?php foreach ($klasterOptions as $option): ?>
                                    <option value="<?= esc((string) $option['value']) ?>" <?= (string) $option['selectedAttr'] ?>><?= esc((string) $option['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Judul Kegiatan <span class="text-danger">*</span></label>
                            <textarea name="judul_kegiatan" rows="2" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" placeholder="Masukkan judul lengkap kegiatan" required><?= esc((string) $formValues['judul_kegiatan']) ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Anggota Terlibat</label>
                            <textarea name="anggota_terlibat" rows="2" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" placeholder="Nama-nama anggota, pisahkan dengan koma"><?= esc((string) $formValues['anggota_terlibat']) ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Resume Singkat Kegiatan</label>
                            <div class="border-0 rounded-top-4 px-3 py-2 bg-white d-flex flex-wrap gap-2 mb-0 border-bottom" data-resume-toolbar data-resume-target="#resume_kegiatan">
                                <button type="button" class="btn btn-sm btn-light border shadow-none" data-wrap="**" title="Bold"><strong>B</strong></button>
                                <button type="button" class="btn btn-sm btn-light border shadow-none" data-wrap="*" title="Italic"><em>I</em></button>
                                <div class="vr mx-1"></div>
                                <button type="button" class="btn btn-sm btn-light border shadow-none" data-prefix="- " title="List"><i class="bi bi-list-ul"></i></button>
                                <button type="button" class="btn btn-sm btn-light border shadow-none" data-prefix="### " title="Heading">H3</button>
                            </div>
                            <textarea name="resume_kegiatan" id="resume_kegiatan" rows="6" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-bottom-3 rounded-top-0" placeholder="Tuliskan ringkasan kegiatan..."><?= esc((string) $formValues['resume_kegiatan']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-light border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="admin-panel-icon-sm bg-primary-soft text-primary">
                            <i class="bi bi-briefcase fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Pelaksanaan & Pendanaan</h6>
                            <p class="text-muted small mb-0 mt-1">Isi detail kolaborasi dan sumber pendanaan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Unit Pelaksana Kegiatan</label>
                            <input type="text" name="unit_pelaksana_kegiatan" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" value="<?= esc((string) $formValues['unit_pelaksana_kegiatan']) ?>" placeholder="Fakultas / Lembaga">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Mitra Kolaborasi</label>
                            <input type="text" name="mitra_kolaborasi" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" value="<?= esc((string) $formValues['mitra_kolaborasi']) ?>" placeholder="Institusi Luar / Industri">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Sumber Dana</label>
                            <input type="text" name="sumber_dana" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" value="<?= esc((string) $formValues['sumber_dana']) ?>" placeholder="Mandiri / Sponsor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Besaran Dana <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">Rp</span>
                                <input type="text" id="besaran_dana" name="besaran_dana" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-end-3" data-numeric-only inputmode="numeric" value="<?= esc((string) $formValues['besaran_dana']) ?>" placeholder="0" required>
                            </div>
                            <small class="form-text opacity-75">Masukkan angka tanpa titik/koma.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-light border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="admin-panel-icon-sm bg-primary-soft text-primary">
                            <i class="bi bi-link-45deg fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Bukti Dukung</h6>
                            <p class="text-muted small mb-0 mt-1">Tautkan berkas pendukung kegiatan Anda</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <label class="form-label small fw-bold text-muted text-uppercase ls-1">Tautan Bukti Dukung <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-globe2"></i></span>
                        <input type="url" name="tautan_bukti_dukung" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-end-3" value="<?= esc((string) $formValues['tautan_bukti_dukung']) ?>" placeholder="https://drive.google.com/..." required>
                    </div>
                    <small class="form-text opacity-75">Satu link yang berisi semua bukti dukung kegiatan.</small>
                </div>
            </div>

            <div class="bg-white p-4 rounded-4 shadow-sm d-flex flex-wrap gap-2 justify-content-end mb-5">
                <a href="<?= site_url('dosen/kegiatan-mandiri') ?>" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" data-submit-trigger>
                    <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span><?= esc((string) $submitLabel) ?></span>
                    </span>
                    <span class="d-none align-items-center gap-2" data-submit-loading-content>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Menyimpan...</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php $this->endSection(); ?>