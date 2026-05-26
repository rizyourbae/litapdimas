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

            if (!$this->isCaptchaValid($this->request->getPost('captcha'))) {
                return redirect()->back()->withInput()->with('error', 'Kode Captcha tidak valid.');
            }

            // Validasi input
            if (empty($username) || empty($password)) {
                return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
            }

            if (service('auth')->attempt($username, $password)) {
                $user = service('auth')->user();
                $displayName = $user['nama_lengkap'] ?? $user['username'] ?? 'User';
                
                // OCP: Delegasikan penentuan rute ke metode terpisah
                $redirectTo = $this->getDashboardRoute(service('auth'));

                $this->auditLog->log('LOGIN', 'user', (string) $user['id'], 'User login berhasil');
                return redirect()->to($redirectTo)->with('welcome', 'Selamat datang, ' . $displayName . '!');
            }
            
            // Optional: Log failed login attempts
            $this->auditLog->log('LOGIN_FAILED', 'user', null, "Percobaan login gagal untuk username: {$username}");

            return redirect()->back()->withInput()->with('error', 'Login gagal. Periksa kembali username dan password.');
        }

        // GET request: tampilkan form
        return view('auth/login', ['title' => 'LOGIN']);
    }

    public function register()
    {
        // Jika sudah login, redirect
        if (service('auth')->isLoggedIn()) {
            return redirect()->to('dashboard');
        }

        if ($this->request->is('post')) {
            $data = $this->request->getPost();

            if (!$this->isCaptchaValid($this->request->getPost('captcha'))) {
                return redirect()->back()->withInput()->with('error', 'Kode Captcha tidak valid.');
            }

            // Validasi sederhana (SOLID: Controller handle basic request validation)
            $rules = [
                'nama_lengkap' => 'required|min_length[3]',
                'email'        => 'required|valid_email|is_unique[users.email]',
                'username'     => 'required|min_length[3]|is_unique[users.username]',
                'password'     => 'required|min_length[6]',
                'confirm_password' => 'required|matches[password]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $userId = service('auth')->register($data);

            if ($userId) {
                $this->auditLog->log('REGISTER', 'user', (string) $userId, "User mendaftar mandiri: {$data['nama_lengkap']}");
                return redirect()->to('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mendaftar. Silakan coba lagi nanti.');
        }

        // GET request: tampilkan form
        return view('auth/register', ['title' => 'REGISTER']);
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

    /**
     * Validasi kode captcha.
     * Menerapkan asas DRY (Don't Repeat Yourself) agar tidak ditulis berulang.
     */
    private function isCaptchaValid(?string $input): bool
    {
        $sessionCaptcha = session()->get('captcha_phrase');
        return $input && strtolower($input) === strtolower((string)$sessionCaptcha);
    }

    /**
     * Menentukan rute dashboard berdasarkan role user.
     * Menerapkan asas OCP (Open-Closed Principle). Jika ada role baru, 
     * kita hanya perlu menambahkan mapping di array ini tanpa mengubah if-else bercabang.
     */
    private function getDashboardRoute($auth): string
    {
        $roleRoutes = [
            'admin'    => 'admin/dashboard',
            'reviewer' => 'reviewer/dashboard',
            'dosen'    => 'dosen/dashboard',
        ];

        foreach ($roleRoutes as $role => $route) {
            if ($auth->hasRole($role)) {
                return $route;
            }
        }

        return 'dashboard';
    }
}
