<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalJurnal extends Model
{
    protected $table            = 'proposal_jurnal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid',
        'proposal_id',
        'issn',
        'nama_jurnal',
        'profil_jurnal',
        'url_website',
        'url_scopus_wos',
        'url_surat_rekomendasi',
        'total_pengajuan_dana',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'proposal_id'           => 'required|integer',
        'issn'                  => 'required|max_length[50]',
        'total_pengajuan_dana'  => 'permit_empty|integer|less_than_equal_to[100000000]',
    ];

    protected $validationMessages = [
        'proposal_id' => [
            'required' => 'Proposal ID wajib diisi',
            'integer' => 'Proposal ID harus berupa angka',
        ],
        'issn' => [
            'required'   => 'ISSN jurnal wajib diisi',
            'max_length' => 'ISSN maksimal 50 karakter',
        ],
        'total_pengajuan_dana' => [
            'integer' => 'Dana harus berupa angka',
            'less_than_equal_to' => 'Dana maksimal Rp 100.000.000',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get journal data for a proposal
     */
    public function getByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)->first();
    }

    /**
     * Check if journal exists for a proposal
     */
    public function existsByProposal(int $proposalId)
    {
        return $this->where('proposal_id', $proposalId)->first() !== null;
    }

    /**
     * Get formatted dana amount
     */
    public function getFormattedDana(int $proposalId)
    {
        $journal = $this->getByProposal($proposalId);
        if ($journal && $journal->total_pengajuan_dana) {
            return number_format($journal->total_pengajuan_dana, 0, ',', '.');
        }
        return '0';
    }
}
