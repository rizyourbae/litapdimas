<?php

namespace App\Controllers\Reviewer;

use App\Controllers\BaseController;
use App\Services\Reviewer\ReviewerAssessmentService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AssessmentController extends BaseController
{
    protected ReviewerAssessmentService $assessmentService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->assessmentService = new ReviewerAssessmentService();
    }

    public function index()
    {
        $activeTab = (string) $this->request->getGet('tab');
        $statusFilters = [
            'proposal' => (string) $this->request->getGet('proposal_status'),
            'presentasi' => (string) $this->request->getGet('presentasi_status'),
            'luaran' => (string) $this->request->getGet('luaran_status'),
            'portofolio' => (string) $this->request->getGet('portofolio_status'),
        ];

        return $this->renderView(
            'reviewer/assessments/index',
            $this->assessmentService->buildQueuePayload($activeTab, $statusFilters)
        );
    }

    public function history()
    {
        return $this->renderView(
            'reviewer/assessments/history',
            $this->assessmentService->buildHistoryPayload()
        );
    }

    public function show(string $category, string $itemKey)
    {
        $activeDetailTab = (string) $this->request->getGet('tab');
        $payload = $this->assessmentService->getAssessmentDetail($category, $itemKey, $activeDetailTab);

        if ($payload === null) {
            return redirect()
                ->to(site_url('reviewer/queue'))
                ->with('error', 'Data penilaian reviewer tidak ditemukan.');
        }

        $view = match ($payload['page_type'] ?? 'generic') {
            'proposal' => 'reviewer/assessments/show_proposal',
            'presentasi' => 'reviewer/assessments/show_presentasi',
            default => 'reviewer/assessments/show',
        };

        return $this->renderView($view, $payload);
    }

    public function saveProposal(string $itemKey)
    {
        $saved = $this->assessmentService->saveProposalAssessment(
            $itemKey,
            [
                'scores' => (array) $this->request->getPost('scores'),
                'comments' => (array) $this->request->getPost('comments'),
                'general_comment' => (string) $this->request->getPost('general_comment'),
                'validator_note' => (string) $this->request->getPost('validator_note'),
            ]
        );

        if (!$saved) {
            return redirect()
                ->to(site_url('reviewer/queue'))
                ->with('error', 'Data proposal untuk penilaian tidak ditemukan.');
        }

        return redirect()
            ->to(site_url('reviewer/queue/proposal/' . $itemKey . '?tab=scoring'))
            ->with('success', 'Penilaian proposal berhasil disimpan pada sesi reviewer ini.');
    }

    public function savePresentation(string $itemKey)
    {
        $saved = $this->assessmentService->savePresentationAssessment(
            $itemKey,
            [
                'scores' => (array) $this->request->getPost('scores'),
                'comments' => (array) $this->request->getPost('comments'),
                'general_comment' => (string) $this->request->getPost('general_comment'),
                'validator_note' => (string) $this->request->getPost('validator_note'),
            ]
        );

        if (!$saved) {
            return redirect()
                ->to(site_url('reviewer/queue?tab=presentasi'))
                ->with('error', 'Data presentasi untuk penilaian tidak ditemukan.');
        }

        return redirect()
            ->to(site_url('reviewer/queue/presentasi/' . $itemKey))
            ->with('success', 'Penilaian presentasi berhasil disimpan pada sesi reviewer ini.');
    }
}
