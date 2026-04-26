<?php

namespace App\Controllers\Profile;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ProfileController extends BaseController
{
    protected $userService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->userService = service('userService');
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
            'master'        => $master,
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

        $uploadDir = FCPATH . 'uploads/profile';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $existingUser = $this->userService->getUserById($userId);
        $oldPhoto = $existingUser['profil']['foto'] ?? null;

        $newName = $file->getRandomName();
        if (!$file->move($uploadDir, $newName)) {
            throw new \Exception('Gagal memindahkan file foto.');
        }

        if (!empty($oldPhoto)) {
            $oldPath = FCPATH . 'uploads/' . ltrim($oldPhoto, '/');
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        $profil['foto'] = 'profile/' . $newName;
        return $profil;
    }
}
