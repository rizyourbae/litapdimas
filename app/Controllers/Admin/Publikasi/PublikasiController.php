<?php

namespace App\Controllers\Admin\Publikasi;

use App\Controllers\BaseController;
use App\Services\Publikasi\PublikasiService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PublikasiController extends BaseController
{
    protected PublikasiService $publikasiService;

    private const REDIRECT_INDEX = 'admin/publikasi';

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->publikasiService = new PublikasiService();
    }

    public function index(): string
    {
        return $this->renderView('admin/publikasi/index', array_merge(
            ['title' => 'Data Publikasi'],
            $this->publikasiService->getIndexPayload()
        ));
    }

    public function create(): string
    {
        return $this->renderView('admin/publikasi/form', array_merge(
            ['title' => 'Tambah Data Publikasi'],
            $this->publikasiService->getCreateFormPayload()
        ));
    }

    public function show(string $key)
    {
        $payload = $this->publikasiService->getPublikasiDetailPayload($key);
        if ($payload === null) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', 'Data tidak ditemukan.');
        }

        return $this->renderView('admin/publikasi/show', array_merge(
            ['title' => 'Detail Publikasi'],
            $payload
        ));
    }

    public function store()
    {
        try {
            $this->publikasiService->simpanPublikasi($this->request->getPost());
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Data Publikasi berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(string $key)
    {
        $payload = $this->publikasiService->getEditFormPayload($key);
        if ($payload === null) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', 'Data tidak ditemukan.');
        }

        return $this->renderView('admin/publikasi/form', array_merge(
            ['title' => 'Edit Data Publikasi'],
            $payload
        ));
    }

    public function update(string $key)
    {
        try {
            $this->publikasiService->updatePublikasiByKey($key, $this->request->getPost());
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Data Publikasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete(string $key)
    {
        try {
            $this->publikasiService->deletePublikasiByKey($key);
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Data Publikasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', $e->getMessage());
        }
    }
}
