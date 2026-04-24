<?php

namespace App\Controllers\Reviewer;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $auth = service('auth');
        $user = $auth->user();

        $data = [
            'title'         => 'Dashboard Reviewer',
            'currentModule' => 'Reviewer',
        ];

        return $this->renderView('reviewer/dashboard', $data);
    }
}
