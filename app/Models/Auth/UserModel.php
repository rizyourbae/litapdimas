<?php

namespace App\Models\Auth;

use App\Models\Traits\HasUuidTrait;
use App\Models\Traits\SoftDeleteTrait;
use App\Models\Traits\HasUserstampsTrait;
use App\Models\Traits\HasUserQueries;
use App\Models\BaseModel;

class UserModel extends BaseModel
{
    use HasUuidTrait;
    use SoftDeleteTrait;
    use HasUserstampsTrait;
    use HasUserQueries;

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
        // Allow empty password for updates; enforce length when provided
        'password' => 'permit_empty|min_length[6]',
        // Required for placeholder substitution in is_unique rules
        'id'       => 'permit_empty',
    ];
}
