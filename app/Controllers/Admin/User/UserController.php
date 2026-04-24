<?php

namespace App\Controllers\Admin\User;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
# use App\Models\Master\UnitKerjaModel;

class UserController extends BaseController
{
    protected $userService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        // Inject UserService via service locator (DI container)
        $this->userService = service('userService');
    }

    // Daftar user
    public function index()
    {
        $filters = [
            'role_id' => $this->request->getGet('role_id'),
            'aktif'   => $this->request->getGet('aktif'),
            'search'  => $this->request->getGet('search'),
        ];
        $users = $this->userService->getUsersWithRoles($filters);
        $roles = $this->userService->getAllRoles();

        $data = [
            'title'   => 'Manajemen User',
            'users'   => $users,
            'roles'   => $roles,
            'filters' => $filters,
        ];
        return $this->renderView('admin/users/index', $data);
    }

    // Form tambah
    public function create()
    {
        $data = [
            'title' => 'Tambah User',
            'action' => site_url('admin/users/store'),
            'user'  => null,
            'roles' => $this->userService->getAllRoles(),
            'master' => $this->userService->getMasterData(),
        ];
        return $this->renderView('admin/users/form', $data);
    }

    // Proses tambah
    public function store()
    {
        $data = $this->request->getPost();
        $roles = $data['roles'] ?? [];
        $profil = $data['profil'] ?? [];

        // Basic validation for required fields (SOLID: keep validation at controller/service boundary)
        $errors = [];
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'Password wajib diisi minimal 6 karakter.';
        }
        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', 'Gagal menambahkan user.');
        }

        $payload = [
            'username'     => $data['username'],
            'email'        => $data['email'],
            'password'     => $data['password'],
            'nama_lengkap' => $data['nama_lengkap'],
            'aktif'        => $data['aktif'] ?? 1,
            'roles'        => $roles,
            'profil'       => $profil,
        ];

        $userId = $this->userService->createUser($payload);
        if ($userId) {
            return redirect()->to(site_url('admin/users'))->with('success', 'User berhasil ditambahkan.');
        }
        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan user.');
    }

    // Form edit
    public function edit($uuid)
    {
        $user = $this->userService->getUserByUuid($uuid);
        if (!$user) {
            return redirect()->to(site_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title'  => 'Edit User',
            'action' => site_url('admin/users/update/' . $uuid),
            'user'   => $user,
            'roles'  => $this->userService->getAllRoles(),
            'master' => $this->userService->getMasterData(),
        ];
        return $this->renderView('admin/users/form', $data);
    }

    // Proses update
    public function update($uuid)
    {
        $user = $this->userService->getUserByUuid($uuid);
        if (!$user) {
            return redirect()->to(site_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }
        $id = $user['id']; // Ambil integer ID untuk proses update

        $data = $this->request->getPost();
        $roles = $data['roles'] ?? [];
        $profil = $data['profil'] ?? [];

        $payload = [
            'username'     => $data['username'] ?? null,
            'email'        => $data['email'] ?? null,
            'nama_lengkap' => $data['nama_lengkap'] ?? null,
            'aktif'        => $data['aktif'] ?? null,
            'password'     => $data['password'] ?? null,
            'roles'        => $roles,
            'profil'       => $profil,
        ];

        if (empty($payload['password'])) unset($payload['password']);

        $updated = $this->userService->updateUser($id, $payload);
        if ($updated) {
            return redirect()->to(site_url('admin/users'))->with('success', 'User berhasil diperbarui.');
        }

        $err = $this->userService->getLastError();

        // If we have validation errors from models, pass them to the session so the view can show details
        if (!empty($err) && is_array($err) && isset($err['errors']) && is_array($err['errors'])) {
            return redirect()->back()->withInput()->with('errors', $err['errors'])->with('error', 'Gagal memperbarui user.');
        }

        $msg = 'Gagal memperbarui user.';
        if (!empty($err) && defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            $msg .= ' DB error: ' . ($err['message'] ?? json_encode($err));
        }

        return redirect()->back()->withInput()->with('error', $msg);
    }

    // Soft delete
    public function delete($uuid)
    {
        $user = $this->userService->getUserByUuid($uuid);
        if ($user) {
            $this->userService->deleteUser($user['id']);
        }
        return redirect()->to(site_url('admin/users'))->with('success', 'User dihapus.');
    }

    // Restore
    public function restore($uuid)
    {
        $user = $this->userService->getUserByUuid($uuid);
        if ($user) {
            $this->userService->restoreUser($user['id']);
        }
        return redirect()->to(site_url('admin/users'))->with('success', 'User dikembalikan.');
    }

    // Reset password (via modal atau form kecil)
    public function resetPassword($uuid)
    {
        $user = $this->userService->getUserByUuid($uuid);
        if (!$user) {
            return redirect()->to(site_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        if ($this->request->is('post')) {
            $newPassword = $this->request->getPost('password');
            if (empty($newPassword)) {
                return redirect()->back()->withInput()->with('error', 'Password tidak boleh kosong.');
            }
            if (strlen($newPassword) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
            }

            if ($this->userService->resetPassword($user['id'], $newPassword)) {
                return redirect()->to(site_url('admin/users'))->with('success', 'Password berhasil direset.');
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mereset password. Silakan coba lagi.');
        }

        return $this->renderView('admin/users/reset_password', [
            'title'  => 'Reset Password',
            'user'   => $user,
            'action' => site_url('admin/users/resetPassword/' . $uuid),
        ]);
    }
}
