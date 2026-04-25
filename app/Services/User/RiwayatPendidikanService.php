<?php

namespace App\Services\User;

use App\Models\User\RiwayatPendidikanModel;
use Exception;
use Ramsey\Uuid\Uuid;

class RiwayatPendidikanService
{
    private RiwayatPendidikanModel $model;
    private const ID_KEY_PREFIX = 'id-';
    private const UPLOAD_DIR = 'uploads/riwayat_pendidikan';

    public function __construct()
    {
        $this->model = new RiwayatPendidikanModel();
    }

    /**
     * Get all riwayat pendidikan for a user
     */
    public function getAllByUser(int $userId): array
    {
        $data = $this->model
            ->where('user_id', $userId)
            ->orderBy('tahun_lulus', 'DESC')
            ->orderBy('tahun_masuk', 'DESC')
            ->findAll();

        // Ensure UUID for all records
        foreach ($data as $record) {
            $this->ensureUuid($record);
        }

        return $data;
    }

    /**
     * Build index payload with table rows
     */
    public function getIndexPayload(int $userId): array
    {
        $rows = [];

        foreach ($this->getAllByUser($userId) as $riwayat) {
            $key = $riwayat->uuid ?: ('id-' . $riwayat->id);

            $rows[] = [
                'key'              => $key,
                'jenjang'          => $riwayat->jenjang_pendidikan,
                'program_studi'    => $riwayat->program_studi,
                'institusi'        => $riwayat->institusi,
                'tahun_masuk'      => $riwayat->tahun_masuk,
                'tahun_lulus'      => $riwayat->tahun_lulus,
                'ipk'              => $this->formatIpk($riwayat->ipk),
                'dokumen_label'    => $this->buildDokumenLabel($riwayat),
                'dokumen_url'      => $this->buildDokumenUrl($riwayat),
                'edit_url'         => site_url('dosen/riwayat-pendidikan/edit/' . $key),
                'delete_url'       => site_url('dosen/riwayat-pendidikan/delete/' . $key),
            ];
        }

        return ['tableRows' => $rows];
    }

    /**
     * Get form payload for create
     */
    public function getCreateFormPayload(): array
    {
        return [
            'formValues' => $this->resolveFormValues(null),
            'formState'  => [
                'is_edit'     => false,
                'action_url'  => site_url('dosen/riwayat-pendidikan/store'),
                'submit_label' => 'Simpan Riwayat Pendidikan',
            ],
            'jenjangOptions' => $this->buildJenjangOptions(''),
        ];
    }

    /**
     * Get form payload for edit
     */
    public function getEditFormPayload(string $key, int $userId): ?array
    {
        $riwayat = $this->findByKeyAndUser($key, $userId);
        if (!$riwayat) {
            return null;
        }

        $riwayatKey = $riwayat->uuid ?: ('id-' . $riwayat->id);

        return [
            'formValues' => $this->resolveFormValues($riwayat),
            'formState'  => [
                'is_edit'     => true,
                'action_url'  => site_url('dosen/riwayat-pendidikan/update/' . $riwayatKey),
                'submit_label' => 'Update Riwayat Pendidikan',
                'form_key'    => $riwayatKey,
            ],
            'jenjangOptions' => $this->buildJenjangOptions($riwayat->jenjang_pendidikan),
        ];
    }

    /**
     * Create new riwayat pendidikan
     */
    public function create(array $input, $fileUpload = null): bool
    {
        $data = $this->normalizeInput($input, $fileUpload);

        if (!$this->model->insert($data)) {
            throw new Exception('Validasi Gagal: ' . implode(', ', $this->model->errors()));
        }

        return true;
    }

    /**
     * Update riwayat pendidikan by key
     */
    public function update(string $key, array $input, int $userId, $fileUpload = null): bool
    {
        $riwayat = $this->findByKeyAndUser($key, $userId);
        if (!$riwayat) {
            throw new Exception('Data riwayat pendidikan tidak ditemukan.');
        }

        $data = $this->normalizeInput($input, $fileUpload);
        $data['id'] = $riwayat->id;

        if (!$this->model->save($data)) {
            throw new Exception('Validasi Gagal: ' . implode(', ', $this->model->errors()));
        }

        return true;
    }

    /**
     * Delete riwayat pendidikan
     */
    public function delete(string $key, int $userId): bool
    {
        $riwayat = $this->findByKeyAndUser($key, $userId);
        if (!$riwayat) {
            throw new Exception('Data riwayat pendidikan tidak ditemukan.');
        }

        // Clean up file if exists
        if ($riwayat->dokumen_tipe === 'file' && $riwayat->dokumen_ijazah) {
            $filePath = WRITEPATH . $riwayat->dokumen_ijazah;
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }

        $this->model->delete($riwayat->id);
        return true;
    }

    /**
     * Find by key and user (ownership check)
     */
    public function findByKeyAndUser(string $key, int $userId): ?object
    {
        $riwayat = $this->findByKey($key);
        if (!$riwayat || (int) $riwayat->user_id !== $userId) {
            return null;
        }

        return $riwayat;
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

    private function ensureUuid(object $record): void
    {
        if (!empty($record->uuid)) {
            return;
        }

        $record->uuid = Uuid::uuid4()->toString();
        $this->model->db
            ->table('riwayat_pendidikan')
            ->where('id', $record->id)
            ->update(['uuid' => $record->uuid]);
    }

    private function normalizeInput(array $input, $fileUpload): array
    {
        $data = [
            'user_id'              => $input['user_id'] ?? 0,
            'jenjang_pendidikan'   => $input['jenjang_pendidikan'] ?? '',
            'program_studi'        => $input['program_studi'] ?? '',
            'institusi'            => $input['institusi'] ?? '',
            'tahun_masuk'          => $input['tahun_masuk'] ?? 0,
            'tahun_lulus'          => $input['tahun_lulus'] ?? 0,
            'ipk'                  => $input['ipk'] ?? null,
        ];

        // Handle dokumen upload
        $dokumenTipe = $input['dokumen_tipe'] ?? 'url';

        if ($dokumenTipe === 'file' && $fileUpload && $fileUpload->isValid()) {
            $filePath = $this->handleFileUpload($fileUpload);
            $data['dokumen_ijazah'] = $filePath;
            $data['dokumen_tipe']   = 'file';
        } elseif ($dokumenTipe === 'url') {
            $data['dokumen_ijazah'] = trim($input['dokumen_ijazah_url'] ?? '');
            $data['dokumen_tipe']   = 'url';
        }

        return $data;
    }

    private function handleFileUpload($file): string
    {
        // Validate file
        if (!$file->isValid()) {
            throw new Exception('File tidak valid: ' . $file->getErrorString());
        }

        // Create directory if not exists (in public folder)
        $uploadPath = FCPATH . self::UPLOAD_DIR;
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Move to public uploads directory
        $newName = $file->getRandomName();
        if (!$file->move($uploadPath, $newName)) {
            throw new Exception('Gagal memindahkan file: ' . $file->getErrorString());
        }

        // Verify file actually exists
        $fullPath = $uploadPath . DIRECTORY_SEPARATOR . $newName;
        if (!is_file($fullPath)) {
            throw new Exception('File tidak ditemukan setelah upload: ' . $fullPath);
        }

        // Return relative path from public root (for base_url())
        return self::UPLOAD_DIR . '/' . $newName;
    }

    private function resolveFormValues(?object $riwayat): array
    {
        $old = $this->getOldPostInput();

        // Default values for new record
        $dokumenTipe = $riwayat?->dokumen_tipe ?? 'url';
        $dokumenJazah = $riwayat?->dokumen_ijazah ?? '';

        return [
            'jenjang_pendidikan'   => $this->pick($old, 'jenjang_pendidikan', $riwayat?->jenjang_pendidikan ?? ''),
            'program_studi'        => $this->pick($old, 'program_studi', $riwayat?->program_studi ?? ''),
            'institusi'            => $this->pick($old, 'institusi', $riwayat?->institusi ?? ''),
            'tahun_masuk'          => $this->pick($old, 'tahun_masuk', $riwayat?->tahun_masuk ?? ''),
            'tahun_lulus'          => $this->pick($old, 'tahun_lulus', $riwayat?->tahun_lulus ?? ''),
            'ipk'                  => $this->pick($old, 'ipk', $riwayat?->ipk ?? ''),
            'dokumen_tipe'         => $this->pick($old, 'dokumen_tipe', $dokumenTipe),
            'dokumen_ijazah_url'   => $this->pick($old, 'dokumen_ijazah_url', $dokumenTipe === 'url' ? $dokumenJazah : ''),
            'dokumen_existing'     => $dokumenJazah,
            'dokumen_existing_tipe' => $dokumenTipe,
        ];
    }

    private function buildJenjangOptions(string $selected): array
    {
        $options = ['S1', 'S2', 'S3', 'D1', 'D2', 'D3'];
        $result = [];

        foreach ($options as $option) {
            $result[] = [
                'value'    => $option,
                'label'    => $option,
                'selected' => $option === $selected ? 'selected' : '',
            ];
        }

        return $result;
    }

    private function buildDokumenLabel(object $riwayat): string
    {
        if (!$riwayat->dokumen_ijazah) {
            return '-';
        }

        if ($riwayat->dokumen_tipe === 'file') {
            $filename = basename($riwayat->dokumen_ijazah);
            return strlen($filename) > 40 ? substr($filename, 0, 37) . '...' : $filename;
        }

        $url = $riwayat->dokumen_ijazah;
        return strlen($url) > 50 ? substr($url, 0, 47) . '...' : $url;
    }

    private function buildDokumenUrl(object $riwayat): ?string
    {
        if (!$riwayat->dokumen_ijazah) {
            return null;
        }

        if ($riwayat->dokumen_tipe === 'file') {
            return base_url($riwayat->dokumen_ijazah);
        }

        return $riwayat->dokumen_ijazah;
    }

    private function formatIpk($ipk): string
    {
        if (!$ipk || $ipk === '') {
            return '-';
        }

        return number_format((float) $ipk, 2, ',', '');
    }

    private function getOldPostInput(): array
    {
        $oldInput = session('_ci_old_input');
        if (!is_array($oldInput)) {
            return [];
        }

        return is_array($oldInput['post'] ?? null) ? $oldInput['post'] : [];
    }

    private function pick(array $source, string $key, $fallback): string
    {
        return (string) ($source[$key] ?? $fallback ?? '');
    }
}
