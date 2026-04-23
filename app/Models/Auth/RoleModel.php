<?php

namespace App\Models\Auth;

use App\Models\Traits\HasUuidTrait;
use App\Models\BaseModel;

class RoleModel extends BaseModel
{
    use HasUuidTrait;

    protected $table      = 'roles';
    protected $allowedFields = ['uuid', 'name', 'description'];
}
