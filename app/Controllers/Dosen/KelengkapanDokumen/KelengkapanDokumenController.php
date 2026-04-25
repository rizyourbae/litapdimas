<?php

namespace App\Controllers\Dosen\KelengkapanDokumen;

use App\Controllers\BaseController;
use App\Services\User\KelengkapanDokumenService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class KelengkapanDokumenController extends BaseController
{
    private const REDIRECT_INDEX = 'dosen/kelengkapan-dokumen';

    private KelengkapanDokumenService $service;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->service = new KelengkapanDokumenService();
    }

    private function userId(): int
    {
        return (int) (user()['id'] ?? 0);
    }

    public function index(): string
    {
        return $this->renderView('dosen/kelengkapan_dokumen/index', array_merge(
            ['title' => 'Kelengkapan Dokumen'],
            $this->service->getIndexPayload($this->userId())
        ));
    }

    public function edit(string $key)
    {
        $payload = $this->service->getEditFormPayload($key, $this->userId());
        if ($payload === null) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', 'Data tidak ditemukan.');
        }

        return $this->renderView('dosen/kelengkapan_dokumen/edit', array_merge(
            ['title' => 'Unggah ' . ($payload['formValues']['jenis_dokumen'] ?? 'Dokumen')],
            $payload
        ));
    }

    public function update(string $key)
    {
        try {
            $fileUpload = $this->request->getFile('file_dokumen');
            $this->service->update($key, $this->userId(), $fileUpload);

            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Dokumen berhasil diupload.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
