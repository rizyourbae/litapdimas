<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalAnggotaEksternal extends Model
{
    protected $table            = 'proposal_anggota_eksternal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid',
        'proposal_id',
        'nama',
        'institusi',
        'posisi',
        'email',
        'tipe',
        'order_position',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'proposal_id' => 'required|integer',
        'nama'        => 'required|max_length[255]',
        'tipe'        => 'required|in_list[PTU,Profesional,Other]',
    ];

    protected $validationMessages = [
        'proposal_id' => [
            'required' => 'Proposal ID wajib diisi',
            'integer' => 'Proposal ID harus berupa angka',
        ],
        'nama' => [
            'required'   => 'Nama anggota wajib diisi',
            'max_length' => 'Nama anggota maksimal 255 karakter',
        ],
        'tipe' => [
            'required' => 'Tipe anggota wajib diisi',
            'in_list' => 'Tipe anggota harus PTU, Profesional, atau Other',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all external members for a proposal
     */
    public function getByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->orderBy('order_position', 'ASC')
            ->findAll();
    }

    /**
     * Get by type
     */
    public function getByProposalAndType(int $proposalId, string $type)
    {
        return $this->where('proposal_id', $proposalId)
            ->where('tipe', $type)
            ->orderBy('order_position', 'ASC')
            ->findAll();
    }

    /**
     * Count by proposal and type
     */
    public function countByProposalAndType(int $proposalId, string $type)
    {
        return $this->where('proposal_id', $proposalId)
            ->where('tipe', $type)
            ->countAllResults();
    }
}
