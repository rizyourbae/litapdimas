<?= $this->extend('layouts/main') ?>

<?php $hide_header = true; ?>

<?= $this->section('content') ?>

<?php
/**
 * View variables defined in AdminProposalService::getDetailPayload
 * 
 * @var string $uuid
 * @var array $hero
 * @var array $summaryItems
 * @var array $abstract
 * @var array $substansiSections
 * @var array $journalInfo
 * @var array $teamSections
 * @var array $documentRows
 * @var array $assignmentPanel
 * @var array $decisionSummary
 * @var array $reviewerResultsPanel
 */

// Data preparation for partials
$recommendedReviewers = $assignmentPanel['recommended_reviewers'] ?? [];
$manualReviewers = $assignmentPanel['manual_reviewers'] ?? [];
$reviewerResultItems = $reviewerResultsPanel['items'] ?? [];
$presentationResultItems = $reviewerResultsPanel['presentation_items'] ?? [];
?>

<div class="row g-3 admin-page">
    <!-- HERO SECTION -->
    <?= view('admin/proposal/partials/_hero', ['hero' => $hero, 'title' => $title ?? 'Detail Proposal']) ?>

    <!-- MAIN CONTENT (Left Column) -->
    <div class="col-xl-8">
        <div class="card shadow-sm border-0 overflow-hidden mb-4 rounded-4">
            <!-- TAB NAVIGATION -->
            <div class="card-header p-0 bg-light border-bottom">
                <ul class="nav admin-nav-tabs border-0 px-3" id="proposalShowTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active border-0 py-3 fw-bold" id="proposalShowDetailTab" data-bs-toggle="tab" data-bs-target="#proposalShowDetailPane" type="button" role="tab">
                            <i class="bi bi-file-earmark-text-fill me-2"></i>Konten Proposal
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link border-0 py-3 fw-bold" id="proposalShowResultTab" data-bs-toggle="tab" data-bs-target="#proposalShowResultPane" type="button" role="tab">
                            <i class="bi bi-chat-left-dots-fill me-2"></i>Evaluasi & Review
                        </button>
                    </li>
                </ul>
            </div>

            <!-- TAB CONTENT -->
            <div class="card-body p-4 p-lg-5">
                <div class="tab-content" id="proposalShowTabsContent">
                    <!-- Tab Detail -->
                    <?= view('admin/proposal/partials/_tab_content_detail', [
                        'summaryItems' => $summaryItems,
                        'abstract' => $abstract,
                        'substansiSections' => $substansiSections,
                        'journalInfo' => $journalInfo,
                        'teamSections' => $teamSections,
                        'documentRows' => $documentRows
                    ]) ?>

                    <!-- Tab Result -->
                    <?= view('admin/proposal/partials/_tab_content_result', [
                        'reviewerResultsPanel' => $reviewerResultsPanel,
                        'reviewerResultItems' => $reviewerResultItems,
                        'presentationResultItems' => $presentationResultItems
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- SIDEBAR (Right Column) -->
    <div class="col-xl-4">
        <!-- Reviewer Assignment -->
        <?= view('admin/proposal/partials/_sidebar_reviewer', [
            'assignmentPanel' => $assignmentPanel,
            'recommendedReviewers' => $recommendedReviewers,
            'manualReviewers' => $manualReviewers
        ]) ?>

        <!-- Final Decision -->
        <?= view('admin/proposal/partials/_sidebar_decision', [
            'decisionSummary' => $decisionSummary,
            'hero' => $hero
        ]) ?>
    </div>
</div>

<!-- MODALS -->
<?= view('admin/proposal/partials/_modal_decision', ['uuid' => $uuid]) ?>

<?= $this->endSection() ?>

<?php $this->section('scripts'); ?>
<script src="<?= base_url('custom/js/admin-proposal-show.js') ?>"></script>
<?php $this->endSection(); ?>