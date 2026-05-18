<!-- MODAL TAMBAH PENGELOLA BANTUAN -->
<div class="modal fade" id="modal-tambah-pengelola" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="<?= site_url('admin/master-data-proposal/store-pengelola-bantuan') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="POST">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold text-dark mb-0" data-admin-modal-title>Tambah Pengelola Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nama_pengelola" class="form-label fw-bold small text-muted text-uppercase ls-1">Nama Pengelola Bantuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border" id="nama_pengelola" name="nama" required minlength="3" maxlength="100" placeholder="Masukkan nama pengelola bantuan">
                        <div class="form-text mt-2 small">Contoh: Direktorat PTKI Kemenag RI, LP2M UINSI Samarinda.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT PENGELOLA BANTUAN -->
<div class="modal fade" id="modal-edit-pengelola" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form id="form-edit-pengelola" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_pengelola_id" name="id">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold text-dark mb-0" data-admin-modal-title>Edit Pengelola Bantuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_nama_pengelola" class="form-label fw-bold small text-muted text-uppercase ls-1">Nama Pengelola Bantuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border" id="edit_nama_pengelola" name="nama" required minlength="3" maxlength="100">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white rounded-pill px-4 shadow-sm">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
