<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $auth = service('auth');
        $user = $auth->user();

        $roleDisplay = implode(' / ', array_map('ucfirst', $auth->getRoleNames()));

        $data = [
            'title'         => 'Dashboard Admin',
            'currentModule' => 'Admin',
            'userName'      => $user['nama_lengkap'] ?? $user['username'],
            'userRole'      => $roleDisplay ?: 'Administrator',
        ];

        return $this->renderView('admin/dashboard', $data);
    }
}
