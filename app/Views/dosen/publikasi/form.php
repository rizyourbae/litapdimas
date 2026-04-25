<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card card-primary card-outline shadow-sm">
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

        <form action="<?= esc($formState['action_url']) ?>" method="post" id="form-publikasi" novalidate>
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
                            <input class="form-check-input trigger-jenis" id="<?= esc($option['id']) ?>" type="radio" name="jenis_publikasi" value="<?= esc($option['value']) ?>" <?= $option['checked_attr'] ?> required>
                            <label class="form-check-label" for="<?= esc($option['id']) ?>"><?= esc($option['label']) ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="dynamic-jurnal" class="dynamic-section d-none rounded border p-3 mb-3 bg-light">
                <h5 class="mb-3">Informasi Jurnal</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Nama Jurnal</label><input type="text" name="nama_jurnal" value="<?= esc($formValues['nama_jurnal']) ?>" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label">Volume</label><input type="text" name="volume" value="<?= esc($formValues['volume']) ?>" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label">Nomor</label><input type="text" name="nomor" value="<?= esc($formValues['nomor']) ?>" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">ISSN</label><input type="text" name="issn" value="<?= esc($formValues['issn']) ?>" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">URL / DOI</label><input type="url" name="url_jurnal" value="<?= esc($formValues['url_jurnal']) ?>" class="form-control"></div>
                </div>
            </div>

            <div id="dynamic-hki" class="dynamic-section d-none rounded border p-3 mb-3 bg-light">
                <h5 class="mb-3">Informasi HKI</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Nomor HKI</label><input type="text" name="no_hki" value="<?= esc($formValues['no_hki']) ?>" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">URL</label><input type="url" name="url_hki" value="<?= esc($formValues['url_hki']) ?>" class="form-control"></div>
                </div>
            </div>

            <div id="dynamic-prosiding" class="dynamic-section d-none rounded border p-3 mb-3 bg-light">
                <h5 class="mb-3">Informasi Prosiding</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Nama Prosiding</label><input type="text" name="nama_prosiding" value="<?= esc($formValues['nama_prosiding']) ?>" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Penyelenggara</label><input type="text" name="penyelenggara" value="<?= esc($formValues['penyelenggara']) ?>" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">ISBN</label><input type="text" name="isbn_prosiding" value="<?= esc($formValues['isbn_prosiding']) ?>" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">URL</label><input type="url" name="url_prosiding" value="<?= esc($formValues['url_prosiding']) ?>" class="form-control"></div>
                </div>
            </div>

            <div id="dynamic-buku" class="dynamic-section d-none rounded border p-3 mb-3 bg-light">
                <h5 class="mb-3">Informasi Buku</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Penerbit</label><input type="text" name="penerbit" value="<?= esc($formValues['penerbit']) ?>" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">ISBN</label><input type="text" name="isbn_buku" value="<?= esc($formValues['isbn_buku']) ?>" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Jumlah Halaman</label><input type="number" name="jumlah_halaman" value="<?= esc($formValues['jumlah_halaman']) ?>" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">URL</label><input type="url" name="url_buku" value="<?= esc($formValues['url_buku']) ?>" class="form-control"></div>
                </div>
            </div>

            <div id="container-pembiayaan" class="mb-4 <?= $formState['show_pembiayaan'] ? '' : 'd-none' ?>">
                <label class="form-label fw-semibold">Sumber Pembiayaan</label>
                <select name="sumber_pembiayaan" id="sumber_pembiayaan" class="form-select">
                    <?php foreach ($pembiayaanOptions as $option): ?>
                        <option value="<?= esc($option['value']) ?>" <?= $option['selected_attr'] ?>><?= esc($option['label']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="sumber_pembiayaan_lainnya" id="input-pembiayaan-lainnya" class="form-control mt-2 <?= $formState['pembiayaan_lainnya_class'] ?>" value="<?= esc($formValues['sumber_pembiayaan_lainnya']) ?>" placeholder="Masukkan sumber pembiayaan lainnya..." <?= $formState['pembiayaan_lainnya_required'] ?>>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioJenis = document.querySelectorAll('.trigger-jenis');
        const sections = document.querySelectorAll('.dynamic-section');
        const containerPembiayaan = document.getElementById('container-pembiayaan');
        const selectPembiayaan = document.getElementById('sumber_pembiayaan');
        const inputLainnya = document.getElementById('input-pembiayaan-lainnya');
        const selectedJenis = <?= json_encode($formState['selected_jenis']) ?>;

        function toggleFormSections(radioValue) {
            if (!radioValue) return;

            sections.forEach(sec => sec.classList.add('d-none'));
            const targetSection = document.getElementById('dynamic-' + radioValue.toLowerCase());
            if (targetSection) targetSection.classList.remove('d-none');

            if (!containerPembiayaan || !selectPembiayaan) return;

            if (radioValue === 'Buku') {
                containerPembiayaan.classList.add('d-none');
                selectPembiayaan.removeAttribute('required');
                if (inputLainnya) {
                    inputLainnya.classList.add('d-none');
                    inputLainnya.removeAttribute('required');
                }
            } else {
                containerPembiayaan.classList.remove('d-none');
                selectPembiayaan.setAttribute('required', 'required');
            }
        }

        radioJenis.forEach(radio => {
            radio.addEventListener('change', function() {
                toggleFormSections(this.value);
            });
        });

        toggleFormSections(selectedJenis);

        if (selectPembiayaan && inputLainnya) {
            const togglePembiayaanLainnya = () => {
                if (selectPembiayaan.value === 'Lainnya') {
                    inputLainnya.classList.remove('d-none');
                    inputLainnya.setAttribute('required', 'required');
                } else {
                    inputLainnya.classList.add('d-none');
                    inputLainnya.removeAttribute('required');
                    inputLainnya.value = '';
                }
            };

            selectPembiayaan.addEventListener('change', togglePembiayaanLainnya);
            togglePembiayaanLainnya();
        }
    });
</script>
<?= $this->endSection() ?>