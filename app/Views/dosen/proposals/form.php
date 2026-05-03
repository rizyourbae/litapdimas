<?php
/** @var string $title */
/** @var string $stepTitle */
/** @var int $currentStep */
/** @var array<int,string> $stepLabels */
/** @var string $formAction */
/** @var string $proposalId */
/** @var array<string,mixed> $proposal */
/** @var array<string,mixed> $masterOptions */

$this->extend('layouts/main');

$this->section('styles'); ?>
<link rel="stylesheet" href="<?= base_url('custom/css/proposal-wizard.css') ?>">
<?php $this->endSection();

$this->section('content');

$step = (int) ($currentStep ?? 1);
$progressPercent = (int) round(($step / 5) * 100);
?>

<?= view('components/dosen-hero', [
    'title' => $stepTitle ?? 'Buat Proposal',
    'subtitle' => 'Langkah ' . $step . ' dari 5',
    'icon' => 'bi bi-file-earmark-text',
]) ?>

<div class="container-fluid proposal-form-page">
    <div class="card wizard-progress-card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold text-primary">Progress Pengisian</div>
                <div class="badge text-bg-primary"><?= esc((string) $progressPercent) ?>%</div>
            </div>
            <div class="progress mb-4" role="progressbar" aria-valuenow="<?= esc((string) $progressPercent) ?>" aria-valuemin="0" aria-valuemax="100" style="height: 10px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: <?= esc((string) $progressPercent) ?>%;"></div>
            </div>

            <div class="stepper-container">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="stepper-step <?= $i <= $step ? 'active' : '' ?> <?= $i < $step ? 'completed' : '' ?>">
                        <div class="stepper-badge">
                            <?php if ($i < $step): ?>
                                <i class="bi bi-check-lg"></i>
                            <?php else: ?>
                                <?= $i ?>
                            <?php endif; ?>
                        </div>
                        <div class="stepper-label"><?= esc((string) ($stepLabels[$i] ?? ('Langkah ' . $i))) ?></div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= esc((string) session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= esc((string) session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card wizard-main-card dosen-form-card shadow-sm">
        <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="bi bi-pencil-square me-2"></i><?= esc((string) ($stepTitle ?? 'Form Pengisian')) ?>
            </h5>
            <span class="badge text-bg-light border">Langkah <?= esc((string) $step) ?>/5</span>
        </div>

        <div class="card-body">
            <form method="POST" action="<?= esc((string) $formAction) ?>" id="proposalForm" enctype="multipart/form-data" novalidate>
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
                                <i class="bi bi-chevron-left me-1"></i> Sebelumnya
                            </button>
                        <?php else: ?>
                            <a href="<?= site_url('dosen/proposals') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary px-4" id="wizardSubmitBtn">
                            <?php if ($step === 5): ?>
                                <i class="bi bi-send me-1"></i> Selesai & Kirim
                            <?php else: ?>
                                Simpan & Lanjut <i class="bi bi-chevron-right ms-1"></i>
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
        const currentStep = <?= (int) $step ?>;
        const proposalId = '<?= esc((string) $proposalId) ?>';

        if (currentStep > 1) {
            window.location.href = `<?= site_url('dosen/proposals/step/') ?>${currentStep - 1}/${proposalId}`;
        }
    }
</script>

<?php $this->endSection(); ?>