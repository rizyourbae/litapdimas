<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>


<div class="row g-3">
    <div class="col-12">
        <div class="card dosen-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">Aktivitas Dosen</span>
                            <span class="badge text-bg-primary px-3 py-2">Form Publikasi</span>
                        </div>
                        <h2 class="h3 dosen-hero__title mb-2"><?= esc($title) ?></h2>
                        <p class="dosen-hero__subtitle mb-0">Input publikasi dipisah per jenis agar data lebih mudah dibaca dan dipelihara.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline shadow-sm dosen-form-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0"><?= esc($title) ?></h3>
                <span class="badge text-bg-light border">Form Publikasi</span>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= esc($formState['action_url']) ?>" method="post" id="form-publikasi" novalidate data-publikasi-form>
                    <?= csrf_field() ?>

                    <div class="row g-3 mb-1">
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Tahun Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" min="1900" max="2100" class="form-control" value="<?= esc($formValues['tahun']) ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-1">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul Publikasi <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" value="<?= esc($formValues['judul']) ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Penulis</label>
                            <input type="text" name="penulis" class="form-control" maxlength="255" value="<?= esc($formValues['penulis']) ?>" placeholder="Contoh: Ahmad, Budi, Citra">
                            <div class="form-text">Pisahkan nama dengan koma jika lebih dari satu penulis.</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Klaster / Skala Publikasi</label>
                            <input type="text" name="klaster" class="form-control" maxlength="100" value="<?= esc($formValues['klaster']) ?>" placeholder="Contoh: Nasional / Internasional">
                        </div>
                    </div>

                    <div class="mb-3 p-3 rounded border bg-light-subtle">
                        <label class="form-label fw-semibold d-block">Jenis Publikasi <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-3">
                            <?php foreach ($jenisOptions as $option): ?>
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input" id="<?= esc($option['id']) ?>" type="radio" name="jenis_publikasi" value="<?= esc($option['value']) ?>" <?= $option['checked_attr'] ?> required data-publikasi-trigger>
                                    <label class="form-check-label" for="<?= esc($option['id']) ?>"><?= esc($option['label']) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div id="dynamic-jurnal" data-publikasi-section="jurnal" class="d-none rounded border p-3 mb-3 bg-light">
                        <h5 class="mb-3">Informasi Jurnal</h5>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Nama Jurnal</label><input type="text" name="nama_jurnal" value="<?= esc($formValues['nama_jurnal']) ?>" class="form-control"></div>
                            <div class="col-md-3"><label class="form-label">Volume</label><input type="text" name="volume" value="<?= esc($formValues['volume']) ?>" class="form-control"></div>
                            <div class="col-md-3"><label class="form-label">Nomor</label><input type="text" name="nomor" value="<?= esc($formValues['nomor']) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">ISSN</label><input type="text" name="issn" value="<?= esc($formValues['issn']) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">URL / DOI</label><input type="url" name="url_jurnal" value="<?= esc($formValues['url_jurnal']) ?>" class="form-control"></div>
                        </div>
                    </div>

                    <div id="dynamic-hki" data-publikasi-section="hki" class="d-none rounded border p-3 mb-3 bg-light">
                        <h5 class="mb-3">Informasi HKI</h5>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Nomor HKI</label><input type="text" name="no_hki" value="<?= esc($formValues['no_hki']) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">URL</label><input type="url" name="url_hki" value="<?= esc($formValues['url_hki']) ?>" class="form-control"></div>
                        </div>
                    </div>

                    <div id="dynamic-prosiding" data-publikasi-section="prosiding" class="d-none rounded border p-3 mb-3 bg-light">
                        <h5 class="mb-3">Informasi Prosiding</h5>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Nama Prosiding</label><input type="text" name="nama_prosiding" value="<?= esc($formValues['nama_prosiding']) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">Penyelenggara</label><input type="text" name="penyelenggara" value="<?= esc($formValues['penyelenggara']) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">ISBN</label><input type="text" name="isbn_prosiding" value="<?= esc($formValues['isbn_prosiding']) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">URL</label><input type="url" name="url_prosiding" value="<?= esc($formValues['url_prosiding']) ?>" class="form-control"></div>
                        </div>
                    </div>

                    <div id="dynamic-buku" data-publikasi-section="buku" class="d-none rounded border p-3 mb-3 bg-light">
                        <h5 class="mb-3">Informasi Buku</h5>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Penerbit</label><input type="text" name="penerbit" value="<?= esc($formValues['penerbit']) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">ISBN</label><input type="text" name="isbn_buku" value="<?= esc($formValues['isbn_buku']) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">Jumlah Halaman</label><input type="number" name="jumlah_halaman" value="<?= esc($formValues['jumlah_halaman']) ?>" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">URL</label><input type="url" name="url_buku" value="<?= esc($formValues['url_buku']) ?>" class="form-control"></div>
                        </div>
                    </div>

                    <div id="container-pembiayaan" class="mb-4 <?= $formState['show_pembiayaan'] ? '' : 'd-none' ?>" data-publikasi-pembiayaan-wrap>
                        <label class="form-label fw-semibold">Sumber Pembiayaan</label>
                        <select name="sumber_pembiayaan" id="sumber_pembiayaan" class="form-select" data-publikasi-pembiayaan-select>
                            <?php foreach ($pembiayaanOptions as $option): ?>
                                <option value="<?= esc($option['value']) ?>" <?= $option['selected_attr'] ?>><?= esc($option['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="sumber_pembiayaan_lainnya" id="input-pembiayaan-lainnya" class="form-control mt-2 <?= $formState['pembiayaan_lainnya_class'] ?>" value="<?= esc($formValues['sumber_pembiayaan_lainnya']) ?>" placeholder="Masukkan sumber pembiayaan lainnya..." <?= $formState['pembiayaan_lainnya_required'] ?> data-publikasi-pembiayaan-other>
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-3">
                        <a href="<?= site_url('dosen/publikasi') ?>" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i><?= esc($formState['submit_label']) ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>