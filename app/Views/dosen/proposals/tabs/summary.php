<?php
$summary = $proposal['summary'] ?? [];
$overviewCards = $proposal['overview_cards'] ?? [];
$teamSections = $proposal['team_sections'] ?? [];
$reviewSummary = $proposal['review_summary'] ?? [];
$documentRows = $proposal['document_rows'] ?? [];
?>
                    <?php if ($proposal['admin_decision']['is_decided'] ?? false): ?>
                        <div class="alert <?= ($proposal['status'] ?? '') === 'approved' ? 'alert-success' : 'alert-danger' ?> border-0 shadow-sm rounded-4 p-4 mb-4">
                            <div class="d-flex gap-3">
                                <div class="fs-1">
                                    <i class="bi <?= ($proposal['status'] ?? '') === 'approved' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' ?>"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Keputusan Akhir Admin: <?= esc((string) ($proposal['status_label'] ?? '')) ?></h5>
                                    <div class="small opacity-75 mb-3">Diterima pada <?= format_indo($proposal['admin_decision']['decided_at'] ?? '', true) ?></div>
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
