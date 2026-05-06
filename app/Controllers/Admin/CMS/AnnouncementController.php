<?php

namespace App\Controllers\Admin\CMS;

use App\Controllers\BaseController;
use App\Services\CMS\AnnouncementService;

class AnnouncementController extends BaseController
{
    private AnnouncementService $service;

    public function __construct()
    {
        $this->service = new AnnouncementService();
    }

    public function index()
    {
        $data = [
            'title'         => 'Manajemen Pengumuman',
            'announcements' => $this->service->getAdminList()
        ];

        return $this->renderView('admin/cms/announcements/index', $data);
    }

    public function store()
    {
        try {
            $image = $this->request->getFile('image');
            $file  = $this->request->getFile('file_attachment');
            
            $this->service->store($this->request->getPost(), $image, $file);
            return redirect()->back()->with('success', 'Pengumuman berhasil diterbitkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function json(string $uuid)
    {
        $data = $this->service->getByUuid($uuid);
        return $this->response->setJSON($data);
    }

    public function update(string $uuid)
    {
        try {
            $image = $this->request->getFile('image');
            $file  = $this->request->getFile('file_attachment');
            
            $this->service->update($uuid, $this->request->getPost(), $image, $file);
            return redirect()->back()->with('success', 'Pengumuman berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete(string $uuid)
    {
        try {
            $this->service->delete($uuid);
            return redirect()->back()->with('success', 'Pengumuman berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
