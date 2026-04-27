<?php

namespace App\Controllers\Dosen\Proposal;

use App\Controllers\BaseController;
use App\Services\Proposal\ProposalDetailService;
use App\Services\Proposal\ProposalWizardService;
use App\Services\Proposal\ProposalMasterOptionService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * ProposalController
 *
 * Thin Controller untuk Proposal Wizard
 * Prinsip:
 * - Ambil request
 * - Delegasi ke service
 * - Return response
 * 
 * Tidak ada business logic di sini!
 */
class ProposalController extends BaseController
{
    private const REDIRECT_INDEX = 'dosen/proposals';
    private const NEW_PROPOSAL_TOKEN = '__new__';

    protected ProposalWizardService $wizardService;
    protected ProposalMasterOptionService $masterService;
    protected ProposalDetailService $detailService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->wizardService = new ProposalWizardService();
        $this->masterService = new ProposalMasterOptionService();
        $this->detailService = new ProposalDetailService();
    }

    /**
     * Helper: Get user ID from auth
     */
    private function userId(): int
    {
        return (int) (user()['id'] ?? 0);
    }

    /**
     * List all proposals for current user
     */
    public function index()
    {
        $userId = $this->userId();

        return $this->renderView('dosen/proposals/index', [
            'title' => 'Proposal Saya',
            'proposals' => $this->prepareProposalIndexPayload(
                $this->wizardService->getProposalsByUser($userId)
            ),
        ]);
    }

    /**
     * Create new proposal (init wizard)
     */
    public function create()
    {
        if ($this->userId() <= 0) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'User tidak terautentikasi dengan benar. Silakan login kembali.');
        }

        return $this->renderProposalStepForm(1, self::NEW_PROPOSAL_TOKEN, []);
    }

    /**
     * Store new proposal (POST create)
     * Redirects to step 1 form
     */
    public function store()
    {
        return $this->create();
    }

    /**
     * Display form for specific step
     */
    public function step(int $stepNum, string $uuid)
    {
        $userId = $this->userId();
        $proposal = $this->wizardService->getProposal($userId, $uuid);

        if (!$proposal) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'Proposal tidak ditemukan');
        }

        // Check authorization
        if ((int) $proposal->user_id !== $userId) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'Akses ditolak');
        }

        return $this->renderProposalStepForm($stepNum, $proposal->uuid, (array) $proposal);
    }

    /**
     * Save step data (POST form)
     */
    public function saveStep(int $stepNum, string $uuid)
    {
        $userId = $this->userId();
        $formData = $this->request->getPost();

        if ($stepNum === 1 && $uuid === self::NEW_PROPOSAL_TOKEN) {
            $createdUuid = $this->wizardService->createDraftFromStepOne($userId, $formData);

            if (!$createdUuid) {
                return redirect()
                    ->to(site_url('dosen/proposals/create'))
                    ->with('error', $this->wizardService->getLastError())
                    ->withInput();
            }

            return redirect()
                ->to("dosen/proposals/step/2/{$createdUuid}")
                ->with('success', 'Step 1 berhasil disimpan.');
        }

        try {
            $result = $this->wizardService->saveStepData(
                $userId,
                $uuid,
                $stepNum,
                $formData
            );

            if (!$result) {
                return redirect()
                    ->to("dosen/proposals/step/{$stepNum}/{$uuid}")
                    ->with('error', $this->wizardService->getLastError())
                    ->withInput();
            }

            // Redirect ke step berikutnya atau review
            $nextStep = $stepNum + 1;
            if ($nextStep > 5) {
                return redirect()
                    ->to("dosen/proposals/review/{$uuid}")
                    ->with('success', 'Step ' . $stepNum . ' berhasil disimpan. Silakan review proposal Anda.');
            }

            return redirect()
                ->to("dosen/proposals/step/{$nextStep}/{$uuid}")
                ->with('success', 'Step ' . $stepNum . ' berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()
                ->to("dosen/proposals/step/{$stepNum}/{$uuid}")
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Review page (ringkasan sebelum submit)
     */
    public function review(string $uuid)
    {
        $userId = $this->userId();
        $proposal = $this->wizardService->getProposal($userId, $uuid);

        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'Proposal tidak ditemukan');
        }

        return $this->renderView('dosen/proposals/review', [
            'title' => 'Review Proposal',
            'proposal' => $this->detailService->buildReviewPayload($proposal),
            'proposalUuid' => $uuid,
        ]);
    }

    /**
     * Submit proposal (final)
     */
    public function submit(string $uuid)
    {
        $userId = $this->userId();

        try {
            $result = $this->wizardService->submitProposal($userId, $uuid);

            if (!$result) {
                return redirect()
                    ->to("dosen/proposals/review/{$uuid}")
                    ->with('error', 'Gagal submit proposal: ' . $this->wizardService->getLastError());
            }

            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('success', 'Proposal berhasil disubmit untuk review.');
        } catch (\Exception $e) {
            return redirect()
                ->to("dosen/proposals/review/{$uuid}")
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show proposal detail
     */
    public function show(string $uuid)
    {
        $userId = $this->userId();
        $proposal = $this->wizardService->getProposal($userId, $uuid);

        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'Proposal tidak ditemukan');
        }

        if ($this->isUntouchedDraft($proposal)) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', 'Proposal ini belum memiliki data yang tersimpan.');
        }

        return $this->renderView('dosen/proposals/show', [
            'title' => 'Detail Proposal',
            'proposal' => $this->detailService->buildDetailPayload($proposal),
        ]);
    }

    public function delete(string $uuid)
    {
        $userId = $this->userId();

        if (!$this->wizardService->deleteProposal($userId, $uuid)) {
            return redirect()
                ->to(self::REDIRECT_INDEX)
                ->with('error', $this->wizardService->getLastError());
        }

        return redirect()
            ->to(self::REDIRECT_INDEX)
            ->with('success', 'Proposal berhasil dihapus.');
    }

    /**
     * Prepare payload untuk index page
     */
    private function prepareProposalIndexPayload(array $proposals): array
    {
        $visibleProposals = array_values(array_filter(
            $proposals,
            fn($proposal) => !$this->isUntouchedDraft($proposal)
        ));

        return array_map(function ($proposal) {
            $statusBadgeClass = match ($proposal->status ?? 'draft') {
                'draft' => 'text-bg-warning',
                'submitted' => 'text-bg-info',
                'reviewed' => 'text-bg-secondary',
                'approved' => 'text-bg-success',
                'rejected' => 'text-bg-danger',
                default => 'text-bg-light',
            };

            $statusLabel = ucfirst($proposal->status ?? 'draft');
            $status = $proposal->status ?? 'draft';
            $currentStep = (int) ($proposal->current_step ?? 1);

            $primaryActionLabel = $status === 'draft' ? 'Lanjutkan' : 'Detail';
            $primaryActionIcon = $status === 'draft' ? 'fas fa-pen' : 'fas fa-eye';
            $primaryActionUrl = $status === 'draft'
                ? site_url("dosen/proposals/step/{$currentStep}/{$proposal->uuid}")
                : site_url("dosen/proposals/show/{$proposal->uuid}");

            return [
                'uuid' => $proposal->uuid,
                'judul' => $proposal->judul ?? 'Judul belum diisi',
                'status' => $status,
                'status_badge_class' => $statusBadgeClass,
                'status_label' => $statusLabel,
                'current_step' => $currentStep,
                'created_at_formatted' => date_format(
                    date_create($proposal->created_at),
                    'd M Y'
                ),
                'show_url' => site_url("dosen/proposals/show/{$proposal->uuid}"),
                'edit_url' => site_url("dosen/proposals/step/{$currentStep}/{$proposal->uuid}"),
                'delete_url' => site_url("dosen/proposals/delete/{$proposal->uuid}"),
                'primary_action_label' => $primaryActionLabel,
                'primary_action_icon' => $primaryActionIcon,
                'primary_action_url' => $primaryActionUrl,
            ];
        }, $visibleProposals);
    }

    private function renderProposalStepForm(int $stepNum, string $proposalId, array $proposal): string
    {
        return $this->renderView('dosen/proposals/form', [
            'title' => 'Buat Proposal',
            'currentStep' => $stepNum,
            'stepTitle' => $this->getStepTitle($stepNum),
            'stepLabels' => [
                1 => 'Pernyataan Peneliti',
                2 => 'Data Peneliti',
                3 => 'Substansi Usulan',
                4 => 'Unggah Berkas',
                5 => 'Data Jurnal',
            ],
            'proposalId' => $proposalId,
            'proposal' => $proposal,
            'formAction' => site_url("dosen/proposals/step/{$stepNum}/{$proposalId}"),
            'masterOptions' => $this->masterService->getAllOptions(),
        ]);
    }

    private function isUntouchedDraft(object $proposal): bool
    {
        if (($proposal->status ?? null) !== 'draft') {
            return false;
        }

        if (trim((string) ($proposal->judul ?? '')) !== '') {
            return false;
        }

        $stepKeys = ['step_1_data', 'step_2_data', 'step_3_data', 'step_4_data', 'step_5_data'];

        foreach ($stepKeys as $stepKey) {
            $stepData = json_decode((string) ($proposal->{$stepKey} ?? '[]'), true);

            if (!empty($stepData)) {
                return false;
            }
        }

        return true;
    }
    /**
     * Get step title
     */
    private function getStepTitle(int $step): string
    {
        return match ($step) {
            1 => 'Pernyataan Peneliti',
            2 => 'Data Peneliti',
            3 => 'Substansi Usulan',
            4 => 'Unggah Berkas',
            5 => 'Data Jurnal',
            default => 'Step ' . $step,
        };
    }
}
