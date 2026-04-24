<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php $user = service('auth')->user(); ?>

<div class="row g-3 mb-4">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card border-0 bg-success text-white">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-clipboard2-check fs-1"></i>
                    <div>
                        <h5 class="mb-0">Selamat datang, <?= esc($user['nama_lengkap'] ?? $user['username']) ?>!</h5>
                        <small class="opacity-75">Panel Reviewer &mdash; Litapdimas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Stat: Menunggu Review -->
    <div class="col-md-4">
        <div class="card card-outline card-warning h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                    <i class="bi bi-hourglass text-warning fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small">Menunggu Review</div>
                    <div class="fs-4 fw-bold">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat: Sudah Direview -->
    <div class="col-md-4">
        <div class="card card-outline card-success h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="bi bi-check2-all text-success fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small">Sudah Direview</div>
                    <div class="fs-4 fw-bold">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat: Ditolak -->
    <div class="col-md-4">
        <div class="card card-outline card-danger h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                    <i class="bi bi-x-circle text-danger fs-3"></i>
                </div>
                <div>
                    <div class="text-muted small">Ditolak</div>
                    <div class="fs-4 fw-bold">0</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-list-check me-2"></i>Antrian Proposal
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted text-center py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    Tidak ada proposal yang perlu direview saat ini.
                </p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>