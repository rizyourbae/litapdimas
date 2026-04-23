<?php

namespace App\Models\Auth;

use CodeIgniter\Model;

class RolePermissionModel extends Model
{
    protected $table      = 'role_permissions';
    protected $primaryKey = ['role_id', 'permission_id'];
    protected $allowedFields = ['role_id', 'permission_id'];
    protected $useTimestamps = false;
}
