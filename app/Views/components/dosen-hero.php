<?php
/**
 * components/dosen-hero.php
 * Wrapper for ui-hero for Dosen module compatibility
 */

echo view('components/ui-hero', [
    'type' => 'dosen',
    'title' => $title ?? '',
    'subtitle' => $subtitle ?? '',
    'icon' => $icon ?? null,
    'badges' => $badges ?? [
        ['label' => 'Panel Dosen', 'class' => 'text-bg-light border'],
        ['label' => 'Litapdimas', 'class' => 'text-bg-primary']
    ],
    'actions' => $actions ?? null
]);