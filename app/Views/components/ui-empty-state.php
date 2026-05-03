<?php
/**
 * components/ui-empty-state.php
 * Consistent empty state display
 * 
 * @var string $icon      Bootstrap icon class
 * @var string $title     Main empty message
 * @var string $desc      Detailed instruction
 * @var string $action    HTML for action button (optional)
 */
?>

<div class="admin-empty-state py-5">
    <i class="<?= esc($icon ?? 'bi bi-inbox') ?> mb-3 d-block" style="font-size: 3rem;"></i>
    <h4 class="h5 fw-bold text-dark mb-2"><?= esc($title ?? 'Data Tidak Ditemukan') ?></h4>
    <p class="text-muted small mx-auto" style="max-width: 400px;">
        <?= esc($desc ?? 'Belum ada data yang tersedia untuk ditampilkan di sini.') ?>
    </p>
    <?php if (isset($action)): ?>
        <div class="mt-4">
            <?= $action ?>
        </div>
    <?php endif; ?>
</div>
