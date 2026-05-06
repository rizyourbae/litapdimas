<?php

namespace App\Controllers;

use App\Services\CMS\AnnouncementService;

class AnnouncementController extends BaseController
{
    private AnnouncementService $service;

    public function __construct()
    {
        $this->service = new AnnouncementService();
    }

    /**
     * Daftar Pengumuman (Indeks)
     */
    public function index()
    {
        $payload = $this->service->getPublicList(9);
        
        $data = [
            'title'         => 'Pengumuman Terbaru',
            'announcements' => $payload['list'],
            'pager'         => $payload['pager'],
            'currentPage'   => 'pengumuman'
        ];

        return view('landing/announcements/index', $data);
    }

    /**
     * Detail Pengumuman
     */
    public function show(string $slug)
    {
        $announcement = $this->service->getBySlug($slug);
        
        if (!$announcement) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pengumuman tidak ditemukan.');
        }

        $data = [
            'title'        => $announcement['title'],
            'row'          => $announcement,
            'currentPage'  => 'pengumuman'
        ];

        return view('landing/announcements/show', $data);
    }
}
