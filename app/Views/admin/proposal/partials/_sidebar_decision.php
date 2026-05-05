<?php
/**
 * @var array $decisionSummary
 * @var array $hero
 */
?>
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-2">
            <i class="bi bi-check2-square text-primary fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark text-uppercase small ls-1">Keputusan Akhir</h6>
        </div>

        <!-- Metrics Cards -->
        <div class="row g-2 mb-4">
            <?php foreach ($decisionSummary['cards'] ?? [] as $card): ?>
                <div class="col-6">
                    <div class="p-2 bg-light border rounded-3 text-center">
                        <div class="small text-muted fw-bold mb-1" style="font-size: 0.6rem;"><?= esc($card['label']) ?></div>
                        <div class="fw-bold <?= esc($card['tone_class']) ?>" style="font-size: 0.9rem;"><?= esc($card['value']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Decision Summary Note -->
        <div class="bg-light p-3 rounded-3 border-start border-4 border-primary mb-4">
            <div class="small fw-bold text-primary text-uppercase mb-1" style="font-size: 0.65rem;">Ringkasan & Panduan</div>
            <div class="small text-secondary lh-sm"><?= esc($decisionSummary['note'] ?? '') ?></div>
        </div>

        <!-- Decision Action -->
        <?php if (!empty($decisionSummary['can_decide'])): ?>
            <button type="button" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-final-decision">
                <i class="bi bi-check-circle-fill me-2"></i>Berikan Keputusan Akhir
            </button>
        <?php elseif (!empty($decisionSummary['is_decided'])): ?>
            <div class="p-3 border rounded-3 bg-light">
                <div class="small fw-bold text-muted text-uppercase mb-2" style="font-size: 0.6rem;">Status Akhir Terpilih</div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill <?= $hero['status_class'] ?? 'text-primary' ?>"></i>
                    <span class="fw-bold <?= $hero['status_class'] ?? 'text-primary' ?>"><?= esc($hero['status_label'] ?? '') ?></span>
                </div>
            </div>
        <?php else: ?>
            <button type="button" class="btn btn-outline-secondary w-100 fw-bold rounded-pill" disabled title="Tunggu semua reviewer selesai memberikan penilaian">
                <i class="bi bi-hourglass-split me-2"></i>Menunggu Penilaian
            </button>
        <?php endif; ?>
    </div>
</div>
