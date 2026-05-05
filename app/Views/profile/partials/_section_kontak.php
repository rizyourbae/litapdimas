<?php
/**
 * @var array $profile
 */
?>
<section class="profile-section-block" data-profile-section="kontak" data-profile-tab="profil">
    <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
        <i class="bi bi-telephone-fill me-2"></i>Kontak
    </h6>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">No HP</label>
            <input type="text" name="profil[no_hp]" class="form-control"
                placeholder="08123456789"
                value="<?= old('profil.no_hp', $profile['no_hp'] ?? '') ?>" data-progress-field>
        </div>
        <div class="col-md-8 mb-3">
            <label class="form-label">Alamat Lengkap</label>
            <textarea name="profil[alamat]" class="form-control" rows="2"
                placeholder="Jalan, Nomor, RT/RW, Kelurahan, dst..." data-progress-field><?= old('profil.alamat', $profile['alamat'] ?? '') ?></textarea>
        </div>
    </div>
</section>
