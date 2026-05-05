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
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'reviewer',
            'title' => esc((string) ($hero['title'] ?? 'Penilaian Proposal')),
            'subtitle' => esc((string) ($hero['subtitle'] ?? 'Tinjau substansi proposal dan berikan skor sesuai kriteria.')),
            'badges' => array_merge(
                [['label' => 'Detail Penilaian', 'class' => 'text-bg-light border']],
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

    <div class="col-12 animate-fade-up delay-1">
        <div class="card shadow-sm border-0 overflow-hidden mb-4">
            <div class="card-header bg-light py-3 px-4">
                <h5 class="h6 fw-bold mb-0 text-uppercase letter-spacing-1">
                    <i class="bi bi-journal-richtext me-2 text-primary"></i><?= esc((string) ($proposal['summary_card_title'] ?? 'Ringkasan Usulan')) ?>
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <?php foreach ($summaryItems as $item): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;"><?= esc((string) ($item['label'] ?? '')) ?></div>
                            <div class="fw-bold text-dark"><?= esc((string) ($item['value'] ?? '')) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 animate-fade-up delay-2">
        <form action="<?= esc((string) ($form['action_url'] ?? '#')) ?>" method="post" id="assessmentForm">
            <?= csrf_field() ?>

            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-header p-0 bg-light border-bottom">
                    <ul class="nav nav-tabs nav-fill border-0" id="reviewerProposalDetailTabs" role="tablist">
                        <?php foreach ($detailTabs as $tab): ?>
                            <li class="nav-item" role="presentation">
                                <button
                                    class="<?= esc((string) ($tab['button_class'] ?? 'nav-link')) ?> border-0 py-3 fw-bold"
                                    id="<?= esc((string) ($tab['button_id'] ?? '')) ?>"
                                    data-bs-toggle="tab"
                                    data-bs-target="#<?= esc((string) ($tab['pane_id'] ?? '')) ?>"
                                    type="button"
                                    role="tab"
                                    aria-controls="<?= esc((string) ($tab['pane_id'] ?? '')) ?>"
                                    aria-selected="<?= esc((string) ($tab['aria_selected'] ?? 'false')) ?>">
                                    <i class="<?= esc((string) ($tab['icon'] ?? 'bi bi-circle')) ?> me-2"></i><?= esc((string) ($tab['label'] ?? '')) ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="reviewerProposalDetailTabsContent">
                        <!-- Tab: Review Substansi -->
                        <div class="<?= esc((string) (($detailTabs[0]['pane_class'] ?? 'tab-pane fade show active'))) ?>" id="<?= esc((string) ($detailTabs[0]['pane_id'] ?? 'reviewer-detail-pane-review')) ?>" role="tabpanel" aria-labelledby="<?= esc((string) ($detailTabs[0]['button_id'] ?? 'reviewer-detail-tab-review')) ?>" tabindex="0">

                            <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center gap-3">
                                <i class="bi bi-info-circle-fill fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Panduan Telaah</h6>
                                    <p class="small mb-0 text-dark-50">Tinjau isian substansi di bawah ini, lalu berikan catatan per bagian jika diperlukan.</p>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-4">
                                <?php foreach ($reviewSections as $section): ?>
                                    <div class="border rounded-3 p-4 bg-white">
                                        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                                            <i class="bi bi-bookmark-fill me-2 small"></i><?= esc((string) ($section['title'] ?? 'Bagian Proposal')) ?>
                                        </h6>
                                        <div class="reviewer-proposal-content admin-proposal-rich mb-4 p-3 bg-light rounded text-dark lh-base" style="font-size: 0.95rem;">
                                            <?= $section['content_html'] ?? '' ?>
                                        </div>
                                        <div class="reviewer-proposal-field">
                                            <label class="form-label fw-bold text-muted small mb-2"><?= esc((string) ($section['comment_label'] ?? 'Catatan Reviewer')) ?></label>
                                            <div
                                                id="<?= esc((string) ($section['editor_id'] ?? '')) ?>"
                                                class="reviewer-proposal-editor bg-white border rounded"
                                                style="min-height: 150px;"
                                                data-reviewer-quill
                                                data-reviewer-hidden-input="#<?= esc((string) ($section['input_id'] ?? '')) ?>"
                                                data-reviewer-placeholder="Tuliskan masukan atau catatan untuk bagian ini..."><?= $section['comment_value'] ?? '' ?></div>
                                            <input type="hidden" id="<?= esc((string) ($section['input_id'] ?? '')) ?>" name="<?= esc((string) ($section['comment_field_name'] ?? 'comments[]')) ?>" value="<?= esc((string) ($section['comment_value'] ?? '')) ?>">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Tab: Berkas Lampiran -->
                        <div class="<?= esc((string) (($detailTabs[2]['pane_class'] ?? 'tab-pane fade'))) ?>" id="<?= esc((string) ($detailTabs[2]['pane_id'] ?? 'reviewer-detail-pane-documents')) ?>" role="tabpanel" aria-labelledby="<?= esc((string) ($detailTabs[2]['button_id'] ?? 'reviewer-detail-tab-documents')) ?>" tabindex="0">
                            <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center gap-3">
                                <i class="bi bi-folder-fill fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1"><?= esc((string) ($proposal['documents']['card_title'] ?? 'Daftar Berkas Lampiran')) ?></h6>
                                    <p class="small mb-0 text-dark-50">Silakan unduh atau lihat berkas yang telah diunggah oleh pengusul untuk mendukung penilaian Anda.</p>
                                </div>
                            </div>

                            <div class="border rounded-3 overflow-hidden bg-white">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="py-3 px-4" style="width: 50px;">#</th>
                                                <th class="py-3 px-4">Jenis & Nama Berkas</th>
                                                <th class="py-3 px-4 text-center" style="width: 200px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($proposal['documents']['rows'])): ?>
                                                <tr>
                                                    <td colspan="3" class="text-center py-5 text-muted">
                                                        <i class="bi bi-file-earmark-x d-block fs-1 mb-2 opacity-25"></i>
                                                        <?= esc((string) ($proposal['documents']['empty_message'] ?? 'Tidak ada berkas.')) ?>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($proposal['documents']['rows'] as $index => $doc): ?>
                                                    <tr>
                                                        <td class="text-center text-muted small px-4"><?= $index + 1 ?></td>
                                                        <td class="px-4">
                                                            <div class="fw-bold text-dark"><?= esc((string) ($doc['label'] ?? '-')) ?></div>
                                                            <div class="small text-muted d-flex align-items-center gap-2">
                                                                <span class="text-truncate" style="max-width: 300px;"><?= esc((string) ($doc['file_name'] ?? '-')) ?></span>
                                                                <?php if (!empty($doc['file_size_label'])): ?>
                                                                    <span class="badge bg-light text-dark fw-normal border"><?= esc((string) $doc['file_size_label']) ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                        <td class="text-center px-4">
                                                            <?php if (!empty($doc['has_file'])): ?>
                                                                <a href="<?= esc((string) $doc['view_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary rounded-pill px-3">
                                                                    <i class="bi bi-eye-fill me-1"></i>Lihat Berkas
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="badge bg-light text-muted fw-normal px-3 py-2 rounded-pill border">Belum Diunggah</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Skor & Penilaian -->
                        <div class="<?= esc((string) (($detailTabs[1]['pane_class'] ?? 'tab-pane fade'))) ?>" id="<?= esc((string) ($detailTabs[1]['pane_id'] ?? 'reviewer-detail-pane-scoring')) ?>" role="tabpanel" aria-labelledby="<?= esc((string) ($detailTabs[1]['button_id'] ?? 'reviewer-detail-tab-scoring')) ?>" tabindex="0">

                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="border rounded-3 p-4 bg-white mb-4">
                                        <h6 class="fw-bold text-primary mb-4 border-bottom pb-2">
                                            <i class="bi bi-check2-square me-2"></i>Parameter Penilaian
                                        </h6>
                                        <div class="d-flex flex-column gap-3">
                                            <?php foreach ($aspects as $aspect): ?>
                                                <div class="reviewer-proposal-field">
                                                    <label class="form-label fw-bold text-dark small mb-1"><?= esc((string) ($aspect['label'] ?? 'Aspek Penilaian')) ?><span class="text-danger ms-1">*</span></label>
                                                    <select name="<?= esc((string) ($aspect['field_name'] ?? 'scores[]')) ?>" class="form-select form-select-lg shadow-none" required>
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

                                    <div class="border rounded-3 p-4 bg-white">
                                        <h6 class="fw-bold text-primary mb-4 border-bottom pb-2">
                                            <i class="bi bi-chat-left-text me-2"></i>Kesimpulan & Rekomendasi
                                        </h6>
                                        <div class="reviewer-proposal-field mb-4">
                                            <label class="form-label fw-bold text-dark small mb-2"><?= esc((string) ($generalComment['label'] ?? 'Komentar Umum Proposal')) ?></label>
                                            <div
                                                id="<?= esc((string) ($generalComment['editor_id'] ?? 'reviewer-general-comment')) ?>"
                                                class="reviewer-proposal-editor bg-white border rounded"
                                                style="min-height: 200px;"
                                                data-reviewer-quill
                                                data-reviewer-hidden-input="#<?= esc((string) ($generalComment['input_id'] ?? 'reviewer-general-comment-input')) ?>"
                                                data-reviewer-placeholder="Berikan ulasan akhir dan rekomendasi untuk usulan ini..."><?= $generalComment['value'] ?? '' ?></div>
                                            <input type="hidden" id="<?= esc((string) ($generalComment['input_id'] ?? 'reviewer-general-comment-input')) ?>" name="<?= esc((string) ($generalComment['field_name'] ?? 'general_comment')) ?>" value="<?= esc((string) ($generalComment['value'] ?? '')) ?>">
                                        </div>

                                        <div class="reviewer-proposal-field">
                                            <label class="form-label fw-bold text-dark small mb-2"><?= esc((string) ($validatorNote['label'] ?? 'Catatan Khusus (Internal)')) ?><span class="text-danger ms-1">*</span></label>
                                            <textarea name="<?= esc((string) ($validatorNote['field_name'] ?? 'validator_note')) ?>" class="form-control shadow-none" rows="4" placeholder="Tuliskan catatan internal..." required><?= esc((string) ($validatorNote['value'] ?? '')) ?></textarea>
                                            <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i><?= esc((string) ($validatorNote['hint'] ?? '')) ?></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="sticky-top" style="top: 2rem;">
                                        <div class="card border-0 bg-primary text-white shadow-sm mb-3">
                                            <div class="card-body p-4">
                                                <div class="small text-uppercase opacity-75 fw-bold mb-3 ls-1">Ringkasan Nilai</div>

                                                <div class="mb-4">
                                                    <div class="small opacity-75 mb-1"><?= esc((string) ($totals['raw_label'] ?? 'Bobot x Skor')) ?></div>
                                                    <div class="h3 fw-bold mb-0"><?= esc((string) ($totals['raw_value'] ?? '0')) ?></div>
                                                </div>

                                                <div class="p-3 bg-white bg-opacity-10 rounded-3 border border-white border-opacity-25">
                                                    <div class="small opacity-75 mb-1"><?= esc((string) ($totals['normalized_label'] ?? 'Nilai Akhir (Skala 100)')) ?></div>
                                                    <div class="h2 fw-bold mb-0 text-warning"><?= esc((string) ($totals['normalized_value'] ?? '-')) ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-0 bg-light shadow-none">
                                            <div class="card-body p-4">
                                                <div class="d-grid gap-2">
                                                    <button type="submit" class="btn btn-danger btn-lg py-3 fw-bold">
                                                        <i class="bi bi-check-all me-2"></i><?= esc((string) ($form['submit_label'] ?? 'Simpan Penilaian')) ?>
                                                    </button>
                                                    <p class="text-muted small text-center mb-0 mt-2">
                                                        <?= esc((string) ($totals['hint'] ?? '')) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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