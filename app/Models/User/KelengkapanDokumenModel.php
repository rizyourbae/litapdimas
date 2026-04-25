<?php

namespace App\Models\User;

use App\Models\BaseModel;

class KelengkapanDokumenModel extends BaseModel
{
    protected $table            = 'kelengkapan_dokumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'uuid',
        'user_id',
        'jenis_dokumen',
        'dokumen_file',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'user_id'       => 'required|integer',
        'jenis_dokumen' => 'required|string|max_length[100]',
        'dokumen_file'  => 'permit_empty|string|max_length[500]',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required'  => 'User harus dipilih.',
            'integer'   => 'User harus berupa angka.',
        ],
        'jenis_dokumen' => [
            'required'   => 'Jenis dokumen harus diisi.',
            'string'     => 'Jenis dokumen harus berupa teks.',
            'max_length' => 'Jenis dokumen maksimal 100 karakter.',
        ],
        'dokumen_file' => [
            'string'     => 'File dokumen harus berupa teks.',
            'max_length' => 'Path file maksimal 500 karakter.',
        ],
    ];
}
