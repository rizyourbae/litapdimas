<?php
$hide_header = true;
$this->extend('layouts/main');

$this->section('content');
?>

<?= view('components/ui-hero', [
    'type' => 'dosen',
    'title' => 'Detail Kegiatan',
    'subtitle' => 'Tinjau detail pelaksanaan dan capaian kegiatan mandiri Anda.',
    'badges' => [
        ['label' => 'Dosen Workspace', 'class' => 'text-bg-light border'],
        ['label' => 'Kegiatan Mandiri', 'class' => 'text-bg-primary shadow-sm']
    ],
    'actions' => [
        [
            'label' => 'Kembali',
            'class' => 'btn btn-outline-secondary rounded-pill px-4',
            'icon' => 'bi bi-arrow-left',
            'url' => $actions['back_url']
        ],
        [
            'label' => 'Edit',
            'class' => 'btn btn-warning text-white rounded-pill px-4 shadow-sm',
            'icon' => 'bi bi-pencil-square',
            'url' => $actions['edit_url']
        ]
    ]
]) ?>

<div class="row g-4">
    <!-- Judul Kegiatan - High Impact Card -->
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden border-start border-primary border-5">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center">
                    <div class="col-lg-1 d-none d-lg-block">
                        <div class="admin-panel-icon-sm bg-primary-soft text-primary mx-auto" style="width: 60px; height: 60px;">
                            <i class="bi bi-clipboard-check fs-2"></i>
                        </div>
                    </div>
                    <div class="col-lg-11">
                        <h1 class="h4 fw-bold text-dark lh-base mb-2">
                            <?= esc((string) ($hero['title'] ?? 'Judul tidak tersedia')) ?>
                        </h1>
                        <div class="d-flex flex-wrap gap-3 align-items-center mt-3">
                            <div class="d-flex align-items-center gap-2 text-muted small">
                                <i class="bi bi-calendar3"></i>
                                <span>Tahun <?= esc((string) ($hero['tahun'] ?? '-')) ?></span>
                            </div>
                            <div class="vr opacity-25"></div>
                            <div class="d-flex align-items-center gap-2 text-muted small">
                                <i class="bi bi-tag"></i>
                                <span><?= esc((string) ($hero['jenis_label'] ?? '-')) ?></span>
                            </div>
                            <div class="vr opacity-25"></div>
                            <span class="badge <?= esc($hero['klaster_badge_class'] ?? 'text-bg-light border') ?> rounded-pill">
                                <?= esc($hero['klaster_label'] ?? '-') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
            <div class="card-header bg-light border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="admin-panel-icon-sm bg-primary-soft text-primary">
                        <i class="bi bi-info-circle fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Ringkasan Data</h6>
                        <p class="text-muted small mb-0 mt-1">Atribut utama kegiatan</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush border-0">
                    <?php foreach ($summaryItems as $item): ?>
                        <div class="list-group-item px-4 py-3 border-0 bg-transparent">
                            <div class="small text-muted text-uppercase fw-bold ls-1 mb-1" style="font-size: 0.65rem;"><?= esc((string) $item['label']) ?></div>
                            <div class="fw-semibold text-dark"><?= esc((string) $item['value']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
            <div class="card-header bg-light border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="admin-panel-icon-sm bg-primary-soft text-primary">
                        <i class="bi bi-cash-stack fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Pelaksanaan & Pendanaan</h6>
                        <p class="text-muted small mb-0 mt-1">Isi detail kolaborasi dan sumber pendanaan</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php foreach ($detailItems as $item): ?>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3 border-0 h-100">
                                <div class="small text-muted text-uppercase fw-bold ls-1 mb-1" style="font-size: 0.65rem;"><?= esc((string) $item['label']) ?></div>
                                <div class="fw-semibold text-dark"><?= esc((string) $item['value']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
            <div class="card-header bg-light border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="admin-panel-icon-sm bg-primary-soft text-primary">
                        <i class="bi bi-file-earmark-text fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Resume Kegiatan</h6>
                        <p class="text-muted small mb-0 mt-1">Ringkasan singkat pelaksanaan kegiatan</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="lh-lg text-secondary"><?= $resumeHtml ?></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 h-100 overflow-hidden">
            <div class="card-header bg-light border-0 py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="admin-panel-icon-sm bg-primary-soft text-primary">
                        <i class="bi bi-link-45deg fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Bukti Dukung</h6>
                        <p class="text-muted small mb-0 mt-1">Tautan berkas pendukung</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4 d-flex flex-column gap-3">
                <div class="bg-light p-3 rounded-3 border-0">
                    <div class="small text-muted text-uppercase fw-bold ls-1 mb-2" style="font-size: 0.65rem;">Tautan Dokumen</div>
                    <div class="fw-semibold text-break text-dark"><?= esc((string) $evidence['label']) ?></div>
                </div>

                <a href="<?= esc((string) $evidence['href']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-pill w-100 py-2 shadow-sm">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Buka Link Bukti
                </a>

                <hr class="my-2 opacity-50">

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-danger rounded-pill flex-fill btn-delete" 
                        data-href="<?= esc((string) $actions['delete_url']) ?>" 
                        data-delete-label="kegiatan mandiri ini" 
                        data-delete-desc="Data yang dihapus tidak dapat dikembalikan.">
                        <i class="bi bi-trash me-2"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
