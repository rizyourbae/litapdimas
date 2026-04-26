<?php

namespace App\Controllers\Admin\Proposal;

use App\Controllers\BaseController;
use App\Services\Proposal\MasterDataService;

/**
 * MasterDataProposalController
 *
 * Controller untuk mengelola master data proposal (Bidang Ilmu, Klaster Bantuan, Tema Penelitian)
 * Khusus untuk role Admin
 *
 * Prinsip Clean Code:
 * - Thin Controller: Hanya handle HTTP request/response
 * - Zero Logic in View: Semua logic di service
 * - DRY: Reuse service untuk semua operasi CRUD
 */
class MasterDataProposalController extends BaseController
{
    /**
     * Service instance for master data operations.
     * Kept untyped to avoid static analysis issues in some environments.
     * @var MasterDataService
     */
    protected $service;
    protected string $pageTitle = 'Master Data Proposal';

    public function __construct()
    {
        $this->service = new MasterDataService();

        // Pastikan hanya admin yang bisa akses (gunakan service auth yang konsisten di project)
        $auth = service('auth');
        if (!$auth || !$auth->hasRole('admin')) {
            // Redirect segera jika tidak berwenang; panggil send() lalu exit agar tidak mengembalikan nilai dari konstruktor
            $response = redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
            if (method_exists($response, 'send')) {
                $response->send();
            }
            exit;
        }
    }

    /**
     * Display halaman master data proposal
     * Menampilkan 3 section: Bidang Ilmu, Klaster Bantuan, Tema Penelitian
     */
    public function index(): string
    {
        $data = [
            'title'             => $this->pageTitle,
            'bidang_ilmu'       => $this->service->getAllBidangIlmu(),
            'klaster_bantuan'   => $this->service->getAllKlasterBantuan(),
            'tema_penelitian'   => $this->service->getAllTemaPenelitian(),
            'counts'            => $this->service->getSummaryCounts(),
        ];

        return $this->renderView('admin/proposal/master_data', $data);
    }

    // ========================================================================
    // BIDANG ILMU ACTIONS
    // ========================================================================

    /**
     * Create bidang ilmu
     */
    public function storeBidangIlmu()
    {
        $validationRules = [
            'nama' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
        ];

        if ($this->service->createBidangIlmu($data)) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('success', 'Bidang ilmu berhasil ditambahkan.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->service->getLastError() ?? 'Gagal menambahkan bidang ilmu.');
    }

    /**
     * Update bidang ilmu
     */
    public function updateBidangIlmu(string $uuid)
    {
        $validationRules = [
            'nama' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
        ];

        $item = $this->service->findBidangIlmuByUuid($uuid);
        if (!$item) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('error', 'Bidang ilmu tidak ditemukan.');
        }

        if ($this->service->updateBidangIlmu((int) $item->id, $data)) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('success', 'Bidang ilmu berhasil diperbarui.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->service->getLastError() ?? 'Gagal memperbarui bidang ilmu.');
    }

    /**
     * Delete bidang ilmu
     */
    public function deleteBidangIlmu(string $uuid)
    {
        $item = $this->service->findBidangIlmuByUuid($uuid);
        if (!$item) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('error', 'Bidang ilmu tidak ditemukan.');
        }

        if ($this->service->deleteBidangIlmu((int) $item->id)) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('success', 'Bidang ilmu berhasil dihapus.');
        }

        return redirect()->back()
            ->with('error', $this->service->getLastError() ?? 'Gagal menghapus bidang ilmu.');
    }

    /**
     * JSON data bidang ilmu untuk form edit.
     */
    public function jsonBidangIlmu(string $uuid)
    {
        $item = $this->service->findBidangIlmuByUuid($uuid);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Bidang ilmu tidak ditemukan.',
            ]);
        }

        return $this->response->setJSON([
            'uuid' => $item->uuid ?? null,
            'id' => $item->id ?? null,
            'nama' => $item->nama ?? '',
        ]);
    }

    // ========================================================================
    // KLASTER BANTUAN ACTIONS
    // ========================================================================

    /**
     * Create klaster bantuan
     */
    public function storeKlasterBantuan()
    {
        $validationRules = [
            'nama' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
        ];

        if ($this->service->createKlasterBantuan($data)) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('success', 'Klaster bantuan berhasil ditambahkan.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->service->getLastError() ?? 'Gagal menambahkan klaster bantuan.');
    }

    /**
     * Update klaster bantuan
     */
    public function updateKlasterBantuan(string $uuid)
    {
        $validationRules = [
            'nama' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
        ];

        $item = $this->service->findKlasterBantuanByUuid($uuid);
        if (!$item) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('error', 'Klaster bantuan tidak ditemukan.');
        }

        if ($this->service->updateKlasterBantuan((int) $item->id, $data)) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('success', 'Klaster bantuan berhasil diperbarui.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->service->getLastError() ?? 'Gagal memperbarui klaster bantuan.');
    }

    /**
     * Delete klaster bantuan
     */
    public function deleteKlasterBantuan(string $uuid)
    {
        $item = $this->service->findKlasterBantuanByUuid($uuid);
        if (!$item) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('error', 'Klaster bantuan tidak ditemukan.');
        }

        if ($this->service->deleteKlasterBantuan((int) $item->id)) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('success', 'Klaster bantuan berhasil dihapus.');
        }

        return redirect()->back()
            ->with('error', $this->service->getLastError() ?? 'Gagal menghapus klaster bantuan.');
    }

    /**
     * JSON data klaster bantuan untuk form edit.
     */
    public function jsonKlasterBantuan(string $uuid)
    {
        $item = $this->service->findKlasterBantuanByUuid($uuid);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Klaster bantuan tidak ditemukan.',
            ]);
        }

        return $this->response->setJSON([
            'uuid' => $item->uuid ?? null,
            'id' => $item->id ?? null,
            'nama' => $item->nama ?? '',
        ]);
    }

    // ========================================================================
    // TEMA PENELITIAN ACTIONS
    // ========================================================================

    /**
     * Create tema penelitian
     */
    public function storeTemaPenelitian()
    {
        $validationRules = [
            'nama' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
        ];

        if ($this->service->createTemaPenelitian($data)) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('success', 'Tema penelitian berhasil ditambahkan.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->service->getLastError() ?? 'Gagal menambahkan tema penelitian.');
    }

    /**
     * Update tema penelitian
     */
    public function updateTemaPenelitian(string $uuid)
    {
        $validationRules = [
            'nama' => 'required|min_length[3]|max_length[100]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(', ', $this->validator->getErrors()));
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
        ];

        $item = $this->service->findTemaPenelitianByUuid($uuid);
        if (!$item) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('error', 'Tema penelitian tidak ditemukan.');
        }

        if ($this->service->updateTemaPenelitian((int) $item->id, $data)) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('success', 'Tema penelitian berhasil diperbarui.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->service->getLastError() ?? 'Gagal memperbarui tema penelitian.');
    }

    /**
     * Delete tema penelitian
     */
    public function deleteTemaPenelitian(string $uuid)
    {
        $item = $this->service->findTemaPenelitianByUuid($uuid);
        if (!$item) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('error', 'Tema penelitian tidak ditemukan.');
        }

        if ($this->service->deleteTemaPenelitian((int) $item->id)) {
            return redirect()->to(site_url('admin/master-data-proposal'))
                ->with('success', 'Tema penelitian berhasil dihapus.');
        }

        return redirect()->back()
            ->with('error', $this->service->getLastError() ?? 'Gagal menghapus tema penelitian.');
    }

    /**
     * JSON data tema penelitian untuk form edit.
     */
    public function jsonTemaPenelitian(string $uuid)
    {
        $item = $this->service->findTemaPenelitianByUuid($uuid);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Tema penelitian tidak ditemukan.',
            ]);
        }

        return $this->response->setJSON([
            'uuid' => $item->uuid ?? null,
            'id' => $item->id ?? null,
            'nama' => $item->nama ?? '',
        ]);
    }
}
