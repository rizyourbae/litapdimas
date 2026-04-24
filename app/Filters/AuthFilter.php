<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');
        if (!$auth->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika ada argumen, cek permission atau role
        if ($arguments) {
            $arg = $arguments[0];

            // Sintaks: auth:role:dosen  -> cek hasRole('dosen')
            if (str_starts_with($arg, 'role:')) {
                $roleName = substr($arg, 5);
                if (!$auth->hasRole($roleName)) {
                    return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
                }
            } else {
                // Sintaks: auth:users.manage -> cek can('users.manage')
                if (!$auth->can($arg)) {
                    return redirect()->back()->with('error', 'Anda tidak memiliki izin.');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada
    }
}
