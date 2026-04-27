<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CreateTestDosen extends Seeder
{
    public function run()
    {
        // Delete existing test users first
        $testUsernames = ['dosen1', 'dosen2'];
        $existingUsers = $this->db->table('users')
            ->whereIn('username', $testUsernames)
            ->select('id')
            ->get()
            ->getResultArray();

        if (!empty($existingUsers)) {
            $userIds = array_column($existingUsers, 'id');
            $this->db->table('user_roles')->whereIn('user_id', $userIds)->delete();
            $this->db->table('users')->whereIn('username', $testUsernames)->delete();
            echo "✓ Existing test users deleted\n";
        }

        $password = password_hash('password123', PASSWORD_BCRYPT);

        $userData = [
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655550001',
                'username' => 'dosen1',
                'email' => 'dosen1@example.com',
                'password' => $password,
                'nama_lengkap' => 'Dr. Ahmad Wijaya',
                'aktif' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'uuid' => '550e8400-e29b-41d4-a716-446655550002',
                'username' => 'dosen2',
                'email' => 'dosen2@example.com',
                'password' => $password,
                'nama_lengkap' => 'Prof. Siti Nurhaliza',
                'aktif' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        // Insert users
        $this->db->table('users')->insertBatch($userData);
        echo "✓ Test dosen users created successfully!\n";

        // Get the 'dosen' role ID
        $dosenRole = $this->db->table('roles')->where('name', 'dosen')->get()->getRow();
        if (!$dosenRole) {
            echo "✗ ERROR: 'dosen' role not found. Run migrations first!\n";
            return;
        }

        // Get the user IDs that were just inserted
        $users = $this->db->table('users')
            ->whereIn('username', ['dosen1', 'dosen2'])
            ->select('id')
            ->get()
            ->getResultArray();

        // Assign dosen role to both users
        $roleAssignments = [];
        foreach ($users as $user) {
            $roleAssignments[] = [
                'user_id' => $user['id'],
                'role_id' => $dosenRole->id,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('user_roles')->insertBatch($roleAssignments);
        echo "✓ Dosen role assigned to test users!\n";
    }
}
