<?php
/**
 * @var array $viewState
 */
?>
<section class="profile-section-block" data-profile-section="foto" data-profile-tab="profil">
    <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
        <i class="bi bi-image me-2"></i>Foto Profil
    </h6>
    <div class="row align-items-center">
        <div class="col-md-3 mb-3 text-center">
            <img id="fotoPreview" src="<?= route_to('profile.foto') ?>"
                class="rounded border profile-photo-preview" width="140" height="140"
                alt="Foto Profil" data-photo-preview-target>
            <div class="small text-muted mt-2">Preview foto</div>
        </div>
        <div class="col-md-9 mb-3">
            <label class="form-label">Pilih Foto</label>
            <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/jpeg,image/png,image/webp"
                data-photo-preview-input="#fotoPreview" data-progress-field data-progress-initial-filled="<?= $viewState['hasSavedPhoto'] ? '1' : '0' ?>">
            <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP. Maks. 2MB.</small>
        </div>
    </div>
</section>
