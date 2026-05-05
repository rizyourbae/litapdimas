<?php
/** @var array<string,mixed> $proposal */

$this->extend('layouts/main');
$hide_header = true;

$this->section('styles');
?>
<link rel="stylesheet" href="<?= base_url('custom/css/dosen-proposal-show.css') ?>">
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>

<?= view('components/dosen-hero', [
    'title' => 'Detail Proposal',
    'subtitle' => trim((string) ($proposal['status_label'] ?? 'Draft')) . ' · Ringkasan dan detail proposal',
    'icon' => 'bi bi-file-earmark-medical',
]) ?>

<div class="container-fluid proposal-show-page px-0">
    <div class="card detail-card dosen-form-card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3 px-4">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Ringkasan Proposal</h5>
            <span class="badge <?= esc((string) $proposal['status_badge_class']) ?> px-3 py-2"><?= esc((string) $proposal['status_label']) ?></span>
        </div>

        <div class="card-body p-4">
            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="meta-pill"><i class="bi bi-clock"></i> Dibuat: <?= format_indo($proposal['created_at'], true) ?></span>
                <span class="meta-pill"><i class="bi bi-graph-up"></i> Status: <?= esc((string) $proposal['status_label']) ?></span>
                <span class="meta-pill"><i class="bi bi-list-ol"></i> Langkah Saat Ini: <?= esc((string) ($proposal['current_step'] ?? 1)) ?>/5</span>
            </div>

            <ul class="nav nav-tabs tab-nav mb-4" id="proposalDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-pane" type="button" role="tab" aria-controls="summary-pane" aria-selected="true">
                        <i class="bi bi-file-text me-1"></i>Summary
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review-pane" type="button" role="tab" aria-controls="review-pane" aria-selected="false">
                        <i class="bi bi-person-search me-1"></i>Review
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="logbook-tab" data-bs-toggle="tab" data-bs-target="#logbook-pane" type="button" role="tab" aria-controls="logbook-pane" aria-selected="false">
                        <i class="bi bi-book me-1"></i>Logbook
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="outputs-tab" data-bs-toggle="tab" data-bs-target="#outputs-pane" type="button" role="tab" aria-controls="outputs-pane" aria-selected="false">
                        <i class="bi bi-table me-1"></i>Outputs
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance-pane" type="button" role="tab" aria-controls="finance-pane" aria-selected="false">
                        <i class="bi bi-wallet2 me-1"></i>Laporan Keuangan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="outcomes-tab" data-bs-toggle="tab" data-bs-target="#outcomes-pane" type="button" role="tab" aria-controls="outcomes-pane" aria-selected="false">
                        <i class="bi bi-display me-1"></i>Outcomes
                    </button>
                </li>
            </ul>

            <div class="tab-content mb-4" id="proposalDetailTabsContent">
                <div class="tab-pane fade show active" id="summary-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                    <?= $this->include('dosen/proposals/tabs/summary') ?>
                </div>

                <div class="tab-pane fade" id="review-pane" role="tabpanel" aria-labelledby="review-tab" tabindex="0">
                    <?= $this->include('dosen/proposals/tabs/review') ?>
                </div>

                <div class="tab-pane fade" id="logbook-pane" role="tabpanel" aria-labelledby="logbook-tab" tabindex="0">
                    <?= $this->include('dosen/proposals/tabs/logbook') ?>
                </div>
                <div class="tab-pane fade" id="outputs-pane" role="tabpanel" aria-labelledby="outputs-tab" tabindex="0">
                    <?= $this->include('dosen/proposals/tabs/outputs') ?>
                </div>
                <div class="tab-pane fade" id="finance-pane" role="tabpanel" aria-labelledby="finance-tab" tabindex="0">
                    <?= $this->include('dosen/proposals/tabs/finance') ?>
                </div>
                <div class="tab-pane fade" id="outcomes-pane" role="tabpanel" aria-labelledby="outcomes-tab" tabindex="0">
                    <?= $this->include('dosen/proposals/tabs/outcomes') ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php if (($proposal['status'] ?? '') === 'approved'): ?>
<?= $this->include('dosen/proposals/modals/add_logbook') ?>

<?= $this->include('dosen/proposals/modals/add_jurnal') ?>

<?= $this->include('dosen/proposals/modals/add_buku') ?>
<?php endif; ?>

<?php $this->endSection(); ?>
