<?php

namespace App\Services\CMS;

use App\Models\Master\AnnouncementModel;

class AnnouncementService
{
    private AnnouncementModel $model;

    public function __construct()
    {
        $this->model = new AnnouncementModel();
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
        $input['slug'] = url_title($input['title'], '-', true) . '-' . random_string('alnum', 4);
        
        // 1. Handle Image
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $imageFile->move(FCPATH . 'uploads/announcements', $newName);
            $input['image'] = 'uploads/announcements/' . $newName;
        }

        // 2. Handle Attachment
        if ($attachFile && $attachFile->isValid() && !$attachFile->hasMoved()) {
            $newName = $attachFile->getRandomName();
            $attachFile->move(FCPATH . 'uploads/attachments', $newName);
            $input['file_attachment'] = 'uploads/attachments/' . $newName;
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
            if ($existing['image'] && file_exists(FCPATH . $existing['image'])) {
                @unlink(FCPATH . $existing['image']);
            }
            $newName = $imageFile->getRandomName();
            $imageFile->move(FCPATH . 'uploads/announcements', $newName);
            $input['image'] = 'uploads/announcements/' . $newName;
        }

        // 2. Handle Attachment Baru
        if ($attachFile && $attachFile->isValid() && !$attachFile->hasMoved()) {
            if ($existing['file_attachment'] && file_exists(FCPATH . $existing['file_attachment'])) {
                @unlink(FCPATH . $existing['file_attachment']);
            }
            $newName = $attachFile->getRandomName();
            $attachFile->move(FCPATH . 'uploads/attachments', $newName);
            $input['file_attachment'] = 'uploads/attachments/' . $newName;
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

        $this->model->delete($existing['id']);
    }
}
