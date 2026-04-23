<?php

namespace App\Models\Auth;

use App\Models\Traits\HasUuidTrait;
use App\Models\Traits\SoftDeleteTrait;
use App\Models\Traits\HasUserstampsTrait;
use App\Models\BaseModel;

class UserModel extends BaseModel
{
    use HasUuidTrait;
    use SoftDeleteTrait;
    use HasUserstampsTrait;

    protected $table      = 'users';
    protected $allowedFields = [
        'uuid',
        'username',
        'email',
        'password',
        'nama_lengkap',
        'aktif',
        'created_by',
        'updated_by',
    ];

    protected $validationRules = [
        'username' => 'required|min_length[3]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
    ];
}
