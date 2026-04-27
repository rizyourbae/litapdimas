<?php

namespace App\Services\Proposal;

/**
 * ProposalStepValidationService
 *
 * Service untuk validasi per step wizard
 * Setiap step memiliki rule validasi yang berbeda
 * 
 * Prinsip: 
 * - Single Responsibility: Hanya handle validasi
 * - DRY: Reusable validation rules
 * - Clean Code: Setiap method fokus satu step
 */
class ProposalStepValidationService
{
    private $lastError = '';

    /**
     * Validate Step 1: Pernyataan Peneliti
     * 
     * @param array $data Form data dari request
     * @return bool
     */
    public function validateStep1(array $data): bool
    {
        $errors = [];

        // Judul wajib
        if (empty(trim($data['judul'] ?? ''))) {
            $errors[] = 'Judul usulan wajib diisi';
        } elseif (strlen($data['judul']) > 255) {
            $errors[] = 'Judul usulan maksimal 255 karakter';
        }

        // Kata kunci - minimal 3
        if (!$this->validateKeywords($data['kata_kunci'] ?? '')) {
            $errors[] = 'Kata kunci harus minimal 3 kata, dipisah koma';
        }

        // Pengelola bantuan wajib
        if (empty($data['pengelola_bantuan_id'] ?? '')) {
            $errors[] = 'Pengelola bantuan wajib dipilih';
        }

        // Klaster bantuan wajib
        if (empty($data['klaster_bantuan_id'] ?? '')) {
            $errors[] = 'Klaster bantuan wajib dipilih';
        }

        // Bidang ilmu wajib
        if (empty($data['bidang_ilmu_id'] ?? '')) {
            $errors[] = 'Bidang ilmu wajib dipilih';
        }

        // Tema penelitian wajib
        if (empty($data['tema_penelitian_id'] ?? '')) {
            $errors[] = 'Tema penelitian wajib dipilih';
        }

        // Jenis penelitian wajib
        if (empty($data['jenis_penelitian_id'] ?? '')) {
            $errors[] = 'Jenis penelitian wajib dipilih';
        }

        // Kontribusi prodi wajib
        if (empty($data['kontribusi_prodi_id'] ?? '')) {
            $errors[] = 'Kontribusi prodi wajib dipilih';
        }

        // Pernyataan wajib harus lengkap (semua checked)
        if (empty($data['statement_1']) || empty($data['statement_2']) || empty($data['statement_3'])) {
            $errors[] = 'Semua pernyataan wajib harus disetujui';
        }

        if (count($errors) > 0) {
            $this->lastError = implode('. ', $errors);
            return false;
        }

        return true;
    }

    /**
     * Validate Step 2: Data Peneliti
     * 
     * @param array $data Form data dari request
     * @return bool
     */
    public function validateStep2(array $data): bool
    {
        $errors = [];

        // Peneliti internal - minimal 1 harus ada
        $penelitiInternal = $data['peneliti_internal'] ?? [];
        if (empty($penelitiInternal) || count(array_filter($penelitiInternal, fn($p) => !empty($p['nama'] ?? ''))) === 0) {
            $errors[] = 'Minimal 1 peneliti internal harus ditambahkan';
        }

        // Validasi setiap peneliti internal yang ada
        foreach ($penelitiInternal as $index => $peneliti) {
            if (!empty($peneliti['nama'] ?? '')) {
                if (strlen($peneliti['nama']) > 255) {
                    $errors[] = "Nama peneliti internal #{$index} maksimal 255 karakter";
                }
                if (!empty($peneliti['email'] ?? '') && !filter_var($peneliti['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Email peneliti internal #{$index} tidak valid";
                }
            }
        }

        // Validasi mahasiswa (optional but if filled must be valid)
        $mahasiswa = $data['mahasiswa'] ?? [];
        foreach ($mahasiswa as $index => $m) {
            if (!empty($m['nama'] ?? '')) {
                if (strlen($m['nama']) > 255) {
                    $errors[] = "Nama mahasiswa #{$index} maksimal 255 karakter";
                }
                if (!empty($m['email'] ?? '') && !filter_var($m['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Email mahasiswa #{$index} tidak valid";
                }
            }
        }

        // Validasi anggota eksternal (optional but if filled must be valid)
        $eksternal = $data['anggota_eksternal'] ?? [];
        foreach ($eksternal as $index => $e) {
            if (!empty($e['nama'] ?? '')) {
                if (strlen($e['nama']) > 255) {
                    $errors[] = "Nama anggota eksternal #{$index} maksimal 255 karakter";
                }
                if (!empty($e['email'] ?? '') && !filter_var($e['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Email anggota eksternal #{$index} tidak valid";
                }
            }
        }

        if (count($errors) > 0) {
            $this->lastError = implode('. ', $errors);
            return false;
        }

        return true;
    }

    /**
     * Validate Step 3: Substansi Usulan
     * 
     * @param array $data Form data dari request
     * @return bool
     */
    public function validateStep3(array $data): bool
    {
        $errors = [];

        // Abstrak wajib
        $abstrak = $data['abstrak'] ?? '';
        if (empty(strip_tags($abstrak))) {
            $errors[] = 'Abstrak wajib diisi';
        } elseif (strlen(strip_tags($abstrak)) < 50) {
            $errors[] = 'Abstrak minimal 50 karakter';
        }

        // Minimal 1 substansi bagian
        $sections = $data['substansi_bagian'] ?? [];
        $filledSections = array_filter($sections, function ($s) {
            return !empty($s['judul_bagian'] ?? '') && !empty(strip_tags($s['isi_bagian'] ?? ''));
        });

        if (count($filledSections) === 0) {
            $errors[] = 'Minimal 1 bagian substansi dengan judul dan isi wajib ditambahkan';
        }

        // Validasi setiap section
        foreach ($sections as $index => $section) {
            if (!empty($section['judul_bagian'] ?? '')) {
                if (strlen($section['judul_bagian']) > 255) {
                    $errors[] = "Judul bagian #{$index} maksimal 255 karakter";
                }
                if (empty(strip_tags($section['isi_bagian'] ?? ''))) {
                    $errors[] = "Isi bagian #{$index} wajib diisi";
                }
            }
        }

        if (count($errors) > 0) {
            $this->lastError = implode('. ', $errors);
            return false;
        }

        return true;
    }

    /**
     * Validate Step 4: Unggah Berkas
     * 
     * Note: Actual file validation (MIME, size) dilakukan di ProposalUploadService
     * Di sini hanya cek keberadaan file
     * 
     * @param array $data Form data
     * @param array $uploadedFiles Array dari $_FILES
     * @return bool
     */
    public function validateStep4(array $data, array $uploadedFiles, array $existingDocTypes = []): bool
    {
        $errors = [];

        // 3 dokumen wajib: proposal, rab, similarity
        $requiredTypes = ['file_proposal', 'file_rab', 'file_similarity'];
        foreach ($requiredTypes as $type) {
            $docType = str_replace('file_', '', $type);
            $hasExistingDoc = in_array($docType, $existingDocTypes, true);
            $hasNewUpload = !empty($uploadedFiles[$type] ?? null)
                && !empty($uploadedFiles[$type]['name'] ?? '');

            if (
                !$hasExistingDoc && !$hasNewUpload
            ) {
                $errors[] = 'File ' . str_replace('file_', '', $type) . ' wajib diunggah';
            }
        }

        if (count($errors) > 0) {
            $this->lastError = implode('. ', $errors);
            return false;
        }

        return true;
    }

    /**
     * Validate Step 5: Data Jurnal
     * 
     * @param array $data Form data dari request
     * @return bool
     */
    public function validateStep5(array $data): bool
    {
        $errors = [];

        // ISSN wajib
        if (empty(trim($data['issn'] ?? ''))) {
            $errors[] = 'ISSN jurnal wajib diisi';
        } elseif (strlen($data['issn']) > 50) {
            $errors[] = 'ISSN maksimal 50 karakter';
        }

        // Nama jurnal wajib
        if (empty(trim($data['nama_jurnal'] ?? ''))) {
            $errors[] = 'Nama jurnal wajib diisi';
        } elseif (strlen($data['nama_jurnal']) > 255) {
            $errors[] = 'Nama jurnal maksimal 255 karakter';
        }

        // Profil jurnal wajib (rich text)
        if (empty(strip_tags($data['profil_jurnal'] ?? ''))) {
            $errors[] = 'Profil jurnal wajib diisi';
        }

        // URL website wajib dan valid
        if (empty(trim($data['url_website'] ?? ''))) {
            $errors[] = 'URL website jurnal wajib diisi';
        } elseif (!filter_var($data['url_website'], FILTER_VALIDATE_URL)) {
            $errors[] = 'URL website jurnal tidak valid';
        }

        // URL Scopus/WoS wajib dan valid
        if (empty(trim($data['url_scopus_wos'] ?? ''))) {
            $errors[] = 'URL Scopus/WoS wajib diisi';
        } elseif (!filter_var($data['url_scopus_wos'], FILTER_VALIDATE_URL)) {
            $errors[] = 'URL Scopus/WoS tidak valid';
        }

        // URL surat rekomendasi wajib dan valid
        if (empty(trim($data['url_surat_rekomendasi'] ?? ''))) {
            $errors[] = 'URL surat rekomendasi wajib diisi';
        } elseif (!filter_var($data['url_surat_rekomendasi'], FILTER_VALIDATE_URL)) {
            $errors[] = 'URL surat rekomendasi tidak valid';
        }

        // Dana wajib dan <= 100000000
        if (empty($data['total_pengajuan_dana'] ?? '')) {
            $errors[] = 'Total pengajuan dana wajib diisi';
        } elseif (!is_numeric($data['total_pengajuan_dana'])) {
            $errors[] = 'Total pengajuan dana harus berupa angka';
        } elseif ((int)$data['total_pengajuan_dana'] > 100000000) {
            $errors[] = 'Total pengajuan dana maksimal Rp 100.000.000';
        } elseif ((int)$data['total_pengajuan_dana'] < 0) {
            $errors[] = 'Total pengajuan dana tidak boleh negatif';
        }

        if (count($errors) > 0) {
            $this->lastError = implode('. ', $errors);
            return false;
        }

        return true;
    }

    /**
     * Validate keywords - harus minimal 3 kata dipisah koma
     * 
     * @param string $keywords
     * @return bool
     */
    private function validateKeywords(string $keywords): bool
    {
        $keywordArray = array_map('trim', explode(',', $keywords));
        // Filter out empty strings
        $keywordArray = array_filter($keywordArray, fn($k) => !empty($k));
        return count($keywordArray) >= 3;
    }

    /**
     * Get last validation error
     * 
     * @return string
     */
    public function getLastError(): string
    {
        return $this->lastError;
    }
}
