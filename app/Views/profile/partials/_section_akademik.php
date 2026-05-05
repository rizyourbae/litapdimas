<?php
/**
 * @var array $profile
 * @var array $master
 */
?>
<section class="profile-section-block" data-profile-section="akademik" data-profile-tab="profil">
    <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
        <i class="bi bi-mortarboard-fill me-2"></i>Akademik
    </h6>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Profesi</label>
            <select name="profil[profesi_id]" class="form-select" data-select2 data-progress-field>
                <option value="">-- Pilih Profesi --</option>
                <?php foreach ($master['profesi'] as $prof): ?>
                    <option value="<?= $prof['id'] ?>"
                        <?= (old('profil.profesi_id', $profile['profesi_id'] ?? '') == $prof['id']) ? 'selected' : '' ?>>
                        <?= esc($prof['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Bidang Ilmu</label>
            <select name="profil[bidang_ilmu_id]" class="form-select" data-select2 data-progress-field>
                <option value="">-- Pilih Bidang Ilmu --</option>
                <?php foreach ($master['bidang_ilmu'] as $bi): ?>
                    <option value="<?= $bi['id'] ?>"
                        <?= (old('profil.bidang_ilmu_id', $profile['bidang_ilmu_id'] ?? '') == $bi['id']) ? 'selected' : '' ?>>
                        <?= esc($bi['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Fakultas</label>
            <select name="profil[fakultas_id]" id="fakultasSelect"
                class="form-select cascade-parent"
                data-cascade-target="#prodiSelect" data-select2 data-progress-field>
                <option value="">-- Pilih Fakultas --</option>
                <?php foreach ($master['fakultas'] as $fak): ?>
                    <option value="<?= $fak['id'] ?>"
                        <?= (old('profil.fakultas_id', $profile['fakultas_id'] ?? '') == $fak['id']) ? 'selected' : '' ?>>
                        <?= esc($fak['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Program Studi</label>
            <select name="profil[program_studi_id]" id="prodiSelect"
                class="form-select cascade-child" data-select2 data-progress-field>
                <option value="">-- Pilih Program Studi --</option>
                <?php foreach ($master['program_studi'] as $prodi): ?>
                    <option value="<?= $prodi['id'] ?>"
                        data-parent="<?= $prodi['fakultas_id'] ?>"
                        <?= (old('profil.program_studi_id', $profile['program_studi_id'] ?? '') == $prodi['id']) ? 'selected' : '' ?>>
                        <?= esc($prodi['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Jabatan Fungsional</label>
            <select name="profil[jabatan_fungsional_id]" class="form-select" data-select2 data-progress-field>
                <option value="">-- Pilih Jabatan --</option>
                <?php foreach ($master['jabatan'] as $jab): ?>
                    <option value="<?= $jab['id'] ?>"
                        <?= (old('profil.jabatan_fungsional_id', $profile['jabatan_fungsional_id'] ?? '') == $jab['id']) ? 'selected' : '' ?>>
                        <?= esc($jab['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</section>
