<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
/** @var string $title */
/** @var string $formAction */
/** @var array<int,array<string,mixed>> $dosenOptions */
/** @var array<string,mixed> $formValues */
/** @var array<int,array<string,mixed>> $jenisOptions */
/** @var array<int,array<string,mixed>> $klasterOptions */
/** @var string $submitLabel */
?>

<div class="row g-4 admin-page">
    <div class="col-12 mb-2">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) ($hero['title'] ?? $title)),
            'subtitle' => esc((string) ($hero['subtitle'] ?? 'Lengkapi formulir di bawah ini untuk mengelola data kegiatan mandiri')),
            'badges' => [
                ['label' => 'Admin Workspace', 'class' => 'text-bg-light border'],
                ['label' => 'Form Kegiatan', 'class' => 'text-bg-primary shadow-sm']
            ],
            'actions' => [
                [
                    'label' => 'Batal',
                    'class' => 'btn btn-outline-secondary rounded-pill px-4',
                    'icon' => 'bi bi-x-lg',
                    'url' => site_url('admin/kegiatan-mandiri')
                ]
            ]
        ]) ?>
    </div>

    <div class="col-xl-8 mx-auto">
        <form action="<?= esc((string) $formAction) ?>" method="post" id="form-kegiatan-mandiri" novalidate data-submit-state-form>
            <?= csrf_field() ?>

            <?php if ((string) session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= esc((string) session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-light border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Informasi Utama Kegiatan</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Peneliti / Dosen <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select shadow-none bg-light border-0 py-2 px-3 rounded-3" data-select2 required>
                                <option value="">Pilih salah satu opsi</option>
                                <?php foreach ((array) $dosenOptions as $option): $option = (array) $option; ?>
                                    <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['selectedAttr'] ?? '')) ?>><?= esc((string) ($option['label'] ?? '')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <select name="jenis_kegiatan" class="form-select shadow-none bg-light border-0 py-2 px-3 rounded-3" required>
                                <option value="">Pilih salah satu opsi</option>
                                <?php foreach ((array) $jenisOptions as $option): $option = (array) $option; ?>
                                    <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['selectedAttr'] ?? '')) ?>><?= esc((string) ($option['label'] ?? '')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Klaster / Skala <span class="text-danger">*</span></label>
                            <select name="klaster_skala_kegiatan" class="form-select shadow-none bg-light border-0 py-2 px-3 rounded-3" required>
                                <option value="">Pilih salah satu opsi</option>
                                <?php foreach ((array) $klasterOptions as $option): $option = (array) $option; ?>
                                    <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['selectedAttr'] ?? '')) ?>><?= esc((string) ($option['label'] ?? '')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" min="1900" max="2100" value="<?= esc((string) ($formValues['tahun'] ?? '')) ?>" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Judul Kegiatan <span class="text-danger">*</span></label>
                            <textarea name="judul_kegiatan" rows="2" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" required placeholder="Tuliskan judul lengkap kegiatan..."><?= esc((string) ($formValues['judul_kegiatan'] ?? '')) ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Anggota Tim</label>
                            <textarea name="anggota_terlibat" rows="2" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" placeholder="Nama-nama anggota (pisahkan dengan koma)..."><?= esc((string) ($formValues['anggota_terlibat'] ?? '')) ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Resume Singkat</label>
                            <div class="border rounded-4 overflow-hidden shadow-sm">
                                <div class="bg-light px-3 py-2 d-flex flex-wrap gap-1 border-bottom" id="resume-toolbar" data-resume-toolbar data-resume-target="#resume_kegiatan">
                                    <button type="button" class="btn btn-sm btn-white border-0 shadow-none px-2" data-wrap="**" title="Bold"><i class="bi bi-type-bold"></i></button>
                                    <button type="button" class="btn btn-sm btn-white border-0 shadow-none px-2" data-wrap="*" title="Italic"><i class="bi bi-type-italic"></i></button>
                                    <button type="button" class="btn btn-sm btn-white border-0 shadow-none px-2" data-prefix="- " title="List"><i class="bi bi-list-ul"></i></button>
                                    <button type="button" class="btn btn-sm btn-white border-0 shadow-none px-2" data-prefix="### " title="Heading"><i class="bi bi-type-h3"></i></button>
                                </div>
                                <textarea name="resume_kegiatan" id="resume_kegiatan" rows="6" class="form-control border-0 shadow-none px-3 py-3 rounded-0" placeholder="Ringkasan eksekutif kegiatan..."><?= esc((string) ($formValues['resume_kegiatan'] ?? '')) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-light border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-currency-dollar text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Pelaksanaan & Pendanaan</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Unit Pelaksana</label>
                            <input type="text" name="unit_pelaksana_kegiatan" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" value="<?= esc((string) ($formValues['unit_pelaksana_kegiatan'] ?? '')) ?>" placeholder="Fakultas / Program Studi...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Mitra Kolaborasi</label>
                            <input type="text" name="mitra_kolaborasi" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" value="<?= esc((string) ($formValues['mitra_kolaborasi'] ?? '')) ?>" placeholder="Nama instansi mitra...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Sumber Dana</label>
                            <input type="text" name="sumber_dana" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" value="<?= esc((string) ($formValues['sumber_dana'] ?? '')) ?>" placeholder="Internal / Eksternal / Mandiri...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Besaran Dana <span class="text-danger">*</span></label>
                            <div class="input-group bg-light rounded-3 overflow-hidden border-0">
                                <span class="input-group-text bg-transparent border-0 pe-0 text-muted fw-bold">Rp</span>
                                <input type="text" id="besaran_dana" name="besaran_dana" class="form-control bg-transparent border-0 shadow-none py-2 px-3" inputmode="numeric" value="<?= esc((string) ($formValues['besaran_dana'] ?? '')) ?>" required data-numeric-only>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-light border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-link-45deg text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Bukti Dukung</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <label class="form-label small fw-bold text-muted text-uppercase ls-1">Tautan Bukti <span class="text-danger">*</span></label>
                    <div class="input-group bg-light rounded-3 overflow-hidden border-0 mb-2">
                        <input type="url" name="tautan_bukti_dukung" class="form-control bg-transparent border-0 shadow-none py-2 px-3" value="<?= esc((string) ($formValues['tautan_bukti_dukung'] ?? '')) ?>" placeholder="https://drive.google.com/..." required>
                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-globe2"></i></span>
                    </div>
                    <p class="small text-muted mb-0">Masukkan tautan menuju folder cloud (Drive/OneDrive) yang berisi berkas pendukung.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-4 shadow-sm d-flex flex-wrap gap-2 justify-content-end mb-5">
                <a href="<?= site_url('admin/kegiatan-mandiri') ?>" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
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


<?= $this->endSection() ?>