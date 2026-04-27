<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalDokumen extends Model
{
    protected $table            = 'proposal_dokumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid',
        'proposal_id',
        'tipe_dokumen',
        'nama_file',
        'path_file',
        'file_size',
        'mime_type',
        'keterangan',
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
        'proposal_id'   => 'required|integer',
        'tipe_dokumen'  => 'required|in_list[proposal,rab,similarity,pendukung]',
        'nama_file'     => 'required|max_length[255]',
        'path_file'     => 'required',
        'mime_type'     => 'required|max_length[100]',
    ];

    protected $validationMessages = [
        'proposal_id' => [
            'required' => 'Proposal ID wajib diisi',
            'integer' => 'Proposal ID harus berupa angka',
        ],
        'tipe_dokumen' => [
            'required' => 'Tipe dokumen wajib diisi',
            'in_list' => 'Tipe dokumen harus proposal, rab, similarity, atau pendukung',
        ],
        'nama_file' => [
            'required'   => 'Nama file wajib diisi',
            'max_length' => 'Nama file maksimal 255 karakter',
        ],
        'path_file' => [
            'required' => 'Path file wajib diisi',
        ],
        'mime_type' => [
            'required'   => 'MIME type wajib diisi',
            'max_length' => 'MIME type maksimal 100 karakter',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all documents for a proposal
     */
    public function getByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->orderBy('tipe_dokumen', 'ASC')
            ->orderBy('order_position', 'ASC')
            ->findAll();
    }

    /**
     * Get document by type
     */
    public function getByProposalAndType(int $proposalId, string $type)
    {
        return $this->where('proposal_id', $proposalId)
            ->where('tipe_dokumen', $type)
            ->orderBy('order_position', 'ASC')
            ->findAll();
    }

    /**
     * Get required documents (proposal, rab, similarity)
     */
    public function getRequiredByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)
            ->whereIn('tipe_dokumen', ['proposal', 'rab', 'similarity'])
            ->findAll();
    }

    /**
     * Check if all required documents exist
     */
    public function hasAllRequiredByProposal(int $proposalId)
    {
        $requiredTypes = ['proposal', 'rab', 'similarity'];
        foreach ($requiredTypes as $type) {
            $exists = $this->where('proposal_id', $proposalId)
                ->where('tipe_dokumen', $type)
                ->first();
            if (!$exists) {
                return false;
            }
        }
        return true;
    }

    /**
     * Count documents by type
     */
    public function countByProposalAndType(int $proposalId, string $type)
    {
        return $this->where('proposal_id', $proposalId)
            ->where('tipe_dokumen', $type)
            ->countAllResults();
    }
}
