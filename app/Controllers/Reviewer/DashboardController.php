<?php

namespace App\Controllers\Reviewer;

use App\Controllers\BaseController;
use App\Services\Reviewer\ReviewerAssessmentService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class DashboardController extends BaseController
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

    public function index(): string
    {
        $auth = service('auth');
        $user = $auth->user();
        $displayName = 'Reviewer';

        if (is_array($user)) {
            $displayName = (string) ($user['nama_lengkap'] ?? $user['username'] ?? $displayName);
        }

        $data = $this->assessmentService->buildDashboardPayload();
        $data['userDisplayName'] = $displayName;

        return $this->renderView('reviewer/dashboard', $data);
    }
}
