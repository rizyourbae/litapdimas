<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalMahasiswa extends Model
{
    protected $table            = 'proposal_mahasiswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid',
        'proposal_id',
        'nama',
        'nim',
        'program_studi_id',
        'email',
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
            'required'   => 'Nama mahasiswa wajib diisi',
            'max_length' => 'Nama mahasiswa maksimal 255 karakter',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all mahasiswa for a proposal
     */
    public function getByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->orderBy('order_position', 'ASC')
            ->findAll();
    }

    /**
     * Count mahasiswa for a proposal
     */
    public function countByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->countAllResults();
    }
}
