<?php

namespace App\Controllers\Dosen\Publikasi;

use App\Controllers\BaseController;
use App\Services\Publikasi\PublikasiService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PublikasiController extends BaseController
{
    private const REDIRECT_INDEX = 'dosen/publikasi';

    protected PublikasiService $publikasiService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->publikasiService = new PublikasiService();
    }

    private function userId(): int
    {
        return (int) (user()['id'] ?? 0);
    }

    public function index(): string
    {
        return $this->renderView('dosen/publikasi/index', array_merge(
            ['title' => 'Publikasi Saya'],
            $this->publikasiService->getIndexPayloadByUser($this->userId())
        ));
    }

    public function create(): string
    {
        return $this->renderView('dosen/publikasi/form', array_merge(
            ['title' => 'Tambah Publikasi'],
            $this->publikasiService->getCreateFormPayloadForDosen()
        ));
    }

    public function show(string $key)
    {
        $payload = $this->publikasiService->getPublikasiDetailPayloadForDosen($key, $this->userId());
        if ($payload === null) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', 'Data tidak ditemukan.');
        }

        return $this->renderView('dosen/publikasi/show', array_merge(
            ['title' => 'Detail Publikasi'],
            $payload
        ));
    }

    public function store()
    {
        try {
            $data = $this->request->getPost();
            $data['user_id'] = $this->userId();
            $this->publikasiService->simpanPublikasi($data);

            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Data Publikasi berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(string $key)
    {
        $payload = $this->publikasiService->getEditFormPayloadForDosen($key, $this->userId());
        if ($payload === null) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', 'Data tidak ditemukan.');
        }

        return $this->renderView('dosen/publikasi/form', array_merge(
            ['title' => 'Edit Publikasi'],
            $payload
        ));
    }

    public function update(string $key)
    {
        try {
            $publikasi = $this->publikasiService->findByKeyAndUser($key, $this->userId());
            if (!$publikasi) {
                return redirect()->to(site_url(self::REDIRECT_INDEX))
                    ->with('error', 'Data tidak ditemukan.');
            }

            $data = $this->request->getPost();
            $data['user_id'] = $this->userId();
            $this->publikasiService->updatePublikasiByKey($key, $data);

            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Data Publikasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete(string $key)
    {
        try {
            $publikasi = $this->publikasiService->findByKeyAndUser($key, $this->userId());
            if (!$publikasi) {
                return redirect()->to(site_url(self::REDIRECT_INDEX))
                    ->with('error', 'Data tidak ditemukan.');
            }

            $this->publikasiService->deletePublikasiByKey($key);

            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Data Publikasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', $e->getMessage());
        }
    }
}
