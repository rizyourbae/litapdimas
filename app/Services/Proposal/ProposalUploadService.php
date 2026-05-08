<?php

namespace App\Services\Proposal;

use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Libraries\Storage;

/**
 * ProposalUploadService
 *
 * Service untuk handle file upload
 * Prinsip:
 * - Single Responsibility: Hanya handle upload & file operation
 * - Security: MIME type validation, size checking
 * - DRY: Reusable untuk semua tipe dokumen
 */
class ProposalUploadService
{
    protected $storage;
    
    // Constants
    private const UPLOAD_DIR = 'uploads/proposal';
    private const MAX_FILE_SIZE = 2097152; // 2MB in bytes
    private const ALLOWED_MIME_TYPES = ['application/pdf'];
    private const ALLOWED_EXTENSIONS = ['pdf'];

    private $lastError = '';

    public function __construct()
    {
        $this->storage = new Storage();
    }

    /**
     * Upload single file
     * 
     * @param string $fileInputName Name dari input file di form
     * @param string $proposalUuid UUID proposal untuk organizing folder
     * @param string $docType Tipe dokumen: proposal, rab, similarity, pendukung
     * @return array|false Array dengan info file, atau false jika gagal
     */
    public function uploadFile(string $fileInputName, string $proposalUuid, string $docType)
    {
        try {
            $file = request()->getFile($fileInputName);

            // Cek apakah file ada
            if (!$file || $file->getError() !== UPLOAD_ERR_OK) {
                $this->lastError = 'File tidak ditemukan atau error saat upload';
                return false;
            }

            // Validasi MIME type
            if (!$this->validateMimeType($file, method_exists($file, 'getClientName') ? $file->getClientName() : null)) {
                return false;
            }

            // Validasi file size
            if (!$this->validateFileSize($file)) {
                return false;
            }

            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            // Opsi Switch Berdasarkan Environment
            if (ENVIRONMENT === 'development') {
                // Mode Development: Simpan ke storage lokal
                $newFilename = $docType . '_' . time() . '_' . $file->getRandomName();
                $uploadPath = WRITEPATH . self::UPLOAD_DIR . '/' . $proposalUuid;

                if (!is_dir($uploadPath)) {
                    if (!mkdir($uploadPath, 0755, true) && !is_dir($uploadPath)) {
                        $this->lastError = 'Gagal membuat direktori upload lokal';
                        return false;
                    }
                }

                if ($file->move($uploadPath, $newFilename)) {
                    return [
                        'nama_file' => $newFilename,
                        'path_file' => self::UPLOAD_DIR . '/' . $proposalUuid . '/' . $newFilename,
                        'file_size' => $fileSize,
                        'mime_type' => $mimeType,
                    ];
                }

                $this->lastError = 'Gagal memindahkan file ke folder lokal';
                return false;
            }

            // Mode Production: Gunakan UINSI Storage API
            $fileName = pathinfo($file->getRandomName(), PATHINFO_FILENAME);
            
            try {
                $upload = $this->storage->putObject(
                    $_FILES[$fileInputName],
                    'proposal',
                    $fileName,
                    Storage::SHR_PUBLIC,
                );
            } catch (\Exception $e) {
                $upload = ['status' => 0, 'message' => $e->getMessage()];
            }

            if (!isset($upload['status']) || $upload['status'] != 1) {
                $this->lastError = 'Gagal mengunggah file: ' . ($upload['message'] ?? 'Unknown error');
                return false;
            }

            $objectName = $upload['data']['object_name'] ?? null;
            $objectDir  = $upload['data']['directory'] ?? null;
            if (!$objectName) {
                $this->lastError = 'Gagal mendapatkan nama file dari upload';
                return false;
            }

            return [
                'nama_file' => $objectName,
                'path_file' => $objectDir . '/' . $objectName,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
            ];
        } catch (\Exception $e) {
            $this->lastError = 'Error saat upload: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Upload multiple files (untuk dokumen pendukung)
     * Menggunakan UINSI Storage API untuk upload
     * 
     * @param string $fileInputName Name dari input file di form
     * @param string $proposalUuid UUID proposal
     * @return array Array of uploaded file info
     */
    public function uploadMultipleFiles(string $fileInputName, string $proposalUuid): array
    {
        $results = [];
        $files = $_FILES[$fileInputName] ?? null;

        if (!$files || !is_array($files['name'])) {
            return [];
        }

        $count = is_array($files['name']) ? count($files['name']) : 1;

        for ($i = 0; $i < $count; $i++) {
            $name = is_array($files['name']) ? $files['name'][$i] : $files['name'];
            $tmpName = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
            $error = is_array($files['error']) ? $files['error'][$i] : $files['error'];
            $size = is_array($files['size']) ? $files['size'][$i] : $files['size'];

            if (!empty($name) && $error === UPLOAD_ERR_OK) {
                $file = new File($tmpName);
                if ($this->validateMimeType($file, $name) && $this->validateFileSize($file)) {
                    $mimeType = $file->getMimeType();
                    
                    // Prepare file array for putObject
                    $fileArray = [
                        'name' => $name,
                        'tmp_name' => $tmpName,
                        'size' => $size,
                        'error' => $error,
                        'type' => $mimeType,
                    ];

                    // Generate filename
                    $fileName = time() . '_' . bin2hex(random_bytes(10));
                    
                    try {
                        $upload = $this->storage->putObject(
                            $fileArray,
                            'proposal',
                            $fileName,
                            Storage::SHR_PUBLIC,
                        );

                        if (isset($upload['status']) && $upload['status'] == 1) {
                            $objectName = $upload['data']['object_name'];
                            $objectDir  = $upload['data']['directory'];
                            if ($objectName) {
                                $results[] = [
                                    'nama_file' => $objectName,
                                    'path_file' => $objectDir .'/'. $objectName,
                                    'file_size' => $size,
                                    'mime_type' => $mimeType,
                                ];
                            }
                        } else {
                            $this->lastError = 'Gagal mengunggah file: ' . ($upload['message'] ?? 'Unknown error');
                        }
                    } catch (\Exception $e) {
                        $this->lastError = 'Error saat upload: ' . $e->getMessage();
                    }
                }
            }
        }

        return $results;
    }

    /**
     * Validasi MIME type
     * Only PDF files allowed untuk step 4
     * 
     * @param File|UploadedFile $file
     * @param string|null $originalName
     * @return bool
     */
    private function validateMimeType(File|UploadedFile $file, ?string $originalName = null): bool
    {
        $mimeType = $file->getMimeType();
        $extension = '';

        if ($file instanceof UploadedFile) {
            $extension = strtolower((string) $file->getClientExtension());
        }

        if ($extension === '' && !empty($originalName)) {
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        }

        if ($extension === '') {
            $extension = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
        }

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            $this->lastError = 'Tipe file tidak didukung. Hanya file PDF yang diperbolehkan. (MIME: ' . $mimeType . ')';
            return false;
        }

        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            $this->lastError = 'Ekstensi file tidak didukung. Hanya file .pdf yang diperbolehkan.';
            return false;
        }

        return true;
    }

    /**
     * Validasi ukuran file
     * Max 2MB per file
     * 
     * @param File $file
     * @return bool
     */
    private function validateFileSize(File $file): bool
    {
        $size = $file->getSize();

        if ($size > self::MAX_FILE_SIZE) {
            $maxSizeMB = self::MAX_FILE_SIZE / 1024 / 1024;
            $actualSizeMB = $size / 1024 / 1024;
            $this->lastError = "Ukuran file terlalu besar. Maksimal {$maxSizeMB}MB, file Anda {$actualSizeMB}MB";
            return false;
        }

        if ($size === 0) {
            $this->lastError = 'File kosong, tidak dapat diunggah';
            return false;
        }

        return true;
    }

    /**
     * Delete file from storage
     * 
     * @param string $filePath Path relative to FCPATH
     * @return bool
     */
    public function deleteFile(string $filePath): bool
    {
        try {
            // Jika development atau file ada di lokal, hapus lokal
            $fullPath = WRITEPATH . $filePath;
            if (is_file($fullPath)) {
                return unlink($fullPath);
            }

            // Jika di production dan tidak ada di lokal, hapus dari Storage API
            if (ENVIRONMENT === 'production') {
                $result = $this->storage->deleteObject($filePath);
                return (isset($result['status']) && $result['status'] == 1);
            }

            return false;
        } catch (\Exception $e) {
            $this->lastError = 'Error saat menghapus file: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Clean up temporary files on error
     * 
     * @param array $uploadedFiles Array of file paths to delete
     * @return void
     */
    public function cleanupTempFiles(array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $filePath) {
            $this->deleteFile($filePath);
        }
    }

    /**
     * Get file path for download/view
     * 
     * @param string $filePath Relative path
     * @return string|false Full path if exists, false otherwise
     */
    public function getFilePath(string $filePath)
    {
        $fullPath = WRITEPATH . $filePath;
        if (is_file($fullPath)) {
            return $fullPath;
        }
        return false;
    }

    /**
     * Get last error message
     * 
     * @return string
     */
    public function getLastError(): string
    {
        return $this->lastError;
    }
}
