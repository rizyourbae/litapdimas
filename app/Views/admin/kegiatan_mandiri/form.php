<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-primary card-outline shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
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
                    <small class="text-muted">Masukkan detail utama kegiatan mandiri Dosen/Peneliti.</small>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pilih Dosen <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" data-select2 required>
                                <option value="">Pilih salah satu opsi</option>
                                <?php foreach ($dosenOptions as $option): ?>
                                    <option value="<?= esc($option['value']) ?>" <?= $option['selectedAttr'] ?>><?= esc($option['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

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
                            <div class="border rounded-top px-2 py-1 bg-light d-flex flex-wrap gap-1" id="resume-toolbar">
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
                    <small class="text-muted">Isi detail kolaborasi dan pendanaan.</small>
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
                                <input type="text" id="besaran_dana" name="besaran_dana" class="form-control" inputmode="numeric" value="<?= esc($formValues['besaran_dana']) ?>" required>
                            </div>
                            <small class="text-muted">Note: Cukup masukkan angka tanpa karakter.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3 border">
                <div class="card-header bg-light-subtle">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-link-45deg me-1"></i>Bukti Dukung Kegiatan</h6>
                    <small class="text-muted">Tautkan berkas pendukung seperti SK, foto, atau laporan.</small>
                </div>
                <div class="card-body">
                    <label class="form-label fw-semibold">Tautan Bukti Dukung <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="url" name="tautan_bukti_dukung" class="form-control" value="<?= esc($formValues['tautan_bukti_dukung']) ?>" placeholder="Contoh: https://drive.google.com/..." required>
                        <span class="input-group-text"><i class="bi bi-globe2"></i></span>
                    </div>
                    <small class="text-muted">Masukkan satu link (Google Drive, Cloud, dll) yang berisi semua berkas bukti dukung Anda.</small>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?= site_url('admin/kegiatan-mandiri') ?>" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i><?= esc($submitLabel) ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function() {
        'use strict';

        const besaranDanaInput = document.getElementById('besaran_dana');
        if (besaranDanaInput) {
            besaranDanaInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D+/g, '');
            });
        }

        const resume = document.getElementById('resume_kegiatan');
        const toolbar = document.getElementById('resume-toolbar');

        if (resume && toolbar) {
            toolbar.addEventListener('click', function(event) {
                const button = event.target.closest('button[data-wrap], button[data-prefix]');
                if (!button) {
                    return;
                }

                const start = resume.selectionStart;
                const end = resume.selectionEnd;
                const selected = resume.value.substring(start, end);
                const wrap = button.getAttribute('data-wrap') || '';
                const prefix = button.getAttribute('data-prefix') || '';
                const suffix = button.getAttribute('data-suffix') || '';

                let replacement = selected;
                if (wrap !== '') {
                    replacement = wrap + selected + wrap;
                }
                replacement = prefix + replacement + suffix;

                resume.setRangeText(replacement, start, end, 'end');
                resume.focus();
            });
        }
    })();
</script>
<?= $this->endSection() ?>