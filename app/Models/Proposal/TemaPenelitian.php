<?php

namespace App\Models\Proposal;

use CodeIgniter\Model;

class TemaPenelitian extends Model
{
    protected $table            = 'tema_penelitian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid',
        'nama',
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
        'nama' => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama tema penelitian wajib diisi.',
            'min_length' => 'Nama tema penelitian minimal 3 karakter.',
        ],
    ];

    // Events
    protected $beforeInsert = ['generateUuid'];
    protected $beforeUpdate = ['setUpdatedBy'];

    /**
     * Generate UUID sebelum insert
     */
    protected function generateUuid(array $data)
    {
        if (empty($data['data']['uuid'])) {
            $data['data']['uuid'] = $this->generateUuidString();
        }
        return $data;
    }

    /**
     * Set updated_by sebelum update
     */
    protected function setUpdatedBy(array $data)
    {
        $auth = service('auth');
        if ($auth && isset($data['data'])) {
            $data['data']['updated_by'] = $auth->userId();
        }
        return $data;
    }

    /**
     * Helper generate UUID string
     */
    private function generateUuidString(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * Get all active tema penelitian
     */
    public function getActive()
    {
        return $this->orderBy('nama', 'ASC')->findAll();
    }

    /**
     * Backward-compatible alias.
     */
    public function getActiveTema()
    {
        return $this->getActive();
    }

    /**
     * Find by UUID
     */
    public function findByUuid(string $uuid): ?object
    {
        return $this->where('uuid', $uuid)->first();
    }
}
