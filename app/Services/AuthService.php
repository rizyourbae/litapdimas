<?php

namespace App\Services;

use App\Models\Auth\UserModel;
# use App\Models\Auth\RoleModel;
use App\Models\Auth\PermissionModel;
use App\Models\Auth\UserRoleModel;
use App\Models\Auth\RolePermissionModel;
use CodeIgniter\Session\Session;
use Config\Services;

class AuthService
{
    protected $session;
    protected $userModel;
    protected $userRoleModel;
    protected $rolePermissionModel;

    public function __construct()
    {
        if (!is_cli()) {
            $this->session = Services::session();
        }
        $this->userModel = new UserModel();
        $this->userRoleModel = new UserRoleModel();
        $this->rolePermissionModel = new RolePermissionModel();
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
        $roles = $this->userRoleModel->where('user_id', $user['id'])->findAll();
        $roleIds = array_column($roles, 'role_id');
        $permissions = [];
        if (!empty($roleIds)) {
            $perms = $this->rolePermissionModel->whereIn('role_id', $roleIds)->findAll();
            $permIds = array_column($perms, 'permission_id');
            if (!empty($permIds)) {
                $permissions = (new PermissionModel())
                    ->whereIn('id', $permIds)
                    ->findColumn('name') ?? [];
            }
        }

        $userData = [
            'id'           => $user['id'],
            'uuid'         => $user['uuid'],
            'username'     => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'email'        => $user['email'],
            'roles'        => array_column($roles, 'role_id'), // bisa juga nama role
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

    public function hasRole($roleId): bool
    {
        $user = $this->user();
        return $user && in_array($roleId, $user['roles'] ?? []);
    }

    public function can(string $permission): bool
    {
        $user = $this->user();
        return $user && in_array($permission, $user['permissions'] ?? []);
    }
}
