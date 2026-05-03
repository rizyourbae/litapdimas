<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalOutput extends Model
{
    protected $table = 'proposal_outputs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'uuid',
        'proposal_id',
        'kategori',
        'file_path',
        'original_filename'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getByProposal(int $proposalId): array
    {
        return $this->where('proposal_id', $proposalId)->findAll();
    }

    public function getByProposalAndKategori(int $proposalId, string $kategori): ?object
    {
        return $this->where('proposal_id', $proposalId)
            ->where('kategori', $kategori)
            ->first();
    }
}
