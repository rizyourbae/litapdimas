<!-- Modal Form Announcement -->
<div class="modal fade" id="modal-announcement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle" data-admin-modal-title>Tambah Pengumuman</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-announcement" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted small mb-1">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg fs-6 shadow-none" 
                                placeholder="Contoh: Jadwal Penerimaan Proposal 2026" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted small mb-1">Konten Informasi <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control shadow-none" rows="6" 
                                placeholder="Tuliskan isi pengumuman di sini..." required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small mb-1">Gambar Unggulan (Opsional)</label>
                            <input type="file" name="image" class="form-control shadow-none" accept="image/*">
                            <div class="form-text small text-muted">Format: JPG, PNG, WEBP (Max 2MB).</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small mb-1">File Lampiran (Opsional)</label>
                            <input type="file" name="file_attachment" class="form-control shadow-none" accept=".pdf,.doc,.docx,.zip">
                            <div class="form-text small text-muted">PDF, DOCX, atau ZIP (Max 10MB).</div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 bg-light rounded-4 border border-dashed">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input ms-0 me-3 shadow-none" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                    <label class="form-check-label fw-bold text-dark" for="is_active">Aktifkan Pengumuman</label>
                                    <div class="form-text small text-muted ms-0 mt-1">Jika aktif, pengumuman akan langsung tampil di halaman depan publik.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">Simpan Pengumuman</button>
                </div>
            </form>
        </div>
    </div>
</div>
