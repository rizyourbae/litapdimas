<?php
/**
 * @var array $summaryItems
 * @var array $abstract
 * @var array $substansiSections
 * @var array $journalInfo
 * @var array $teamSections
 * @var array $documentRows
 */
?>
<div class="tab-pane fade show active" id="proposalShowDetailPane" role="tabpanel">
    <!-- Summary Grid -->
    <div class="row g-4 mb-5">
        <?php foreach ($summaryItems as $item): ?>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded-3 border h-100">
                    <div class="small text-muted fw-bold text-uppercase ls-1 mb-1" style="font-size: 0.7rem;"><?= esc((string) ($item['label'] ?? '')) ?></div>
                    <div class="fw-bold text-dark"><?= esc((string) ($item['value'] ?? '')) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Abstract -->
    <div class="mb-5">
        <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Abstrak & Substansi Utama</h6>
        <div class="p-4 bg-white border rounded-4 shadow-sm">
            <h4 class="h5 fw-bold mb-3"><?= esc($abstract['title'] ?? 'Abstrak') ?></h4>
            <?php if (!empty($abstract['html'])): ?>
                <div class="admin-proposal-rich fs-6 lh-lg text-secondary"><?= $abstract['html'] ?></div>
            <?php else: ?>
                <div class="text-center py-5 opacity-50">
                    <i class="bi bi-card-text display-4 mb-2"></i>
                    <p class="mb-0 fw-semibold"><?= esc($abstract['empty_message'] ?? 'Data belum tersedia') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Substansi Sections -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h6 class="fw-bold text-primary text-uppercase small mb-0 ls-1">Struktur Detail Proposal</h6>
            <?php if (!empty($substansiSections)): ?>
                <span class="badge text-bg-light border px-3 rounded-pill"><?= count($substansiSections) ?> Bagian</span>
            <?php endif; ?>
        </div>

        <?php if (empty($substansiSections)): ?>
            <div class="p-4 bg-light text-center rounded-4 border">
                <i class="bi bi-layout-text-window fs-2 text-muted mb-2"></i>
                <p class="mb-0 text-muted small">Belum ada bagian substansi terperinci.</p>
            </div>
        <?php else: ?>
            <div class="accordion accordion-flush admin-proposal-accordion border rounded-4 overflow-hidden" id="substansiAccordion">
                <?php foreach ($substansiSections as $index => $section): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?> fw-bold py-3 px-4 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#sec-<?= $index ?>">
                                <span class="badge text-bg-primary me-3"><?= esc((string) $section['number']) ?></span>
                                <?= esc($section['title']) ?>
                            </button>
                        </h2>
                        <div id="sec-<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#substansiAccordion">
                            <div class="accordion-body p-4 fs-6 text-secondary lh-base">
                                <?= $section['content_html'] ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Journal & Output -->
    <div class="mb-5">
        <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Informasi Pendukung & Luaran</h6>
        <div class="row g-3">
            <?php foreach ($journalInfo['items'] as $item): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="p-3 bg-white border rounded-3">
                        <div class="small text-muted mb-1"><?= esc((string) ($item['label'] ?? '')) ?></div>
                        <div class="fw-bold text-dark small"><?= esc((string) ($item['value'] ?? '')) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (!empty($journalInfo['links'])): ?>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <?php foreach ($journalInfo['links'] as $link): ?>
                    <a href="<?= esc((string) ($link['url'] ?? '#')) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="bi bi-link-45deg me-1"></i><?= esc((string) ($link['label'] ?? '')) ?>: <?= esc((string) ($link['value'] ?? '')) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Team Composition -->
    <div class="mb-5">
        <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Komposisi Tim & Peneliti</h6>
        <?php foreach ($teamSections as $section): ?>
            <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="vr" style="width: 4px; border-radius: 2px; background-color: var(--bs-primary); opacity: 1;"></div>
                    <h6 class="fw-bold mb-0 text-dark"><?= esc($section['title']) ?></h6>
                </div>
                <div class="table-responsive border rounded-3 overflow-hidden shadow-sm">
                    <table class="table table-hover table-sm align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="bg-light border-bottom">
                            <tr>
                                <?php foreach ($section['headers'] as $header): ?>
                                    <th class="p-3 fw-bold text-muted text-uppercase small" style="letter-spacing: 0.05em;"><?= esc($header) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($section['rows'])): ?>
                                <tr>
                                    <td colspan="<?= esc((string) $section['colspan']) ?>" class="p-4 text-center text-muted italic"><?= esc($section['empty_message']) ?></td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($section['rows'] as $row): ?>
                                    <tr>
                                        <?php foreach ($row['cells'] as $cell): ?>
                                            <td class="p-3 text-dark"><?= esc((string) $cell) ?></td>
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

    <!-- Documents -->
    <div class="mb-0">
        <h6 class="fw-bold text-primary text-uppercase small mb-4 ls-1 border-bottom pb-2">Dokumen & Lampiran Resmi</h6>
        <div class="row g-3">
            <?php foreach ($documentRows as $row): ?>
                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-white d-flex align-items-center justify-content-between shadow-sm hover-shadow-sm transition-all">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light p-2 rounded text-primary">
                                <i class="bi bi-file-earmark-pdf fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark small mb-0"><?= esc($row['label']) ?></div>
                                <div class="text-muted" style="font-size: 0.7rem;"><?= esc($row['file_name']) ?> (<?= esc($row['file_size_label']) ?>)</div>
                            </div>
                        </div>
                        <?php if (!empty($row['view_url'])): ?>
                            <a href="<?= esc($row['view_url']) ?>" target="_blank" class="btn btn-light btn-sm rounded-circle border p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Buka Dokumen">
                                <i class="bi bi-eye-fill text-primary"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
