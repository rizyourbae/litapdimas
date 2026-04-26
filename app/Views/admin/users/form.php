<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Admin Workspace</span>
                            <span class="badge text-bg-primary px-3 py-2"><?= $viewState['isEdit'] ? 'Update User' : 'Create User' ?></span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($title) ?></h2>
                    </div>
                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12">
        <div class="card card-primary card-outline admin-form-card shadow-sm">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title mb-0">
                    <i class="bi bi-person-check me-2"></i><?= esc($title) ?>
                </h3>
            </div>
            <form action="<?= esc($action ?? site_url('admin/users/store')) ?>" method="post" enctype="multipart/form-data"
                data-admin-tab-form data-admin-active-tab="<?= esc($viewState['activeTab']) ?>" data-submit-state-form>
                <?= csrf_field() ?>
                <?php if (isset($user)): ?>
                    <input type="hidden" name="uuid" value="<?= esc($user['uuid']) ?>">
                <?php endif; ?>

                <div class="card-body">
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

                    <ul class="nav nav-tabs nav-fill admin-tabs" id="userTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $viewState['activeTab'] === 'akun' ? 'active' : '' ?>" id="akun-tab" data-bs-toggle="tab" data-bs-target="#akun" type="button" role="tab">
                                <i class="bi bi-key me-2"></i>Data Akun
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $viewState['activeTab'] === 'profil' ? 'active' : '' ?>" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil" type="button" role="tab">
                                <i class="bi bi-person me-2"></i>Data Profil
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <div class="tab-pane fade <?= $viewState['activeTab'] === 'akun' ? 'show active' : '' ?>" id="akun" role="tabpanel">
                            <section class="admin-section-block">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                                        <input type="text" name="username" class="form-control <?= !empty($viewState['errors']['username']) ? 'is-invalid' : '' ?>"
                                            value="<?= old('username', $user['username'] ?? '') ?>" placeholder="Minimal 6 karakter" required>
                                        <?php if (!empty($viewState['errors']['username'])): ?>
                                            <div class="invalid-feedback"><?= esc($viewState['errors']['username']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control <?= !empty($viewState['errors']['email']) ? 'is-invalid' : '' ?>"
                                            value="<?= old('email', $user['email'] ?? '') ?>" placeholder="email@example.com" required>
                                        <?php if (!empty($viewState['errors']['email'])): ?>
                                            <div class="invalid-feedback"><?= esc($viewState['errors']['email']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" class="form-control <?= !empty($viewState['errors']['nama_lengkap']) ? 'is-invalid' : '' ?>"
                                        value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>" placeholder="Nama lengkap sesuai KTP" required>
                                    <?php if (!empty($viewState['errors']['nama_lengkap'])): ?>
                                        <div class="invalid-feedback"><?= esc($viewState['errors']['nama_lengkap']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Password <?php if (!$viewState['isEdit']): ?><span class="text-danger">*</span><?php endif; ?>
                                    </label>
                                    <input type="password" name="password" class="form-control <?= !empty($viewState['errors']['password']) ? 'is-invalid' : '' ?>"
                                        placeholder="Masukkan password" <?php if (!$viewState['isEdit']): ?>required<?php endif; ?>>
                                    <?php if (!empty($viewState['errors']['password'])): ?>
                                        <div class="invalid-feedback"><?= esc($viewState['errors']['password']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($viewState['isEdit']): ?>
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                    <?php else: ?>
                                        <small class="text-muted">Minimal 6 karakter untuk user baru.</small>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Status Aktif</label>
                                        <select name="aktif" class="form-select" data-select2>
                                            <option value="1" <?= (old('aktif', $user['aktif'] ?? 1) == 1) ? 'selected' : '' ?>>Aktif</option>
                                            <option value="0" <?= (old('aktif', $user['aktif'] ?? 1) == 0) ? 'selected' : '' ?>>Nonaktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <?php foreach ($roles as $role): ?>
                                            <div class="col-md-6">
                                                <label class="admin-role-item">
                                                    <input class="form-check-input" type="checkbox" id="role-<?= $role['id'] ?>" name="roles[]" value="<?= $role['id'] ?>"
                                                        <?= in_array($role['id'], old('roles', $user['roles'] ?? [])) ? 'checked' : '' ?>>
                                                    <span>
                                                        <strong><?= esc($role['name']) ?></strong>
                                                        <small class="d-block text-muted">Hak akses sesuai peran sistem.</small>
                                                    </span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="tab-pane fade <?= $viewState['activeTab'] === 'profil' ? 'show active' : '' ?>" id="profil" role="tabpanel">
                            <section class="admin-section-block">
                                <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3"><i class="bi bi-person-fill me-2"></i>Data Pribadi</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gelar Depan</label>
                                        <input type="text" name="profil[gelar_depan]" class="form-control" placeholder="cth. Dr., Prof., Ir." value="<?= old('profil.gelar_depan', $viewState['profile']['gelar_depan'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gelar Belakang</label>
                                        <input type="text" name="profil[gelar_belakang]" class="form-control" placeholder="cth. M.Kom., S.T., M.Sc." value="<?= old('profil.gelar_belakang', $viewState['profile']['gelar_belakang'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select name="profil[jenis_kelamin]" class="form-select" data-select2>
                                            <option value="">-- Pilih --</option>
                                            <option value="L" <?= (old('profil.jenis_kelamin', $viewState['profile']['jenis_kelamin'] ?? '') == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                            <option value="P" <?= (old('profil.jenis_kelamin', $viewState['profile']['jenis_kelamin'] ?? '') == 'P') ? 'selected' : '' ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" name="profil[tempat_lahir]" class="form-control" placeholder="cth. Jakarta" value="<?= old('profil.tempat_lahir', $viewState['profile']['tempat_lahir'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <div class="input-group">
                                            <input type="text" name="profil[tanggal_lahir]" class="form-control datepicker"
                                                data-locale="id" data-date-format="Y-m-d" data-alt-format="d F Y" data-max-date="today" placeholder="dd/mm/yyyy" autocomplete="off"
                                                value="<?= old('profil.tanggal_lahir', $viewState['profile']['tanggal_lahir'] ?? '') ?>">
                                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="admin-section-block">
                                <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3"><i class="bi bi-telephone-fill me-2"></i>Kontak</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">No HP</label>
                                        <input type="text" name="profil[no_hp]" class="form-control" placeholder="08123456789" value="<?= old('profil.no_hp', $viewState['profile']['no_hp'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea name="profil[alamat]" class="form-control" rows="2" placeholder="Jalan, Nomor, RT/RW, Kelurahan, dst..."><?= old('profil.alamat', $viewState['profile']['alamat'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </section>

                            <section class="admin-section-block">
                                <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3"><i class="bi bi-card-text me-2"></i>Identitas</h6>
                                <div class="row">
                                    <div class="col-md-3 mb-3"><label class="form-label">NIK</label><input type="text" name="profil[nik]" class="form-control" placeholder="16 digit NIK" value="<?= old('profil.nik', $viewState['profile']['nik'] ?? '') ?>"></div>
                                    <div class="col-md-3 mb-3"><label class="form-label">NIDN (Dosen)</label><input type="text" name="profil[nidn]" class="form-control" placeholder="NIDN dosen" value="<?= old('profil.nidn', $viewState['profile']['nidn'] ?? '') ?>"></div>
                                    <div class="col-md-3 mb-3"><label class="form-label">NIP (ASN)</label><input type="text" name="profil[nip]" class="form-control" placeholder="NIP ASN" value="<?= old('profil.nip', $viewState['profile']['nip'] ?? '') ?>"></div>
                                    <div class="col-md-3 mb-3"><label class="form-label">ID SINTA</label><input type="text" name="profil[id_sinta]" class="form-control" placeholder="ID SINTA" value="<?= old('profil.id_sinta', $viewState['profile']['id_sinta'] ?? '') ?>"></div>
                                </div>
                            </section>

                            <section class="admin-section-block">
                                <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3"><i class="bi bi-mortarboard-fill me-2"></i>Akademik</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Profesi</label>
                                        <select name="profil[profesi_id]" class="form-select" data-select2>
                                            <option value="">-- Pilih Profesi --</option>
                                            <?php foreach ($master['profesi'] as $prof): ?>
                                                <option value="<?= $prof['id'] ?>" <?= (old('profil.profesi_id', $viewState['profile']['profesi_id'] ?? '') == $prof['id']) ? 'selected' : '' ?>><?= esc($prof['nama']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Bidang Ilmu</label>
                                        <select name="profil[bidang_ilmu_id]" class="form-select" data-select2>
                                            <option value="">-- Pilih Bidang Ilmu --</option>
                                            <?php foreach ($master['bidang_ilmu'] as $bi): ?>
                                                <option value="<?= $bi['id'] ?>" <?= (old('profil.bidang_ilmu_id', $viewState['profile']['bidang_ilmu_id'] ?? '') == $bi['id']) ? 'selected' : '' ?>><?= esc($bi['nama']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fakultas</label>
                                        <select name="profil[fakultas_id]" id="fakultasSelect" class="form-select cascade-parent" data-cascade-target="#prodiSelect" data-select2>
                                            <option value="">-- Pilih Fakultas --</option>
                                            <?php foreach ($master['fakultas'] as $fak): ?>
                                                <option value="<?= $fak['id'] ?>" <?= (old('profil.fakultas_id', $viewState['profile']['fakultas_id'] ?? '') == $fak['id']) ? 'selected' : '' ?>><?= esc($fak['nama']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Program Studi</label>
                                        <select name="profil[program_studi_id]" id="prodiSelect" class="form-select cascade-child" data-select2>
                                            <option value="">-- Pilih Program Studi --</option>
                                            <?php foreach ($master['program_studi'] as $prodi): ?>
                                                <option value="<?= $prodi['id'] ?>" data-parent="<?= $prodi['fakultas_id'] ?>" <?= (old('profil.program_studi_id', $viewState['profile']['program_studi_id'] ?? '') == $prodi['id']) ? 'selected' : '' ?>><?= esc($prodi['nama']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jabatan Fungsional</label>
                                        <select name="profil[jabatan_fungsional_id]" class="form-select" data-select2>
                                            <option value="">-- Pilih Jabatan --</option>
                                            <?php foreach ($master['jabatan'] as $jab): ?>
                                                <option value="<?= $jab['id'] ?>" <?= (old('profil.jabatan_fungsional_id', $viewState['profile']['jabatan_fungsional_id'] ?? '') == $jab['id']) ? 'selected' : '' ?>><?= esc($jab['nama']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </section>

                            <section class="admin-section-block">
                                <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3"><i class="bi bi-image me-2"></i>Foto Profil</h6>
                                <div class="row align-items-center">
                                    <div class="col-md-3 mb-3 text-center">
                                        <img src="<?= esc($viewState['currentPhotoUrl']) ?>" class="rounded border admin-photo-preview" width="120" height="120" alt="Foto Profil">
                                        <div class="small text-muted mt-2">Preview foto</div>
                                    </div>
                                    <div class="col-md-9 mb-3">
                                        <label class="form-label">Pilih Foto</label>
                                        <input type="file" name="foto" class="form-control" accept="image/*">
                                        <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP. Maks. 2MB.</small>
                                        <?php if ($viewState['hasPhoto']): ?>
                                            <small class="text-success d-block mt-2">Foto tersimpan: <?= esc($viewState['photoName']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>

                <div class="card-footer admin-form-footer d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                            <i class="bi bi-save"></i>
                            <span>Simpan</span>
                        </span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <span>Menyimpan...</span>
                        </span>
                    </button>
                    <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>