<?php
/**
 * components/ui-card.php
 * Universal Card component
 * 
 * @var string $title
 * @var string $icon
 * @var string $body
 * @var string $footer
 * @var string $headerAction HTML for right-side header elements
 */
?>

<div class="card shadow-sm">
    <?php if (isset($title)): ?>
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <?php if (isset($icon)): ?>
                    <i class="<?= esc($icon) ?> me-2"></i>
                <?php endif; ?>
                <?= esc($title) ?>
            </h3>
            <?php if (isset($headerAction)): ?>
                <div><?= $headerAction ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="card-body">
        <?= $body ?? '' ?>
    </div>
    
    <?php if (isset($footer)): ?>
        <div class="card-footer">
            <?= $footer ?>
        </div>
    <?php endif; ?>
</div>
