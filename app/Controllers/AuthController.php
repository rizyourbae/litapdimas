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

                $this->auditLog->log('LOGIN', 'user', (string) $user['id'], 'User login berhasil');
                return redirect()->to($redirectTo)->with('welcome', 'Selamat datang, ' . $displayName . '!');
            }
            
            // Optional: Log failed login attempts
            $this->auditLog->log('LOGIN_FAILED', 'user', null, "Percobaan login gagal untuk username: {$username}");

            return redirect()->back()->withInput()->with('error', 'Login gagal. Periksa kembali username dan password.');
        }

        // GET request: tampilkan form
        return view('auth/login', ['title' => 'Login Litapdimas']);
    }

    public function logout()
    {
        $user = service('auth')->user();
        if ($user) {
            $this->auditLog->log('LOGOUT', 'user', (string) $user['id'], 'User logout');
        }
        service('auth')->logout();
        return redirect()->to('login');
    }
}
