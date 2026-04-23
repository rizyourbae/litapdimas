<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard Admin',
            'currentModule' => 'Admin',
            'userName' => 'Admin Test',   // nanti dari session
            'userRole' => 'Administrator',
        ];

        return $this->renderView('admin/dashboard', $data);
    }
}
