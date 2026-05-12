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
            ['name' => 'master.manage', 'description' => 'Mengelola Data Master'],
            ['name' => 'dashboard.access', 'description' => 'Akses dashboard'],
            ['name' => 'profile.manage', 'description' => 'Kelola profil sendiri'],
            ['name' => 'reviews.manage', 'description' => 'Mengelola review proposal'],
        ];
        foreach ($permissions as $p) {
            // Check if permission already exists to avoid duplicate errors
            if (!$permModel->where('name', $p['name'])->first()) {
                $permModel->insert($p);
            }
        }

        // --- Role Admin ---
        $adminRole = $roleModel->where('name', 'admin')->first();
        if (!$adminRole) {
            $roleId = $roleModel->insert(['name' => 'admin', 'description' => 'Administrator'], true);
        } else {
            $roleId = $adminRole['id'];
        }
        
        $allPerms = $permModel->findAll();
        // Link permissions to role (check if not already linked)
        foreach ($allPerms as $perm) {
            $existing = $rolePermModel->where(['role_id' => $roleId, 'permission_id' => $perm['id']])->first();
            if (!$existing) {
                $rolePermModel->insert(['role_id' => $roleId, 'permission_id' => $perm['id']]);
            }
        }

        // --- User Admin ---
        $adminUser = $userModel->where('email', 'admin.smart@uinsi.ac.id')->first();
        if (!$adminUser) {
            $userId = $userModel->insert([
                'username'     => 'admin',
                'email'        => 'admin.smart@uinsi.ac.id',
                'password'     => password_hash('Litap@admin123', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Administrator',
                'aktif'        => 1,
            ], true);
        } else {
            $userId = $adminUser['id'];
        }

        // Link admin role to admin user (check if not already linked)
        $existing = $userRoleModel->where(['user_id' => $userId, 'role_id' => $roleId])->first();
        if (!$existing) {
            $userRoleModel->insert(['user_id' => $userId, 'role_id' => $roleId]);
        }
    }
}
