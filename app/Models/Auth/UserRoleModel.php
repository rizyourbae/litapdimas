<?php

namespace App\Models\Auth;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table = 'user_roles';
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['user_id', 'role_id', 'created_at'];
    protected $useTimestamps = false;
}
