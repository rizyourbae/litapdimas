<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$errors = session()->getFlashdata('errors') ?? [];
$BASE_URL = site_url('admin/users');
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-person-check me-2"></i><?= $title ?>
        </h3>
    </div>
    <form action="<?= esc($action ?? site_url('admin/users/store')) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php if (isset($user)): ?>
            <input type="hidden" name="uuid" value="<?= esc($user['uuid']) ?>">
        <?php endif; ?>
        <div class="card-body">

            <!-- Error Alert -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <strong>Ada kesalahan:</strong>
                    <ul class="mb-0 ps-3 mt-1">
                        <?php foreach ((array)$errors as $e): ?>
                            <li><?= esc($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <ul class="nav nav-tabs nav-fill" id="userTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="akun-tab" data-bs-toggle="tab" data-bs-target="#akun" type="button" role="tab">
                        <i class="bi bi-key me-2"></i>Data Akun
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil" type="button" role="tab">
                        <i class="bi bi-person me-2"></i>Data Profil
                    </button>
                </li>
            </ul>
            <div class="tab-content mt-3">

                <!-- ==================== TAB: AKUN ==================== -->
                <div class="tab-pane fade show active" id="akun" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                Username <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="username" class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>"
                                value="<?= old('username', $user['username'] ?? '') ?>"
                                placeholder="Minimal 6 karakter"
                                required>
                            <?php if (!empty($errors['username'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['username']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                                value="<?= old('email', $user['email'] ?? '') ?>"
                                placeholder="email@example.com"
                                required>
                            <?php if (!empty($errors['email'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" class="form-control <?= !empty($errors['nama_lengkap']) ? 'is-invalid' : '' ?>"
                            value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>"
                            placeholder="Nama lengkap sesuai KTP"
                            required>
                        <?php if (!empty($errors['nama_lengkap'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['nama_lengkap']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Password <?php if (!isset($user)): ?><span class="text-danger">*</span><?php endif; ?>
                        </label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Masukkan password"
                            <?php if (!isset($user)): ?>required<?php endif; ?>>
                        <?php if (isset($user)): ?>
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        <?php else: ?>
                            <small class="text-muted">Minimal 6 karakter untuk user baru.</small>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status Aktif</label>
                            <select name="aktif" class="form-select" data-select2>
                                <option value="1" <?= (old('aktif', $user['aktif'] ?? 1) == 1) ? 'selected' : '' ?>>
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </option>
                                <option value="0" <?= (old('aktif', $user['aktif'] ?? 1) == 0) ? 'selected' : '' ?>>
                                    <i class="bi bi-dash-circle me-1"></i>Nonaktif
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                        <div class="row">
                            <?php foreach ($roles as $role): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="role-<?= $role['id'] ?>"
                                            name="roles[]" value="<?= $role['id'] ?>"
                                            <?= in_array($role['id'], old('roles', $user['roles'] ?? [])) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="role-<?= $role['id'] ?>">
                                            <i class="bi bi-shield-check me-1"></i><?= esc($role['name']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- ==================== TAB: PROFIL ==================== -->
                <div class="tab-pane fade" id="profil" role="tabpanel">

                    <!-- Bagian: Data Pribadi -->
                    <div class="mb-4">
                        <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
                            <i class="bi bi-person-fill me-2"></i>Data Pribadi
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gelar Depan</label>
                                <input type="text" name="profil[gelar_depan]" class="form-control"
                                    placeholder="cth. Dr., Prof., Ir."
                                    value="<?= old('profil.gelar_depan', $user['profil']['gelar_depan'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gelar Belakang</label>
                                <input type="text" name="profil[gelar_belakang]" class="form-control"
                                    placeholder="cth. M.Kom., S.T., M.Sc."
                                    value="<?= old('profil.gelar_belakang', $user['profil']['gelar_belakang'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="profil[jenis_kelamin]" class="form-select" data-select2>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" <?= (old('profil.jenis_kelamin', $user['profil']['jenis_kelamin'] ?? '') == 'L') ? 'selected' : '' ?>>
                                        <i class="bi bi-person me-1"></i>Laki-laki
                                    </option>
                                    <option value="P" <?= (old('profil.jenis_kelamin', $user['profil']['jenis_kelamin'] ?? '') == 'P') ? 'selected' : '' ?>>
                                        <i class="bi bi-person me-1"></i>Perempuan
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="profil[tempat_lahir]" class="form-control"
                                    placeholder="cth. Jakarta"
                                    value="<?= old('profil.tempat_lahir', $user['profil']['tempat_lahir'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <div class="input-group">
                                    <input type="text" name="profil[tanggal_lahir]" class="form-control datepicker"
                                        data-locale="id" data-date-format="Y-m-d" data-alt-format="d F Y"
                                        data-max-date="today" placeholder="dd/mm/yyyy" autocomplete="off"
                                        value="<?= old('profil.tanggal_lahir', $user['profil']['tanggal_lahir'] ?? '') ?>">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian: Kontak -->
                    <div class="mb-4">
                        <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
                            <i class="bi bi-telephone-fill me-2"></i>Kontak
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">No HP</label>
                                <input type="text" name="profil[no_hp]" class="form-control"
                                    placeholder="08123456789"
                                    value="<?= old('profil.no_hp', $user['profil']['no_hp'] ?? '') ?>">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="profil[alamat]" class="form-control" rows="2"
                                    placeholder="Jalan, Nomor, RT/RW, Kelurahan, dst..."><?= old('profil.alamat', $user['profil']['alamat'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian: Identitas -->
                    <div class="mb-4">
                        <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
                            <i class="bi bi-card-text me-2"></i>Identitas
                        </h6>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">NIK</label>
                                <input type="text" name="profil[nik]" class="form-control"
                                    placeholder="16 digit NIK"
                                    value="<?= old('profil.nik', $user['profil']['nik'] ?? '') ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">NIDN (Dosen)</label>
                                <input type="text" name="profil[nidn]" class="form-control"
                                    placeholder="NIDN dosen"
                                    value="<?= old('profil.nidn', $user['profil']['nidn'] ?? '') ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">NIP (ASN)</label>
                                <input type="text" name="profil[nip]" class="form-control"
                                    placeholder="NIP ASN"
                                    value="<?= old('profil.nip', $user['profil']['nip'] ?? '') ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">ID SINTA</label>
                                <input type="text" name="profil[id_sinta]" class="form-control"
                                    placeholder="ID SINTA"
                                    value="<?= old('profil.id_sinta', $user['profil']['id_sinta'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Bagian: Akademik -->
                    <div class="mb-4">
                        <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
                            <i class="bi bi-mortarboard-fill me-2"></i>Akademik
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profesi</label>
                                <select name="profil[profesi_id]" class="form-select" data-select2>
                                    <option value="">-- Pilih Profesi --</option>
                                    <?php foreach ($master['profesi'] as $prof): ?>
                                        <option value="<?= $prof['id'] ?>" <?= (old('profil.profesi_id', $user['profil']['profesi_id'] ?? '') == $prof['id']) ? 'selected' : '' ?>>
                                            <?= esc($prof['nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bidang Ilmu</label>
                                <select name="profil[bidang_ilmu_id]" class="form-select" data-select2>
                                    <option value="">-- Pilih Bidang Ilmu --</option>
                                    <?php foreach ($master['bidang_ilmu'] as $bi): ?>
                                        <option value="<?= $bi['id'] ?>" <?= (old('profil.bidang_ilmu_id', $user['profil']['bidang_ilmu_id'] ?? '') == $bi['id']) ? 'selected' : '' ?>>
                                            <?= esc($bi['nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fakultas</label>
                                <select name="profil[fakultas_id]" id="fakultasSelect" class="form-select cascade-parent"
                                    data-cascade-target="#prodiSelect" data-select2>
                                    <option value="">-- Pilih Fakultas --</option>
                                    <?php foreach ($master['fakultas'] as $fak): ?>
                                        <option value="<?= $fak['id'] ?>" <?= (old('profil.fakultas_id', $user['profil']['fakultas_id'] ?? '') == $fak['id']) ? 'selected' : '' ?>>
                                            <?= esc($fak['nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Program Studi</label>
                                <select name="profil[program_studi_id]" id="prodiSelect" class="form-select cascade-child" data-select2>
                                    <option value="">-- Pilih Program Studi --</option>
                                    <?php foreach ($master['program_studi'] as $prodi): ?>
                                        <option value="<?= $prodi['id'] ?>" data-parent="<?= $prodi['fakultas_id'] ?>"
                                            <?= (old('profil.program_studi_id', $user['profil']['program_studi_id'] ?? '') == $prodi['id']) ? 'selected' : '' ?>>
                                            <?= esc($prodi['nama']) ?>
                                        </option>
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
                                        <option value="<?= $jab['id'] ?>" <?= (old('profil.jabatan_fungsional_id', $user['profil']['jabatan_fungsional_id'] ?? '') == $jab['id']) ? 'selected' : '' ?>>
                                            <?= esc($jab['nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian: Foto Profil -->
                    <div class="mb-4">
                        <h6 class="text-muted fw-semibold border-bottom pb-2 mb-3">
                            <i class="bi bi-image me-2"></i>Foto Profil
                        </h6>
                        <div class="row align-items-end">
                            <?php if (!empty($user['profil']['foto'])): ?>
                                <div class="col-auto mb-3">
                                    <img src="<?= base_url('uploads/' . esc($user['profil']['foto'])) ?>"
                                        class="rounded border" width="100" height="100" style="object-fit:cover;" alt="Foto Profil">
                                </div>
                            <?php endif; ?>
                            <div class="col mb-3">
                                <label class="form-label">Pilih Foto</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <small class="text-muted d-block mt-1">Format: JPG, PNG. Maks. 2MB.</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Simpan
            </button>
            <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">
                <i class="bi bi-x-lg me-1"></i>Batal
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function() {
        'use strict';

        // Wait for jQuery & Select2 ready
        function setupCascade() {
            console.log('[form.php] Setting up cascade filtering');

            document.querySelectorAll('.cascade-parent').forEach(function(parent) {
                parent.addEventListener('change', function() {
                    const parentValue = this.value;
                    const targetId = this.getAttribute('data-cascade-target');
                    const childSelect = document.querySelector(targetId);

                    if (!childSelect) {
                        console.warn('[form.php] Cascade child not found:', targetId);
                        return;
                    }

                    console.log('[form.php] Cascade parent changed:', parentValue, '-> filtering', targetId);

                    const options = childSelect.querySelectorAll('option');
                    options.forEach(function(opt) {
                        // Empty option selalu tampil
                        if (opt.value === '') {
                            opt.style.display = '';
                        }
                        // Option yang cocok dengan parent value tampil
                        else if (opt.getAttribute('data-parent') === parentValue) {
                            opt.style.display = '';
                        }
                        // Opsi lain disembunyikan
                        else {
                            opt.style.display = 'none';
                        }
                    });

                    // Reset nilai child jika selected option sekarang disembunyikan
                    const selectedOption = childSelect.options[childSelect.selectedIndex];
                    if (selectedOption && selectedOption.value !== '' && selectedOption.style.display === 'none') {
                        childSelect.value = '';
                    }

                    if (window.Select2Init && typeof window.Select2Init.sync === 'function') {
                        window.Select2Init.sync(childSelect);
                    }
                });

                // Trigger initial filter on page load
                parent.dispatchEvent(new Event('change'));
            });

            console.log('[form.php] Cascade filtering setup complete');
        }

        // Setup cascade ketika DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupCascade);
        } else {
            setupCascade();
        }

    })();
</script>
<?= $this->endSection() ?>