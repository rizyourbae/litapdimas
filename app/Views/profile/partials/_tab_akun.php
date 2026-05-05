<?php
/**
 * @var array $user
 * @var array $viewState
 */
?>
<div class="tab-pane fade <?= $viewState['activeTab'] === 'akun' ? 'show active' : '' ?>" id="akun" role="tabpanel">
    <section class="profile-section-block" data-profile-section="akun-core" data-profile-tab="akun">
        <div class="profile-section-header mb-3">
            <h6 class="mb-1 fw-semibold text-body">Data Akun</h6>
            <p class="small text-muted mb-0">Pastikan identitas akun mudah dikenali dan tetap aman.</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                <input type="text" name="username"
                    class="form-control <?= !empty($viewState['errors']['username']) ? 'is-invalid' : '' ?>"
                    value="<?= old('username', $user['username'] ?? '') ?>"
                    placeholder="Minimal 6 karakter" required data-progress-field>
                <?php if (!empty($viewState['errors']['username'])): ?>
                    <div class="invalid-feedback"><?= esc($viewState['errors']['username']) ?></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email"
                    class="form-control <?= !empty($viewState['errors']['email']) ? 'is-invalid' : '' ?>"
                    value="<?= old('email', $user['email'] ?? '') ?>"
                    placeholder="email@example.com" required data-progress-field>
                <?php if (!empty($viewState['errors']['email'])): ?>
                    <div class="invalid-feedback"><?= esc($viewState['errors']['email']) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama_lengkap"
                class="form-control <?= !empty($viewState['errors']['nama_lengkap']) ? 'is-invalid' : '' ?>"
                value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>"
                placeholder="Nama lengkap sesuai KTP" required data-progress-field>
            <?php if (!empty($viewState['errors']['nama_lengkap'])): ?>
                <div class="invalid-feedback"><?= esc($viewState['errors']['nama_lengkap']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-0">
            <label class="form-label fw-semibold">Ganti Password</label>
            <input type="password" name="password" class="form-control"
                placeholder="Kosongkan jika tidak ingin mengubah password">
            <?php if (!empty($viewState['errors']['password'])): ?>
                <div class="text-danger small mt-1"><?= esc($viewState['errors']['password']) ?></div>
            <?php endif; ?>
            <small class="text-muted">Isi hanya jika ingin mengganti password. Minimal 6 karakter.</small>
        </div>
    </section>
</div>
