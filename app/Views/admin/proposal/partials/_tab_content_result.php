<?php
/**
 * @var array $reviewerResultsPanel
 * @var array $reviewerResultItems
 * @var array $presentationResultItems
 */
?>
<div class="tab-pane fade" id="proposalShowResultPane" role="tabpanel">
    <div class="mb-5">
        <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Hasil Evaluasi Reviewer</h6>

        <ul class="nav nav-pills mb-4 bg-light p-1 rounded-3 d-inline-flex" id="proposalReviewerResultTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-2 py-2 px-4 small fw-bold" id="proposalReviewerResultListTab" data-bs-toggle="tab" data-bs-target="#proposalReviewerResultListPane" type="button" role="tab">
                    <i class="bi bi-chat-left-text me-2"></i>Penilaian Substansi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-2 py-2 px-4 small fw-bold" id="proposalReviewerPresentasiTab" data-bs-toggle="tab" data-bs-target="#proposalReviewerPresentasiPane" type="button" role="tab">
                    <i class="bi bi-easel me-2"></i>Penilaian Presentasi
                </button>
            </li>
        </ul>

        <div class="tab-content" id="proposalReviewerResultTabsContent">
            <!-- Substance Assessment Pane -->
            <div class="tab-pane fade show active" id="proposalReviewerResultListPane" role="tabpanel">
                <?php if (empty($reviewerResultItems)): ?>
                    <div class="p-5 text-center bg-light rounded-4 border">
                        <i class="bi bi-inbox fs-1 text-muted mb-2"></i>
                        <p class="mb-0 text-muted">Belum ada hasil review dari reviewer.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($reviewerResultItems as $reviewer): ?>
                            <div class="p-4 border rounded-4 bg-white shadow-sm">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px;">
                                            <?= strtoupper(substr($reviewer['reviewer_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark"><?= esc($reviewer['reviewer_name'] ?? 'Reviewer') ?></h6>
                                            <div class="small text-muted"><?= esc($reviewer['reviewer_email'] ?? '-') ?></div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="badge <?= esc($reviewer['status_badge_class'] ?? 'bg-secondary') ?> mb-1 rounded-pill px-3"><?= esc($reviewer['status_label'] ?? 'Unknown') ?></div>
                                        <div class="h4 fw-bold text-dark mb-0"><?= esc((string) ($reviewer['review_score_display'] ?? '-')) ?></div>
                                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">Total Skor</div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Bidang Keahlian</div>
                                        <div class="fw-bold small text-dark"><?= esc($reviewer['reviewer_bidang_ilmu'] ?? '-') ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Rekomendasi</div>
                                        <span class="badge <?= esc($reviewer['recommendation_badge_class'] ?? 'bg-light text-dark') ?>"><?= esc($reviewer['recommendation_label'] ?? 'Pending') ?></span>
                                    </div>
                                </div>

                                <div class="bg-light p-3 rounded-3 border-start border-4 border-primary">
                                    <div class="small fw-bold text-primary text-uppercase mb-2" style="font-size: 0.7rem;">Catatan & Review</div>
                                    <div class="small text-secondary lh-base"><?= nl2br(esc((string) ($reviewer['review_notes'] ?? 'Belum ada catatan.'))) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Presentation Assessment Pane -->
            <div class="tab-pane fade" id="proposalShowPresentationPane" role="tabpanel">
                <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4 small">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <?= esc((string) ($reviewerResultsPanel['completion_message'] ?? '')) ?>
                </div>

                <?php if (!empty($presentationResultItems)): ?>
                    <div class="d-flex flex-column gap-3 mb-4">
                        <?php foreach ($presentationResultItems as $reviewer): ?>
                            <div class="p-4 border rounded-4 bg-white shadow-sm">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px;">
                                            <?= strtoupper(substr($reviewer['reviewer_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark"><?= esc($reviewer['reviewer_name'] ?? 'Reviewer') ?></h6>
                                            <div class="small text-muted"><?= esc($reviewer['reviewer_email'] ?? '-') ?></div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="badge <?= esc($reviewer['status_badge_class'] ?? 'bg-secondary') ?> mb-1 rounded-pill px-3"><?= esc($reviewer['status_label'] ?? 'Unknown') ?></div>
                                        <div class="h4 fw-bold text-success mb-0"><?= esc((string) ($reviewer['presentation_score_display'] ?? '-')) ?></div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Anggaran Disetujui</div>
                                        <div class="fw-bold text-dark"><?= esc((string) ($reviewer['presentation_recommended_budget_label'] ?? '-')) ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Update Terakhir</div>
                                        <div class="small text-dark fw-bold"><?= esc((string) ($reviewer['presentation_reviewed_at_label'] ?? '-')) ?></div>
                                    </div>
                                </div>

                                <div class="bg-light p-3 rounded-3 border-start border-4 border-success">
                                    <div class="small fw-bold text-success text-uppercase mb-2" style="font-size: 0.7rem;">Catatan Presentasi</div>
                                    <div class="small text-secondary lh-base"><?= nl2br(esc((string) ($reviewer['presentation_notes'] ?? 'Belum ada hasil penilaian presentasi.'))) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-5 text-center bg-light rounded-4 border mb-4">
                        <i class="bi bi-easel fs-1 text-muted mb-2"></i>
                        <p class="mb-0 text-muted">Belum ada hasil penilaian presentasi.</p>
                    </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <?php if (!empty($reviewerResultsPanel['all_reviewed'])): ?>
                        <a href="<?= esc((string) ($reviewerResultsPanel['presentasi_url'] ?? '#')) ?>" class="btn btn-success btn-lg px-5 rounded-pill shadow-sm fw-bold">
                            <i class="bi bi-easel2 me-2"></i><?= esc((string) ($reviewerResultsPanel['presentasi_label'] ?? 'Buka Penilaian Presentasi')) ?>
                        </a>
                        <p class="small text-muted mt-2 px-lg-5"><?= esc((string) ($reviewerResultsPanel['presentasi_hint'] ?? '')) ?></p>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-secondary btn-lg px-5 rounded-pill" disabled>
                            <i class="bi bi-lock-fill me-2"></i>Penilaian Presentasi Belum Tersedia
                        </button>
                        <p class="small text-muted mt-2 px-lg-5"><?= esc((string) ($reviewerResultsPanel['presentasi_hint'] ?? '')) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
