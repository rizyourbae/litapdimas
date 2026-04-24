<?php

namespace App\Services\User;

use App\Interfaces\User\UserServiceInterface;
use App\Models\Auth\RoleModel;
use App\Models\Auth\UserModel;
use App\Models\Auth\UserRoleModel;
use App\Models\User\UserProfileModel;
use App\Models\Master\ProfesiModel;
use App\Models\Master\BidangIlmuModel;
use App\Models\Master\FakultasModel;
use App\Models\Master\ProgramStudiModel;
use App\Models\Master\JabatanFungsionalModel;
use Config\Database;

class UserService implements UserServiceInterface
{
    protected $db;
    protected $userModel;
    protected $roleModel;
    protected $userRoleModel;
    protected $userProfileModel;
    protected $lastError;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->userRoleModel = new UserRoleModel();
        $this->userProfileModel = new UserProfileModel();
    }

    /**
     * Return last database error info (if any)
     * @return array|null
     */
    public function getLastError(): ?array
    {
        return $this->lastError ?? null;
    }

    // Mendapatkan semua user dengan role dan profil (join)
    public function getUsersWithRoles(array $filters = []): array
    {
        $builder = $this->userModel->select('users.*, GROUP_CONCAT(roles.name SEPARATOR ", ") as role_names')
            ->join('user_roles', 'user_roles.user_id = users.id', 'left')
            ->join('roles', 'roles.id = user_roles.role_id', 'left')
            ->groupBy('users.id');

        // Filter: role
        if (!empty($filters['role_id'])) {
            $builder->where('user_roles.role_id', $filters['role_id']);
        }
        // Filter: status aktif/non
        if (isset($filters['aktif'])) {
            $builder->where('users.aktif', $filters['aktif']);
        }
        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('users.nama_lengkap', $filters['search'])
                ->orLike('users.email', $filters['search'])
                ->groupEnd();
        }

        return $builder->findAll();
    }

    /**
     * Cari user berdasarkan UUID dan sertakan roles/profil
     */
    public function getUserByUuid(string $uuid): ?array
    {
        $user = $this->userModel->where('uuid', $uuid)->first();
        if (!$user) {
            return null;
        }

        return $this->getUserById($user['id']);
    }

    protected function hasProfileData(array $profil): bool
    {
        foreach ($profil as $value) {
            if (is_array($value)) {
                if ($this->hasProfileData($value)) {
                    return true;
                }
            } elseif ($value !== null && trim((string) $value) !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * Konversi empty string ke null agar MySQL tidak reject pada kolom INT/DATE/ENUM.
     */
    private function sanitizeProfilData(array $profil): array
    {
        return array_map(function ($value) {
            if (is_string($value) && trim($value) === '') {
                return null;
            }
            return $value;
        }, $profil);
    }

    // Membuat user baru + role + profil (transaksi)
    public function createUser(array $data): ?int
    {
        $this->db->transStart();

        // Data akun
        $akun = [
            'username'     => $data['username'],
            'email'        => $data['email'],
            'password'     => password_hash($data['password'], PASSWORD_BCRYPT),
            'nama_lengkap' => $data['nama_lengkap'],
            'aktif'        => $data['aktif'] ?? 1,
        ];
        $userId = $this->userModel->insert($akun, true);
        if (!$userId) {
            $this->db->transRollback();
            return null;
        }

        // Assign roles
        if (!empty($data['roles']) && is_array($data['roles'])) {
            foreach ($data['roles'] as $roleId) {
                $this->userRoleModel->insert(['user_id' => $userId, 'role_id' => $roleId]);
            }
        }

        // Profil (jika ada data yang diisi)
        $profil = $data['profil'] ?? null;
        if (is_array($profil)) {
            $profil = $this->sanitizeProfilData($profil);
            if ($this->hasProfileData($profil)) {
                $profil['user_id'] = $userId;
                $this->userProfileModel->insert($profil);
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return null;
        }
        return $userId;
    }

    // Update user, role, profil
    public function updateUser(int $userId, array $data): bool
    {
        $this->lastError = null;
        $this->db->transStart();

        // Update akun
        $akun = [
            'username'     => $data['username'] ?? null,
            'email'        => $data['email'] ?? null,
            'nama_lengkap' => $data['nama_lengkap'] ?? null,
            'aktif'        => $data['aktif'] ?? null,
        ];
        // Hanya field yang tidak null yang diupdate
        $akun = array_filter($akun, function ($v) {
            return $v !== null;
        });
        if (!empty($akun)) {
            // Ensure validator can ignore current record when using is_unique with {id}
            $akun['id'] = $userId;
            $result = $this->userModel->update($userId, $akun);
            if ($result === false) {
                $this->lastError = ['message' => 'Validation/update failed for userModel', 'errors' => $this->userModel->errors()];
                $this->db->transRollback();
                if (function_exists('log_message')) {
                    log_message('error', 'UserService::updateUser userModel update failed: ' . json_encode($this->lastError));
                }
                return false;
            }
        }

        // Update password jika diberikan
        if (!empty($data['password'])) {
            $result = $this->userModel->update($userId, [
                'password' => password_hash($data['password'], PASSWORD_BCRYPT)
            ]);
            if ($result === false) {
                $this->lastError = ['message' => 'Validation/update failed for password', 'errors' => $this->userModel->errors()];
                $this->db->transRollback();
                if (function_exists('log_message')) {
                    log_message('error', 'UserService::updateUser password update failed: ' . json_encode($this->lastError));
                }
                return false;
            }
        }

        // Sinkronisasi roles: hapus dulu lalu insert ulang
        if (isset($data['roles']) && is_array($data['roles'])) {
            $this->userRoleModel->where('user_id', $userId)->delete();
            foreach ($data['roles'] as $roleId) {
                $res = $this->userRoleModel->insert(['user_id' => $userId, 'role_id' => $roleId]);
                if ($res === false) {
                    $this->lastError = ['message' => 'Failed to insert user_role', 'role_id' => $roleId];
                    $this->db->transRollback();
                    if (function_exists('log_message')) {
                        log_message('error', 'UserService::updateUser userRole insert failed: ' . json_encode($this->lastError));
                    }
                    return false;
                }
            }
        }

        // Update profil
        if (isset($data['profil']) && is_array($data['profil'])) {
            $profilData = $this->sanitizeProfilData($data['profil']);
            $existing = $this->userProfileModel->where('user_id', $userId)->first();
            if ($existing) {
                // Hanya update jika ada field yang berubah (hindari update tanpa data)
                $filteredProfil = array_filter($profilData, fn($v) => $v !== null);
                if (!empty($filteredProfil)) {
                    $res = $this->userProfileModel->update($existing['id'], $profilData);
                    if ($res === false) {
                        $this->lastError = ['message' => 'Validation/update failed for userProfile', 'errors' => $this->userProfileModel->errors()];
                        $this->db->transRollback();
                        log_message('error', 'UserService::updateUser userProfile update failed: ' . json_encode($this->lastError));
                        return false;
                    }
                }
            } elseif ($this->hasProfileData($profilData)) {
                $profilData['user_id'] = $userId;
                $res = $this->userProfileModel->insert($profilData);
                if ($res === false) {
                    $this->lastError = ['message' => 'Failed to insert userProfile', 'errors' => $this->userProfileModel->errors()];
                    $this->db->transRollback();
                    log_message('error', 'UserService::updateUser userProfile insert failed: ' . json_encode($this->lastError));
                    return false;
                }
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            // Store and log DB error for debugging
            $this->lastError = $this->db->error() ?? [];
            if (function_exists('log_message')) {
                log_message('error', 'UserService::updateUser transaction failed: ' . json_encode($this->lastError));
            }
            return false;
        }
        return true;
    }

    // Soft delete user
    public function deleteUser(int $id): bool
    {
        return $this->userModel->delete($id);
    }

    public function restoreUser(int $id): bool
    {
        return $this->userModel->update($id, ['deleted_at' => null]);
    }

    // Reset password oleh admin
    public function resetPassword(int $id, string $newPassword): bool
    {
        return $this->userModel->update($id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);
    }

    // Ambil semua role untuk dropdown
    public function getAllRoles(): array
    {
        return $this->roleModel->findAll();
    }

    // Ambil user berdasarkan ID dengan roles dan profil
    public function getUserById(int $id): ?array
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return null;
        }

        $user['roles'] = $this->userRoleModel->where('user_id', $id)->findColumn('role_id') ?? [];
        $user['profil'] = $this->userProfileModel->where('user_id', $id)->first();
        return $user;
    }

    // Ambil data master untuk dropdown
    public function getMasterData(): array
    {
        $profesiModel = new ProfesiModel();
        $bidangIlmuModel = new BidangIlmuModel();
        $fakultasModel = new FakultasModel();
        $prodiModel = new ProgramStudiModel();
        $jabatanModel = new JabatanFungsionalModel();

        return [
            'profesi'       => $profesiModel->findAll(),
            'bidang_ilmu'   => $bidangIlmuModel->findAll(),
            'fakultas'      => $fakultasModel->findAll(),
            'program_studi' => $prodiModel->findAll(),
            'jabatan'       => $jabatanModel->findAll(),
        ];
    }
}
