<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$errors          = session()->getFlashdata('errors') ?? [];
$profil          = $user['profil'] ?? [];
$currentPhotoUrl = !empty($profil['foto'])
    ? base_url('uploads/' . ltrim((string) $profil['foto'], '/'))
    : base_url('assets/adminlte/assets/img/user2-160x160.jpg');
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-person-circle me-2"></i><?= esc($title) ?>
        </h3>
    </div>

    <form action="<?= site_url('profile/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

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

            <!-- Tabs -->
            <ul class="nav nav-tabs nav-fill" id="profileTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="akun-tab" data-bs-toggle="tab"
                        data-bs-target="#akun" type="button" role="tab">
                        <i class="bi bi-key me-2"></i>Data Akun
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profil-tab" data-bs-toggle="tab"
                        data-bs-target="#profil" type="button" role="tab">
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
                            <input type="text" name="username"
                                class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>"
                                value="<?= old('username', $user['username'] ?? '') ?>"
                                placeholder="Minimal 6 karakter" required>
                            <?php if (!empty($errors['username'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['username']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email"
                                class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                                value="<?= old('email', $user['email'] ?? '') ?>"
                                placeholder="email@example.com" required>
                            <?php if (!empty($errors['email'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_lengkap"
                            class="form-control <?= !empty($errors['nama_lengkap']) ? 'is-invalid' : '' ?>"
                            value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>"
                            placeholder="Nama lengkap sesuai KTP" required>
                        <?php if (!empty($errors['nama_lengkap'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['nama_lengkap']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ganti Password</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Kosongkan jika tidak ingin mengubah password">
                        <?php if (!empty($errors['password'])): ?>
                            <div class="text-danger small mt-1"><?= esc($errors['password']) ?></div>
                        <?php endif; ?>
                        <small class="text-muted">Isi hanya jika ingin mengganti password. Minimal 6 karakter.</small>
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
                                    value="<?= old('profil.gelar_depan', $profil['gelar_depan'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gelar Belakang</label>
                                <input type="text" name="profil[gelar_belakang]" class="form-control"
                                    placeholder="cth. M.Kom., S.T., M.Sc."
                                    value="<?= old('profil.gelar_belakang', $profil['gelar_belakang'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="profil[jenis_kelamin]" class="form-select" data-select2>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" <?= (old('profil.jenis_kelamin', $profil['jenis_kelamin'] ?? '') === 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="P" <?= (old('profil.jenis_kelamin', $profil['jenis_kelamin'] ?? '') === 'P') ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="profil[tempat_lahir]" class="form-control"
                                    placeholder="cth. Jakarta"
                                    value="<?= old('profil.tempat_lahir', $profil['tempat_lahir'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <div class="input-group">
                                    <input type="text" name="profil[tanggal_lahir]" class="form-control datepicker"
                                        data-locale="id" data-date-format="Y-m-d" data-alt-format="d F Y"
                                        data-max-date="today" placeholder="dd/mm/yyyy" autocomplete="off"
                                        value="<?= old('profil.tanggal_lahir', $profil['tanggal_lahir'] ?? '') ?>">
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
                                    value="<?= old('profil.no_hp', $profil['no_hp'] ?? '') ?>">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="profil[alamat]" class="form-control" rows="2"
                                    placeholder="Jalan, Nomor, RT/RW, Kelurahan, dst..."><?= old('profil.alamat', $profil['alamat'] ?? '') ?></textarea>
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
                                    value="<?= old('profil.nik', $profil['nik'] ?? '') ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">NIDN</label>
                                <input type="text" name="profil[nidn]" class="form-control"
                                    placeholder="NIDN dosen"
                                    value="<?= old('profil.nidn', $profil['nidn'] ?? '') ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">NIP (ASN)</label>
                                <input type="text" name="profil[nip]" class="form-control"
                                    placeholder="NIP ASN"
                                    value="<?= old('profil.nip', $profil['nip'] ?? '') ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">ID SINTA</label>
                                <input type="text" name="profil[id_sinta]" class="form-control"
                                    placeholder="ID SINTA"
                                    value="<?= old('profil.id_sinta', $profil['id_sinta'] ?? '') ?>">
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
                                        <option value="<?= $prof['id'] ?>"
                                            <?= (old('profil.profesi_id', $profil['profesi_id'] ?? '') == $prof['id']) ? 'selected' : '' ?>>
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
                                        <option value="<?= $bi['id'] ?>"
                                            <?= (old('profil.bidang_ilmu_id', $profil['bidang_ilmu_id'] ?? '') == $bi['id']) ? 'selected' : '' ?>>
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
                                    data-cascade-target="#prodiSelect" data-select2>
                                    <option value="">-- Pilih Fakultas --</option>
                                    <?php foreach ($master['fakultas'] as $fak): ?>
                                        <option value="<?= $fak['id'] ?>"
                                            <?= (old('profil.fakultas_id', $profil['fakultas_id'] ?? '') == $fak['id']) ? 'selected' : '' ?>>
                                            <?= esc($fak['nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Program Studi</label>
                                <select name="profil[program_studi_id]" id="prodiSelect"
                                    class="form-select cascade-child" data-select2>
                                    <option value="">-- Pilih Program Studi --</option>
                                    <?php foreach ($master['program_studi'] as $prodi): ?>
                                        <option value="<?= $prodi['id'] ?>"
                                            data-parent="<?= $prodi['fakultas_id'] ?>"
                                            <?= (old('profil.program_studi_id', $profil['program_studi_id'] ?? '') == $prodi['id']) ? 'selected' : '' ?>>
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
                                        <option value="<?= $jab['id'] ?>"
                                            <?= (old('profil.jabatan_fungsional_id', $profil['jabatan_fungsional_id'] ?? '') == $jab['id']) ? 'selected' : '' ?>>
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
                        <div class="row">
                            <div class="col-md-3 mb-3 text-center">
                                <img id="fotoPreview" src="<?= esc($currentPhotoUrl) ?>"
                                    class="rounded border" width="140" height="140"
                                    style="object-fit:cover;" alt="Foto Profil">
                                <div class="small text-muted mt-2">Preview foto</div>
                            </div>
                            <div class="col-md-9 mb-3">
                                <label class="form-label">Pilih Foto</label>
                                <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP. Maks. 2MB.</small>
                                <?php if (!empty($profil['foto'])): ?>
                                    <small class="text-success d-block mt-2">
                                        Foto tersimpan: <?= esc(basename((string) $profil['foto'])) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div><!-- /tab-content -->
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Simpan Perubahan
            </button>
            <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function() {
        'use strict';

        function setupCascade() {
            document.querySelectorAll('.cascade-parent').forEach(function(parent) {
                parent.addEventListener('change', function() {
                    const parentValue = this.value;
                    const targetId = this.getAttribute('data-cascade-target');
                    const childSelect = document.querySelector(targetId);

                    if (!childSelect) return;

                    childSelect.querySelectorAll('option').forEach(function(opt) {
                        if (opt.value === '') {
                            opt.style.display = '';
                        } else if (opt.getAttribute('data-parent') === parentValue) {
                            opt.style.display = '';
                        } else {
                            opt.style.display = 'none';
                        }
                    });

                    // Reset child jika pilihan yang aktif sekarang tersembunyi
                    const selected = childSelect.options[childSelect.selectedIndex];
                    if (selected && selected.value !== '' && selected.style.display === 'none') {
                        childSelect.value = '';
                    }

                    if (window.Select2Init && typeof window.Select2Init.sync === 'function') {
                        window.Select2Init.sync(childSelect);
                    }
                });

                // Trigger filter awal saat halaman load
                parent.dispatchEvent(new Event('change'));
            });
        }

        function setupPhotoPreview() {
            const input = document.getElementById('fotoInput');
            const preview = document.getElementById('fotoPreview');
            if (!input || !preview) return;

            input.addEventListener('change', function() {
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target && e.target.result ? e.target.result : preview.src;
                };
                reader.readAsDataURL(file);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setupCascade();
                setupPhotoPreview();
            });
        } else {
            setupCascade();
            setupPhotoPreview();
        }
    })();
</script>
<?= $this->endSection() ?>
