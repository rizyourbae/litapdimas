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

<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Admin Workspace</span>
                            <span class="badge text-bg-primary px-3 py-2">Form Kegiatan</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc((string) $title) ?></h2>
                    </div>
                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= site_url('admin/kegiatan-mandiri') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12">
        <div class="card card-primary card-outline admin-form-card shadow-sm">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title mb-0"><i class="bi bi-list-check me-2"></i><?= esc($title) ?></h3>
            </div>
            <form action="<?= esc((string) $formAction) ?>" method="post" id="form-kegiatan-mandiri" novalidate data-submit-state-form>
                <?= csrf_field() ?>

                <div class="card-body">
                    <?php if ((string) session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <?= esc((string) session()->getFlashdata('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <section class="admin-section-block">
                        <div class="mb-3">
                            <h3 class="h5 mb-1">Informasi Kegiatan</h3>
                            <p class="text-muted small mb-0">Masukkan detail utama kegiatan mandiri dosen atau peneliti.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pilih Dosen <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select" data-select2 required>
                                    <option value="">Pilih salah satu opsi</option>
                                    <?php foreach ((array) $dosenOptions as $option): $option = (array) $option; ?>
                                        <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['selectedAttr'] ?? '')) ?>><?= esc((string) ($option['label'] ?? '')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tahun Pelaksanaan <span class="text-danger">*</span></label>
                                <input type="number" name="tahun" class="form-control" min="1900" max="2100" value="<?= esc((string) ($formValues['tahun'] ?? '')) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Kegiatan <span class="text-danger">*</span></label>
                                <select name="jenis_kegiatan" class="form-select" required>
                                    <option value="">Pilih salah satu opsi</option>
                                    <?php foreach ((array) $jenisOptions as $option): $option = (array) $option; ?>
                                        <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['selectedAttr'] ?? '')) ?>><?= esc((string) ($option['label'] ?? '')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Klaster/Skala Kegiatan <span class="text-danger">*</span></label>
                                <select name="klaster_skala_kegiatan" class="form-select" required>
                                    <option value="">Pilih salah satu opsi</option>
                                    <?php foreach ((array) $klasterOptions as $option): $option = (array) $option; ?>
                                        <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['selectedAttr'] ?? '')) ?>><?= esc((string) ($option['label'] ?? '')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                                <textarea name="judul_kegiatan" rows="2" class="form-control" required><?= esc((string) ($formValues['judul_kegiatan'] ?? '')) ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Anggota Terlibat</label>
                                <textarea name="anggota_terlibat" rows="2" class="form-control"><?= esc((string) ($formValues['anggota_terlibat'] ?? '')) ?></textarea>
                                <small class="text-muted">Jika anggota lebih dari satu, pisahkan dengan koma.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Resume Singkat Kegiatan</label>
                                <div class="border rounded-top px-2 py-1 bg-light d-flex flex-wrap gap-1" id="resume-toolbar" data-resume-toolbar data-resume-target="#resume_kegiatan">
                                    <button type="button" class="btn btn-sm btn-light border" data-wrap="**" title="Bold"><strong>B</strong></button>
                                    <button type="button" class="btn btn-sm btn-light border" data-wrap="*" title="Italic"><em>I</em></button>
                                    <button type="button" class="btn btn-sm btn-light border" data-prefix="- " title="List">• List</button>
                                    <button type="button" class="btn btn-sm btn-light border" data-prefix="### " title="Heading">H3</button>
                                    <button type="button" class="btn btn-sm btn-light border" data-prefix="[" data-suffix="](https://)" title="Link">Link</button>
                                </div>
                                <textarea name="resume_kegiatan" id="resume_kegiatan" rows="8" class="form-control rounded-top-0"><?= esc((string) ($formValues['resume_kegiatan'] ?? '')) ?></textarea>
                            </div>
                        </div>
                    </section>

                    <section class="admin-section-block">
                        <div class="mb-3">
                            <h3 class="h5 mb-1">Detail Pelaksanaan &amp; Pendanaan</h3>
                            <p class="text-muted small mb-0">Isi detail kolaborasi, unit pelaksana, dan nominal dukungan dana.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Unit Pelaksana Kegiatan</label>
                                <input type="text" name="unit_pelaksana_kegiatan" class="form-control" value="<?= esc((string) ($formValues['unit_pelaksana_kegiatan'] ?? '')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mitra Kolaborasi</label>
                                <input type="text" name="mitra_kolaborasi" class="form-control" value="<?= esc((string) ($formValues['mitra_kolaborasi'] ?? '')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Sumber Dana</label>
                                <input type="text" name="sumber_dana" class="form-control" value="<?= esc((string) ($formValues['sumber_dana'] ?? '')) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Besaran Dana <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="besaran_dana" name="besaran_dana" class="form-control" inputmode="numeric" value="<?= esc((string) ($formValues['besaran_dana'] ?? '')) ?>" required data-numeric-only>
                                </div>
                                <small class="text-muted">Note: Cukup masukkan angka tanpa karakter.</small>
                            </div>
                        </div>
                    </section>

                    <section class="admin-section-block mb-0">
                        <div class="mb-3">
                            <h3 class="h5 mb-1">Bukti Dukung Kegiatan</h3>
                            <p class="text-muted small mb-0">Tautkan berkas pendukung seperti SK, foto, atau laporan akhir.</p>
                        </div>
                        <label class="form-label fw-semibold">Tautan Bukti Dukung <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="url" name="tautan_bukti_dukung" class="form-control" value="<?= esc((string) ($formValues['tautan_bukti_dukung'] ?? '')) ?>" placeholder="Contoh: https://drive.google.com/..." required>
                            <span class="input-group-text"><i class="bi bi-globe2"></i></span>
                        </div>
                        <small class="text-muted">Masukkan satu link (Google Drive, Cloud, dll) yang berisi semua berkas bukti dukung Anda.</small>
                    </section>
                </div>

                <div class="card-footer admin-form-footer d-flex flex-wrap gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span><?= esc((string) $submitLabel) ?></span></span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span>
                    </button>
                    <a href="<?= site_url('admin/kegiatan-mandiri') ?>" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>