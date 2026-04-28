<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$hero = isset($hero) && is_array($hero) ? $hero : [];
$detail = isset($detail) && is_array($detail) ? $detail : [];
$presentation = isset($presentation) && is_array($presentation) ? $presentation : [];
$scoringSections = isset($presentation['sections']) && is_array($presentation['sections']) ? $presentation['sections'] : [];
$generalComment = isset($presentation['general_comment']) && is_array($presentation['general_comment']) ? $presentation['general_comment'] : [];
$validatorNote = isset($presentation['validator_note']) && is_array($presentation['validator_note']) ? $presentation['validator_note'] : [];
$initialBudget = isset($presentation['initial_budget']) && is_array($presentation['initial_budget']) ? $presentation['initial_budget'] : [];
$recommendedBudget = isset($presentation['recommended_budget']) && is_array($presentation['recommended_budget']) ? $presentation['recommended_budget'] : [];
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

    <div class="col-12">
        <div class="card card-primary card-outline admin-table-card reviewer-proposal-form-card">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title mb-1"><?= esc((string) ($presentation['card_title'] ?? 'Formulir Penilaian Presentasi')) ?></h3>
            </div>
            <form action="<?= esc((string) ($form['action_url'] ?? '#')) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="reviewer-proposal-stack">
                        <div class="reviewer-proposal-section-card">
                            <div class="row g-4 reviewer-presentation-form-grid">
                                <?php foreach ($scoringSections as $section): ?>
                                    <div class="col-md-6 reviewer-presentation-score-field">
                                        <label class="form-label reviewer-proposal-field__label"><?= esc((string) ($section['title'] ?? 'Aspek Presentasi')) ?><span class="text-danger">*</span></label>
                                        <select name="<?= esc((string) ($section['score_field_name'] ?? 'scores[]')) ?>" class="form-select" required>
                                            <option value=""><?= esc((string) ($section['placeholder'] ?? 'Pilih salah satu opsi')) ?></option>
                                            <?php foreach (($section['options'] ?? []) as $option): ?>
                                                <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= ((string) ($section['score_value'] ?? '') === (string) ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                                    <?= esc((string) ($option['label'] ?? '')) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="<?= esc((string) ($section['comment_field_name'] ?? 'comments[]')) ?>" value="<?= esc((string) ($section['comment_value'] ?? '')) ?>">
                                    </div>
                                <?php endforeach; ?>

                                <div class="col-md-6 reviewer-presentation-budget-display">
                                    <div class="reviewer-proposal-field__label mb-2"><?= esc((string) ($initialBudget['label'] ?? 'Usulan Anggaran Awal')) ?></div>
                                    <div class="reviewer-presentation-budget-value"><?= esc((string) ($initialBudget['value'] ?? '-')) ?></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="recommendedBudget" class="form-label reviewer-proposal-field__label"><?= esc((string) ($recommendedBudget['label'] ?? 'Rekomendasi Anggaran yang Disetujui')) ?><span class="text-danger">*</span></label>
                                    <div class="input-group reviewer-presentation-budget-input">
                                        <span class="input-group-text">Rp</span>
                                        <input
                                            type="text"
                                            id="recommendedBudget"
                                            name="<?= esc((string) ($recommendedBudget['field_name'] ?? 'recommended_budget')) ?>"
                                            class="form-control"
                                            inputmode="numeric"
                                            placeholder="0"
                                            value="<?= esc((string) ($recommendedBudget['value'] ?? '')) ?>"
                                            required>
                                    </div>
                                    <small class="text-muted d-block mt-2"><?= esc((string) ($recommendedBudget['hint'] ?? '')) ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="reviewer-proposal-section-card">
                            <div class="reviewer-proposal-field">
                                <label class="form-label reviewer-proposal-field__label"><?= esc((string) ($generalComment['label'] ?? 'Komentar Umum Presentasi')) ?></label>
                                <div
                                    id="<?= esc((string) ($generalComment['editor_id'] ?? 'reviewer-presentation-general-comment')) ?>"
                                    class="reviewer-proposal-editor reviewer-proposal-editor--large reviewer-presentation-editor"
                                    data-reviewer-quill
                                    data-reviewer-hidden-input="#<?= esc((string) ($generalComment['input_id'] ?? 'reviewer-presentation-general-comment-input')) ?>"
                                    data-reviewer-placeholder="Tuliskan komentar umum presentasi..."><?= $generalComment['value'] ?? '' ?></div>
                                <input type="hidden" id="<?= esc((string) ($generalComment['input_id'] ?? 'reviewer-presentation-general-comment-input')) ?>" name="<?= esc((string) ($generalComment['field_name'] ?? 'general_comment')) ?>" value="<?= esc((string) ($generalComment['value'] ?? '')) ?>">
                            </div>

                            <input type="hidden" name="<?= esc((string) ($validatorNote['field_name'] ?? 'validator_note')) ?>" value="<?= esc((string) ($validatorNote['value'] ?? '')) ?>">
                        </div>
                    </div>
                </div>

                <div class="card-footer admin-form-footer reviewer-proposal-form-footer d-flex flex-column gap-3">
                    <div class="text-muted small"><?= esc((string) ($form['helper_text'] ?? ($totals['hint'] ?? ''))) ?></div>
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

<?= $this->section('scripts') ?>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="<?= base_url('custom/js/reviewer-proposal-form.js') ?>?v=20260428-01"></script>
<?= $this->endSection() ?>