<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (service('auth')->isLoggedIn()) {
            return redirect()->to('admin/dashboard');
        }

        if ($this->request->is('post')) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // Validasi input
            if (empty($username) || empty($password)) {
                return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
            }

            if (service('auth')->attempt($username, $password)) {
                $user = service('auth')->user();
                $displayName = $user['nama_lengkap'] ?? $user['username'] ?? 'User';
                $auth = service('auth');

                if ($auth->hasRole('admin')) {
                    $redirectTo = 'admin/dashboard';
                } elseif ($auth->hasRole('reviewer')) {
                    $redirectTo = 'reviewer/dashboard';
                } elseif ($auth->hasRole('dosen')) {
                    $redirectTo = 'dosen/dashboard';
                } else {
                    $redirectTo = 'dashboard';
                }

                return redirect()->to($redirectTo)->with('welcome', 'Selamat datang, ' . $displayName . '!');
            }

            return redirect()->back()->withInput()->with('error', 'Login gagal. Periksa kembali username dan password.');
        }

        // GET request: tampilkan form
        return view('auth/login', ['title' => 'Login Litapdimas']);
    }

    public function logout()
    {
        service('auth')->logout();
        return redirect()->to('login');
    }
}
