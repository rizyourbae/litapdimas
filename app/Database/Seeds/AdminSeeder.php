<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Auth\UserModel;
use App\Models\Auth\RoleModel;
use App\Models\Auth\PermissionModel;
use App\Models\Auth\UserRoleModel;
use App\Models\Auth\RolePermissionModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();
        $roleModel = new RoleModel();
        $permModel = new PermissionModel();
        $userRoleModel = new UserRoleModel();
        $rolePermModel = new RolePermissionModel();

        // Nonaktifkan validasi untuk seeder
        $userModel->skipValidation(true);
        $roleModel->skipValidation(true);
        $permModel->skipValidation(true);

        // --- Permissions ---
        $permissions = [
            ['name' => 'admin.access', 'description' => 'Akses panel admin'],
            ['name' => 'users.manage', 'description' => 'Manajemen user'],
            ['name' => 'proposals.view', 'description' => 'Lihat proposal'],
        ];
        foreach ($permissions as $p) {
            $permModel->insert($p);
        }

        // --- Role Admin ---
        $roleId = $roleModel->insert(['name' => 'admin', 'description' => 'Administrator'], true);
        $allPerms = $permModel->findAll();
        foreach ($allPerms as $perm) {
            $rolePermModel->insert(['role_id' => $roleId, 'permission_id' => $perm['id']]);
        }

        // --- User Admin ---
        $userId = $userModel->insert([
            'username'     => 'admin',
            'email'        => 'admin@litapdimas.ac.id',
            'password'     => password_hash('admin123', PASSWORD_BCRYPT),
            'nama_lengkap' => 'Administrator',
            'aktif'        => 1,
        ], true);

        $userRoleModel->insert(['user_id' => $userId, 'role_id' => $roleId]);
    }
}
