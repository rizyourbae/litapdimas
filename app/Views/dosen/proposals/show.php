<?php

/**
 * dosen/proposals/show.php
 * Display proposal details (submitted or draft)
 */
$this->extend('layouts/main');
$this->section('content');

$summary = $proposal['summary'] ?? [];
$review = $proposal['review'] ?? [];
$sections = $review['substansi_bagian'] ?? [];
$overviewCards = $proposal['overview_cards'] ?? [];
$teamSections = $proposal['team_sections'] ?? [];
$reviewSummary = $proposal['review_summary'] ?? [];
$documentRows = $proposal['document_rows'] ?? [];

$renderJournalLink = static function (?string $url, string $label): string {
    if (empty($url)) {
        return '-';
    }

    $host = parse_url($url, PHP_URL_HOST) ?: $url;
    $host = preg_replace('/^www\./', '', (string) $host);

    return '<div class="journal-link-item">'
        . '<span class="journal-link-host">' . esc($host) . '</span>'
        . '<a href="' . esc($url) . '" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary journal-link-btn">'
        . '<i class="fas fa-arrow-up-right-from-square me-1"></i>' . esc($label)
        . '</a>'
        . '</div>';
};
?>

<?= view('components/dosen-hero', [
    'title' => 'Detail Proposal',
    'subtitle' => trim((string) ($proposal['status_label'] ?? 'Draft')) . ' · Ringkasan dan detail proposal',
    'icon' => 'fas fa-file-contract',
]) ?>

<div class="container-fluid proposal-show-page">
    <style>
        .proposal-show-page .detail-card {
            border: 0;
            border-radius: 0.9rem;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        }

        .proposal-show-page .meta-pill {
            border-radius: 999px;
            padding: 0.35rem 0.75rem;
            background: #f1f5f9;
            color: #0f172a;
            font-size: 0.82rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .proposal-show-page .tab-nav {
            border-bottom: 1px solid #e2e8f0;
            gap: 0.25rem;
        }

        .proposal-show-page .tab-nav .nav-link {
            border: 0;
            border-bottom: 2px solid transparent;
            border-radius: 0;
            color: #334155;
            font-weight: 500;
            padding: 0.85rem 1rem;
        }

        .proposal-show-page .tab-nav .nav-link.active {
            color: #0f766e;
            border-bottom-color: #14b8a6;
            background: transparent;
        }

        .proposal-show-page .content-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.8rem;
            background: #fff;
            padding: 1rem 1.1rem;
        }

        .proposal-show-page .summary-stack {
            display: grid;
            gap: 1rem;
        }

        .proposal-show-page .summary-overview-grid {
            display: grid;
            gap: 0.9rem;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 1rem;
        }

        .proposal-show-page .summary-overview-card {
            border: 1px solid #dbeafe;
            border-radius: 0.85rem;
            background: linear-gradient(180deg, #f8fbff 0%, #eef6ff 100%);
            padding: 0.95rem 1rem;
        }

        .proposal-show-page .summary-overview-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            margin-bottom: 0.45rem;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }

        .proposal-show-page .summary-overview-value {
            color: #0f172a;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.35;
        }

        .proposal-show-page .summary-panel-title {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.95rem;
        }

        .proposal-show-page .summary-panel-dot {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #e0f2fe 0%, #bfdbfe 100%);
            box-shadow: inset 0 0 0 1px rgba(147, 197, 253, 0.65);
            flex-shrink: 0;
        }

        .proposal-show-page .summary-list {
            display: grid;
            gap: 0.8rem;
        }

        .proposal-show-page .summary-item {
            display: grid;
            gap: 0.35rem;
            grid-template-columns: minmax(160px, 190px) minmax(0, 1fr);
            align-items: start;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #eef2f7;
        }

        .proposal-show-page .summary-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .proposal-show-page .summary-item-label {
            color: #64748b;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .proposal-show-page .summary-item-value {
            color: #0f172a;
            font-weight: 500;
            min-width: 0;
        }

        .proposal-show-page .summary-item-value.muted {
            color: #64748b;
            font-weight: 400;
        }

        .proposal-show-page .summary-abstract {
            border: 1px solid #e2e8f0;
            border-radius: 0.85rem;
            padding: 1rem;
            background: #f8fafc;
        }

        .proposal-show-page .summary-note-box {
            border: 1px dashed #cbd5e1;
            border-radius: 0.85rem;
            background: #fff;
            padding: 0.95rem 1rem;
        }

        .proposal-show-page .summary-note-label {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.4rem;
        }

        .proposal-show-page .summary-note-body {
            color: #64748b;
            line-height: 1.65;
        }

        .proposal-show-page .summary-table {
            color: #334155;
        }

        .proposal-show-page .summary-table thead th {
            background: #f8fafc;
            color: #0f172a;
            font-weight: 700;
            white-space: nowrap;
        }

        .proposal-show-page .summary-table tbody td {
            vertical-align: top;
        }

        .proposal-show-page .summary-document-file {
            font-weight: 600;
            color: #0f172a;
        }

        .proposal-show-page .summary-document-meta {
            color: #64748b;
            font-size: 0.82rem;
        }

        .proposal-show-page .empty-pane {
            border: 1px dashed #cbd5e1;
            border-radius: 0.85rem;
            padding: 2rem 1.25rem;
            text-align: center;
            color: #64748b;
            background: #f8fafc;
        }

        .proposal-show-page .rich-content {
            color: #334155;
            line-height: 1.7;
        }

        .proposal-show-page .rich-content p:last-child {
            margin-bottom: 0;
        }

        .proposal-show-page .journal-link-item {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.65rem;
        }

        .proposal-show-page .journal-link-host {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.82rem;
            font-weight: 600;
            max-width: 100%;
            word-break: break-all;
        }

        .proposal-show-page .journal-link-btn {
            border-radius: 999px;
            white-space: nowrap;
        }

        @media (max-width: 991.98px) {
            .proposal-show-page .summary-overview-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .proposal-show-page .summary-overview-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .proposal-show-page .summary-item {
                grid-template-columns: minmax(0, 1fr);
            }
        }
    </style>

    <div class="card detail-card dosen-form-card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="fas fa-file-alt me-1"></i> Ringkasan Proposal</h5>
            <span class="badge <?= esc($proposal['status_badge_class']) ?>"><?= esc($proposal['status_label']) ?></span>
        </div>

        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="meta-pill"><i class="fas fa-clock"></i> Dibuat: <?= esc($proposal['created_at_formatted']) ?></span>
                <span class="meta-pill"><i class="fas fa-chart-line"></i> Status: <?= esc($proposal['status_label']) ?></span>
                <span class="meta-pill"><i class="fas fa-list-ol"></i> Step Saat Ini: <?= esc((string) ($proposal['current_step'] ?? 1)) ?>/5</span>
            </div>

            <ul class="nav nav-tabs tab-nav mb-4" id="proposalDetailTabs" role="tablist">
                <li class="nav-item" role="presentation"><button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-pane" type="button" role="tab" aria-controls="summary-pane" aria-selected="true"><i class="far fa-file-lines me-1"></i>Summary</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review-pane" type="button" role="tab" aria-controls="review-pane" aria-selected="false"><i class="fas fa-users-viewfinder me-1"></i>Review</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="logbook-tab" data-bs-toggle="tab" data-bs-target="#logbook-pane" type="button" role="tab" aria-controls="logbook-pane" aria-selected="false"><i class="fas fa-book me-1"></i>Logbook</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="outputs-tab" data-bs-toggle="tab" data-bs-target="#outputs-pane" type="button" role="tab" aria-controls="outputs-pane" aria-selected="false"><i class="fas fa-table-list me-1"></i>Outputs</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance-pane" type="button" role="tab" aria-controls="finance-pane" aria-selected="false"><i class="fas fa-wallet me-1"></i>Laporan Keuangan</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="outcomes-tab" data-bs-toggle="tab" data-bs-target="#outcomes-pane" type="button" role="tab" aria-controls="outcomes-pane" aria-selected="false"><i class="fas fa-desktop me-1"></i>Outcomes</button></li>
            </ul>

            <div class="tab-content mb-4" id="proposalDetailTabsContent">
                <div class="tab-pane fade show active" id="summary-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                    <div class="summary-overview-grid">
                        <?php foreach ($overviewCards as $card): ?>
                            <div class="summary-overview-card">
                                <div class="summary-overview-label"><i class="<?= esc($card['icon'] ?? 'fas fa-circle') ?>"></i> <?= esc($card['label'] ?? '-') ?></div>
                                <div class="summary-overview-value"><?= esc($card['value'] ?? '-') ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-stack">
                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Ringkasan Proposal</div>
                            <div class="summary-list">
                                <div class="summary-item">
                                    <div class="summary-item-label">Judul Proposal</div>
                                    <div class="summary-item-value"><?= esc($summary['judul'] ?? '-') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Kata Kunci</div>
                                    <div class="summary-item-value"><?= esc($summary['kata_kunci'] ?? '-') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Pengelola Bantuan</div>
                                    <div class="summary-item-value"><?= esc($summary['pengelola_bantuan'] ?? '-') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Klaster Bantuan</div>
                                    <div class="summary-item-value"><?= esc($summary['klaster_bantuan'] ?? '-') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Bidang Ilmu</div>
                                    <div class="summary-item-value"><?= esc($summary['bidang_ilmu'] ?? '-') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Tema Penelitian</div>
                                    <div class="summary-item-value"><?= esc($summary['tema_penelitian'] ?? '-') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Jenis Penelitian</div>
                                    <div class="summary-item-value"><?= esc($summary['jenis_penelitian'] ?? '-') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Kontribusi Prodi</div>
                                    <div class="summary-item-value"><?= esc($summary['kontribusi_prodi'] ?? '-') ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Ringkasan Ulasan</div>
                            <div class="summary-abstract rich-content"><?= !empty($reviewSummary['abstrak'] ?? '') ? $reviewSummary['abstrak'] : '<p class="mb-0 text-muted">Abstrak belum diisi.</p>' ?></div>
                            <div class="summary-note-box mt-3">
                                <div class="summary-note-label">Catatan Validator</div>
                                <div class="summary-note-body"><?= esc($reviewSummary['validator_notes_display'] ?? '') ?></div>
                            </div>
                        </div>

                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Peneliti (PTKI)</div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle summary-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Jabatan</th>
                                            <th>Nama</th>
                                            <th>NIP</th>
                                            <th>NIDN</th>
                                            <th>Institusi</th>
                                            <th>ID Peneliti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($teamSections[0]['rows'] ?? [])): ?>
                                            <tr>
                                                <td class="text-center text-muted py-4" colspan="6">Data peneliti belum diisi.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($teamSections[0]['rows'] as $row): ?><tr><?php foreach (($row['cells'] ?? []) as $cell): ?><td><?= esc((string) $cell) ?></td><?php endforeach; ?></tr><?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Mahasiswa Pembantu Peneliti</div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle summary-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIM</th>
                                            <th>Nama</th>
                                            <th>Program Studi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($teamSections[1]['rows'] ?? [])): ?>
                                            <tr>
                                                <td class="text-center text-muted py-4" colspan="4">Data mahasiswa belum diisi.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($teamSections[1]['rows'] as $row): ?><tr><?php foreach (($row['cells'] ?? []) as $cell): ?><td><?= esc((string) $cell) ?></td><?php endforeach; ?></tr><?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Anggota Peneliti PTU / Profesional</div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle summary-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>NIDN / NIK</th>
                                            <th>Nama Peneliti</th>
                                            <th>Institusi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($teamSections[2]['rows'] ?? [])): ?>
                                            <tr>
                                                <td class="text-center text-muted py-4" colspan="4">Data anggota eksternal belum diisi.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($teamSections[2]['rows'] as $row): ?><tr><?php foreach (($row['cells'] ?? []) as $cell): ?><td><?= esc((string) $cell) ?></td><?php endforeach; ?></tr><?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Publikasi Tujuan</div>
                            <div class="summary-list">
                                <div class="summary-item">
                                    <div class="summary-item-label">ISSN</div>
                                    <div class="summary-item-value"><?= esc($summary['issn'] ?: '-') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Nama Jurnal</div>
                                    <div class="summary-item-value"><?= esc($summary['nama_jurnal'] ?: '-') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Website Jurnal</div>
                                    <div class="summary-item-value"><?= $renderJournalLink($summary['url_website'] ?? null, 'Buka Website Jurnal') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Scopus / WoS</div>
                                    <div class="summary-item-value"><?= $renderJournalLink($summary['url_scopus_wos'] ?? null, 'Buka Scopus/WoS') ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Surat Rekomendasi</div>
                                    <div class="summary-item-value"><?= $renderJournalLink($summary['url_surat_rekomendasi'] ?? null, 'Buka Surat Rekomendasi') ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Dokumen Proposal</div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle summary-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nama Berkas</th>
                                            <th>Berkas Terunggah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($documentRows as $row): ?>
                                            <tr>
                                                <td>
                                                    <div class="summary-document-file"><?= esc($row['label'] ?? '-') ?></div>
                                                    <div class="summary-document-meta"><?= esc($row['file_name'] ?? '-') ?><?= !empty($row['file_size_label']) ? ' · ' . esc($row['file_size_label']) : '' ?></div>
                                                </td>
                                                <td><?php if (!empty($row['has_file'])): ?><a href="<?= esc($row['view_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-info"><i class="fas fa-file-arrow-down me-1"></i>Lihat Berkas</a><?php else: ?><span class="text-muted">Belum diunggah</span><?php endif; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="review-pane" role="tabpanel" aria-labelledby="review-tab" tabindex="0">
                    <div class="content-card">
                        <div class="section-label">Judul Proposal</div>
                        <div class="rich-content"><?= esc($review['judul'] ?? '-') ?></div>
                    </div>
                    <div class="content-card">
                        <div class="section-label">Abstrak</div>
                        <div class="rich-content"><?= !empty($review['abstrak']) ? $review['abstrak'] : '<p class="mb-0 text-muted">Abstrak belum diisi.</p>' ?></div>
                    </div>
                    <div class="content-card">
                        <div class="section-label">Bagian Substansi</div>
                        <?php if (empty($sections)): ?>
                            <div class="text-muted">Bagian substansi belum diisi.</div>
                        <?php else: ?>
                            <?php foreach ($sections as $index => $section): ?>
                                <div class="border rounded-3 p-3 <?= $index < count($sections) - 1 ? 'mb-3' : '' ?>">
                                    <div class="fw-semibold mb-2"><?= esc($section['judul_bagian'] ?? ('Bagian ' . ($index + 1))) ?></div>
                                    <div class="rich-content"><?= !empty($section['isi_bagian']) ? $section['isi_bagian'] : '<p class="mb-0 text-muted">Isi bagian belum tersedia.</p>' ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="logbook-pane" role="tabpanel" aria-labelledby="logbook-tab" tabindex="0">
                    <div class="empty-pane">
                        <h6 class="mb-2">Logbook</h6>
                        <p class="mb-0">Konten logbook akan ditambahkan di sini.</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="outputs-pane" role="tabpanel" aria-labelledby="outputs-tab" tabindex="0">
                    <div class="empty-pane">
                        <h6 class="mb-2">Outputs</h6>
                        <p class="mb-0">Konten outputs akan ditambahkan di sini.</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="finance-pane" role="tabpanel" aria-labelledby="finance-tab" tabindex="0">
                    <div class="empty-pane">
                        <h6 class="mb-2">Laporan Keuangan</h6>
                        <p class="mb-0">Konten laporan keuangan akan ditambahkan di sini.</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="outcomes-pane" role="tabpanel" aria-labelledby="outcomes-tab" tabindex="0">
                    <div class="empty-pane">
                        <h6 class="mb-2">Outcomes</h6>
                        <p class="mb-0">Konten outcomes akan ditambahkan di sini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>