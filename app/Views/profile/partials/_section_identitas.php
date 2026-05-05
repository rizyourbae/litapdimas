<?php
/**
 * @var array $profile
 */
?>
<section class="profile-section-block" data-profile-section="identitas" data-profile-tab="profil">
    <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
        <i class="bi bi-card-text me-2"></i>Identitas
    </h6>
    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">NIK</label>
            <input type="text" name="profil[nik]" class="form-control"
                placeholder="16 digit NIK"
                value="<?= old('profil.nik', $profile['nik'] ?? '') ?>" data-progress-field>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">NIDN</label>
            <input type="text" name="profil[nidn]" class="form-control"
                placeholder="NIDN dosen"
                value="<?= old('profil.nidn', $profile['nidn'] ?? '') ?>" data-progress-field>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">NIP (ASN)</label>
            <input type="text" name="profil[nip]" class="form-control"
                placeholder="NIP ASN"
                value="<?= old('profil.nip', $profile['nip'] ?? '') ?>" data-progress-field>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">ID SINTA</label>
            <input type="text" name="profil[id_sinta]" class="form-control"
                placeholder="ID SINTA"
                value="<?= old('profil.id_sinta', $profile['id_sinta'] ?? '') ?>" data-progress-field>
        </div>
    </div>
</section>
