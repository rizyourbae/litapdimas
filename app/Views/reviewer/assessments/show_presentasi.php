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
        <?= view('components/ui-hero', [
            'type' => 'reviewer',
            'title' => esc((string) ($hero['title'] ?? 'Penilaian Presentasi')),
            'subtitle' => esc((string) ($hero['subtitle'] ?? 'Berikan skor penilaian presentasi dan rekomendasi anggaran.')),
            'badges' => array_merge(
                [['label' => 'Detail Presentasi', 'class' => 'text-bg-light border']],
                $hero['badges'] ?? [],
                [['label' => esc((string) ($detail['review_status_label'] ?? 'Belum Dinilai')), 'class' => esc((string) ($detail['review_status_badge_class'] ?? 'text-bg-light border'))]]
            ),
            'actions' => '
                <a href="' . esc((string) ($detail['back_url'] ?? '#')) . '" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Antrian
                </a>
            '
        ]) ?>
    </div>

    <div class="col-12">
        <form action="<?= esc((string) ($form['action_url'] ?? '#')) ?>" method="post" id="presentationForm">
            <?= csrf_field() ?>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 overflow-hidden mb-4">
                        <div class="card-header bg-light py-3 px-4">
                            <h5 class="h6 fw-bold mb-0 text-uppercase letter-spacing-1">
                                <i class="bi bi-check2-square me-2 text-primary"></i>Parameter Penilaian Presentasi
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4 mb-4">
                                <?php foreach ($scoringSections as $section): ?>
                                    <div class="col-md-6">
                                        <div class="reviewer-proposal-field">
                                            <label class="form-label fw-bold text-dark small mb-1"><?= esc((string) ($section['title'] ?? 'Aspek Presentasi')) ?><span class="text-danger ms-1">*</span></label>
                                            <select name="<?= esc((string) ($section['score_field_name'] ?? 'scores[]')) ?>" class="form-select form-select-lg shadow-none" required>
                                                <option value=""><?= esc((string) ($section['placeholder'] ?? 'Pilih salah satu opsi')) ?></option>
                                                <?php foreach (($section['options'] ?? []) as $option): ?>
                                                    <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= ((string) ($section['score_value'] ?? '') === (string) ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                                        <?= esc((string) ($option['label'] ?? '')) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="hidden" name="<?= esc((string) ($section['comment_field_name'] ?? 'comments[]')) ?>" value="<?= esc((string) ($section['comment_value'] ?? '')) ?>">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="reviewer-proposal-field">
                                <label class="form-label fw-bold text-dark small mb-2"><?= esc((string) ($generalComment['label'] ?? 'Komentar Umum Presentasi')) ?></label>
                                <div
                                    id="<?= esc((string) ($generalComment['editor_id'] ?? 'reviewer-presentation-general-comment')) ?>"
                                    class="reviewer-proposal-editor bg-white border rounded"
                                    style="min-height: 200px;"
                                    data-reviewer-quill
                                    data-reviewer-hidden-input="#<?= esc((string) ($generalComment['input_id'] ?? 'reviewer-presentation-general-comment-input')) ?>"
                                    data-reviewer-placeholder="Tuliskan masukan atau catatan akhir untuk presentasi ini..."><?= $generalComment['value'] ?? '' ?></div>
                                <input type="hidden" id="<?= esc((string) ($generalComment['input_id'] ?? 'reviewer-presentation-general-comment-input')) ?>" name="<?= esc((string) ($generalComment['field_name'] ?? 'general_comment')) ?>" value="<?= esc((string) ($generalComment['value'] ?? '')) ?>">
                                <input type="hidden" name="<?= esc((string) ($validatorNote['field_name'] ?? 'validator_note')) ?>" value="<?= esc((string) ($validatorNote['value'] ?? '')) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 2rem;">
                        <div class="card border-0 bg-primary text-white shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="small text-uppercase opacity-75 fw-bold mb-3 ls-1">Rekomendasi Anggaran</div>
                                
                                <div class="mb-4">
                                    <div class="small opacity-75 mb-1"><?= esc((string) ($initialBudget['label'] ?? 'Usulan Dosen')) ?></div>
                                    <div class="h4 fw-bold mb-0 text-white"><?= esc((string) ($initialBudget['value'] ?? '-')) ?></div>
                                </div>

                                <div class="p-3 bg-white bg-opacity-10 rounded-3 border border-white border-opacity-25 mb-3">
                                    <label for="recommendedBudget" class="small opacity-75 mb-2 d-block"><?= esc((string) ($recommendedBudget['label'] ?? 'Disetujui Reviewer')) ?></label>
                                    <div class="input-group input-group-lg border-0 bg-white rounded shadow-sm">
                                        <span class="input-group-text bg-transparent border-0 text-dark fw-bold">Rp</span>
                                        <input
                                            type="text"
                                            id="recommendedBudget"
                                            name="<?= esc((string) ($recommendedBudget['field_name'] ?? 'recommended_budget')) ?>"
                                            class="form-control border-0 text-dark fw-bold"
                                            inputmode="numeric"
                                            placeholder="0"
                                            value="<?= esc((string) ($recommendedBudget['value'] ?? '')) ?>"
                                            required>
                                    </div>
                                </div>
                                <small class="opacity-75 d-block" style="font-size: 0.75rem;"><?= esc((string) ($recommendedBudget['hint'] ?? '')) ?></small>
                            </div>
                        </div>

                        <div class="card border-0 bg-light shadow-none">
                            <div class="card-body p-4">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-danger btn-lg py-3 fw-bold">
                                        <i class="bi bi-check-all me-2"></i><?= esc((string) ($form['submit_label'] ?? 'Simpan Penilaian')) ?>
                                    </button>
                                    <p class="text-muted small text-center mb-0 mt-2">
                                        <?= esc((string) ($form['helper_text'] ?? ($totals['hint'] ?? ''))) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="<?= base_url('custom/js/reviewer-proposal-form.js') ?>?v=20260428-01"></script>
<?= $this->endSection() ?>