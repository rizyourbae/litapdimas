<?php

namespace App\Models\User;

use App\Models\BaseModel;
use App\Models\Traits\SoftDeleteTrait;
use App\Models\Traits\HasUserstampsTrait;

class UserProfileModel extends BaseModel
{
    use SoftDeleteTrait;
    use HasUserstampsTrait;

    protected $table      = 'user_profiles';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'uuid',
        'user_id',
        'foto',
        'gelar_depan',
        'gelar_belakang',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'nik',
        'nidn',
        'nip',
        'profesi_id',
        'bidang_ilmu_id',
        'fakultas_id',
        'program_studi_id',
        'jabatan_fungsional_id',
        'id_sinta',
        'created_by',
        'updated_by'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\UserModel', 'user_id', 'id');
    }
}
