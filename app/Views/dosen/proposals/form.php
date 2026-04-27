<?php

/**
 * dosen/proposals/form.php
 * Wizard form container dengan stepper
 */
$this->extend('layouts/main');
$this->section('content');

$step = (int) ($currentStep ?? 1);
$progressPercent = (int) round(($step / 5) * 100);
?>

<?= view('components/dosen-hero', [
    'title' => $stepTitle ?? 'Buat Proposal',
    'subtitle' => 'Step ' . $step . ' dari 5',
    'icon' => 'fas fa-file-alt',
]) ?>

<div class="container-fluid proposal-form-page">
    <style>
        .proposal-form-page .wizard-progress-card,
        .proposal-form-page .wizard-main-card {
            border: 0;
            border-radius: 0.9rem;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        }

        .proposal-form-page .stepper-container {
            display: grid;
            gap: 0.75rem;
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .proposal-form-page .stepper-step {
            position: relative;
            text-align: center;
            padding: 0.75rem 0.4rem;
            border-radius: 0.75rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .proposal-form-page .stepper-step.active {
            background: #e7f1ff;
            border-color: #7fb3ff;
        }

        .proposal-form-page .stepper-step.completed {
            background: #e8fff2;
            border-color: #77d8a8;
        }

        .proposal-form-page .stepper-badge {
            width: 32px;
            height: 32px;
            margin: 0 auto 0.4rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            font-weight: 700;
            background: #dbeafe;
            color: #1e40af;
        }

        .proposal-form-page .stepper-step.completed .stepper-badge {
            background: #10b981;
            color: #fff;
        }

        .proposal-form-page .stepper-label {
            font-size: 0.76rem;
            color: #334155;
            line-height: 1.25;
        }

        .proposal-form-page .wizard-actions {
            position: sticky;
            bottom: 0;
            background: #fff;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 991.98px) {
            .proposal-form-page .stepper-container {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .proposal-form-page .wizard-actions {
                position: static;
            }
        }
    </style>

    <div class="card wizard-progress-card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Progress Pengisian</div>
                <div class="text-muted small"><?= esc((string) $progressPercent) ?>%</div>
            </div>
            <div class="progress" role="progressbar" aria-valuenow="<?= esc((string) $progressPercent) ?>" aria-valuemin="0" aria-valuemax="100" style="height: 10px;">
                <div class="progress-bar" style="width: <?= esc((string) $progressPercent) ?>%;"></div>
            </div>

            <div class="stepper-container mt-3">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="stepper-step <?= $i <= $step ? 'active' : '' ?> <?= $i < $step ? 'completed' : '' ?>">
                        <div class="stepper-badge">
                            <?php if ($i < $step): ?>
                                <i class="fas fa-check"></i>
                            <?php else: ?>
                                <?= $i ?>
                            <?php endif; ?>
                        </div>
                        <div class="stepper-label"><?= esc($stepLabels[$i] ?? ('Step ' . $i)) ?></div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card wizard-main-card dosen-form-card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= esc($stepTitle ?? 'Form') ?></h5>
            <span class="badge text-bg-light">Step <?= esc((string) $step) ?>/5</span>
        </div>

        <div class="card-body">
            <form method="POST" action="<?= esc($formAction) ?>" id="proposalForm" enctype="multipart/form-data" novalidate>
                <?= csrf_field() ?>

                <?php
                $partialMap = [
                    1 => 'dosen/proposals/_partials/_step1',
                    2 => 'dosen/proposals/_partials/_step2',
                    3 => 'dosen/proposals/_partials/_step3',
                    4 => 'dosen/proposals/_partials/_step4',
                    5 => 'dosen/proposals/_partials/_step5',
                ];

                $partialPath = $partialMap[$step] ?? null;
                if ($partialPath) {
                    echo view($partialPath, [
                        'proposal' => $proposal,
                        'masterOptions' => $masterOptions,
                    ]);
                }
                ?>

                <div class="wizard-actions mt-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <?php if ($step > 1): ?>
                            <button type="button" class="btn btn-outline-secondary" onclick="previousStep()">
                                <i class="fas fa-chevron-left me-1"></i> Sebelumnya
                            </button>
                        <?php else: ?>
                            <a href="<?= site_url('dosen/proposals') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary px-4" id="wizardSubmitBtn">
                            <?php if ($step === 5): ?>
                                <i class="fas fa-check me-1"></i> Lanjut ke Review
                            <?php else: ?>
                                Simpan dan Lanjut <i class="fas fa-chevron-right ms-1"></i>
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('custom/js/proposal-wizard.js') ?>?v=20260427-02"></script>
<script>
    function previousStep() {
        const currentStep = <?= $step ?>;
        const proposalId = '<?= esc($proposalId) ?>';

        if (currentStep > 1) {
            window.location.href = `<?= site_url('dosen/proposals/step/') ?>${currentStep - 1}/${proposalId}`;
        }
    }
</script>

<?php $this->endSection(); ?>