<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
/** @var string $title */
/** @var array<string,mixed> $formState */
/** @var array<int,array<string,mixed>> $dosenOptions */
/** @var array<string,mixed> $formValues */
/** @var array<int,array<string,mixed>> $jenisOptions */
/** @var array<int,array<string,mixed>> $pembiayaanOptions */
?>

<div class="row g-4 admin-page">
    <div class="col-12 mb-2">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) ($hero['title'] ?? $title)),
            'subtitle' => esc((string) ($hero['subtitle'] ?? 'Lengkapi metadata publikasi ilmiah dengan seksama')),
            'badges' => [
                ['label' => 'Admin Workspace', 'class' => 'text-bg-light border'],
                ['label' => 'Form Publikasi', 'class' => 'text-bg-primary shadow-sm']
            ],
            'actions' => [
                [
                    'label' => 'Batal',
                    'class' => 'btn btn-outline-secondary rounded-pill px-4',
                    'icon' => 'bi bi-x-lg',
                    'url' => site_url('admin/publikasi')
                ]
            ]
        ]) ?>
    </div>

    <div class="col-xl-9 mx-auto">
        <form action="<?= esc((string) ($formState['action_url'] ?? '')) ?>" method="post" id="form-publikasi" novalidate data-publikasi-form data-submit-state-form>
            <?= csrf_field() ?>

            <?php if ((string) session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= esc((string) session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-light border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Informasi Utama</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Peneliti / Dosen <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select shadow-none bg-light border-0 py-2 px-3 rounded-3" data-select2 required>
                                <option value="">-- Pilih Dosen --</option>
                                <?php foreach ($dosenOptions as $option): $option = (array) $option; ?>
                                    <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['selected_attr'] ?? '')) ?>>
                                        <?= esc((string) ($option['label'] ?? '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" min="1900" max="2100" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" value="<?= esc((string) ($formValues['tahun'] ?? '')) ?>" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Judul Publikasi <span class="text-danger">*</span></label>
                            <textarea name="judul" rows="2" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" required placeholder="Tuliskan judul lengkap publikasi..."><?= esc((string) ($formValues['judul'] ?? '')) ?></textarea>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Penulis</label>
                            <input type="text" name="penulis" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" maxlength="255" value="<?= esc((string) ($formValues['penulis'] ?? '')) ?>" placeholder="Ahmad, Budi, ...">
                            <div class="form-text small opacity-75 mt-1">Pisahkan nama dengan koma.</div>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Klaster / Skala</label>
                            <input type="text" name="klaster" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3" maxlength="100" value="<?= esc((string) ($formValues['klaster'] ?? '')) ?>" placeholder="Nasional / Internasional...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-light border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="admin-panel-icon-sm bg-primary-soft text-primary">
                            <i class="bi bi-journal-bookmark fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Jenis Publikasi</h6>
                            <p class="text-muted small mb-0 mt-1">Pilih kategori untuk memunculkan detail metadata yang sesuai</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="admin-choice-grid">
                        <?php 
                        $iconMap = [
                            'jurnal' => 'bi-journal-text',
                            'hki' => 'bi-shield-check',
                            'prosiding' => 'bi-journal-album',
                            'buku' => 'bi-book'
                        ];
                        ?>
                        <?php foreach ($jenisOptions as $option): $option = (array) $option; ?>
                            <?php 
                                $originalVal = (string) ($option['value'] ?? '');
                                $val = strtolower($originalVal); 
                            ?>
                            <label class="admin-choice-card">
                                <input class="form-check-input d-none" type="radio" name="jenis_publikasi" value="<?= esc($originalVal) ?>" <?= esc((string) ($option['checked_attr'] ?? '')) ?> required data-publikasi-trigger>
                                <div class="choice-content">
                                    <i class="bi <?= $iconMap[$val] ?? 'bi-journal-bookmark' ?>"></i>
                                    <div class="fw-bold text-dark mb-1"><?= esc((string) ($option['label'] ?? '')) ?></div>
                                    <div class="text-muted small ls-0" style="font-size: 0.7rem;">Metadata Khusus</div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <div id="dynamic-jurnal" class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden d-none border-primary-start" data-publikasi-section="jurnal" style="border-left: 4px solid var(--bs-primary) !important;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-journal-text me-2"></i>Metadata Jurnal</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nama Jurnal</label>
                            <input type="text" name="nama_jurnal" value="<?= esc((string) ($formValues['nama_jurnal'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Volume</label>
                            <input type="text" name="volume" value="<?= esc((string) ($formValues['volume'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Nomor</label>
                            <input type="text" name="nomor" value="<?= esc((string) ($formValues['nomor'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">ISSN</label>
                            <input type="text" name="issn" value="<?= esc((string) ($formValues['issn'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">URL / DOI</label>
                            <input type="url" name="url_jurnal" value="<?= esc((string) ($formValues['url_jurnal'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3" placeholder="https://doi.org/...">
                        </div>
                    </div>
                </div>
            </div>

            <div id="dynamic-hki" class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden d-none border-info-start" data-publikasi-section="hki" style="border-left: 4px solid var(--bs-info) !important;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-info"><i class="bi bi-shield-check me-2"></i>Metadata HKI</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nomor Registrasi HKI</label>
                            <input type="text" name="no_hki" value="<?= esc((string) ($formValues['no_hki'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">URL Sertifikat</label>
                            <input type="url" name="url_hki" value="<?= esc((string) ($formValues['url_hki'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                    </div>
                </div>
            </div>

            <div id="dynamic-prosiding" class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden d-none border-warning-start" data-publikasi-section="prosiding" style="border-left: 4px solid var(--bs-warning) !important;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-warning"><i class="bi bi-journal-album me-2"></i>Metadata Prosiding</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nama Prosiding</label>
                            <input type="text" name="nama_prosiding" value="<?= esc((string) ($formValues['nama_prosiding'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Penyelenggara</label>
                            <input type="text" name="penyelenggara" value="<?= esc((string) ($formValues['penyelenggara'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">ISBN</label>
                            <input type="text" name="isbn_prosiding" value="<?= esc((string) ($formValues['isbn_prosiding'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">URL Publikasi</label>
                            <input type="url" name="url_prosiding" value="<?= esc((string) ($formValues['url_prosiding'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                    </div>
                </div>
            </div>

            <div id="dynamic-buku" class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden d-none border-success-start" data-publikasi-section="buku" style="border-left: 4px solid var(--bs-success) !important;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-success"><i class="bi bi-book me-2"></i>Metadata Buku</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Penerbit</label>
                            <input type="text" name="penerbit" value="<?= esc((string) ($formValues['penerbit'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">ISBN</label>
                            <input type="text" name="isbn_buku" value="<?= esc((string) ($formValues['isbn_buku'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Jumlah Halaman</label>
                            <input type="number" name="jumlah_halaman" value="<?= esc((string) ($formValues['jumlah_halaman'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">URL Katalog / Buku</label>
                            <input type="url" name="url_buku" value="<?= esc((string) ($formValues['url_buku'] ?? '')) ?>" class="form-control shadow-none bg-light border-0 rounded-3">
                        </div>
                    </div>
                </div>
            </div>

            <div id="container-pembiayaan" class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden <?= !empty($formState['show_pembiayaan']) ? '' : 'd-none' ?>" data-publikasi-pembiayaan-wrap>
                <div class="card-header bg-light border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-cash-stack text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Pendanaan</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <label class="form-label small fw-bold text-muted text-uppercase ls-1">Sumber Pembiayaan</label>
                    <select name="sumber_pembiayaan" id="sumber_pembiayaan" class="form-select shadow-none bg-light border-0 py-2 px-3 rounded-3" data-publikasi-pembiayaan-select>
                        <?php foreach ($pembiayaanOptions as $option): $option = (array) $option; ?>
                            <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= esc((string) ($option['selected_attr'] ?? '')) ?>><?= esc((string) ($option['label'] ?? '')) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="sumber_pembiayaan_lainnya" id="input-pembiayaan-lainnya" class="form-control shadow-none bg-light border-0 py-2 px-3 rounded-3 mt-3 <?= esc((string) ($formState['pembiayaan_lainnya_class'] ?? '')) ?>" value="<?= esc((string) ($formValues['sumber_pembiayaan_lainnya'] ?? '')) ?>" placeholder="Sebutkan sumber lainnya..." <?= esc((string) ($formState['pembiayaan_lainnya_required'] ?? '')) ?> data-publikasi-pembiayaan-other>
                </div>
            </div>

            <div class="bg-white p-4 rounded-4 shadow-sm d-flex flex-wrap gap-2 justify-content-end mb-5">
                <a href="<?= site_url('admin/publikasi') ?>" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" data-submit-trigger>
                    <span class="d-inline-flex align-items-center gap-2" data-submit-default-content>
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span><?= esc((string) ($formState['submit_label'] ?? 'Simpan Data')) ?></span>
                    </span>
                    <span class="d-none align-items-center gap-2" data-submit-loading-content>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Menyimpan...</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>


<?= $this->endSection() ?>