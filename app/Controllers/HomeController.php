<?php

namespace App\Controllers;

use App\Services\CMS\LandingPageService;

class HomeController extends BaseController
{
    public function index()
    {
        $cmsService = new LandingPageService();
        $payload = $cmsService->getPublicLandingPayload();

        $data = array_merge([
            'title'       => 'Litapdimas - UINSI Samarinda',
            'currentPage' => 'home',
        ], $payload);

        return view('layouts/landing', $data);
    }

    /**
     * Smart redirect dashboard berdasarkan role user.
     * Priority: admin > reviewer > dosen
     */
    public function dashboard()
    {
        $auth = service('auth');

        if ($auth->hasRole('admin')) {
            return redirect()->to('admin/dashboard');
        }

        if ($auth->hasRole('reviewer')) {
            return redirect()->to('reviewer/dashboard');
        }

        if ($auth->hasRole('dosen')) {
            return redirect()->to('dosen/dashboard');
        }

        // Fallback: user login tapi belum punya role, arahkan ke login
        return redirect()->to('login')->with('error', 'Akun Anda belum memiliki role. Hubungi administrator.');
    }
}
