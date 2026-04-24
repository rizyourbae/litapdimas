<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Ramsey\Uuid\Uuid;

class AddDosenReviewerRoles extends Migration
{
    public function up()
    {
        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        // --------------------------------------------------------
        // 1. Pastikan semua permission yang dibutuhkan ada
        // --------------------------------------------------------
        $requiredPerms = [
            'dashboard.access' => 'Akses dashboard',
            'dosen.access'     => 'Akses panel dosen',
            'profile.manage'   => 'Kelola profil sendiri',
            'reviewer.access'  => 'Akses panel reviewer',
            'reviews.manage'   => 'Mengelola review proposal',
        ];

        foreach ($requiredPerms as $name => $desc) {
            if (!$db->table('permissions')->where('name', $name)->countAllResults()) {
                $db->table('permissions')->insert([
                    'uuid'        => Uuid::uuid4()->toString(),
                    'name'        => $name,
                    'description' => $desc,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }

        // Load semua permission ID yang baru saja diinsert/sudah ada
        $rows    = $db->table('permissions')->whereIn('name', array_keys($requiredPerms))->get()->getResultArray();
        $permMap = array_column($rows, 'id', 'name');

        // --------------------------------------------------------
        // 2. Tambah dashboard.access ke role admin (jika belum)
        // --------------------------------------------------------
        $adminRole = $db->table('roles')->where('name', 'admin')->get()->getRowArray();
        if ($adminRole) {
            $this->assignPermissions($db, $adminRole['id'], [$permMap['dashboard.access']]);
        }

        // --------------------------------------------------------
        // 3. Buat role dosen & assign permissions
        // --------------------------------------------------------
        if (!$db->table('roles')->where('name', 'dosen')->countAllResults()) {
            $db->table('roles')->insert([
                'uuid'        => Uuid::uuid4()->toString(),
                'name'        => 'dosen',
                'description' => 'Dosen / Lecturer',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
            $dosenId = $db->insertID();
        } else {
            $dosenId = $db->table('roles')->where('name', 'dosen')->get()->getRowArray()['id'];
        }

        $this->assignPermissions($db, $dosenId, [
            $permMap['dashboard.access'],
            $permMap['dosen.access'],
            $permMap['profile.manage'],
        ]);

        // --------------------------------------------------------
        // 4. Buat role reviewer & assign permissions
        // --------------------------------------------------------
        if (!$db->table('roles')->where('name', 'reviewer')->countAllResults()) {
            $db->table('roles')->insert([
                'uuid'        => Uuid::uuid4()->toString(),
                'name'        => 'reviewer',
                'description' => 'Reviewer Proposal',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
            $reviewerId = $db->insertID();
        } else {
            $reviewerId = $db->table('roles')->where('name', 'reviewer')->get()->getRowArray()['id'];
        }

        $this->assignPermissions($db, $reviewerId, [
            $permMap['dashboard.access'],
            $permMap['reviewer.access'],
            $permMap['reviews.manage'],
            $permMap['profile.manage'],
        ]);
    }

    public function down()
    {
        $db = \Config\Database::connect();

        // Hapus role dosen & reviewer beserta relasi user_roles & role_permissions
        foreach (['dosen', 'reviewer'] as $roleName) {
            $role = $db->table('roles')->where('name', $roleName)->get()->getRowArray();
            if ($role) {
                $db->table('role_permissions')->where('role_id', $role['id'])->delete();
                $db->table('user_roles')->where('role_id', $role['id'])->delete();
                $db->table('roles')->where('id', $role['id'])->delete();
            }
        }

        // Hapus permissions baru
        $permNames = ['dashboard.access', 'dosen.access', 'profile.manage', 'reviewer.access', 'reviews.manage'];
        foreach ($permNames as $name) {
            $perm = $db->table('permissions')->where('name', $name)->get()->getRowArray();
            if ($perm) {
                $db->table('role_permissions')->where('permission_id', $perm['id'])->delete();
                $db->table('permissions')->where('id', $perm['id'])->delete();
            }
        }
    }

    // --------------------------------------------------------
    // Helper: assign permission ke role (idempotent)
    // --------------------------------------------------------
    private function assignPermissions($db, int $roleId, array $permIds): void
    {
        foreach ($permIds as $permId) {
            $exists = $db->table('role_permissions')
                ->where('role_id', $roleId)
                ->where('permission_id', $permId)
                ->countAllResults();
            if (!$exists) {
                $db->table('role_permissions')->insert([
                    'role_id'       => $roleId,
                    'permission_id' => $permId,
                ]);
            }
        }
    }
}
