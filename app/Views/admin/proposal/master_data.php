<?= $this->extend('layouts/main') ?>

<?php
/**
 * Master Data Proposal View
 * 
 * @var string $title
 * @var array $bidang_ilmu
 * @var array $klaster_bantuan
 * @var array $tema_penelitian
 */
?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <!-- HERO SECTION -->
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Kelola data pendukung proposal meliputi Bidang Ilmu, Klaster Bantuan, dan Tema Penelitian.',
            'badges' => [
                ['label' => 'Master Data', 'class' => 'text-bg-light border shadow-sm'],
                ['label' => 'Proposal', 'class' => 'text-bg-primary shadow-sm']
            ]
        ]) ?>
    </div>

    <!-- MAIN TABS SECTION -->
    <div class="col-12 animate-fade-up delay-1">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <!-- TAB NAVIGATION -->
            <div class="card-header bg-white p-0 border-bottom">
                <ul class="nav admin-nav-tabs px-3" id="masterDataTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active py-3" id="tab-bidang-link" data-bs-toggle="tab" href="#tab-bidang" role="tab">
                            <i class="bi bi-book-fill me-2"></i>Bidang Ilmu
                            <span class="badge bg-primary-soft text-primary rounded-pill ms-2"><?= count($bidang_ilmu) ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" id="tab-klaster-link" data-bs-toggle="tab" href="#tab-klaster" role="tab">
                            <i class="bi bi-layers-fill me-2"></i>Klaster Bantuan
                            <span class="badge bg-primary-soft text-primary rounded-pill ms-2"><?= count($klaster_bantuan) ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" id="tab-tema-link" data-bs-toggle="tab" href="#tab-tema" role="tab">
                            <i class="bi bi-lightbulb-fill me-2"></i>Tema Penelitian
                            <span class="badge bg-primary-soft text-primary rounded-pill ms-2"><?= count($tema_penelitian) ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- TAB CONTENT -->
            <div class="card-body p-4">
                <div class="tab-content" id="masterDataTabsContent">
                    <!-- Tab Bidang Ilmu -->
                    <?= view('admin/proposal/partials/_tab_bidang_ilmu', ['bidang_ilmu' => $bidang_ilmu]) ?>

                    <!-- Tab Klaster Bantuan -->
                    <?= view('admin/proposal/partials/_tab_klaster_bantuan', ['klaster_bantuan' => $klaster_bantuan]) ?>

                    <!-- Tab Tema Penelitian -->
                    <?= view('admin/proposal/partials/_tab_tema_penelitian', ['tema_penelitian' => $tema_penelitian]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS SECTION -->
<?= view('admin/proposal/partials/_modals_bidang_ilmu') ?>
<?= view('admin/proposal/partials/_modals_klaster_bantuan') ?>
<?= view('admin/proposal/partials/_modals_tema_penelitian') ?>

<?= $this->endSection() ?>