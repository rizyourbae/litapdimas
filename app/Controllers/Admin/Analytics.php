<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Admin\AnalyticsService;

class Analytics extends BaseController
{
    protected $service;

    public function __construct()
    {
        $this->service = new AnalyticsService();
    }

    public function index()
    {
        $data = [
            'title'         => 'Analisa & Statistik',
            'currentModule' => 'Admin',
            'chartData'     => [
                'status'  => $this->service->getStatusDistribution(),
                'trend'   => $this->service->getProposalTrend(),
                'cluster' => $this->service->getClusterDistribution()
            ]
        ];

        return $this->renderView('admin/analytics/index', $data);
    }
}
