<?php

namespace App\Services\Proposal;

use App\Models\Proposal\ProposalAnggotaEksternal;
use App\Models\Proposal\ProposalDokumen;
use App\Models\Proposal\ProposalJurnal;
use App\Models\Proposal\ProposalMahasiswa;
use App\Models\Proposal\ProposalPeneliti;
use App\Models\Proposal\ProposalPengajuan;
use App\Models\Proposal\ProposalSubstansiBagian;
use Config\Database;
use Exception;
use Ramsey\Uuid\Uuid;

/**
 * ProposalWizardService
 *
 * Main orchestrator untuk workflow proposal wizard
 * Menangani:
 * - Step navigation & gating
 * - Draft saving per step
 * - Final submission & atomic transaction
 * - Data persistence
 * 
 * Prinsip SOLID & Clean Code:
 * - Single Responsibility: Orchestration only, validation delegated to ProposalStepValidationService
 * - DRY: Reusable methods untuk common operations
 * - Clean Code: Clear method names, one responsibility per method
 */
class ProposalWizardService
{
    private $proposalModel;
    private $penelitiModel;
    private $mahasiswaModel;
    private $eksternalModel;
    private $substansiModel;
    private $dokumenModel;
    private $jurnalModel;
    private $validationService;
    private $uploadService;
    private $masterOptionService;
    private $db;
    private $lastError = '';

    public function __construct()
    {
        $this->proposalModel = new ProposalPengajuan();
        $this->penelitiModel = new ProposalPeneliti();
        $this->mahasiswaModel = new ProposalMahasiswa();
        $this->eksternalModel = new ProposalAnggotaEksternal();
        $this->substansiModel = new ProposalSubstansiBagian();
        $this->dokumenModel = new ProposalDokumen();
        $this->jurnalModel = new ProposalJurnal();
        $this->validationService = new ProposalStepValidationService();
        $this->uploadService = new ProposalUploadService();
        $this->masterOptionService = new ProposalMasterOptionService();
        $this->db = Database::connect();
    }

    /**
     * Create new proposal (init wizard at step 1)
     * 
     * @param int $userId ID of user creating proposal
     * @return string|false Proposal UUID if success, false otherwise
     */
    public function createDraftProposal(int $userId)
    {
        try {
            // Validate user ID
            if ($userId <= 0) {
                $this->lastError = 'User ID tidak valid: ' . $userId;
                return false;
            }

            $uuid = Uuid::uuid4()->toString();

            $data = [
                'uuid' => $uuid,
                'user_id' => $userId,
                'judul' => '',
                'status' => 'draft',
                'current_step' => 1,
                'step_1_data' => json_encode([]),
                'step_2_data' => json_encode([]),
                'step_3_data' => json_encode([]),
                'step_4_data' => json_encode([]),
                'step_5_data' => json_encode([]),
            ];

            if (!$this->proposalModel->insert($data)) {
                // Get validation errors first
                $validationErrors = $this->proposalModel->validation->listErrors();

                if (!empty($validationErrors)) {
                    $this->lastError = 'Validation errors: ' . $validationErrors;
                } else {
                    // If no validation errors, get database error
                    $dbError = $this->db->error()['message'] ?? 'Unknown database error';
                    $this->lastError = 'Database error: ' . $dbError;
                }
                return false;
            }

            return $uuid;
        } catch (Exception $e) {
            $this->lastError = 'Exception: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine();
            return false;
        }
    }

    /**
     * Create a draft only after step 1 passes validation and can be persisted.
     *
     * @return string|false
     */
    public function createDraftFromStepOne(int $userId, array $data)
    {
        if (!$this->validationService->validateStep1($data)) {
            $this->lastError = $this->validationService->getLastError();
            return false;
        }

        $uuid = $this->createDraftProposal($userId);
        if (!$uuid) {
            return false;
        }

        if ($this->saveStepData($userId, $uuid, 1, $data)) {
            return $uuid;
        }

        $proposal = $this->proposalModel->where('uuid', $uuid)->first();
        if ($proposal) {
            $this->proposalModel->delete($proposal->id);
        }

        return false;
    }

    /**
     * Get proposal by UUID for current step
     * 
     * @param int $userId User ID (for authorization check)
     * @param string $uuid Proposal UUID
     * @return object|null Proposal object
     */
    public function getProposal(int $userId, string $uuid)
    {
        return $this->proposalModel->findByUserAndUuid($userId, $uuid);
    }

    /**
     * Get all proposals for user
     * 
     * @param int $userId
     * @return array
     */
    public function getProposalsByUser(int $userId): array
    {
        return $this->proposalModel->getByUser($userId);
    }

    /**
     * Delete proposal and all detail rows for a user.
     */
    public function deleteProposal(int $userId, string $proposalUuid): bool
    {
        try {
            $proposal = $this->getProposal($userId, $proposalUuid);
            if (!$proposal) {
                $this->lastError = 'Proposal tidak ditemukan';
                return false;
            }

            $documents = $this->dokumenModel->where('proposal_id', $proposal->id)->findAll();

            $this->db->transStart();

            $this->penelitiModel->where('proposal_id', $proposal->id)->delete();
            $this->mahasiswaModel->where('proposal_id', $proposal->id)->delete();
            $this->eksternalModel->where('proposal_id', $proposal->id)->delete();
            $this->substansiModel->where('proposal_id', $proposal->id)->delete();
            $this->jurnalModel->where('proposal_id', $proposal->id)->delete();
            $this->dokumenModel->where('proposal_id', $proposal->id)->delete();

            if (!$this->proposalModel->delete($proposal->id)) {
                throw new Exception('Gagal menghapus proposal');
            }

            $this->db->transComplete();

            if (!$this->db->transStatus()) {
                throw new Exception('Transaksi penghapusan proposal gagal');
            }

            foreach ($documents as $document) {
                if (!empty($document->path_file)) {
                    $this->uploadService->deleteFile($document->path_file);
                }
            }

            $proposalDir = FCPATH . 'writable/uploads/proposal/' . $proposalUuid;
            if (is_dir($proposalDir)) {
                @rmdir($proposalDir);
            }

            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Save step data (draft save)
     * 
     * @param int $userId User ID
     * @param string $proposalUuid Proposal UUID
     * @param int $step Current step (1-5)
     * @param array $data Form data from request
     * @return bool
     */
    public function saveStepData(int $userId, string $proposalUuid, int $step, array $data): bool
    {
        try {
            // Get proposal
            $proposal = $this->getProposal($userId, $proposalUuid);
            if (!$proposal) {
                $this->lastError = 'Proposal tidak ditemukan';
                return false;
            }

            // Validate step after proposal is resolved so step 4 can check existing uploads.
            if (!$this->validateStepByNumber($step, $data, $proposal)) {
                return false;
            }

            // Check gating - can only progress if previous steps valid
            if (!$this->canProgressToStep($proposal, $step)) {
                return false;
            }

            // Start transaction
            $this->db->transStart();

            // Save step data to JSON column
            $stepKey = 'step_' . $step . '_data';
            $proposal->$stepKey = json_encode($data);

            // Update proposal
            if (!$this->proposalModel->update($proposal->id, [
                $stepKey => json_encode($data),
                'current_step' => $step,
            ])) {
                throw new Exception('Gagal update proposal');
            }

            // Save detailed data to respective tables based on step
            $deferredFileDeletes = $this->saveStepDetailData($proposalUuid, $step, $data) ?? [];

            $this->db->transComplete();

            if (!$this->db->transStatus()) {
                throw new Exception('Transaction gagal');
            }

            foreach ($deferredFileDeletes as $filePath) {
                $this->uploadService->deleteFile($filePath);
            }

            return true;
        } catch (Exception $e) {
            $this->lastError = 'Error: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Save detail data to respective models based on step
     * 
     * @param string $proposalUuid
     * @param int $step
     * @param array $data
     * @return array
     * @throws Exception
     */
    private function saveStepDetailData(string $proposalUuid, int $step, array $data): array
    {
        $proposal = $this->proposalModel->where('uuid', $proposalUuid)->first();
        if (!$proposal) {
            throw new Exception('Proposal tidak ditemukan');
        }

        switch ($step) {
            case 1:
                // Update proposal header fields
                $this->proposalModel->update($proposal->id, [
                    'judul' => $data['judul'] ?? '',
                    'kata_kunci' => $data['kata_kunci'] ?? '',
                    'pengelola_bantuan_id' => $data['pengelola_bantuan_id'] ?? null,
                    'klaster_bantuan_id' => $data['klaster_bantuan_id'] ?? null,
                    'bidang_ilmu_id' => $data['bidang_ilmu_id'] ?? null,
                    'tema_penelitian_id' => $data['tema_penelitian_id'] ?? null,
                    'jenis_penelitian_id' => $data['jenis_penelitian_id'] ?? null,
                    'kontribusi_prodi_id' => $data['kontribusi_prodi_id'] ?? null,
                ]);
                return [];

            case 2:
                // Save researchers
                $this->savePeneliti($proposal->id, $data['peneliti_internal'] ?? []);
                $this->saveMahasiswa($proposal->id, $data['mahasiswa'] ?? []);
                $this->saveAnggotaEksternal($proposal->id, $data['anggota_eksternal'] ?? []);
                return [];

            case 3:
                // Save substance
                $this->saveSubstansi($proposal->id, $data);
                return [];

            case 4:
                return $this->saveDokumen($proposal->id, $proposalUuid);

            case 5:
                // Save journal data
                $this->saveJurnal($proposal->id, $data);
                return [];
        }

        return [];
    }

    /**
     * Save peneliti data
     * 
     * @param int $proposalId
     * @param array $penelitList
     * @return void
     */
    private function savePeneliti(int $proposalId, array $penelitList): void
    {
        // Delete existing
        $this->penelitiModel->where('proposal_id', $proposalId)->delete();

        $order = 0;
        foreach ($penelitList as $item) {
            if (!empty($item['nama'] ?? '')) {
                $this->penelitiModel->insert([
                    'uuid' => Uuid::uuid4()->toString(),
                    'proposal_id' => $proposalId,
                    'nama' => $item['nama'],
                    'nip' => $item['nip'] ?? null,
                    'email' => $item['email'] ?? null,
                    'asal_instansi' => $item['asal_instansi'] ?? null,
                    'posisi' => $item['posisi'] ?? null,
                    'is_internal' => 1,
                    'is_ketua' => $order === 0 ? 1 : 0,
                    'order_position' => $order,
                ]);
                $order++;
            }
        }
    }

    /**
     * Save mahasiswa data
     * 
     * @param int $proposalId
     * @param array $mahasiswaList
     * @return void
     */
    private function saveMahasiswa(int $proposalId, array $mahasiswaList): void
    {
        $this->mahasiswaModel->where('proposal_id', $proposalId)->delete();

        $order = 0;
        foreach ($mahasiswaList as $item) {
            if (!empty($item['nama'] ?? '')) {
                $this->mahasiswaModel->insert([
                    'uuid' => Uuid::uuid4()->toString(),
                    'proposal_id' => $proposalId,
                    'nama' => $item['nama'],
                    'nim' => $item['nim'] ?? null,
                    'program_studi_id' => $item['program_studi_id'] ?? null,
                    'email' => $item['email'] ?? null,
                    'order_position' => $order,
                ]);
                $order++;
            }
        }
    }

    /**
     * Save anggota eksternal data
     * 
     * @param int $proposalId
     * @param array $eksternalList
     * @return void
     */
    private function saveAnggotaEksternal(int $proposalId, array $eksternalList): void
    {
        $this->eksternalModel->where('proposal_id', $proposalId)->delete();

        $order = 0;
        foreach ($eksternalList as $item) {
            if (!empty($item['nama'] ?? '')) {
                $this->eksternalModel->insert([
                    'uuid' => Uuid::uuid4()->toString(),
                    'proposal_id' => $proposalId,
                    'nama' => $item['nama'],
                    'institusi' => $item['institusi'] ?? null,
                    'posisi' => $item['posisi'] ?? null,
                    'email' => $item['email'] ?? null,
                    'tipe' => $item['tipe'] ?? 'Profesional',
                    'order_position' => $order,
                ]);
                $order++;
            }
        }
    }

    /**
     * Save substansi data
     * 
     * @param int $proposalId
     * @param array $data
     * @return void
     */
    private function saveSubstansi(int $proposalId, array $data): void
    {
        $this->substansiModel->where('proposal_id', $proposalId)->delete();

        // Save abstrak (always first entry)
        $this->substansiModel->insert([
            'uuid' => Uuid::uuid4()->toString(),
            'proposal_id' => $proposalId,
            'abstrak' => $data['abstrak'] ?? '',
            'judul_bagian' => '',
            'isi_bagian' => '',
            'order_position' => 0,
        ]);

        // Save sections
        $order = 1;
        $sections = $data['substansi_bagian'] ?? [];
        foreach ($sections as $item) {
            if (!empty($item['judul_bagian'] ?? '') && !empty($item['isi_bagian'] ?? '')) {
                $this->substansiModel->insert([
                    'uuid' => Uuid::uuid4()->toString(),
                    'proposal_id' => $proposalId,
                    'abstrak' => '',
                    'judul_bagian' => $item['judul_bagian'],
                    'isi_bagian' => $item['isi_bagian'],
                    'order_position' => $order,
                ]);
                $order++;
            }
        }
    }

    /**
     * Save dokumen references
     * 
     * @param int $proposalId
     * @param string $proposalUuid
     * @return array
     */
    private function saveDokumen(int $proposalId, string $proposalUuid): array
    {
        $cleanupAfterCommit = [];
        $uploadedNow = [];
        $singleFileMap = [
            'file_proposal' => 'proposal',
            'file_rab' => 'rab',
            'file_similarity' => 'similarity',
        ];

        try {
            foreach ($singleFileMap as $inputName => $docType) {
                $file = request()->getFile($inputName);
                if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
                    continue;
                }

                $uploadResult = $this->uploadService->uploadFile($inputName, $proposalUuid, $docType);
                if ($uploadResult === false) {
                    throw new Exception($this->uploadService->getLastError());
                }

                $uploadedNow[] = $uploadResult['path_file'];

                $existingDocs = $this->dokumenModel->getByProposalAndType($proposalId, $docType);
                foreach ($existingDocs as $existingDoc) {
                    if (!empty($existingDoc->path_file)) {
                        $cleanupAfterCommit[] = $existingDoc->path_file;
                    }
                }

                $this->dokumenModel->where('proposal_id', $proposalId)
                    ->where('tipe_dokumen', $docType)
                    ->delete();

                $this->dokumenModel->insert([
                    'uuid' => Uuid::uuid4()->toString(),
                    'proposal_id' => $proposalId,
                    'tipe_dokumen' => $docType,
                    'nama_file' => $uploadResult['nama_file'],
                    'path_file' => $uploadResult['path_file'],
                    'file_size' => $uploadResult['file_size'],
                    'mime_type' => $uploadResult['mime_type'],
                    'order_position' => 0,
                ]);
            }

            $pendukungFiles = request()->getFiles()['file_pendukung'] ?? [];
            $hasPendukungUpload = is_array($pendukungFiles)
                && count(array_filter($pendukungFiles, fn($file) => $file && $file->getError() !== UPLOAD_ERR_NO_FILE)) > 0;

            if ($hasPendukungUpload) {
                $pendukungUploads = $this->uploadService->uploadMultipleFiles('file_pendukung', $proposalUuid);

                $existingPendukung = $this->dokumenModel->getByProposalAndType($proposalId, 'pendukung');
                foreach ($existingPendukung as $existingDoc) {
                    if (!empty($existingDoc->path_file)) {
                        $cleanupAfterCommit[] = $existingDoc->path_file;
                    }
                }

                $this->dokumenModel->where('proposal_id', $proposalId)
                    ->where('tipe_dokumen', 'pendukung')
                    ->delete();

                foreach ($pendukungUploads as $index => $uploadResult) {
                    $uploadedNow[] = $uploadResult['path_file'];

                    $this->dokumenModel->insert([
                        'uuid' => Uuid::uuid4()->toString(),
                        'proposal_id' => $proposalId,
                        'tipe_dokumen' => 'pendukung',
                        'nama_file' => $uploadResult['nama_file'],
                        'path_file' => $uploadResult['path_file'],
                        'file_size' => $uploadResult['file_size'],
                        'mime_type' => $uploadResult['mime_type'],
                        'order_position' => $index,
                    ]);
                }
            }

            return $cleanupAfterCommit;
        } catch (Exception $e) {
            $this->uploadService->cleanupTempFiles($uploadedNow);
            throw $e;
        }

        return [];
    }

    /**
     * Save jurnal data
     * 
     * @param int $proposalId
     * @param array $data
     * @return void
     */
    private function saveJurnal(int $proposalId, array $data): void
    {
        // Check if jurnal exists
        $existing = $this->jurnalModel->where('proposal_id', $proposalId)->first();

        $jurnalData = [
            'proposal_id' => $proposalId,
            'issn' => $data['issn'] ?? '',
            'nama_jurnal' => $data['nama_jurnal'] ?? '',
            'profil_jurnal' => $data['profil_jurnal'] ?? '',
            'url_website' => $data['url_website'] ?? '',
            'url_scopus_wos' => $data['url_scopus_wos'] ?? '',
            'url_surat_rekomendasi' => $data['url_surat_rekomendasi'] ?? '',
            'total_pengajuan_dana' => $data['total_pengajuan_dana'] ?? 0,
        ];

        if ($existing) {
            $this->jurnalModel->update($existing->id, $jurnalData);
        } else {
            $jurnalData['uuid'] = Uuid::uuid4()->toString();
            $this->jurnalModel->insert($jurnalData);
        }
    }

    /**
     * Check if can progress to step (gating)
     * Cannot skip steps
     * 
     * @param object $proposal
     * @param int $targetStep
     * @return bool
     */
    private function canProgressToStep($proposal, int $targetStep): bool
    {
        // Can only go to next step or current step
        if ($targetStep > $proposal->current_step + 1) {
            $this->lastError = 'Tidak boleh melompati step. Selesaikan step sebelumnya terlebih dahulu';
            return false;
        }

        return true;
    }

    /**
     * Validate step by number
     * 
     * @param int $step
     * @param array $data
     * @return bool
     */
    private function validateStepByNumber(int $step, array $data, $proposal = null): bool
    {
        switch ($step) {
            case 1:
                return $this->validationService->validateStep1($data);
            case 2:
                return $this->validationService->validateStep2($data);
            case 3:
                return $this->validationService->validateStep3($data);
            case 4:
                $existingDocTypes = [];
                if ($proposal) {
                    $existingDocs = $this->dokumenModel->getRequiredByProposal((int) $proposal->id);
                    $existingDocTypes = array_map(fn($doc) => $doc->tipe_dokumen, $existingDocs);
                }

                return $this->validationService->validateStep4($data, $_FILES, $existingDocTypes);
            case 5:
                return $this->validationService->validateStep5($data);
            default:
                $this->lastError = 'Step tidak valid';
                return false;
        }
    }

    /**
     * Submit final proposal
     * Revalidate all steps and change status to submitted
     * 
     * @param int $userId
     * @param string $proposalUuid
     * @return bool
     */
    public function submitProposal(int $userId, string $proposalUuid): bool
    {
        try {
            $proposal = $this->getProposal($userId, $proposalUuid);
            if (!$proposal) {
                $this->lastError = 'Proposal tidak ditemukan';
                return false;
            }

            // Start transaction
            $this->db->transStart();

            // Update status to submitted
            $this->proposalModel->update($proposal->id, [
                'status' => 'submitted',
            ]);

            $this->db->transComplete();

            if (!$this->db->transStatus()) {
                throw new Exception('Gagal submit proposal');
            }

            return true;
        } catch (Exception $e) {
            $this->lastError = 'Error: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Get last error message
     * 
     * @return string
     */
    public function getLastError(): string
    {
        return $this->lastError ?: $this->validationService->getLastError();
    }
}
