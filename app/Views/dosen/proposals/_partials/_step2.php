<?php

/**
 * dosen/proposals/_partials/_step2.php
 * Step 2: Data Peneliti (with repeatable rows)
 */

$stepData = !empty($proposal['step_2_data']) ? json_decode($proposal['step_2_data'], true) : [];
$penelitiInternal = $stepData['peneliti_internal'] ?? [['nama' => '', 'nip' => '', 'email' => '', 'asal_instansi' => '', 'posisi' => 'Ketua']];
$mahasiswa = $stepData['mahasiswa'] ?? [];
$eksternal = $stepData['anggota_eksternal'] ?? [];
?>

<div class="card border-0 bg-light mb-4" style="border-radius:0.8rem;">
    <div class="card-body py-3">
        <h6 class="mb-1"><i class="fas fa-users text-primary me-1"></i> Komposisi Tim Peneliti</h6>
        <p class="text-muted small mb-0">Isi data peneliti internal terlebih dahulu. Data mahasiswa dan anggota eksternal bersifat opsional.</p>
    </div>
</div>

<div class="card mb-4" style="border-radius:0.8rem;">
    <div class="card-header d-flex align-items-center gap-2">
        <h6 class="mb-0">Peneliti Internal <span class="text-danger">*</span></h6>
        <button type="button" class="btn btn-sm btn-success ms-auto" data-repeatable-action="add-peneliti">
            <i class="fas fa-plus me-1"></i> Tambah Peneliti
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle" id="penelitiTable">
                <thead class="table-light">
                    <tr>
                        <th>Nama <span class="text-danger">*</span></th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>Asal Instansi</th>
                        <th>Posisi</th>
                        <th style="width:70px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="penelitiBody">
                    <?php foreach ($penelitiInternal as $index => $p): ?>
                        <tr class="peneliti-row" data-index="<?= $index ?>">
                            <td><input type="text" class="form-control form-control-sm" name="peneliti_internal[<?= $index ?>][nama]" value="<?= esc($p['nama'] ?? '') ?>" placeholder="Nama lengkap" required></td>
                            <td><input type="text" class="form-control form-control-sm" name="peneliti_internal[<?= $index ?>][nip]" value="<?= esc($p['nip'] ?? '') ?>" placeholder="123456789"></td>
                            <td><input type="email" class="form-control form-control-sm" name="peneliti_internal[<?= $index ?>][email]" value="<?= esc($p['email'] ?? '') ?>" placeholder="email@domain.ac.id"></td>
                            <td><input type="text" class="form-control form-control-sm" name="peneliti_internal[<?= $index ?>][asal_instansi]" value="<?= esc($p['asal_instansi'] ?? '') ?>" placeholder="Institusi"></td>
                            <td><input type="text" class="form-control form-control-sm" name="peneliti_internal[<?= $index ?>][posisi]" value="<?= esc($p['posisi'] ?? '') ?>" placeholder="Ketua/Anggota"></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-repeatable-action="remove-peneliti" title="Hapus baris">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-4" style="border-radius:0.8rem;">
    <div class="card-header d-flex align-items-center gap-2">
        <h6 class="mb-0">Mahasiswa Terlibat <span class="text-muted small">(Opsional)</span></h6>
        <button type="button" class="btn btn-sm btn-success ms-auto" data-repeatable-action="add-mahasiswa">
            <i class="fas fa-plus me-1"></i> Tambah Mahasiswa
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle" id="mahasiswaTable">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Program Studi</th>
                        <th>Email</th>
                        <th style="width:70px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="mahasiswaBody">
                    <?php foreach ($mahasiswa as $index => $m): ?>
                        <tr class="mahasiswa-row" data-index="<?= $index ?>">
                            <td><input type="text" class="form-control form-control-sm" name="mahasiswa[<?= $index ?>][nama]" value="<?= esc($m['nama'] ?? '') ?>" placeholder="Nama mahasiswa"></td>
                            <td><input type="text" class="form-control form-control-sm" name="mahasiswa[<?= $index ?>][nim]" value="<?= esc($m['nim'] ?? '') ?>" placeholder="NIM"></td>
                            <td><input type="text" class="form-control form-control-sm" name="mahasiswa[<?= $index ?>][program_studi_id]" value="<?= esc($m['program_studi_id'] ?? '') ?>" placeholder="Program studi"></td>
                            <td><input type="email" class="form-control form-control-sm" name="mahasiswa[<?= $index ?>][email]" value="<?= esc($m['email'] ?? '') ?>" placeholder="email@domain"></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-repeatable-action="remove-mahasiswa" title="Hapus baris">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card" style="border-radius:0.8rem;">
    <div class="card-header d-flex align-items-center gap-2">
        <h6 class="mb-0">Anggota Eksternal <span class="text-muted small">(Opsional)</span></h6>
        <button type="button" class="btn btn-sm btn-success ms-auto" data-repeatable-action="add-eksternal">
            <i class="fas fa-plus me-1"></i> Tambah Anggota Eksternal
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle" id="eksternalTable">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Institusi</th>
                        <th>Posisi</th>
                        <th>Email</th>
                        <th>Tipe</th>
                        <th style="width:70px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="eksternalBody">
                    <?php foreach ($eksternal as $index => $e): ?>
                        <tr class="eksternal-row" data-index="<?= $index ?>">
                            <td><input type="text" class="form-control form-control-sm" name="anggota_eksternal[<?= $index ?>][nama]" value="<?= esc($e['nama'] ?? '') ?>" placeholder="Nama"></td>
                            <td><input type="text" class="form-control form-control-sm" name="anggota_eksternal[<?= $index ?>][institusi]" value="<?= esc($e['institusi'] ?? '') ?>" placeholder="Institusi"></td>
                            <td><input type="text" class="form-control form-control-sm" name="anggota_eksternal[<?= $index ?>][posisi]" value="<?= esc($e['posisi'] ?? '') ?>" placeholder="Posisi"></td>
                            <td><input type="email" class="form-control form-control-sm" name="anggota_eksternal[<?= $index ?>][email]" value="<?= esc($e['email'] ?? '') ?>" placeholder="email@domain"></td>
                            <td>
                                <select class="form-select form-select-sm" name="anggota_eksternal[<?= $index ?>][tipe]">
                                    <option value="Profesional" <?= ($e['tipe'] ?? 'Profesional') === 'Profesional' ? 'selected' : '' ?>>Profesional</option>
                                    <option value="PTU" <?= ($e['tipe'] ?? '') === 'PTU' ? 'selected' : '' ?>>PTU</option>
                                    <option value="Lainnya" <?= ($e['tipe'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-repeatable-action="remove-eksternal" title="Hapus baris">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= base_url('custom/js/proposal-repeatable.js') ?>?v=20260427-02"></script>