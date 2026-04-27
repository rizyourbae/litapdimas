<?php

/**
 * dosen/proposals/review.php
 * Review page - ringkasan proposal sebelum submit
 */
$this->extend('layouts/main');
$this->section('content');

$overviewCards = $proposal['review_overview_cards'] ?? [];
$step1Items = $proposal['review_step1_items'] ?? [];
$step2Sections = $proposal['review_step2_sections'] ?? [];
$step3Summary = $proposal['review_step3_summary'] ?? [];
$step5Summary = $proposal['review_step5_summary'] ?? [];
$documents = $proposal['documents'] ?? [];
?>

<?= view('components/dosen-hero', [
    'title' => 'Review Proposal',
    'subtitle' => 'Cek kembali isi proposal, pastikan semua data sudah benar sebelum submit final.',
    'icon' => 'fas fa-check-double',
]) ?>

<div class="container-fluid proposal-review-page">
    <style>
        .proposal-review-page .review-card {
            border: 0;
            border-radius: 0.95rem;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        }

        .proposal-review-page .review-overview-grid {
            display: grid;
            gap: 0.9rem;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 1rem;
        }

        .proposal-review-page .review-overview-card {
            border: 1px solid #dbeafe;
            border-radius: 0.85rem;
            background: linear-gradient(180deg, #f8fbff 0%, #eef6ff 100%);
            padding: 0.95rem 1rem;
        }

        .proposal-review-page .review-overview-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            margin-bottom: 0.45rem;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }

        .proposal-review-page .review-overview-value {
            color: #0f172a;
            font-size: 1rem;
            font-weight: 700;
        }

        .proposal-review-page .review-section {
            border: 1px solid #e2e8f0;
            border-radius: 0.85rem;
            padding: 1rem;
            background: #fff;
        }

        .proposal-review-page .review-section+.review-section {
            margin-top: 1rem;
        }

        .proposal-review-page .review-section-title {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.95rem;
        }

        .proposal-review-page .review-section-dot {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #dbeafe 0%, #bfdbfe 100%);
            box-shadow: inset 0 0 0 1px rgba(147, 197, 253, 0.65);
            flex-shrink: 0;
        }

        .proposal-review-page .review-list {
            display: grid;
            gap: 0.8rem;
        }

        .proposal-review-page .review-item-row {
            display: grid;
            gap: 0.35rem;
            grid-template-columns: minmax(150px, 180px) minmax(0, 1fr);
            align-items: start;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #eef2f7;
        }

        .proposal-review-page .review-item-row:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .proposal-review-page .review-label {
            color: #64748b;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .proposal-review-page .review-value {
            color: #0f172a;
            font-weight: 500;
            min-width: 0;
        }

        .proposal-review-page .review-table {
            color: #334155;
        }

        .proposal-review-page .review-table thead th {
            background: #f8fafc;
            color: #0f172a;
            font-weight: 700;
            white-space: nowrap;
        }

        .proposal-review-page .review-table tbody td {
            vertical-align: top;
        }

        .proposal-review-page .review-link-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .proposal-review-page .review-link-host {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.82rem;
            font-weight: 600;
            word-break: break-all;
        }

        .proposal-review-page .review-link-btn {
            border-radius: 999px;
            white-space: nowrap;
        }

        .proposal-review-page .review-rich {
            color: #334155;
            line-height: 1.7;
        }

        .proposal-review-page .review-rich p:last-child {
            margin-bottom: 0;
        }

        .proposal-review-page .review-note {
            border: 1px dashed #cbd5e1;
            border-radius: 0.85rem;
            padding: 1rem;
            background: #f8fafc;
        }

        .proposal-review-page .review-note-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.4rem;
        }

        .proposal-review-page .review-note-body {
            color: #64748b;
            line-height: 1.65;
        }

        .proposal-review-page .warning-box {
            border: 1px solid #fcd34d;
            background: #fffbeb;
            border-radius: 0.8rem;
            padding: 0.9rem 1rem;
        }

        .proposal-review-page .action-bar {
            border-top: 1px solid #e5e7eb;
            padding-top: 1rem;
        }

        @media (max-width: 991.98px) {
            .proposal-review-page .review-overview-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .proposal-review-page .review-overview-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .proposal-review-page .review-item-row {
                grid-template-columns: minmax(0, 1fr);
            }
        }
    </style>

    <div class="card review-card dosen-form-card">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="fas fa-list-check me-1"></i> Ringkasan Proposal</h5>
            <span class="badge text-bg-light">Final Check</span>
        </div>

        <div class="card-body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i> <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="review-overview-grid">
                <?php foreach ($overviewCards as $card): ?>
                    <div class="review-overview-card">
                        <div class="review-overview-label"><i class="<?= esc($card['icon'] ?? 'fas fa-circle') ?>"></i> <?= esc($card['label'] ?? '-') ?></div>
                        <div class="review-overview-value"><?= esc($card['value'] ?? '-') ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="review-section">
                <div class="review-section-title"><span class="review-section-dot" aria-hidden="true"></span>Step 1: Pernyataan Peneliti</div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle review-table mb-0">
                        <tbody>
                            <?php foreach ($step1Items as $item): ?>
                                <tr>
                                    <td class="fw-semibold" style="width: 26%;"><?= esc($item['label'] ?? '-') ?></td>
                                    <td><?= esc($item['value'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="review-section">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div class="review-section-title mb-0"><span class="review-section-dot" aria-hidden="true"></span>Step 2: Data Peneliti</div>
                    <a href="<?= site_url('dosen/proposals/step/2/' . esc($proposalUuid)) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                </div>

                <?php foreach ($step2Sections as $section): ?>
                    <div class="review-section review-section--inner">
                        <div class="review-section-title"><span class="review-section-dot" aria-hidden="true"></span><?= esc($section['title'] ?? '-') ?></div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle review-table mb-0">
                                <thead>
                                    <tr>
                                        <?php foreach (($section['columns'] ?? []) as $column): ?>
                                            <th><?= esc($column) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($section['rows'])): ?>
                                        <tr>
                                            <td class="text-center text-muted py-4" colspan="<?= esc((string) ($section['colspan'] ?? 1)) ?>"><?= esc($section['empty_message'] ?? 'Data belum tersedia.') ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($section['rows'] as $row): ?>
                                            <tr>
                                                <?php foreach (($row['cells'] ?? []) as $cell): ?>
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

            <div class="review-section">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div class="review-section-title mb-0"><span class="review-section-dot" aria-hidden="true"></span>Step 3: Substansi Usulan</div>
                    <a href="<?= site_url('dosen/proposals/step/3/' . esc($proposalUuid)) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                </div>

                <div class="review-section review-section--inner mb-3">
                    <div class="review-section-title"><span class="review-section-dot" aria-hidden="true"></span>Abstrak</div>
                    <div class="review-rich">
                        <?= !empty($step3Summary['abstrak']) ? $step3Summary['abstrak'] : '<p class="mb-0 text-muted">Abstrak belum diisi.</p>' ?>
                    </div>
                </div>

                <div class="review-section review-section--inner">
                    <div class="review-section-title"><span class="review-section-dot" aria-hidden="true"></span>Bagian Substansi</div>
                    <?php if (empty($step3Summary['substansi_bagian'])): ?>
                        <p class="text-muted mb-0">Belum ada bagian substansi.</p>
                    <?php else: ?>
                        <div class="review-list">
                            <?php foreach ($step3Summary['substansi_bagian'] as $index => $section): ?>
                                <div class="review-item-row">
                                    <div class="review-label"><?= esc($section['judul_bagian'] ?? ('Bagian ' . ($index + 1))) ?></div>
                                    <div class="review-rich"><?= !empty($section['isi_bagian']) ? $section['isi_bagian'] : '<p class="mb-0 text-muted">Isi bagian belum tersedia.</p>' ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="review-section">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div class="review-section-title mb-0"><span class="review-section-dot" aria-hidden="true"></span>Step 4: Unggah Berkas</div>
                    <a href="<?= site_url('dosen/proposals/step/4/' . esc($proposalUuid)) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle review-table mb-0">
                        <thead>
                            <tr>
                                <th>Nama Berkas</th>
                                <th>Berkas Terunggah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($documents)): ?>
                                <tr>
                                    <td class="text-center text-muted py-4" colspan="2">Belum ada dokumen yang tersimpan. Silakan unggah ulang pada Step 4.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($documents as $document): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?= esc($document['label']) ?></div>
                                            <div class="small text-muted"><?= esc($document['file_size_label']) ?></div>
                                        </td>
                                        <td>
                                            <div class="review-link-chip">
                                                <span class="review-link-host"><?= esc((string) ($document['file_name'] ?? '-')) ?></span>
                                                <a href="<?= esc($document['view_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary review-link-btn"><i class="fas fa-eye me-1"></i>Lihat Berkas</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="review-section mb-3">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div class="review-section-title mb-0"><span class="review-section-dot" aria-hidden="true"></span>Step 5: Data Jurnal</div>
                    <a href="<?= site_url('dosen/proposals/step/5/' . esc($proposalUuid)) ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                </div>

                <div class="review-list">
                    <div class="review-item-row">
                        <div class="review-label">ISSN</div>
                        <div class="review-value"><?= esc($step5Summary['issn'] ?? '-') ?></div>
                    </div>
                    <div class="review-item-row">
                        <div class="review-label">Nama Jurnal</div>
                        <div class="review-value"><?= esc($step5Summary['nama_jurnal'] ?? '-') ?></div>
                    </div>
                    <div class="review-item-row">
                        <div class="review-label">Profil Jurnal</div>
                        <div class="review-rich"><?= !empty($step5Summary['profil_jurnal']) ? $step5Summary['profil_jurnal'] : '<span class="text-muted">Belum diisi</span>' ?></div>
                    </div>
                    <div class="review-item-row">
                        <div class="review-label">Total Pengajuan Dana</div>
                        <div class="review-value"><?= !empty($step5Summary['total_pengajuan_dana']) ? 'Rp ' . number_format((float) $step5Summary['total_pengajuan_dana'], 0, ',', '.') : '-' ?></div>
                    </div>
                    <?php foreach ($step5Summary['links'] ?? [] as $link): ?>
                        <div class="review-item-row">
                            <div class="review-label"><?= esc($link['label'] ?? '-') ?></div>
                            <div class="review-value">
                                <?php if (!empty($link['has_url'])): ?>
                                    <div class="review-link-chip">
                                        <span class="review-link-host"><?= esc($link['host'] ?? '-') ?></span>
                                        <a href="<?= esc($link['url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary review-link-btn"><i class="fas fa-arrow-up-right-from-square me-1"></i>Buka</a>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="warning-box mb-4">
                <i class="fas fa-triangle-exclamation text-warning me-1"></i>
                Setelah Anda submit proposal, data tidak dapat diubah. Pastikan seluruh informasi sudah benar.
            </div>

            <div class="action-bar d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="<?= site_url('dosen/proposals/step/5/' . esc($proposalUuid)) ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-left me-1"></i> Kembali Edit
                </a>

                <form method="POST" action="<?= site_url('dosen/proposals/submit/' . esc($proposalUuid)) ?>" class="m-0" id="proposalReviewForm" data-confirm-title="Submit proposal ini?" data-confirm-message="Setelah submit, proposal akan terkirim untuk review dan tidak bisa diubah lagi.">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-paper-plane me-1"></i> Submit Proposal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->section('scripts'); ?>
<script src="<?= base_url('custom/js/proposal-review.js') ?>?v=20260427-01"></script>
<?php $this->endSection(); ?>

<?php $this->endSection(); ?>