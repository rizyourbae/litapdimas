<?php
/** @var array<string,mixed> $proposal */
/** @var string $proposalUuid */

$this->extend('layouts/main');

$this->section('content');
?>

<?= view('components/ui-hero', [
    'type' => 'dosen',
    'title' => 'Final Review Proposal',
    'subtitle' => 'Periksa kembali seluruh data sebelum melakukan submit akhir. Data tidak dapat diubah setelah dikirim.',
    'badges' => [
        ['label' => 'Final Check', 'class' => 'text-bg-warning px-3']
    ]
]) ?>


<?php
$overviewCards = $proposal['review_overview_cards'] ?? [];
$step1Items = $proposal['review_step1_items'] ?? [];
$step2Sections = $proposal['review_step2_sections'] ?? [];
$step3Summary = $proposal['review_step3_summary'] ?? [];
$step5Summary = $proposal['review_step5_summary'] ?? [];
$documents = $proposal['documents'] ?? [];
?>

<div class="proposal-review-page">
            <div class="row g-3 mb-4">
                <?php foreach ($overviewCards as $card): ?>
                    <div class="col-6 col-md-3">
                        <?= view('components/ui-stat-card', [
                            'label' => $card['label'] ?? '-',
                            'value' => $card['value'] ?? '-',
                            'icon' => $card['icon'] ?? 'bi bi-info-circle',
                            'colorClass' => 'text-dark'
                        ]) ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light py-3 px-4">
                    <h5 class="h6 fw-bold mb-0 text-uppercase letter-spacing-1">Step 1: Pernyataan Peneliti</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                <?php foreach ($step1Items as $item): ?>
                                    <tr>
                                        <td class="fw-bold text-muted px-4" style="width: 30%;"><?= esc($item['label'] ?? '-') ?></td>
                                        <td class="px-4 text-dark"><?= esc($item['value'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
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
                                <thead class="bg-light">
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
                        <thead class="bg-light">
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
                                                <span class="review-link-host"><?= esc((string) ($document['nama_file'] ?? '-')) ?></span>
                                                <a href="<?= esc((string) $document['view_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary review-link-btn"><i class="bi bi-eye me-1"></i>Lihat Berkas</a>
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
                        <div class="review-value fw-bold text-success"><?= !empty($step5Summary['total_pengajuan_dana']) ? 'Rp ' . number_format((float) $step5Summary['total_pengajuan_dana'], 0, ',', '.') : '-' ?></div>
                    </div>
                    <?php foreach ($step5Summary['links'] ?? [] as $link): ?>
                        <div class="review-item-row">
                            <div class="review-label"><?= esc($link['label'] ?? '-') ?></div>
                            <div class="review-value">
                                <?php if (!empty($link['url'])): ?>
                                    <div class="review-link-chip">
                                        <span class="review-link-host"><?= esc(parse_url((string) $link['url'], PHP_URL_HOST) ?: $link['url']) ?></span>
                                        <a href="<?= esc((string) $link['url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary review-link-btn"><i class="bi bi-box-arrow-up-right me-1"></i>Buka</a>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="alert alert-warning border-0 shadow-sm mb-4">
                <div class="d-flex gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>
                        <div class="fw-bold">Peringatan</div>
                        Setelah Anda submit proposal, data tidak dapat diubah kembali. Pastikan seluruh informasi sudah benar.
                    </div>
                </div>
            </div>

            <div class="action-bar d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top">
                <a href="<?= site_url('dosen/proposals/step/5/' . esc($proposalUuid)) ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-chevron-left me-1"></i> Kembali Edit
                </a>

                <form method="POST" action="<?= site_url('dosen/proposals/submit/' . esc($proposalUuid)) ?>" class="m-0" id="proposalReviewForm" data-confirm-title="Submit proposal ini?" data-confirm-message="Setelah submit, proposal akan terkirim untuk review dan tidak bisa diubah lagi.">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-send me-1"></i> Submit Proposal
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