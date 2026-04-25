<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-primary card-outline shadow-sm">
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

            <!-- Informasi Dokumen -->
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

            <!-- Unggah Dokumen -->
            <div class="card mb-3 border">
                <div class="card-header bg-light-subtle">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-pdf me-1"></i>Unggah Dokumen</h6>
                    <small class="text-muted">Pilih file dokumen (PDF, JPG, PNG, DOC, DOCX) - Maksimal 10MB</small>
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
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                        <small class="text-muted d-block mt-2">
                            Format yang didukung: PDF, JPG, PNG, DOC, DOCX
                        </small>
                    </div>

                    <!-- File Preview -->
                    <div id="file-preview" class="d-none">
                        <div class="alert alert-info">
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file-upload');
        const filePreview = document.getElementById('file-preview');
        const previewFilename = document.getElementById('preview-filename');
        const previewFilesize = document.getElementById('preview-filesize');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    previewFilename.textContent = file.name;
                    previewFilesize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                    filePreview.classList.remove('d-none');
                } else {
                    filePreview.classList.add('d-none');
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>