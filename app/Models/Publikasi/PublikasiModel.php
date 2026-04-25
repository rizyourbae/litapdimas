<?php

namespace App\Models\Publikasi;

use App\Models\BaseModel;
use App\Models\Traits\HasUuidTrait;

class PublikasiModel extends BaseModel
{
    use HasUuidTrait;

    protected $table            = 'publikasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'uuid',
        'user_id',
        'judul',
        'penulis',
        'klaster',
        'jenis_publikasi',
        'tahun',
        'sumber_pembiayaan',
        'metadata'
    ];

    protected $useTimestamps = true;

    // Validation Rules yang ketat (Security)
    protected $validationRules = [
        'user_id'         => 'required|is_not_unique[users.id]',
        'judul'           => 'required|min_length[5]',
        'penulis'         => 'permit_empty|max_length[255]',
        'klaster'         => 'permit_empty|max_length[100]',
        'jenis_publikasi' => 'required|in_list[Jurnal,HKI,Prosiding,Buku]',
        'tahun'           => 'required|exact_length[4]|numeric',
    ];

    protected $validationMessages = [
        'jenis_publikasi' => [
            'in_list' => 'Jenis publikasi tidak valid.'
        ]
    ];
}
