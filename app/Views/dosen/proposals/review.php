<?php
/** @var array<string,mixed> $proposal */
/** @var string $proposalUuid */

$this->extend('layouts/main');

$this->section('content');
?>

<div class="container-fluid pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="review-header mb-5 text-center">
                <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-3">
                    <ol class="breadcrumb mb-0 bg-white shadow-sm px-4 py-2 rounded-pill">
                        <li class="breadcrumb-item"><a href="<?= site_url('dosen/proposals') ?>" class="text-decoration-none text-primary">Proposal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Final Review</li>
                    </ol>
                </nav>
                <h2 class="fw-bold text-dark display-6 mb-2">Review Akhir Proposal</h2>
                <p class="text-muted fs-5">Pastikan seluruh data berikut sudah akurat sebelum dikirimkan ke sistem.</p>
            </div>

            <?php
            $overviewCards = $proposal['review_overview_cards'] ?? [];
            $step1Items = $proposal['review_step1_items'] ?? [];
            $step2Sections = $proposal['review_step2_sections'] ?? [];
            $step3Summary = $proposal['review_step3_summary'] ?? [];
            $step5Summary = $proposal['review_step5_summary'] ?? [];
            $documents = $proposal['documents'] ?? [];
            ?>

            <!-- Overview Stats -->
            <div class="row g-4 mb-5">
                <?php foreach ($overviewCards as $card): ?>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                            <div class="card-body p-4 text-center">
                                <div class="stat-icon bg-primary-soft text-primary rounded-circle mx-auto mb-3">
                                    <i class="<?= $card['icon'] ?? 'bi bi-info-circle' ?> fs-4"></i>
                                </div>
                                <h3 class="fw-bold mb-1 text-dark"><?= $card['value'] ?? '-' ?></h3>
                                <p class="text-muted small fw-semibold text-uppercase mb-0 ls-1"><?= $card['label'] ?? '-' ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- STEP 1: IDENTITAS UTAMA -->
            <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="step-badge bg-primary text-white rounded-circle">1</div>
                        <h5 class="fw-bold mb-0 text-dark">Identitas Utama Penelitian</h5>
                    </div>
                    <a href="<?= site_url('dosen/proposals/step/1/' . esc($proposalUuid)) ?>" class="btn btn-light-soft btn-sm rounded-pill px-3">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                <?php foreach ($step1Items as $item): ?>
                                    <tr>
                                        <td class="text-muted py-3 px-4 border-end-0" style="width: 30%; background: #fbfbfb;"><?= esc($item['label'] ?? '-') ?></td>
                                        <td class="px-4 text-dark fw-medium border-start-0"><?= esc($item['value'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- STEP 2: TIM PENELITI -->
            <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="step-badge bg-success text-white rounded-circle">2</div>
                        <h5 class="fw-bold mb-0 text-dark">Komposisi Tim & Anggota</h5>
                    </div>
                    <a href="<?= site_url('dosen/proposals/step/2/' . esc($proposalUuid)) ?>" class="btn btn-light-soft btn-sm rounded-pill px-3">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                </div>
                <div class="card-body p-4 pt-0">
                    <?php foreach ($step2Sections as $section): ?>
                        <div class="mb-4 last-child-mb-0">
                            <h6 class="text-primary fw-bold small text-uppercase ls-2 mb-3 border-start border-4 border-primary ps-3">
                                <?= esc($section['title'] ?? '-') ?>
                            </h6>
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted">
                                        <tr>
                                            <?php foreach (($section['columns'] ?? []) as $column): ?>
                                                <th class="small fw-bold px-3 py-2"><?= esc($column) ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($section['rows'])): ?>
                                            <tr>
                                                <td class="text-center text-muted py-4" colspan="<?= esc((string) ($section['colspan'] ?? 1)) ?>">
                                                    <i class="bi bi-info-circle me-2"></i><?= esc($section['empty_message'] ?? 'Data belum tersedia.') ?>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($section['rows'] as $row): ?>
                                                <tr>
                                                    <?php foreach (($row['cells'] ?? []) as $cell): ?>
                                                        <td class="px-3 py-3 small"><?= esc((string) $cell) ?></td>
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

            <!-- STEP 3: SUBSTANSI -->
            <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="step-badge bg-info text-white rounded-circle">3</div>
                        <h5 class="fw-bold mb-0 text-dark">Substansi & Rencana Usulan</h5>
                    </div>
                    <a href="<?= site_url('dosen/proposals/step/3/' . esc($proposalUuid)) ?>" class="btn btn-light-soft btn-sm rounded-pill px-3">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                </div>
                <div class="card-body p-4 pt-0">
                    <!-- Abstrak -->
                    <div class="bg-light rounded-4 p-4 mb-4 border border-dashed border-primary">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-card-text me-2"></i>Abstrak</h6>
                        <div class="text-muted lh-lg">
                            <?= !empty($step3Summary['abstrak']) ? $step3Summary['abstrak'] : '<p class="mb-0 text-muted fst-italic">Abstrak belum diisi.</p>' ?>
                        </div>
                    </div>

                    <!-- Detail Bagian -->
                    <?php if (!empty($step3Summary['substansi_bagian'])): ?>
                        <div class="row g-4">
                            <?php foreach ($step3Summary['substansi_bagian'] as $index => $section): ?>
                                <div class="col-12">
                                    <div class="border rounded-4 p-4 h-100 bg-white shadow-xs">
                                        <h6 class="fw-bold text-primary mb-3"><?= esc($section['judul_bagian'] ?? ('Bagian ' . ($index + 1))) ?></h6>
                                        <div class="text-dark lh-base">
                                            <?= !empty($section['isi_bagian']) ? $section['isi_bagian'] : '<p class="mb-0 text-muted small">Isi bagian belum tersedia.</p>' ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- STEP 4: BERKAS -->
            <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="step-badge bg-danger text-white rounded-circle">4</div>
                        <h5 class="fw-bold mb-0 text-dark">Kelengkapan Berkas</h5>
                    </div>
                    <a href="<?= site_url('dosen/proposals/step/4/' . esc($proposalUuid)) ?>" class="btn btn-light-soft btn-sm rounded-pill px-3">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3">
                        <?php if (empty($documents)): ?>
                            <div class="col-12">
                                <div class="text-center py-5 border rounded-4 bg-light">
                                    <i class="bi bi-folder-x display-4 text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Belum ada dokumen yang tersimpan.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($documents as $document): ?>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card border shadow-none rounded-4 h-100 bg-light-soft">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="file-icon bg-white text-danger shadow-sm rounded-3">
                                                    <i class="bi bi-file-earmark-pdf fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h6 class="mb-1 fw-bold text-dark text-truncate small"><?= esc($document['label']) ?></h6>
                                                    <p class="text-muted x-small mb-0"><?= esc($document['file_size_label']) ?></p>
                                                </div>
                                                <a href="<?= esc((string) $document['view_url']) ?>" target="_blank" class="btn btn-white btn-sm rounded-pill shadow-sm border">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- STEP 5: LUARAN & DANA -->
            <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
                <div class="card-header bg-white border-bottom-0 py-4 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="step-badge bg-warning text-white rounded-circle">5</div>
                        <h5 class="fw-bold mb-0 text-dark">Data Luaran & Anggaran</h5>
                    </div>
                    <a href="<?= site_url('dosen/proposals/step/5/' . esc($proposalUuid)) ?>" class="btn btn-light-soft btn-sm rounded-pill px-3">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <h6 class="fw-bold text-dark mb-3">Informasi Publikasi Target</h6>
                            <div class="list-group list-group-flush rounded-4 border overflow-hidden">
                                <div class="list-group-item py-3">
                                    <div class="row align-items-center">
                                        <div class="col-4 text-muted small">ISSN / Target</div>
                                        <div class="col-8 fw-bold"><?= esc($step5Summary['issn'] ?? '-') ?></div>
                                    </div>
                                </div>
                                <div class="list-group-item py-3">
                                    <div class="row align-items-center">
                                        <div class="col-4 text-muted small">Nama Jurnal</div>
                                        <div class="col-8 fw-medium"><?= esc($step5Summary['nama_jurnal'] ?? '-') ?></div>
                                    </div>
                                </div>
                                <div class="list-group-item py-3">
                                    <div class="row">
                                        <div class="col-4 text-muted small">Profil Jurnal</div>
                                        <div class="col-8 text-muted small"><?= !empty($step5Summary['profil_jurnal']) ? $step5Summary['profil_jurnal'] : 'Belum diisi' ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card border-0 bg-primary-soft rounded-4 h-100">
                                <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                                    <h6 class="text-primary fw-bold text-uppercase ls-2 mb-2">Total Pengajuan Dana</h6>
                                    <h2 class="fw-bold text-primary mb-0 display-6">
                                        <?= !empty($step5Summary['total_pengajuan_dana']) ? 'Rp ' . number_format((float) $step5Summary['total_pengajuan_dana'], 0, ',', '.') : '-' ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- External Links -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="fw-bold text-dark mb-3">Tautan Pendukung</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($step5Summary['links'] ?? [] as $link): ?>
                                <?php if (!empty($link['url'])): ?>
                                    <a href="<?= esc((string) $link['url']) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-link-45deg me-1"></i> <?= esc($link['label'] ?? 'Link') ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Final Warning -->
            <div class="card border-0 bg-warning-soft rounded-4 mb-5 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="warning-icon bg-warning text-white rounded-circle shadow-sm">
                            <i class="bi bi-shield-lock-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Konfirmasi Akhir</h5>
                            <p class="text-dark mb-0 opacity-75">Setelah menekan tombol <strong>Submit Proposal</strong>, Anda tidak dapat mengubah data ini lagi. Sistem akan langsung meneruskan proposal Anda ke tahap verifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Area -->
            <div class="d-flex justify-content-between align-items-center py-4 border-top">
                <a href="<?= site_url('dosen/proposals/step/5/' . esc($proposalUuid)) ?>" class="btn btn-light rounded-pill px-4 py-2 border shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i>Kembali Edit
                </a>

                <form method="POST" action="<?= site_url('dosen/proposals/submit/' . esc($proposalUuid)) ?>" id="proposalReviewForm" data-confirm-title="Submit proposal ini?" data-confirm-message="Proposal akan terkirim untuk review dan tidak bisa diubah lagi.">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 shadow-lg">
                        <i class="bi bi-send-check-fill me-2"></i>Submit Proposal Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Styling Utilities */
    .bg-primary-soft { background-color: rgba(var(--bs-primary-rgb), 0.1); }
    .bg-warning-soft { background-color: rgba(var(--bs-warning-rgb), 0.1); }
    .bg-light-soft { background-color: #f8f9fa; }
    .ls-1 { letter-spacing: 1px; }
    .ls-2 { letter-spacing: 2px; }
    .x-small { font-size: 0.75rem; }
    .stat-icon { width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; }
    .step-badge { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem; }
    .file-icon { width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .warning-icon { width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .btn-light-soft { background-color: #f1f3f5; color: #495057; border: none; }
    .btn-light-soft:hover { background-color: #e9ecef; }
    .btn-white { background: white; border: none; }
    .shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .last-child-mb-0:last-child { margin-bottom: 0 !important; }
    
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 1.2rem; line-height: 1; vertical-align: middle; }
    
    .proposal-review-page .table td { border-color: #f1f1f1; }
    .border-dashed { border-style: dashed !important; }
</style>

<?php $this->section('scripts'); ?>
<script src="<?= base_url('custom/js/proposal-review.js') ?>?v=20260427-01"></script>
<?php $this->endSection(); ?>

<?php $this->endSection(); ?>