<?php

namespace App\Controllers\Admin\CMS;

use App\Controllers\BaseController;
use App\Services\CMS\LandingPageService;

class LandingController extends BaseController
{
    private LandingPageService $cmsService;

    public function __construct()
    {
        $this->cmsService = new LandingPageService();
    }

    /**
     * Halaman Utama CMS Landing
     */
    public function index()
    {
        return $this->renderView('admin/cms/landing/index', array_merge(
            ['title' => 'Manajemen Landing Page'],
            $this->cmsService->getAdminSettingsPayload()
        ));
    }

    /**
     * Update Pengaturan Umum (Hero, Stats, dll)
     */
    public function updateSettings()
    {
        try {
            $this->cmsService->updateSettings($this->request->getPost());
            return redirect()->back()->with('success', 'Pengaturan landing page berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Simpan Tema Riset Baru
     */
    public function storeTheme()
    {
        try {
            $this->cmsService->storeTheme($this->request->getPost());
            return redirect()->back()->with('success', 'Tema riset baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function jsonTheme(string $uuid)
    {
        try {
            $theme = $this->cmsService->getThemeByUuid($uuid);
            if (!$theme) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'Data tidak ditemukan']);
            }
            return $this->response->setJSON($theme);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    /**
     * Update Tema Riset
     */
    public function updateTheme(string $uuid)
    {
        try {
            $this->cmsService->updateTheme($uuid, $this->request->getPost());
            return redirect()->back()->with('success', 'Tema riset berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Hapus Tema Riset
     */
    public function deleteTheme(string $uuid)
    {
        try {
            $this->cmsService->deleteTheme($uuid);
            return redirect()->back()->with('success', 'Tema riset berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ============================================================
    // BANNER MANAGEMENT
    // ============================================================

    public function storeBanner()
    {
        try {
            $file = $this->request->getFile('image');
            $this->cmsService->storeBanner($this->request->getPost(), $file);
            return redirect()->back()->with('success', 'Banner baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function jsonBanner(string $uuid)
    {
        $banner = $this->cmsService->getBannerByUuid($uuid);
        return $this->response->setJSON($banner);
    }

    public function updateBanner(string $uuid)
    {
        try {
            $file = $this->request->getFile('image');
            $this->cmsService->updateBanner($uuid, $this->request->getPost(), $file);
            return redirect()->back()->with('success', 'Banner berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function deleteBanner(string $uuid)
    {
        try {
            $this->cmsService->deleteBanner($uuid);
            return redirect()->back()->with('success', 'Banner berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
