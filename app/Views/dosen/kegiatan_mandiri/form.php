<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card dosen-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Aktivitas Dosen</span>
                            <span class="badge text-bg-primary px-3 py-2">Form Kegiatan Mandiri</span>
                        </div>
                        <h2 class="h3 dosen-hero__title mb-2"><?= esc($title) ?></h2>
                        <p class="dosen-hero__subtitle mb-0">Susun data kegiatan dengan urutan yang jelas, ringkas, dan seragam di semua halaman.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm dosen-form-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0"><?= esc($title) ?></h3>
                <span class="badge text-bg-light border">Form Kegiatan Mandiri</span>
            </div>

            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= esc($formAction) ?>" method="post" id="form-kegiatan-mandiri" novalidate>
                    <?= csrf_field() ?>

                    <div class="card mb-3 border">
                        <div class="card-header bg-light-subtle">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-1"></i>Informasi Kegiatan</h6>
                            <small class="dosen-section-note">Masukkan detail utama kegiatan mandiri Anda.</small>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tahun Pelaksanaan <span class="text-danger">*</span></label>
                                    <input type="number" name="tahun" class="form-control" min="1900" max="2100" value="<?= esc($formValues['tahun']) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jenis Kegiatan <span class="text-danger">*</span></label>
                                    <select name="jenis_kegiatan" class="form-select" required>
                                        <option value="">Pilih salah satu opsi</option>
                                        <?php foreach ($jenisOptions as $option): ?>
                                            <option value="<?= esc($option['value']) ?>" <?= $option['selectedAttr'] ?>><?= esc($option['label']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Klaster/Skala Kegiatan <span class="text-danger">*</span></label>
                                    <select name="klaster_skala_kegiatan" class="form-select" required>
                                        <option value="">Pilih salah satu opsi</option>
                                        <?php foreach ($klasterOptions as $option): ?>
                                            <option value="<?= esc($option['value']) ?>" <?= $option['selectedAttr'] ?>><?= esc($option['label']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                                    <textarea name="judul_kegiatan" rows="2" class="form-control" required><?= esc($formValues['judul_kegiatan']) ?></textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Anggota Terlibat</label>
                                    <textarea name="anggota_terlibat" rows="2" class="form-control"><?= esc($formValues['anggota_terlibat']) ?></textarea>
                                    <small class="text-muted">Jika anggota lebih dari satu, pisahkan dengan koma.</small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Resume Singkat Kegiatan</label>
                                    <div class="border rounded-top px-2 py-1 bg-light d-flex flex-wrap gap-1" data-resume-toolbar data-resume-target="#resume_kegiatan">
                                        <button type="button" class="btn btn-sm btn-light border" data-wrap="**" title="Bold"><strong>B</strong></button>
                                        <button type="button" class="btn btn-sm btn-light border" data-wrap="*" title="Italic"><em>I</em></button>
                                        <button type="button" class="btn btn-sm btn-light border" data-prefix="- " title="List">• List</button>
                                        <button type="button" class="btn btn-sm btn-light border" data-prefix="### " title="Heading">H3</button>
                                        <button type="button" class="btn btn-sm btn-light border" data-prefix="[" data-suffix="](https://)" title="Link">Link</button>
                                    </div>
                                    <textarea name="resume_kegiatan" id="resume_kegiatan" rows="8" class="form-control rounded-top-0"><?= esc($formValues['resume_kegiatan']) ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 border">
                        <div class="card-header bg-light-subtle">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-briefcase me-1"></i>Detail Pelaksanaan &amp; Pendanaan</h6>
                            <small class="dosen-section-note">Isi detail kolaborasi dan sumber pendanaan.</small>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Unit Pelaksana Kegiatan</label>
                                    <input type="text" name="unit_pelaksana_kegiatan" class="form-control" value="<?= esc($formValues['unit_pelaksana_kegiatan']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mitra Kolaborasi</label>
                                    <input type="text" name="mitra_kolaborasi" class="form-control" value="<?= esc($formValues['mitra_kolaborasi']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Sumber Dana</label>
                                    <input type="text" name="sumber_dana" class="form-control" value="<?= esc($formValues['sumber_dana']) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Besaran Dana <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="besaran_dana" name="besaran_dana" class="form-control" data-numeric-only inputmode="numeric" value="<?= esc($formValues['besaran_dana']) ?>" required>
                                    </div>
                                    <small class="text-muted">Masukkan angka tanpa karakter tambahan.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 border">
                        <div class="card-header bg-light-subtle">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-link-45deg me-1"></i>Bukti Dukung Kegiatan</h6>
                            <small class="dosen-section-note">Tautkan berkas pendukung seperti SK, foto, atau laporan.</small>
                        </div>
                        <div class="card-body">
                            <label class="form-label fw-semibold">Tautan Bukti Dukung <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="url" name="tautan_bukti_dukung" class="form-control" value="<?= esc($formValues['tautan_bukti_dukung']) ?>" placeholder="Contoh: https://drive.google.com/..." required>
                                <span class="input-group-text"><i class="bi bi-globe2"></i></span>
                            </div>
                            <small class="text-muted">Masukkan satu link (Google Drive, Cloud, dll) yang berisi semua bukti dukung.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="<?= site_url('dosen/kegiatan-mandiri') ?>" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i><?= esc($submitLabel) ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>