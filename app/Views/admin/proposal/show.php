<?= $this->extend('layouts/main') ?>

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
/** @var array{items?: array<int, array{reviewer_name: string, reviewer_email: string, reviewer_bidang_ilmu: string, status_badge_class: string, status_label: string, review_score_display?: string|int|null, recommendation_badge_class: string, recommendation_label: string, reviewed_at_label?: string, review_notes?: string}>, completion_message?: string, all_reviewed?: bool, presentasi_url?: string, presentasi_label?: string, presentasi_hint?: string} $reviewerResultsPanel */
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
?>

<div class="row g-3 admin-page admin-proposal-page">
    <div class="col-xl-8">
        <div class="card admin-panel-card reviewer-detail-shell">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="proposalShowTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="proposalShowDetailTab" data-bs-toggle="tab" data-bs-target="#proposalShowDetailPane" type="button" role="tab" aria-controls="proposalShowDetailPane" aria-selected="true">
                            <i class="bi bi-file-earmark-text me-1"></i>Detail Proposal
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="proposalShowResultTab" data-bs-toggle="tab" data-bs-target="#proposalShowResultPane" type="button" role="tab" aria-controls="proposalShowResultPane" aria-selected="false">
                            <i class="bi bi-chat-square-text me-1"></i>Hasil Review
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="proposalShowTabsContent">
                    <div class="tab-pane fade show active" id="proposalShowDetailPane" role="tabpanel" aria-labelledby="proposalShowDetailTab" tabindex="0">
                        <div class="admin-summary-grid">
                            <?php foreach ($summaryItems as $item): ?>
                                <div class="admin-detail-item">
                                    <div class="admin-detail-item__label"><?= esc((string) ($item['label'] ?? '')) ?></div>
                                    <div class="admin-detail-item__value"><?= esc((string) ($item['value'] ?? '')) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="card admin-panel-card mt-3">
                            <div class="card-body">
                                <div class="admin-proposal-section__header mb-3">
                                    <div>
                                        <div class="small text-uppercase text-muted mb-1">Substansi</div>
                                        <h3 class="h5 mb-0"><?= esc($abstract['title']) ?></h3>
                                    </div>
                                </div>
                                <?php if (!empty($abstract['html'])): ?>
                                    <div class="admin-proposal-rich"><?= $abstract['html'] ?></div>
                                <?php else: ?>
                                    <div class="admin-empty-state py-4">
                                        <i class="bi bi-card-text"></i>
                                        <p class="mb-0 fw-semibold text-body-emphasis"><?= esc($abstract['empty_message']) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card admin-panel-card mt-3">
                            <div class="card-body">
                                <div class="admin-proposal-section__header mb-3">
                                    <div>
                                        <div class="small text-uppercase text-muted mb-1">Struktur Proposal</div>
                                        <h3 class="h5 mb-0">Bagian Substansi</h3>
                                    </div>
                                    <?php if (!empty($substansiSections)): ?>
                                        <button
                                            class="btn btn-sm btn-outline-secondary admin-collapse-toggle collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#proposalStructureCollapse"
                                            aria-expanded="false"
                                            aria-controls="proposalStructureCollapse">
                                            <span class="badge text-bg-light border"><?= esc((string) $substansiSectionCount) ?> bagian</span>
                                            <span class="admin-collapse-toggle__label">Buka Struktur</span>
                                            <i class="bi bi-chevron-down admin-collapse-toggle__icon" aria-hidden="true"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <?php if (empty($substansiSections)): ?>
                                    <div class="admin-empty-state py-4">
                                        <i class="bi bi-layout-text-window"></i>
                                        <p class="mb-0 fw-semibold text-body-emphasis">Belum ada bagian substansi yang tersimpan.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="collapse" id="proposalStructureCollapse">
                                        <div class="admin-proposal-stack">
                                            <?php foreach ($substansiSections as $section): ?>
                                                <div class="admin-proposal-section-block">
                                                    <div class="admin-proposal-section__header">
                                                        <div>
                                                            <div class="small text-uppercase text-muted mb-1">Bagian <?= esc((string) $section['number']) ?></div>
                                                            <h4 class="h6 mb-0"><?= esc($section['title']) ?></h4>
                                                        </div>
                                                    </div>
                                                    <div class="admin-proposal-rich mt-3"><?= $section['content_html'] ?></div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card admin-panel-card mt-3">
                            <div class="card-body">
                                <div class="admin-proposal-section__header mb-3">
                                    <div>
                                        <div class="small text-uppercase text-muted mb-1">Data Pendukung</div>
                                        <h3 class="h5 mb-0">Informasi Jurnal</h3>
                                    </div>
                                </div>
                                <div class="admin-summary-grid admin-summary-grid--compact mb-3">
                                    <?php foreach ($journalInfo['items'] as $item): ?>
                                        <div class="admin-detail-item">
                                            <div class="admin-detail-item__label"><?= esc((string) ($item['label'] ?? '')) ?></div>
                                            <div class="admin-detail-item__value"><?= esc((string) ($item['value'] ?? '')) ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (!empty($journalInfo['links'])): ?>
                                    <div class="admin-link-stack">
                                        <?php foreach ($journalInfo['links'] as $link): ?>
                                            <a href="<?= esc((string) ($link['url'] ?? '#')) ?>" target="_blank" rel="noopener noreferrer" class="admin-link-chip">
                                                <span class="admin-link-chip__label"><?= esc((string) ($link['label'] ?? '')) ?></span>
                                                <span class="admin-link-chip__value"><?= esc((string) ($link['value'] ?? '')) ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card admin-panel-card mt-3">
                            <div class="card-body">
                                <div class="admin-proposal-section__header mb-3">
                                    <div>
                                        <div class="small text-uppercase text-muted mb-1">Tim Proposal</div>
                                        <h3 class="h5 mb-0">Komposisi Peneliti dan Mitra</h3>
                                    </div>
                                    <button
                                        class="btn btn-sm btn-outline-secondary admin-collapse-toggle collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#proposalTeamCollapse"
                                        aria-expanded="false"
                                        aria-controls="proposalTeamCollapse">
                                        <span class="badge text-bg-light border"><?= esc((string) $teamSectionCount) ?> grup</span>
                                        <span class="admin-collapse-toggle__label">Buka Tim</span>
                                        <i class="bi bi-chevron-down admin-collapse-toggle__icon" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="collapse" id="proposalTeamCollapse">
                                    <div class="admin-proposal-stack">
                                        <?php foreach ($teamSections as $section): ?>
                                            <div class="admin-proposal-section-block">
                                                <h4 class="h6 mb-3"><?= esc($section['title']) ?></h4>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm align-middle admin-team-table mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <?php foreach ($section['headers'] as $header): ?>
                                                                    <th><?= esc($header) ?></th>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (empty($section['rows'])): ?>
                                                                <tr>
                                                                    <td colspan="<?= esc((string) $section['colspan']) ?>" class="text-center text-muted"><?= esc($section['empty_message']) ?></td>
                                                                </tr>
                                                            <?php else: ?>
                                                                <?php foreach ($section['rows'] as $row): ?>
                                                                    <tr>
                                                                        <?php foreach ($row['cells'] as $cell): ?>
                                                                            <td><?= esc((string) $cell) ?></td>
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
                                </div>
                            </div>
                        </div>

                        <div class="card admin-panel-card mt-3">
                            <div class="card-body">
                                <div class="admin-proposal-section__header mb-3">
                                    <div>
                                        <div class="small text-uppercase text-muted mb-1">Berkas</div>
                                        <h3 class="h5 mb-0">Dokumen Proposal</h3>
                                    </div>
                                    <button
                                        class="btn btn-sm btn-outline-secondary admin-collapse-toggle collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#proposalDocumentCollapse"
                                        aria-expanded="false"
                                        aria-controls="proposalDocumentCollapse">
                                        <span class="badge text-bg-light border"><?= esc((string) $documentRowCount) ?> dokumen</span>
                                        <span class="admin-collapse-toggle__label">Buka Dokumen</span>
                                        <i class="bi bi-chevron-down admin-collapse-toggle__icon" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="collapse" id="proposalDocumentCollapse">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Jenis Dokumen</th>
                                                    <th>Nama File</th>
                                                    <th>Ukuran</th>
                                                    <th style="width:110px">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($documentRows as $row): ?>
                                                    <tr>
                                                        <td><?= esc($row['label']) ?></td>
                                                        <td><?= esc($row['file_name']) ?></td>
                                                        <td><?= esc($row['file_size_label']) ?></td>
                                                        <td>
                                                            <?php if (!empty($row['view_url'])): ?>
                                                                <a href="<?= esc($row['view_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm">
                                                                    <i class="bi bi-box-arrow-up-right me-1"></i>Lihat
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="text-muted small">Belum ada file</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="proposalShowResultPane" role="tabpanel" aria-labelledby="proposalShowResultTab" tabindex="0">
                        <div class="card admin-panel-card mt-0">
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="small text-uppercase text-muted mb-1">Hasil Review</div>
                                        <h3 class="h5 mb-0">Komentar dan Nilai Reviewer</h3>
                                    </div>
                                    <span class="badge text-bg-light border"><?= esc((string) count($reviewerResultItems)) ?> reviewer</span>
                                </div>

                                <ul class="nav nav-tabs mt-3" id="proposalReviewerResultTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="proposalReviewerResultListTab" data-bs-toggle="tab" data-bs-target="#proposalReviewerResultListPane" type="button" role="tab" aria-controls="proposalReviewerResultListPane" aria-selected="true">
                                            <i class="bi bi-chat-square-text me-1"></i>Hasil Reviewer
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="proposalReviewerPresentasiTab" data-bs-toggle="tab" data-bs-target="#proposalReviewerPresentasiPane" type="button" role="tab" aria-controls="proposalReviewerPresentasiPane" aria-selected="false">
                                            <i class="bi bi-easel2 me-1"></i>Penilaian Presentasi
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content pt-3" id="proposalReviewerResultTabsContent">
                                    <div class="tab-pane fade show active" id="proposalReviewerResultListPane" role="tabpanel" aria-labelledby="proposalReviewerResultListTab" tabindex="0">
                                        <?php if (empty($reviewerResultItems)): ?>
                                            <div class="admin-empty-state py-4">
                                                <i class="bi bi-inbox"></i>
                                                <p class="mb-0 fw-semibold text-body-emphasis">Belum ada hasil review reviewer.</p>
                                            </div>
                                        <?php else: ?>
                                            <div class="admin-proposal-stack">
                                                <?php foreach ($reviewerResultItems as $reviewer): ?>
                                                    <div class="admin-reviewer-card admin-reviewer-card--assigned">
                                                        <div class="admin-reviewer-card__body">
                                                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                                <div>
                                                                    <div class="fw-semibold"><?= esc($reviewer['reviewer_name']) ?></div>
                                                                    <div class="small text-muted"><?= esc($reviewer['reviewer_email']) ?></div>
                                                                </div>
                                                                <div class="d-flex flex-column align-items-end gap-2">
                                                                    <span class="badge <?= esc($reviewer['status_badge_class']) ?>"><?= esc($reviewer['status_label']) ?></span>
                                                                    <span class="badge text-bg-dark">Nilai: <?= esc((string) ($reviewer['review_score_display'] ?? '-')) ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="small text-muted mb-1">Bidang Ilmu</div>
                                                            <div class="mb-3"><?= esc($reviewer['reviewer_bidang_ilmu']) ?></div>

                                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                                <span class="badge <?= esc($reviewer['recommendation_badge_class']) ?>"><?= esc($reviewer['recommendation_label']) ?></span>
                                                                <span class="badge text-bg-light border">Diperbarui: <?= esc((string) ($reviewer['reviewed_at_label'] ?? '-')) ?></span>
                                                            </div>

                                                            <div class="admin-note-box">
                                                                <div class="admin-note-box__title">Komentar Reviewer</div>
                                                                <div class="admin-note-box__body"><?= nl2br(esc((string) ($reviewer['review_notes'] ?? 'Belum ada catatan reviewer.'))) ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="tab-pane fade" id="proposalReviewerPresentasiPane" role="tabpanel" aria-labelledby="proposalReviewerPresentasiTab" tabindex="0">
                                        <div class="admin-note-box mb-3">
                                            <div class="admin-note-box__title">Kesiapan Tahap Presentasi</div>
                                            <div class="admin-note-box__body"><?= esc((string) ($reviewerResultsPanel['completion_message'] ?? '')) ?></div>
                                        </div>

                                        <?php if (!empty($reviewerResultsPanel['all_reviewed'])): ?>
                                            <a href="<?= esc((string) ($reviewerResultsPanel['presentasi_url'] ?? '#')) ?>" class="btn btn-success w-100">
                                                <i class="bi bi-easel2 me-1"></i><?= esc((string) ($reviewerResultsPanel['presentasi_label'] ?? 'Buka Penilaian Presentasi')) ?>
                                            </a>
                                            <small class="text-muted d-block mt-2"><?= esc((string) ($reviewerResultsPanel['presentasi_hint'] ?? '')) ?></small>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-outline-secondary w-100" disabled>
                                                <i class="bi bi-lock me-1"></i><?= esc((string) ($reviewerResultsPanel['presentasi_label'] ?? 'Buka Penilaian Presentasi')) ?>
                                            </button>
                                            <small class="text-muted d-block mt-2"><?= esc((string) ($reviewerResultsPanel['presentasi_hint'] ?? '')) ?></small>
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
        <div class="card admin-panel-card admin-assignment-card">
            <div class="card-body">
                <div class="admin-proposal-section__header mb-3">
                    <div>
                        <div class="small text-uppercase text-muted mb-1">Penugasan Reviewer</div>
                        <h3 class="h5 mb-0">Reviewer Aktif</h3>
                    </div>
                </div>

                <?php if (empty($assignmentPanel['assigned_reviewers'])): ?>
                    <div class="admin-empty-state py-4">
                        <i class="bi bi-clipboard-x"></i>
                        <p class="mb-0 fw-semibold text-body-emphasis">Belum ada reviewer yang ditugaskan.</p>
                    </div>
                <?php else: ?>
                    <div class="admin-proposal-stack mb-3">
                        <?php foreach ($assignmentPanel['assigned_reviewers'] as $reviewer): ?>
                            <div class="admin-reviewer-card admin-reviewer-card--assigned">
                                <div class="admin-reviewer-card__body">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                        <div>
                                            <div class="fw-semibold"><?= esc($reviewer['reviewer_name']) ?></div>
                                            <div class="small text-muted"><?= esc($reviewer['reviewer_email']) ?></div>
                                        </div>
                                        <span class="badge <?= esc($reviewer['status_badge_class']) ?>"><?= esc($reviewer['status_label']) ?></span>
                                    </div>
                                    <div class="small text-muted mb-1">Bidang Ilmu</div>
                                    <div class="mb-2"><?= esc($reviewer['reviewer_bidang_ilmu']) ?></div>
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <span class="badge <?= esc($reviewer['recommendation_badge_class']) ?>"><?= esc($reviewer['recommendation_label']) ?></span>
                                    </div>
                                    <div class="small text-muted mb-1">Catatan Reviewer</div>
                                    <div class="small text-body mb-3"><?= esc($reviewer['review_notes']) ?></div>
                                    <form action="<?= esc($reviewer['remove_url']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash me-1"></i><?= esc($assignmentPanel['remove_button_label']) ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="admin-proposal-section__header mb-3 mt-4">
                    <div>
                        <div class="small text-uppercase text-muted mb-1">Pilih Reviewer</div>
                        <h3 class="h6 mb-0">Tambahkan Reviewer Baru</h3>
                    </div>
                </div>
                <p class="text-muted small mb-3"><?= esc($assignmentPanel['assignment_hint']) ?></p>

                <?php if (!$assignmentPanel['has_candidates']): ?>
                    <div class="admin-empty-state py-4">
                        <i class="bi bi-people"></i>
                        <p class="mb-0 fw-semibold text-body-emphasis"><?= esc($assignmentPanel['empty_message']) ?></p>
                    </div>
                <?php else: ?>
                    <form action="<?= esc($assignmentPanel['form_action']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="admin-proposal-stack">
                            <?php if (!empty($recommendedReviewers)): ?>
                                <div class="admin-reviewer-group">
                                    <div class="small text-uppercase text-muted mb-2">Rekomendasi Sistem</div>
                                    <div class="admin-assignment-note mb-3">
                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-semibold text-body-emphasis">Prioritas otomatis ditampilkan lebih dulu</div>
                                                <div class="small text-muted">Sistem hanya menampilkan reviewer paling relevan di tampilan awal agar panel tetap ringkas.</div>
                                            </div>
                                            <span class="badge text-bg-success"><?= esc((string) ($assignmentPanel['recommended_total'] ?? count($recommendedReviewers))) ?> prioritas</span>
                                        </div>
                                        <?php if ($recommendedHiddenCount > 0): ?>
                                            <div class="small text-muted mt-2"><?= esc((string) $recommendedHiddenCount) ?> kandidat relevan lain tetap tersedia di pencarian manual.</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="admin-proposal-stack">
                                        <?php foreach ($recommendedReviewers as $reviewer): ?>
                                            <label class="admin-reviewer-card admin-reviewer-card--recommended">
                                                <div class="admin-reviewer-card__selector">
                                                    <input class="form-check-input" type="checkbox" name="reviewer_ids[]" value="<?= esc((string) $reviewer['id']) ?>">
                                                </div>
                                                <div class="admin-reviewer-card__body">
                                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                        <div>
                                                            <div class="fw-semibold"><?= esc($reviewer['name']) ?></div>
                                                            <div class="small text-muted"><?= esc($reviewer['email']) ?></div>
                                                        </div>
                                                        <span class="badge <?= esc($reviewer['fit_badge_class']) ?>"><?= esc($reviewer['fit_label']) ?></span>
                                                    </div>
                                                    <div class="small text-muted mb-1">Bidang Ilmu</div>
                                                    <div><?= esc($reviewer['bidang_ilmu']) ?></div>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($manualReviewers)): ?>
                                <div class="admin-reviewer-group">
                                    <div class="small text-uppercase text-muted mb-2">Pencarian Manual</div>
                                    <div class="admin-assignment-note mb-3">
                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                            <div>
                                                <div class="fw-semibold text-body-emphasis">Buka kandidat lain saat diperlukan</div>
                                                <div class="small text-muted"><?= esc($assignmentPanel['manual_hint'] ?? '') ?></div>
                                            </div>
                                            <span class="badge text-bg-light border"><span id="manualReviewerVisibleCount"><?= esc((string) $manualReviewerCount) ?></span> reviewer</span>
                                        </div>
                                    </div>

                                    <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#manualReviewerCollapse" aria-expanded="false" aria-controls="manualReviewerCollapse">
                                        <span><i class="bi bi-search me-2"></i>Lihat reviewer lain</span>
                                        <span class="badge text-bg-secondary"><?= esc((string) $manualReviewerCount) ?></span>
                                    </button>

                                    <div class="collapse mt-3" id="manualReviewerCollapse">
                                        <div class="admin-reviewer-search-panel">
                                            <label for="manualReviewerSearch" class="form-label small text-muted fw-semibold">Cari reviewer</label>
                                            <input
                                                type="search"
                                                id="manualReviewerSearch"
                                                class="form-control"
                                                placeholder="Cari nama, email, atau bidang ilmu"
                                                data-reviewer-filter-input="#manualReviewerList"
                                                data-reviewer-empty-target="#manualReviewerEmpty"
                                                data-reviewer-count-target="#manualReviewerVisibleCount">
                                        </div>

                                        <div class="admin-proposal-stack admin-reviewer-scroll mt-3" id="manualReviewerList">
                                            <?php foreach ($manualReviewers as $reviewer): ?>
                                                <label class="admin-reviewer-card" data-reviewer-card-filter-item data-reviewer-search="<?= esc(strtolower(trim(($reviewer['name'] ?? '') . ' ' . ($reviewer['email'] ?? '') . ' ' . ($reviewer['bidang_ilmu'] ?? '')))) ?>">
                                                    <div class="admin-reviewer-card__selector">
                                                        <input class="form-check-input" type="checkbox" name="reviewer_ids[]" value="<?= esc((string) $reviewer['id']) ?>">
                                                    </div>
                                                    <div class="admin-reviewer-card__body">
                                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                            <div>
                                                                <div class="fw-semibold"><?= esc($reviewer['name']) ?></div>
                                                                <div class="small text-muted"><?= esc($reviewer['email']) ?></div>
                                                            </div>
                                                            <span class="badge <?= esc($reviewer['fit_badge_class']) ?>"><?= esc($reviewer['fit_label']) ?></span>
                                                        </div>
                                                        <div class="small text-muted mb-1">Bidang Ilmu</div>
                                                        <div><?= esc($reviewer['bidang_ilmu']) ?></div>
                                                    </div>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>

                                        <div id="manualReviewerEmpty" class="admin-empty-state py-4 d-none mt-3">
                                            <i class="bi bi-search"></i>
                                            <p class="mb-0 fw-semibold text-body-emphasis">Tidak ada reviewer yang cocok dengan pencarian.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-3">
                            <label for="assignment_notes" class="form-label small text-muted fw-semibold">Catatan Penugasan</label>
                            <textarea id="assignment_notes" name="assignment_notes" class="form-control" rows="3" placeholder="Opsional. Catatan ini akan tersimpan pada assignment reviewer yang dipilih."></textarea>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus me-1"></i>Tugaskan Reviewer Terpilih
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="card admin-panel-card mt-3">
            <div class="card-body">
                <div class="admin-proposal-section__header mb-3">
                    <div>
                        <div class="small text-uppercase text-muted mb-1">Ringkasan Keputusan</div>
                        <h3 class="h5 mb-0">Kesiapan Keputusan Akhir Admin</h3>
                    </div>
                </div>
                <div class="admin-decision-grid mb-3">
                    <?php foreach ($decisionSummary['cards'] as $card): ?>
                        <div class="admin-detail-item">
                            <div class="admin-detail-item__label"><?= esc($card['label']) ?></div>
                            <div class="admin-detail-item__value <?= esc($card['tone_class']) ?>"><?= esc($card['value']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="admin-note-box">
                    <div class="admin-note-box__title">Catatan</div>
                    <div class="admin-note-box__body"><?= esc($decisionSummary['note']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?php $this->section('scripts'); ?>
<script src="<?= base_url('custom/js/admin-proposal-show.js') ?>"></script>
<?php $this->endSection(); ?>