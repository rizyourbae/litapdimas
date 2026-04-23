<?php if (!empty($breadcrumbs)): ?>
    <ol class="breadcrumb float-sm-end">
        <?php foreach ($breadcrumbs as $crumb): ?>
            <?php if (isset($crumb['url'])): ?>
                <li class="breadcrumb-item"><a href="<?= $crumb['url'] ?>"><?= esc($crumb['title']) ?></a></li>
            <?php else: ?>
                <li class="breadcrumb-item active" aria-current="page"><?= esc($crumb['title']) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>