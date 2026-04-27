<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalPeneliti extends Model
{
    protected $table            = 'proposal_peneliti';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid',
        'proposal_id',
        'nama',
        'nip',
        'email',
        'asal_instansi',
        'posisi',
        'is_internal',
        'is_ketua',
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
    ];

    protected $validationMessages = [
        'proposal_id' => [
            'required' => 'Proposal ID wajib diisi',
            'integer' => 'Proposal ID harus berupa angka',
        ],
        'nama' => [
            'required'   => 'Nama peneliti wajib diisi',
            'max_length' => 'Nama peneliti maksimal 255 karakter',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all researchers for a proposal
     */
    public function getByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->orderBy('order_position', 'ASC')
            ->findAll();
    }

    /**
     * Get lead researcher for a proposal
     */
    public function getLeadByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->where('is_ketua', 1)
            ->first();
    }

    /**
     * Get internal researchers for a proposal
     */
    public function getInternalByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->where('is_internal', 1)
            ->orderBy('order_position', 'ASC')
            ->findAll();
    }

    /**
     * Get external researchers for a proposal
     */
    public function getExternalByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->where('is_internal', 0)
            ->orderBy('order_position', 'ASC')
            ->findAll();
    }
}
