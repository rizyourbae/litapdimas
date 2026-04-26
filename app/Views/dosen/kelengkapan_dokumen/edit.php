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
                            <span class="badge text-bg-primary px-3 py-2">Unggah Dokumen</span>
                        </div>
                        <h2 class="h3 dosen-hero__title mb-2"><?= esc($title) ?></h2>
                        <p class="dosen-hero__subtitle mb-0">Perbarui dokumen wajib dengan alur unggah yang lebih sederhana dan konsisten.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm dosen-form-card">
            <div class="card-header">
                <h3 class="card-title mb-0"><?= esc($title) ?></h3>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= esc($formState['action_url']) ?>" method="post" id="form-dokumen" enctype="multipart/form-data" novalidate>
                    <?= csrf_field() ?>

                    <div class="card mb-3 border">
                        <div class="card-header bg-light-subtle">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-1"></i>Informasi Dokumen</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Jenis Dokumen</label>
                                    <div class="p-3 rounded bg-light border">
                                        <strong><?= esc($formValues['jenis_dokumen']) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 border">
                        <div class="card-header bg-light-subtle">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-pdf me-1"></i>Unggah Dokumen</h6>
                            <small class="dosen-section-note">Pilih file dokumen (PDF, JPG, PNG, DOC, DOCX). Maksimal 10MB.</small>
                        </div>
                        <div class="card-body">
                            <?php if ($formValues['has_dokumen']): ?>
                                <div class="alert alert-info mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Dokumen Saat Ini:</strong><br>
                                    <a href="<?= esc(base_url($formValues['dokumen_existing'])) ?>" target="_blank">
                                        <?= esc(basename($formValues['dokumen_existing'])) ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih File <span class="text-danger">*</span></label>
                                <input type="file" name="file_dokumen" class="form-control" id="file-upload"
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required
                                    data-file-preview-input
                                    data-file-preview-wrap="#file-preview"
                                    data-file-preview-name="#preview-filename"
                                    data-file-preview-size="#preview-filesize">
                                <small class="text-muted d-block mt-2">
                                    Format yang didukung: PDF, JPG, PNG, DOC, DOCX
                                </small>
                            </div>

                            <div id="file-preview" class="d-none">
                                <div class="alert alert-info mb-0">
                                    <strong>File Terpilih:</strong><br>
                                    <span id="preview-filename"></span>
                                    (<span id="preview-filesize"></span>)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="<?= site_url('dosen/kelengkapan-dokumen') ?>" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-upload me-1"></i><?= esc($formState['submit_label']) ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>