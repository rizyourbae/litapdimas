<?php

namespace App\Services\CMS;

use App\Models\Master\AnnouncementModel;
use App\Libraries\Storage;

class AnnouncementService
{
    private AnnouncementModel $model;
    protected Storage $storage;

    public function __construct()
    {
        $this->model = new AnnouncementModel();
        $this->storage = new Storage();
    }

    /**
     * Data untuk Tabel Admin
     */
    public function getAdminList(): array
    {
        return $this->model->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Data untuk Publik
     */
    public function getPublicList(int $perPage = 9): array
    {
        return [
            'list'   => $this->model->where('is_active', true)->orderBy('created_at', 'DESC')->paginate($perPage, 'announcements'),
            'pager'  => $this->model->pager,
        ];
    }

    public function getByUuid(string $uuid): ?array
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    public function getBySlug(string $slug): ?array
    {
        $data = $this->model->where('slug', $slug)->where('is_active', true)->first();
        if ($data) {
            // Increment view count secara pasif
            $this->model->update($data['id'], ['view_count' => $data['view_count'] + 1]);
        }
        return $data;
    }

    /**
     * Simpan Pengumuman Baru
     */
    public function store(array $input, $imageFile, $attachFile): void
    {
        helper('text');
        $input['slug'] = url_title($input['title'], '-', true);
        
        // 1. Handle Image
        $input['image'] = $this->handleFileUpload('announcements', $imageFile);

        // 2. Handle Attachment
        if ($attachFile && $attachFile->isValid() && !$attachFile->hasMoved()) {
            $input['file_attachment'] = $this->handleFileUpload('attachments', $attachFile);
        }

        $input['is_active'] = isset($input['is_active']) ? 1 : 0;

        if (!$this->model->insert($input)) {
            throw new \Exception('Gagal menyimpan pengumuman: ' . implode(', ', $this->model->errors()));
        }
    }

    /**
     * Update Pengumuman
     */
    public function update(string $uuid, array $input, $imageFile = null, $attachFile = null): void
    {
        $existing = $this->getByUuid($uuid);
        if (!$existing) throw new \Exception('Pengumuman tidak ditemukan.');

        // Update slug jika judul berubah (opsional, tapi biar konsisten)
        if ($existing['title'] !== $input['title']) {
            helper('text');
            $input['slug'] = url_title($input['title'], '-', true) . '-' . random_string('alnum', 4);
        }

        // 1. Handle Image Baru
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $input['image'] = $this->handleFileUpload('announcements', $imageFile);

            if ($existing['image']) {
                try {
                    $this->storage->deleteObject($existing['image']);
                } catch (\Exception $e) {
                    log_message('error', 'Gagal menghapus file gambar lama: ' . $e->getMessage());
                }
            }
        }

        // 2. Handle Attachment Baru
        if ($attachFile && $attachFile->isValid() && !$attachFile->hasMoved()) {
            $input['file_attachment'] = $this->handleFileUpload('attachments', $attachFile);

             // Hapus file lama jika ada
             if ($existing['file_attachment']) {
                try {
                    $this->storage->deleteObject($existing['file_attachment']);
                } catch (\Exception $e) {
                    log_message('error', 'Gagal menghapus file attachment lama: ' . $e->getMessage());
                }
            }
        }

        $input['is_active'] = isset($input['is_active']) ? 1 : 0;

        if (!$this->model->update($existing['id'], $input)) {
            throw new \Exception('Gagal memperbarui pengumuman: ' . implode(', ', $this->model->errors()));
        }
    }

    /**
     * Hapus Pengumuman
     */
    public function delete(string $uuid): void
    {
        $existing = $this->getByUuid($uuid);
        if (!$existing) throw new \Exception('Pengumuman tidak ditemukan.');

        if($existing['image']){
            try {
                $this->storage->deleteObject($existing['image']);
            } catch (\Exception $e) {
                log_message('error', 'Gagal menghapus file gambar: ' . $e->getMessage());
            }
        }
        $this->model->delete($existing['id']);
    }

    private function handleFileUpload(string $folder, $file)
    {
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = pathinfo($file->getRandomName(), PATHINFO_FILENAME);
            
            try {
                $fileArray = [
                    'tmp_name' => $file->getTempName(),
                    'size'     => $file->getSize(),
                    'name'     => $file->getClientName(),
                ];
                
                $upload = $this->storage->putObject(
                    $fileArray,
                    $folder,
                    $fileName,
                    Storage::SHR_PUBLIC,
                );
            } catch (\Exception $e) {
                $upload = ['status' => 0, 'message' => $e->getMessage()];
            }

            if (!isset($upload['status']) || $upload['status'] != 1) {
                $errorMsg = $upload['message'] ?? 'Gagal mengunggah berkas.';
                throw new \Exception($errorMsg);
            }

            $objectName = $upload['data']['object_name'] ?? null;
            if (!$objectName) {
                throw new \Exception('Gagal mendapatkan nama berkas dari penyimpanan.');
            }

            return $objectName;
        }
        throw new \Exception('File tidak valid atau gagal diupload.');
    }
}
