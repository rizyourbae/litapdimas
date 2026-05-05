<?php
$review = $proposal['review'] ?? [];
$sections = $review['substansi_bagian'] ?? [];
?>
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
                        <div class="small text-muted"><i class="bi bi-calendar-check me-1"></i><?= format_indo($result['reviewed_at'], true) ?></div>
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
