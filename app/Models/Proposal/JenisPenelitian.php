<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class JenisPenelitian extends Model
{
    protected $table            = 'proposal_jenis_penelitian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid',
        'nama',
        'keterangan',
        'is_active',
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
        'nama' => 'required|max_length[255]',
    ];

    protected $validationMessages = [
        'nama' => [
            'required'   => 'Nama jenis penelitian wajib diisi',
            'max_length' => 'Nama jenis penelitian maksimal 255 karakter',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get active jenis penelitian
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->findAll();
    }

    /**
     * Get all jenis penelitian with soft delete excluded
     */
    public function getAllWithoutDeleted()
    {
        return $this->where('is_active', 1)->findAll();
    }
}
