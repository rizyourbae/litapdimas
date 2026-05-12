<?php

namespace App\Controllers;

use App\Services\CMS\LandingPageService;
use App\Libraries\Storage;

class HomeController extends BaseController
{
    protected Storage $storage;

    public function __construct()
    {
        $this->storage = new Storage();
    }

    public function index()
    {
        $cmsService = new LandingPageService();
        $payload = $cmsService->getPublicLandingPayload();

        $data = array_merge([
            'title'       => 'SMART - LP2M',
            'currentPage' => 'home',
        ], $payload);

        return view('landing/index', $data);
    }

    public function mediaServe(string $mod, string $img){
        try {
            $fileUrl = $this->storage->getObjectUrl($mod, $img, '', Storage::SHR_PUBLIC);
            return redirect()->to($fileUrl);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(404)
                ->setBody('<h1>404 Not Found</h1><p>File tidak ditemukan atau tidak dapat diakses.</p>');
        }
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
