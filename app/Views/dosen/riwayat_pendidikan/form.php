<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-primary card-outline shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0"><?= esc($title) ?></h3>
        <span class="badge text-bg-light border">Form Riwayat Pendidikan</span>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= esc($formState['action_url']) ?>" method="post" id="form-riwayat" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>

            <!-- Bagian 1: Informasi Dasar -->
            <div class="card mb-3 border">
                <div class="card-header bg-light-subtle">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-1"></i>Informasi Pendidikan</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenjang Pendidikan <span class="text-danger">*</span></label>
                            <select name="jenjang_pendidikan" class="form-select" required>
                                <option value="">Pilih jenjang pendidikan</option>
                                <?php foreach ($jenjangOptions as $option): ?>
                                    <option value="<?= esc($option['value']) ?>" <?= $option['selected'] ?>>
                                        <?= esc($option['label']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
                            <input type="text" name="program_studi" class="form-control"
                                value="<?= esc($formValues['program_studi']) ?>"
                                placeholder="Contoh: Ilmu Komputer, Fisika, dll" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Institusi <span class="text-danger">*</span></label>
                            <input type="text" name="institusi" class="form-control"
                                value="<?= esc($formValues['institusi']) ?>"
                                placeholder="Contoh: Universitas Gadjah Mada, ITB, dll" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tahun Masuk <span class="text-danger">*</span></label>
                            <input type="number" name="tahun_masuk" class="form-control"
                                min="1900" max="2100" value="<?= esc($formValues['tahun_masuk']) ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tahun Lulus <span class="text-danger">*</span></label>
                            <input type="number" name="tahun_lulus" class="form-control"
                                min="1900" max="2100" value="<?= esc($formValues['tahun_lulus']) ?>" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">IPK</label>
                            <input type="text" name="ipk" class="form-control" inputmode="decimal"
                                value="<?= esc($formValues['ipk']) ?>"
                                placeholder="Contoh: 3.45" min="0" max="4" step="0.01">
                            <small class="text-muted">Skala 0-4</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Dokumen Ijazah -->
            <div class="card mb-3 border">
                <div class="card-header bg-light-subtle">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-pdf me-1"></i>Dokumen Ijazah</h6>
                    <small class="text-muted">Pilih salah satu: upload file atau gunakan URL</small>
                </div>
                <div class="card-body">
                    <!-- Mode Selector -->
                    <div class="mb-3 p-3 rounded border bg-light-subtle">
                        <label class="form-label fw-semibold d-block mb-2">Cara Upload Dokumen</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input toggle-dokumen" type="radio" name="dokumen_tipe"
                                    id="dokumen-url" value="url"
                                    <?= $formValues['dokumen_tipe'] === 'url' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="dokumen-url">
                                    <i class="bi bi-link me-1"></i>Gunakan URL
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input toggle-dokumen" type="radio" name="dokumen_tipe"
                                    id="dokumen-file" value="file"
                                    <?= $formValues['dokumen_tipe'] === 'file' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="dokumen-file">
                                    <i class="bi bi-upload me-1"></i>Upload File
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- URL Input (shown when dokumen_tipe = url) -->
                    <div id="section-url" class="<?= $formValues['dokumen_tipe'] === 'file' ? 'd-none' : '' ?>">
                        <label class="form-label fw-semibold">Tautan Dokumen</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-globe2"></i></span>
                            <input type="url" name="dokumen_ijazah_url" class="form-control"
                                value="<?= esc($formValues['dokumen_ijazah_url']) ?>"
                                placeholder="Contoh: https://drive.google.com/file/d/...">
                        </div>
                        <small class="text-muted">Masukkan link ke dokumen ijazah (Google Drive, Cloud, dll)</small>
                    </div>

                    <!-- File Upload (shown when dokumen_tipe = file) -->
                    <div id="section-file" class="<?= $formValues['dokumen_tipe'] === 'url' ? 'd-none' : '' ?>">
                        <label class="form-label fw-semibold">Unggah File Dokumen</label>
                        <div class="mb-3">
                            <input type="file" name="file_dokumen" class="form-control" id="file-upload"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" capture="environment">
                            <small class="text-muted d-block mt-2">
                                Format: PDF, JPG, PNG, DOC, DOCX (Maksimal 10MB)
                            </small>
                        </div>

                        <?php if ($formValues['dokumen_existing'] && $formValues['dokumen_existing_tipe'] === 'file'): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Dokumen Saat Ini:</strong><br>
                                <?= esc(basename($formValues['dokumen_existing'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?= site_url('dosen/riwayat-pendidikan') ?>" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i><?= esc($formState['submit_label']) ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleDokumen = document.querySelectorAll('.toggle-dokumen');
        const sectionUrl = document.getElementById('section-url');
        const sectionFile = document.getElementById('section-file');

        function toggleDokumenMode(mode) {
            if (mode === 'url') {
                sectionUrl.classList.remove('d-none');
                sectionFile.classList.add('d-none');
            } else {
                sectionUrl.classList.add('d-none');
                sectionFile.classList.remove('d-none');
            }
        }

        toggleDokumen.forEach(radio => {
            radio.addEventListener('change', function() {
                toggleDokumenMode(this.value);
            });
        });

        // Format IPK input
        const ipkInput = document.querySelector('input[name="ipk"]');
        if (ipkInput) {
            ipkInput.addEventListener('blur', function() {
                const value = parseFloat(this.value);
                if (!isNaN(value) && value >= 0 && value <= 4) {
                    this.value = value.toFixed(2);
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>