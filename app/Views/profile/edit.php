<?= $this->extend('layouts/main') ?>

<?php
/**
 * Profile Edit View
 * 
 * @var string $title
 * @var array $user
 * @var array $profile
 * @var array $master
 * @var array $viewState
 */
?>

<?= $this->section('content') ?>

<div class="profile-edit-page animate-fade-up">
    <div class="card card-primary card-outline shadow-sm profile-main-card">
        <div class="card-header border-0 pb-0">
            <h3 class="card-title mb-0">
                <i class="bi bi-person-circle me-2"></i><?= esc($title) ?>
            </h3>
        </div>

        <form action="<?= site_url('profile/update') ?>" method="post" enctype="multipart/form-data"
            class="profile-edit-form" data-profile-form data-profile-active-tab="<?= esc($viewState['activeTab']) ?>">
            <?= csrf_field() ?>

            <div class="card-body pb-5">
                <!-- PROGRESS BOARD -->
                <?= view('profile/partials/_progress_board') ?>

                <!-- ERROR ALERTS -->
                <?php if (!empty($viewState['errors'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Ada kesalahan:</strong>
                        <ul class="mb-0 ps-3 mt-1">
                            <?php foreach ((array) $viewState['errors'] as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- TAB NAVIGATION -->
                <ul class="nav nav-tabs nav-fill profile-tabs animate-fade-up delay-2" id="profileTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $viewState['activeTab'] === 'akun' ? 'active' : '' ?>" id="akun-tab" data-bs-toggle="tab"
                            data-bs-target="#akun" type="button" role="tab" aria-selected="<?= $viewState['activeTab'] === 'akun' ? 'true' : 'false' ?>">
                            <i class="bi bi-key me-2"></i>Data Akun
                            <span class="badge text-bg-light border ms-2 profile-tab-badge" data-profile-tab-value="akun">0%</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $viewState['activeTab'] === 'profil' ? 'active' : '' ?>" id="profil-tab" data-bs-toggle="tab"
                            data-bs-target="#profil" type="button" role="tab" aria-selected="<?= $viewState['activeTab'] === 'profil' ? 'true' : 'false' ?>">
                            <i class="bi bi-person me-2"></i>Data Profil
                            <span class="badge text-bg-light border ms-2 profile-tab-badge" data-profile-tab-value="profil">0%</span>
                        </button>
                    </li>
                </ul>

                <!-- TAB CONTENT -->
                <div class="tab-content mt-3 animate-fade-up delay-3">
                    <!-- Tab Akun -->
                    <?= view('profile/partials/_tab_akun', ['user' => $user, 'viewState' => $viewState]) ?>

                    <!-- Tab Profil -->
                    <div class="tab-pane fade <?= $viewState['activeTab'] === 'profil' ? 'show active' : '' ?>" id="profil" role="tabpanel">
                        <!-- Section: Data Pribadi -->
                        <?= view('profile/partials/_section_pribadi', ['profile' => $profile]) ?>

                        <!-- Section: Kontak -->
                        <?= view('profile/partials/_section_kontak', ['profile' => $profile]) ?>

                        <!-- Section: Identitas -->
                        <?= view('profile/partials/_section_identitas', ['profile' => $profile]) ?>

                        <!-- Section: Akademik -->
                        <?= view('profile/partials/_section_akademik', ['profile' => $profile, 'master' => $master]) ?>

                        <!-- Section: Foto Profil -->
                        <?= view('profile/partials/_section_foto', ['viewState' => $viewState]) ?>
                    </div>
                </div>
            </div>

            <!-- ACTION BAR -->
            <div class="profile-action-bar" role="toolbar" aria-label="Aksi form profil">
                <div class="profile-action-bar__meta">
                    <div class="small text-muted">Progress keseluruhan</div>
                    <div class="fw-semibold"><span data-profile-overall-inline>0%</span> lengkap</div>
                </div>
                <div class="profile-action-bar__actions d-flex gap-2">
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>