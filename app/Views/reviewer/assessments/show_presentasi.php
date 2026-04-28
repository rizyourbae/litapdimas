<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$hero = isset($hero) && is_array($hero) ? $hero : [];
$detail = isset($detail) && is_array($detail) ? $detail : [];
$proposal = isset($proposal) && is_array($proposal) ? $proposal : [];
$presentation = isset($presentation) && is_array($presentation) ? $presentation : [];
$summaryItems = isset($proposal['summary_items']) && is_array($proposal['summary_items']) ? $proposal['summary_items'] : [];
$sections = isset($proposal['substansi_sections']) && is_array($proposal['substansi_sections']) ? $proposal['substansi_sections'] : [];
$abstract = isset($proposal['abstract']) && is_array($proposal['abstract']) ? $proposal['abstract'] : [];
$scoringSections = isset($presentation['sections']) && is_array($presentation['sections']) ? $presentation['sections'] : [];
$generalComment = isset($presentation['general_comment']) && is_array($presentation['general_comment']) ? $presentation['general_comment'] : [];
$validatorNote = isset($presentation['validator_note']) && is_array($presentation['validator_note']) ? $presentation['validator_note'] : [];
$totals = isset($presentation['totals']) && is_array($presentation['totals']) ? $presentation['totals'] : [];
$form = isset($presentation['form']) && is_array($presentation['form']) ? $presentation['form'] : [];
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
                        <h2 class="h3 admin-hero__title mb-2"><?= esc((string) ($hero['title'] ?? 'Penilaian Presentasi')) ?></h2>
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

    <div class="col-lg-7">
        <div class="card admin-panel-card h-100">
            <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-start gap-3 flex-wrap">
                <div>
                    <h3 class="card-title mb-1">
                        <i class="bi bi-file-earmark-text me-2"></i><?= esc((string) ($proposal['summary_card_title'] ?? 'Ringkasan Proposal untuk Presentasi')) ?>
                    </h3>
                    <p class="text-muted small mb-0">Materi ini berasal dari proposal yang sudah selesai di tahap review proposal.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge text-bg-light border">Skor proposal: <?= esc((string) ($proposal['proposal_score_value'] ?? '-')) ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="admin-summary-grid admin-summary-grid--compact mb-3">
                    <?php foreach ($summaryItems as $item): ?>
                        <div class="admin-detail-item">
                            <div class="admin-detail-item__label"><?= esc((string) ($item['label'] ?? '')) ?></div>
                            <div class="admin-detail-item__value"><?= esc((string) ($item['value'] ?? '')) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="admin-note-box mb-3">
                    <div class="admin-note-box__title"><?= esc((string) ($proposal['proposal_score_label'] ?? 'Skor Review Proposal')) ?></div>
                    <div class="admin-note-box__body"><?= esc((string) ($proposal['proposal_score_value'] ?? '-')) ?></div>
                </div>

                <div class="admin-note-box mb-4">
                    <div class="admin-note-box__title"><?= esc((string) ($proposal['proposal_notes_label'] ?? 'Catatan Review Proposal')) ?></div>
                    <div class="admin-note-box__body"><?= esc((string) ($proposal['proposal_notes_value'] ?? 'Belum ada catatan review proposal.')) ?></div>
                </div>

                <div class="admin-note-box mb-3">
                    <div class="admin-note-box__title">
                        <i class="bi bi-card-text me-1"></i><?= esc((string) ($abstract['title'] ?? 'Abstrak Proposal')) ?>
                    </div>
                    <?php if (!empty($abstract['html'])): ?>
                        <div class="admin-note-box__body admin-proposal-rich"><?= $abstract['html'] ?></div>
                    <?php else: ?>
                        <div class="admin-note-box__body"><?= esc((string) ($abstract['empty_message'] ?? 'Abstrak proposal belum tersedia.')) ?></div>
                    <?php endif; ?>
                </div>

                <div class="admin-proposal-stack">
                    <?php foreach ($sections as $section): ?>
                        <div class="reviewer-proposal-section-card">
                            <div class="reviewer-proposal-section-card__header">
                                <h4 class="h6 mb-0"><?= esc((string) ($section['title'] ?? 'Bagian Proposal')) ?></h4>
                            </div>
                            <div class="admin-proposal-rich"><?= $section['content_html'] ?? '' ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-primary card-outline admin-table-card h-100 reviewer-proposal-form-card">
            <div class="card-header border-0 pb-0">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div>
                        <h3 class="card-title mb-1">
                            <i class="bi bi-easel2 me-2"></i><?= esc((string) ($presentation['card_title'] ?? 'Penilaian Presentasi')) ?>
                        </h3>
                        <p class="text-muted small mb-0">Isi form ini untuk tahap presentasi. Aspek dan komentarnya berbeda dari penilaian proposal.</p>
                    </div>
                    <span class="badge text-bg-light border"><?= esc((string) count($scoringSections)) ?> aspek</span>
                </div>
            </div>
            <form action="<?= esc((string) ($form['action_url'] ?? '#')) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="reviewer-proposal-stack">
                        <?php foreach ($scoringSections as $section): ?>
                            <div class="reviewer-proposal-section-card">
                                <div class="reviewer-proposal-section-card__header">
                                    <h4 class="h6 mb-0"><?= esc((string) ($section['title'] ?? 'Aspek Presentasi')) ?></h4>
                                </div>
                                <div class="reviewer-proposal-field mb-3">
                                    <label class="form-label reviewer-proposal-field__label">Nilai <span class="text-danger">*</span></label>
                                    <select name="<?= esc((string) ($section['score_field_name'] ?? 'scores[]')) ?>" class="form-select" required>
                                        <option value="">Pilih nilai</option>
                                        <?php foreach (($section['options'] ?? []) as $option): ?>
                                            <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= ((string) ($section['score_value'] ?? '') === (string) ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                                <?= esc((string) ($option['label'] ?? '')) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="reviewer-proposal-field">
                                    <label class="form-label reviewer-proposal-field__label">Komentar Aspek</label>
                                    <textarea name="<?= esc((string) ($section['comment_field_name'] ?? 'comments[]')) ?>" class="form-control reviewer-proposal-textarea" rows="3" placeholder="Catatan untuk aspek ini..."><?= esc((string) ($section['comment_value'] ?? '')) ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="reviewer-proposal-section-card">
                            <div class="reviewer-proposal-field">
                                <label class="form-label reviewer-proposal-field__label"><?= esc((string) ($generalComment['label'] ?? 'Komentar Umum Presentasi')) ?></label>
                                <textarea name="<?= esc((string) ($generalComment['field_name'] ?? 'general_comment')) ?>" class="form-control reviewer-proposal-textarea" rows="4" placeholder="Tuliskan komentar umum untuk presentasi..."><?= esc((string) ($generalComment['value'] ?? '')) ?></textarea>
                            </div>

                            <div class="reviewer-proposal-field mt-4">
                                <label class="form-label reviewer-proposal-field__label"><?= esc((string) ($validatorNote['label'] ?? 'Catatan Validator Presentasi')) ?><span class="text-danger">*</span></label>
                                <textarea name="<?= esc((string) ($validatorNote['field_name'] ?? 'validator_note')) ?>" class="form-control reviewer-proposal-textarea" rows="4" required><?= esc((string) ($validatorNote['value'] ?? '')) ?></textarea>
                                <small class="text-muted d-block mt-2"><?= esc((string) ($validatorNote['hint'] ?? '')) ?></small>
                            </div>
                        </div>

                        <div class="admin-note-box">
                            <div class="admin-note-box__title"><?= esc((string) ($totals['normalized_label'] ?? 'Nilai Akhir Presentasi')) ?></div>
                            <div class="reviewer-proposal-total-value reviewer-proposal-total-value--accent"><?= esc((string) ($totals['normalized_value'] ?? '-')) ?></div>
                            <small class="text-muted d-block mt-2"><?= esc((string) ($totals['hint'] ?? '')) ?></small>
                        </div>
                    </div>
                </div>

                <div class="card-footer admin-form-footer reviewer-proposal-form-footer d-flex flex-column gap-3">
                    <div class="text-muted small"><?= esc((string) ($form['helper_text'] ?? '')) ?></div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-save me-1"></i><?= esc((string) ($form['submit_label'] ?? 'Simpan Penilaian Presentasi')) ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>