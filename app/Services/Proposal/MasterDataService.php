<?php

namespace App\Services\Proposal;

use App\Models\Proposal\BidangIlmu;
use App\Models\Proposal\KlasterBantuan;
use App\Models\Proposal\TemaPenelitian;
use Exception;

/**
 * MasterDataService
 *
 * Menangani business logic untuk pengelolaan master data proposal:
 * - Bidang Ilmu
 * - Klaster Bantuan
 * - Tema Penelitian
 *
 * Prinsip SOLID:
 * - Single Responsibility: Hanya handle master data proposal
 * - Dependency Inversion: Inject models via constructor
 */
class MasterDataService
{
    protected BidangIlmu $bidangModel;
    protected KlasterBantuan $klasterModel;
    protected TemaPenelitian $temaModel;

    protected ?string $lastError = null;

    public function __construct()
    {
        $this->bidangModel  = new BidangIlmu();
        $this->klasterModel = new KlasterBantuan();
        $this->temaModel    = new TemaPenelitian();
    }

    /**
     * Get last error message
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    // ========================================================================
    // BIDANG ILMU
    // ========================================================================

    /**
     * Get all bidang ilmu
     */
    public function getAllBidangIlmu(): array
    {
        return $this->bidangModel->orderBy('nama', 'ASC')->findAll();
    }

    /**
     * Create bidang ilmu
     */
    public function createBidangIlmu(array $data): bool
    {
        try {
            $result = $this->bidangModel->insert($data);
            if (!$result) {
                $this->lastError = implode(', ', $this->bidangModel->errors());
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', '[MasterDataService] createBidangIlmu: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update bidang ilmu
     */
    public function updateBidangIlmu(int $id, array $data): bool
    {
        try {
            $result = $this->bidangModel->update($id, $data);
            if (!$result) {
                $this->lastError = implode(', ', $this->bidangModel->errors());
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', '[MasterDataService] updateBidangIlmu: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete bidang ilmu (soft delete)
     */
    public function deleteBidangIlmu(int $id): bool
    {
        try {
            $result = $this->bidangModel->delete($id);
            if (!$result) {
                $this->lastError = implode(', ', $this->bidangModel->errors());
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', '[MasterDataService] deleteBidangIlmu: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Find bidang ilmu by ID
     */
    public function findBidangIlmuById(int $id): ?object
    {
        return $this->bidangModel->find($id);
    }

    /**
     * Find bidang ilmu by UUID
     */
    public function findBidangIlmuByUuid(string $uuid): ?object
    {
        return $this->bidangModel->findByUuid($uuid);
    }

    // ========================================================================
    // KLASTER BANTUAN
    // ========================================================================

    /**
     * Get all klaster bantuan
     */
    public function getAllKlasterBantuan(): array
    {
        return $this->klasterModel->orderBy('nama', 'ASC')->findAll();
    }

    /**
     * Create klaster bantuan
     */
    public function createKlasterBantuan(array $data): bool
    {
        try {
            $result = $this->klasterModel->insert($data);
            if (!$result) {
                $this->lastError = implode(', ', $this->klasterModel->errors());
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', '[MasterDataService] createKlasterBantuan: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update klaster bantuan
     */
    public function updateKlasterBantuan(int $id, array $data): bool
    {
        try {
            $result = $this->klasterModel->update($id, $data);
            if (!$result) {
                $this->lastError = implode(', ', $this->klasterModel->errors());
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', '[MasterDataService] updateKlasterBantuan: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete klaster bantuan (soft delete)
     */
    public function deleteKlasterBantuan(int $id): bool
    {
        try {
            $result = $this->klasterModel->delete($id);
            if (!$result) {
                $this->lastError = implode(', ', $this->klasterModel->errors());
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', '[MasterDataService] deleteKlasterBantuan: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Find klaster bantuan by ID
     */
    public function findKlasterBantuanById(int $id): ?object
    {
        return $this->klasterModel->find($id);
    }

    /**
     * Find klaster bantuan by UUID
     */
    public function findKlasterBantuanByUuid(string $uuid): ?object
    {
        return $this->klasterModel->findByUuid($uuid);
    }

    // ========================================================================
    // TEMA PENELITIAN
    // ========================================================================

    /**
     * Get all tema penelitian
     */
    public function getAllTemaPenelitian(): array
    {
        return $this->temaModel->orderBy('nama', 'ASC')->findAll();
    }

    /**
     * Create tema penelitian
     */
    public function createTemaPenelitian(array $data): bool
    {
        try {
            $result = $this->temaModel->insert($data);
            if (!$result) {
                $this->lastError = implode(', ', $this->temaModel->errors());
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', '[MasterDataService] createTemaPenelitian: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update tema penelitian
     */
    public function updateTemaPenelitian(int $id, array $data): bool
    {
        try {
            $result = $this->temaModel->update($id, $data);
            if (!$result) {
                $this->lastError = implode(', ', $this->temaModel->errors());
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', '[MasterDataService] updateTemaPenelitian: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete tema penelitian (soft delete)
     */
    public function deleteTemaPenelitian(int $id): bool
    {
        try {
            $result = $this->temaModel->delete($id);
            if (!$result) {
                $this->lastError = implode(', ', $this->temaModel->errors());
                return false;
            }
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            log_message('error', '[MasterDataService] deleteTemaPenelitian: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Find tema penelitian by ID
     */
    public function findTemaPenelitianById(int $id): ?object
    {
        return $this->temaModel->find($id);
    }

    /**
     * Find tema penelitian by UUID
     */
    public function findTemaPenelitianByUuid(string $uuid): ?object
    {
        return $this->temaModel->findByUuid($uuid);
    }

    /**
     * Get summary counts for dashboard
     */
    public function getSummaryCounts(): array
    {
        return [
            'bidang_ilmu_count'    => $this->bidangModel->countAllResults(false),
            'klaster_bantuan_count' => $this->klasterModel->countAllResults(false),
            'tema_penelitian_count' => $this->temaModel->countAllResults(false),
        ];
    }
}
