<?php

namespace App\Controllers\Profile;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\Storage;

class ProfileController extends BaseController
{
    protected $userService;
    protected $storage;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->userService = service('userService');
        $this->storage = new Storage();
    }

    /**
     * Tampilkan halaman edit profil untuk user yang sedang login.
     */
    public function index()
    {
        $auth   = service('auth');
        $userId = $auth->userId();

        $user   = $this->userService->getUserById($userId);
        $master = $this->userService->getMasterData();

        $data = [
            'title'         => 'Edit Profil',
            'currentModule' => 'Profil',
            'user'          => $user,
            'profile'       => $user['profil'] ?? [],
            'master'        => $master,
            'viewState'     => $this->buildEditProfileViewState($user),
        ];

        return $this->renderView('profile/edit', $data);
    }

    /**
     * Proses simpan perubahan profil.
     * Hanya boleh mengubah data akun (username, email, nama_lengkap, password)
     * dan data profil — TIDAK boleh mengubah roles atau status aktif.
     */
    public function update()
    {
        $auth   = service('auth');
        $userId = $auth->userId();

        $data   = $this->request->getPost();
        $profil = $data['profil'] ?? [];

        try {
            $profil = $this->handlePhotoUpload($userId, $profil);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }

        $payload = [
            'username'     => $data['username']      ?? null,
            'email'        => $data['email']         ?? null,
            'nama_lengkap' => $data['nama_lengkap']  ?? null,
            'profil'       => $profil,
        ];

        // Update password hanya jika diisi
        if (!empty($data['password'])) {
            if (strlen($data['password']) < 6) {
                return redirect()->back()->withInput()
                    ->with('errors', ['password' => 'Password minimal 6 karakter.'])
                    ->with('error', 'Gagal menyimpan profil.');
            }
            $payload['password'] = $data['password'];
        }

        // Bersihkan null agar model tidak mencoba update field kosong
        $payload = array_filter($payload, fn($v) => $v !== null);

        $updated = $this->userService->updateUser($userId, $payload);

        if ($updated) {
            // Perbarui session agar nama dan foto di navbar ikut berubah
            $auth->refreshSession($userId);

            return redirect()->to(site_url('profile'))
                ->with('success', 'Profil berhasil disimpan.');
        }

        $err = $this->userService->getLastError();
        if (!empty($err['errors'])) {
            return redirect()->back()->withInput()
                ->with('errors', $err['errors'])
                ->with('error', 'Gagal menyimpan profil.');
        }

        return redirect()->back()->withInput()
            ->with('error', 'Gagal menyimpan profil. Silakan coba lagi.');
    }

    public function foto()
    {
        $auth   = service('auth');
        $userId = $auth->userId();

        $user   = $this->userService->getUserById($userId);
        $profil = $user['profil'] ?? [];
        $photoPath = $profil['foto'] ?? null;

        if (empty($photoPath)) {
            return redirect()->to(base_url('assets/img/avatar.jpg'));
        }

        try {
            $imageUrl = $this->storage->getObjectUrl('foto', $photoPath, '', Storage::SHR_PUBLIC);
            return redirect()->to($imageUrl);
        } catch (\Exception $e) {
            return redirect()->to(base_url('assets/img/avatar.jpg'));
        }
    }

    private function handlePhotoUpload(int $userId, array $profil): array
    {
        $file = $this->request->getFile('foto');
        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return $profil;
        }

        if (!$file->isValid()) {
            throw new \Exception('Upload foto gagal: ' . $file->getErrorString());
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimes, true)) {
            throw new \Exception('Format foto harus JPG, PNG, atau WEBP.');
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            throw new \Exception('Ukuran foto maksimal 2MB.');
        }

        $existingUser = $this->userService->getUserById($userId);
        $oldPhoto = $existingUser['profil']['foto'] ?? null;
        if (!empty($oldPhoto)) {
            $this->storage->deleteObject($oldPhoto);
        }

        $newName = pathinfo($file->getRandomName(), PATHINFO_FILENAME);
        try {
            $upload = $this->storage->putObject(
                $_FILES['foto'],
                'foto',
                $newName,
                Storage::SHR_PUBLIC,
            );
        } catch (\Exception $e) {
            $upload = ['status' => 0, 'message' => $e->getMessage()];
        }

        if (!isset($upload['status']) || $upload['status'] != 1) {
            throw new \Exception('Gagal mengunggah foto: ' . ($upload['message'] ?? 'Unknown error'));
        }

        $profil['foto'] = $upload['data']['object_name'];
        return $profil;
    }

    private function buildEditProfileViewState(array $user): array
    {
        $errors = session()->getFlashdata('errors') ?? [];
        $profil = $user['profil'] ?? [];
        $photoPath = $profil['foto'] ?? null;

        $currentPhotoUrl = base_url('assets/img/avatar.jpg');
        if (!empty($photoPath)) {
            try {
                $currentPhotoUrl = $this->storage->getObjectUrl('foto', $photoPath, '', Storage::SHR_PUBLIC);
            } catch (\Exception $e) {
                $currentPhotoUrl = base_url('assets/img/avatar.jpg');
            }
        }

        return [
            'errors'           => $errors,
            'activeTab'        => $this->resolveActiveProfileTab($errors),
            'currentPhotoUrl'  => $currentPhotoUrl,
            'hasSavedPhoto'    => !empty($photoPath),
            'savedPhotoName'   => !empty($photoPath) ? $photoPath : null,
        ];
    }

    private function resolveActiveProfileTab(array $errors): string
    {
        if (empty($errors)) {
            return 'akun';
        }

        $accountFieldKeys = ['username', 'email', 'nama_lengkap', 'password'];
        $firstErrorKey = array_key_first($errors);

        if ($firstErrorKey === null) {
            return 'akun';
        }

        return in_array((string) $firstErrorKey, $accountFieldKeys, true) ? 'akun' : 'profil';
    }
}
