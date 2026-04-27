<?php

namespace App\Controllers\Admin\Proposal;

use App\Controllers\BaseController;
use App\Services\Proposal\AdminProposalService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ProposalController extends BaseController
{
    private const REDIRECT_INDEX = 'admin/proposals';

    private AdminProposalService $adminProposalService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->adminProposalService = new AdminProposalService();
    }

    public function index(): string
    {
        return $this->renderView('admin/proposal/index', array_merge(
            ['title' => 'Ajuan Proposal'],
            $this->adminProposalService->getIndexPayload()
        ));
    }

    public function show(string $uuid)
    {
        $payload = $this->adminProposalService->getDetailPayload($uuid);
        if ($payload === null) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', 'Proposal tidak ditemukan atau belum siap dikelola dari panel admin.');
        }

        return $this->renderView('admin/proposal/show', array_merge(
            ['title' => 'Detail Ajuan Proposal'],
            $payload
        ));
    }

    public function assignReviewers(string $uuid)
    {
        try {
            $reviewerIds = (array) $this->request->getPost('reviewer_ids');
            $assignmentNotes = trim((string) $this->request->getPost('assignment_notes'));

            $this->adminProposalService->assignReviewers(
                $uuid,
                $reviewerIds,
                (int) (user()['id'] ?? 0),
                $assignmentNotes !== '' ? $assignmentNotes : null
            );

            return redirect()->to(site_url('admin/proposals/show/' . $uuid))
                ->with('success', 'Reviewer berhasil ditugaskan.');
        } catch (\Throwable $throwable) {
            return redirect()->to(site_url('admin/proposals/show/' . $uuid))
                ->with('error', $throwable->getMessage());
        }
    }

    public function removeReviewer(string $proposalUuid, string $assignmentUuid)
    {
        try {
            $this->adminProposalService->removeReviewerAssignment($proposalUuid, $assignmentUuid);

            return redirect()->to(site_url('admin/proposals/show/' . $proposalUuid))
                ->with('success', 'Assignment reviewer berhasil dibatalkan.');
        } catch (\Throwable $throwable) {
            return redirect()->to(site_url('admin/proposals/show/' . $proposalUuid))
                ->with('error', $throwable->getMessage());
        }
    }
}
