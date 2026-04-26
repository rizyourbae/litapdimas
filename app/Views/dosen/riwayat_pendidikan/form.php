<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card dosen-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Data Akademik</span>
                            <span class="badge text-bg-primary px-3 py-2">Form Riwayat Pendidikan</span>
                        </div>
                        <h2 class="h3 dosen-hero__title mb-2"><?= esc($title) ?></h2>
                        <p class="dosen-hero__subtitle mb-0">Isi data pendidikan secara ringkas, konsisten, dan mudah dipelihara.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm dosen-form-card">
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

                <form action="<?= esc($formState['action_url']) ?>" method="post" id="form-riwayat" enctype="multipart/form-data" novalidate data-riwayat-form>
                    <?= csrf_field() ?>

                    <div class="card mb-3 border">
                        <div class="card-header bg-light-subtle">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-1"></i>Informasi Pendidikan</h6>
                            <small class="dosen-section-note">Data dasar pendidikan ditampilkan terlebih dahulu agar mudah dibaca.</small>
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
                                    <input type="text" name="ipk" class="form-control" data-ipk-field inputmode="decimal"
                                        value="<?= esc($formValues['ipk']) ?>"
                                        placeholder="Contoh: 3.45">
                                    <small class="text-muted">Skala 0-4</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 border">
                        <div class="card-header bg-light-subtle">
                            <h6 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-pdf me-1"></i>Dokumen Ijazah</h6>
                            <small class="dosen-section-note">Pilih salah satu: gunakan URL atau unggah file dokumen.</small>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 p-3 rounded border bg-light-subtle">
                                <label class="form-label fw-semibold d-block mb-2">Cara Upload Dokumen</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="dokumen_tipe"
                                            id="dokumen-url" value="url" data-dokumen-toggle
                                            <?= $formValues['dokumen_tipe'] === 'url' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="dokumen-url">
                                            <i class="bi bi-link me-1"></i>Gunakan URL
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="dokumen_tipe"
                                            id="dokumen-file" value="file" data-dokumen-toggle
                                            <?= $formValues['dokumen_tipe'] === 'file' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="dokumen-file">
                                            <i class="bi bi-upload me-1"></i>Upload File
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="section-url" data-dokumen-section="url" class="<?= $formValues['dokumen_tipe'] === 'file' ? 'd-none' : '' ?>">
                                <label class="form-label fw-semibold">Tautan Dokumen</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="bi bi-globe2"></i></span>
                                    <input type="url" name="dokumen_ijazah_url" class="form-control"
                                        value="<?= esc($formValues['dokumen_ijazah_url']) ?>"
                                        placeholder="Contoh: https://drive.google.com/file/d/...">
                                </div>
                                <small class="text-muted">Masukkan link ke dokumen ijazah (Google Drive, Cloud, dll).</small>
                            </div>

                            <div id="section-file" data-dokumen-section="file" class="<?= $formValues['dokumen_tipe'] === 'url' ? 'd-none' : '' ?>">
                                <label class="form-label fw-semibold">Unggah File Dokumen</label>
                                <div class="mb-3">
                                    <input type="file" name="file_dokumen" class="form-control" id="file-upload"
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" capture="environment"
                                        data-file-preview-input
                                        data-file-preview-wrap="#file-preview"
                                        data-file-preview-name="#preview-filename"
                                        data-file-preview-size="#preview-filesize">
                                    <small class="text-muted d-block mt-2">
                                        Format: PDF, JPG, PNG, DOC, DOCX. Maksimal 10MB.
                                    </small>
                                </div>

                                <?php if ($formValues['dokumen_existing'] && $formValues['dokumen_existing_tipe'] === 'file'): ?>
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <strong>Dokumen Saat Ini:</strong><br>
                                        <?= esc(basename($formValues['dokumen_existing'])) ?>
                                    </div>
                                <?php endif; ?>

                                <div id="file-preview" class="d-none">
                                    <div class="alert alert-info mb-0">
                                        <strong>File Terpilih:</strong><br>
                                        <span id="preview-filename"></span>
                                        (<span id="preview-filesize"></span>)
                                    </div>
                                </div>
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
    </div>
</div>

<?= $this->endSection() ?>