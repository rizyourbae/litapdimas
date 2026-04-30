<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
/** @var string $title */
/** @var array<string,mixed> $formState */
/** @var array<int,array<string,mixed>> $dosenOptions */
/** @var array<string,mixed> $formValues */
/** @var array<int,array<string,mixed>> $jenisOptions */
/** @var array<int,array<string,mixed>> $pembiayaanOptions */

$title = (string) ($title ?? '');
$formState = (array) ($formState ?? []);
$dosenOptions = (array) ($dosenOptions ?? []);
$formValues = (array) ($formValues ?? []);
$jenisOptions = (array) ($jenisOptions ?? []);
$pembiayaanOptions = (array) ($pembiayaanOptions ?? []);
?>

<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Admin Workspace</span>
                            <span class="badge text-bg-primary px-3 py-2">Form Publikasi</span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc((string) $title) ?></h2>
                        <p class="admin-hero__subtitle mb-0">Isi metadata publikasi dengan urutan yang lebih jelas agar admin bisa input, meninjau, dan memperbarui data tanpa harus berpindah konteks.</p>
                    </div>
                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= site_url('admin/publikasi') ?>" class="btn btn-outline-secondary">
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
                    <i class="bi bi-journal-richtext me-2"></i><?= esc((string) $title) ?>
                </h3>
            </div>
            <form action="<?= esc((string) ($formState['action_url'] ?? '')) ?>" method="post" id="form-publikasi" novalidate data-publikasi-form data-submit-state-form>
                <?= csrf_field() ?>

                <div class="card-body">
                    <?php if ((string) session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <?= esc((string) session()->getFlashdata('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <section class="admin-section-block">
                        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap mb-3">
                            <div>
                                <h3 class="h5 mb-1">Informasi Utama</h3>
                                <p class="text-muted small mb-0">Tentukan dosen pemilik, judul publikasi, dan konteks dasarnya lebih dulu.</p>
                            </div>
                            <span class="badge text-bg-light border">Tahap 1</span>
                        </div>

                        <div class="row g-3 mb-1">
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">Pilih Dosen <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select" data-select2 required>
                                    <option value="">-- Pilih Dosen --</option>
                                    <?php foreach ($dosenOptions as $option): $option = (array) $option; ?>
                                        <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['selected_attr'] ?? '')) ?>>
                                            <?= esc((string) ($option['label'] ?? '')) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">Tahun Pelaksanaan <span class="text-danger">*</span></label>
                                <input type="number" name="tahun" min="1900" max="2100" class="form-control" value="<?= esc((string) ($formValues['tahun'] ?? '')) ?>" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-1">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul Publikasi <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control" value="<?= esc((string) ($formValues['judul'] ?? '')) ?>" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-0">
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">Penulis</label>
                                <input type="text" name="penulis" class="form-control" maxlength="255" value="<?= esc((string) ($formValues['penulis'] ?? '')) ?>" placeholder="Contoh: Ahmad, Budi, Citra">
                                <div class="form-text">Pisahkan nama dengan koma jika lebih dari satu penulis.</div>
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">Klaster / Skala Publikasi</label>
                                <input type="text" name="klaster" class="form-control" maxlength="100" value="<?= esc((string) ($formValues['klaster'] ?? '')) ?>" placeholder="Contoh: Nasional / Internasional">
                            </div>
                        </div>
                    </section>

                    <section class="admin-section-block">
                        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap mb-3">
                            <div>
                                <h3 class="h5 mb-1">Jenis Publikasi</h3>
                                <p class="text-muted small mb-0">Pilih kategori agar field lanjutan yang relevan otomatis muncul.</p>
                            </div>
                            <span class="badge text-bg-light border">Tahap 2</span>
                        </div>

                        <div class="admin-choice-grid">
                            <?php foreach ($jenisOptions as $option): $option = (array) $option; ?>
                                <label class="admin-choice-card">
                                    <input class="form-check-input" id="<?= esc((string) ($option['id'] ?? '')) ?>" type="radio" name="jenis_publikasi" value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['checked_attr'] ?? '')) ?> required data-publikasi-trigger>
                                    <span>
                                        <strong><?= esc((string) ($option['label'] ?? '')) ?></strong>
                                        <small class="d-block text-muted">Lengkapi metadata khusus setelah jenis dipilih.</small>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <section id="dynamic-jurnal" class="admin-section-block dynamic-section d-none" data-publikasi-section="jurnal">
                        <div class="mb-3">
                            <h3 class="h5 mb-1">Informasi Jurnal</h3>
                            <p class="text-muted small mb-0">Isi identitas jurnal dan tautan publikasi jika tersedia.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Nama Jurnal</label><input type="text" name="nama_jurnal" value="<?= esc((string) ($formValues['nama_jurnal'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-3"><label class="form-label">Volume</label><input type="text" name="volume" value="<?= esc((string) ($formValues['volume'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-3"><label class="form-label">Nomor</label><input type="text" name="nomor" value="<?= esc((string) ($formValues['nomor'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">ISSN</label><input type="text" name="issn" value="<?= esc((string) ($formValues['issn'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">URL / DOI</label><input type="url" name="url_jurnal" value="<?= esc((string) ($formValues['url_jurnal'] ?? '')) ?>" class="form-control"></div>
                        </div>
                    </section>

                    <section id="dynamic-hki" class="admin-section-block dynamic-section d-none" data-publikasi-section="hki">
                        <div class="mb-3">
                            <h3 class="h5 mb-1">Informasi HKI</h3>
                            <p class="text-muted small mb-0">Gunakan nomor registrasi dan tautan pendukung bila tersedia.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Nomor HKI</label><input type="text" name="no_hki" value="<?= esc((string) ($formValues['no_hki'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">URL</label><input type="url" name="url_hki" value="<?= esc((string) ($formValues['url_hki'] ?? '')) ?>" class="form-control"></div>
                        </div>
                    </section>

                    <section id="dynamic-prosiding" class="admin-section-block dynamic-section d-none" data-publikasi-section="prosiding">
                        <div class="mb-3">
                            <h3 class="h5 mb-1">Informasi Prosiding</h3>
                            <p class="text-muted small mb-0">Lengkapi nama kegiatan, penyelenggara, ISBN, dan tautan publikasi.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Nama Prosiding</label><input type="text" name="nama_prosiding" value="<?= esc((string) ($formValues['nama_prosiding'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">Penyelenggara</label><input type="text" name="penyelenggara" value="<?= esc((string) ($formValues['penyelenggara'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">ISBN</label><input type="text" name="isbn_prosiding" value="<?= esc((string) ($formValues['isbn_prosiding'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">URL</label><input type="url" name="url_prosiding" value="<?= esc((string) ($formValues['url_prosiding'] ?? '')) ?>" class="form-control"></div>
                        </div>
                    </section>

                    <section id="dynamic-buku" class="admin-section-block dynamic-section d-none" data-publikasi-section="buku">
                        <div class="mb-3">
                            <h3 class="h5 mb-1">Informasi Buku</h3>
                            <p class="text-muted small mb-0">Isi detail penerbitan buku dan jumlah halaman untuk dokumentasi yang lengkap.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Penerbit</label><input type="text" name="penerbit" value="<?= esc((string) ($formValues['penerbit'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">ISBN</label><input type="text" name="isbn_buku" value="<?= esc((string) ($formValues['isbn_buku'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">Jumlah Halaman</label><input type="number" name="jumlah_halaman" value="<?= esc((string) ($formValues['jumlah_halaman'] ?? '')) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">URL</label><input type="url" name="url_buku" value="<?= esc((string) ($formValues['url_buku'] ?? '')) ?>" class="form-control"></div>
                        </div>
                    </section>

                    <section id="container-pembiayaan" class="admin-section-block mb-0 <?= !empty($formState['show_pembiayaan']) ? '' : 'd-none' ?>" data-publikasi-pembiayaan-wrap>
                        <div class="mb-3">
                            <h3 class="h5 mb-1">Pendanaan Publikasi</h3>
                            <p class="text-muted small mb-0">Tentukan sumber pembiayaan agar riwayat pendanaan tetap terbaca konsisten.</p>
                        </div>
                        <label class="form-label fw-semibold">Sumber Pembiayaan</label>
                        <select name="sumber_pembiayaan" id="sumber_pembiayaan" class="form-select" data-publikasi-pembiayaan-select>
                            <?php foreach ($pembiayaanOptions as $option): $option = (array) $option; ?>
                                <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['selected_attr'] ?? '')) ?>><?= esc((string) ($option['label'] ?? '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="sumber_pembiayaan_lainnya" id="input-pembiayaan-lainnya" class="form-control mt-2 <?= esc((string) ($formState['pembiayaan_lainnya_class'] ?? '')) ?>" value="<?= esc((string) ($formValues['sumber_pembiayaan_lainnya'] ?? '')) ?>" placeholder="Masukkan sumber pembiayaan lainnya..." <?= esc((string) ($formState['pembiayaan_lainnya_required'] ?? '')) ?> data-publikasi-pembiayaan-other>
                    </section>
                </div>

                <div class="card-footer admin-form-footer d-flex flex-wrap gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary" data-submit-trigger>
                        <span class="d-inline-flex align-items-center gap-2" data-submit-default-content><i class="bi bi-save"></i><span><?= esc((string) ($formState['submit_label'] ?? '')) ?></span></span>
                        <span class="d-none align-items-center gap-2" data-submit-loading-content><span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Menyimpan...</span></span>
                    </button>
                    <a href="<?= site_url('admin/publikasi') ?>" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>