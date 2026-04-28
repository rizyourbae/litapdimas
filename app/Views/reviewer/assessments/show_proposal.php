<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$hero = isset($hero) && is_array($hero) ? $hero : [];
$detail = isset($detail) && is_array($detail) ? $detail : [];
$proposal = isset($proposal) && is_array($proposal) ? $proposal : [];
$summaryItems = isset($proposal['summary_items']) && is_array($proposal['summary_items']) ? $proposal['summary_items'] : [];
$detailTabs = isset($proposal['detail_tabs']) && is_array($proposal['detail_tabs']) ? $proposal['detail_tabs'] : [];
$review = isset($proposal['review']) && is_array($proposal['review']) ? $proposal['review'] : [];
$reviewSections = isset($review['sections']) && is_array($review['sections']) ? $review['sections'] : [];
$scoring = isset($proposal['scoring']) && is_array($proposal['scoring']) ? $proposal['scoring'] : [];
$form = isset($proposal['form']) && is_array($proposal['form']) ? $proposal['form'] : [];
$aspects = isset($scoring['aspects']) && is_array($scoring['aspects']) ? $scoring['aspects'] : [];
$totals = isset($scoring['totals']) && is_array($scoring['totals']) ? $scoring['totals'] : [];
$generalComment = isset($scoring['general_comment']) && is_array($scoring['general_comment']) ? $scoring['general_comment'] : [];
$validatorNote = isset($scoring['validator_note']) && is_array($scoring['validator_note']) ? $scoring['validator_note'] : [];
?>

<div class="row g-3 admin-page reviewer-proposal-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <?php foreach (($hero['badges'] ?? []) as $badge): ?>
                                <span class="badge <?= esc((string) ($badge['class'] ?? 'text-bg-light border')) ?> px-3 py-2"><?= esc((string) ($badge['label'] ?? '')) ?></span>
                            <?php endforeach; ?>
                            <span class="badge <?= esc((string) ($detail['review_status_badge_class'] ?? 'text-bg-light border')) ?> px-3 py-2"><?= esc((string) ($detail['review_status_label'] ?? 'Belum Dinilai')) ?></span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc((string) ($hero['title'] ?? 'Penilaian Proposal')) ?></h2>
                        <p class="admin-hero__subtitle mb-0"><?= esc((string) ($hero['subtitle'] ?? '')) ?></p>
                    </div>
                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= esc((string) ($detail['back_url'] ?? '#')) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card admin-panel-card reviewer-proposal-summary-card">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title mb-0">
                    <i class="bi bi-journal-richtext me-2"></i><?= esc((string) ($proposal['summary_card_title'] ?? 'Ringkasan Usulan')) ?>
                </h3>
            </div>
            <div class="card-body">
                <div class="reviewer-proposal-summary-grid">
                    <?php foreach ($summaryItems as $item): ?>
                        <div class="reviewer-proposal-summary-item">
                            <div class="reviewer-proposal-summary-item__label"><?= esc((string) ($item['label'] ?? '')) ?></div>
                            <div class="reviewer-proposal-summary-item__value"><?= esc((string) ($item['value'] ?? '')) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-primary card-outline admin-table-card card-outline-tabs reviewer-proposal-form-card">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs reviewer-proposal-tabs" id="reviewerProposalDetailTabs" role="tablist">
                    <?php foreach ($detailTabs as $tab): ?>
                        <li class="nav-item" role="presentation">
                            <button
                                class="<?= esc((string) ($tab['button_class'] ?? 'nav-link')) ?>"
                                id="<?= esc((string) ($tab['button_id'] ?? '')) ?>"
                                data-bs-toggle="tab"
                                data-bs-target="#<?= esc((string) ($tab['pane_id'] ?? '')) ?>"
                                type="button"
                                role="tab"
                                aria-controls="<?= esc((string) ($tab['pane_id'] ?? '')) ?>"
                                aria-selected="<?= esc((string) ($tab['aria_selected'] ?? 'false')) ?>">
                                <i class="<?= esc((string) ($tab['icon'] ?? 'bi bi-circle')) ?> me-1"></i><?= esc((string) ($tab['label'] ?? '')) ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <form action="<?= esc((string) ($form['action_url'] ?? '#')) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="tab-content" id="reviewerProposalDetailTabsContent">
                        <div class="<?= esc((string) (($detailTabs[0]['pane_class'] ?? 'tab-pane fade show active'))) ?>" id="<?= esc((string) ($detailTabs[0]['pane_id'] ?? 'reviewer-detail-pane-review')) ?>" role="tabpanel" aria-labelledby="<?= esc((string) ($detailTabs[0]['button_id'] ?? 'reviewer-detail-tab-review')) ?>" tabindex="0">
                            <div class="reviewer-proposal-stack">
                                <div class="reviewer-proposal-section-card">
                                    <div class="reviewer-proposal-section-card__header">
                                        <div>
                                            <h3 class="h5 mb-1"><?= esc((string) ($review['card_title'] ?? 'Review Isian Substansi')) ?></h3>
                                            <p class="text-muted small mb-0">Telaah isi proposal sesuai naskah yang diunggah dosen, lalu berikan catatan reviewer pada setiap bagian yang perlu diperjelas.</p>
                                        </div>
                                    </div>
                                </div>

                                <?php foreach ($reviewSections as $section): ?>
                                    <div class="reviewer-proposal-section-card">
                                        <div class="reviewer-proposal-section-card__header">
                                            <h4 class="h6 mb-0"><?= esc((string) ($section['title'] ?? 'Bagian Proposal')) ?></h4>
                                        </div>
                                        <div class="reviewer-proposal-content admin-proposal-rich"><?= $section['content_html'] ?? '' ?></div>
                                        <div class="reviewer-proposal-field">
                                            <label class="form-label reviewer-proposal-field__label"><?= esc((string) ($section['comment_label'] ?? 'Komentar Reviewer')) ?></label>
                                            <div
                                                id="<?= esc((string) ($section['editor_id'] ?? '')) ?>"
                                                class="reviewer-proposal-editor"
                                                data-reviewer-quill
                                                data-reviewer-hidden-input="#<?= esc((string) ($section['input_id'] ?? '')) ?>"
                                                data-reviewer-placeholder="Tuliskan komentar reviewer untuk bagian ini..."><?= $section['comment_value'] ?? '' ?></div>
                                            <input type="hidden" id="<?= esc((string) ($section['input_id'] ?? '')) ?>" name="<?= esc((string) ($section['comment_field_name'] ?? 'comments[]')) ?>" value="<?= esc((string) ($section['comment_value'] ?? '')) ?>">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="<?= esc((string) (($detailTabs[1]['pane_class'] ?? 'tab-pane fade'))) ?>" id="<?= esc((string) ($detailTabs[1]['pane_id'] ?? 'reviewer-detail-pane-scoring')) ?>" role="tabpanel" aria-labelledby="<?= esc((string) ($detailTabs[1]['button_id'] ?? 'reviewer-detail-tab-scoring')) ?>" tabindex="0">
                            <div class="reviewer-proposal-stack">
                                <div class="reviewer-proposal-section-card">
                                    <div class="reviewer-proposal-section-card__header reviewer-proposal-section-card__header--split">
                                        <div>
                                            <h3 class="h5 mb-1"><?= esc((string) ($scoring['card_title'] ?? 'Skor Penilaian')) ?></h3>
                                            <p class="text-muted small mb-0">Setiap aspek menggunakan skala 1 sampai 5. Nilai akhir reviewer mengikuti rumus bobot dikali skor lalu dikonversi ke skala 100.</p>
                                        </div>
                                        <span class="badge text-bg-light border">Skala 1-5</span>
                                    </div>

                                    <div class="reviewer-proposal-total-grid">
                                        <div class="admin-note-box">
                                            <div class="admin-note-box__title"><?= esc((string) ($totals['raw_label'] ?? 'Total Bobot x Skor')) ?></div>
                                            <div class="reviewer-proposal-total-value"><?= esc((string) ($totals['raw_value'] ?? '0')) ?></div>
                                        </div>
                                        <div class="admin-note-box">
                                            <div class="admin-note-box__title"><?= esc((string) ($totals['normalized_label'] ?? 'Nilai Akhir Reviewer')) ?></div>
                                            <div class="reviewer-proposal-total-value reviewer-proposal-total-value--accent"><?= esc((string) ($totals['normalized_value'] ?? '-')) ?></div>
                                        </div>
                                    </div>

                                    <div class="reviewer-proposal-score-grid">
                                        <?php foreach ($aspects as $aspect): ?>
                                            <div class="reviewer-proposal-field">
                                                <label class="form-label reviewer-proposal-field__label"><?= esc((string) ($aspect['label'] ?? 'Aspek Penilaian')) ?><span class="text-danger">*</span></label>
                                                <select name="<?= esc((string) ($aspect['field_name'] ?? 'scores[]')) ?>" class="form-select" required>
                                                    <?php foreach (($aspect['options'] ?? []) as $option): ?>
                                                        <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= (($aspect['selected_value'] ?? '') === ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                                            <?= esc((string) ($option['label'] ?? '')) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="reviewer-proposal-section-card">
                                    <div class="reviewer-proposal-field">
                                        <label class="form-label reviewer-proposal-field__label"><?= esc((string) ($generalComment['label'] ?? 'Komentar Umum Proposal')) ?></label>
                                        <div
                                            id="<?= esc((string) ($generalComment['editor_id'] ?? 'reviewer-general-comment')) ?>"
                                            class="reviewer-proposal-editor reviewer-proposal-editor--large"
                                            data-reviewer-quill
                                            data-reviewer-hidden-input="#<?= esc((string) ($generalComment['input_id'] ?? 'reviewer-general-comment-input')) ?>"
                                            data-reviewer-placeholder="Tuliskan komentar umum reviewer untuk proposal ini..."><?= $generalComment['value'] ?? '' ?></div>
                                        <input type="hidden" id="<?= esc((string) ($generalComment['input_id'] ?? 'reviewer-general-comment-input')) ?>" name="<?= esc((string) ($generalComment['field_name'] ?? 'general_comment')) ?>" value="<?= esc((string) ($generalComment['value'] ?? '')) ?>">
                                    </div>

                                    <div class="reviewer-proposal-field mt-4">
                                        <label class="form-label reviewer-proposal-field__label"><?= esc((string) ($validatorNote['label'] ?? 'Catatan Validator')) ?><span class="text-danger">*</span></label>
                                        <textarea name="<?= esc((string) ($validatorNote['field_name'] ?? 'validator_note')) ?>" class="form-control reviewer-proposal-textarea" rows="4" required><?= esc((string) ($validatorNote['value'] ?? '')) ?></textarea>
                                        <small class="text-muted d-block mt-2"><?= esc((string) ($validatorNote['hint'] ?? '')) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer admin-form-footer reviewer-proposal-form-footer d-flex flex-column gap-3">
                    <div class="text-muted small"><?= esc((string) ($totals['hint'] ?? '')) ?></div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-save me-1"></i><?= esc((string) ($form['submit_label'] ?? 'Simpan Penilaian Proposal')) ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="<?= base_url('custom/js/reviewer-proposal-form.js') ?>?v=20260428-01"></script>
<?= $this->endSection() ?>