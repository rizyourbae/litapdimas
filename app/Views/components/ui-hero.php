<?php
/**
 * components/ui-hero.php
 * Universal Hero component for all modules (Admin, Dosen, Reviewer)
 * 
 * @var string $title        Main heading
 * @var string $subtitle     Secondary description
 * @var array  $badges       Array of ['label' => '', 'class' => '']
 * @var string $actions      HTML string for buttons/actions
 * @var string $type         'admin' | 'dosen' | 'reviewer'
 */

$heroClass = ($type ?? 'dosen') . '-hero';
?>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card <?= esc($heroClass) ?>">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-4 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <?php foreach (($badges ?? []) as $badge): ?>
                                <span class="badge <?= esc($badge['class'] ?? 'text-bg-light border') ?> px-3 py-2">
                                    <?= esc($badge['label'] ?? '') ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <h2 class="h3 <?= esc($heroClass) ?>__title mb-2">
                            <?= esc($title ?? '') ?>
                        </h2>
                        <p class="<?= esc($heroClass) ?>__subtitle mb-0">
                            <?= esc($subtitle ?? '') ?>
                        </p>
                    </div>
                    <?php if (isset($actions)): ?>
                        <div class="<?= esc($heroClass) ?>__actions d-flex flex-wrap gap-2">
                            <?php if (is_array($actions)): ?>
                                <?php foreach ($actions as $action): ?>
                                    <a href="<?= esc($action['url'] ?? '#') ?>" class="btn <?= esc($action['class'] ?? 'btn-primary') ?>">
                                        <?php if (!empty($action['icon'])): ?>
                                            <i class="<?= esc($action['icon']) ?> me-1"></i>
                                        <?php endif; ?>
                                        <?= esc($action['label'] ?? '') ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?= $actions ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
