<?php
/**
 * @var array $profile
 */
?>
<section class="profile-section-block" data-profile-section="pribadi" data-profile-tab="profil">
    <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
        <i class="bi bi-person-fill me-2"></i>Data Pribadi
    </h6>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Gelar Depan</label>
            <input type="text" name="profil[gelar_depan]" class="form-control"
                placeholder="cth. Dr., Prof., Ir."
                value="<?= old('profil.gelar_depan', $profile['gelar_depan'] ?? '') ?>" data-progress-field>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Gelar Belakang</label>
            <input type="text" name="profil[gelar_belakang]" class="form-control"
                placeholder="cth. M.Kom., S.T., M.Sc."
                value="<?= old('profil.gelar_belakang', $profile['gelar_belakang'] ?? '') ?>" data-progress-field>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="profil[jenis_kelamin]" class="form-select" data-select2 data-progress-field>
                <option value="">-- Pilih --</option>
                <option value="L" <?= (old('profil.jenis_kelamin', $profile['jenis_kelamin'] ?? '') === 'L') ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= (old('profil.jenis_kelamin', $profile['jenis_kelamin'] ?? '') === 'P') ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Tempat Lahir</label>
            <input type="text" name="profil[tempat_lahir]" class="form-control"
                placeholder="cth. Jakarta"
                value="<?= old('profil.tempat_lahir', $profile['tempat_lahir'] ?? '') ?>" data-progress-field>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <div class="input-group">
                <input type="text" name="profil[tanggal_lahir]" class="form-control datepicker"
                    data-locale="id" data-date-format="Y-m-d" data-alt-format="d F Y"
                    data-max-date="today" placeholder="dd/mm/yyyy" autocomplete="off"
                    value="<?= old('profil.tanggal_lahir', $profile['tanggal_lahir'] ?? '') ?>" data-progress-field>
                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
            </div>
        </div>
    </div>
</section>
