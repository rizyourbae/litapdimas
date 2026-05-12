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
            if (str_contains($img, '..')) {
                throw new \Exception('Invalid path');
            }

            $fileUrl = $this->storage->getObjectUrl($mod, $img, '', Storage::SHR_PUBLIC);
            if (!$fileUrl) {
                throw new \Exception('Not found');
            }

            return redirect()->to($fileUrl);

        } catch (\Throwable $e) {
            return $this->response->setStatusCode(404)
                ->setBody('Not Found');
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
