<?php

namespace App\Services\Proposal;

use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Files\UploadedFile;

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
    // Constants
    private const UPLOAD_DIR = 'writable/uploads/proposal';
    private const MAX_FILE_SIZE = 2097152; // 2MB in bytes
    private const ALLOWED_MIME_TYPES = ['application/pdf'];
    private const ALLOWED_EXTENSIONS = ['pdf'];

    private $lastError = '';

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

            $mimeType = $file->getMimeType();

            // Validasi file size
            if (!$this->validateFileSize($file)) {
                return false;
            }

            // Create proposal upload directory
            $uploadPath = FCPATH . self::UPLOAD_DIR . '/' . $proposalUuid;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $newFilename = $docType . '_' . time() . '_' . bin2hex(random_bytes(8)) . '.pdf';
            $destination = $uploadPath . '/' . $newFilename;

            if (!move_uploaded_file($file->getTempName(), $destination)) {
                $this->lastError = 'Gagal memindahkan file ke folder tujuan';
                return false;
            }

            return [
                'nama_file' => $newFilename,
                'path_file' => self::UPLOAD_DIR . '/' . $proposalUuid . '/' . $newFilename,
                'file_size' => filesize($destination),
                'mime_type' => $mimeType,
            ];
        } catch (\Exception $e) {
            $this->lastError = 'Error saat upload: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Upload multiple files (untuk dokumen pendukung)
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

            if (!empty($name) && $error === UPLOAD_ERR_OK) {
                $file = new \CodeIgniter\Files\File($tmpName);
                if ($this->validateMimeType($file, $name) && $this->validateFileSize($file)) {
                    $mimeType = $file->getMimeType();
                    $newFilename = 'pendukung_' . $i . '_' . time() . '_' . bin2hex(random_bytes(8)) . '.pdf';
                    $uploadPath = FCPATH . self::UPLOAD_DIR . '/' . $proposalUuid;
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    $destination = $uploadPath . '/' . $newFilename;
                    if (move_uploaded_file($tmpName, $destination)) {
                        $results[] = [
                            'nama_file' => $newFilename,
                            'path_file' => self::UPLOAD_DIR . '/' . $proposalUuid . '/' . $newFilename,
                            'file_size' => filesize($destination),
                            'mime_type' => $mimeType,
                        ];
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
            $fullPath = FCPATH . $filePath;
            if (file_exists($fullPath)) {
                unlink($fullPath);
                return true;
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
        $fullPath = FCPATH . $filePath;
        if (file_exists($fullPath)) {
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
