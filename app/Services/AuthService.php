<?php

namespace App\Services;

use App\Models\Auth\UserModel;
use App\Models\Auth\RoleModel;
use App\Models\Auth\PermissionModel;
use App\Models\Auth\UserRoleModel;
use App\Models\Auth\RolePermissionModel;
use App\Models\User\UserProfileModel;
use CodeIgniter\Session\Session;
use Config\Services;

class AuthService
{
    protected $session;
    protected $userModel;
    protected $roleModel;
    protected $userRoleModel;
    protected $rolePermissionModel;
    protected $userProfileModel;

    public function __construct()
    {
        if (!is_cli()) {
            $this->session = Services::session();
        }
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->userRoleModel = new UserRoleModel();
        $this->rolePermissionModel = new RolePermissionModel();
        $this->userProfileModel = new UserProfileModel();
    }

    public function attempt(string $username, string $password): bool
    {
        $user = $this->userModel->where('username', $username)->orWhere('email', $username)->first();

        // Debug: cek apakah user ditemukan
        if (!$user) {
            log_message('debug', 'User tidak ditemukan: ' . $username);
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            log_message('debug', 'Password salah untuk user: ' . $username);
            return false;
        }

        if (!$user['aktif']) {
            log_message('debug', 'User tidak aktif: ' . $username);
            return false;
        }

        $this->session->regenerate();
        $this->setUserSession($user);
        return true;
    }

    protected function setUserSession(array $user)
    {
        $userRoles = $this->userRoleModel->where('user_id', $user['id'])->findAll();
        $roleIds   = array_column($userRoles, 'role_id');

        // Ambil nama-nama role
        $roleNames = [];
        if (!empty($roleIds)) {
            $roleNames = $this->roleModel->whereIn('id', $roleIds)->findColumn('name') ?? [];
        }

        // Ambil permissions dari semua role yang dimiliki
        $permissions = [];
        if (!empty($roleIds)) {
            $perms   = $this->rolePermissionModel->whereIn('role_id', $roleIds)->findAll();
            $permIds = array_column($perms, 'permission_id');
            if (!empty($permIds)) {
                $permissions = (new PermissionModel())
                    ->whereIn('id', $permIds)
                    ->findColumn('name') ?? [];
            }
        }

        $profile = $this->userProfileModel->where('user_id', $user['id'])->first();
        $photoUrl = base_url('assets/adminlte/assets/img/user2-160x160.jpg');
        if (!empty($profile['foto'])) {
            $photoUrl = base_url('uploads/' . ltrim((string) $profile['foto'], '/'));
        }

        $userData = [
            'id'           => $user['id'],
            'uuid'         => $user['uuid'],
            'username'     => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'email'        => $user['email'],
            'foto'         => $profile['foto'] ?? null,
            'foto_url'     => $photoUrl,
            'roles'        => $roleIds,        // array of int IDs (backward compat)
            'role_names'   => $roleNames,      // array of string names e.g. ['dosen','reviewer']
            'permissions'  => $permissions,
            'logged_in'    => true,
        ];
        $this->session->set('user', $userData);
    }

    public function logout()
    {
        $this->session->destroy();
    }

    public function user(): ?array
    {
        if (!$this->session) {
            return null;
        }
        return $this->session->get('user');
    }

    public function userId(): ?int
    {
        if (!$this->session) {
            return null;
        }
        return $this->session->get('user')['id'] ?? null;
    }

    public function isLoggedIn(): bool
    {
        return $this->session->get('user')['logged_in'] ?? false;
    }

    /**
     * Perbarui data session user setelah user mengubah profil/akunnya sendiri.
     * Dipanggil setelah update profil agar navbar nama/role langsung ter-update.
     */
    public function refreshSession(int $userId): void
    {
        $user = $this->userModel->find($userId);
        if ($user) {
            $this->setUserSession($user);
        }
    }

    /**
     * Cek apakah user memiliki role tertentu berdasarkan nama.
     * Contoh: hasRole('admin'), hasRole('dosen')
     */
    public function hasRole(string $roleName): bool
    {
        $user = $this->user();
        return $user && in_array($roleName, $user['role_names'] ?? []);
    }

    /**
     * Cek apakah user memiliki salah satu dari beberapa role.
     * Contoh: hasAnyRole(['dosen', 'reviewer'])
     */
    public function hasAnyRole(array $roleNames): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }
        return !empty(array_intersect($roleNames, $user['role_names'] ?? []));
    }

    /**
     * Kembalikan array nama role user yang sedang login.
     */
    public function getRoleNames(): array
    {
        return $this->user()['role_names'] ?? [];
    }

    public function can(string $permission): bool
    {
        $user = $this->user();
        return $user && in_array($permission, $user['permissions'] ?? []);
    }
}
