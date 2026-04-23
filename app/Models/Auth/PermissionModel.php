<?php

namespace App\Models\Auth;

use App\Models\Traits\HasUuidTrait;
use App\Models\BaseModel;

class PermissionModel extends BaseModel
{
    use HasUuidTrait;

    protected $table      = 'permissions';
    protected $allowedFields = ['uuid', 'name', 'description'];
}
