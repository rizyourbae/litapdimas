<?php

/**
 * components/dosen-hero.php
 * Reusable hero component untuk dosen views
 * 
 * Expected variables:
 * - $title (string): Hero section title
 * - $subtitle (string): Hero section subtitle
 * - $icon (string, optional): Font Awesome icon class
 */
?>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card dosen-hero">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge text-bg-light border px-3 py-2">
                                <i class="bi bi-file-earmark"></i> Panel Dosen
                            </span>
                            <span class="badge text-bg-primary px-3 py-2">
                                <i class="bi bi-check-circle"></i> Litapdimas
                            </span>
                        </div>
                        <h2 class="h3 dosen-hero__title mb-2">
                            <?php if (isset($icon)): ?>
                                <i class="<?= esc($icon) ?> me-2"></i>
                            <?php endif; ?>
                            <?= esc($title ?? '') ?>
                        </h2>
                        <p class="dosen-hero__subtitle mb-0">
                            <?= esc($subtitle ?? '') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>