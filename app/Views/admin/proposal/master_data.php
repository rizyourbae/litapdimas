<?= $this->extend('layouts/main') ?>

<?php
/**
 * Master Data Proposal View
 * 
 * @var string $title
 * @var array $bidang_ilmu
 * @var array $klaster_bantuan
 * @var array $tema_penelitian
 * @var array $pengelola_bantuan
 * @var array $jenis_penelitian
 * @var array $kontribusi_prodi
 */
?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <!-- HERO SECTION -->
    <div class="col-12 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => esc((string) $title),
            'subtitle' => 'Kelola data pendukung proposal meliputi rumpun ilmu, skema bantuan, kontribusi program studi, hingga instrumen penelitian.',
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
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" id="tab-pengelola-link" data-bs-toggle="tab" href="#tab-pengelola" role="tab">
                            <i class="bi bi-person-gear me-2"></i>Pengelola Bantuan
                            <span class="badge bg-primary-soft text-primary rounded-pill ms-2"><?= count($pengelola_bantuan) ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" id="tab-jenis-link" data-bs-toggle="tab" href="#tab-jenis" role="tab">
                            <i class="bi bi-clipboard2-check me-2"></i>Jenis Penelitian
                            <span class="badge bg-primary-soft text-primary rounded-pill ms-2"><?= count($jenis_penelitian) ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-3" id="tab-kontribusi-link" data-bs-toggle="tab" href="#tab-kontribusi" role="tab">
                            <i class="bi bi-building-up me-2"></i>Kontribusi Prodi
                            <span class="badge bg-primary-soft text-primary rounded-pill ms-2"><?= count($kontribusi_prodi) ?></span>
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

                    <!-- Tab Pengelola Bantuan -->
                    <?= view('admin/proposal/partials/_tab_pengelola_bantuan', ['pengelola_bantuan' => $pengelola_bantuan]) ?>

                    <!-- Tab Jenis Penelitian -->
                    <?= view('admin/proposal/partials/_tab_jenis_penelitian', ['jenis_penelitian' => $jenis_penelitian]) ?>

                    <!-- Tab Kontribusi Prodi -->
                    <?= view('admin/proposal/partials/_tab_kontribusi_prodi', ['kontribusi_prodi' => $kontribusi_prodi]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS SECTION -->
<?= view('admin/proposal/partials/_modals_bidang_ilmu') ?>
<?= view('admin/proposal/partials/_modals_klaster_bantuan') ?>
<?= view('admin/proposal/partials/_modals_tema_penelitian') ?>
<?= view('admin/proposal/partials/_modals_pengelola_bantuan') ?>
<?= view('admin/proposal/partials/_modals_jenis_penelitian') ?>
<?= view('admin/proposal/partials/_modals_kontribusi_prodi') ?>

<?= $this->endSection() ?>