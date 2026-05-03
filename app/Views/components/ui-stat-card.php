<?php
/**
 * components/ui-stat-card.php
 * Component for metric/stat display
 * 
 * @var string $label        Metric label (e.g. "Total Users")
 * @var string $value        Metric value (e.g. "1,234")
 * @var string $desc         Short description
 * @var string $icon         Bootstrap icon class
 * @var string $colorClass   'text-success', 'text-primary', etc.
 */
?>

<div class="card admin-metric-card h-100">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="small text-uppercase text-muted fw-bold"><?= esc($label) ?></div>
            <?php if (isset($icon)): ?>
                <i class="<?= esc($icon) ?> text-muted opacity-50"></i>
            <?php endif; ?>
        </div>
        <div class="admin-metric-card__value <?= esc($colorClass ?? '') ?>"><?= esc($value) ?></div>
        <?php if (isset($desc)): ?>
            <div class="text-muted small"><?= esc($desc) ?></div>
        <?php endif; ?>
    </div>
</div>
