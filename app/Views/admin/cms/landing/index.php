<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-4 admin-page">
    <!-- HERO SECTION -->
    <div class="col-12 mb-2 animate-fade-up">
        <?= view('components/ui-hero', [
            'type' => 'admin',
            'title' => 'Manajemen Landing Page',
            'subtitle' => 'Kelola konten visual dan informasi yang tampil di halaman depan publik',
            'badges' => [
                ['label' => 'CMS', 'class' => 'text-bg-light border'],
                ['label' => 'Landing Page', 'class' => 'text-bg-primary shadow-sm']
            ]
        ]) ?>
    </div>

    <!-- TABS NAVIGATION -->
    <div class="col-12 animate-fade-up delay-1">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 p-0">
                <ul class="nav nav-pills nav-justified bg-light p-2" id="cmsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill fw-bold py-3" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                            <i class="bi bi-sliders2 me-2"></i>Pengaturan Umum
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold py-3" id="themes-tab" data-bs-toggle="tab" data-bs-target="#themes" type="button" role="tab">
                            <i class="bi bi-grid-3x3-gap me-2"></i>Tema Riset Prioritas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold py-3" id="banners-tab" data-bs-toggle="tab" data-bs-target="#banners" type="button" role="tab">
                            <i class="bi bi-images me-2"></i>Banner Slider
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="cmsTabsContent">
                    <!-- Tab 1: Settings -->
                    <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                        <?= view('admin/cms/landing/_tab_settings', [
                            'hero' => $heroSettings,
                            'contact' => $contactSettings,
                            'stats' => $statsSettings
                        ]) ?>
                    </div>

                    <!-- Tab 2: Themes -->
                    <div class="tab-pane fade" id="themes" role="tabpanel" aria-labelledby="themes-tab">
                        <?= view('admin/cms/landing/_tab_themes', ['themes' => $themes]) ?>
                    </div>

                    <!-- Tab 3: Banners -->
                    <div class="tab-pane fade" id="banners" role="tabpanel" aria-labelledby="banners-tab">
                        <?= view('admin/cms/landing/_tab_banners', ['banners' => $banners]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PARTIALS: MODALS -->
<?= view('admin/cms/landing/partials/_modals_theme') ?>
<?= view('admin/cms/landing/partials/_modals_banner') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('custom/js/admin-cms-landing.js') ?>"></script>
<?= $this->endSection() ?>
