<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$hero = isset($hero) && is_array($hero) ? $hero : [];
$metrics = isset($metrics) && is_array($metrics) ? $metrics : [];
$table = isset($table) && is_array($table) ? $table : [];
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
                        <h2 class="h3 admin-hero__title mb-2"><?= esc((string) ($hero['title'] ?? 'Riwayat Review')) ?></h2>
                        <p class="admin-hero__subtitle mb-0"><?= esc((string) ($hero['subtitle'] ?? '')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php foreach ($metrics as $metric): ?>
        <div class="col-md-6 col-xl-4">
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
        <div class="card card-primary card-outline admin-table-card">
            <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <h3 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Penilaian Reviewer
                </h3>
                <span class="badge text-bg-light border">Total data: <?= esc((string) count($table['rows'] ?? [])) ?></span>
            </div>
            <div class="card-body">
                <div class="dt-skeleton-wrap">
                    <div class="dt-skeleton-overlay" id="<?= esc((string) ($table['skeleton_id'] ?? '')) ?>">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:170px"></th>
                                    <th></th>
                                    <th style="width:170px"></th>
                                    <th style="width:140px"></th>
                                    <th style="width:100px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ([62, 77, 58, 84, 49] as $width): ?>
                                    <tr>
                                        <td><span class="skeleton-line" style="width:72%"></span></td>
                                        <td><span class="skeleton-line" style="width:<?= esc((string) $width) ?>%"></span></td>
                                        <td><span class="skeleton-line" style="width:66%"></span></td>
                                        <td><span class="skeleton-line" style="width:58%;border-radius:20px;height:20px"></span></td>
                                        <td class="text-center"><span class="skeleton-btn"></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="dt-real-wrap" id="<?= esc((string) ($table['real_wrap_id'] ?? '')) ?>">
                        <?php if (empty($table['rows'])): ?>
                            <div class="admin-empty-state py-4">
                                <i class="bi bi-inbox"></i>
                                <p class="mb-1 fw-semibold text-body-emphasis">Belum ada riwayat review.</p>
                                <small class="d-block">Riwayat reviewer akan tampil di sini setelah penilaian mulai dijalankan.</small>
                            </div>
                        <?php else: ?>
                            <table
                                id="<?= esc((string) ($table['table_id'] ?? '')) ?>"
                                class="table table-hover table-bordered align-middle w-100"
                                data-admin-datatable
                                data-admin-datatable-options='{"columnDefs":[{"orderable":false,"targets":[4]}]}'
                                data-skeleton-id="<?= esc((string) ($table['skeleton_id'] ?? '')) ?>"
                                data-real-wrap-id="<?= esc((string) ($table['real_wrap_id'] ?? '')) ?>">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:170px">Kategori</th>
                                        <th>Judul</th>
                                        <th style="width:170px">Klaster</th>
                                        <th style="width:140px">Nilai</th>
                                        <th style="width:100px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($table['rows'] as $row): ?>
                                        <tr>
                                            <td><?= esc((string) ($row['category_label'] ?? '')) ?></td>
                                            <td>
                                                <div class="fw-semibold text-body-emphasis"><?= esc((string) ($row['title'] ?? '')) ?></div>
                                                <div class="small text-muted mt-1">
                                                    <span class="badge <?= esc((string) ($row['review_status_badge_class'] ?? 'text-bg-light border')) ?>"><?= esc((string) ($row['review_status_label'] ?? '')) ?></span>
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
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>