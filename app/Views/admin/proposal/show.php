<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3 admin-page admin-proposal-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <?php foreach ($hero['badges'] as $badge): ?>
                                <span class="badge <?= esc($badge['class']) ?> px-3 py-2"><?= esc($badge['label']) ?></span>
                            <?php endforeach; ?>
                            <span class="badge <?= esc($hero['status_badge_class']) ?> px-3 py-2"><?= esc($hero['status_label']) ?></span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc($hero['title']) ?></h2>
                        <p class="admin-hero__subtitle mb-0"><?= esc($hero['subtitle']) ?></p>
                    </div>
                    <div class="admin-hero__actions d-flex flex-wrap gap-2">
                        <a href="<?= esc($actions['back_url']) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php foreach ($metrics as $metric): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card admin-metric-card h-100">
                <div class="card-body">
                    <div class="small text-uppercase text-muted mb-2"><?= esc($metric['label']) ?></div>
                    <div class="admin-metric-card__value <?= esc($metric['tone_class']) ?>"><?= esc($metric['value']) ?></div>
                    <div class="text-muted small"><?= esc($metric['caption']) ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="col-xl-8">
        <div class="card admin-panel-card">
            <div class="card-body">
                <div class="admin-summary-grid">
                    <?php foreach ($summaryItems as $item): ?>
                        <div class="admin-detail-item">
                            <div class="admin-detail-item__label"><?= esc($item['label']) ?></div>
                            <div class="admin-detail-item__value"><?= esc($item['value']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
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
                </div>
                <?php if (empty($substansiSections)): ?>
                    <div class="admin-empty-state py-4">
                        <i class="bi bi-layout-text-window"></i>
                        <p class="mb-0 fw-semibold text-body-emphasis">Belum ada bagian substansi yang tersimpan.</p>
                    </div>
                <?php else: ?>
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
                            <div class="admin-detail-item__label"><?= esc($item['label']) ?></div>
                            <div class="admin-detail-item__value"><?= esc($item['value']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (!empty($journalInfo['links'])): ?>
                    <div class="admin-link-stack">
                        <?php foreach ($journalInfo['links'] as $link): ?>
                            <a href="<?= esc($link['url']) ?>" target="_blank" rel="noopener noreferrer" class="admin-link-chip">
                                <span class="admin-link-chip__label"><?= esc($link['label']) ?></span>
                                <span class="admin-link-chip__value"><?= esc($link['value']) ?></span>
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
                </div>
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

        <div class="card admin-panel-card mt-3">
            <div class="card-body">
                <div class="admin-proposal-section__header mb-3">
                    <div>
                        <div class="small text-uppercase text-muted mb-1">Berkas</div>
                        <h3 class="h5 mb-0">Dokumen Proposal</h3>
                    </div>
                </div>
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
                            <?php if (!empty($assignmentPanel['recommended_reviewers'])): ?>
                                <div>
                                    <div class="small text-uppercase text-muted mb-2">Rekomendasi Sistem</div>
                                    <div class="admin-proposal-stack">
                                        <?php foreach ($assignmentPanel['recommended_reviewers'] as $reviewer): ?>
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

                            <?php if (!empty($assignmentPanel['other_reviewers'])): ?>
                                <div>
                                    <div class="small text-uppercase text-muted mb-2">Reviewer Lainnya</div>
                                    <div class="admin-proposal-stack">
                                        <?php foreach ($assignmentPanel['other_reviewers'] as $reviewer): ?>
                                            <label class="admin-reviewer-card">
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