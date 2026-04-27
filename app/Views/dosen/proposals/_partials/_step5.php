<?php

/**
 * dosen/proposals/_partials/_step5.php
 * Step 5: Data Jurnal
 */

$stepData = !empty($proposal['step_5_data']) ? json_decode($proposal['step_5_data'], true) : [];
?>

<div class="card border-0 bg-light mb-4" style="border-radius:0.8rem;">
    <div class="card-body py-3">
        <h6 class="mb-1"><i class="fas fa-book-open text-info me-1"></i> Data Jurnal Tujuan Publikasi</h6>
        <p class="text-muted small mb-0">Lengkapi identitas jurnal, tautan pendukung, dan total pengajuan dana.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label for="issn" class="form-label">ISSN <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="issn" name="issn" value="<?= esc($stepData['issn'] ?? '') ?>" placeholder="Contoh: 2087-1821" required>
        <small class="text-muted">Gunakan format ISSN resmi jurnal.</small>
    </div>

    <div class="col-md-6">
        <label for="nama_jurnal" class="form-label">Nama Jurnal <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_jurnal" name="nama_jurnal" value="<?= esc($stepData['nama_jurnal'] ?? '') ?>" placeholder="Nama lengkap jurnal" required>
    </div>

    <div class="col-12">
        <label for="profilJurnalEditor" class="form-label">Profil Jurnal <span class="text-danger">*</span></label>
        <div id="profilJurnalEditor" data-quill="profil_jurnal" class="quill-editor" style="height: 180px;"><?= $stepData['profil_jurnal'] ?? '' ?></div>
        <input type="hidden" name="profil_jurnal" id="profilJurnalInput" value="<?= esc($stepData['profil_jurnal'] ?? '') ?>">
        <small class="text-muted">Cantumkan fokus jurnal, reputasi, dan kecocokan dengan tema riset.</small>
    </div>

    <div class="col-md-6">
        <label for="url_website" class="form-label">URL Website Jurnal <span class="text-danger">*</span></label>
        <input type="url" class="form-control" id="url_website" name="url_website" value="<?= esc($stepData['url_website'] ?? '') ?>" placeholder="https://example.com/jurnal" required>
    </div>

    <div class="col-md-6">
        <label for="url_scopus_wos" class="form-label">URL Scopus/Web of Science <span class="text-danger">*</span></label>
        <input type="url" class="form-control" id="url_scopus_wos" name="url_scopus_wos" value="<?= esc($stepData['url_scopus_wos'] ?? '') ?>" placeholder="https://www.scopus.com/..." required>
    </div>

    <div class="col-md-8">
        <label for="url_surat_rekomendasi" class="form-label">URL Surat Rekomendasi Publikasi <span class="text-danger">*</span></label>
        <input type="url" class="form-control" id="url_surat_rekomendasi" name="url_surat_rekomendasi" value="<?= esc($stepData['url_surat_rekomendasi'] ?? '') ?>" placeholder="https://drive.google.com/..." required>
    </div>

    <div class="col-md-4">
        <label for="total_pengajuan_dana" class="form-label">Total Pengajuan Dana <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" class="form-control" id="total_pengajuan_dana" name="total_pengajuan_dana" value="<?= esc($stepData['total_pengajuan_dana'] ?? '') ?>" min="0" max="100000000" placeholder="0" required>
        </div>
        <small class="text-muted">Maksimal Rp 100.000.000</small>
    </div>
</div>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="<?= base_url('custom/js/proposal-quill.js') ?>?v=20260427-01"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const danaInput = document.getElementById('total_pengajuan_dana');
        if (!danaInput) {
            return;
        }

        danaInput.addEventListener('change', function() {
            this.value = parseInt(this.value, 10) || 0;
        });
    });
</script>