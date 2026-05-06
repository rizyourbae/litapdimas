<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation">
    <ul class="pagination pagination-rounded justify-content-center">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link shadow-sm border-0 mx-1" href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
                    <span aria-hidden="true"><i class="bi bi-chevron-double-left"></i></span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link shadow-sm border-0 mx-1" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>">
                    <span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link shadow-sm border-0 mx-1 <?= $link['active'] ? 'bg-primary text-white' : 'text-dark' ?>" href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link shadow-sm border-0 mx-1" href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>">
                    <span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link shadow-sm border-0 mx-1" href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>">
                    <span aria-hidden="true"><i class="bi bi-chevron-double-right"></i></span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>

<style>
    .pagination-rounded .page-link {
        border-radius: 12px !important;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        transition: all 0.3s;
    }
    .pagination-rounded .page-item.active .page-link {
        background: var(--primary-emerald) !important;
    }
</style>
