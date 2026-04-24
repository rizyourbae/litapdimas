<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $auth = service('auth');
        $user = $auth->user();

        $data = [
            'title'         => 'Dashboard Dosen',
            'currentModule' => 'Dosen',
        ];

        return $this->renderView('dosen/dashboard', $data);
    }
}
