<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalOutcome extends Model
{
    protected $table = 'proposal_outcomes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'uuid',
        'proposal_id',
        'tipe',
        'judul',
        'nama_penerbit_jurnal',
        'volume_nomor',
        'file_path',
        'url',
        'isbn',
        'tahun_terbit'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getByProposal(int $proposalId): array
    {
        return $this->where('proposal_id', $proposalId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
