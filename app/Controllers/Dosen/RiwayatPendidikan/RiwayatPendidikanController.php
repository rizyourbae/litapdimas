<?php

namespace App\Controllers\Dosen\RiwayatPendidikan;

use App\Controllers\BaseController;
use App\Services\User\RiwayatPendidikanService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class RiwayatPendidikanController extends BaseController
{
    private const REDIRECT_INDEX = 'dosen/riwayat-pendidikan';

    private RiwayatPendidikanService $service;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->service = new RiwayatPendidikanService();
    }

    private function userId(): int
    {
        return (int) (user()['id'] ?? 0);
    }

    public function index(): string
    {
        return $this->renderView('dosen/riwayat_pendidikan/index', array_merge(
            ['title' => 'Riwayat Pendidikan Saya'],
            $this->service->getIndexPayload($this->userId())
        ));
    }

    public function create(): string
    {
        return $this->renderView('dosen/riwayat_pendidikan/form', array_merge(
            ['title' => 'Tambah Riwayat Pendidikan'],
            $this->service->getCreateFormPayload()
        ));
    }

    public function store()
    {
        try {
            $data = $this->request->getPost();
            $data['user_id'] = $this->userId();
            $fileUpload = $this->request->getFile('file_dokumen');

            $this->service->create($data, $fileUpload);

            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Riwayat pendidikan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(string $key)
    {
        $payload = $this->service->getEditFormPayload($key, $this->userId());
        if ($payload === null) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', 'Data tidak ditemukan.');
        }

        return $this->renderView('dosen/riwayat_pendidikan/form', array_merge(
            ['title' => 'Edit Riwayat Pendidikan'],
            $payload
        ));
    }

    public function update(string $key)
    {
        try {
            $data = $this->request->getPost();
            $data['user_id'] = $this->userId();
            $fileUpload = $this->request->getFile('file_dokumen');

            $this->service->update($key, $data, $this->userId(), $fileUpload);

            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Riwayat pendidikan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete(string $key)
    {
        try {
            $this->service->delete($key, $this->userId());

            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Riwayat pendidikan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', $e->getMessage());
        }
    }
}
