<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

abstract class BaseModel extends Model
{
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';
    protected $returnType    = 'array';
    protected $protectFields = true;

    // Konfigurasi default
    protected $beforeInsert = [];
    protected $beforeUpdate = [];

    /**
     * Override insert untuk otomatis mengisi UUID jika kolom 'uuid' ada di allowedFields.
     */
    public function insert($data = null, bool $returnID = true)
    {
        // Konversi ke array jika belum
        if (is_object($data)) {
            $data = (array) $data;
        }

        // Jika kolom 'uuid' ada di allowedFields dan data belum punya UUID
        if (in_array('uuid', $this->allowedFields) && empty($data['uuid'])) {
            $data['uuid'] = Uuid::uuid4()->toString();
        }

        return parent::insert($data, $returnID);
    }
}
