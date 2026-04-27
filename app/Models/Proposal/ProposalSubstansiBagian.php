<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalSubstansiBagian extends Model
{
    protected $table            = 'proposal_substansi_bagian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid',
        'proposal_id',
        'abstrak',
        'judul_bagian',
        'isi_bagian',
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
        'judul_bagian'  => 'required|max_length[255]',
        'isi_bagian'    => 'required',
    ];

    protected $validationMessages = [
        'proposal_id' => [
            'required' => 'Proposal ID wajib diisi',
            'integer' => 'Proposal ID harus berupa angka',
        ],
        'judul_bagian' => [
            'required'   => 'Judul bagian wajib diisi',
            'max_length' => 'Judul bagian maksimal 255 karakter',
        ],
        'isi_bagian' => [
            'required' => 'Isi bagian wajib diisi',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all substance sections for a proposal
     */
    public function getByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->orderBy('order_position', 'ASC')
            ->findAll();
    }

    /**
     * Get abstrak for a proposal
     */
    public function getAbstrakByProposal(int $proposalId)
    {
        $result = $this->where('proposal_id', $proposalId)
            ->first();
        return $result ? $result->abstrak : null;
    }

    /**
     * Count sections (excluding abstrak) for a proposal
     */
    public function countSectionsByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->where('judul_bagian !=', '')
            ->countAllResults();
    }
}
