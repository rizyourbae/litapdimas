<!-- Modal Banner -->
<div class="modal fade" id="modalBanner" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white p-4">
                <h5 class="modal-title fw-bold" id="modalBannerTitle">
                    <i class="bi bi-image me-2"></i>Tambah Banner Carousel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/cms/landing/banners/store') ?>" method="post" id="formBanner" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Upload Image -->
                        <div class="col-md-5">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Gambar Banner</label>
                                <div class="p-3 border-2 border-dashed rounded-4 text-center bg-light mb-3" id="banner_preview_container">
                                    <img src="<?= base_url('assets/adminlte/assets/img/no-image.png') ?>" id="banner_preview" class="img-fluid rounded-3 shadow-sm mb-3 d-none">
                                    <div id="banner_placeholder">
                                        <i class="bi bi-cloud-arrow-up display-4 text-muted"></i>
                                        <p class="small text-muted mt-2">Klik untuk pilih gambar<br>(Rekomendasi 1200x500 px)</p>
                                    </div>
                                </div>
                                <input type="file" name="image" id="banner_file" class="form-control border-2 shadow-none" accept="image/*">
                            </div>
                        </div>

                        <!-- Text Information -->
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Judul Banner (Opsional)</label>
                                <input type="text" name="title" id="banner_title" class="form-control border-2 shadow-none" 
                                       placeholder="Contoh: Pengumuman Proposal 2026">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Deskripsi Singkat</label>
                                <textarea name="description" id="banner_description" class="form-control border-2 shadow-none" rows="3" 
                                          placeholder="Teks tambahan di bawah judul..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Link URL (Opsional)</label>
                                <input type="text" name="link_url" id="banner_link" class="form-control border-2 shadow-none" 
                                       placeholder="https://example.com/info-detail">
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Urutan</label>
                                    <input type="number" name="sort_order" id="banner_sort" class="form-control border-2 shadow-none" value="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold d-block">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="banner_active" value="1" checked>
                                        <label class="form-check-label" for="banner_active">Aktif & Tampilkan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-4 border-0">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold" id="btnSubmitBanner">
                        <i class="bi bi-save me-2"></i>Simpan Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
