<?php

namespace App\Controllers\Dosen\Proposal;

use App\Controllers\BaseController;
use App\Models\Proposal\ProposalOutput;
use App\Models\Proposal\ProposalPengajuan;
use App\Libraries\Storage;
use Exception;

class OutputController extends BaseController
{
    protected ProposalOutput $outputModel;
    protected ProposalPengajuan $proposalModel;
    protected Storage $storage;

    public function __construct()
    {
        $this->outputModel = new ProposalOutput();
        $this->proposalModel = new ProposalPengajuan();
        $this->storage = new Storage();
    }

    /**
     * Store or update output file
     */
    public function upload(string $proposalUuid)
    {
        $userId = (int) (user()['id'] ?? 0);
        $proposal = $this->proposalModel->where('uuid', $proposalUuid)->first();

        if (!$proposal || (int) $proposal->user_id !== $userId) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan atau Anda tidak memiliki akses.');
        }

        if ($proposal->status !== 'approved') {
            return redirect()->back()->with('error', 'Hanya proposal yang disetujui yang dapat mengunggah luaran.');
        }

        $rules = [
            'kategori' => 'required|in_list[HKI,Laporan Bantuan Lengkap,Draft Artikel,Dummy Buku,Dokumen Kemanfaatan,Executive Summary]',
            'berkas'   => 'uploaded[berkas]|max_size[berkas,10240]|ext_in[berkas,pdf]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }

        $kategori = $this->request->getPost('kategori');
        $file = $this->request->getFile('berkas');

        // Check if already exists to replace
        $existing = $this->outputModel->getByProposalAndKategori($proposal->id, $kategori);

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = pathinfo($file->getRandomName(), PATHINFO_FILENAME);
            
            try {
                $upload = $this->storage->putObject(
                    $_FILES['berkas'],
                    'output',
                    $fileName,
                    Storage::SHR_PUBLIC,
                );
            } catch (\Exception $e) {
                $upload = ['status' => 0, 'message' => $e->getMessage()];
            }

            if (!isset($upload['status']) || $upload['status'] != 1) {
                $errorMsg = $upload['message'] ?? 'Gagal mengunggah berkas.';
                return redirect()->back()->with('error', $errorMsg);
            }

            $objectName = $upload['data']['object_name'] ?? null;
            if (!$objectName) {
                return redirect()->back()->with('error', 'Gagal mendapatkan nama file dari penyimpanan.');
            }

            $data = [
                'uuid'              => bin2hex(random_bytes(16)),
                'proposal_id'       => $proposal->id,
                'kategori'          => $kategori,
                'file_path'         => $objectName,
                'original_filename' => $file->getClientName(),
            ];

            try {
                if ($existing) {
                    // Delete old file from storage
                    if ($existing && !empty($existing->file_path)) {
                        $this->storage->deleteObject($existing->file_path);
                    }
                    $this->outputModel->update($existing->id, $data);
                } else {
                    $this->outputModel->insert($data);
                }

                return redirect()->back()->with('success', "Luaran '{$kategori}' berhasil diunggah.");
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah luaran: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'File tidak valid.');
    }
}
