<?php
/**
 * components/ui-table-card.php
 * Universal Table Card wrapper
 * 
 * @var string $title
 * @var string $tableId
 * @var string $header
 * @var string $body
 * @var string $footer
 * @var string $badge
 * @var string $icon
 * @var bool   $useSkeleton  Default true
 * @var string $type         'dosen' | 'admin' (affects data- attribute)
 */

$tableId = $tableId ?? ('dt-' . uniqid());
$dtAttr = ($type ?? 'dosen') === 'admin' ? 'data-admin-datatable' : 'data-dosen-datatable';
$skeletonId = "sk-" . $tableId;
$realWrapId = "rw-" . $tableId;
?>

<div class="card shadow-sm border-0 overflow-hidden">
    <?php if (isset($title)): ?>
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="h5 fw-bold mb-0">
                    <?php if (isset($icon)): ?>
                        <i class="<?= esc($icon) ?> me-2 opacity-50"></i>
                    <?php endif; ?>
                    <?= esc($title) ?>
                </h3>
                <?php if (isset($badge)): ?>
                    <span class="badge text-bg-light border px-3 py-2"><?= esc($badge) ?></span>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card-body p-4">
        <div class="dt-skeleton-wrap">
            <div class="dt-skeleton-overlay" id="<?= esc($skeletonId) ?>">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px"><span class="skeleton-line" style="width:20px"></span></th>
                                <th><span class="skeleton-line" style="width:60%"></span></th>
                                <th><span class="skeleton-line" style="width:40%"></span></th>
                                <th style="width:100px"><span class="skeleton-line" style="width:60px"></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ([80, 60, 70, 50] as $w): ?>
                                <tr>
                                    <td><span class="skeleton-line" style="width:20px"></span></td>
                                    <td><span class="skeleton-line" style="width:<?= $w ?>%"></span></td>
                                    <td><span class="skeleton-line" style="width:40%"></span></td>
                                    <td><span class="skeleton-btn"></span> <span class="skeleton-btn"></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dt-real-wrap" id="<?= esc($realWrapId) ?>">
                <?php if (isset($content)): ?>
                    <?= $content ?>
                <?php else: ?>
                    <div class="table-responsive">
                        <table id="<?= esc($tableId) ?>" class="table table-hover table-bordered align-middle mb-0 w-100" 
                            <?= $dtAttr ?> 
                            data-skeleton-id="<?= esc($skeletonId) ?>" 
                            data-real-wrap-id="<?= esc($realWrapId) ?>">
                            <?= $header ?? '' ?>
                            <tbody>
                                <?= $body ?? '' ?>
                            </tbody>
                            <?= $footer ?? '' ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

