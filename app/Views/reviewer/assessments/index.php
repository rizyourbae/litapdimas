<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$hero = isset($hero) && is_array($hero) ? $hero : [];
$metrics = isset($metrics) && is_array($metrics) ? $metrics : [];
$tabs = isset($tabs) && is_array($tabs) ? $tabs : [];
?>

<div class="row g-3 admin-page">
    <div class="col-12">
        <div class="card admin-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <?php foreach (($hero['badges'] ?? []) as $badge): ?>
                                <span class="badge <?= esc((string) ($badge['class'] ?? 'text-bg-light border')) ?> px-3 py-2"><?= esc((string) ($badge['label'] ?? '')) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc((string) ($hero['title'] ?? 'Penilaian Reviewer')) ?></h2>
                        <p class="admin-hero__subtitle mb-0"><?= esc((string) ($hero['subtitle'] ?? '')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php foreach ($metrics as $metric): ?>
        <div class="col-md-6 col-xl-3">
            <div class="card admin-metric-card h-100">
                <div class="card-body">
                    <div class="small text-uppercase text-muted mb-2"><?= esc((string) ($metric['label'] ?? '')) ?></div>
                    <div class="admin-metric-card__value <?= esc((string) ($metric['tone_class'] ?? 'text-dark')) ?>"><?= esc((string) ($metric['value'] ?? '0')) ?></div>
                    <div class="text-muted small"><?= esc((string) ($metric['caption'] ?? '')) ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="col-12">
        <div class="card card-primary card-outline admin-table-card card-outline-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="reviewerAssessmentTabs" role="tablist">
                    <?php foreach ($tabs as $tab): ?>
                        <li class="nav-item" role="presentation">
                            <button
                                class="<?= esc((string) ($tab['button_class'] ?? 'nav-link')) ?>"
                                id="<?= esc((string) ($tab['button_id'] ?? '')) ?>"
                                data-bs-toggle="tab"
                                data-bs-target="#<?= esc((string) ($tab['pane_id'] ?? '')) ?>"
                                type="button"
                                role="tab"
                                aria-controls="<?= esc((string) ($tab['pane_id'] ?? '')) ?>"
                                aria-selected="<?= $tab['is_active'] ? 'true' : 'false' ?>">
                                <i class="<?= esc((string) ($tab['icon'] ?? 'bi bi-circle')) ?> me-1"></i><?= esc((string) ($tab['label'] ?? '')) ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="reviewerAssessmentTabsContent">
                    <?php foreach ($tabs as $tab): ?>
                        <div
                            class="<?= esc((string) ($tab['pane_class'] ?? 'tab-pane fade')) ?>"
                            id="<?= esc((string) ($tab['pane_id'] ?? '')) ?>"
                            role="tabpanel"
                            aria-labelledby="<?= esc((string) ($tab['button_id'] ?? '')) ?>"
                            tabindex="0">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <h3 class="card-title mb-0">
                                    <i class="<?= esc((string) ($tab['icon'] ?? 'bi bi-circle')) ?> me-2"></i><?= esc((string) ($tab['label'] ?? '')) ?>
                                </h3>
                                <span class="badge text-bg-light border">Total data: <?= esc((string) count($tab['rows'] ?? [])) ?></span>
                            </div>

                            <form action="<?= esc((string) (($tab['filter']['action_url'] ?? '#'))) ?>" method="get" class="row g-2 align-items-end mb-3">
                                <input type="hidden" name="tab" value="<?= esc((string) ($tab['key'] ?? 'proposal')) ?>">
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <label class="form-label small text-muted fw-semibold mb-1">Filter Status</label>
                                    <select name="<?= esc((string) (($tab['filter']['field_name'] ?? 'status'))) ?>" class="form-select">
                                        <?php foreach (($tab['filter']['options'] ?? []) as $option): ?>
                                            <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= (($tab['filter']['selected_status'] ?? '') === ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                                <?= esc((string) ($option['label'] ?? '')) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-auto">
                                    <button type="submit" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-funnel me-1"></i>Terapkan
                                    </button>
                                </div>
                            </form>

                            <?php if (empty($tab['rows'])): ?>
                                <div class="admin-empty-state py-4">
                                    <i class="bi bi-inbox"></i>
                                    <p class="mb-1 fw-semibold text-body-emphasis">Tidak ada data yang cocok dengan filter pada tab ini.</p>
                                    <small class="d-block">Ubah filter status atau tunggu data penilaian reviewer muncul pada kategori ini.</small>
                                </div>
                            <?php else: ?>
                                <div class="dt-skeleton-wrap">
                                    <div class="dt-skeleton-overlay" id="<?= esc((string) ($tab['skeleton_id'] ?? '')) ?>">
                                        <table class="table table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th></th>
                                                    <th style="width:180px"></th>
                                                    <th style="width:140px"></th>
                                                    <th style="width:100px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ([72, 58, 81, 64, 47] as $width): ?>
                                                    <tr>
                                                        <td><span class="skeleton-line" style="width:<?= esc((string) $width) ?>%"></span></td>
                                                        <td><span class="skeleton-line" style="width:68%"></span></td>
                                                        <td><span class="skeleton-line" style="width:52%"></span></td>
                                                        <td class="text-center"><span class="skeleton-btn"></span></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="dt-real-wrap" id="<?= esc((string) ($tab['real_wrap_id'] ?? '')) ?>">
                                        <table
                                            id="<?= esc((string) ($tab['table_id'] ?? '')) ?>"
                                            class="table table-hover table-bordered align-middle w-100"
                                            data-admin-datatable
                                            data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[3]}]}'
                                            data-skeleton-id="<?= esc((string) ($tab['skeleton_id'] ?? '')) ?>"
                                            data-real-wrap-id="<?= esc((string) ($tab['real_wrap_id'] ?? '')) ?>">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Judul</th>
                                                    <th style="width:180px">Klaster</th>
                                                    <th style="width:140px">Nilai</th>
                                                    <th style="width:100px" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($tab['rows'] as $row): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="fw-semibold text-body-emphasis"><?= esc((string) ($row['title'] ?? '')) ?></div>
                                                            <div class="small text-muted mt-1">
                                                                <span class="badge <?= esc((string) ($row['review_status_badge_class'] ?? 'text-bg-warning')) ?>"><?= esc((string) ($row['review_status_label'] ?? 'Belum Dinilai')) ?></span>
                                                            </div>
                                                        </td>
                                                        <td><?= esc((string) ($row['cluster'] ?? '')) ?></td>
                                                        <td class="fw-semibold"><?= esc((string) ($row['score_display'] ?? '-')) ?></td>
                                                        <td class="text-center">
                                                            <a href="<?= esc((string) ($row['action_url'] ?? '#')) ?>" class="btn btn-info btn-sm admin-icon-btn" title="<?= esc((string) ($row['action_label'] ?? 'Lihat')) ?>">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>