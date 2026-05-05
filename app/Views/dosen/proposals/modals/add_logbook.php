<!-- Modal Tambah Logbook -->
<div class="modal fade" id="addLogbookModal" tabindex="-1" aria-labelledby="addLogbookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold" id="addLogbookModalLabel">
                    <i class="bi bi-journal-plus me-2"></i>Tambah Logbook Penelitian
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('dosen/proposals/logbook/store/' . $proposal['uuid']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Kegiatan</label>
                            <input type="text" name="tanggal" 
                                class="form-control shadow-none datepicker" 
                                value="<?= date('Y-m-d') ?>" 
                                data-locale="id"
                                data-date-format="Y-m-d"
                                data-alt-format="d F Y"
                                placeholder="Pilih tanggal kegiatan"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tempat / Lokasi</label>
                            <input type="text" name="tempat" class="form-control shadow-none" placeholder="Nama lokasi kegiatan" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control shadow-none" placeholder="Nama kegiatan" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Teknik Penelitian</label>
                            <select name="teknik" class="form-select shadow-none" required>
                                <option value="" disabled selected>Pilih Teknik...</option>
                                <option value="Analisis Dokumen">Analisis Dokumen</option>
                                <option value="Diskusi">Diskusi</option>
                                <option value="FGD">FGD</option>
                                <option value="Observasi">Observasi</option>
                                <option value="Penyebaran Angket">Penyebaran Angket</option>
                                <option value="Wawancara">Wawancara</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi Kegiatan</label>
                            <textarea name="deskripsi_kegiatan" class="form-control shadow-none" rows="4" placeholder="Jelaskan detail kegiatan..." required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Berkas / File Pendukung (Opsional)</label>
                            <input type="file" name="berkas" class="form-control shadow-none">
                            <div class="form-text small text-muted">Format: PDF, JPG, PNG, ZIP, DOCX (Maks. 2MB)</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Logbook</button>
                </div>
            </form>
        </div>
    </div>
</div>
