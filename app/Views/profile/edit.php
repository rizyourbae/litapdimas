<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="profile-edit-page">
    <div class="card card-primary card-outline shadow-sm profile-main-card">
        <div class="card-header border-0 pb-0">
            <h3 class="card-title mb-0">
                <i class="bi bi-person-circle me-2"></i><?= esc($title) ?>
            </h3>
        </div>

        <form action="<?= site_url('profile/update') ?>" method="post" enctype="multipart/form-data"
            class="profile-edit-form" data-profile-form data-profile-active-tab="<?= esc($viewState['activeTab']) ?>">
            <?= csrf_field() ?>

            <div class="card-body pb-5">
                <div class="profile-progress-board mb-4">
                    <div class="row g-3">
                        <div class="col-xl-4">
                            <div class="profile-progress-card profile-progress-card--overall h-100">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                    <div>
                                        <div class="small text-uppercase text-muted fw-semibold mb-1">Progress Form</div>
                                        <h4 class="h5 mb-1">Ringkasan Pengisian Profil</h4>
                                        <p class="text-muted mb-0">Pantau progres setiap section tanpa harus scroll bolak-balik.</p>
                                    </div>
                                    <span class="badge text-bg-primary px-3 py-2" data-profile-overall-value>0%</span>
                                </div>
                                <div class="progress profile-progress-bar mb-2" role="progressbar" aria-label="Progress keseluruhan">
                                    <div class="progress-bar" data-profile-overall-bar style="width: 0%"></div>
                                </div>
                                <div class="small text-muted">
                                    <span data-profile-overall-text>0 dari 0 field utama terisi</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8">
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-progress-card" data-profile-summary-card="akun-core">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                            <div>
                                                <div class="fw-semibold">Data Akun</div>
                                                <div class="small text-muted">Akses dan identitas</div>
                                            </div>
                                            <span class="badge text-bg-light border" data-profile-summary-value="akun-core">0%</span>
                                        </div>
                                        <div class="progress profile-progress-bar">
                                            <div class="progress-bar" data-profile-summary-bar="akun-core" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-progress-card" data-profile-summary-card="pribadi">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                            <div>
                                                <div class="fw-semibold">Data Pribadi</div>
                                                <div class="small text-muted">Identitas dasar</div>
                                            </div>
                                            <span class="badge text-bg-light border" data-profile-summary-value="pribadi">0%</span>
                                        </div>
                                        <div class="progress profile-progress-bar">
                                            <div class="progress-bar" data-profile-summary-bar="pribadi" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-progress-card" data-profile-summary-card="kontak">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                            <div>
                                                <div class="fw-semibold">Kontak</div>
                                                <div class="small text-muted">Komunikasi aktif</div>
                                            </div>
                                            <span class="badge text-bg-light border" data-profile-summary-value="kontak">0%</span>
                                        </div>
                                        <div class="progress profile-progress-bar">
                                            <div class="progress-bar" data-profile-summary-bar="kontak" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-progress-card" data-profile-summary-card="identitas">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                            <div>
                                                <div class="fw-semibold">Identitas</div>
                                                <div class="small text-muted">Nomor dan referensi</div>
                                            </div>
                                            <span class="badge text-bg-light border" data-profile-summary-value="identitas">0%</span>
                                        </div>
                                        <div class="progress profile-progress-bar">
                                            <div class="progress-bar" data-profile-summary-bar="identitas" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-progress-card" data-profile-summary-card="akademik">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                            <div>
                                                <div class="fw-semibold">Akademik</div>
                                                <div class="small text-muted">Profesi dan afiliasi</div>
                                            </div>
                                            <span class="badge text-bg-light border" data-profile-summary-value="akademik">0%</span>
                                        </div>
                                        <div class="progress profile-progress-bar">
                                            <div class="progress-bar" data-profile-summary-bar="akademik" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="profile-progress-card" data-profile-summary-card="foto">
                                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                            <div>
                                                <div class="fw-semibold">Foto Profil</div>
                                                <div class="small text-muted">Visual akun</div>
                                            </div>
                                            <span class="badge text-bg-light border" data-profile-summary-value="foto">0%</span>
                                        </div>
                                        <div class="progress profile-progress-bar">
                                            <div class="progress-bar" data-profile-summary-bar="foto" style="width: 0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($viewState['errors'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Ada kesalahan:</strong>
                        <ul class="mb-0 ps-3 mt-1">
                            <?php foreach ((array) $viewState['errors'] as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <ul class="nav nav-tabs nav-fill profile-tabs" id="profileTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $viewState['activeTab'] === 'akun' ? 'active' : '' ?>" id="akun-tab" data-bs-toggle="tab"
                            data-bs-target="#akun" type="button" role="tab" aria-selected="<?= $viewState['activeTab'] === 'akun' ? 'true' : 'false' ?>">
                            <i class="bi bi-key me-2"></i>Data Akun
                            <span class="badge text-bg-light border ms-2 profile-tab-badge" data-profile-tab-value="akun">0%</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $viewState['activeTab'] === 'profil' ? 'active' : '' ?>" id="profil-tab" data-bs-toggle="tab"
                            data-bs-target="#profil" type="button" role="tab" aria-selected="<?= $viewState['activeTab'] === 'profil' ? 'true' : 'false' ?>">
                            <i class="bi bi-person me-2"></i>Data Profil
                            <span class="badge text-bg-light border ms-2 profile-tab-badge" data-profile-tab-value="profil">0%</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-3">
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

                    <div class="tab-pane fade <?= $viewState['activeTab'] === 'profil' ? 'show active' : '' ?>" id="profil" role="tabpanel">
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

                        <section class="profile-section-block" data-profile-section="foto" data-profile-tab="profil">
                            <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
                                <i class="bi bi-image me-2"></i>Foto Profil
                            </h6>
                            <div class="row align-items-center">
                                <div class="col-md-3 mb-3 text-center">
                                    <img id="fotoPreview" src="<?= esc($viewState['currentPhotoUrl']) ?>"
                                        class="rounded border profile-photo-preview" width="140" height="140"
                                        alt="Foto Profil" data-photo-preview-target>
                                    <div class="small text-muted mt-2">Preview foto</div>
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label class="form-label">Pilih Foto</label>
                                    <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/jpeg,image/png,image/webp"
                                        data-photo-preview-input="#fotoPreview" data-progress-field data-progress-initial-filled="<?= $viewState['hasSavedPhoto'] ? '1' : '0' ?>">
                                    <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP. Maks. 2MB.</small>
                                    <?php if ($viewState['hasSavedPhoto']): ?>
                                        <small class="text-success d-block mt-2">Foto tersimpan: <?= esc($viewState['savedPhotoName']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <div class="profile-action-bar" role="toolbar" aria-label="Aksi form profil">
                <div class="profile-action-bar__meta">
                    <div class="small text-muted">Progress keseluruhan</div>
                    <div class="fw-semibold"><span data-profile-overall-inline>0%</span> lengkap</div>
                </div>
                <div class="profile-action-bar__actions d-flex gap-2">
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>