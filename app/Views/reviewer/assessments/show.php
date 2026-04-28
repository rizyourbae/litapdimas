<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$hero = isset($hero) && is_array($hero) ? $hero : [];
$detail = isset($detail) && is_array($detail) ? $detail : [];
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
                            <span class="badge <?= esc((string) ($detail['review_status_badge_class'] ?? 'text-bg-light border')) ?> px-3 py-2"><?= esc((string) ($detail['review_status_label'] ?? 'Belum Dinilai')) ?></span>
                        </div>
                        <h2 class="h3 admin-hero__title mb-2"><?= esc((string) ($hero['title'] ?? 'Detail Penilaian Reviewer')) ?></h2>
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

    <div class="col-lg-8">
        <div class="card admin-panel-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="card-title mb-0">
                    <i class="bi bi-card-text me-2"></i><?= esc((string) ($detail['title'] ?? '')) ?>
                </h3>
                <span class="badge <?= esc((string) ($detail['review_status_badge_class'] ?? 'text-bg-light border')) ?> px-3 py-2"><?= esc((string) ($detail['review_status_label'] ?? 'Belum Dinilai')) ?></span>
            </div>
            <div class="card-body">
                <div class="admin-summary-grid admin-summary-grid--compact">
                    <div class="admin-detail-item">
                        <div class="admin-detail-item__label">Kategori</div>
                        <div class="admin-detail-item__value"><?= esc((string) ($detail['category_label'] ?? '')) ?></div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-item__label">Klaster</div>
                        <div class="admin-detail-item__value"><?= esc((string) ($detail['cluster'] ?? '')) ?></div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-item__label">Nilai Reviewer</div>
                        <div class="admin-detail-item__value"><?= esc((string) ($detail['score_display'] ?? '-')) ?></div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-item__label">Status Review</div>
                        <div class="admin-detail-item__value"><?= esc((string) ($detail['review_status_label'] ?? 'Belum Dinilai')) ?></div>
                    </div>
                </div>

                <div class="admin-note-box mt-3">
                    <div class="admin-note-box__title">Status MVP</div>
                    <div class="admin-note-box__body">Halaman ini disiapkan sebagai placeholder detail MVP. Form penilaian rinci akan ditambahkan per kategori pada iterasi berikutnya.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card admin-panel-card h-100">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="bi bi-list-check me-2"></i>Catatan Pengembangan
                </h3>
            </div>
            <div class="card-body">
                <div class="admin-proposal-stack">
                    <?php foreach (($detail['notes'] ?? []) as $note): ?>
                        <div class="admin-note-box">
                            <div class="admin-note-box__body"><?= esc((string) $note) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>