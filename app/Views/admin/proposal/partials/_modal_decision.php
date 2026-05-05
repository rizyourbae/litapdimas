<?php
/**
 * @var string $uuid
 */
?>
<!-- MODAL FINAL DECISION -->
<div class="modal fade" id="modal-final-decision" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="<?= site_url('admin/proposals/decide/' . $uuid) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-bold text-dark mb-0">Keputusan Akhir Proposal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 small mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Pastikan Anda telah meninjau seluruh hasil evaluasi reviewer sebelum mengambil keputusan akhir.
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase ls-1">Pilih Keputusan <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="flex-grow-1">
                                <input type="radio" class="btn-check" name="decision" id="dec_approve" value="approved" required>
                                <label class="btn btn-outline-success w-100 py-3 rounded-3 fw-bold" for="dec_approve">
                                    <i class="bi bi-check-circle-fill d-block fs-4 mb-1"></i>
                                    Setujui
                                </label>
                            </div>
                            <div class="flex-grow-1">
                                <input type="radio" class="btn-check" name="decision" id="dec_reject" value="rejected" required>
                                <label class="btn btn-outline-danger w-100 py-3 rounded-3 fw-bold" for="dec_reject">
                                    <i class="bi bi-x-circle-fill d-block fs-4 mb-1"></i>
                                    Tolak
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="decision_notes" class="form-label fw-bold small text-muted text-uppercase ls-1">Catatan Akhir Admin (Opsional)</label>
                        <textarea class="form-control rounded-3 shadow-sm" id="decision_notes" name="decision_notes" rows="4" placeholder="Tulis catatan atau alasan keputusan untuk pengusul..."></textarea>
                        <div class="form-text small mt-2">Catatan ini akan dapat dilihat oleh pengusul (Dosen) pada dashboard mereka.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">Konfirmasi Keputusan</button>
                </div>
            </form>
        </div>
    </div>
</div>
