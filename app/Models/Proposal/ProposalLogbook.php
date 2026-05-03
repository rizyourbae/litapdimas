<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalLogbook extends Model
{
    protected $table = 'proposal_logbooks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'uuid',
        'proposal_id',
        'tanggal',
        'tempat',
        'nama_kegiatan',
        'teknik',
        'deskripsi_kegiatan',
        'berkas_path'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->orderBy('tanggal', 'DESC')
            ->findAll();
    }
}
