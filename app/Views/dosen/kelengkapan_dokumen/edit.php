<?php
/** @var string $title */
/** @var array<string,mixed> $formValues */
/** @var array<string,mixed> $formState */

$this->extend('layouts/main');

$this->section('content');
?>

<?= view('components/dosen-hero', [
    'title' => $title ?? 'Unggah Dokumen',
    'subtitle' => 'Perbarui dokumen wajib dengan alur unggah yang lebih sederhana dan konsisten.',
    'icon' => 'bi bi-file-earmark-arrow-up',
]) ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm dosen-form-card">
            <div class="card-header border-0 bg-transparent pt-4 d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0"><?= esc((string) $title) ?></h3>
                <span class="badge text-bg-light border px-3 py-2">Kelengkapan Dokumen</span>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= esc((string) session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= esc((string) $formState['action_url']) ?>" method="post" id="form-dokumen" enctype="multipart/form-data" novalidate>
                    <?= csrf_field() ?>

                    <div class="card mb-4 border-0 dosen-soft-surface">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h6 class="mb-1 fw-bold text-primary"><i class="bi bi-info-circle me-2"></i>Informasi Dokumen</h6>
                            <p class="text-muted small mb-0">Detail jenis dokumen yang akan diunggah.</p>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Jenis Dokumen</label>
                                    <div class="p-3 rounded bg-white shadow-sm border-start border-primary border-4">
                                        <div class="fw-bold text-dark"><?= esc((string) $formValues['jenis_dokumen']) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 border-0 dosen-soft-surface">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h6 class="mb-1 fw-bold text-primary"><i class="bi bi-file-earmark-pdf me-2"></i>Unggah Dokumen Baru</h6>
                            <p class="text-muted small mb-0">Pilih file dokumen (PDF, JPG, PNG, DOC, DOCX). Maksimal 10MB.</p>
                        </div>
                        <div class="card-body p-4 pt-0">
                            <?php if ($formValues['has_dokumen']): ?>
                                <div class="alert alert-info border-0 shadow-sm mb-4">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <strong>Dokumen Saat Ini:</strong><br>
                                    <div class="mt-2">
                                        <a href="<?= esc((string) base_url($formValues['dokumen_existing'])) ?>" target="_blank" class="btn btn-sm btn-light border">
                                            <i class="bi bi-eye me-1"></i><?= esc((string) basename($formValues['dokumen_existing'])) ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Pilih File <span class="text-danger">*</span></label>
                                <input type="file" name="file_dokumen" class="form-control form-control-lg" id="file-upload"
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required
                                    data-file-preview-input
                                    data-file-preview-wrap="#file-preview"
                                    data-file-preview-name="#preview-filename"
                                    data-file-preview-size="#preview-filesize">
                                <small class="text-muted d-block mt-2">
                                    Format yang didukung: <strong>PDF, JPG, PNG, DOC, DOCX</strong>
                                </small>
                            </div>

                            <div id="file-preview" class="d-none">
                                <div class="alert alert-primary border-0 shadow-sm mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                                        <div>
                                            <div class="fw-bold">File Terpilih:</div>
                                            <span id="preview-filename"></span> (<span id="preview-filesize"></span>)
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center gap-3 border-top pt-4">
                        <a href="<?= site_url('dosen/kelengkapan-dokumen') ?>" class="btn btn-link text-secondary text-decoration-none px-4">Batal</a>
                        <button type="submit" class="btn btn-warning px-5 py-2 text-white fw-bold">
                            <i class="bi bi-upload me-2"></i><?= esc((string) $formState['submit_label']) ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>