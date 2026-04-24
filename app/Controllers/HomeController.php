<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        // Data untuk landing page (nanti bisa dinamis dari database)
        $data = [
            'title'       => 'Litapdimas - Direktori Data',
            'currentPage' => 'home',
        ];

        // Gunakan layout khusus landing (tanpa sidebar)
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
