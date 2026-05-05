<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AuditLogService;

class AuditLogController extends BaseController
{
    protected AuditLogService $auditLogService;

    public function __construct()
    {
        $this->auditLogService = new AuditLogService();
    }

    public function index()
    {
        $data = [
            'title'         => 'Audit Logs',
            'currentModule' => 'Keamanan',
            'logs'          => $this->auditLogService->getFormattedLogs(150),
        ];

        return $this->renderView('admin/audit_logs/index', $data);
    }
}
