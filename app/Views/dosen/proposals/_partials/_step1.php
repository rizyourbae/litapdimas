<?php

/**
 * dosen/proposals/_partials/_step1.php
 * Step 1: Pernyataan Peneliti
 */
?>

<div class="card border-0 bg-light mb-4" style="border-radius:0.8rem;">
    <div class="card-body py-3">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-info-circle text-primary mt-1"></i>
            <div>
                <h6 class="mb-1">Informasi Dasar Proposal</h6>
                <p class="text-muted small mb-0">Lengkapi data utama proposal dan pastikan semua pilihan kategori sesuai fokus penelitian.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <label for="judul" class="form-label">Judul Usulan <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="judul" name="judul" value="<?= old('judul', $proposal['judul'] ?? '') ?>" placeholder="Masukkan judul usulan penelitian" required>
        <small class="text-muted">Gunakan judul yang spesifik, ringkas, dan mewakili tujuan penelitian.</small>
    </div>

    <div class="col-12">
        <label for="kata_kunci" class="form-label">Kata Kunci <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="kata_kunci" name="kata_kunci" value="<?= old('kata_kunci', $proposal['kata_kunci'] ?? '') ?>" placeholder="Contoh: pendidikan, inovasi, pembelajaran" required>
        <small class="text-muted">Minimal 3 kata kunci dipisahkan dengan koma.</small>
    </div>

    <?= view('dosen/proposals/_partials/_form_select', [
        'name' => 'pengelola_bantuan_id',
        'label' => 'Pengelola Bantuan',
        'options' => $masterOptions['pengelola_bantuan'] ?? [],
        'selected' => old('pengelola_bantuan_id', $proposal['pengelola_bantuan_id'] ?? 0)
    ]) ?>

    <?= view('dosen/proposals/_partials/_form_select', [
        'name' => 'klaster_bantuan_id',
        'label' => 'Klaster Bantuan',
        'options' => $masterOptions['klaster_bantuan'] ?? [],
        'selected' => old('klaster_bantuan_id', $proposal['klaster_bantuan_id'] ?? 0)
    ]) ?>

    <?= view('dosen/proposals/_partials/_form_select', [
        'name' => 'bidang_ilmu_id',
        'label' => 'Bidang Ilmu',
        'options' => $masterOptions['bidang_ilmu'] ?? [],
        'selected' => old('bidang_ilmu_id', $proposal['bidang_ilmu_id'] ?? 0)
    ]) ?>

    <?= view('dosen/proposals/_partials/_form_select', [
        'name' => 'tema_penelitian_id',
        'label' => 'Tema Penelitian',
        'options' => $masterOptions['tema_penelitian'] ?? [],
        'selected' => old('tema_penelitian_id', $proposal['tema_penelitian_id'] ?? 0)
    ]) ?>

    <?= view('dosen/proposals/_partials/_form_select', [
        'name' => 'jenis_penelitian_id',
        'label' => 'Jenis Penelitian',
        'options' => $masterOptions['jenis_penelitian'] ?? [],
        'selected' => old('jenis_penelitian_id', $proposal['jenis_penelitian_id'] ?? 0)
    ]) ?>

    <?= view('dosen/proposals/_partials/_form_select', [
        'name' => 'kontribusi_prodi_id',
        'label' => 'Kontribusi Prodi',
        'options' => $masterOptions['kontribusi_prodi'] ?? [],
        'selected' => old('kontribusi_prodi_id', $proposal['kontribusi_prodi_id'] ?? 0)
    ]) ?>
</div>

<div class="card border-warning mt-4" style="border-radius:0.8rem;">
    <div class="card-header bg-warning-subtle">
        <h6 class="mb-0"><i class="bi bi-shield-check me-1"></i> Pernyataan Peneliti</h6>
    </div>
    <div class="card-body">
        <p class="text-muted small">Centang semua pernyataan untuk melanjutkan ke tahap berikutnya.</p>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="statement_1" name="statement_1" value="1" <?= old('statement_1', $proposal['statement_1'] ?? '') ? 'checked' : '' ?> required>
            <label class="form-check-label" for="statement_1">
                Proposal ini adalah hasil karya saya sendiri dan tidak melanggar hak kekayaan intelektual.
            </label>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="statement_2" name="statement_2" value="1" <?= old('statement_2', $proposal['statement_2'] ?? '') ? 'checked' : '' ?> required>
            <label class="form-check-label" for="statement_2">
                Data dan informasi yang saya berikan benar serta dapat dipertanggungjawabkan.
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="statement_3" name="statement_3" value="1" <?= old('statement_3', $proposal['statement_3'] ?? '') ? 'checked' : '' ?> required>
            <label class="form-check-label" for="statement_3">
                Saya bersedia diproses sesuai ketentuan yang berlaku.
            </label>
        </div>
    </div>
</div>