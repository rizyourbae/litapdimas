<!-- Modal Tambah Buku -->
<div class="modal fade" id="modal-add-buku" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-bold text-dark mb-0">Tambah Publikasi Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('dosen/proposals/outcomes/store/' . $proposal['uuid']) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="tipe" value="buku">
                <div class="modal-body p-4">
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Judul Buku</label>
                        <div class="col-sm-8">
                            <input type="text" name="judul" class="form-control" placeholder="Judul Buku" required>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">ISBN</label>
                        <div class="col-sm-8">
                            <input type="text" name="isbn" class="form-control" placeholder="ISBN Buku" required>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Penerbit</label>
                        <div class="col-sm-8">
                            <input type="text" name="penerbit" class="form-control" placeholder="Nama Penerbit" required>
                        </div>
                    </div>
                    <div class="mb-0 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Tahun Terbit</label>
                        <div class="col-sm-8">
                            <input type="number" name="tahun_terbit" class="form-control" placeholder="Tahun Terbit" value="<?= date('Y') ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-success px-4 rounded-3 fw-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
