<?php
/**
 * @var array $hero
 * @var string $title
 */
?>
<div class="col-12 mb-2">
    <?= view('components/ui-hero', [
        'type' => 'admin',
        'title' => esc((string) ($hero['title'] ?? $title)),
        'subtitle' => esc((string) ($hero['subtitle'] ?? '')),
        'badges' => [
            ['label' => 'Proposal Detail', 'class' => 'text-bg-light border shadow-sm'],
            ['label' => esc((string) ($hero['status_label'] ?? '')), 'class' => esc((string) ($hero['status_class'] ?? 'text-bg-primary')) . ' shadow-sm']
        ],
        'actions' => [
            [
                'label' => 'Kembali',
                'class' => 'btn btn-outline-secondary',
                'icon' => 'bi bi-arrow-left',
                'url' => site_url('admin/proposals')
            ]
        ]
    ]) ?>
</div>
