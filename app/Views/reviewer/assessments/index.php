<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$hero = isset($hero) && is_array($hero) ? $hero : [];
$metrics = isset($metrics) && is_array($metrics) ? $metrics : [];
$tabs = isset($tabs) && is_array($tabs) ? $tabs : [];
?>

<div class="row g-3 admin-page">
    <div class="col-12">
        <?= view('components/ui-hero', [
            'type' => 'reviewer',
            'title' => esc((string) ($hero['title'] ?? 'Antrian Penilaian')),
            'subtitle' => esc((string) ($hero['subtitle'] ?? 'Kelola seluruh tugas penilaian proposal Anda.')),
            'badges' => array_merge(
                [['label' => 'Tugas Reviewer', 'class' => 'text-bg-light border']],
                $hero['badges'] ?? []
            )
        ]) ?>
    </div>

    <?php foreach ($metrics as $metric): ?>
        <div class="col-md-6 col-xl-3">
            <?= view('components/ui-stat-card', [
                'label' => $metric['label'] ?? '',
                'value' => $metric['value'] ?? '0',
                'desc' => $metric['caption'] ?? '',
                'icon' => $metric['icon'] ?? 'bi bi-info-circle',
                'colorClass' => $metric['tone_class'] ?? 'text-dark'
            ]) ?>
        </div>
    <?php endforeach; ?>

    <div class="col-12 mt-4">
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-header p-0 bg-light border-bottom">
                <ul class="nav nav-tabs nav-fill border-0" id="reviewerAssessmentTabs" role="tablist">
                    <?php foreach ($tabs as $tab): ?>
                        <li class="nav-item" role="presentation">
                            <button
                                class="<?= esc((string) ($tab['button_class'] ?? 'nav-link')) ?> border-0 py-3 fw-bold"
                                id="<?= esc((string) ($tab['button_id'] ?? '')) ?>"
                                data-bs-toggle="tab"
                                data-bs-target="#<?= esc((string) ($tab['pane_id'] ?? '')) ?>"
                                type="button"
                                role="tab"
                                aria-controls="<?= esc((string) ($tab['pane_id'] ?? '')) ?>"
                                aria-selected="<?= $tab['is_active'] ? 'true' : 'false' ?>">
                                <i class="<?= esc((string) ($tab['icon'] ?? 'bi bi-circle')) ?> me-2"></i><?= esc((string) ($tab['label'] ?? '')) ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="reviewerAssessmentTabsContent">
                    <?php foreach ($tabs as $tab): ?>
                        <div
                            class="<?= esc((string) ($tab['pane_class'] ?? 'tab-pane fade')) ?>"
                            id="<?= esc((string) ($tab['pane_id'] ?? '')) ?>"
                            role="tabpanel"
                            aria-labelledby="<?= esc((string) ($tab['button_id'] ?? '')) ?>"
                            tabindex="0">
                            
                            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                                <div>
                                    <h4 class="h5 fw-bold mb-1 text-dark"><?= esc((string) ($tab['label'] ?? '')) ?></h4>
                                    <p class="small text-muted mb-0">Daftar item yang memerlukan penilaian Anda.</p>
                                </div>
                                
                                <form action="<?= esc((string) (($tab['filter']['action_url'] ?? '#'))) ?>" method="get" class="d-flex gap-2">
                                    <input type="hidden" name="tab" value="<?= esc((string) ($tab['key'] ?? 'proposal')) ?>">
                                    <select name="<?= esc((string) (($tab['filter']['field_name'] ?? 'status'))) ?>" class="form-select form-select-sm shadow-none" style="min-width: 200px;">
                                        <?php foreach (($tab['filter']['options'] ?? []) as $option): ?>
                                            <option value="<?= esc((string) ($option['value'] ?? '')) ?>" <?= (($tab['filter']['selected_status'] ?? '') === ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                                <?= esc((string) ($option['label'] ?? '')) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm px-3">
                                        <i class="bi bi-filter"></i>
                                    </button>
                                </form>
                            </div>

                            <?php if (empty($tab['rows'])): ?>
                                <?= view('components/ui-empty-state', [
                                    'icon' => 'bi bi-inbox',
                                    'title' => 'Antrian Kosong',
                                    'desc' => 'Tidak ada data yang tersedia untuk kategori ini saat ini.'
                                ]) ?>
                            <?php else: ?>
                                <?php ob_start(); ?>
                                <thead class="table-light">
                                    <tr>
                                        <th>Detail Item</th>
                                        <th style="width:180px">Klaster</th>
                                        <th style="width:140px" class="text-center">Skor</th>
                                        <th style="width:80px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <?php $header = ob_get_clean(); ?>

                                <?php ob_start(); ?>
                                <?php foreach ($tab['rows'] as $row): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark mb-1"><?= esc((string) ($row['title'] ?? '')) ?></div>
                                            <span class="badge <?= esc((string) ($row['review_status_badge_class'] ?? 'text-bg-warning')) ?> px-2 py-1 rounded-pill small">
                                                <?= esc((string) ($row['review_status_label'] ?? 'Belum Dinilai')) ?>
                                            </span>
                                        </td>
                                        <td class="small text-muted"><?= esc((string) ($row['cluster'] ?? '')) ?></td>
                                        <td class="text-center">
                                            <div class="fw-bold text-primary fs-5"><?= esc((string) ($row['score_display'] ?? '-')) ?></div>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= esc((string) ($row['action_url'] ?? '#')) ?>" class="btn btn-info btn-sm text-white admin-icon-btn" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php $body = ob_get_clean(); ?>

                                <?= view('components/ui-table-card', [
                                    'tableId' => $tab['table_id'] ?? uniqid(),
                                    'header' => $header,
                                    'body' => $body,
                                    'type' => 'admin'
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>