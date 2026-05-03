<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
/** @var array<string,mixed> $hero */
/** @var array<string,string> $actions */
/** @var array<string,mixed> $evidence */
/** @var array<int,array<string,mixed>> $summaryItems */
/** @var array<int,array<string,mixed>> $detailItems */
/** @var string $resumeHtml */
?>

<div class="row g-4 admin-page">
    <?php
    $evidenceHref = trim((string) ($evidence['url'] ?? ''));
    if ($evidenceHref !== '' && !preg_match('~^[a-z][a-z0-9+.-]*:~i', $evidenceHref)) {
        $evidenceHref = 'https://' . ltrim($evidenceHref, '/');
    }
    ?>
    <div class="col-12 mb-2">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) ($hero['title'] ?? $title)),
            'subtitle' => esc((string) ($hero['subtitle'] ?? '')),
            'badges' => [
                ['label' => 'Detail Kegiatan', 'class' => 'text-bg-light border'],
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
                    <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Informasi Utama</h6>
                </div>
                
                <div class="d-flex flex-column gap-3">
                    <?php foreach ((array) $summaryItems as $item): ?>
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="small text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.65rem;"><?= esc((string) ($item['label'] ?? '')) ?></div>
                            <div class="fw-bold text-dark small"><?= esc((string) ($item['value'] ?? '')) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-2">
                    <i class="bi bi-paperclip text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Bukti Dukung</h6>
                </div>

                <div class="p-3 border rounded-4 bg-white mb-4 shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light p-3 rounded-3 text-primary">
                            <i class="bi bi-file-earmark-link fs-3"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark small mb-1">Tautan Dokumen</div>
                            <div class="text-muted text-truncate small"><?= esc((string) ($evidence['label'] ?? '')) ?></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="<?= esc((string) $evidenceHref, 'attr') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-pill fw-bold shadow-sm py-2">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Buka Link Bukti
                    </a>
                    <button class="btn btn-outline-danger border-0 rounded-pill py-2 btn-delete" data-href="<?= esc((string) ($actions['delete_url'] ?? '')) ?>" data-delete-label="<?= esc((string) ($hero['title'] ?? 'kegiatan ini')) ?>" data-delete-desc="Data yang dihapus tidak dapat dipulihkan kembali.">
                        <i class="bi bi-trash me-2"></i>Hapus Kegiatan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-2">
                    <i class="bi bi-graph-up-arrow text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Detail Pelaksanaan & Pendanaan</h6>
                </div>
                
                <div class="row g-3">
                    <?php foreach ((array) $detailItems as $item): ?>
                        <div class="col-md-6">
                            <div class="p-3 bg-white border rounded-3 h-100">
                                <div class="small text-muted mb-1"><?= esc((string) ($item['label'] ?? '')) ?></div>
                                <div class="fw-bold text-dark small"><?= esc((string) ($item['value'] ?? '')) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-light border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-justify-left text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Resume Kegiatan</h6>
                </div>
            </div>
            <div class="card-body p-4 p-lg-5">
                <?php if (trim((string) $resumeHtml) === ''): ?>
                    <div class="text-center py-5 opacity-50">
                        <i class="bi bi-file-earmark-text display-4 mb-3"></i>
                        <h5 class="fw-bold mb-0">Belum ada resume kegiatan</h5>
                        <p class="text-muted small">Ringkasan aktivitas belum ditambahkan oleh pengusul.</p>
                    </div>
                <?php else: ?>
                    <div class="admin-proposal-rich fs-6 lh-lg text-secondary">
                        <?= $resumeHtml ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>