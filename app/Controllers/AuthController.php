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
            $captchaInput = $this->request->getPost('captcha');
            $captchaSession = session()->get('captcha_phrase');

            if (!$captchaInput || strtolower($captchaInput) !== strtolower((string)$captchaSession)) {
                return redirect()->back()->withInput()->with('error', 'Kode Captcha tidak valid.');
            }

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
        $builder = new \Gregwar\Captcha\CaptchaBuilder;
        $builder->build();
        session()->set('captcha_phrase', $builder->getPhrase());

        return view('auth/login', [
            'title' => 'LOGIN',
            'captcha' => $builder->inline()
        ]);
    }

    public function register()
    {
        // Jika sudah login, redirect
        if (service('auth')->isLoggedIn()) {
            return redirect()->to('dashboard');
        }

        if ($this->request->is('post')) {
            $data = $this->request->getPost();
            $captchaInput = $this->request->getPost('captcha');
            $captchaSession = session()->get('captcha_phrase');

            if (!$captchaInput || strtolower($captchaInput) !== strtolower((string)$captchaSession)) {
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

        $builder = new \Gregwar\Captcha\CaptchaBuilder;
        $builder->build();
        session()->set('captcha_phrase', $builder->getPhrase());

        return view('auth/register', [
            'title' => 'REGISTER',
            'captcha' => $builder->inline()
        ]);
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
