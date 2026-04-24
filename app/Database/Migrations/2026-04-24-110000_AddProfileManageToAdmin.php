<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileManageToAdmin extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Ambil permission profile.manage
        $perm = $db->table('permissions')->where('name', 'profile.manage')->get()->getRowArray();
        if (!$perm) {
            return;
        }

        // Ambil role admin
        $admin = $db->table('roles')->where('name', 'admin')->get()->getRowArray();
        if (!$admin) {
            return;
        }

        // Assign jika belum ada
        $exists = $db->table('role_permissions')
            ->where('role_id', $admin['id'])
            ->where('permission_id', $perm['id'])
            ->countAllResults();

        if (!$exists) {
            $db->table('role_permissions')->insert([
                'role_id'       => $admin['id'],
                'permission_id' => $perm['id'],
            ]);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        $perm  = $db->table('permissions')->where('name', 'profile.manage')->get()->getRowArray();
        $admin = $db->table('roles')->where('name', 'admin')->get()->getRowArray();

        if ($perm && $admin) {
            $db->table('role_permissions')
                ->where('role_id', $admin['id'])
                ->where('permission_id', $perm['id'])
                ->delete();
        }
    }
}
