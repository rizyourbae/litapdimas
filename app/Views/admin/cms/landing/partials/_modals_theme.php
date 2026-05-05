<!-- Modal Tema Riset -->
<div class="modal fade" id="modalTheme" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white p-4">
                <h5 class="modal-title fw-bold" id="modalThemeTitle">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Tema Riset
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/cms/landing/themes/store') ?>" method="post" id="formTheme">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Tema Riset</label>
                        <input type="text" name="nama" id="theme_nama" class="form-control border-2 shadow-none" required 
                               placeholder="Contoh: Moderasi Beragama">
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Ikon (Bootstrap Icons)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-2 border-end-0"><i class="bi bi-info-circle"></i></span>
                                <input type="text" name="icon" id="theme_icon" class="form-control border-2 border-start-0 shadow-none" required 
                                       placeholder="bi-journal-text">
                            </div>
                            <div class="form-text small mt-2">
                                <a href="https://icons.getbootstrap.com/" target="_blank" class="text-decoration-none">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Cari Nama Ikon di Sini
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Urutan</label>
                            <input type="number" name="sort_order" id="theme_sort" class="form-control border-2 shadow-none" value="1" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold d-block">Status</label>
                        <div class="form-check form-switch form-check-inline">
                            <input class="form-check-input" type="checkbox" name="is_active" id="theme_active" value="1" checked>
                            <label class="form-check-label" for="theme_active">Aktif & Tampilkan</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-4 border-0">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold" id="btnSubmitTheme">
                        <i class="bi bi-save me-2"></i>Simpan Tema
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
