<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $auth = service('auth');
        $user = $auth->user();

        $dashboardService = new \App\Services\Admin\DashboardService();
        $stats = $dashboardService->getStats();

        $roleDisplay = implode(' / ', array_map('ucfirst', $auth->getRoleNames()));

        $data = [
            'title'         => 'Dashboard Admin',
            'currentModule' => 'Admin',
            'userName'      => $user['nama_lengkap'] ?? $user['username'],
            'userRole'      => $roleDisplay ?: 'Administrator',
            'stats'         => $stats
        ];

        return $this->renderView('admin/dashboard', $data);
    }
}
