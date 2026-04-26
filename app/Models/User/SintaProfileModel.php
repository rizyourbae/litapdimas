<?php

namespace App\Models\User;

use App\Models\BaseModel;

class SintaProfileModel extends BaseModel
{
    protected $table            = 'sinta_profiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'uuid',
        'user_id',
        'id_sinta',
        'nama_sinta',
        'sinta_score_all_years',
        'sinta_score_3_years',
        'sinta_profile_url',
        'status_validasi_sinta',
        'sync_status',
        'sync_error_message',
        'raw_payload_json',
        'last_synced_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'user_id'               => 'required|integer',
        'id_sinta'              => 'required|alpha_numeric_punct|max_length[30]',
        'nama_sinta'            => 'permit_empty|string|max_length[200]',
        'sinta_score_all_years' => 'permit_empty|decimal',
        'sinta_score_3_years'   => 'permit_empty|decimal',
        'sinta_profile_url'     => 'permit_empty|valid_url|max_length[255]',
        'status_validasi_sinta' => 'permit_empty|string|max_length[50]',
        'sync_status'           => 'permit_empty|in_list[never,success,failed,partial]',
        'sync_error_message'    => 'permit_empty|string',
        'raw_payload_json'      => 'permit_empty|string',
    ];

    protected $validationMessages = [
        'id_sinta' => [
            'required'           => 'ID SINTA wajib diisi.',
            'alpha_numeric_punct' => 'Format ID SINTA tidak valid.',
            'max_length'         => 'ID SINTA maksimal 30 karakter.',
        ],
        'sinta_profile_url' => [
            'valid_url' => 'URL profil SINTA tidak valid.',
        ],
    ];
}
