<?php

namespace App\Controllers\Dosen\ProfilSinta;

use App\Controllers\BaseController;
use App\Services\User\SintaProfileService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ProfilSintaController extends BaseController
{
    private const REDIRECT_INDEX = 'dosen/profil-sinta';

    private SintaProfileService $service;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->service = new SintaProfileService();
    }

    private function userId(): int
    {
        return (int) (user()['id'] ?? 0);
    }

    public function index(): string
    {
        return $this->renderView('dosen/profil_sinta/index', array_merge(
            ['title' => 'Profil SINTA'],
            $this->service->getIndexPayload($this->userId())
        ));
    }

    public function sync()
    {
        $idSinta = (string) $this->request->getPost('id_sinta');

        try {
            $this->service->sync($this->userId(), $idSinta);
            return redirect()->to(site_url(self::REDIRECT_INDEX))
                ->with('success', 'Profil SINTA berhasil disinkronkan.');
        } catch (\Throwable $e) {
            $this->service->markSyncFailed($this->userId(), $idSinta, $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
