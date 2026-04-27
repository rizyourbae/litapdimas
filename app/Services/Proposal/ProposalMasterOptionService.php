<?php

namespace App\Services\Proposal;

use App\Models\Proposal\PengelolaBantuan;
use App\Models\Proposal\JenisPenelitian;
use App\Models\Proposal\KontribusiProdi;
use App\Models\Proposal\BidangIlmu;
use App\Models\Proposal\KlasterBantuan;
use App\Models\Proposal\TemaPenelitian;

/**
 * ProposalMasterOptionService
 *
 * Service untuk menampilkan semua master data option/dropdown
 * Prinsip: DRY - satu source of truth untuk dropdown di semua form
 * 
 * Zero Logic in View: Controller/Service prepare data, view only display
 * Thin Controller: All aggregation done here
 */
class ProposalMasterOptionService
{
    private $pengelolaBantuan;
    private $jenisPenelitian;
    private $kontribusiProdi;
    private $bidangIlmu;
    private $klasterBantuan;
    private $temaPenelitian;

    public function __construct()
    {
        $this->pengelolaBantuan = new PengelolaBantuan();
        $this->jenisPenelitian = new JenisPenelitian();
        $this->kontribusiProdi = new KontribusiProdi();
        $this->bidangIlmu = new BidangIlmu();
        $this->klasterBantuan = new KlasterBantuan();
        $this->temaPenelitian = new TemaPenelitian();
    }

    /**
     * Get all master options untuk form dropdown
     * Format: array with keys 'pengelola_bantuan', 'jenis_penelitian', etc
     * 
     * @return array Nested array of all master options
     */
    public function getAllOptions(): array
    {
        return [
            'pengelola_bantuan' => $this->formatOptions($this->pengelolaBantuan->getActive()),
            'jenis_penelitian' => $this->formatOptions($this->jenisPenelitian->getActive()),
            'kontribusi_prodi' => $this->formatOptions($this->kontribusiProdi->getActive()),
            'bidang_ilmu' => $this->formatOptions($this->bidangIlmu->getActive()),
            'klaster_bantuan' => $this->formatOptions($this->klasterBantuan->getActive()),
            'tema_penelitian' => $this->formatOptions($this->temaPenelitian->getActive()),
        ];
    }

    /**
     * Get pengelola bantuan options
     * 
     * @return array
     */
    public function getPengelolaBantuan(): array
    {
        return $this->formatOptions($this->pengelolaBantuan->getActive());
    }

    /**
     * Get jenis penelitian options
     * 
     * @return array
     */
    public function getJenisPenelitian(): array
    {
        return $this->formatOptions($this->jenisPenelitian->getActive());
    }

    /**
     * Get kontribusi prodi options
     * 
     * @return array
     */
    public function getKontribusiProdi(): array
    {
        return $this->formatOptions($this->kontribusiProdi->getActive());
    }

    /**
     * Get bidang ilmu options
     * 
     * @return array
     */
    public function getBidangIlmu(): array
    {
        return $this->formatOptions($this->bidangIlmu->getActive());
    }

    /**
     * Get klaster bantuan options
     * 
     * @return array
     */
    public function getKlasterBantuan(): array
    {
        return $this->formatOptions($this->klasterBantuan->getActive());
    }

    /**
     * Get tema penelitian options
     * 
     * @return array
     */
    public function getTemaPenelitian(): array
    {
        return $this->formatOptions($this->temaPenelitian->getActive());
    }

    /**
     * Format master data untuk dropdown option
     * Setiap item diberi kunci 'id', 'nama', 'keterangan'
     * 
     * @param array|null $data
     * @return array
     */
    private function formatOptions($data): array
    {
        if (!$data || count($data) === 0) {
            return [];
        }

        return array_map(function ($item) {
            return [
                'id'          => $item->id ?? $item->uuid,
                'uuid'        => $item->uuid,
                'nama'        => $item->nama,
                'keterangan'  => $item->keterangan ?? '',
                'is_active'   => $item->is_active,
            ];
        }, $data);
    }

    /**
     * Get single option by id
     * 
     * @param string $type Type: pengelola_bantuan, jenis_penelitian, etc
     * @param int $id
     * @return object|null
     */
    public function getOptionById(string $type, int $id)
    {
        switch ($type) {
            case 'pengelola_bantuan':
                return $this->pengelolaBantuan->find($id);
            case 'jenis_penelitian':
                return $this->jenisPenelitian->find($id);
            case 'kontribusi_prodi':
                return $this->kontribusiProdi->find($id);
            case 'bidang_ilmu':
                return $this->bidangIlmu->find($id);
            case 'klaster_bantuan':
                return $this->klasterBantuan->find($id);
            case 'tema_penelitian':
                return $this->temaPenelitian->find($id);
            default:
                return null;
        }
    }

    /**
     * Get nama/label for dropdown option
     * Useful untuk display selected option value
     * 
     * @param string $type Type: pengelola_bantuan, jenis_penelitian, etc
     * @param int $id
     * @return string
     */
    public function getOptionNama(string $type, int $id): string
    {
        $option = $this->getOptionById($type, $id);
        return $option ? $option->nama : '';
    }
}
