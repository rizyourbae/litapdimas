<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
/** @var string $title */
/** @var array<string,mixed> $hero */
/** @var array<string,string> $actions */
/** @var array<int,array<string,mixed>> $summaryItems */
/** @var string $metadataTitle */
/** @var array<int,array<string,mixed>> $metadataItems */
?>

<div class="row g-4 admin-page">
    <div class="col-12 mb-2">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) ($hero['title'] ?? $title)),
            'subtitle' => esc((string) ($hero['subtitle'] ?? '')),
            'badges' => [
                ['label' => 'Detail Publikasi', 'class' => 'text-bg-light border'],
                ['label' => esc((string) ($hero['jenis_label'] ?? '')), 'class' => esc((string) ($hero['jenis_badge_class'] ?? 'text-bg-primary'))],
                ['label' => 'Tahun ' . esc((string) ($hero['tahun'] ?? '')), 'class' => 'text-bg-light border']
            ],
            'actions' => [
                [
                    'label' => 'Kembali',
                    'class' => 'btn btn-outline-secondary rounded-pill px-4',
                    'icon' => 'bi bi-arrow-left',
                    'url' => esc((string) ($actions['back_url'] ?? ''))
                ],
                [
                    'label' => 'Edit Data',
                    'class' => 'btn btn-warning rounded-pill px-4',
                    'icon' => 'bi bi-pencil-square',
                    'url' => esc((string) ($actions['edit_url'] ?? ''))
                ]
            ]
        ]) ?>
    </div>

    <div class="col-xl-4">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-2">
                    <i class="bi bi-journal-check text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Ringkasan Publikasi</h6>
                </div>
                
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($summaryItems as $item): ?>
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;"><?= esc((string) ($item['label'] ?? '')) ?></div>
                            <div class="fw-bold text-dark small"><?= esc((string) ($item['value'] ?? '')) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4 pt-3 border-top d-grid gap-2">
                    <button class="btn btn-outline-danger border-0 rounded-pill py-2 btn-delete" data-href="<?= esc((string) ($actions['delete_url'] ?? '')) ?>" data-delete-label="<?= esc((string) ($hero['title'] ?? 'publikasi ini')) ?>" data-delete-desc="Data yang dihapus tidak dapat dipulihkan kembali.">
                        <i class="bi bi-trash me-2"></i>Hapus Publikasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-light border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1"><?= esc((string) $metadataTitle) ?></h6>
                </div>
            </div>
            <div class="card-body p-4">
                <?php if (empty($metadataItems)): ?>
                    <div class="text-center py-5 opacity-50">
                        <i class="bi bi-info-circle display-4 mb-3"></i>
                        <h5 class="fw-bold mb-0">Metadata tidak tersedia</h5>
                        <p class="text-muted small">Belum ada rincian metadata tambahan untuk publikasi ini.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($metadataItems as $item): $item = (array) $item; ?>
                            <?php if (!empty($item['url'])): ?>
                                <div class="col-12">
                                    <div class="p-3 border rounded-3 bg-white shadow-sm border-primary-hover transition-all">
                                        <div class="small text-muted fw-bold text-uppercase ls-1 mb-2" style="font-size: 0.65rem;"><?= esc((string) ($item['label'] ?? '')) ?></div>
                                        <a href="<?= esc((string) ($item['href'] ?? ''), 'attr') ?>" target="_blank" rel="noopener noreferrer" class="fw-bold text-primary text-decoration-none d-flex align-items-center gap-2">
                                            <span class="text-break"><?= esc((string) ($item['value'] ?? '')) ?></span>
                                            <i class="bi bi-box-arrow-up-right small"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-3 h-100">
                                        <div class="small text-muted mb-1"><?= esc((string) ($item['label'] ?? '')) ?></div>
                                        <div class="fw-bold text-dark small"><?= esc((string) ($item['value'] ?? '')) ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 bg-primary text-white overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle d-none d-md-flex">
                        <i class="bi bi-shield-check fs-2"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Verifikasi & Pengelolaan</h6>
                        <p class="small mb-0 opacity-75">Gunakan tombol edit di atas jika Anda perlu melakukan koreksi metadata publikasi untuk validasi pelaporan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>