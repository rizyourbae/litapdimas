<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php $user = service('auth')->user(); ?>

<div class="row g-3 mb-4">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card border-0 bg-primary text-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-mortarboard fs-1"></i>
                    <div>
                        <h5 class="mb-0">Selamat datang, <?= esc($user['nama_lengkap'] ?? $user['username']) ?>!</h5>
                        <small class="opacity-75">Panel Dosen &mdash; Litapdimas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Stat: Proposal Diajukan -->
    <div class="col-md-4">
        <div class="card card-outline card-primary h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-file-earmark-text text-primary fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small">Proposal Diajukan</div>
                    <div class="fs-4 fw-bold">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat: Sedang Direview -->
    <div class="col-md-4">
        <div class="card card-outline card-warning h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                    <i class="bi bi-hourglass-split text-warning fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small">Sedang Direview</div>
                    <div class="fs-4 fw-bold">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat: Diterima -->
    <div class="col-md-4">
        <div class="card card-outline card-success h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="bi bi-check-circle text-success fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small">Proposal Diterima</div>
                    <div class="fs-4 fw-bold">0</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>Proposal Terbaru
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted text-center py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    Belum ada proposal yang diajukan.
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>