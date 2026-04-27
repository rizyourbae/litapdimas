<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class KontribusiProdi extends Model
{
    protected $table            = 'proposal_kontribusi_prodi';
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
            'required'   => 'Nama kontribusi prodi wajib diisi',
            'max_length' => 'Nama kontribusi prodi maksimal 255 karakter',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get active kontribusi prodi
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->findAll();
    }

    /**
     * Get all kontribusi prodi with soft delete excluded
     */
    public function getAllWithoutDeleted()
    {
        return $this->where('is_active', 1)->findAll();
    }
}
