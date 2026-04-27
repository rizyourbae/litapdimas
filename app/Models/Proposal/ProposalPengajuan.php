<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class ProposalPengajuan extends Model
{
    protected $table            = 'proposal_pengajuan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid',
        'user_id',
        'judul',
        'kata_kunci',
        'pengelola_bantuan_id',
        'klaster_bantuan_id',
        'bidang_ilmu_id',
        'tema_penelitian_id',
        'jenis_penelitian_id',
        'kontribusi_prodi_id',
        'status',
        'current_step',
        'step_1_data',
        'step_2_data',
        'step_3_data',
        'step_4_data',
        'step_5_data',
        'created_by',
        'updated_by',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'judul'   => 'permit_empty|max_length[255]',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID wajib diisi',
            'integer' => 'User ID harus berupa angka',
        ],
        'judul' => [
            'max_length' => 'Judul proposal maksimal 255 karakter',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Find proposal by user and uuid
     */
    public function findByUserAndUuid(int $userId, string $uuid)
    {
        return $this->where('user_id', $userId)
            ->where('uuid', $uuid)
            ->first();
    }

    /**
     * Find draft proposal by user and step
     */
    public function findDraftByUserAndStep(int $userId, int $step)
    {
        return $this->where('user_id', $userId)
            ->where('current_step', $step)
            ->where('status', 'draft')
            ->first();
    }

    /**
     * Get all proposals by user
     */
    public function getByUser(int $userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('updated_at', 'DESC')
            ->findAll();
    }

    /**
     * Get draft proposals by user
     */
    public function getDraftByUser(int $userId)
    {
        return $this->where('user_id', $userId)
            ->where('status', 'draft')
            ->orderBy('updated_at', 'DESC')
            ->findAll();
    }

    /**
     * Get submitted proposals by user
     */
    public function getSubmittedByUser(int $userId)
    {
        return $this->where('user_id', $userId)
            ->where('status', 'submitted')
            ->orderBy('updated_at', 'DESC')
            ->findAll();
    }
}
