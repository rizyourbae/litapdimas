<?php
/**
 * @var array $assignmentPanel
 * @var array $recommendedReviewers
 * @var array $manualReviewers
 */
?>
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-2">
            <i class="bi bi-person-check-fill text-primary fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Tim Reviewer Aktif</h6>
        </div>

        <!-- List of Assigned Reviewers -->
        <?php if (empty($assignmentPanel['assigned_reviewers'])): ?>
            <div class="p-4 text-center bg-light rounded-4 border mb-4">
                <i class="bi bi-clipboard-x fs-2 text-muted mb-2"></i>
                <p class="mb-0 text-muted small">Belum ada reviewer ditugaskan.</p>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3 mb-4">
                <?php foreach ($assignmentPanel['assigned_reviewers'] as $reviewer): ?>
                    <div class="p-3 border rounded-3 bg-white hover-shadow-sm transition-all position-relative overflow-hidden">
                        <div class="position-absolute top-0 end-0 p-2">
                            <form action="<?= esc($reviewer['remove_url']) ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-danger btn-sm border-0 p-1" title="Hapus Penugasan">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        <div class="fw-bold text-dark small mb-0 pe-4"><?= esc($reviewer['reviewer_name']) ?></div>
                        <div class="text-muted mb-2" style="font-size: 0.7rem;"><?= esc($reviewer['reviewer_email']) ?></div>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge <?= esc($reviewer['status_badge_class']) ?> small" style="font-size: 0.65rem;"><?= esc($reviewer['status_label']) ?></span>
                            <span class="badge text-bg-light border small" style="font-size: 0.65rem;"><?= esc($reviewer['reviewer_bidang_ilmu']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Assignment Form -->
        <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-2 mt-2">
            <i class="bi bi-person-plus-fill text-primary fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Tugaskan Reviewer Baru</h6>
        </div>

        <p class="text-muted small mb-3 lh-sm" style="font-size: 0.75rem;"><?= esc($assignmentPanel['assignment_hint'] ?? '') ?></p>

        <?php if (empty($assignmentPanel['has_candidates'])): ?>
            <div class="p-4 text-center bg-light rounded-4 border">
                <p class="mb-0 text-muted small"><?= esc($assignmentPanel['empty_message'] ?? 'Tidak ada kandidat tersedia.') ?></p>
            </div>
        <?php else: ?>
            <form action="<?= esc($assignmentPanel['form_action'] ?? '') ?>" method="post" id="assignmentForm">
                <?= csrf_field() ?>

                <!-- Recommended Reviewers -->
                <?php if (!empty($recommendedReviewers)): ?>
                    <div class="mb-4">
                        <div class="small fw-bold text-muted text-uppercase mb-3" style="font-size: 0.65rem;">Saran Sistem (Match Score Tinggi)</div>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($recommendedReviewers as $reviewer): ?>
                                <label class="p-3 border rounded-3 bg-white d-flex align-items-center gap-3 cursor-pointer hover-shadow-sm transition-all border-primary-hover">
                                    <input class="form-check-input mt-0 shadow-none" type="checkbox" name="reviewer_ids[]" value="<?= esc((string) $reviewer['id']) ?>">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-dark small text-truncate"><?= esc($reviewer['name']) ?></div>
                                        <div class="badge <?= esc($reviewer['fit_badge_class']) ?> mt-1" style="font-size: 0.6rem;"><?= esc($reviewer['fit_label']) ?></div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Manual Search Toggle -->
                <div class="mb-4">
                    <button class="btn btn-light w-100 btn-sm text-muted fw-bold d-flex justify-content-between align-items-center rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#manualReviewerCollapse">
                        <span style="font-size: 0.75rem;"><i class="bi bi-search me-2"></i>Pencarian Manual</span>
                        <i class="bi bi-chevron-down small"></i>
                    </button>

                    <div class="collapse mt-3" id="manualReviewerCollapse">
                        <input type="search" id="manualReviewerSearch" class="form-control form-control-sm mb-3 shadow-none bg-light border-0" placeholder="Cari nama, email, atau bidang..."
                            data-reviewer-filter-input="#manualReviewerList" data-reviewer-empty-target="#manualReviewerEmpty" data-reviewer-count-target="#manualReviewerVisibleCount">

                        <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 300px;" id="manualReviewerList">
                            <?php if (!empty($manualReviewers)): ?>
                                <?php foreach ($manualReviewers as $reviewer): ?>
                                    <label class="p-2 border rounded-3 bg-white d-flex align-items-center gap-3 cursor-pointer hover-shadow-sm transition-all small" data-reviewer-card-filter-item data-reviewer-search="<?= esc(strtolower(trim(($reviewer['name'] ?? '') . ' ' . ($reviewer['email'] ?? '') . ' ' . ($reviewer['bidang_ilmu'] ?? '')))) ?>">
                                        <input class="form-check-input mt-0 shadow-none" type="checkbox" name="reviewer_ids[]" value="<?= esc((string) $reviewer['id']) ?>">
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.75rem;"><?= esc($reviewer['name']) ?></div>
                                            <div class="text-muted" style="font-size: 0.65rem;"><?= esc($reviewer['bidang_ilmu']) ?></div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div id="manualReviewerEmpty" class="text-center py-3 d-none">
                            <small class="text-muted italic">Tidak ditemukan.</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Catatan Admin (Internal)</label>
                    <textarea name="assignment_notes" class="form-control form-control-sm shadow-none" rows="2" placeholder="Tulis instruksi khusus..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm rounded-pill">
                    <i class="bi bi-person-plus-fill me-1"></i>Tugaskan Reviewer
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>
