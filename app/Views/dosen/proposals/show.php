<?php
/** @var array<string,mixed> $proposal */

$this->extend('layouts/main');
$hide_header = true;

$this->section('styles');
?>
<link rel="stylesheet" href="<?= base_url('custom/css/dosen-proposal-show.css') ?>">
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>

<?php
$summary = $proposal['summary'] ?? [];
$review = $proposal['review'] ?? [];
$sections = $review['substansi_bagian'] ?? [];
$overviewCards = $proposal['overview_cards'] ?? [];
$teamSections = $proposal['team_sections'] ?? [];
$reviewSummary = $proposal['review_summary'] ?? [];
$documentRows = $proposal['document_rows'] ?? [];
?>

<?= view('components/dosen-hero', [
    'title' => 'Detail Proposal',
    'subtitle' => trim((string) ($proposal['status_label'] ?? 'Draft')) . ' · Ringkasan dan detail proposal',
    'icon' => 'bi bi-file-earmark-medical',
]) ?>

<div class="container-fluid proposal-show-page px-0">
    <div class="card detail-card dosen-form-card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3 px-4">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Ringkasan Proposal</h5>
            <span class="badge <?= esc((string) $proposal['status_badge_class']) ?> px-3 py-2"><?= esc((string) $proposal['status_label']) ?></span>
        </div>

        <div class="card-body p-4">
            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="meta-pill"><i class="bi bi-clock"></i> Dibuat: <?= format_indo($proposal['created_at']) ?></span>
                <span class="meta-pill"><i class="bi bi-graph-up"></i> Status: <?= esc((string) $proposal['status_label']) ?></span>
                <span class="meta-pill"><i class="bi bi-list-ol"></i> Langkah Saat Ini: <?= esc((string) ($proposal['current_step'] ?? 1)) ?>/5</span>
            </div>

            <ul class="nav nav-tabs tab-nav mb-4" id="proposalDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-pane" type="button" role="tab" aria-controls="summary-pane" aria-selected="true">
                        <i class="bi bi-file-text me-1"></i>Summary
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review-pane" type="button" role="tab" aria-controls="review-pane" aria-selected="false">
                        <i class="bi bi-person-search me-1"></i>Review
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="logbook-tab" data-bs-toggle="tab" data-bs-target="#logbook-pane" type="button" role="tab" aria-controls="logbook-pane" aria-selected="false">
                        <i class="bi bi-book me-1"></i>Logbook
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="outputs-tab" data-bs-toggle="tab" data-bs-target="#outputs-pane" type="button" role="tab" aria-controls="outputs-pane" aria-selected="false">
                        <i class="bi bi-table me-1"></i>Outputs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance-pane" type="button" role="tab" aria-controls="finance-pane" aria-selected="false">
                        <i class="bi bi-wallet2 me-1"></i>Laporan Keuangan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="outcomes-tab" data-bs-toggle="tab" data-bs-target="#outcomes-pane" type="button" role="tab" aria-controls="outcomes-pane" aria-selected="false">
                        <i class="bi bi-display me-1"></i>Outcomes
                    </button>
                </li>
            </ul>

            <div class="tab-content mb-4" id="proposalDetailTabsContent">
                <div class="tab-pane fade show active" id="summary-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                    <?php if ($proposal['admin_decision']['is_decided'] ?? false): ?>
                        <div class="alert <?= ($proposal['status'] ?? '') === 'approved' ? 'alert-success' : 'alert-danger' ?> border-0 shadow-sm rounded-4 p-4 mb-4">
                            <div class="d-flex gap-3">
                                <div class="fs-1">
                                    <i class="bi <?= ($proposal['status'] ?? '') === 'approved' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' ?>"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Keputusan Akhir Admin: <?= esc((string) ($proposal['status_label'] ?? '')) ?></h5>
                                    <div class="small opacity-75 mb-3">Diterima pada <?= esc((string) ($proposal['admin_decision']['decided_at'] ?? '')) ?></div>
                                    <div class="bg-white bg-opacity-50 p-3 rounded-3 text-dark">
                                        <div class="small fw-bold text-uppercase mb-1" style="font-size: 0.65rem; opacity: 0.7;">Catatan Admin:</div>
                                        <div class="lh-sm"><?= nl2br(esc((string) ($proposal['admin_decision']['notes'] ?: 'Tidak ada catatan tambahan.'))) ?></div>
                                    </div>
                                    <?php if (($proposal['status'] ?? '') === 'approved'): ?>
                                        <div class="mt-3">
                                            <span class="badge bg-white text-success px-3 py-2 rounded-pill small fw-bold">
                                                <i class="bi bi-rocket-takeoff-fill me-1"></i>Anda dapat mulai mengerjakan penelitian ini
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="summary-overview-grid">
                        <?php foreach ($overviewCards as $card): ?>
                            <div class="summary-overview-card">
                                <div class="summary-overview-label"><i class="<?= esc((string) ($card['icon'] ?? 'bi bi-circle')) ?>"></i> <?= esc((string) ($card['label'] ?? '-')) ?></div>
                                <div class="summary-overview-value"><?= esc((string) ($card['value'] ?? '-')) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-stack">
                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Ringkasan Proposal</div>
                            <div class="summary-list">
                                <div class="summary-item">
                                    <div class="summary-item-label">Judul Proposal</div>
                                    <div class="summary-item-value text-primary fw-bold"><?= esc((string) ($summary['judul'] ?? '-')) ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Kata Kunci</div>
                                    <div class="summary-item-value"><?= esc((string) ($summary['kata_kunci'] ?? '-')) ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Pengelola Bantuan</div>
                                    <div class="summary-item-value"><?= esc((string) ($summary['pengelola_bantuan'] ?? '-')) ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Klaster Bantuan</div>
                                    <div class="summary-item-value"><?= esc((string) ($summary['klaster_bantuan'] ?? '-')) ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Bidang Ilmu</div>
                                    <div class="summary-item-value"><?= esc((string) ($summary['bidang_ilmu'] ?? '-')) ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Tema Penelitian</div>
                                    <div class="summary-item-value"><?= esc((string) ($summary['tema_penelitian'] ?? '-')) ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Jenis Penelitian</div>
                                    <div class="summary-item-value"><?= esc((string) ($summary['jenis_penelitian'] ?? '-')) ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Kontribusi Prodi</div>
                                    <div class="summary-item-value"><?= esc((string) ($summary['kontribusi_prodi'] ?? '-')) ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Ringkasan Ulasan</div>
                            <div class="summary-abstract rich-content"><?= !empty($reviewSummary['abstrak'] ?? '') ? $reviewSummary['abstrak'] : '<p class="mb-0 text-muted">Abstrak belum diisi.</p>' ?></div>
                            <div class="summary-note-box mt-3">
                                <div class="summary-note-label">Catatan Validator</div>
                                <div class="summary-note-body"><?= esc((string) ($reviewSummary['validator_notes_display'] ?? '')) ?></div>
                            </div>
                        </div>

                        <?php foreach ($teamSections as $section): ?>
                            <div class="content-card">
                                <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span><?= esc((string) ($section['title'] ?? 'Tim')) ?></div>
                                <div class="table-responsive dosen-table-wrap">
                                    <table class="table table-sm table-bordered align-middle summary-table mb-0">
                                        <thead>
                                            <tr>
                                                <?php foreach (($section['headers'] ?? []) as $header): ?>
                                                    <th><?= esc((string) $header) ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($section['rows'] ?? [])): ?>
                                                <tr>
                                                    <td class="text-center text-muted py-4" colspan="<?= esc((string) ($section['colspan'] ?? 1)) ?>"><?= esc((string) ($section['empty_message'] ?? 'Data belum diisi.')) ?></td>
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

                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Publikasi Tujuan</div>
                            <div class="summary-list">
                                <div class="summary-item">
                                    <div class="summary-item-label">ISSN</div>
                                    <div class="summary-item-value fw-bold"><?= esc((string) ($summary['issn'] ?: '-')) ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Nama Jurnal</div>
                                    <div class="summary-item-value"><?= esc((string) ($summary['nama_jurnal'] ?: '-')) ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Website Jurnal</div>
                                    <div class="summary-item-value"><?= $summary['url_website_formatted'] ?? '-' ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Scopus / WoS</div>
                                    <div class="summary-item-value"><?= $summary['url_scopus_wos_formatted'] ?? '-' ?></div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-item-label">Surat Rekomendasi</div>
                                    <div class="summary-item-value"><?= $summary['url_surat_rekomendasi_formatted'] ?? '-' ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="content-card">
                            <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Dokumen Proposal</div>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-sm table-bordered align-middle summary-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nama Berkas</th>
                                            <th style="width: 180px;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($documentRows as $row): ?>
                                            <tr>
                                                <td>
                                                    <div class="summary-document-file"><?= esc((string) ($row['label'] ?? '-')) ?></div>
                                                    <div class="summary-document-meta"><?= esc((string) ($row['file_name'] ?? '-')) ?><?= !empty($row['file_size_label']) ? ' · ' . esc((string) $row['file_size_label']) : '' ?></div>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (!empty($row['has_file'])): ?>
                                                        <a href="<?= esc((string) $row['view_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-info text-white">
                                                            <i class="bi bi-file-earmark-arrow-down me-1"></i>Lihat Berkas
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted small">Belum diunggah</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="review-pane" role="tabpanel" aria-labelledby="review-tab" tabindex="0">
                    <?php if (!empty($proposal['reviewer_results'])): ?>
                        <div class="mb-5">
                            <h6 class="fw-bold text-dark text-uppercase small mb-4 ls-1 border-bottom pb-2">Hasil Evaluasi Reviewer</h6>
                            <div class="row g-4">
                                <?php foreach ($proposal['reviewer_results'] as $result): ?>
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                                            <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                                                <div class="fw-bold text-primary"><i class="bi bi-person-badge me-2"></i><?= esc((string) $result['reviewer_label']) ?></div>
                                                <span class="badge <?= esc((string) $result['recommendation_badge']) ?> rounded-pill px-3"><?= esc((string) $result['recommendation_label']) ?></span>
                                            </div>
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="small text-muted"><i class="bi bi-calendar-check me-1"></i><?= format_indo($result['reviewed_at']) ?></div>
                                                    <div class="small fw-bold">Skor: <span class="text-primary"><?= esc((string) $result['score']) ?></span></div>
                                                </div>
                                                <div class="bg-light p-3 rounded-3 small">
                                                    <div class="fw-bold text-uppercase mb-2 text-muted" style="font-size: 0.65rem;">Komentar / Catatan:</div>
                                                    <div class="lh-sm"><?= nl2br(esc((string) $result['notes'])) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="content-card">
                        <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Judul Proposal</div>
                        <div class="rich-content fw-bold text-primary"><?= esc((string) ($review['judul'] ?? '-')) ?></div>
                    </div>
                    <div class="content-card">
                        <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Abstrak</div>
                        <div class="rich-content"><?= !empty($review['abstrak']) ? $review['abstrak'] : '<p class="mb-0 text-muted">Abstrak belum diisi.</p>' ?></div>
                    </div>
                    <div class="content-card">
                        <div class="summary-panel-title"><span class="summary-panel-dot" aria-hidden="true"></span>Bagian Substansi</div>
                        <?php if (empty($sections)): ?>
                            <div class="text-muted">Bagian substansi belum diisi.</div>
                        <?php else: ?>
                            <?php foreach ($sections as $index => $section): ?>
                                <div class="border rounded-3 p-3 <?= $index < count($sections) - 1 ? 'mb-3' : '' ?>">
                                    <div class="fw-semibold mb-2 text-primary"><?= esc((string) ($section['judul_bagian'] ?? ('Bagian ' . ($index + 1)))) ?></div>
                                    <div class="rich-content"><?= !empty($section['isi_bagian']) ? $section['isi_bagian'] : '<p class="mb-0 text-muted">Isi bagian belum tersedia.</p>' ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="logbook-pane" role="tabpanel" aria-labelledby="logbook-tab" tabindex="0">
                    <?php if (($proposal['status'] ?? '') === 'approved'): ?>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="fw-bold mb-0">Logbook Penelitian</h6>
                                <p class="text-muted small mb-0">Catat setiap aktivitas penelitian yang Anda lakukan.</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#addLogbookModal">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Logbook
                            </button>
                        </div>

                        <div class="table-responsive dosen-table-wrap">
                            <table class="table table-sm table-bordered align-middle summary-table mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No</th>
                                        <th style="width: 120px;">Tanggal</th>
                                        <th style="width: 150px;">Tempat</th>
                                        <th>Kegiatan (Teknik)</th>
                                        <th>Deskripsi</th>
                                        <th class="text-center" style="width: 120px;">Berkas</th>
                                        <th class="text-center" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($proposal['logbooks'])): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                                                Belum ada data logbook.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($proposal['logbooks'] as $index => $log): ?>
                                            <tr>
                                                <td class="text-center"><?= $index + 1 ?></td>
                                                <td><?= format_indo($log['tanggal']) ?></td>
                                                <td><?= esc($log['tempat']) ?></td>
                                                <td>
                                                    <div class="fw-bold"><?= esc($log['nama_kegiatan']) ?></div>
                                                    <span class="badge bg-info-subtle text-info border border-info-subtle small"><?= esc($log['teknik']) ?></span>
                                                </td>
                                                <td><div class="small text-wrap" style="max-width: 300px;"><?= esc($log['deskripsi']) ?></div></td>
                                                <td class="text-center">
                                                    <?php if ($log['berkas_url']): ?>
                                                        <a href="<?= $log['berkas_url'] ?>" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-2 small">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> Lihat
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted small">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-link text-danger p-0 border-0" 
                                                        onclick="SwalDelete('<?= $log['delete_url'] ?>', 'Logbook <?= esc($log['tanggal']) ?>', 'Kegiatan: <?= esc($log['nama_kegiatan']) ?>')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-pane">
                            <i class="bi bi-lock fs-1 mb-3 d-block text-muted"></i>
                            <h6 class="mb-2">Logbook Terkunci</h6>
                            <p class="mb-0">Fitur logbook hanya dapat diakses setelah proposal Anda disetujui oleh admin.</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="tab-pane fade" id="outputs-pane" role="tabpanel" aria-labelledby="outputs-tab" tabindex="0">
                    <?php if (($proposal['status'] ?? '') === 'approved'): ?>
                        <div class="alert alert-light border-0 bg-light rounded-4 p-4 mb-4">
                            <h6 class="fw-bold mb-3">Silahkan unggah berkas Luaran di sini.</h6>
                            <ol class="small text-muted mb-0 ps-3">
                                <li class="mb-1">File yang diperbolehkan hanya dalam format <span class="fw-bold">.pdf</span> dengan ukuran maksimal <span class="fw-bold">10MB</span>.</li>
                                <li class="mb-1">Luaran yang muncul sesuai dengan ceklist Anda saat mengajukan proposal ini.</li>
                                <li>Luaran dipresentasikan dalam seminar luaran sebagai bagian dari tahapan penilaian tahap akhir bantuan.</li>
                            </ol>
                        </div>

                        <div class="table-responsive dosen-table-wrap">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th class="py-3" style="width: 300px;">Nama Luaran</th>
                                        <th class="py-3">Berkas Tersimpan</th>
                                        <th class="py-3">Unggah di sini</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($proposal['outputs'] as $output): ?>
                                        <tr>
                                            <td class="fw-bold ps-4"><?= esc($output['kategori']) ?></td>
                                            <td class="text-center">
                                                <?php if ($output['file_url']): ?>
                                                    <a href="<?= $output['file_url'] ?>" target="_blank" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm">
                                                        <i class="bi bi-file-earmark-text me-1"></i>Lihat berkas
                                                    </a>
                                                    <div class="small text-muted mt-1" style="font-size: 0.65rem;">
                                                        Diunggah: <?= esc($output['uploaded_at']) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-2">Belum ada berkas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="<?= site_url('dosen/proposals/outputs/upload/' . $proposal['uuid']) ?>" method="post" enctype="multipart/form-data" class="d-flex gap-2 px-3">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="kategori" value="<?= esc($output['kategori']) ?>">
                                                    <input type="file" name="berkas" class="form-control form-control-sm shadow-none" accept=".pdf" required>
                                                    <button type="submit" class="btn btn-success btn-sm px-2 shadow-sm">
                                                        <i class="bi bi-upload"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-pane">
                            <i class="bi bi-lock fs-1 mb-3 d-block text-muted"></i>
                            <h6 class="mb-2">Luaran Terkunci</h6>
                            <p class="mb-0">Fitur luaran hanya dapat diakses setelah proposal Anda disetujui oleh admin.</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="tab-pane fade" id="finance-pane" role="tabpanel" aria-labelledby="finance-tab" tabindex="0">
                    <?php if (($proposal['status'] ?? '') === 'approved'): ?>
                        <div class="alert alert-light border-0 bg-light rounded-4 p-4 mb-4">
                            <h6 class="fw-bold mb-3">Silahkan unggah berkas Laporan Kegiatan Penelitian di sini.</h6>
                            <ol class="small text-muted mb-0 ps-3">
                                <li class="mb-1">File yang diperbolehkan hanya dalam format <span class="fw-bold">.pdf</span> dengan ukuran maksimal <span class="fw-bold">10MB</span>.</li>
                                <li class="mb-1">Laporan Antara/Progres dapat berisi laporan perkembangan kegiatan bantuan ataupun revisi proposal berdasarkan masukan reviewer.</li>
                                <li class="mb-1">Laporan keuangan disusun dengan mengacu kepada SBM & SBK Kemenkeu yang berlaku pada tahun pelaksanaan.</li>
                                <li class="mb-1">Laporan keuangan memuat Cash Flow dan bukti transaksi terscan, Laporan keuangan sementara dilaporkan dalam seminar luaran.</li>
                                <li>Laporan akhir merupakan laporan final berdasarkan hasil review seminar luaran, berisi <span class="fw-bold">Laporan Keuangan</span> dan <span class="fw-bold">Laporan Akademik</span> final.</li>
                            </ol>
                        </div>

                        <!-- 1. Laporan Antara / Progress -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-arrow-right-circle me-2 text-primary"></i>Laporan Antara / Progress</h6>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3" style="width: 300px;">Nama Laporan</th>
                                            <th class="py-3">Berkas Tersimpan</th>
                                            <th class="py-3">Unggah di sini</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $report = $proposal['reports']['Laporan Antara']; ?>
                                        <tr>
                                            <td class="fw-bold ps-4">Laporan Antara</td>
                                            <td class="text-center">
                                                <?php if ($report['file_url']): ?>
                                                    <a href="<?= $report['file_url'] ?>" target="_blank" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm">
                                                        <i class="bi bi-file-earmark-text me-1"></i>Lihat berkas
                                                    </a>
                                                    <div class="small text-muted mt-1" style="font-size: 0.65rem;">
                                                        Diunggah: <?= esc($report['uploaded_at']) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-2">Belum ada berkas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="<?= site_url('dosen/proposals/reports/upload/' . $proposal['uuid']) ?>" method="post" enctype="multipart/form-data" class="d-flex gap-2 px-3">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="kategori" value="Laporan Antara">
                                                    <input type="file" name="berkas" class="form-control form-control-sm shadow-none" accept=".pdf" required>
                                                    <button type="submit" class="btn btn-success btn-sm px-2 shadow-sm">
                                                        <i class="bi bi-upload"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 2. Laporan Keuangan Sementara -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-cash-stack me-2 text-primary"></i>Laporan Keuangan Sementara</h6>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3">Usulan Biaya</th>
                                            <th class="py-3">Biaya Disetujui</th>
                                            <th class="py-3">Berkas Tersimpan</th>
                                            <th class="py-3">Unggah di sini</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $report = $proposal['reports']['Laporan Keuangan Sementara']; ?>
                                        <tr>
                                            <td class="text-center fw-bold">Rp. <?= $proposal['finance']['proposed_formatted'] ?></td>
                                            <td class="text-center fw-bold text-success">
                                                Rp. <?= $proposal['finance']['approved_amount'] > 0 ? $proposal['finance']['approved_formatted'] : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($report['file_url']): ?>
                                                    <a href="<?= $report['file_url'] ?>" target="_blank" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm">
                                                        <i class="bi bi-file-earmark-text me-1"></i>Lihat berkas
                                                    </a>
                                                    <div class="small text-muted mt-1" style="font-size: 0.65rem;">
                                                        Diunggah: <?= esc($report['uploaded_at']) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-2">Belum ada berkas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="<?= site_url('dosen/proposals/reports/upload/' . $proposal['uuid']) ?>" method="post" enctype="multipart/form-data" class="d-flex gap-2 px-3">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="kategori" value="Laporan Keuangan Sementara">
                                                    <input type="file" name="berkas" class="form-control form-control-sm shadow-none" accept=".pdf" required>
                                                    <button type="submit" class="btn btn-success btn-sm px-2 shadow-sm">
                                                        <i class="bi bi-upload"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 3. Laporan Akhir -->
                        <div class="mb-0">
                            <h6 class="fw-bold mb-3"><i class="bi bi-flag-fill me-2 text-primary"></i>Laporan Akhir</h6>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3" style="width: 300px;">Nama Laporan</th>
                                            <th class="py-3">Berkas Tersimpan</th>
                                            <th class="py-3">Unggah di sini</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $akhirCategories = ['Laporan Akademik', 'Laporan Keuangan'];
                                        foreach ($akhirCategories as $kat): 
                                            $report = $proposal['reports'][$kat];
                                        ?>
                                        <tr>
                                            <td class="fw-bold ps-4"><?= $kat ?></td>
                                            <td class="text-center">
                                                <?php if ($report['file_url']): ?>
                                                    <a href="<?= $report['file_url'] ?>" target="_blank" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm">
                                                        <i class="bi bi-file-earmark-text me-1"></i>Lihat berkas
                                                    </a>
                                                    <div class="small text-muted mt-1" style="font-size: 0.65rem;">
                                                        Diunggah: <?= esc($report['uploaded_at']) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-2">Belum ada berkas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form action="<?= site_url('dosen/proposals/reports/upload/' . $proposal['uuid']) ?>" method="post" enctype="multipart/form-data" class="d-flex gap-2 px-3">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="kategori" value="<?= $kat ?>">
                                                    <input type="file" name="berkas" class="form-control form-control-sm shadow-none" accept=".pdf" required>
                                                    <button type="submit" class="btn btn-success btn-sm px-2 shadow-sm">
                                                        <i class="bi bi-upload"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="empty-pane">
                            <i class="bi bi-lock fs-1 mb-3 d-block text-muted"></i>
                            <h6 class="mb-2">Laporan Terkunci</h6>
                            <p class="mb-0">Fitur laporan hanya dapat diakses setelah proposal Anda disetujui oleh admin.</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="tab-pane fade" id="outcomes-pane" role="tabpanel" aria-labelledby="outcomes-tab" tabindex="0">
                    <?php if (($proposal['status'] ?? '') === 'approved'): ?>
                        <!-- Jurnal Section -->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">Publikasi sebagai Artikel Jurnal</h6>
                                <button type="button" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-add-jurnal">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah
                                </button>
                            </div>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3">Judul Artikel</th>
                                            <th class="py-3">Nama Jurnal</th>
                                            <th class="py-3" style="width: 80px;">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($proposal['outcomes']['journals'])): ?>
                                            <tr>
                                                <td colspan="3" class="text-center py-4 text-muted small">Maaf, belum ada data publikasi dalam bentuk artikel jurnal.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($proposal['outcomes']['journals'] as $jurnal): ?>
                                                <tr>
                                                    <td class="ps-3 small"><?= esc($jurnal['judul']) ?></td>
                                                    <td class="text-center">
                                                        <a href="https://<?= esc($jurnal['url']) ?>" target="_blank" class="text-decoration-none">
                                                            <?= esc($jurnal['nama_penerbit_jurnal']) ?>
                                                            <div class="small text-muted"><?= esc($jurnal['volume_nomor']) ?></div>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm px-2" onclick="SwalDelete('<?= site_url('dosen/proposals/outcomes/delete/' . $jurnal['uuid']) ?>', 'Outcome ini')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Buku Section -->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">Publikasi sebagai Buku</h6>
                                <button type="button" class="btn btn-info btn-sm text-white px-3 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-add-buku">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah
                                </button>
                            </div>
                            <div class="table-responsive dosen-table-wrap">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th class="py-3">Judul Buku</th>
                                            <th class="py-3">Penerbit</th>
                                            <th class="py-3" style="width: 80px;">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($proposal['outcomes']['books'])): ?>
                                            <tr>
                                                <td colspan="3" class="text-center py-4 text-muted small">Maaf, belum ada data publikasi dalam bentuk buku.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($proposal['outcomes']['books'] as $buku): ?>
                                                <tr>
                                                    <td class="ps-3 small"><?= esc($buku['judul']) ?></td>
                                                    <td class="text-center small">
                                                        <?= esc($buku['nama_penerbit_jurnal']) ?>
                                                        <div class="text-muted">ISBN: <?= esc($buku['isbn']) ?> (<?= esc($buku['tahun_terbit']) ?>)</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm px-2" onclick="SwalDelete('<?= site_url('dosen/proposals/outcomes/delete/' . $buku['uuid']) ?>', 'Outcome ini')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="mt-4 p-4 bg-light rounded-4 border-0">
                            <h6 class="fw-bold mb-2">Catatan validator outcome :</h6>
                            <p class="mb-0 text-secondary"><?= $proposal['admin_decision']['outcome_notes'] ?: 'Belum ada catatan dari validator.' ?></p>
                        </div>
                    <?php else: ?>
                        <div class="empty-pane">
                            <i class="bi bi-lock fs-1 mb-3 d-block text-muted"></i>
                            <h6 class="mb-2">Outcomes Terkunci</h6>
                            <p class="mb-0">Fitur hasil penelitian hanya dapat diakses setelah proposal Anda disetujui oleh admin.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php if (($proposal['status'] ?? '') === 'approved'): ?>
<!-- Modal Tambah Logbook -->
<div class="modal fade" id="addLogbookModal" tabindex="-1" aria-labelledby="addLogbookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold" id="addLogbookModalLabel">
                    <i class="bi bi-journal-plus me-2"></i>Tambah Logbook Penelitian
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('dosen/proposals/logbook/store/' . $proposal['uuid']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Kegiatan</label>
                            <input type="text" name="tanggal" 
                                class="form-control shadow-none datepicker" 
                                value="<?= date('Y-m-d') ?>" 
                                data-locale="id"
                                data-date-format="Y-m-d"
                                data-alt-format="d F Y"
                                placeholder="Pilih tanggal kegiatan"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tempat / Lokasi</label>
                            <input type="text" name="tempat" class="form-control shadow-none" placeholder="Nama lokasi kegiatan" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" class="form-control shadow-none" placeholder="Nama kegiatan" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Teknik Penelitian</label>
                            <select name="teknik" class="form-select shadow-none" required>
                                <option value="" disabled selected>Pilih Teknik...</option>
                                <option value="Analisis Dokumen">Analisis Dokumen</option>
                                <option value="Diskusi">Diskusi</option>
                                <option value="FGD">FGD</option>
                                <option value="Observasi">Observasi</option>
                                <option value="Penyebaran Angket">Penyebaran Angket</option>
                                <option value="Wawancara">Wawancara</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Deskripsi Kegiatan</label>
                            <textarea name="deskripsi_kegiatan" class="form-control shadow-none" rows="4" placeholder="Jelaskan detail kegiatan..." required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Berkas / File Pendukung (Opsional)</label>
                            <input type="file" name="berkas" class="form-control shadow-none">
                            <div class="form-text small text-muted">Format: PDF, JPG, PNG, ZIP, DOCX (Maks. 2MB)</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Logbook</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Jurnal -->
<div class="modal fade" id="modal-add-jurnal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-bold text-dark mb-0">Tambah Artikel Jurnal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('dosen/proposals/outcomes/store/' . $proposal['uuid']) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="tipe" value="jurnal">
                <div class="modal-body p-4">
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Judul Artikel</label>
                        <div class="col-sm-8">
                            <input type="text" name="judul" class="form-control" placeholder="Judul artikel" required>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Nama Jurnal</label>
                        <div class="col-sm-8">
                            <input type="text" name="nama_jurnal" class="form-control" placeholder="Nama Jurnal" required>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Volume - Nomor</label>
                        <div class="col-sm-8">
                            <input type="text" name="volume_nomor" class="form-control" placeholder="volume dan nomor terbitan" required>
                        </div>
                    </div>
                    <div class="mb-0 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">URL artikel</label>
                        <div class="col-sm-8">
                            <input type="text" name="url" class="form-control" placeholder="URL artikel" required>
                            <div class="form-text small text-danger" style="font-size: 0.7rem;">hapus https:// atau http:// agar tidak diblokir.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-success px-4 rounded-3 fw-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Buku -->
<div class="modal fade" id="modal-add-buku" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="fw-bold text-dark mb-0">Tambah Publikasi Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('dosen/proposals/outcomes/store/' . $proposal['uuid']) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="tipe" value="buku">
                <div class="modal-body p-4">
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Judul Buku</label>
                        <div class="col-sm-8">
                            <input type="text" name="judul" class="form-control" placeholder="Judul Buku" required>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">ISBN</label>
                        <div class="col-sm-8">
                            <input type="text" name="isbn" class="form-control" placeholder="ISBN Buku" required>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Penerbit</label>
                        <div class="col-sm-8">
                            <input type="text" name="penerbit" class="form-control" placeholder="Nama Penerbit" required>
                        </div>
                    </div>
                    <div class="mb-0 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-secondary">Tahun Terbit</label>
                        <div class="col-sm-8">
                            <input type="number" name="tahun_terbit" class="form-control" placeholder="Tahun Terbit" value="<?= date('Y') ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-success px-4 rounded-3 fw-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $this->endSection(); ?>
