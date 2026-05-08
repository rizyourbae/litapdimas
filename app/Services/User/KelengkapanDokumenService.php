<?php

namespace App\Services\User;

use App\Models\User\KelengkapanDokumenModel;
use App\Libraries\Storage;
use Exception;
use Ramsey\Uuid\Uuid;

class KelengkapanDokumenService
{
    private KelengkapanDokumenModel $model;
    private Storage $storage;
    private const ID_KEY_PREFIX = 'id-';

    // Fixed document types per gambar
    private const DOCUMENT_TYPES = [
        'Sertifikat Dosen',
        'SK Jabatan Fungsional',
        'Kartu NIDN',
    ];

    public function __construct()
    {
        $this->model = new KelengkapanDokumenModel();
        $this->storage = new Storage();
    }

    /**
     * Get all kelengkapan dokumen untuk user
     * Auto-create if not exist
     */
    public function getAllByUser(int $userId): array
    {
        $records = $this->model
            ->where('user_id', $userId)
            ->findAll();

        // Ensure all document types exist for user
        $this->ensureAllDocumentTypes($userId, $records);

        // Re-fetch to get complete list
        $records = $this->model
            ->where('user_id', $userId)
            ->findAll();

        // Ensure UUID for all records
        foreach ($records as $record) {
            $this->ensureUuid($record);
        }

        return $records;
    }

    /**
     * Build index payload with table rows
     */
    public function getIndexPayload(int $userId): array
    {
        $rows = [];

        $records = $this->getAllByUser($userId);
        $recordMap = [];
        foreach ($records as $record) {
            $recordMap[$record->jenis_dokumen] = $record;
        }

        foreach (self::DOCUMENT_TYPES as $documentType) {
            if (!isset($recordMap[$documentType])) {
                continue;
            }

            $dokumen = $recordMap[$documentType];
            $key = $dokumen->uuid ?: ('id-' . $dokumen->id);
            $statusLabel = $dokumen->dokumen_file ? 'Lihat Dokumen' : 'Unggah Dokumen';

            $rows[] = [
                'key'              => $key,
                'jenis_dokumen'    => $dokumen->jenis_dokumen,
                'status'           => $dokumen->dokumen_file ? 'Lengkap' : 'Belum Lengkap',
                'status_badge'     => $dokumen->dokumen_file ? 'success' : 'warning',
                'dokumen_label'    => $this->buildDokumenLabel($dokumen),
                'dokumen_url'      => $this->buildDokumenUrl($dokumen),
                'view_label'       => $statusLabel,
                'view_url'         => $dokumen->dokumen_file ? $this->buildDokumenUrl($dokumen) : '#',
                'edit_url'         => site_url('dosen/kelengkapan-dokumen/edit/' . $key),
                'is_uploaded'      => !empty($dokumen->dokumen_file),
            ];
        }

        return ['tableRows' => $rows];
    }

    /**
     * Get edit form payload
     */
    public function getEditFormPayload(string $key, int $userId): ?array
    {
        $dokumen = $this->findByKeyAndUser($key, $userId);
        if (!$dokumen) {
            return null;
        }

        $dokumenKey = $dokumen->uuid ?: ('id-' . $dokumen->id);

        return [
            'formValues' => $this->resolveFormValues($dokumen),
            'formState'  => [
                'action_url'     => site_url('dosen/kelengkapan-dokumen/update/' . $dokumenKey),
                'submit_label'   => 'Unggah Dokumen',
                'form_key'       => $dokumenKey,
            ],
        ];
    }

    /**
     * Update dokumen
     */
    public function update(string $key, int $userId, $fileUpload = null): bool
    {
        $dokumen = $this->findByKeyAndUser($key, $userId);
        if (!$dokumen) {
            throw new Exception('Data kelengkapan dokumen tidak ditemukan.');
        }

        if (!$fileUpload || !$fileUpload->isValid()) {
            throw new Exception('File dokumen harus diupload.');
        }

        // Delete old file from storage if exists
        if ($dokumen->dokumen_file) {
            $this->storage->deleteObject($dokumen->dokumen_file);
        }

        // Handle new file upload
        $filePath = $this->handleFileUpload($fileUpload, $dokumen->jenis_dokumen);

        $data = [
            'id'            => $dokumen->id,
            'dokumen_file'  => $filePath,
        ];

        if (!$this->model->save($data)) {
            throw new Exception('Validasi Gagal: ' . implode(', ', $this->model->errors()));
        }

        return true;
    }

    /**
     * Find by key and user (ownership check)
     */
    public function findByKeyAndUser(string $key, int $userId): ?object
    {
        $dokumen = $this->findByKey($key);
        if (!$dokumen || (int) $dokumen->user_id !== $userId) {
            return null;
        }

        return $dokumen;
    }

    /**
     * Private helper methods
     */
    private function findByKey(string $key): ?object
    {
        if (str_starts_with($key, self::ID_KEY_PREFIX)) {
            $id = (int) substr($key, strlen(self::ID_KEY_PREFIX));
            return $id > 0 ? $this->model->find($id) : null;
        }

        return $this->model->where('uuid', $key)->first();
    }

    private function ensureAllDocumentTypes(int $userId, array $existingRecords): void
    {
        $existingTypes = [];
        foreach ($existingRecords as $record) {
            if (!empty($record->jenis_dokumen)) {
                $existingTypes[] = $record->jenis_dokumen;
            }
        }

        foreach (self::DOCUMENT_TYPES as $docType) {
            if (!in_array($docType, $existingTypes, true)) {
                $saved = $this->model->insert([
                    'user_id'       => $userId,
                    'jenis_dokumen' => $docType,
                    'dokumen_file'  => null,
                ]);

                if ($saved === false) {
                    throw new Exception('Gagal menyiapkan data kelengkapan dokumen: ' . implode(', ', $this->model->errors()));
                }
            }
        }
    }

    private function ensureUuid(object $record): void
    {
        if (!empty($record->uuid)) {
            return;
        }

        $record->uuid = Uuid::uuid4()->toString();
        $this->model->db
            ->table('kelengkapan_dokumen')
            ->where('id', $record->id)
            ->update(['uuid' => $record->uuid]);
    }

    private function handleFileUpload($file, string $docType): string
    {
        // Validate file
        if (!$file->isValid()) {
            throw new Exception('File tidak valid: ' . $file->getErrorString());
        }

        // Validate file type
        $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($file->getMimeType(), $allowedMimes, true)) {
            throw new Exception('Tipe file tidak didukung. Gunakan: PDF, JPG, PNG, DOC, DOCX');
        }

        // Validate file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new Exception('Ukuran file tidak boleh lebih dari 10MB.');
        }

        $fileName = pathinfo($file->getRandomName(), PATHINFO_FILENAME);

        try {
            $upload = $this->storage->putObject(
                $_FILES['file_dokumen'],
                'kelengkapan',
                $fileName,
                Storage::SHR_PUBLIC,
            );
        } catch (\Exception $e) {
            $upload = ['status' => 0, 'message' => $e->getMessage()];
        }

        if (!isset($upload['status']) || $upload['status'] != 1) {
            $errorMsg = $upload['message'] ?? 'Gagal mengunggah berkas.';
            throw new Exception($errorMsg);
        }

        $objectName = $upload['data']['object_name'] ?? null;
        if (!$objectName) {
            throw new Exception('Gagal mendapatkan nama file dari penyimpanan.');
        }

        // Return file path from storage
        return $objectName;
    }

    private function resolveFormValues(?object $dokumen): array
    {
        return [
            'jenis_dokumen'     => $dokumen?->jenis_dokumen ?? '',
            'dokumen_existing'  => $dokumen?->dokumen_file ?? '',
            'has_dokumen'       => !empty($dokumen?->dokumen_file),
        ];
    }

    private function buildDokumenLabel(object $dokumen): string
    {
        if (!$dokumen->dokumen_file) {
            return '-';
        }

        $filename = basename($dokumen->dokumen_file);
        return strlen($filename) > 40 ? substr($filename, 0, 37) . '...' : $filename;
    }

    private function buildDokumenUrl(object $dokumen): ?string
    {
        if (!$dokumen->dokumen_file) {
            return null;
        }

        return site_url('files/kelengkapan/' . $dokumen->uuid);
    }
}
