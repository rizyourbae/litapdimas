<?php

/**
 * dosen/proposals/_partials/_step3.php
 * Step 3: Substansi Usulan (with Quill editors)
 */

$stepData = !empty($proposal['step_3_data']) ? json_decode($proposal['step_3_data'], true) : [];
$abstrak = $stepData['abstrak'] ?? '';
$sections = $stepData['substansi_bagian'] ?? [];
?>

<div class="card border-0 bg-light mb-4" style="border-radius:0.8rem;">
    <div class="card-body py-3">
        <h6 class="mb-1"><i class="fas fa-file-lines text-primary me-1"></i> Substansi Usulan</h6>
        <p class="text-muted small mb-0">Tulis abstrak yang ringkas dan susun substansi proposal dalam beberapa bagian agar mudah direview.</p>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <label for="abstrakEditor" class="form-label">Abstrak <span class="text-danger">*</span></label>
        <div id="abstrakEditor" data-quill="abstrak" class="quill-editor" style="height: 180px;"><?= $abstrak ?></div>
        <input type="hidden" name="abstrak" id="abstrakInput" value="<?= esc($abstrak) ?>">
        <small class="text-muted">Minimal 50 karakter. Jelaskan tujuan, metode, dan manfaat penelitian.</small>
    </div>
</div>

<div class="card" style="border-radius:0.8rem;">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="mb-0 flex-grow-1">Bagian Substansi <span class="text-danger">*</span></h6>
        <button type="button" class="btn btn-success btn-sm ms-2" onclick="addSubstansiSection()">
            <i class="fas fa-plus me-1"></i> Tambah Bagian
        </button>
    </div>
    <div class="card-body">
        <div id="substansiContainer">
            <?php if (count($sections) > 0): ?>
                <?php foreach ($sections as $index => $section): ?>
                    <div class="substansi-section card mb-3" data-index="<?= $index ?>">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label">Judul Bagian</label>
                                    <input type="text" class="form-control" name="substansi_bagian[<?= $index ?>][judul_bagian]" value="<?= esc($section['judul_bagian'] ?? '') ?>" placeholder="Contoh: Latar Belakang, Metodologi, Luaran">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label">Isi Bagian</label>
                                    <div class="quill-editor" data-quill="substansi[<?= $index ?>]" style="height: 160px;"><?= $section['isi_bagian'] ?? '' ?></div>
                                    <input type="hidden" name="substansi_bagian[<?= $index ?>][isi_bagian]" class="substansi-isi" value="<?= esc($section['isi_bagian'] ?? '') ?>">
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSubstansiSection(this)">
                                <i class="fas fa-trash me-1"></i> Hapus Bagian
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <p class="text-muted small mb-0">Tips: buat struktur yang konsisten agar reviewer mudah menilai kelayakan usulan.</p>
    </div>
</div>

<script>
    let substansiCounter = <?= count($sections) ?>;

    function addSubstansiSection() {
        const container = document.getElementById('substansiContainer');
        const index = substansiCounter++;

        const html = `
        <div class="substansi-section card mb-3" data-index="${index}">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Judul Bagian</label>
                        <input type="text" class="form-control" name="substansi_bagian[${index}][judul_bagian]" placeholder="Contoh: Latar Belakang, Metodologi, Luaran">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">Isi Bagian</label>
                        <div class="quill-editor" data-quill="substansi[${index}]" style="height: 160px;"></div>
                        <input type="hidden" name="substansi_bagian[${index}][isi_bagian]" class="substansi-isi">
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSubstansiSection(this)">
                    <i class="fas fa-trash me-1"></i> Hapus Bagian
                </button>
            </div>
        </div>`;

        container.insertAdjacentHTML('beforeend', html);

        if (typeof initializeQuillEditors === 'function') {
            initializeQuillEditors();
        }
    }

    function removeSubstansiSection(button) {
        const section = button.closest('.substansi-section');
        if (section) {
            section.remove();
        }
    }
</script>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="<?= base_url('custom/js/proposal-quill.js') ?>?v=20260427-01"></script>