<?= $this->extend('layouts/main') ?>

<?php $hide_header = true; ?>

<?= $this->section('content') ?>

<?php
$hero = isset($hero) && is_array($hero) ? $hero : [];
$actions = isset($actions) && is_array($actions) ? $actions : [];
$metrics = isset($metrics) && is_array($metrics) ? $metrics : [];
$summaryItems = isset($summaryItems) && is_array($summaryItems) ? $summaryItems : [];
$abstract = isset($abstract) && is_array($abstract) ? $abstract : [];
$journalInfo = isset($journalInfo) && is_array($journalInfo) ? $journalInfo : [];

/** @var array<int, array{number: int|string, title: string, content_html: string}> $substansiSections */
$substansiSections = isset($substansiSections) && is_array($substansiSections) ? $substansiSections : [];
/** @var array<int, array{title: string, headers: array<int, string>, rows: array<int, array{cells: array<int, string|int|float|bool|null>}>, colspan: int, empty_message: string}> $teamSections */
$teamSections = isset($teamSections) && is_array($teamSections) ? $teamSections : [];
/** @var array<int, array{label: string, file_name: string, file_size_label: string, view_url?: string}> $documentRows */
$documentRows = isset($documentRows) && is_array($documentRows) ? $documentRows : [];
/** @var array{recommended_reviewers?: array<int, array{id: int|string, name: string, email: string, bidang_ilmu: string, fit_badge_class: string, fit_label: string}>, manual_reviewers?: array<int, array{id: int|string, name: string, email: string, bidang_ilmu: string, fit_badge_class: string, fit_label: string}>, manual_reviewer_count?: int, recommended_hidden_count?: int, assignment_hint?: string, form_action?: string, has_candidates?: bool, empty_message?: string, remove_button_label?: string, assigned_reviewers?: array<int, array{reviewer_name: string, reviewer_email: string, reviewer_bidang_ilmu: string, status_badge_class: string, status_label: string, recommendation_badge_class: string, recommendation_label: string, review_notes: string, remove_url: string}>, recommended_total?: int, manual_hint?: string} $assignmentPanel */
$assignmentPanel = isset($assignmentPanel) && is_array($assignmentPanel) ? $assignmentPanel : [];
/** @var array{cards?: array<int, array{label: string, tone_class: string, value: string}>, note?: string} $decisionSummary */
$decisionSummary = isset($decisionSummary) && is_array($decisionSummary) ? $decisionSummary : [];
/** @var array{items?: array<int, array{reviewer_name: string, reviewer_email: string, reviewer_bidang_ilmu: string, status_badge_class: string, status_label: string, review_score_display?: string|int|null, recommendation_badge_class: string, recommendation_label: string, reviewed_at_label?: string, review_notes?: string}>, presentation_items?: array<int, array{reviewer_name: string, reviewer_email: string, reviewer_bidang_ilmu: string, status_badge_class: string, status_label: string, presentation_score_display?: string|int|null, presentation_notes?: string, presentation_recommended_budget_label?: string, presentation_reviewed_at_label?: string}>, completion_message?: string, all_reviewed?: bool, presentasi_url?: string, presentasi_label?: string, presentasi_hint?: string, has_presentation_items?: bool} $reviewerResultsPanel */
$reviewerResultsPanel = isset($reviewerResultsPanel) && is_array($reviewerResultsPanel) ? $reviewerResultsPanel : [];

/** @var array<int, array{id: int|string, name: string, email: string, bidang_ilmu: string, fit_badge_class: string, fit_label: string}> $recommendedReviewers */
$recommendedReviewers = isset($assignmentPanel['recommended_reviewers']) && is_array($assignmentPanel['recommended_reviewers']) ? $assignmentPanel['recommended_reviewers'] : [];
/** @var array<int, array{id: int|string, name: string, email: string, bidang_ilmu: string, fit_badge_class: string, fit_label: string}> $manualReviewers */
$manualReviewers = isset($assignmentPanel['manual_reviewers']) && is_array($assignmentPanel['manual_reviewers']) ? $assignmentPanel['manual_reviewers'] : [];
$manualReviewerCount = (int) ($assignmentPanel['manual_reviewer_count'] ?? count($manualReviewers));
$recommendedHiddenCount = (int) ($assignmentPanel['recommended_hidden_count'] ?? 0);
$substansiSectionCount = count($substansiSections);
$teamSectionCount = count($teamSections ?? []);
$documentRowCount = count($documentRows);
/** @var array<int, array{reviewer_name: string, reviewer_email: string, reviewer_bidang_ilmu: string, status_badge_class: string, status_label: string, review_score_display?: string|int|null, recommendation_badge_class: string, recommendation_label: string, reviewed_at_label?: string, review_notes?: string}> $reviewerResultItems */
$reviewerResultItems = isset($reviewerResultsPanel['items']) && is_array($reviewerResultsPanel['items']) ? $reviewerResultsPanel['items'] : [];
/** @var array<int, array{reviewer_name: string, reviewer_email: string, reviewer_bidang_ilmu: string, status_badge_class: string, status_label: string, presentation_score_display?: string|int|null, presentation_notes?: string, presentation_recommended_budget_label?: string, presentation_reviewed_at_label?: string}> $presentationResultItems */
$presentationResultItems = isset($reviewerResultsPanel['presentation_items']) && is_array($reviewerResultsPanel['presentation_items']) ? $reviewerResultsPanel['presentation_items'] : [];
?>

<div class="row g-3 admin-page">
    <div class="col-12 mb-2">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) ($hero['title'] ?? $title)),
            'subtitle' => esc((string) ($hero['subtitle'] ?? '')),
            'badges' => [
                ['label' => 'Proposal Detail', 'class' => 'text-bg-light border shadow-sm'],
                ['label' => esc((string) ($hero['status_label'] ?? '')), 'class' => esc((string) ($hero['status_class'] ?? 'text-bg-primary')) . ' shadow-sm']
            ],
            'actions' => [
                [
                    'label' => 'Kembali',
                    'class' => 'btn btn-outline-secondary',
                    'icon' => 'bi bi-arrow-left',
                    'url' => site_url('admin/proposal')
                ]
            ]
        ]) ?>
    </div>

    <div class="col-xl-8">
        <div class="card shadow-sm border-0 overflow-hidden mb-4">
            <div class="card-header p-0 bg-light border-bottom">
                <ul class="nav admin-nav-tabs border-0 px-3" id="proposalShowTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active border-0 py-3 fw-bold" id="proposalShowDetailTab" data-bs-toggle="tab" data-bs-target="#proposalShowDetailPane" type="button" role="tab">
                            <i class="bi bi-file-earmark-text-fill me-2"></i>Konten Proposal
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 py-3 fw-bold" id="proposalShowResultTab" data-bs-toggle="tab" data-bs-target="#proposalShowResultPane" type="button" role="tab">
                            <i class="bi bi-chat-left-dots-fill me-2"></i>Evaluasi & Review
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4 p-lg-5">
                <div class="tab-content" id="proposalShowTabsContent">
                    <div class="tab-pane fade show active" id="proposalShowDetailPane" role="tabpanel">
                        <div class="row g-4 mb-5">
                            <?php foreach ($summaryItems as $item): ?>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded-3 border h-100">
                                        <div class="small text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.7rem;"><?= esc((string) ($item['label'] ?? '')) ?></div>
                                        <div class="fw-bold text-dark"><?= esc((string) ($item['value'] ?? '')) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mb-5">
                            <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Abstrak & Substansi Utama</h6>
                            <div class="p-4 bg-white border rounded-4 shadow-sm">
                                <h4 class="h5 fw-bold mb-3"><?= esc($abstract['title']) ?></h4>
                                <?php if (!empty($abstract['html'])): ?>
                                    <div class="admin-proposal-rich fs-6 lh-lg text-secondary"><?= $abstract['html'] ?></div>
                                <?php else: ?>
                                    <div class="text-center py-5 opacity-50">
                                        <i class="bi bi-card-text display-4 mb-2"></i>
                                        <p class="mb-0 fw-semibold"><?= esc($abstract['empty_message']) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                <h6 class="fw-bold text-primary text-uppercase small mb-0 ls-1">Struktur Detail Proposal</h6>
                                <?php if (!empty($substansiSections)): ?>
                                    <span class="badge text-bg-light border px-3 rounded-pill"><?= esc((string) $substansiSectionCount) ?> Bagian</span>
                                <?php endif; ?>
                            </div>

                            <?php if (empty($substansiSections)): ?>
                                <div class="p-4 bg-light text-center rounded-4 border">
                                    <i class="bi bi-layout-text-window fs-2 text-muted mb-2"></i>
                                    <p class="mb-0 text-muted small">Belum ada bagian substansi terperinci.</p>
                                </div>
                            <?php else: ?>
                                <div class="accordion accordion-flush admin-proposal-accordion border rounded-4 overflow-hidden" id="substansiAccordion">
                                    <?php foreach ($substansiSections as $index => $section): ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?> fw-bold py-3 px-4 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#sec-<?= $index ?>">
                                                    <span class="badge text-bg-primary me-3"><?= esc((string) $section['number']) ?></span>
                                                    <?= esc($section['title']) ?>
                                                </button>
                                            </h2>
                                            <div id="sec-<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#substansiAccordion">
                                                <div class="accordion-body p-4 fs-6 text-secondary lh-base">
                                                    <?= $section['content_html'] ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>


                        <div class="mb-5">
                            <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Informasi Pendukung & Luaran</h6>
                            <div class="row g-3">
                                <?php foreach ($journalInfo['items'] as $item): ?>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="p-3 bg-white border rounded-3">
                                            <div class="small text-muted mb-1"><?= esc((string) ($item['label'] ?? '')) ?></div>
                                            <div class="fw-bold text-dark small"><?= esc((string) ($item['value'] ?? '')) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (!empty($journalInfo['links'])): ?>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <?php foreach ($journalInfo['links'] as $link): ?>
                                        <a href="<?= esc((string) ($link['url'] ?? '#')) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                                            <i class="bi bi-link-45deg me-1"></i><?= esc((string) ($link['label'] ?? '')) ?>: <?= esc((string) ($link['value'] ?? '')) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-5">
                            <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Komposisi Tim & Peneliti</h6>
                            <?php foreach ($teamSections as $section): ?>
                                <div class="mb-4">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="vr" style="width: 4px; border-radius: 2px; background-color: var(--bs-primary); opacity: 1;"></div>
                                        <h6 class="fw-bold mb-0 text-dark"><?= esc($section['title']) ?></h6>
                                    </div>
                                    <div class="table-responsive border rounded-3 overflow-hidden shadow-sm">
                                        <table class="table table-hover table-sm align-middle mb-0" style="font-size: 0.85rem;">
                                            <thead class="bg-light border-bottom">
                                                <tr>
                                                    <?php foreach ($section['headers'] as $header): ?>
                                                        <th class="p-3 fw-bold text-muted text-uppercase small" style="letter-spacing: 0.05em;"><?= esc($header) ?></th>
                                                    <?php endforeach; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($section['rows'])): ?>
                                                    <tr>
                                                        <td colspan="<?= esc((string) $section['colspan']) ?>" class="p-4 text-center text-muted italic"><?= esc($section['empty_message']) ?></td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($section['rows'] as $row): ?>
                                                        <tr>
                                                            <?php foreach ($row['cells'] as $cell): ?>
                                                                <td class="p-3 text-dark"><?= esc((string) $cell) ?></td>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mb-0">
                            <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Dokumen & Lampiran Resmi</h6>
                            <div class="row g-3">
                                <?php foreach ($documentRows as $row): ?>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-white d-flex align-items-center justify-content-between shadow-sm hover-shadow-sm transition-all">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-light p-2 rounded text-primary">
                                                    <i class="bi bi-file-earmark-pdf fs-4"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark small mb-0"><?= esc($row['label']) ?></div>
                                                    <div class="text-muted" style="font-size: 0.7rem;"><?= esc($row['file_name']) ?> (<?= esc($row['file_size_label']) ?>)</div>
                                                </div>
                                            </div>
                                            <?php if (!empty($row['view_url'])): ?>
                                                <a href="<?= esc($row['view_url']) ?>" target="_blank" class="btn btn-light btn-sm rounded-circle border p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Buka Dokumen">
                                                    <i class="bi bi-eye-fill text-primary"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>


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
                                                                <?= strtoupper(substr($reviewer['reviewer_name'], 0, 1)) ?>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-bold mb-0 text-dark"><?= esc($reviewer['reviewer_name']) ?></h6>
                                                                <div class="small text-muted"><?= esc($reviewer['reviewer_email']) ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="badge <?= esc($reviewer['status_badge_class']) ?> mb-1 rounded-pill px-3"><?= esc($reviewer['status_label']) ?></div>
                                                            <div class="h4 fw-bold text-dark mb-0"><?= esc((string) ($reviewer['review_score_display'] ?? '-')) ?></div>
                                                            <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">Total Skor</div>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mb-4">
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">Bidang Keahlian</div>
                                                            <div class="fw-bold small text-dark"><?= esc($reviewer['reviewer_bidang_ilmu']) ?></div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">Rekomendasi</div>
                                                            <span class="badge <?= esc($reviewer['recommendation_badge_class']) ?>"><?= esc($reviewer['recommendation_label']) ?></span>
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

                                <div class="tab-pane fade" id="proposalReviewerPresentasiPane" role="tabpanel">
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
                                                                <?= strtoupper(substr($reviewer['reviewer_name'], 0, 1)) ?>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-bold mb-0 text-dark"><?= esc($reviewer['reviewer_name']) ?></h6>
                                                                <div class="small text-muted"><?= esc($reviewer['reviewer_email']) ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="badge <?= esc($reviewer['status_badge_class']) ?> mb-1 rounded-pill px-3"><?= esc($reviewer['status_label']) ?></div>
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
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-2">
                    <i class="bi bi-person-check-fill text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Tim Reviewer Aktif</h6>
                </div>

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

                <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-2 mt-2">
                    <i class="bi bi-person-plus-fill text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Tugaskan Reviewer Baru</h6>
                </div>

                <p class="text-muted small mb-3 lh-sm" style="font-size: 0.75rem;"><?= esc($assignmentPanel['assignment_hint']) ?></p>

                <?php if (!$assignmentPanel['has_candidates']): ?>
                    <div class="p-4 text-center bg-light rounded-4 border">
                        <p class="mb-0 text-muted small"><?= esc($assignmentPanel['empty_message']) ?></p>
                    </div>
                <?php else: ?>
                    <form action="<?= esc($assignmentPanel['form_action']) ?>" method="post" id="assignmentForm">
                        <?= csrf_field() ?>

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

                        <div class="mb-4">
                            <button class="btn btn-light w-100 btn-sm text-muted fw-bold d-flex justify-content-between align-items-center rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#manualReviewerCollapse">
                                <span style="font-size: 0.75rem;"><i class="bi bi-search me-2"></i>Pencarian Manual</span>
                                <i class="bi bi-chevron-down small"></i>
                            </button>

                            <div class="collapse mt-3" id="manualReviewerCollapse">
                                <input type="search" id="manualReviewerSearch" class="form-control form-control-sm mb-3 shadow-none bg-light border-0" placeholder="Cari nama, email, atau bidang..."
                                    data-reviewer-filter-input="#manualReviewerList" data-reviewer-empty-target="#manualReviewerEmpty" data-reviewer-count-target="#manualReviewerVisibleCount">

                                <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 300px;" id="manualReviewerList">
                                    <?php foreach ($manualReviewers as $reviewer): ?>
                                        <label class="p-2 border rounded-3 bg-white d-flex align-items-center gap-3 cursor-pointer hover-shadow-sm transition-all small" data-reviewer-card-filter-item data-reviewer-search="<?= esc(strtolower(trim(($reviewer['name'] ?? '') . ' ' . ($reviewer['email'] ?? '') . ' ' . ($reviewer['bidang_ilmu'] ?? '')))) ?>">
                                            <input class="form-check-input mt-0 shadow-none" type="checkbox" name="reviewer_ids[]" value="<?= esc((string) $reviewer['id']) ?>">
                                            <div class="overflow-hidden">
                                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.75rem;"><?= esc($reviewer['name']) ?></div>
                                                <div class="text-muted" style="font-size: 0.65rem;"><?= esc($reviewer['bidang_ilmu']) ?></div>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
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

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-2">
                    <i class="bi bi-check2-square text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Keputusan Akhir</h6>
                </div>

                <div class="row g-2 mb-4">
                    <?php foreach ($decisionSummary['cards'] as $card): ?>
                        <div class="col-6">
                            <div class="p-2 bg-light border rounded-3 text-center">
                                <div class="small text-muted fw-bold mb-1" style="font-size: 0.6rem;"><?= esc($card['label']) ?></div>
                                <div class="fw-bold <?= esc($card['tone_class']) ?>" style="font-size: 0.9rem;"><?= esc($card['value']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="bg-light p-3 rounded-3 border-start border-4 border-primary mb-4">
                    <div class="small fw-bold text-primary text-uppercase mb-1" style="font-size: 0.65rem;">Ringkasan & Panduan</div>
                    <div class="small text-secondary lh-sm"><?= esc($decisionSummary['note']) ?></div>
                </div>

                <?php if ($decisionSummary['can_decide']): ?>
                    <button type="button" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-final-decision">
                        <i class="bi bi-check-circle-fill me-2"></i>Berikan Keputusan Akhir
                    </button>
                <?php elseif ($decisionSummary['is_decided']): ?>
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="small fw-bold text-muted text-uppercase mb-2" style="font-size: 0.6rem;">Status Akhir Terpilih</div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle-fill <?= $hero['status_class'] ?? 'text-primary' ?>"></i>
                            <span class="fw-bold <?= $hero['status_class'] ?? 'text-primary' ?>"><?= esc($hero['status_label'] ?? '') ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <button type="button" class="btn btn-outline-secondary w-100 fw-bold rounded-pill" disabled title="Tunggu semua reviewer selesai memberikan penilaian">
                        <i class="bi bi-hourglass-split me-2"></i>Menunggu Penilaian
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

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


<?= $this->endSection() ?>

<?php $this->section('scripts'); ?>
<script src="<?= base_url('custom/js/admin-proposal-show.js') ?>"></script>
<?php $this->endSection(); ?>