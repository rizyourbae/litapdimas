<!-- Modal Tambah Jurnal -->
<div class="modal fade" id="modal-add-jurnal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-bold text-dark mb-0">Tambah Artikel Jurnal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('dosen/proposals/outcomes/store/' . $proposal['uuid']) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="tipe" value="jurnal">
                <div class="modal-body p-4">
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Judul Artikel</label>
                        <div class="col-sm-8">
                            <input type="text" name="judul" class="form-control" placeholder="Judul artikel" required>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Nama Jurnal</label>
                        <div class="col-sm-8">
                            <input type="text" name="nama_jurnal" class="form-control" placeholder="Nama Jurnal" required>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Volume - Nomor</label>
                        <div class="col-sm-8">
                            <input type="text" name="volume_nomor" class="form-control" placeholder="volume dan nomor terbitan" required>
                        </div>
                    </div>
                    <div class="mb-0 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">URL artikel</label>
                        <div class="col-sm-8">
                            <input type="text" name="url" class="form-control" placeholder="URL artikel" required>
                            <div class="form-text small text-danger" style="font-size: 0.7rem;">hapus https:// atau http:// agar tidak diblokir.</div>
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
