<?php if (!empty($breadcrumbs)): ?>
    <nav aria-label="breadcrumb" class="admin-breadcrumb-nav float-sm-end">
        <ol class="breadcrumb mb-0 align-items-center flex-wrap gap-1">
            <?php foreach ($breadcrumbs as $index => $crumb): ?>
                <?php $isLast = $index === array_key_last($breadcrumbs); ?>
                <li class="breadcrumb-item<?= $isLast ? ' active fw-semibold text-body-emphasis' : '' ?>">
                    <?php if ($index === 0): ?>
                        <i class="bi bi-house-door me-1"></i>
                    <?php endif; ?>

                    <?php if (!empty($crumb['url']) && ! $isLast): ?>
                        <a href="<?= esc((string) $crumb['url']) ?>" class="text-decoration-none">
                            <?= esc((string) $crumb['title']) ?>
                        </a>
                    <?php else: ?>
                        <span><?= esc((string) $crumb['title']) ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>