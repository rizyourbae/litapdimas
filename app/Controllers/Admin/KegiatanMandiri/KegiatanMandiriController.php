<?php

namespace App\Controllers\Admin\KegiatanMandiri;

use App\Controllers\BaseController;
use App\Services\KegiatanMandiri\KegiatanMandiriService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class KegiatanMandiriController extends BaseController
{
    private const REDIRECT_INDEX = 'admin/kegiatan-mandiri';

    private KegiatanMandiriService $kegiatanMandiriService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->kegiatanMandiriService = new KegiatanMandiriService();
    }

    public function index(): string
    {
        return $this->renderView('admin/kegiatan_mandiri/index', array_merge(
            ['title' => 'Data Kegiatan Mandiri'],
            $this->kegiatanMandiriService->getIndexPayload()
        ));
    }

    public function show(string $uuid)
    {
        $payload = $this->kegiatanMandiriService->getDetailPayload($uuid);
        if ($payload === null) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', 'Data kegiatan mandiri tidak ditemukan.');
        }

        return $this->renderView('admin/kegiatan_mandiri/show', array_merge(
            ['title' => 'Detail Kegiatan Mandiri'],
            $payload
        ));
    }

    public function create(): string
    {
        return $this->renderForm('Tambah Kegiatan Mandiri', null, site_url(self::REDIRECT_INDEX . '/store'));
    }

    public function store()
    {
        try {
            $this->kegiatanMandiriService->createKegiatan($this->request->getPost());
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Data kegiatan mandiri berhasil disimpan.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(string $uuid)
    {
        $kegiatan = $this->kegiatanMandiriService->getKegiatanByUuid($uuid);
        if (!$kegiatan) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', 'Data kegiatan mandiri tidak ditemukan.');
        }

        return $this->renderForm('Edit Kegiatan Mandiri', $kegiatan, site_url(self::REDIRECT_INDEX . '/update/' . $uuid));
    }

    public function update(string $uuid)
    {
        try {
            $this->kegiatanMandiriService->updateKegiatan($uuid, $this->request->getPost());
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Data kegiatan mandiri berhasil diperbarui.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete(string $uuid)
    {
        try {
            $this->kegiatanMandiriService->deleteKegiatan($uuid);
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Data kegiatan mandiri berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('error', $e->getMessage());
        }
    }

    private function renderForm(string $title, ?object $kegiatan, string $actionUrl): string
    {
        $payload = $kegiatan
            ? $this->kegiatanMandiriService->getEditFormPayload($kegiatan)
            : $this->kegiatanMandiriService->getCreateFormPayload();

        return $this->renderView('admin/kegiatan_mandiri/form', array_merge($payload, [
            'title'       => $title,
            'formAction'  => $actionUrl,
            'submitLabel' => $kegiatan ? 'Update Kegiatan' : 'Simpan Kegiatan',
        ]));
    }
}
