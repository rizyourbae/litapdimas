<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
/** @var string $title */
/** @var string $action */
/** @var array<string,mixed>|null $user */
/** @var array<int,array{id:int,name:string}> $roles */
/** @var array{profesi:array<int,array{id:int,nama:string}>,bidang_ilmu:array<int,array{id:int,nama:string}>,fakultas:array<int,array{id:int,nama:string}>,program_studi:array<int,array{id:int,nama:string,fakultas_id:int|string}>,jabatan:array<int,array{id:int,nama:string}>} $master */
/** @var array{
 *     isEdit:bool,
 *     activeTab:string,
 *     errors:array<string,string>,
 *     profile:array<string,mixed>,
 *     currentPhotoUrl:string,
 *     hasPhoto:bool,
 *     photoName:string
 * } $viewState */
?>

<div class="row g-3 admin-page">
    <div class="col-12">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Konfigurasi data akun dan profil detail pengguna.',
            'badges' => [
                ['label' => 'Admin Workspace', 'class' => 'text-bg-light border'],
                ['label' => $viewState['isEdit'] ? 'Update Mode' : 'Creation Mode', 'class' => 'text-bg-primary']
            ],
            'actions' => [
                [
                    'label' => 'Kembali ke Daftar',
                    'class' => 'btn btn-outline-secondary',
                    'icon' => 'bi bi-arrow-left',
                    'url' => site_url('admin/users')
                ]
            ]
        ]) ?>
    </div>

    <div class="col-12">
        <form action="<?= esc((string) $action) ?>" method="post" enctype="multipart/form-data" id="userForm"
            data-admin-tab-form data-admin-active-tab="<?= esc((string) $viewState['activeTab']) ?>" data-submit-state-form>
            <?= csrf_field() ?>
            <?php if (isset($user)): ?>
                <input type="hidden" name="uuid" value="<?= esc((string) ($user['uuid'] ?? '')) ?>">
            <?php endif; ?>

            <div class="card shadow-sm border-0 overflow-hidden mb-5">
                <div class="card-header p-0 bg-light border-bottom">
                    <ul class="nav nav-tabs nav-fill border-0" id="userTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $viewState['activeTab'] === 'akun' ? 'active' : '' ?> border-0 py-3 fw-bold" id="akun-tab" data-bs-toggle="tab" data-bs-target="#akun" type="button" role="tab">
                                <i class="bi bi-shield-lock me-2"></i>Kredensial & Peran
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $viewState['activeTab'] === 'profil' ? 'active' : '' ?> border-0 py-3 fw-bold" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil" type="button" role="tab">
                                <i class="bi bi-person-badge me-2"></i>Identitas & Profil
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <?php if (!empty($viewState['errors'])): ?>
                        <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-start gap-3">
                            <i class="bi bi-exclamation-octagon-fill fs-4 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Periksa Kembali Isian Anda</h6>
                                <ul class="small mb-0 ps-3">
                                    <?php foreach ($viewState['errors'] as $error): ?>
                                        <li><?= esc((string) $error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="tab-content" id="userTabContent">
                        <!-- Tab: Data Akun -->
                        <div class="tab-pane fade <?= $viewState['activeTab'] === 'akun' ? 'show active' : '' ?>" id="akun" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <h6 class="fw-bold text-dark text-uppercase small mb-4 ls-1">Informasi Kredensial</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Username <span class="text-danger">*</span></label>
                                            <input type="text" name="username" class="form-control form-control-lg fs-6 shadow-none <?= !empty($viewState['errors']['username']) ? 'is-invalid' : '' ?>"
                                                value="<?= old('username', $user['username'] ?? '') ?>" placeholder="Minimal 6 karakter" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Email Pengguna <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control form-control-lg fs-6 shadow-none <?= !empty($viewState['errors']['email']) ? 'is-invalid' : '' ?>"
                                                value="<?= old('email', $user['email'] ?? '') ?>" placeholder="email@example.com" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold text-muted">Nama Lengkap Sesuai Identitas <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_lengkap" class="form-control form-control-lg fs-6 shadow-none <?= !empty($viewState['errors']['nama_lengkap']) ? 'is-invalid' : '' ?>"
                                                value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>" placeholder="Nama lengkap tanpa gelar" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label small fw-bold text-muted">
                                                Password Keamanan <?php if (!$viewState['isEdit']): ?><span class="text-danger">*</span><?php endif; ?>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="password" class="form-control form-control-lg fs-6 shadow-none <?= !empty($viewState['errors']['password']) ? 'is-invalid' : '' ?>"
                                                    placeholder="<?= $viewState['isEdit'] ? 'Kosongkan jika tidak diubah' : 'Masukkan password' ?>" <?php if (!$viewState['isEdit']): ?>required<?php endif; ?>>
                                                <span class="input-group-text bg-white text-muted"><i class="bi bi-eye"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold text-muted">Status Akun</label>
                                            <select name="aktif" class="form-select form-select-lg fs-6 shadow-none">
                                                <option value="1" <?= (old('aktif', $user['aktif'] ?? 1) == 1) ? 'selected' : '' ?>>Aktif</option>
                                                <option value="0" <?= (old('aktif', $user['aktif'] ?? 1) == 0) ? 'selected' : '' ?>>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="bg-light p-4 rounded-3 h-100 border">
                                        <h6 class="fw-bold text-dark text-uppercase small mb-4 ls-1">Peran & Akses <span class="text-danger">*</span></h6>
                                        <div class="d-flex flex-column gap-2">
                                            <?php foreach ($roles as $role): ?>
                                                <label class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3 cursor-pointer hover-shadow-sm transition-all">
                                                    <input class="form-check-input mt-0 shadow-none" type="checkbox" name="roles[]" value="<?= esc((string) $role['id']) ?>"
                                                        <?= in_array($role['id'], (array) old('roles', $user['roles'] ?? []), true) ? 'checked' : '' ?>>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold text-dark small"><?= esc($role['name']) ?></div>
                                                        <div class="text-muted" style="font-size: 0.75rem;">Akses fitur sesuai peran.</div>
                                                    </div>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Data Profil -->
                        <div class="tab-pane fade <?= $viewState['activeTab'] === 'profil' ? 'show active' : '' ?>" id="profil" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-lg-9">
                                    <div class="mb-5">
                                        <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Data Pribadi</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted">Gelar Depan</label>
                                                <input type="text" name="profil[gelar_depan]" class="form-control shadow-none" placeholder="cth. Dr., Prof." value="<?= old('profil.gelar_depan', $viewState['profile']['gelar_depan'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label small fw-bold text-muted">Gelar Belakang</label>
                                                <input type="text" name="profil[gelar_belakang]" class="form-control shadow-none" placeholder="cth. M.Kom., Ph.D" value="<?= old('profil.gelar_belakang', $viewState['profile']['gelar_belakang'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted">Jenis Kelamin</label>
                                                <select name="profil[jenis_kelamin]" class="form-select shadow-none">
                                                    <option value="">-- Pilih --</option>
                                                    <option value="L" <?= (old('profil.jenis_kelamin', $viewState['profile']['jenis_kelamin'] ?? '') == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                                    <option value="P" <?= (old('profil.jenis_kelamin', $viewState['profile']['jenis_kelamin'] ?? '') == 'P') ? 'selected' : '' ?>>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted">Tempat Lahir</label>
                                                <input type="text" name="profil[tempat_lahir]" class="form-control shadow-none" value="<?= old('profil.tempat_lahir', $viewState['profile']['tempat_lahir'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted">Tanggal Lahir</label>
                                                <input type="text" name="profil[tanggal_lahir]" class="form-control datepicker shadow-none" placeholder="yyyy-mm-dd" value="<?= old('profil.tanggal_lahir', $viewState['profile']['tanggal_lahir'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold text-muted">No HP / WhatsApp</label>
                                                <input type="text" name="profil[no_hp]" class="form-control shadow-none" value="<?= old('profil.no_hp', $viewState['profile']['no_hp'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label small fw-bold text-muted">Alamat Tinggal</label>
                                                <input type="text" name="profil[alamat]" class="form-control shadow-none" value="<?= old('profil.alamat', $viewState['profile']['alamat'] ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Identitas & Akademik</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4"><label class="form-label small fw-bold text-muted">NIK (KTP)</label><input type="text" name="profil[nik]" class="form-control shadow-none" value="<?= old('profil.nik', $viewState['profile']['nik'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label small fw-bold text-muted">NIDN (Dosen)</label><input type="text" name="profil[nidn]" class="form-control shadow-none" value="<?= old('profil.nidn', $viewState['profile']['nidn'] ?? '') ?>"></div>
                                            <div class="col-md-4"><label class="form-label small fw-bold text-muted">NIP (ASN)</label><input type="text" name="profil[nip]" class="form-control shadow-none" value="<?= old('profil.nip', $viewState['profile']['nip'] ?? '') ?>"></div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-muted">Profesi Utama</label>
                                                <select name="profil[profesi_id]" class="form-select shadow-none">
                                                    <option value="">-- Pilih --</option>
                                                    <?php foreach ($master['profesi'] as $prof): ?>
                                                        <option value="<?= esc((string) $prof['id']) ?>" <?= (old('profil.profesi_id', $viewState['profile']['profesi_id'] ?? '') == $prof['id']) ? 'selected' : '' ?>><?= esc($prof['nama']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-muted">Bidang Ilmu</label>
                                                <select name="profil[bidang_ilmu_id]" class="form-select shadow-none">
                                                    <option value="">-- Pilih --</option>
                                                    <?php foreach ($master['bidang_ilmu'] as $bi): ?>
                                                        <option value="<?= esc((string) $bi['id']) ?>" <?= (old('profil.bidang_ilmu_id', $viewState['profile']['bidang_ilmu_id'] ?? '') == $bi['id']) ? 'selected' : '' ?>><?= esc($bi['nama']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-muted">Fakultas</label>
                                                <select name="profil[fakultas_id]" id="fakultasSelect" class="form-select shadow-none cascade-parent" data-cascade-target="#prodiSelect">
                                                    <option value="">-- Pilih --</option>
                                                    <?php foreach ($master['fakultas'] as $fak): ?>
                                                        <option value="<?= esc((string) $fak['id']) ?>" <?= (old('profil.fakultas_id', $viewState['profile']['fakultas_id'] ?? '') == $fak['id']) ? 'selected' : '' ?>><?= esc($fak['nama']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-muted">Program Studi</label>
                                                <select name="profil[program_studi_id]" id="prodiSelect" class="form-select shadow-none cascade-child">
                                                    <option value="">-- Pilih --</option>
                                                    <?php foreach ($master['program_studi'] as $prodi): ?>
                                                        <option value="<?= esc((string) $prodi['id']) ?>" data-parent="<?= esc((string) $prodi['fakultas_id']) ?>" <?= (old('profil.program_studi_id', $viewState['profile']['program_studi_id'] ?? '') == $prodi['id']) ? 'selected' : '' ?>><?= esc($prodi['nama']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="sticky-top" style="top: 2rem;">
                                        <div class="card border-0 bg-light shadow-none text-center p-4 rounded-4">
                                            <div class="mb-4">
                                                <img src="<?= esc((string) $viewState['currentPhotoUrl']) ?>" class="rounded-circle shadow-sm border border-4 border-white" width="120" height="120" style="object-fit: cover;">
                                            </div>
                                            <h6 class="fw-bold text-dark small mb-3">Foto Profil</h6>
                                            <div class="mb-3">
                                                <input type="file" name="foto" id="fotoInput" class="form-control form-control-sm d-none" accept="image/*">
                                                <label for="fotoInput" class="btn btn-primary btn-sm px-3 rounded-pill">
                                                    <i class="bi bi-camera me-1"></i>Ubah Foto
                                                </label>
                                            </div>
                                            <p class="small text-muted mb-0">Format: JPG, PNG. Maksimal 2MB.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light p-4 d-flex justify-content-end gap-2 border-0">
                    <a href="<?= site_url('admin/users') ?>" class="btn btn-link text-muted text-decoration-none fw-bold me-2">Batal</a>
                    <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow-sm" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                            <i class="bi bi-check2-circle"></i>
                            <span><?= $viewState['isEdit'] ? 'Perbarui Akun' : 'Simpan Akun Baru' ?></span>
                        </span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content>
                            <span class="spinner-border spinner-border-sm"></span>
                            <span>Menyimpan...</span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<?= $this->endSection() ?>