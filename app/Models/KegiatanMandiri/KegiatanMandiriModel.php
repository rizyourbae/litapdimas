<?php

namespace App\Models\KegiatanMandiri;

use App\Models\BaseModel;
use App\Models\Traits\HasUuidTrait;

class KegiatanMandiriModel extends BaseModel
{
    use HasUuidTrait;

    protected $table            = 'kegiatan_mandiri';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'uuid',
        'user_id',
        'tahun',
        'jenis_kegiatan',
        'klaster_skala_kegiatan',
        'judul_kegiatan',
        'anggota_terlibat',
        'resume_kegiatan',
        'unit_pelaksana_kegiatan',
        'mitra_kolaborasi',
        'sumber_dana',
        'besaran_dana',
        'tautan_bukti_dukung',
    ];

    protected $validationRules = [
        'user_id'                => 'required|is_not_unique[users.id]',
        'tahun'                  => 'required|exact_length[4]|numeric',
        'jenis_kegiatan'         => 'required|max_length[100]',
        'klaster_skala_kegiatan' => 'required|max_length[100]',
        'judul_kegiatan'         => 'required|min_length[5]|max_length[255]',
        'besaran_dana'           => 'required|numeric',
        'tautan_bukti_dukung'    => 'required|valid_url',
    ];
}
