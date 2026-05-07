<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Proposal\ProposalPengajuan as ProposalModel;
use App\Models\Proposal\ProposalReviewerAssignment;
use App\Services\AuditLogService;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Storage;

class SecureFileController extends BaseController
{

    protected $storage;

    function __construct()
    {
        $this->storage = new Storage();
    }

    /**
     * View proposal document
     */
    public function proposalDocument(string $uuid)
    {
        $model = new \App\Models\Proposal\ProposalDokumen();
        $record = $model->where('uuid', $uuid)->first();
        if (!$record) return $this->response->setStatusCode(404)->setBody('<h1>404</h1><p>Rekor dokumen tidak ditemukan.</p>');

        if (!$this->canAccessProposal($record->proposal_id)) {
            return $this->response->setStatusCode(403)
                ->setBody('<h1>403 Forbidden</h1><p>Anda tidak memiliki akses ke dokumen proposal ini.</p>');
        }

        $this->auditLog->log('VIEW_FILE', 'proposal', $uuid, 'Melihat dokumen proposal: ' . $record->nama_file);

        return $this->serveFile($record->path_file, $record->nama_file, null, 'proposal');
    }

    /**
     * View logbook document
     */
    public function logbookDocument(string $uuid)
    {
        $model = new \App\Models\Proposal\ProposalLogbook();
        $record = $model->where('uuid', $uuid)->first();
        if (!$record || !$record->berkas_path) return $this->response->setStatusCode(404)->setBody('Logbook file not found.');
        if (!$this->canAccessProposal($record->proposal_id)) return $this->response->setStatusCode(403)->setBody('Access Denied.');
        
        $this->auditLog->log('VIEW_FILE', 'logbook', $uuid, 'Melihat berkas logbook');

        return $this->serveFile($record->berkas_path, basename($record->berkas_path));
    }

    /**
     * View output document
     */
    public function outputDocument(string $uuid)
    {
        $model = new \App\Models\Proposal\ProposalOutput();
        $record = $model->where('uuid', $uuid)->first();
        if (!$record || !$record->file_path) return $this->response->setStatusCode(404)->setBody('Output file not found.');
        if (!$this->canAccessProposal($record->proposal_id)) return $this->response->setStatusCode(403)->setBody('Access Denied.');
        
        $this->auditLog->log('VIEW_FILE', 'output', $uuid, 'Melihat berkas luaran: ' . $record->kategori);

        return $this->serveFile($record->file_path, $record->original_filename);
    }

    /**
     * View report document
     */
    public function reportDocument(string $uuid)
    {
        $model = new \App\Models\Proposal\ProposalReport();
        $record = $model->where('uuid', $uuid)->first();
        if (!$record || !$record->file_path) return $this->response->setStatusCode(404)->setBody('Report file not found.');
        if (!$this->canAccessProposal($record->proposal_id)) return $this->response->setStatusCode(403)->setBody('Access Denied.');
        
        $this->auditLog->log('VIEW_FILE', 'report', $uuid, 'Melihat berkas laporan: ' . $record->kategori);

        return $this->serveFile($record->file_path, $record->original_filename);
    }

    /**
     * Display in-browser PDF viewer
     */
    public function viewer(string $type, string $uuid)
    {
        $fileUrl = site_url("files/{$type}/{$uuid}");
        $title = "Document Viewer";

        // Try to get a better title based on type
        switch ($type) {
            case 'proposal':
                $model = new \App\Models\Proposal\ProposalDokumen();
                $rec = $model->where('uuid', $uuid)->first();
                if ($rec) $title = $rec->nama_file;
                break;
            case 'logbook':
                $model = new \App\Models\Proposal\ProposalLogbook();
                $rec = $model->where('uuid', $uuid)->first();
                if ($rec) $title = "Logbook: " . $rec->nama_kegiatan;
                break;
            case 'output':
                $model = new \App\Models\Proposal\ProposalOutput();
                $rec = $model->where('uuid', $uuid)->first();
                if ($rec) $title = "Output: " . $rec->kategori;
                break;
            case 'report':
                $model = new \App\Models\Proposal\ProposalReport();
                $rec = $model->where('uuid', $uuid)->first();
                if ($rec) $title = "Report: " . $rec->kategori;
                break;
        }

        return view('viewer/pdf_viewer', [
            'fileUrl' => $fileUrl,
            'title'   => $title
        ]);
    }

    /**
     * Serve other uploads (Kelengkapan, Riwayat, Profile, etc.)
     * 
     * @param string $folder Folder name
     * @param string $filename Filename
     */
    public function generalUpload(string $folder, string $filename): ResponseInterface
    {
        // Simple logic for now: must be logged in
        if (!service('auth')->isLoggedIn()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $path = 'uploads/' . $folder . '/' . $filename;
        return $this->serveFile($path, $filename);
    }

    /**
     * Check if current user can access a proposal
     */
    private function canAccessProposal(int $proposalId): bool
    {
        $userId = (int) (user()['id'] ?? 0);
        if ($userId <= 0) return false;

        // Admin always has access
        if (service('auth')->hasRole('admin')) return true;

        $proposalModel = new ProposalModel();
        $proposal = $proposalModel->find($proposalId);
        if (!$proposal) return false;

        // Owner has access
        if ((int) $proposal->user_id === $userId) return true;

        // Assigned reviewer has access
        $assignmentModel = new ProposalReviewerAssignment();
        $isAssigned = $assignmentModel->where('proposal_id', $proposalId)
                                      ->where('reviewer_id', $userId)
                                      ->countAllResults() > 0;
        
        if ($isAssigned) return true;

        return false;
    }

    /**
     * Internal helper to serve file
     * Access control sudah dilakukan di method pemanggil
     */
    private function serveFile(string $relativePath, string $originalName, ?string $mimeType = null, string $mod = null): ResponseInterface
    {
        // Clean relative path from redundant prefixes
        $cleanPath = ltrim($relativePath, '/');

        // Prevent serving directories directly
        if (basename($cleanPath) === 'outputs' || basename($cleanPath) === 'logbooks' || basename($cleanPath) === 'reports') {
             return $this->response->setStatusCode(404)
                ->setBody('<h1>404 Not Found</h1><p>File tidak ditemukan atau path tidak valid.</p>');
        }

        // 1. Cek apakah file ada di lokal (untuk mode development)
        $fullPath = WRITEPATH . $cleanPath;
        if (is_file($fullPath)) {
            return $this->response->download($fullPath, null)->inline();
        }

        // 2. Jika tidak ada di lokal, coba ambil dari Storage API (mode production)
        try {
            $fileUrl = $this->storage->getObjectUrl($mod, $originalName, '', Storage::SHR_PUBLIC);
            return redirect()->to($fileUrl);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(404)
                ->setBody('<h1>404 Not Found</h1><p>File tidak ditemukan atau tidak dapat diakses.</p>');
        }
    }
}
